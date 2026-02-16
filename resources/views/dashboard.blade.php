@extends('layouts.app')

@section('title', 'Dashboard - MONSWHEEL')

@section('topbar')
    <x-topbar />
@endsection

@section('content')
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
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-900 rounded-xl p-4 text-center">
                <div class="text-gray-400 text-sm">TOTAL UNIT</div>
                <div class="text-2xl font-bold">{{ $totalUnit }}</div>
            </div>
            <div class="bg-gray-900 rounded-xl p-4 text-center">
                <div class="text-gray-400 text-sm">JUMLAH UNIT SERVICE ALL</div>
                <div class="text-2xl font-bold">{{ $totalService }}</div>
            </div>
            <div class="bg-gray-900 rounded-xl p-4 text-center">
                <div class="text-gray-400 text-sm">JUMLAH UNIT SERVICE YANG MASUK</div>
                <div class="text-2xl font-bold">{{ $totalServiceDone }}</div>
            </div>
            <div class="bg-gray-900 rounded-xl p-4 text-center">
                <div class="text-gray-400 text-sm">JUMLAH UNIT RFU</div>
                <div class="text-2xl font-bold text-green-400">{{ $rfuReady }}</div>
            </div>
        </div>

        <!-- TABLE CONTAINER -->
        <div class="overflow-hidden rounded-2xl border border-gray-700 bg-gray-950">
            <table id="serviceTable" class="w-full min-w-[1400px] border-collapse">

                <thead class="bg-gray-900 text-gray-200 uppercase text-xs">

                    <!-- ROW 1 : MAIN HEADER -->
                    <tr>
                        <th rowspan="2" class="px-4 py-4 border border-gray-700 text-center">Date</th>
                        <th rowspan="2" class="px-4 py-4 border border-gray-700 text-center">CN</th>
                        <th rowspan="2" class="px-4 py-4 border border-gray-700 text-center">Status</th>
                        <th rowspan="2" class="px-4 py-4 border border-gray-700 text-center">GL</th>
                        <th rowspan="2" class="px-4 py-4 border border-gray-700 text-center">Backlog Item</th>
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
                            <th class="px-3 py-2 border border-gray-700 text-center">Plan</th>
                            <th class="px-3 py-2 border border-gray-700 text-center">Actual</th>
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
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase
                                                            {{ $map[$status] ?? 'bg-gray-500/20 text-gray-300' }}">
                                        {{ $status }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>


                            <!-- GL -->
                            <td class="px-4 py-3 text-cente border-r border-gray-700">{{ $service->gl ?? '-' }}</td>

                            <!-- Backlog -->
                            <td class="px-4 py-3 text-center border-r border-gray-700">{{ $service->backlog_item ?? '-' }}</td>

                            <!-- Kapten -->
                            <td class="px-4 py-3 text-center border-r border-gray-700">{{ $service->kapten ?? '-' }}</td>

                            <!-- Bays -->
                            <td class="px-4 py-3 text-center border-r border-gray-700">{{ $service->bays ?? '-' }}</td>

                            <!-- IN -->
                            <td class="px-3 py-3 text-center ">{{ $service->in_plan?->format('H:i') ?? '-' }}</td>
                            <td class="px-3 py-3 text-center border-r border-gray-700
                                {{ actualCellClass('in_actual', $lastActualField) }}">
                                {{ $service->in_actual?->format('H:i') ?? '-' }}
                            </td>

                            <!-- QA 1 -->
                            <td class="px-3 py-3 text-center">{{ $service->qa1_plan?->format('H:i') ?? '-' }}</td>
                            <td class="px-3 py-3 text-center border-r border-gray-700
                                {{ actualCellClass('qa1_actual', $lastActualField) }}">
                                {{ $service->qa1_actual?->format('H:i') ?? '-' }}
                            </td>

                            <!-- Washing -->
                            <td class="px-3 py-3 text-center">{{ $service->washing_plan?->format('H:i') ?? '-' }}</td>
                            <td class="px-3 py-3 text-center border-r border-gray-700
                                {{ actualCellClass('washing_actual', $lastActualField) }}">
                                {{ $service->washing_actual?->format('H:i') ?? '-' }}
                            </td>

                            <!-- Action Service -->
                            <td class="px-3 py-3 text-center">{{ $service->action_service_plan?->format('H:i') ?? '-' }}</td>
                            <td class="px-3 py-3 text-center border-r border-gray-700
                                {{ actualCellClass('action_service_actual', $lastActualField) }}">
                                {{ $service->action_service_actual?->format('H:i') ?? '-' }}
                            </td>

                            <!-- Action Backlog -->
                            <td class="px-3 py-3 text-center">{{ $service->action_backlog_plan?->format('H:i') ?? '-' }}</td>
                            
                            <td class="px-3 py-3 text-center border-r border-gray-700
                                {{ actualCellClass('action_backlog_actual', $lastActualField) }}">
                                {{ $service->action_backlog_actual?->format('H:i') ?? '-' }}
                            </td>

                            <!-- QA 7 -->
                            <td class="px-3 py-3 text-center">{{ $service->qa7_plan?->format('H:i') ?? '-' }}</td>
                            <td class="px-3 py-3 text-center border-r border-gray-700
                                {{ actualCellClass('qa7_actual', $lastActualField) }}">
                                {{ $service->qa7_actual?->format('H:i') ?? '-' }}
                            </td>

                            <!-- Downtime -->
<td class="px-3 py-3 text-center">
    {{ $service->downtime_plan_formatted }}
</td>

<td class="px-3 py-3 text-center bg-red-600/20 font-bold border-r border-gray-700">
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
        <!-- ACTION BUTTONS -->
        <div class="flex justify-center sm:justify-end gap-3 mt-4 flex-wrap">

            <!-- DOWNLOAD BUTTON -->
            <a href="{{ route('dashboard.download') }}" class="w-full sm:w-auto
                              flex items-center justify-center gap-2
                              px-6 py-3 rounded-xl
                              bg-emerald-600 hover:bg-emerald-500
                              text-white font-semibold
                              transition duration-200 shadow-lg">
                <i class="fa-solid fa-download"></i>
                Download
            </a>

            <!-- SHOW VIDEOTRON BUTTON -->
            <a target="_blank" href="{{ url('/videotron') }}" class="w-full sm:w-auto
                              flex items-center justify-center gap-2
                              px-6 py-3 rounded-xl
                              bg-blue-600 hover:bg-blue-500
                              text-white font-semibold
                              transition duration-200 shadow-lg">
                <i class="fa-solid fa-tv"></i>
                Show Videotron
            </a>

        </div>




    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <style>
        table.dataTable thead th {
            text-align: center !important;
            vertical-align: middle !important;
        }
    </style>

    <script>
        $(document).ready(function () {
            $.fn.dataTable.ext.errMode = 'none';
            $('#serviceTable').DataTable({
                scrollX: true,
                autoWidth: false,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],

                order: [[3, 'asc']], // default sort by "Unit Masuk"

                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ rows",
                    info: "Showing _START_ to _END_ of _TOTAL_ services",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    },
                    zeroRecords: "No matching service found"
                }
            });
        });
    </script>

@endsection