<style>
    /* FORCE TABLE FULL WIDTH */
    /* #serviceTable {
        width: 100% !important;
        table-layout: fixed;
    } */

    /* HEADER */
    #serviceTable thead th {
        font-size: 11px;
        padding: 6px 4px !important;
        text-align: center;
        vertical-align: middle;
        /* white-space: nowrap; */
    }

    /* BODY */
    #serviceTable tbody td {
        font-size: 12px;
        padding: 6px 4px !important;
        text-align: center;
        /* white-space: nowrap; */
    }

    /* STATUS BADGE SMALLER */
    /* #serviceTable .status-badge {
        font-size: 10px;
        padding: 2px 8px;
    } */

    /* BACKLOG ITEM WRAP */
/* #serviceTable .backlog-cell {
    white-space: normal !important;
    word-break: break-word;
    line-height: 1.3;
    text-align: left;     
    padding-left: 8px;
    padding-right: 8px;
} */

</style>
@php
    function actualCellClass($field, $last) {
        if (!$last) {
            return 'bg-green-600/20';
        }

        return $field === $last
            ? 'bg-green-600 text-white font-bold ring-2 ring-green-400'
            : 'bg-green-600/20';
    }
@endphp
    <div class="p-4 space-y-4">
<!-- DASHBOARD CARDS -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-6">

    <!-- DATE & TIME -->
    <div class="bg-gray-900 rounded-2xl px-6 py-6 text-center flex flex-col justify-center">
        <div class="text-gray-400 text-sm tracking-widest uppercase">
            Date & Time
        </div>
        <div class="text-3xl lg:text-4xl font-bold mt-2">
            {{ now()->format('d M Y') }}
        </div>
<div class="text-xl lg:text-2xl text-gray-300 mt-1">
    {{ now('UTC')->format('H:i') }}
</div>
    </div>

    <!-- TOTAL SERVICE -->
    <div class="bg-gray-900 rounded-2xl px-6 py-6 text-center flex flex-col justify-center">
        <div class="text-gray-400 text-sm tracking-widest uppercase">
            Total Service
        </div>
        <div class="text-4xl lg:text-5xl font-extrabold mt-3">
            {{ $totalService }}
        </div>
    </div>

    <!-- SERVICE MASUK -->
    <div class="bg-gray-900 rounded-2xl px-6 py-6 text-center flex flex-col justify-center">
        <div class="text-gray-400 text-sm tracking-widest uppercase">
            Service Masuk
        </div>
        <div class="text-4xl lg:text-5xl font-extrabold mt-3 text-yellow-400">
            {{ $totalServiceDone }}
        </div>
    </div>

    <!-- RFU -->
    <div class="bg-gray-900 rounded-2xl px-6 py-6 text-center flex flex-col justify-center">
        <div class="text-gray-400 text-sm tracking-widest uppercase">
            Unit RFU
        </div>
        <div class="text-4xl lg:text-5xl font-extrabold mt-3 text-green-400">
            {{ $rfuReady }}
        </div>
    </div>

</div>


        <!-- TABLE CONTAINER -->
        <div class="overflow-hidden rounded-2xl border border-gray-700 bg-gray-950">
            <table id="serviceTable" class="w-full border-collapse table-fixed">

                <thead class="bg-gray-900 text-gray-200 uppercase text-xs">

                    <!-- ROW 1 : MAIN HEADER -->
                    <tr>
                        <th rowspan="2" class="px-4 py-4 border border-gray-700 text-center">Date</th>
                        <th rowspan="2" class="px-4 py-4 border border-gray-700 text-center">CN</th>
                        <th rowspan="2" class="px-4 py-4 border border-gray-700 text-center">Status</th>
                        <th rowspan="2" class="px-4 py-4 border border-gray-700 text-center">GL</th>
                        <th rowspan="2" class="px-4 py-4 border border-gray-700 text-center">Kapten</th>
                        <th rowspan="2" class="px-4 py-4 border border-gray-700 text-center">Bays</th>
                        <th colspan="2" class="px-4 py-2 border border-gray-700 text-center">IN</th>
                        <th colspan="2" class="px-4 py-2 border border-gray-700 text-center">QA 1</th>
                        <th colspan="2" class="px-4 py-2 border border-gray-700 text-center">Washing</th>
                        <th colspan="2" class="px-4 py-2 border border-gray-700 text-center">Action Service</th>
                        <th colspan="2" class="px-4 py-2 border border-gray-700 text-center">Action Backlog</th>
                        <th colspan="2" class="px-4 py-2 border border-gray-700 text-center">QA 7</th>

                        <th colspan="2" class="px-4 py-2 border border-gray-700 text-center">Downtime</th>

                        <th rowspan="2" class="px-4 py-4 border border-gray-700 text-center">Remark</th>
                    </tr>

                    <!-- ROW 2 : PLAN / ACTUAL -->
                    <tr>
                        @for ($i = 0; $i < 7; $i++)
    <th class="px-2 py-2  border border-gray-700 text-center">Plan</th>
    <th class="px-2 py-2  border border-gray-700 text-center">Actual</th>
