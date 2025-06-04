<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = \request()->q;
        $data = Order::with([
            'product',
            'customer',
        ])
            ->when($q, function ($query) use ($q) {
                $query->where('code', 'like', "%$q%")
                    ->orWhereHas('product', function ($_query) use ($q) {
                        $_query->where('name', 'like', "%$q%")
                            ->orWhere('code', 'like', "%$q%");
                    });
            })
            ->paginate(20);

        return view('admin.products.index', compact('data'));
    }

    public function history($id)
    {
        $q = \request()->q;
        $data = Order::findOrFail($id);
        $services = Service::whereHas('order', function ($query) use ($id) {
            $query->select('id')
                ->where('product_id', $id);
        })
            ->when($q, function ($query) use ($q) {
                $query->where('code', 'like', "%$q%");
            })
            ->latest()
            ->get();

        return view('admin.products.show', compact('data', 'services'));
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
        $data = Order::with('product:id,code,name')
            ->where('code', $id)
            ->get(['id', 'product_id', 'code']);

        return response()->json($data);
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
