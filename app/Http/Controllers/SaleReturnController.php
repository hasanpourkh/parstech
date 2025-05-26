<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Stock;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleReturnController extends Controller
{
    public function index()
    {
        $returns = SaleReturn::with(['sale', 'items.product'])->orderByDesc('created_at')->paginate(20);
        return view('sales.returns.index', compact('returns'));
    }

    public function create()
    {
        return view('sales.returns.create');
    }

    public function show($id)
    {
        $return = SaleReturn::with(['sale', 'items.product'])->findOrFail($id);
        return view('sales.returns.show', compact('return'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'items' => 'required|array|min:1',
        ]);

        $items = $request->input('items');
        $filteredItems = [];
        foreach ($items as $id => $item) {
            if (isset($item['selected']) && $item['selected']) {
                $filteredItems[$id] = $item;
            }
        }
        if (empty($filteredItems)) {
            return back()->withErrors(['items' => 'هیچ آیتمی انتخاب نشده است.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $sale = Sale::with('items')->findOrFail($request->input('sale_id'));
            $totalReturnAmount = 0;

            // محاسبه مبلغ مرجوعی و ثبت آیتم‌ها
            $returnItems = [];
            foreach ($filteredItems as $saleItemId => $item) {
                $saleItem = $sale->items()->where('id', $saleItemId)->first();
                if (!$saleItem) continue;

                $product = Product::find($saleItem->product_id);
                $qty = min((int)($item['qty'] ?? 1), $saleItem->qty);

                if ($product && $product->is_product) {
                    $stock = Stock::firstOrCreate(['product_id' => $product->id]);
                    $stock->quantity += $qty;
                    $stock->save();
                    $saleItem->qty -= $qty;
                    $saleItem->save();
                    $returnItems[] = [
                        'product_id'       => $product->id,
                        'qty'              => $qty,
                        'reason'           => $item['reason'] ?? '',
                        'item_description' => $item['item_description'] ?? '',
                        'barcode'          => $item['barcode'] ?? null,
                        'is_product'       => true,
                    ];
                    $totalReturnAmount += $qty * $saleItem->price;
                } else {
                    $saleItem->qty = 0;
                    $saleItem->save();
                    $returnItems[] = [
                        'product_id'       => $product->id,
                        'qty'              => 1,
                        'reason'           => $item['reason'] ?? '',
                        'item_description' => $item['item_description'] ?? '',
                        'barcode'          => $item['barcode'] ?? null,
                        'is_product'       => false,
                    ];
                    $totalReturnAmount += $saleItem->price;
                }
            }

            // ساخت مرجوعی
            $return = SaleReturn::create([
                'sale_id'       => $sale->id,
                'return_number' => SaleReturn::generateReturnNumber(),
                'return_date'   => now(),
                'user_id'       => auth()->id(),
                'note'          => $request->input('note'),
                'total_amount'  => $totalReturnAmount,
            ]);

            // ثبت آیتم‌های مرجوعی
            foreach ($returnItems as $item) {
                $item['sale_return_id'] = $return->id;
                SaleReturnItem::create($item);
            }

            // بروزرسانی مبلغ کل فاکتور اصلی
            $sale->final_amount -= $totalReturnAmount;
            if ($sale->final_amount < 0) $sale->final_amount = 0;
            $sale->save();

            // ساخت فاکتور جدید ویرایش‌شده در صورت وجود آیتم باقی‌مانده
            $remainingItems = $sale->items()->where('qty', '>', 0)->get();
            if ($remainingItems->count() > 0) {
                $newSale = $sale->replicate();
                $newSale->invoice_number = $sale->invoice_number . '-R';
                $newSale->tag = 'ویرایش‌شده-مرجوعی';
                $newSale->final_amount = $remainingItems->sum(function ($i) { return $i->qty * $i->price; });
                $newSale->save();
                foreach ($remainingItems as $item) {
                    $newItem = $item->replicate();
                    $newItem->sale_id = $newSale->id;
                    $newItem->save();
                }
            }

            DB::commit();
            return redirect()->route('sale_returns.index')->with('success', 'مرجوعی ثبت شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'خطا در ثبت مرجوعی: ' . $e->getMessage()])->withInput();
        }
    }
}
