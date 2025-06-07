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
            $data = Order::with([
                'product',
                'customer',
                'service'
            ])
                ->where('customer_id', $customer->id)
                ->when($q, function ($query) use ($q) {
                    $query->where('code', 'like', "%$q%")
                        ->orWhereHas('product', function ($_query) use ($q) {
                            $_query->where('name', 'like', "%$q%")
                                ->orWhere('code', 'like', "%$q%");
                        });
                })
                ->paginate(20);
        }


        return view('pages.orders.index', compact('data'));
    }

    public function history($email, $id)
    {
        $q = \request()->q;
        $customer = Customer::where('email', $email)->first('id');
        $data = Order::where('customer_id', $customer->id)
            ->findOrFail($id);

        $services = Service::where('order_id', $id)
            ->when($q, function ($query) use ($q) {
                $query->where('code', 'like', "%$q%");
            })
            ->latest()
            ->get();

        return view('pages.orders.history', compact('data', 'services'));
    }
}
