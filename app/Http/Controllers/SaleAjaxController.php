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

        $query = Sale::with(['buyer', 'seller'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search, $filterField) {
                if ($filterField == 'all' || $filterField == 'invoice_number') {
                    $q->orWhere('invoice_number', 'like', "%$search%");
                }
                if ($filterField == 'all' || $filterField == 'buyer') {
                    $q->orWhereHas('buyer', function($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%");
                    });
                }
                if ($filterField == 'all' || $filterField == 'seller') {
                    $q->orWhereHas('seller', function($q2) use ($search) {
                        $q2->where('first_name', 'like', "%$search%")
                           ->orWhere('last_name', 'like', "%$search%");
                    });
                }
                if ($filterField == 'all' || $filterField == 'created_at') {
                    $q->orWhereDate('created_at', $search);
                }
                if ($filterField == 'all' || $filterField == 'final_amount') {
                    $q->orWhere('final_amount', 'like', "%$search%");
                }
            });
        }

        $sales = $query->limit(10)->get();

        $result = $sales->map(function($sale){
            return [
                'id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'created_at' => jdate($sale->created_at)->format('Y/m/d H:i'),
                'buyer' => optional($sale->buyer)->name ?? 'نامشخص',
                'seller' => trim(optional($sale->seller)->first_name . ' ' . optional($sale->seller)->last_name),
                'final_amount' => number_format($sale->final_amount),
            ];
        });

        return response()->json($result);
    }
}
