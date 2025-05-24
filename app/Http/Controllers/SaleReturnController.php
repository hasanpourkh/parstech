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
        // 10 فروش آخر و شماره مرجوعی بعدی
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
            'items' => 'required|array|min:1',
        ]);

        DB::transaction(function() use ($request) {
            $sale = Sale::findOrFail($request->sale_id);

            $return = SaleReturn::create([
                'return_number' => $request->return_number,
                'sale_id' => $sale->id,
                'reason' => $request->reason,
                'description' => $request->description,
                'user_id' => auth()->id(),
                'returned_at' => Carbon::now(),
            ]);

            foreach($request->items as $item) {
                if(empty($item['selected']) || empty($item['qty']) || $item['qty'] <= 0) continue;

                $saleItem = $sale->items()->find($item['id']);
                if(!$saleItem) continue;

                $qty = min(intval($item['qty']), $saleItem->qty);

                // ثبت مرجوعی برای آیتم
                $return->items()->create([
                    'sale_item_id' => $saleItem->id,
                    'qty' => $qty,
                    'reason' => $item['reason'] ?? null,
                ]);

                // افزایش موجودی محصول (در صورت محصول بودن)
                if($saleItem->product_id) {
                    $product = Product::find($saleItem->product_id);
                    if($product) {
                        $product->increment('stock', $qty);
                    }
                }

                // کم کردن از فروش اصلی
                $saleItem->decrement('qty', $qty);
            }
        });

        return redirect()->route('returns.create')->with('success', 'مرجوعی با موفقیت ثبت شد.');
    }
}
