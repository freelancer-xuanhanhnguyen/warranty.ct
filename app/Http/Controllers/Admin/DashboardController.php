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
            ->select('ss1.service_id', 'ss1.code', 'ss1.created_at')
            ->whereRaw('ss1.id = (SELECT MAX(ss2.id) FROM service_statuses ss2 WHERE ss2.service_id = ss1.service_id)');

        $reportRepairman = DB::table('services')
            ->select('users.name', 'services.repairman_id', 'users.email')
            ->join('users', 'users.id', '=', 'services.repairman_id')
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->where('users.status', 1)
            ->where('users.role', '=', User::ROLE_REPAIRMAN)
            ->groupBy('services.repairman_id', 'users.name', 'users.email')
            ->selectRaw("
        SUM(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) AS total_under_warranty,
        SUM(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) AS total_under_repair,
        SUM(CASE WHEN latest_status.code IN (?, ?) THEN 1 ELSE 0 END) AS total_services
    ", [
                ServiceStatus::STATUS_UNDER_WARRANTY,
                ServiceStatus::STATUS_UNDER_REPAIR,
                ServiceStatus::STATUS_UNDER_WARRANTY,
                ServiceStatus::STATUS_UNDER_REPAIR,
            ])
            ->havingRaw('total_services > 0')
            ->orderBy('total_services', 'desc')
            ->limit(10)
            ->get();

        $reportServices = DB::table('services')
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->selectRaw("
        SUM(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) AS total_under_warranty,
        SUM(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) AS total_under_repair,
        SUM(CASE WHEN latest_status.code IN (?, ?) THEN 1 ELSE 0 END) AS total_services
    ", [
                ServiceStatus::STATUS_UNDER_WARRANTY,
                ServiceStatus::STATUS_UNDER_REPAIR,
                ServiceStatus::STATUS_UNDER_WARRANTY,
                ServiceStatus::STATUS_UNDER_REPAIR,
            ])
            ->first();

        $now = Carbon::now();
        $today = $now->toDateString();

        $startOfLastWeek = $now->clone()->subWeek()->startOfWeek();
        $endOfLastWeek = $now->clone()->subWeek()->endOfWeek();

        $usersLastWeek = User::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();
        $customersLastWeek = Customer::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();
        $servicesLastWeek = Service::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();
        $completedServicesLastWeek = Service::selectRaw("COUNT(*) AS count, SUM(services.fee_total) AS total")
            ->whereBetween('latest_status.created_at', [$startOfLastWeek, $endOfLastWeek])
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->where('latest_status.code', ServiceStatus::STATUS_COMPLETED)
            ->first();

        $startOfWeek = $now->clone()->startOfWeek();
        $endOfWeek = $now->clone()->endOfWeek();

        $usersThisWeek = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $customersThisWeek = Customer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $servicesThisWeek = Service::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $completedServicesThisWeek = Service::selectRaw("COUNT(*) AS count, SUM(services.fee_total) AS total")
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->whereBetween('latest_status.created_at', [$startOfWeek, $endOfWeek])
            ->where('latest_status.code', ServiceStatus::STATUS_COMPLETED)
            ->first();

        $growthUser = $this->calculatorGrowth($usersThisWeek, $usersLastWeek);
        $growthCustomer = $this->calculatorGrowth($customersThisWeek, $customersLastWeek);
        $growthService = $this->calculatorGrowth($servicesThisWeek, $servicesLastWeek);
        $growthCompletedService = $this->calculatorGrowth($completedServicesThisWeek->count, $completedServicesLastWeek->count);
        $growthTotalService = $this->calculatorGrowth($completedServicesThisWeek->total, $completedServicesLastWeek->total);

        // 3. Main query: join vào  services, filter theo created_at, group by ngày
        $rawCreatedStats = DB::table('services')
            ->selectRaw('DATE(services.created_at) as date')
            ->selectRaw('count(*) as created_total')
            ->whereDate('services.created_at', '>=', $startOfLastWeek->toDateString())
            ->whereDate('services.created_at', '<=', $endOfWeek->toDateString())
            ->groupBy(DB::raw('DATE(services.created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->toArray();

        $rawCompletedStats = DB::table('services')
            ->selectRaw('DATE(latest_status.created_at) as date')
            ->selectRaw('count(*) as completed_total')
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->whereDate('latest_status.created_at', '>=', $startOfLastWeek->toDateString())
            ->whereDate('latest_status.created_at', '<=', $endOfWeek->toDateString())
            ->where('latest_status.code', ServiceStatus::STATUS_COMPLETED)
            ->groupBy(DB::raw('DATE(latest_status.created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->toArray();

        $rawTotalStats = DB::table('services')
            ->selectRaw('DATE(latest_status.created_at) as date')
            ->selectRaw('sum(fee_total) as total')
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->whereDate('latest_status.created_at', '>=', $startOfLastWeek->toDateString())
            ->whereDate('latest_status.created_at', '<=', $endOfWeek->toDateString())
            ->where('latest_status.code', ServiceStatus::STATUS_COMPLETED)
            ->groupBy(DB::raw('DATE(latest_status.created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->toArray();

        $rawNewUsersStats = DB::table('users')
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('count(*) as total')
            ->whereDate('created_at', '>=', $startOfLastWeek->toDateString())
            ->whereDate('created_at', '<=', $endOfWeek->toDateString())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->toArray();

        $rawNewCustomersStats = DB::table('customers')
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('count(*) as total')
            ->whereDate('created_at', '>=', $startOfLastWeek->toDateString())
            ->whereDate('created_at', '<=', $endOfWeek->toDateString())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->toArray();

        $stats = [];
        for ($i = 0; $i < 14; $i++) {
            $day = $startOfLastWeek->copy()->addDays($i)->toDateString();
            $created = (int)($rawCreatedStats[$day]?->created_total ?? 0);
            $completed = (int)($rawCompletedStats[$day]?->completed_total ?? 0);

            $stats['created'][$i < 7 ? 'last' : 'today'][] = $created;
            $stats['completed'][$i < 7 ? 'last' : 'today'][] = $completed;

            $stats['users'][] = (int)($rawNewUsersStats[$day]?->total ?? 0);
            $stats['customers'][] = (int)($rawNewCustomersStats[$day]?->total ?? 0);
            $stats['total'][] = (int)($rawTotalStats[$day]?->total ?? 0);
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

        return view('dashboard', compact('reportRepairman', 'reportServices', 'reportUsers', 'reportCustomer', 'reportService', 'stats',
            'usersThisWeek', 'growthUser', 'customersThisWeek', 'growthCustomer', 'servicesThisWeek', 'growthService',
            'growthCompletedService', 'growthTotalService', 'completedServicesThisWeek'));
    }

    private function calculatorGrowth($new, $old): float|int
    {
        if ($old == 0) {
            $growth = $new > 0 ? 100 : 0;
        } else {
            $growth = (($new - $old) / $old) * 100;
        }

        return (float) bcdiv($growth, 1, 2);
    }
}
