<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class SyncWooOrders extends Command
{
    protected $signature = 'sync:woo-orders {--all}';
    protected $description = 'Đồng bộ đơn hàng WooCommerce theo ngày (completed, modified bằng date)';

    public function handle()
    {
        $this->info("Bắt đầu đồng bộ WooCommerce orders");

        $query = ['token' => 'your_secret_token'];
        if (!$this->option('all')) {
            // nếu không có --all, bạn có thể truyền date để chỉ lấy của ngày hôm qua
            $date = now()->subDay()->format('Y-m-d');
            $query['date'] = now()->subDay()->format('Y-m-d');

            $this->info("Ngày đồng bộ: $date");
        }

        $response = Http::get('https://sandienlanh.com/wp-json/custom-api/v1/orders', $query);

        if (!$response->successful()) {
            $this->error('Lỗi khi gọi API: HTTP ' . $response->status());
            return 1;
        }

        $orders = $response->json();

        try {
            DB::beginTransaction();
            foreach ($orders as $order) {
                $customer = $order['customer'];
                $billing_address = $customer['billing_address'];
                $customer = Customer::updateOrCreate([
                    'email' => $customer['email'],
                ], [
                    'code' => $customer['id'] ?: null,
                    'name' => $customer['first_name'] . " " . $customer['last_name'],
                    'phone' => $customer['phone'],
                    'address' => $billing_address['address_1'] . ", " . $billing_address['city'] . ", " . $billing_address['country'],
                ]);
                foreach ($order['items'] as $item) {
                    $product = Product::updateOrCreate([
                        'code' => $item['product_id'],
                    ], [
                        'name' => $item['name'],
                        'serial' => $item['sku']
                    ]);

                    Order::updateOrCreate([
                        'code' => $order['id'],
                        'product_id' => $product->id,
                        'customer_id' => $customer->id,
                    ], [
                        'purchase_date' => $order['date_created'] ?? $order['date_completed'],
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            Log::debug($e);
            DB::rollBack();

            $this->error($e->getMessage());
            return 1;
        }

        $this->info('Đã đồng bộ ' . count($orders) . ' đơn hàng.');
        return 0;
    }
}
