<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Service;

class OrderController extends Controller
{
    public function index($email)
    {
        $q = \request()->q;
        $customer = Customer::where('email', $email)->first('id');
        $data = null;
        if ($customer) {
            $query = Order::with([
                'product',
                'customer',
                'service'
            ])
                ->where('customer_id', $customer->id)
                ->when($q, function ($query) use ($q) {
                    $q = escape_like($q);
                    $query->where('code', 'like', "%{$q}%")
                        ->orWhereHas('product', function ($_query) use ($q) {
                            $_query->where('name', 'like', "%{$q}%")
                                ->orWhere('code', 'like', "%{$q}%");
                        });
                });

            $sort = \request()->sort ?? [];
            foreach ($sort as $key => $value) {
                $query = $query->join('products', 'products.id', '=', 'orders.product_id')
                    ->orderBy(str_replace('__', '.', $key), $value);
            }

            $data = $query->paginate(20);
        }


        return view('pages.orders.index', compact('data'));
    }

    public function history($email, $id)
    {
        $customer = Customer::where('email', $email)->first('id');
        $data = Order::where('customer_id', $customer->id)
            ->findOrFail($id);

        $q = \request()->q;
        $status = \request()->status;
        $query = Service::with([
            'order.product',
            'order.customer',
            'repairman',
            'status',
        ])
            ->where('order_id', $id)
            ->when($q, function ($query) use ($q) {
                $q = escape_like($q);
                $query->where('code', 'like', "%{$q}%");
            });

        if (isset($status)) {
            $query = $query->whereHas('status', function ($query) use ($status) {
                $query->select('id')->where('code', $status);
            });
        }

        $services = $query
            ->latest()
            ->paginate(20);

        return view('pages.orders.history', compact('data', 'services'));
    }
}
