<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\Sale;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleReturnController extends Controller
{
    public function create()
    {
        // اینجا می‌توانی لیست فاکتورهای فروش، مشتریان و ... را به ویو بفرستی
        $sales = Sale::all();
        $customers = Person::all();

        // فرض بر این است که ویوی ساخت مرجوعی sales.returns.create است
        return view('sales.returns.create', compact('sales', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|unique:sale_returns,number',
            'sale_id' => 'required|exists:sales,id',
            'customer_id' => 'required|exists:persons,id',
            'date' => 'required|date',
            'total_amount' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.barcode' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // ثبت مرجوعی
            $saleReturn = SaleReturn::create([
                'number' => $request->number,
                'reference' => $request->reference,
                'sale_id' => $request->sale_id,
                'customer_id' => $request->customer_id,
                'date' => $request->date,
                'due_date' => $request->due_date,
                'total_amount' => $request->total_amount,
                'note' => $request->note,
            ]);

            // ثبت آیتم‌ها و بروزرسانی انبار و فاکتور
            foreach ($request->items as $item) {
                SaleReturnItem::create([
                    'sale_return_id' => $saleReturn->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'barcode' => $item['barcode'],
                    'note' => $item['note'] ?? null,
                ]);

                // افزایش موجودی انبار
                $stock = Stock::firstOrCreate(
                    ['product_id' => $item['product_id']],
                    ['quantity' => 0]
                );
                $stock->increment('quantity', $item['quantity']);

                // کاهش تعداد محصول از فاکتور
                $sale = Sale::findOrFail($request->sale_id);
                $saleItem = $sale->items()->where('product_id', $item['product_id'])->first();
                if ($saleItem) {
                    $saleItem->quantity -= $item['quantity'];
                    $saleItem->quantity = max(0, $saleItem->quantity);
                    $saleItem->save();
                }
            }

            // بروزرسانی مبلغ کل فاکتور فروش
            $sale = Sale::findOrFail($request->sale_id);
            $newFinalAmount = $sale->items->sum(function($item) {
                return $item->quantity * $item->price; // فرض بر این که فیلد price داری
            });
            $sale->final_amount = $newFinalAmount;
            $sale->save();

            DB::commit();
            return response()->json(['success' => true, 'id' => $saleReturn->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
