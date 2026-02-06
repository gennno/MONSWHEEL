<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceHistory;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    // Show monitoring page


        public function index()
    {
        $units = Unit::whereHas('activeService')
            ->with('activeService') // eager load active service
            ->orderBy('code')
            ->get();

        // 🔽 ambil service plan
        $planServices = Service::where('status', 'plan')
            ->with('unit')
            ->orderBy('service_date')
            ->get();

        return view('monitoring', compact('units', 'planServices'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'service_id' => 'required|exists:services,id',

        'gl' => 'nullable|string',
        'kapten' => 'nullable|string',

        'in_actual' => 'nullable|date_format:H:i',
        'qa1_actual' => 'nullable|date_format:H:i',
        'washing_actual' => 'nullable|date_format:H:i',
        'action_service_actual' => 'nullable|date_format:H:i',
        'action_backlog_actual' => 'nullable|date_format:H:i',
        'qa7_actual' => 'nullable|date_format:H:i',

        'bays' => 'nullable|integer|min:1',
    ]);

    $service = Service::where('id', $validated['service_id'])
        ->where('status', 'plan')
        ->firstOrFail();

    $service->update([
        'gl' => $validated['gl'],
        'kapten' => $validated['kapten'],
        'bays' => $validated['bays'],

        'in_actual' => $validated['in_actual'],
        'qa1_actual' => $validated['qa1_actual'],
        'washing_actual' => $validated['washing_actual'],
        'action_service_actual' => $validated['action_service_actual'],
        'action_backlog_actual' => $validated['action_backlog_actual'],
        'qa7_actual' => $validated['qa7_actual'],

        'status' => 'process',
    ]);

    return redirect()
        ->route('monitoring.index')
        ->with('success', 'Service started');
}

    public function edit(Unit $unit)
{
    $service = $unit->activeService;
    return response()->json($service);
}

public function update(Request $request, Service $service)
{
    $validated = $request->validate([
        'date'            => 'required|date',
        'shift'           => 'required|in:1,2',

        'kapten'          => 'nullable|string|max:255',
        'gl'              => 'nullable|string|max:255',
        'qa1'             => 'nullable|string|max:255',

        'note1'           => 'nullable|string',
        'washing'         => 'nullable|in:yes,no',
        'note2'           => 'nullable|string',
        'action_service'  => 'nullable|string',
        'note3'           => 'nullable|string',

        'bays'            => 'nullable|string|max:255',
        'action_backlog'  => 'nullable|string',
        'note4'           => 'nullable|string',

        'rfu'             => 'nullable|in:ready,not_ready',
        'downtime_plan'   => 'nullable|date',
        'downtime_actual' => 'nullable|date',
        'note5'           => 'nullable|string',
    ]);

    $service->update([
        'service_date'    => $validated['date'],
        'shift'           => $validated['shift'],

        'kapten'          => $validated['kapten'] ?? null,
        'gl'              => $validated['gl'] ?? null,
        'qa1'             => $validated['qa1'] ?? null,

        'note1'           => $validated['note1'] ?? null,
        'washing'         => $validated['washing'] ?? null,
        'note2'           => $validated['note2'] ?? null,
        'action_service'  => $validated['action_service'] ?? null,
        'note3'           => $validated['note3'] ?? null,

        'bays'            => $validated['bays'] ?? null,
        'action_backlog'  => $validated['action_backlog'] ?? null,
        'note4'           => $validated['note4'] ?? null,

        'rfu'             => $validated['rfu'] ?? null,
        'downtime_plan'   => $validated['downtime_plan'] ?? null,
        'downtime_actual' => $validated['downtime_actual'] ?? null,
        'note5'           => $validated['note5'] ?? null,
    ]);

    return redirect()
        ->route('monitoring.index')
        ->with('success', 'Service successfully updated');
}


public function handover(Service $service, Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $service->update([
        'status'      => 'handover',
        'shift'       => 2,
        'created_by'  => $request->user_id, // penerima handover
        'handover_by' => auth()->id(),       // pemberi handover
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Service handed over successfully',
    ]);
}


public function handoverUsers()
{
    return response()->json(
        User::where('role', '!=', 'admin')
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
    );
}
public function done(Service $service, Request $request)
{
    $service->update([
        'status' => 'done',
    ]);
    return response()->json(['success'=>true]);
}

public function showJson(Service $service)
{
    return response()->json([
        'id' => $service->id,
        'service_date' => optional($service->service_date)->format('Y-m-d'),
        'shift' => $service->shift,
        'kapten' => $service->kapten,
        'gl' => $service->gl,
        'qa1' => $service->qa1,
        'note1' => $service->note1,
        'washing' => $service->washing,
        'note2' => $service->note2,
        'action_service' => $service->action_service,
        'note3' => $service->note3,
        'bays' => $service->bays,
        'action_backlog' => $service->action_backlog,
        'note4' => $service->note4,
        'rfu' => $service->rfu,
        'downtime_plan' => optional($service->downtime_plan)->format('Y-m-d\TH:i'),
        'downtime_actual' => optional($service->downtime_actual)->format('Y-m-d\TH:i'),
        'note5' => $service->note5,
        'status' => $service->status,
        'unit' => [
            'id' => $service->unit->id,
            'code' => $service->unit->code,
        ],
    ]);
}
public function endJob(Service $service)
{
    // OPTIONAL: validasi status (recommended)
    if ($service->status !== 'handover') {
        return response()->json([
            'message' => 'Service cannot be ended'
        ], 422);
    }

    $service->update([
        'status'      => 'done',
        'finished_at' => now(), // kalau ada kolom ini
    ]);

    return response()->json([
        'success' => true
    ]);
}
public function updateTime(Request $request, Service $service)
{
    $request->validate([
        'field' => 'required|string',
        'value' => 'required|date_format:H:i',
    ]);

    $service->update([
        $request->field => $request->value . ':00'
    ]);

    return response()->json(['ok' => true]);
}

}
