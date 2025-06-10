<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepairmanController extends Controller
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
            ->select('users.name as repairman_name', 'services.repairman_id', 'users.email', DB::raw('COUNT(services.id) as total_services'))
            ->join('users', 'users.id', '=', 'services.repairman_id')
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            });

        if (\request()->has('q')) {
            $q = \request()->q;
            $query = $query->where([
                ['users.role', '=', User::ROLE_REPAIRMAN],
                ['users.name', 'like', "%$q%"],
            ])->orWhere([
                ['users.role', '=', User::ROLE_REPAIRMAN],
                ['users.email', 'like', "%$q%"],
            ]);
        } else {
            $query = $query->where('users.role', '=', User::ROLE_REPAIRMAN);
        }

        $query = $query->groupBy('services.repairman_id', 'users.name', 'users.email')
            ->selectRaw("SUM(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) as total_under_warranty", [ServiceStatus::STATUS_UNDER_WARRANTY])
            ->selectRaw("SUM(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) as total_under_repair", [ServiceStatus::STATUS_UNDER_REPAIR]);

        $data = $query->paginate(20);

        return view('admin.repairman.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        $latestStatuses = DB::table('service_statuses as ss1')
            ->select('ss1.service_id', 'ss1.code')
            ->whereRaw('ss1.id = (SELECT MAX(ss2.id) FROM service_statuses ss2 WHERE ss2.service_id = ss1.service_id)');

        $report = DB::table('services')
            ->select('users.name as repairman_name', 'services.repairman_id', 'users.email', DB::raw('COUNT(services.id) as total_services'))
            ->join('users', 'users.id', '=', 'services.repairman_id')
            ->leftJoinSub($latestStatuses, 'latest_status', function ($join) {
                $join->on('services.id', '=', 'latest_status.service_id');
            })
            ->where('users.id', '=', $id)
            ->groupBy('services.repairman_id', 'users.name', 'users.email')
            ->selectRaw("SUM(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) as total_under_warranty", [ServiceStatus::STATUS_UNDER_WARRANTY])
            ->selectRaw("SUM(CASE WHEN latest_status.code = ? THEN 1 ELSE 0 END) as total_under_repair", [ServiceStatus::STATUS_UNDER_REPAIR])
            ->first();

        $q = \request()->q;
        $status = \request()->status;
        $query = Service::with([
            'order.product',
            'order.customer',
            'repairman',
            'status',
        ])
            ->where('repairman_id', $id)
            ->when($q, function ($query) use ($q) {
                $query->where('code', 'like', "%$q%");
            });

        if (isset($status)) {
            $query = $query->whereHas('status', function ($query) use ($status) {
                $query->select('id')->where('code', $status);
            });
        }

        $data = $query
            ->latest()
            ->paginate(20);

        return view('admin.repairman.show', compact('data', 'user', 'report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
