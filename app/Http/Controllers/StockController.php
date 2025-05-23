<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;

class StockController extends Controller
{
    /**
     * نمایش لیست موجودی انبار
     */
    public function index()
    {
        return view('stock.index');
    }

    /**
     * نمایش فرم ایجاد موجودی جدید
     */
    public function create()
    {
        return view('stock.create');
    }

    /**
     * ذخیره موجودی جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0',
            'minimum_quantity' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        Stock::create($validated);

        return redirect()->route('stocks.index')
            ->with('success', 'موجودی با موفقیت ثبت شد');
    }

    /**
     * نمایش جزئیات موجودی
     */
    public function show(Stock $stock)
    {
        return view('stock.show', compact('stock'));
    }

    /**
     * نمایش فرم ویرایش موجودی
     */
    public function edit(Stock $stock)
    {
        return view('stock.edit', compact('stock'));
    }

    /**
     * بروزرسانی موجودی
     */
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0',
            'minimum_quantity' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $stock->update($validated);

        return redirect()->route('stocks.index')
            ->with('success', 'موجودی با موفقیت بروزرسانی شد');
    }

    /**
     * حذف موجودی
     */
    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('stocks.index')
            ->with('success', 'موجودی با موفقیت حذف شد');
    }
}
