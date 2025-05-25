<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleReturn;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Carbon\Carbon;
use DB;

class SaleReturnController extends Controller
{
    public function index()
    {
        // ارتباط customer و person را eager load کن تا کند نشه
        $returns = ReturnInvoice::with(['customer.person'])->get();

        return view('returns.index', compact('returns'));
    }
    public function create()
    {
        // این خط درست شد: مقداردهی شماره مرجوعی
        $nextReturnNumber = 'RET' . str_pad(SaleReturn::max('id') + 1, 5, '0', STR_PAD_LEFT);

        // فاکتورهای فروش رو با مشتری و شخص لود کن
        $sales = Sale::with(['customer.person', 'seller'])->orderBy('created_at', 'desc')->get();

        return view('sales.returns.create', compact('sales', 'nextReturnNumber'));
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

            foreach($request->items as $itemId => $item) {
                if(empty($item['selected']) || empty($item['qty']) || $item['qty'] <= 0) continue;

                $saleItem = SaleItem::find($itemId);
                if(!$saleItem) continue;

                $returnQty = min(intval($item['qty']), $saleItem->quantity);

                // ذخیره آیتم مرجوعی
                $return->items()->create([
                    'sale_item_id' => $saleItem->id,
                    'qty' => $returnQty,
                    'reason' => $item['reason'] ?? null,
                ]);

                // اگر کالا است، موجودی را اضافه کند
                if($saleItem->product_id) {
                    $product = Product::find($saleItem->product_id);
                    if($product) {
                        $product->increment('stock', $returnQty);
                    }
                }

                // تعداد آیتم را کم کن (چه کالا چه خدمت)
                $saleItem->decrement('quantity', $returnQty);

                // مبلغ آیتم را از مبلغ نهایی فاکتور کم کن
                $amountToReduce = $saleItem->unit_price * $returnQty;
                $sale->decrement('final_amount', $amountToReduce);
            }
        });

        return redirect()->route('returns.create')->with('success', 'مرجوعی با موفقیت ثبت شد.');
    }
}
