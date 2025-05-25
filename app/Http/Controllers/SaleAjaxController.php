<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class SaleAjaxController extends Controller
{
    // لیست فاکتورها
    public function latest(Request $request)
    {
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
                'created_at' => jdate($sale->created_at)->format('Y/m/d'),
                'buyer' => $buyerFullName,
                'seller' => $sale->seller ? $sale->seller->first_name . ' ' . $sale->seller->last_name : '',
                'final_amount' => number_format($sale->final_amount),
            ];
        });

        return response()->json($result);
    }

    // نمایش جزئیات یک فاکتور
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
            'created_at' => jdate($sale->created_at)->format('Y/m/d'),
            'buyer' => $buyerFullName,
            'seller' => $sellerFullName,
            'final_amount' => number_format($sale->final_amount),
            'items' => $items,
        ]);
    }

    // ذخیره فاکتور جدید
    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required',
            'customer_id' => 'required|exists:persons,id',
            'seller_id' => 'required|exists:sellers,id',
            'final_amount' => 'required|numeric',
        ]);

        $sale = Sale::create([
            'invoice_number' => $request->invoice_number,
            'customer_id'   => $request->customer_id, // مهم!
            'seller_id'     => $request->seller_id,
            'final_amount'  => $request->final_amount,
            'created_at'    => now(),
        ]);

        return response()->json(['success' => true, 'sale_id' => $sale->id]);
    }

    // ویرایش فاکتور
    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_number' => 'required',
            'customer_id' => 'required|exists:persons,id',
            'seller_id' => 'required|exists:sellers,id',
            'final_amount' => 'required|numeric',
        ]);

        $sale = Sale::findOrFail($id);
        $sale->update([
            'invoice_number' => $request->invoice_number,
            'customer_id'   => $request->customer_id, // مهم!
            'seller_id'     => $request->seller_id,
            'final_amount'  => $request->final_amount,
        ]);

        return response()->json(['success' => true]);
    }
}
