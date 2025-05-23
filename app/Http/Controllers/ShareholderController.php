<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\Sale;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class ShareholderController extends Controller
{
    /**
     * نمایش لیست سهامداران با کارت و چارت و آمار
     */
    public function index()
    {
        // لیست سهامداران به همراه آمار کلی
        $shareholders = Person::where('type', 'shareholder')->get();
        $summary = [];
        foreach ($shareholders as $shareholder) {
            // محصولات متعلق به سهامدار
            if (method_exists($shareholder, 'ownedProducts')) {
                $products = $shareholder->ownedProducts()->get();
            } else {
                // اگر Relation نبود، به درستی تعریف کن:
                $products = Product::whereHas('shareholders', function($q) use ($shareholder){
                    $q->where('person_id', $shareholder->id);
                })->get();
            }
            $productIds = $products->pluck('id');
            $saleItems = SaleItem::whereIn('product_id', $productIds)->get();
            $totalSell = 0;
            $totalBuy = 0;
            foreach ($saleItems as $item) {
                $percent = $products->find($item->product_id)?->pivot?->percent ?? 100;
                $totalSell += ($item->total * $percent / 100);
                $totalBuy += ($item->quantity * ($products->find($item->product_id)?->buy_price ?? 0) * $percent / 100);
            }
            $profit = $totalSell - $totalBuy;
            $summary[$shareholder->id] = [
                'totalSell' => $totalSell,
                'profit' => $profit,
                'products_count' => $products->count(),
            ];
        }
        return view('shareholders.index', compact('shareholders', 'summary'));
    }

    /**
     * نمایش اطلاعات مالی و چارت ها و محصولات یک سهامدار خاص
     */
    public function show($id, Request $request)
    {
        $shareholder = Person::findOrFail($id);

        // تعیین بازه زمانی (دوره)
        $period = $request->input('period', 'month');
        $nowJalali = Jalalian::now();

        switch ($period) {
            case 'day':
                $from = $nowJalali->subDays(1);
                break;
            case '3day':
                $from = $nowJalali->subDays(3);
                break;
            case 'week':
                $from = $nowJalali->subWeeks(1);
                break;
            case '2week':
                $from = $nowJalali->subWeeks(2);
                break;
            case '3week':
                $from = $nowJalali->subWeeks(3);
                break;
            case '3month':
                $from = $nowJalali->subMonths(3);
                break;
            case '6month':
                $from = $nowJalali->subMonths(6);
                break;
            case 'year':
                $from = $nowJalali->subYears(1);
                break;
            default:
                $from = $nowJalali->subMonths(1);
        }
        $fromGregorian = $from->toCarbon()->toDateString();
        $toGregorian = $nowJalali->toCarbon()->toDateString();

        // محصولات متعلق به سهامدار
        if (method_exists($shareholder, 'ownedProducts')) {
            $products = $shareholder->ownedProducts()->with('category')->get();
        } else {
            $products = Product::whereHas('shareholders', function($q) use ($shareholder){
                $q->where('person_id', $shareholder->id);
            })->with('category')->get();
        }

        // آیتم‌های فروش محصولات این سهامدار در بازه انتخابی
        $productIds = $products->pluck('id');
        $saleItems = SaleItem::whereIn('product_id', $productIds)
            ->whereHas('sale', function($q) use ($fromGregorian, $toGregorian) {
                $q->whereBetween('created_at', [$fromGregorian, $toGregorian]);
            })
            ->get();

        // مجموع فروش، سود و زیان
        $totalSell = 0;
        $totalBuy = 0;
        foreach ($saleItems as $item) {
            $percent = $products->find($item->product_id)?->pivot?->percent ?? 100;
            $totalSell += ($item->total * $percent / 100);
            $totalBuy += ($item->quantity * ($products->find($item->product_id)?->buy_price ?? 0) * $percent / 100);
        }
        $profit = $totalSell - $totalBuy;

        // داده برای چارت فروش - روزانه در بازه دوره
        $chartData = [];
        $dates = [];
        $periodLength = match ($period) {
            'day' => 1, '3day' => 3, 'week' => 7, '2week' => 14, '3week' => 21,
            '3month' => 90, '6month' => 180, 'year' => 365, default => 30,
        };
        for ($i = $periodLength - 1; $i >= 0; $i--) {
            $dateJalali = Jalalian::now()->subDays($i);
            $date = $dateJalali->toCarbon()->toDateString();
            $dates[] = $dateJalali->format('Y/m/d');
            $daySell = $saleItems->filter(function($item) use ($date) {
                return \Carbon\Carbon::parse($item->created_at)->toDateString() == $date;
            })->sum('total');
            $chartData[] = $daySell;
        }

        return view('shareholders.show', compact(
            'shareholder', 'products', 'saleItems', 'totalSell', 'totalBuy', 'profit',
            'chartData', 'dates', 'period'
        ));
    }
}
