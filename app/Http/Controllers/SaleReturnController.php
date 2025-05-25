<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleReturn;
use Carbon\Carbon;
use DB;

class SaleReturnController extends Controller
{
    public function create()
    {
        $nextReturnNumber = 'RET' . str_pad(SaleReturn::max('id') + 1, 5, '0', STR_PAD_LEFT);

        // گرفتن همه فاکتورها با اطلاعات خریدار و فروشنده و آیتم‌ها
        $sales = Sale::with(['buyer', 'seller', 'items.product'])->orderBy('created_at', 'desc')->get();

        return view('sales.returns.create', compact('nextReturnNumber', 'sales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'return_number' => 'required|string|max:100|unique:sale_returns,return_number',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'return_data' => 'required|string',
        ]);

        $returnData = json_decode($request->return_data, true);

        DB::transaction(function() use ($request, $returnData) {
            foreach ($returnData as $saleId => $saleInfo) {
                $return = SaleReturn::create([
                    'return_number' => $request->return_number,
                    'sale_id' => $saleId,
                    'reason' => $request->reason,
                    'description' => $request->description,
                    'user_id' => auth()->id(),
                    'returned_at' => Carbon::now(),
                ]);
                foreach ($saleInfo['items'] as $itemId => $item) {
                    // پیدا کردن sale_item
                    $saleItem = \App\Models\SaleItem::find($itemId);
                    if(!$saleItem) continue;

                    $qty = $saleItem->quantity;
                    $return->items()->create([
                        'sale_item_id' => $saleItem->id,
                        'qty' => $qty,
                        'reason' => null,
                    ]);
                    // برگرداندن موجودی محصول
                    if($saleItem->product_id) {
                        $product = \App\Models\Product::find($saleItem->product_id);
                        if($product) {
                            $product->increment('stock', $qty);
                        }
                    }
                    $saleItem->decrement('quantity', $qty);
                }
            }
        });

        return redirect()->route('returns.create')->with('success', 'مرجوعی با موفقیت ثبت شد.');
    }
}
