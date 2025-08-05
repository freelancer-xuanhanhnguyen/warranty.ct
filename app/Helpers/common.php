<?php

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

const FORMAT_DATE = 'd/m/Y';
const FORMAT_DATETIME = 'd/m/Y H:m';

function isWarrantyExpired($purchaseDate, $warrantyValue, $warrantyUnit): bool
{
    // Convert về Carbon nếu không phải là Carbon
    $purchaseDate = $purchaseDate instanceof Carbon ? $purchaseDate : Carbon::parse($purchaseDate);

    // Tính ngày hết bảo hành
    $warrantyEndDate = match ((int)$warrantyUnit) {
        Product::WARRANTY_UNIT_DAY => $purchaseDate->copy()->addDays($warrantyValue),
        Product::WARRANTY_UNIT_MONTH => $purchaseDate->copy()->addMonths($warrantyValue),
        Product::WARRANTY_UNIT_YEAR => $purchaseDate->copy()->addYears($warrantyValue),
        default => throw new InvalidArgumentException("Invalid warranty unit: $warrantyUnit"),
    };

    return now()->greaterThan($warrantyEndDate);
}

function checkWarrantyStatus($order, $product = null): array
{
    $lastWarrantyDate = $order->purchase_date;
    $lastWarrantyDate = $lastWarrantyDate instanceof Carbon ? $lastWarrantyDate : Carbon::parse($lastWarrantyDate);

    $product = $product ?? $order->product;

    $warranty_period_unit = $product->warranty_period_unit;
    $warranty_period = $product->warranty_period;


    $warrantyEndDate = $warranty_period ? match ((int)$warranty_period_unit) {
        Product::WARRANTY_UNIT_DAY => $lastWarrantyDate->copy()->addDays($warranty_period),
        Product::WARRANTY_UNIT_MONTH => $lastWarrantyDate->copy()->addMonths($warranty_period),
        Product::WARRANTY_UNIT_YEAR => $lastWarrantyDate->copy()->addYears($warranty_period),
        default => null,
    } : null;

    $expired = $warrantyEndDate ? now()->greaterThan($warrantyEndDate) : null;

    $warrantyNextDate = null;
    if (!$expired) {
        if ($order->old_date) {
            if (now()->toDateString() < $order->old_date->toDateString()) {
                $lastWarrantyDate = $order->old_date;
            }
        }

        $periodic_warranty_unit = $product->periodic_warranty_unit;
        $periodic_warranty = $product->periodic_warranty;

        [$warrantyNextDate, $lastWarrantyDate] = $periodic_warranty ? getNexDate($lastWarrantyDate, $periodic_warranty_unit, $periodic_warranty) : null;
    }

    return [
        'expired' => $expired,
        'end_date' => $warrantyEndDate,
        'next_date' => $warrantyNextDate,
        'old_date' => $lastWarrantyDate,
    ];
}

function getNexDate($lastWarrantyDate, $periodic_warranty_unit, $periodic_warranty)
{
    $warrantyNextDate = match ((int)$periodic_warranty_unit) {
        Product::WARRANTY_UNIT_DAY => $lastWarrantyDate->copy()->addDays($periodic_warranty),
        Product::WARRANTY_UNIT_MONTH => $lastWarrantyDate->copy()->addMonths($periodic_warranty),
        Product::WARRANTY_UNIT_YEAR => $lastWarrantyDate->copy()->addYears($periodic_warranty),
        default => null,
    };

    if ($warrantyNextDate) {
        if (now()->greaterThan($warrantyNextDate)) {
            $lastWarrantyDate = $warrantyNextDate;
            return getNexDate($lastWarrantyDate, $periodic_warranty_unit, $periodic_warranty);
        }

        return [$warrantyNextDate, $lastWarrantyDate];
    }

    return null;
}

function hasRole($role = array(), $only = false)
{
    if (Auth::check()) {
        if ($only) {
            if (in_array(Auth::user()->role, $role)) {
                return true;
            }
        } else if (in_array(Auth::user()->role, array_merge($role, [User::ROLE_ADMIN]))) {
            return true;
        }
    }

    return false;
}

function format_money($amount)
{
    if (!$amount) return null;
    $amount = number_format($amount, 0, ',', '.');
    return "$amount\u{00A0}₫";
}


function the_website_name()
{
    return "CÔNG TY CỔ PHẦN TẬP ĐOÀN NAM VIỆT NAM";
}

if (!function_exists('escape_like')) {
    /**
     * @param $string
     * @return string
     */
    function escape_like($string): string
    {

        return addcslashes($string, '%_');
    }
}

function customer(): \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
{
    return Auth::guard('customer');
}
