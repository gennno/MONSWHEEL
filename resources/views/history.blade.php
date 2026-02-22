@extends('layouts.app')

@section('title', 'History - MONSWHEEL')

@section('topbar')
    <x-topbar />
@endsection

@section('content')
    <div class="p-4 space-y-6">
    <!-- HEADER ACTION -->
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold">Service History</h1>

        <!-- EXPORT BUTTON -->
        <a href="{{ route('history.export') }}"
           class="flex items-center gap-2 px-5 py-2 rounded-xl bg-green-600 text-white font-semibold">
            <i class="fa-solid fa-file-export"></i> Export
        </a>
    </div>
    <!-- TABLE -->
<div class="bg-gray-900 rounded-xl shadow-md">
    <div class="overflow-x-auto">
        <table id="historyTable" class="min-w-[1000px] w-full border-collapse table-fixed text-sm">

            <!-- HEADER -->
            <thead class="bg-gray-800 text-gray-300 uppercase text-xs">
                <tr>
                    <th class="w-14 px-4 py-3 border border-gray-700 text-center">#</th>
                    <th class="w-32 px-4 py-3 border border-gray-700 text-center">Date</th>
                    <th class="w-32 px-4 py-3 border border-gray-700 text-center">Unit</th>
                    <th class="w-24 px-4 py-3 border border-gray-700 text-center">GL</th>
                    <th class="w-32 px-4 py-3 border border-gray-700 text-center">Kapten</th>
                    <th class="w-24 px-4 py-3 border border-gray-700 text-center">Status</th>
                    <th class="w-24 px-4 py-3 border border-gray-700 text-center">Remark</th>
                    <th class="w-20 px-4 py-3 border border-gray-700 text-center">Action</th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody class="bg-gray-900 text-gray-200">
                @forelse ($histories as $index => $history)
                    <tr class="hover:bg-gray-800 transition">

                        <td class="px-4 py-3 border border-gray-700 text-center">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-4 py-3 border border-gray-700 text-center">
                            {{ $history->service_date?->format('d M Y') }}
                        </td>

                        <td class="px-4 py-3 border border-gray-700 text-center font-semibold">
                            {{ $history->unit->code ?? '-' }}
                        </td>

                        <td class="px-4 py-3 border border-gray-700 text-center">
                            {{ $history->gl ?? '-' }}
                        </td>

                        <td class="px-4 py-3 border border-gray-700 text-center">
                            {{ $history->kapten ?? '-' }}
                        </td>

                        <td class="px-4 py-3 border border-gray-700 text-center">
                            @php
                                $statusMap = [
                                    'done' => 'bg-green-600/20 text-green-400 ring-1 ring-green-500/40',
                                    'continue' => 'bg-yellow-600/20 text-yellow-300 ring-1 ring-yellow-400/40',
                                    'process' => 'bg-blue-600/20 text-blue-400 ring-1 ring-blue-500/40',
                                ];
                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                {{ $statusMap[$history->status] ?? 'bg-gray-600/20 text-gray-300' }}">
                                {{ $history->status }}
                            </span>
                        </td>

                        <td class="px-4 py-3 border border-gray-700 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                {{ $history->isOver() ? 'bg-red-600/20 text-red-400' :
                                   ($history->isOk() ? 'bg-green-600/20 text-green-400' :
                                   'bg-gray-600/20 text-gray-300') }}">
                                {{ $history->remark ?? '-' }}
                            </span>
                        </td>

                        <td class="px-4 py-3 border border-gray-700 text-center">
                            <form action="{{ route('history.destroy', $history) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this history?')">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 rounded-lg transition">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-500 border border-gray-700">
                            No history data found
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>



    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {

    $.fn.dataTable.ext.errMode = 'none';
    let table = $('#historyTable').DataTable({
        scrollX: true,
        autoWidth: false,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        pageLength: 10,
        order: [[1, 'desc']],
        columnDefs: [
            { orderable: false, targets: [0, 7] }
        ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ rows",
            info: "Showing _START_ to _END_ of _TOTAL_ records",
            zeroRecords: "No matching history found"
        }
    });

    // Dynamic numbering
    table.on('draw.dt', function () {
        let PageInfo = table.page.info();
        table.column(0, { page: 'current' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1 + PageInfo.start;
        });
    });

});
</script>


    <script>
        function confirmDeleteUnit(code) {
            return confirm(`Delete unit "${code}"?\nThis action cannot be undone.`);
        }
    </script>

@endsection