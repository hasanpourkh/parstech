<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Customer;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::with('customer')->orderByDesc('income_date')->paginate(20);
        $total = Income::sum('amount');
        return view('financial.incomes.index', compact('incomes', 'total'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('financial.incomes.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'income_date' => 'nullable|date',
            'customer_id' => 'nullable|exists:customers,id',
            'note' => 'nullable|string',
        ]);
        Income::create($request->all());
        return redirect()->route('financial.incomes.index')->with('success', 'درآمد با موفقیت ثبت شد.');
    }

    public function edit(Income $income)
    {
        $customers = Customer::all();
        return view('financial.incomes.edit', compact('income', 'customers'));
    }

    public function update(Request $request, Income $income)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'income_date' => 'nullable|date',
            'customer_id' => 'nullable|exists:customers,id',
            'note' => 'nullable|string',
        ]);
        $income->update($request->all());
        return redirect()->route('financial.incomes.index')->with('success', 'درآمد ویرایش شد.');
    }

    public function destroy(Income $income)
    {
        $income->delete();
        return redirect()->route('financial.incomes.index')->with('success', 'درآمد حذف شد.');
    }
}
