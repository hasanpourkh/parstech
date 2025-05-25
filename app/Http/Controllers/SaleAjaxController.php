<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class SaleAjaxController extends Controller
{
    // متد لیست آخرین فروش‌ها
    public function latest(Request $request)
    {
        // گرفتن لیست آخرین فروش‌ها همراه با شخص و فروشنده
        $sales = Sale::with(['person', 'seller'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $result = $sales->map(function($sale){
            $buyerFullName = 'نامشخص';
            if ($sale->person) {
                $buyerFullName = $sale->person->full_name;
            }
            return [
                'id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'created_at' => \Morilog\Jalali\Jalalian::fromDateTime($sale->created_at)->format('Y/m/d'),
                'buyer' => $buyerFullName,
                'seller' => $sale->seller ? $sale->seller->first_name . ' ' . $sale->seller->last_name : '',
                'final_amount' => number_format($sale->final_amount),
            ];
        });

        return response()->json($result);
    }

    // متد نمایش جزییات یک فاکتور
    public function show($id)
    {
        $sale = Sale::with(['person', 'seller', 'items'])->findOrFail($id);

        $buyerFullName = 'نامشخص';
        if ($sale->person) {
            $buyerFullName = $sale->person->full_name;
        }

        $sellerFullName = $sale->seller ? $sale->seller->first_name . ' ' . $sale->seller->last_name : '';

        $items = $sale->items ? $sale->items->toArray() : [];

        return response()->json([
            'id' => $sale->id,
            'invoice_number' => $sale->invoice_number,
            'created_at' => \Morilog\Jalali\Jalalian::fromDateTime($sale->created_at)->format('Y/m/d'),
            'buyer' => $buyerFullName,
            'seller' => $sellerFullName,
            'final_amount' => number_format($sale->final_amount),
            'items' => $items,
        ]);
    }
}
