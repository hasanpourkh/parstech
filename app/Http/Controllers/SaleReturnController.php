<?php

namespace App\Http\Controllers;

use App\Models\SaleReturn;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;

class SaleReturnController extends Controller
{
    public function index()
    {
        $returns = SaleReturn::with(['sale', 'customer'])->orderByDesc('date')->paginate(20);
        return view('sales.returns.index', compact('returns'));
    }

    public function create()
    {
        $sales = Sale::with('customer')->get();
        $customers = Customer::all();
        return view('sales.returns.create', compact('sales', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string|unique:sale_returns,number',
            'reference' => 'nullable|string',
            'sale_id' => 'required|exists:sales,id',
            'customer_id' => 'nullable|exists:customers,id',
            'date' => 'required|date',
            'due_date' => 'nullable|date',
            'total_amount' => 'required|numeric',
            'note' => 'nullable|string',
        ]);
        SaleReturn::create($request->all());
        return redirect()->route('sales.returns.index')->with('success', 'برگشت از فروش ثبت شد.');
    }

    public function edit(SaleReturn $return)
    {
        $sales = Sale::with('customer')->get();
        $customers = Customer::all();
        return view('sales.returns.edit', compact('return', 'sales', 'customers'));
    }

    public function update(Request $request, SaleReturn $return)
    {
        $request->validate([
            'number' => 'required|string|unique:sale_returns,number,' . $return->id,
            'reference' => 'nullable|string',
            'sale_id' => 'required|exists:sales,id',
            'customer_id' => 'nullable|exists:customers,id',
            'date' => 'required|date',
            'due_date' => 'nullable|date',
            'total_amount' => 'required|numeric',
            'note' => 'nullable|string',
        ]);
        $return->update($request->all());
        return redirect()->route('sales.returns.index')->with('success', 'برگشت از فروش ویرایش شد.');
    }

    public function destroy(SaleReturn $return)
    {
        $return->delete();
        return redirect()->route('sales.returns.index')->with('success', 'برگشت از فروش حذف شد.');
    }
}
