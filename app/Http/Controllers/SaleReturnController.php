<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleReturnController extends Controller
{
    // لیست مرجوعی‌ها
    public function index()
    {
        $returns = SaleReturn::with(['sale', 'user'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('sales.returns.index', compact('returns'));
    }

    // فرم ثبت مرجوعی جدید
    public function create()
    {
        $sales = Sale::with('buyer', 'items.product')->latest()->limit(30)->get();
        return view('sales.returns.create', compact('sales'));
    }

    // ثبت مرجوعی
    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'items' => 'required|array|min:1',
        ], [
            'sale_id.required' => 'انتخاب فاکتور الزامی است.',
            'items.required' => 'هیچ آیتمی برای مرجوعی انتخاب نشده است.',
        ]);

        DB::beginTransaction();
        try {
            $sale = Sale::with('items')->findOrFail($request->input('sale_id'));
            $itemsData = $request->input('items', []);

            $totalReturnAmount = 0;

            $return = SaleReturn::create([
                'sale_id' => $sale->id,
                'user_id' => auth()->id(),
                'return_number' => SaleReturn::generateReturnNumber(),
                'return_date' => now(),
                'note' => $request->input('note'),
                'total_amount' => 0, // بعداً آپدیت می‌کنیم
            ]);

            foreach ($itemsData as $saleItemId => $item) {
                if (empty($item['qty']) || intval($item['qty']) < 1) continue;

                $saleItem = $sale->items()->where('id', $saleItemId)->first();
                if (!$saleItem) continue;

                $product = Product::find($saleItem->product_id);
                $returnQty = min(intval($item['qty']), $saleItem->quantity);

                if ($product && $product->is_product) {
                    // افزایش موجودی انبار
                    $stock = Stock::firstOrCreate(['product_id' => $product->id]);
                    $stock->quantity += $returnQty;
                    $stock->save();

                    // از تعداد آیتم فروش فقط به اندازه مرجوعی کم کن
                    $saleItem->quantity -= $returnQty;
                    if ($saleItem->quantity < 0) $saleItem->quantity = 0;
                    $saleItem->save();

                    // ثبت آیتم مرجوعی
                    SaleReturnItem::create([
                        'sale_return_id' => $return->id,
                        'product_id' => $product->id,
                        'qty' => $returnQty,
                        'reason' => $item['reason'] ?? '',
                        'item_description' => $item['item_description'] ?? '',
                        'barcode' => $item['barcode'] ?? null,
                        'is_product' => true,
                    ]);
                    $totalReturnAmount += $returnQty * $saleItem->price;
                } else {
                    // اگر سرویس است (is_product = false)
                    $saleItem->quantity = 0;
                    $saleItem->save();

                    SaleReturnItem::create([
                        'sale_return_id' => $return->id,
                        'product_id' => $product ? $product->id : null,
                        'qty' => 1,
                        'reason' => $item['reason'] ?? '',
                        'item_description' => $item['item_description'] ?? '',
                        'barcode' => $item['barcode'] ?? null,
                        'is_product' => false,
                    ]);
                    $totalReturnAmount += $saleItem->price;
                }
            }

            // ثبت مبلغ مرجوعی
            $return->total_amount = $totalReturnAmount;
            $return->save();

            // کاهش مبلغ مرجوعی از مبلغ نهایی فاکتور
            if ($totalReturnAmount > 0) {
                $sale->final_amount -= $totalReturnAmount;
                if ($sale->final_amount < 0) $sale->final_amount = 0;
                $sale->save();
            }

            DB::commit();
            return redirect()->route('sale_returns.index')->with('success', 'مرجوعی ثبت شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'خطا در ثبت مرجوعی: ' . $e->getMessage()])->withInput();
        }
    }
}
