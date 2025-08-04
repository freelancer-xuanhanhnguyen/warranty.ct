<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $latestStatuses = DB::table('service_statuses as ss1')
            ->select('ss1.service_id', 'ss1.code')
            ->whereRaw('ss1.id = (SELECT MAX(ss2.id) FROM service_statuses ss2 WHERE ss2.service_id = ss1.service_id)');

        $query = DB::table('services')
            ->select('users.name', 'services.repairman_id', 'users.email')
            ->join('users', 'users.id', '=', 'services.repairman_id')
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->where('users.role', '=', User::ROLE_REPAIRMAN);

        $query = $query->groupBy('services.repairman_id', 'users.name', 'users.email')
            ->selectRaw("SUM(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) as total_under_warranty", [ServiceStatus::STATUS_UNDER_WARRANTY])
            ->selectRaw("SUM(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) as total_under_repair", [ServiceStatus::STATUS_UNDER_REPAIR])
            ->selectRaw("
        SUM(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END)
      + SUM(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END)
      AS total_services
    ", [
                ServiceStatus::STATUS_UNDER_WARRANTY,
                ServiceStatus::STATUS_UNDER_REPAIR,
            ])
            ->havingRaw('total_services > 0')
            ->orderBy('total_services', 'desc');


        $reportRepairman = $query->limit(10)->get();

        $reportServices = DB::table('services')
            ->select('services.id', 'latest_status.code')
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->groupBy('services.id', 'latest_status.code')
            ->selectRaw("Count(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) as total_under_warranty", [ServiceStatus::STATUS_UNDER_WARRANTY])
            ->selectRaw("Count(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) as total_under_repair", [ServiceStatus::STATUS_UNDER_REPAIR])
            ->selectRaw("
        Count(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END)
      + Count(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END)
      AS total_services
    ", [
                ServiceStatus::STATUS_UNDER_WARRANTY,
                ServiceStatus::STATUS_UNDER_REPAIR,
            ])
            ->first();

        $now = Carbon::now();
        $today = $now->toDateString();

        $startOfWeek = now()->startOfWeek()->subWeek(); // Thứ 2
        $endOfWeek = now()->endOfWeek()->subWeek();     // Chủ nhật

        // 3. Main query: join vào services, filter theo created_at, group by ngày
        $rawStats = DB::table('services')
            ->selectRaw('DATE(services.created_at) as date')
            ->selectRaw('count(*) as created_total')
            ->selectRaw('sum(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) as completed_total', [
                ServiceStatus::STATUS_COMPLETED,
            ])
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->whereDate('services.created_at', '>=', $startOfWeek->toDateString())
            ->whereDate('services.created_at', '<=', $endOfWeek->toDateString())
            ->groupBy(DB::raw('DATE(services.created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->toArray();

        $stats = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i)->toDateString();
            $stats['created'][] = (int)($rawStats[$day]?->created_total ?? 0);
            $stats['completed'][] = (int)($rawStats[$day]?->completed_total ?? 0);
        }

        $reportCustomer = Customer::selectRaw('count(*) as total')
            ->selectRaw('SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today_total', [$today])
            ->first();

        $reportService = Service::selectRaw('count(*) as total')
            ->selectRaw('SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today_total', [$today])
            ->first();

        $reportUsers = DB::table('users')
            ->select('role')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today_total', [$today])
            ->groupBy('role')
            ->whereIn('role', [User::ROLE_CSKH, User::ROLE_REPAIRMAN])
            ->get()
            ->keyBy('role')
            ->toArray();

        return view('dashboard', compact('reportRepairman', 'reportServices', 'reportUsers', 'reportCustomer', 'reportService', 'stats'));
    }
}
