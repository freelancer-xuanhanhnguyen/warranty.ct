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

        $startOfLastWeek = $now->clone()->subWeek()->startOfWeek();
        $endOfLastWeek = $now->clone()->subWeek()->endOfWeek();

        $usersLastWeek = User::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();
        $customersLastWeek = Customer::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();
        $servicesLastWeek = Service::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();
        $completedServicesLastWeek = Service::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->where('latest_status.code', ServiceStatus::STATUS_COMPLETED)
            ->count();

        $startOfWeek = $now->clone()->startOfWeek();
        $endOfWeek = $now->clone()->endOfWeek();

        $usersThisWeek = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $customersThisWeek = Customer::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $servicesThisWeek = Service::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $completedServicesThisWeek = Service::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->where('latest_status.code', ServiceStatus::STATUS_COMPLETED)
            ->count();

        $growthUser = $this->calculatorGrowth($usersThisWeek, $usersLastWeek);
        $growthCustomer = $this->calculatorGrowth($customersThisWeek, $customersLastWeek);
        $growthService = $this->calculatorGrowth($servicesThisWeek, $servicesLastWeek);
        $growthCompletedService = $this->calculatorGrowth($completedServicesThisWeek, $completedServicesLastWeek);

        // 3. Main query: join vào  services, filter theo created_at, group by ngày
        $rawStats = DB::table('services')
            ->selectRaw('DATE(services.created_at) as date')
            ->selectRaw('count(*) as created_total')
            ->selectRaw('sum(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) as completed_total', [
                ServiceStatus::STATUS_COMPLETED,
            ])
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->whereDate('services.created_at', '>=', $startOfLastWeek->toDateString())
            ->whereDate('services.created_at', '<=', $endOfWeek->toDateString())
            ->groupBy(DB::raw('DATE(services.created_at)'))
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
            $created = (int)($rawStats[$day]?->created_total ?? 0);
            $completed = (int)($rawStats[$day]?->completed_total ?? 0);

            $stats['created'][$i < 7 ? 'last' : 'today'][] = $created;
            $stats['completed'][$i < 7 ? 'last' : 'today'][] = $completed;

            $stats['users'][] = (int)($rawNewUsersStats[$day]?->total ?? 0);
            $stats['customers'][] = (int)($rawNewCustomersStats[$day]?->total ?? 0);
            $stats['services'][] = $created + $completed;
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
            'usersThisWeek', 'growthUser', 'customersThisWeek', 'growthCustomer', 'servicesThisWeek', 'growthService', 'growthCompletedService'));
    }

    private function calculatorGrowth($new, $old): float|int
    {
        if ($old == 0) {
            $growth = $new > 0 ? 100 : 0;
        } else {
            $growth = (($new - $old) / $old) * 100;
        }
        return round($growth, 2);
    }
}
