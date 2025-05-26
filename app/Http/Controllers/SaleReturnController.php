<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Stock;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\SaleItem;
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
        // لیست فاکتورها با ایجکس لود می‌شود
        return view('sales.returns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'items' => 'required|array|min:1',
        ]);

        $items = $request->input('items');
        // فقط آیتم‌هایی که کاربر تیک زده را نگه دار
        $filteredItems = [];
        foreach ($items as $id => $item) {
            if (isset($item['selected']) && $item['selected'] == 1) {
                $filteredItems[$id] = $item;
            }
        }
        if (empty($filteredItems)) {
            return back()->withErrors(['items' => 'هیچ آیتمی انتخاب نشده است.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $sale = Sale::with('items')->findOrFail($request->input('sale_id'));
            $return = SaleReturn::create([
                'sale_id' => $sale->id,
                'note' => $request->input('note'),
                'user_id' => auth()->id(),
            ]);
            $totalReturnAmount = 0;

            foreach ($filteredItems as $saleItemId => $item) {
                $saleItem = $sale->items()->where('id', $saleItemId)->first();
                if (!$saleItem) continue;

                $product = Product::find($saleItem->product_id);
                $qty = isset($item['qty']) ? (int)$item['qty'] : 1;
                $qty = min($qty, $saleItem->qty);

                if ($product && $product->is_product) {
                    // کالا: به انبار اضافه کن و از فاکتور کم کن
                    $stock = Stock::firstOrCreate(['product_id' => $product->id]);
                    $stock->quantity += $qty;
                    $stock->save();

                    $saleItem->qty -= $qty;
                    $saleItem->save();

                    $returnItem = SaleReturnItem::create([
                        'sale_return_id' => $return->id,
                        'product_id' => $product->id,
                        'qty' => $qty,
                        'reason' => $item['reason'] ?? '',
                        'item_description' => $item['item_description'] ?? '',
                        'barcode' => $item['barcode'] ?? null,
                        'is_product' => true,
                    ]);
                    $totalReturnAmount += $qty * $saleItem->price;
                } else {
                    // خدمات: فقط مبلغ کم شود
                    $saleItem->qty = 0;
                    $saleItem->save();

                    $returnItem = SaleReturnItem::create([
                        'sale_return_id' => $return->id,
                        'product_id' => $product->id,
                        'qty' => 1,
                        'reason' => $item['reason'] ?? '',
                        'item_description' => $item['item_description'] ?? '',
                        'barcode' => $item['barcode'] ?? null,
                        'is_product' => false,
                    ]);
                    $totalReturnAmount += $saleItem->price;
                }
            }

            // به‌روزرسانی مبلغ کل فاکتور اصلی
            $sale->final_amount -= $totalReturnAmount;
            if ($sale->final_amount < 0) $sale->final_amount = 0;
            $sale->save();

            // اگر آیتمی باقی ماند، فاکتور جدید (ویرایش‌شده) بساز
            $remainingItems = $sale->items()->where('qty', '>', 0)->get();
            if ($remainingItems->count() > 0) {
                // فاکتور جدید با شماره جدید و تگ مرجوعی
                $newSale = $sale->replicate();
                $newSale->invoice_number = $sale->invoice_number . '-R';
                $newSale->tag = 'ویرایش‌شده-مرجوعی';
                $newSale->final_amount = $remainingItems->sum(function ($i) { return $i->qty * $i->price; });
                $newSale->save();

                // کپی آیتم‌های باقی‌مانده به فاکتور جدید
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

    public function show($id)
{
    $return = \App\Models\SaleReturn::with(['sale', 'items.product'])->findOrFail($id);
    return view('sales.returns.show', compact('return'));
}
}
