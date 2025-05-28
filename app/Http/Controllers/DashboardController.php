<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $personCount = Person::count();
        $companyCount = Company::count();
        $invoiceCount = class_exists(Invoice::class) ? Invoice::count() : 0;
        $productCount = class_exists(Product::class) ? Product::count() : 0;

        // آمار هفتگی (۷ روز اخیر)
        $week_days = [];
        $invoices_per_week = [];
        $sales_per_week = [];
        $profit_per_week = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $week_days[] = verta($date)->format('%d %B');
            $invoices = Invoice::whereDate('created_at', $date);
            $invoices_per_week[] = $invoices->count();
            $sales_per_week[] = $invoices->sum('total_amount');
            $profit_per_week[] = $invoices->sum('profit'); // اگر profit نداری، باید محاسبه شود
        }

        // آمار ماهانه (۱۲ ماه اخیر)
        $months = [];
        $invoices_per_month = [];
        $sales_per_month = [];
        $profit_per_month = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month_label = verta($date)->format('%B %Y'); // نام ماه شمسی و سال
            $months[] = $month_label;
            $invoices = Invoice::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month);
            $invoices_per_month[] = $invoices->count();
            $sales_per_month[] = $invoices->sum('total_amount');
            $profit_per_month[] = $invoices->sum('profit');
        }

        return view('dashboard.index', [
            'personCount'         => $personCount,
            'companyCount'        => $companyCount,
            'invoiceCount'        => $invoiceCount,
            'productCount'        => $productCount,
            // داده‌های هفتگی
            'week_days'           => json_encode($week_days, JSON_UNESCAPED_UNICODE),
            'invoices_per_week'   => json_encode($invoices_per_week),
            'sales_per_week'      => json_encode($sales_per_week),
            'profit_per_week'     => json_encode($profit_per_week),
            // داده‌های ماهانه
            'months'              => json_encode($months, JSON_UNESCAPED_UNICODE),
            'invoices_per_month'  => json_encode($invoices_per_month),
            'sales_per_month'     => json_encode($sales_per_month),
            'profit_per_month'    => json_encode($profit_per_month),
        ]);
    }
}
