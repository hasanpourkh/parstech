<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\Product;
use Carbon\Carbon;
use DB;

class SaleReturnController extends Controller
{
    public function create()
    {
        $lastSales = Sale::orderBy('created_at', 'desc')->limit(10)->get();
        $nextReturnNumber = 'RET' . str_pad(SaleReturn::max('id') + 1, 5, '0', STR_PAD_LEFT);

        return view('sales.returns.create', compact('lastSales', 'nextReturnNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'return_number' => 'required|string|max:100|unique:sale_returns,return_number',
            'sale_id' => 'required|exists:sales,id',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'items_data' => 'required|string',
        ]);

        $itemIds = json_decode($request->items_data, true);

        DB::transaction(function() use ($request, $itemIds) {
            $sale = \App\Models\Sale::findOrFail($request->sale_id);

            $return = \App\Models\SaleReturn::create([
                'return_number' => $request->return_number,
                'sale_id' => $sale->id,
                'reason' => $request->reason,
                'description' => $request->description,
                'user_id' => auth()->id(),
                'returned_at' => \Carbon\Carbon::now(),
            ]);

            foreach($itemIds as $item_id) {
                $saleItem = $sale->items()->find($item_id);
                if(!$saleItem) continue;

                $qty = $saleItem->quantity; // کل تعداد آیتم را مرجوع می‌کند

                $return->items()->create([
                    'sale_item_id' => $saleItem->id,
                    'qty' => $qty,
                    'reason' => null,
                ]);
                // موجودی محصول را برگرداند
                if($saleItem->product_id) {
                    $product = \App\Models\Product::find($saleItem->product_id);
                    if($product) {
                        $product->increment('stock', $qty);
                    }
                }
                $saleItem->decrement('quantity', $qty);
            }
        });

        return redirect()->route('returns.create')->with('success', 'مرجوعی با موفقیت ثبت شد.');
    }
    public function index()
    {
        $returns = SaleReturn::latest()->paginate(15);
        return view('sales.returns.index', compact('returns'));
    }
}
