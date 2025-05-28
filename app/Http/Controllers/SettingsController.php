<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Shop;
use App\Models\Province;
class SettingsController extends Controller
{
    public function company()
    {
        $shop = Shop::first();
        $provinces = Province::orderBy('name')->get();
        return view('settings.company', compact('shop', 'provinces'));
    }

    public function updateCompany(Request $request)
    {
        $shop = Shop::first() ?? new Shop();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:1024',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'economic_code' => 'nullable|string|max:50',
            'national_id' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|string|max:100',
            'support_phone' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $shop->fill($validated);

        if($request->hasFile('logo')) {
            if(!empty($shop->logo) && Storage::disk('public')->exists($shop->logo)) {
                Storage::disk('public')->delete($shop->logo);
            }
            $shop->logo = $request->file('logo')->store('shop-logos', 'public');
        }

        $shop->save();

        return redirect()->route('settings.company')->with('success', 'اطلاعات با موفقیت ذخیره شد.');
    }
}