@endfor
                    </tr>

                </thead>
                <tbody class="text-sm font-semibold">
                    @forelse ($services as $service)
                        @php
                            $actualOrder = [
                                'in_actual',
                                'qa1_actual',
                                'washing_actual',
                                'action_service_actual',
                                'action_backlog_actual',
                                'qa7_actual',
                            ];

                            $lastActualField = null;

                            if ($service->status !== 'done') {
                                foreach ($actualOrder as $field) {
                                    if (!is_null($service->$field)) {
                                        $lastActualField = $field;
                                    }
                                }
                            }
                        @endphp
                        <tr class="border-b border-gray-900 even:bg-gray-800">
                            <td class="px-4 py-3 text-center border-r border-gray-700 ">
                                {{ $service->created_at->format('Y-m-d') }}
                            </td>
                            <!-- CN -->
                            <td class="px-4 py-3 text-center border-r border-gray-700 ">{{ $service->unit->code }}</td>

                            <td class="px-4 py-4 text-center border-r border-gray-700">
                                @php
                                    $status = $service->status;
                                    $map = [
                                        'plan' => 'bg-gray-500/20 text-gray-300',
                                        'process' => 'bg-blue-500/20 text-blue-300',
                                        'continue' => 'bg-yellow-500/20 text-yellow-300',
                                        'done' => 'bg-green-600/20 text-green-400',
                                    ];
                                @endphp

                                @if($status)
                                    <span class="status-badge px-3 py-1 rounded-full font-semibold uppercase
             {{ $map[$status] ?? 'bg-gray-500/20 text-gray-300' }}">
    {{ $status }}
</span>
                                @else
                                    -
                                @endif
                            </td>


                            <!-- GL -->
                            <td class="px-4 py-3 text-cente border-r border-gray-700">{{ $service->gl ?? '-' }}</td>

                            <!-- Kapten -->
                            <td class="px-4 py-3 text-center border-r border-gray-700">{{ $service->kapten ?? '-' }}</td>

                            <!-- Bays -->
                            <td class="px-4 py-3 text-center border-r border-gray-700">{{ $service->bays ?? '-' }}</td>

                            <!-- IN -->
                            <td class="px-2 py-3 text-center ">{{ $service->in_plan?->format('H:i') ?? '-' }}</td>
                            <td class="px-2 py-3 text-center  border-r border-gray-700
                                {{ actualCellClass('in_actual', $lastActualField) }}">
                                {{ $service->in_actual?->format('H:i') ?? '-' }}
                            </td>

                            <!-- QA 1 -->
                            <td class="px-2 py-3 text-center ">{{ $service->qa1_plan?->format('H:i') ?? '-' }}</td>
                            <td class="px-2 py-3 text-center  border-r border-gray-700
                                {{ actualCellClass('qa1_actual', $lastActualField) }}">
                                {{ $service->qa1_actual?->format('H:i') ?? '-' }}
                            </td>

                            <!-- Washing -->
                            <td class="px-2 py-3 text-center ">{{ $service->washing_plan?->format('H:i') ?? '-' }}</td>
                            <td class="px-2 py-3 text-center  border-r border-gray-700
                                {{ actualCellClass('washing_actual', $lastActualField) }}">
                                {{ $service->washing_actual?->format('H:i') ?? '-' }}
                            </td>

                            <!-- Action Service -->
                            <td class="px-2 py-3 text-center ">{{ $service->action_service_plan?->format('H:i') ?? '-' }}</td>
                            <td class="px-2 py-3 text-center  border-r border-gray-700
                                {{ actualCellClass('action_service_actual', $lastActualField) }}">
                                {{ $service->action_service_actual?->format('H:i') ?? '-' }}
                            </td>

                            <!-- Action Backlog -->
                            <td class="px-2 py-3 text-center ">{{ $service->action_backlog_plan?->format('H:i') ?? '-' }}</td>
                            <td class="px-2 py-3 text-center  border-r border-gray-700
                                {{ actualCellClass('action_backlog_actual', $lastActualField) }}">
                                {{ $service->action_backlog_actual?->format('H:i') ?? '-' }}
                            </td>

                            <!-- QA 7 -->
                            <td class="px-2 py-3 text-center ">{{ $service->qa7_plan?->format('H:i') ?? '-' }}</td>
                            <td class="px-2 py-3 text-center  border-r border-gray-700
                                {{ actualCellClass('qa7_actual', $lastActualField) }}">
                                {{ $service->qa7_actual?->format('H:i') ?? '-' }}
                            </td>

                            <!-- Downtime -->
<td class="px-2 py-3 text-center ">
    {{ $service->downtime_plan_formatted }}
</td>

<td class="px-3 py-3 text-center  bg-red-600/20 font-bold border-r border-gray-700">
    {{ $service->downtime_actual_formatted }}
</td>

                            

                            <!-- Remark -->
                            <td class="px-4 py-3 text-center">
                                @if($service->isOver())
                                    <span class="px-3 py-1 rounded bg-red-600 text-white">OVER</span>
                                @elseif($service->isOk())
                                    <span class="px-3 py-1 rounded bg-green-600 text-white">OK</span>
                                @else
                                    -
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="22" class="py-10 text-center text-gray-500">
                                No service data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>



        <!-- FOOTER INFO -->
        <div class="mt-6 flex justify-between text-gray-400 text-sm">
            <span>Updated every 5 seconds</span>
            <span>Status: LIVE MONITORING</span>
        </div>

    </div>