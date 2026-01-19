@extends('layouts.app')

@section('title', 'Videotron - MONSWHEEL')

@section('content')
    <div class="min-h-screen bg-black text-white p-6">

        <!-- PAGE HEADER -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold tracking-wide">
                MONSWHEEL UNIT MONITORING
            </h1>
            <div class="text-lg text-gray-300">
                {{ now()->format('d M Y • H:i') }}
            </div>
        </div>

        <!-- TABLE CONTAINER -->
        <div class="overflow-hidden rounded-2xl border border-gray-700 bg-gray-950">
            <table id="serviceTable" class="w-full min-w-[1400px] border-collapse">

                <thead class="bg-gray-900 text-gray-200 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-4 border-r border-b border-gray-700">CN</th>
                        <th class="px-4 py-4 border-r border-b border-gray-700">Kapten</th>
                        <th class="px-4 py-4 border-r border-b border-gray-700">GL</th>
                        <th class="px-4 py-4 border-r border-b border-gray-700">Unit Masuk</th>
                        <th class="px-4 py-4 border-r border-b border-gray-700">QA 1</th>
                        <th class="px-4 py-4 border-r border-b border-gray-700">Washing</th>
                        <th class="px-4 py-4 border-r border-b border-gray-700">Action Service</th>
                        <th class="px-4 py-4 border-r border-b border-gray-700">Action Backlog</th>
                        <th class="px-4 py-4 border-r border-b border-gray-700">QA 7</th>
                        <th class="px-4 py-4 border-r border-b border-gray-700">RFU</th>
                        <th class="px-4 py-4 border-r border-b border-gray-700">DT Plan</th>
                        <th class="px-4 py-4 border-r border-b border-gray-700">DT Actual</th>
                        <th class="px-4 py-4 border-b border-gray-700">Remark</th>
                    </tr>
                </thead>

                <tbody class="text-sm font-semibold">
                    @forelse ($services as $service)
                        <tr class="border-b border-gray-800 even:bg-gray-900/40">
                            <td class="px-4 py-4 text-center">{{ $service->unit->code }}</td>
                            <td class="px-4 py-4 text-center">{{ $service->kapten ?? '-' }}</td>
                            <td class="px-4 py-4 text-center">{{ $service->gl ?? '-' }}</td>
                            <td class="px-4 py-4 text-center">{{ $service->unit->code }}</td>
                            <td class="px-4 py-4 text-center">{{ $service->qa1 }}</td>
                            <td class="px-4 py-4 text-center">
                                {{ $service->washing === 'yes' ? 'YES' : 'NO' }}
                            </td>
                            <td class="px-4 py-4 text-center text-yellow-400">
                                {{ $service->action_service ?? '—' }}
                            </td>
                            <td class="px-4 py-4 text-center text-gray-400">
                                {{ $service->action_backlog ?? '—' }}
                            </td>
                            <td class="px-4 py-4 text-center text-gray-400">
                                {{ $service->qa1 ?? '—' }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($service->rfu === 'ready')
                                    <span class="px-3 py-1 rounded-full bg-green-500/20 text-green-400">
                                        READY
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-gray-500/20 text-gray-300">
                                        PROCESS
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center text-blue-400">
                                {{ $service->downtime_plan?->diffForHumans(null, true) ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-center text-red-400 font-bold">
                                {{ $service->downtime_actual?->diffForHumans(null, true) ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-center text-yellow-300 max-w-[120px] truncate">
                                {{ $service->note5 ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="py-10 text-center text-gray-500">
                                No service data today
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>



        <!-- FOOTER INFO -->
        <div class="mt-6 flex justify-between text-gray-400 text-sm">
            <span>Updated every 10 seconds</span>
            <span>Status: LIVE MONITORING</span>
        </div>

    </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#serviceTable').DataTable({
                scrollX: true,
                autoWidth: false,
                paging: true,
                searching: false,
                ordering: true,
                info: true,
                pageLength: 15,
                lengthChange: false, 

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