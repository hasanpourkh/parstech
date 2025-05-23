<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Person;
use App\Models\CustomerPurchase;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class InvoiceController extends Controller
{
    // فرم ایجاد فاکتور جدید
    public function newForm()
    {
        $sellers = Seller::all();
        $currencies = Currency::all();
        $lastInvoice = Invoice::orderByDesc('id')->first();
        // اگر شماره فاکتور قبلی رشته باشد، فقط عدد انتهایش را استخراج کن
        if ($lastInvoice && preg_match('/\d+$/', $lastInvoice->invoice_number, $matches)) {
            $nextNumber = intval($matches[0]) + 1;
        } else {
            $nextNumber = 10001;
        }
        return view('sales.create', compact('sellers', 'currencies', 'nextNumber'));
    }

    // گرفتن شماره فاکتور بعدی (برای حالت اتوماتیک)
    public function getNextNumber()
    {
        $last = Invoice::where('invoice_number', 'LIKE', 'invoices-%')
            ->whereRaw("invoice_number REGEXP '^invoices-[0-9]+$'")
            ->orderByRaw("CAST(SUBSTRING(invoice_number, 10) AS UNSIGNED) DESC")
            ->first();

        if ($last && preg_match('/\d+$/', $last->invoice_number, $matches)) {
            $next = intval($matches[0]) + 1;
        } else {
            $next = 10001;
        }
        return response()->json(['number' => "invoices-$next"]);
    }

    // ذخیره فاکتور جدید
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:persons,id',
            'invoice_items' => 'required|string'
        ]);

        $items = json_decode($request->invoice_items, true);

        if (empty($items) || !is_array($items)) {
            return back()->withErrors(['invoice_items' => 'حداقل یک محصول باید به فاکتور اضافه شود.']);
        }

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'invoice_number' => $request->input('invoice_number') ?? $this->getNextNumber()['number'],
                'customer_id' => $request->customer_id,
                'date' => now(),
                // سایر فیلدهای لازم را اینجا اضافه کن
            ]);

            // ثبت اقلام فاکتور
            foreach ($items as $item) {
                $invoice->items()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['count'],
                    'unit_price' => $item['sell_price'],
                    'total' => ($item['count'] * $item['sell_price']) - ($item['discount'] ?? 0),
                ]);
            }

            // ثبت خرید مشتری
            CustomerPurchase::create([
                'customer_id' => $invoice->customer_id,
                'invoice_id'  => $invoice->id,
                'total_amount'=> $invoice->final_amount ?? 0,
                'purchase_date' => $invoice->date ?? now(),
            ]);

            DB::commit();
            return redirect()->route('invoices.show', $invoice->id)->with('success', 'فاکتور با موفقیت ثبت شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'خطا در ثبت فاکتور: ' . $e->getMessage()]);
        }
    }

    // نمایش فاکتور
    public function show($id)
    {
        $invoice = Invoice::with(['items.product', 'customer'])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }
}
