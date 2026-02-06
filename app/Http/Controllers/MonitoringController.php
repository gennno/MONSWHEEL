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
        ->with('unit')
        ->firstOrFail();

    // Update service
    $service->update([
        'gl' => $validated['gl'] ?? null,
        'kapten' => $validated['kapten'] ?? null,
        'bays' => $validated['bays'] ?? null,

        'in_actual' => $validated['in_actual'] ?? null,
        'qa1_actual' => $validated['qa1_actual'] ?? null,
        'washing_actual' => $validated['washing_actual'] ?? null,
        'action_service_actual' => $validated['action_service_actual'] ?? null,
        'action_backlog_actual' => $validated['action_backlog_actual'] ?? null,
        'qa7_actual' => $validated['qa7_actual'] ?? null,

        'status' => 'process',
    ]);

    // 🔥 IMPORTANT: set unit status to service
    if ($service->unit && $service->unit->isActive()) {
        $service->unit->update([
            'status' => 'service',
        ]);
    }

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


public function handover(Request $request, Service $service)
{
    $data = $request->validate([
        'gl' => 'nullable|string|max:255',
        'kapten' => 'nullable|string|max:255',
    ]);

    $service->update([
        'gl' => $data['gl'],
        'kapten' => $data['kapten'],
        'status' => 'continue',
    ]);

    return response()->json([
        'message' => 'Handover success, status set to continue',
        'status' => $service->status,
    ]);
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

        // BASIC
        'service_date' => optional($service->service_date)->format('Y-m-d'),
        'shift' => $service->shift,
        'kapten' => $service->kapten,
        'gl' => $service->gl,
        'bays' => $service->bays,
        'status' => $service->status,

        // TIME LOG (FOR <input type="time">)
        'in_actual' => optional($service->in_actual)->format('H:i'),
        'qa1_actual' => optional($service->qa1_actual)->format('H:i'),
        'washing_actual' => optional($service->washing_actual)->format('H:i'),
        'action_service_actual' => optional($service->action_service_actual)->format('H:i'),
        'action_backlog_actual' => optional($service->action_backlog_actual)->format('H:i'),
        'qa7_actual' => optional($service->qa7_actual)->format('H:i'),


        // UNIT
        'unit' => [
            'id' => $service->unit->id,
            'code' => $service->unit->code,
        ],
    ]);
}

public function endJob(Service $service)
{
    // Prevent double end
    if ($service->status === 'done') {
        return response()->json([
            'message' => 'Service already completed'
        ], 409);
    }

    // Collect actual timestamps (washing is optional)
    $times = collect([
        $service->in_actual,
        $service->qa1_actual,
        $service->washing_actual,
        $service->action_service_actual,
        $service->action_backlog_actual,
        $service->qa7_actual,
    ])->filter();

    $downtimeActualMinutes = 0;
    $timeValues = $times->values();

    for ($i = 0; $i < $timeValues->count() - 1; $i++) {
        $start = Carbon::parse($timeValues[$i]);
        $end   = Carbon::parse($timeValues[$i + 1]);

        if ($end->greaterThan($start)) {
            $downtimeActualMinutes += $start->diffInMinutes($end);
        }
    }

    $downtimePlanMinutes = (int) $service->downtime_plan;

    $remark = $downtimeActualMinutes > $downtimePlanMinutes
        ? 'over'
        : 'ok';

    $service->update([
        'downtime_actual' => $downtimeActualMinutes,
        'remark' => $remark,
        'status' => 'done',
        'completed_at' => now(),
    ]);

    return response()->json([
        'message' => 'Job completed',
        'status' => 'done',
        'downtime_actual' => $downtimeActualMinutes,
        'remark' => $remark,
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
