<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('code')->get();
        return view('unit', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'   => 'required|unique:units,code',
            'type'   => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        Unit::create($request->only('code', 'type', 'status'));

        return redirect()->route('units.index');
    }

    public function show(Unit $unit)
    {
        return response()->json($unit);
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'code'   => 'required|unique:units,code,' . $unit->id,
            'type'   => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $unit->update($request->only('code', 'type', 'status'));

        return redirect()->route('units.index');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index');
    }
}


