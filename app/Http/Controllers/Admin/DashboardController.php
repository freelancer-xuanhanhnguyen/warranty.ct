<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceStatus;
use App\Models\User;
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
            ->orderBy('total_services', 'desc');


        $reportRepairman = $query->limit(10)->get();

        $reportServices = DB::table('services')
            ->select('services.id', 'latest_status.code')
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->groupBy('services.id', 'latest_status.code')
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
            ->first();

        $cskh = User::where('role', User::ROLE_CSKH)->count();
        $repairman = User::where('role', User::ROLE_REPAIRMAN)->count();
        $customer = Customer::count();
        $service = Service::count();

        return view('dashboard', compact('reportRepairman', 'reportServices', 'cskh', 'repairman', 'customer', 'service'));
    }
}
