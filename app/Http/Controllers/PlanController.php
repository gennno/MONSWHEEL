<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Unit;
use App\Models\ServiceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    DB::transaction(function () use ($data) {

        // 🔎 cari service aktif lama (CN sama)
        $oldService = Service::where('unit_id', $data['unit_id'])
            ->whereIn('status', ['plan', 'process', 'continue','done'])
            ->first();

        if ($oldService) {
            // 🗃️ pindahkan ke service_histories
            ServiceHistory::create([
                'service_id' => $oldService->id,
                'unit_id' => $oldService->unit_id,

                'service_date' => $oldService->service_date,
                'gl' => $oldService->gl,
                'kapten' => $oldService->kapten,
                'bays' => $oldService->bays,
                'backlog_item' => $oldService->backlog_item,

                'in_plan' => $oldService->in_plan,
                'in_actual' => $oldService->in_actual,
                'qa1_plan' => $oldService->qa1_plan,
                'qa1_actual' => $oldService->qa1_actual,
                'washing_plan' => $oldService->washing_plan,
                'washing_actual' => $oldService->washing_actual,
                'action_service_plan' => $oldService->action_service_plan,
                'action_service_actual' => $oldService->action_service_actual,
                'action_backlog_plan' => $oldService->action_backlog_plan,
                'action_backlog_actual' => $oldService->action_backlog_actual,
                'qa7_plan' => $oldService->qa7_plan,
                'qa7_actual' => $oldService->qa7_actual,

                'downtime_plan' => $oldService->downtime_plan,
                'downtime_actual' => $oldService->downtime_actual,

                'note_in' => $oldService->note_in,
                'note_qa1' => $oldService->note_qa1,
                'note_washing' => $oldService->note_washing,
                'note_action_service' => $oldService->note_action_service,
                'note_action_backlog' => $oldService->note_action_backlog,
                'note_qa7' => $oldService->note_qa7,
                'note_downtime' => $oldService->note_downtime,

                'remark' => $oldService->remark,
                'status' => $oldService->status,
                'handover_at' => $oldService->handover_at,
                'completed_at' => $oldService->completed_at,

                'archived_at' => now(),
                'archived_by' => auth()->id(),
            ]);

            // ❌ hapus service lama
            $oldService->delete();
        }

        // ➕ simpan service baru
        Service::create($data);
    });

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
        
        'in_plan' => 'required|date_format:H:i',
        'qa1_plan' => 'nullable|date_format:H:i',
        'washing_plan' => 'nullable|date_format:H:i',
        'action_service_plan' => 'nullable|date_format:H:i',
        'action_backlog_plan' => 'nullable|date_format:H:i',
        'qa7_plan' => 'required|date_format:H:i',

        'backlog_item' => 'nullable|string',
        'downtime_plan' => 'required|integer|min:0|max:32767',
        'status' => 'required|in:plan',
    ]);

        foreach ([
        'in_plan',
        'qa1_plan',
        'washing_plan',
        'action_service_plan',
        'action_backlog_plan',
        'qa7_plan',
    ] as $field) {
        if (!empty($data[$field])) {
            $data[$field] .= ':00';
        }
    }

    $service->update($data);

    return back()->with('success', 'Plan updated');
}


public function destroy(Service $service)
{
    $service->delete();
    return back()->with('success', 'Plan deleted');
}

}
