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

function checkWarrantyStatus(
    $purchaseDate,
    $warrantyValue,
    $warrantyUnit,
    $lastWarrantyDate = null
): array
{
    $baseDate = $lastWarrantyDate ?: $purchaseDate;

    $baseDate = $baseDate instanceof Carbon ? $baseDate : Carbon::parse($baseDate);

    $warrantyEndDate = match ((int)$warrantyUnit) {
        Product::WARRANTY_UNIT_DAY => $baseDate->copy()->addDays($warrantyValue),
        Product::WARRANTY_UNIT_MONTH => $baseDate->copy()->addMonths($warrantyValue),
        Product::WARRANTY_UNIT_YEAR => $baseDate->copy()->addYears($warrantyValue),
        default => throw new InvalidArgumentException("Invalid warranty unit: $warrantyUnit"),
    };

    $expired = now()->greaterThan($warrantyEndDate);

    return [
        'expired' => $expired,
        'warranty_end_date' => $warrantyEndDate->toDateString(),
        'next_warranty_check_date' => $expired ? null : $warrantyEndDate->toDateString(),
        'used_base_date' => $baseDate->toDateString(), // để biết đang dùng ngày nào tính bảo hành
    ];
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
    return number_format($amount, 0, ',', '.') . 'đ';
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
