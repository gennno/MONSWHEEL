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
        // Only units with active services
        $units = Unit::whereHas('activeService')
            ->with('activeService') // eager load active service
            ->orderBy('code')
            ->get();

        return view('monitoring', compact('units'));
    }

    // Store new service
    public function store(Request $request)
    {
        $request->validate([
            'date'  => 'required|date',
            'cn'    => 'required|exists:units,code',
            'shift' => 'required|in:1,2',
        ]);

        DB::transaction(function () use ($request) {

            $unit = Unit::where('code', $request->cn)->firstOrFail();

            // 🔴 Check if there is an existing active service
            $oldService = Service::where('unit_id', $unit->id)
                ->whereIn('status', ['open', 'handover', 'on_process', 'done'])
                ->latest('created_at')
                ->first();

            if ($oldService) {
                // 🟡 Move old service to history
                ServiceHistory::create([
                    'unit_id'      => $oldService->unit_id,
                    'service_date' => $oldService->service_date,
                    'shift'        => $oldService->shift,
                    'status'       => $oldService->status,
                    'created_by'   => $oldService->created_by,
                    'finished_at'  => now(),
                    'data'         => $oldService->toArray(),
                ]);

                // 🔥 Delete old service
                $oldService->delete();
            }

            // 🟢 Create new service
            Service::create([
                'unit_id'         => $unit->id,
                'service_date'    => $request->date,
                'shift'           => $request->shift,
                'created_by'      => Auth::id(),
                'kapten'          => $request->kapten,
                'gl'              => $request->gl,
                'qa1'             => $request->qa1,
                'note1'           => $request->note1,
                'washing'         => $request->washing,
                'note2'           => $request->note2,
                'action_service'  => $request->action_service,
                'note3'           => $request->note3,
                'bays'            => $request->bays,
                'action_backlog'  => $request->action_backlog,
                'note4'           => $request->note4,
                'rfu'             => $request->rfu,
                'downtime_plan'   => $request->downtime_plan,
                'downtime_actual' => $request->downtime_actual,
                'note5'           => $request->note5,
                'status'          => 'open',
            ]);
        });

        return redirect()
            ->route('monitoring.index')
            ->with('success', 'Service berhasil ditambahkan');
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

}
