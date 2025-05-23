<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;

class HeaderComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('layouts.header', function ($view) {
            // محصولات با موجودی کمتر از حداقل (min_stock)
            $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->get();

            // فروش روزانه (مجموع مبلغ فروش‌های امروز)
            $today = Carbon::today();
            $dailySales = Sale::whereDate('created_at', $today)->sum('total_amount');

            $view->with([
                'lowStockProducts' => $lowStockProducts,
                'dailySales' => $dailySales,
            ]);
        });
    }

    public function register()
    {
        //
    }
}
