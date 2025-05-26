<?php

namespace App\Http\Controllers;

use App\Models\Shareholder;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

    /**
     * نمایش فرم افزودن خدمت جدید
     */
    public function create()
    {
        $serviceCategories = Category::where('category_type', 'service')->get();
        $units = Unit::orderBy('title')->get();
        $shareholders = Shareholder::all(); // این خط برای پاس دادن به ویو لازم است
        return view('services.create', compact('serviceCategories', 'units', 'shareholders'));
    }


    /**
     * لیست خدمات
     */
    public function index()
    {
        $serviceCategories = Category::where('category_type', 'service')->get();
        $services = Service::latest()->paginate(20);
        return view('services.index', compact('services', 'serviceCategories'));
    }

    public function ajaxList(Request $request)
    {
        $query = Service::with('category')->where('is_active', 1);

        if ($request->has('q') && $request->q) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%$q%")
                    ->orWhere('service_code', 'like', "%$q%");
            });
        }

        $limit = intval($request->get('limit', 10));
        $services = $query->limit($limit)->get();

        $results = $services->map(function ($service) {
            // محصول معادل را پیدا کن
            $product = \App\Models\Product::where('code', $service->service_code)->first();
            return [
                'id'         => $product ? $product->id : null, // این id باید id محصول باشد!
                'code'       => $service->service_code,
                'name'       => $service->title,
                'category'   => $service->category ? $service->category->name : '-',
                'unit'       => $service->unit,
                'sell_price' => $service->price,
                'description'=> $service->short_description ?? $service->description,
                'stock'      => 1,
            ];
        });

        return response()->json($results);
    }

    /**
     * تولید کد خدمت جدید (برای ajax)
     */
    public function nextCode()
    {
        // همه کدهایی که با services- شروع می‌شوند را بگیر
        $last = Service::where('service_code', 'like', 'services-%')
            ->orderByRaw('CAST(SUBSTRING(service_code, 10) AS UNSIGNED) DESC')
            ->first();
        if ($last && preg_match('/^services-(\d+)$/', $last->service_code, $m)) {
            $next = intval($m[1]) + 1;
        } else {
            $next = 1001;
        }
        return response()->json(['code' => 'services-' . $next]);
    }


    /**
     * ثبت خدمت جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_code' => 'required|string|max:255|unique:services,service_code',
            'service_category_id' => 'nullable|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'execution_cost' => 'nullable|numeric',
            'short_description' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
            'is_vat_included' => 'nullable|boolean',
            'is_discountable' => 'nullable|boolean',
            'service_info' => 'nullable|string|max:255',
            'info_link' => 'nullable|string',
            'full_description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_vat_included'] = $request->has('is_vat_included');
        $validated['is_discountable'] = $request->has('is_discountable');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        // واحد باید از unit_id بیاد
        $unit = Unit::find($validated['unit_id']);
        $validated['unit'] = $unit ? $unit->title : null;

        $service = Service::create($validated);

        // محصول معادل بساز
        $service->createOrUpdateProduct();

        return redirect()->route('services.index')->with('success', 'خدمات با موفقیت ثبت شد.');
    }

    /**
     * نمایش فرم ویرایش خدمت
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $serviceCategories = Category::where('category_type', 'service')->get();
        $units = Unit::orderBy('title')->get();
        return view('services.edit', compact('service', 'serviceCategories', 'units'));
    }

    /**
     * ویرایش خدمت
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'title'        => 'required|string|max:255',
            'service_code' => 'required|string|max:255|unique:services,service_code,' . $service->id,
            'service_category_id' => 'nullable|exists:categories,id',
            'unit_id'        => 'required|exists:units,id',
            'price'       => 'nullable|numeric',
            'is_active'   => 'nullable|boolean',
            'service_info' => 'nullable|string|max:255',
            'info_link' => 'nullable|string',
            'full_description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'title', 'service_code', 'service_category_id', 'unit_id', 'price', 'is_active',
            'service_info', 'info_link', 'full_description', 'short_description', 'description'
        ]);
        if (!isset($data['is_active'])) $data['is_active'] = true;

        // واحد باید از unit_id بیاد
        $unit = Unit::find($data['unit_id']);
        $data['unit'] = $unit ? $unit->title : null;

        // اگر عکس جدید آپلود شده
        if ($request->hasFile('image')) {
            // حذف تصویر قبلی (در صورت وجود)
            if ($service->image) {
                \Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        // محصول معادل بروزرسانی شود
        $service->createOrUpdateProduct();

        return redirect()->route('services.index')->with('success', 'خدمت با موفقیت ویرایش شد.');
    }

    /**
     * حذف خدمت
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('services.index')->with('success', 'خدمت با موفقیت حذف شد.');
    }

    // سایر متدها مثل saveForm و ...
}
