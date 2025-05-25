<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class SaleAjaxController extends Controller
{
    public function latest(Request $request)
    {
        $filterField = $request->get('filter', 'all');
        $search = $request->get('search', '');

        // ارتباط buyer به customers است و باید لود شود
        $query = Sale::with(['buyer', 'seller'])
            ->orderBy('created_at', 'desc');

        // فیلتر جستجو همانند قبل...

        $sales = $query->limit(10)->get();

        $result = $sales->map(function($sale){
            return [
                'id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'created_at' => jdate($sale->created_at)->format('Y/m/d'),
                // خریدار: اگر موجود باشد name مشتری را نمایش بده
                'buyer' => $sale->buyer ? $sale->buyer->name : 'نامشخص',
                'seller' => $sale->seller ? $sale->seller->first_name . ' ' . $sale->seller->last_name : '',
                'final_amount' => number_format($sale->final_amount),
            ];
        });

        return response()->json($result);
    }
}
