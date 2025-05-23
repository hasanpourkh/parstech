<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:units,title'
        ]);

        $unit = Unit::create([
            'title' => $validated['title']
        ]);

        return response()->json([
            'success' => true,
            'unit' => $unit
        ]);
    }
}
