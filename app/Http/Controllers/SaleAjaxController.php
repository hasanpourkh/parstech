<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class SaleAjaxController extends Controller
{
    public function latest(Request $request)
    {
        // دقت کن with('person') باید باشد نه با customers
        $sales = Sale::with(['person', 'seller'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $result = $sales->map(function($sale){
            // مستقیماً person را چک کن
            $buyerFullName = 'نامشخص';
            if ($sale->person) {
                $buyerFullName = $sale->person->full_name;
            }
            return [
                'id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'created_at' => jdate($sale->created_at)->format('Y/m/d'),
                'buyer' => $buyerFullName,
                'seller' => $sale->seller ? $sale->seller->first_name . ' ' . $sale->seller->last_name : '',
                'final_amount' => number_format($sale->final_amount),
            ];
        });

        return response()->json($result);
    }
}
