<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlanController extends Controller
{
    public function index()
    {
        $services = Service::with('unit')
            ->where('status', 'plan')
            ->orderBy('service_date', 'desc')
            ->get();

        $units = Unit::where('status', 'active')->get();

        return view('plan', compact('services', 'units'));
    }

public function store(Request $request)
{
    $data = $request->validate([
        'service_date' => 'required|date',
        'unit_id' => 'required|exists:units,id',

        'in_plan' => 'required|date_format:H:i',
        'qa1_plan' => 'nullable|date_format:H:i',
        'washing_plan' => 'nullable|date_format:H:i',
        'action_service_plan' => 'nullable|date_format:H:i',
        'action_backlog_plan' => 'nullable|date_format:H:i',
        'qa7_plan' => 'required|date_format:H:i',

        'downtime_plan' => 'nullable|integer|min:0|max:32767',
        'backlog_item' => 'nullable|string',
        'status' => 'required|in:plan',
    ]);

    Service::create($data);

    return redirect()
        ->route('plans.index')
        ->with('success', 'Plan service created');
}
public function show(Service $service)
{
    return response()->json($service);
}

public function update(Request $request, Service $service)
{
    $data = $request->validate([
        'service_date' => 'required|date',
        'unit_id' => 'required|exists:units,id',
        'in_plan' => 'required|date_format:H:i:s',
        'qa1_plan' => 'nullable|date_format:H:i:s',
        'washing_plan' => 'nullable|date_format:H:i:s',
        'action_service_plan' => 'nullable|date_format:H:i:s',
        'action_backlog_plan' => 'nullable|date_format:H:i:s',
        'qa7_plan' => 'required|date_format:H:i:s',
        'backlog_item' => 'nullable|string',
        'downtime_plan' => 'required|integer|min:0|max:32767',
        'status' => 'required|in:plan',
    ]);

    $service->update($data);

    return back()->with('success', 'Plan updated');
}


public function destroy(Service $service)
{
    $service->delete();
    return back()->with('success', 'Plan deleted');
}

}
