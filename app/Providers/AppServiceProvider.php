<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('layouts.header', function ($view) {
            // محصولات با موجودی کمتر از حداقل (min_stock)
            $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->get();

            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            $monthStart = Carbon::now()->startOfMonth();
            $now = Carbon::now();

            // فروش امروز (کل مبلغ)
            $dailySales = Sale::whereDate('created_at', $today)->sum('total_amount');

            // فروش روز قبل
            $yesterdaySales = Sale::whereDate('created_at', $yesterday)->sum('total_amount');

            // فروش ماه جاری
            $monthlySales = Sale::whereBetween('created_at', [$monthStart, $now])->sum('total_amount');

            // فروش امروز به تفکیک ساعت (آرایه 24 تایی)
            $hourlySales = [];
            for ($h = 0; $h < 24; $h++) {
                $from = $today->copy()->setTime($h, 0, 0);
                $to = $today->copy()->setTime($h, 59, 59);

                $hourlySales[] = Sale::whereBetween('created_at', [$from, $to])->sum('total_amount');
            }

            $view->with([
                'lowStockProducts' => $lowStockProducts,
                'dailySales' => $dailySales,
                'yesterdaySales' => $yesterdaySales,
                'monthlySales' => $monthlySales,
                'hourlySales' => $hourlySales,
            ]);
        });
    }
}
