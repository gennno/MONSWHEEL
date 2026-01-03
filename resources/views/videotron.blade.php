@extends('layouts.app')

@section('title', 'Monitor - MONSWHEEL')

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
    <!-- TABLE CONTAINER -->
<div class="overflow-hidden rounded-2xl border border-gray-700 bg-gray-950">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">

            <!-- TABLE HEADER -->
            <thead class="bg-gray-900 text-gray-200 uppercase sticky top-0 z-10">
                <tr class="text-sm">
                    <th class="px-4 py-5 border-r border-gray-700">CN</th>
                    <th class="px-4 py-5 border-r border-gray-700">Kapten</th>
                    <th class="px-4 py-5 border-r border-gray-700">GL</th>
                    <th class="px-4 py-5 border-r border-gray-700">QA 1</th>
                    <th class="px-4 py-5 border-r border-gray-700">Washing</th>
                    <th class="px-4 py-5 border-r border-gray-700">Action Service</th>
                    <th class="px-4 py-5 border-r border-gray-700">Backlog</th>
                    <th class="px-4 py-5 border-r border-gray-700">QA 7</th>
                    <th class="px-4 py-5 border-r border-gray-700">RFU</th>

                    <!-- DOWNTIME GROUP -->
                    <th class="px-4 py-5 border-r border-gray-700 text-center" colspan="2">
                        Downtime Service
                    </th>

                    <th class="px-4 py-5">Remark</th>
                </tr>

                <tr class="text-xs bg-gray-900">
                    <th colspan="9"></th>
                    <th class="px-4 py-3 border-r border-gray-700">Plan</th>
                    <th class="px-4 py-3 border-r border-gray-700">Actual</th>
                    <th></th>
                </tr>
            </thead>

            <!-- TABLE BODY -->
            <tbody class="text-xl font-semibold">

                <!-- ROW -->
                <tr class="border-t border-gray-800 even:bg-gray-900/40">
                    <td class="py-5 text-center">DT7201</td>
                    <td class="text-center">MARIO</td>
                    <td class="text-center">GILANG</td>

                    <td class="text-green-400 text-center">07:15</td>
                    <td class="text-green-400 text-center">07:25</td>
                    <td class="text-yellow-400 text-center">08:30</td>
                    <td class="text-gray-500 text-center">—</td>
                    <td class="text-gray-500 text-center">—</td>

                    <td class="text-center">
                        <span class="px-4 py-1 rounded-full bg-blue-500/20 text-blue-400">
                            READY
                        </span>
                    </td>

                    <!-- DOWNTIME -->
                    <td class="text-center border-l border-gray-800">2h</td>
                    <td class="text-center text-red-400 font-bold">2h 30m</td>

                    <td class="text-yellow-300 text-center">
                        Delay Washing
                    </td>
                </tr>

                <tr class="border-t border-gray-800 even:bg-gray-700/40">
                    <td class="py-5 text-center">DT7100</td>
                    <td class="text-center">FUAD</td>
                    <td class="text-center">FAJAR</td>

                    <td class="text-green-400 text-center">07:30</td>
                    <td class="text-green-400 text-center">07:45</td>
                    <td class="text-yellow-400 text-center">08:30</td>
                    <td class="text-red-400 text-center">OPEN</td>
                    <td class="text-gray-500 text-center">—</td>

                    <td class="text-center">
                        <span class="px-4 py-1 rounded-full bg-gray-500/20 text-gray-300">
                            PROCESS
                        </span>
                    </td>

                    <td class="text-center border-l border-gray-800">3h</td>
                    <td class="text-center text-red-500 font-bold">4h</td>

                    <td class="text-red-300 text-center">
                        Backlog Part
                    </td>
                </tr>

                <tr class="border-t border-gray-800 even:bg-gray-900/40">
                    <td class="py-5 text-center">DT7302</td>
                    <td class="text-center">RIDHO</td>
                    <td class="text-center">ADIT</td>

                    <td class="text-green-400 text-center">08:00</td>
                    <td class="text-green-400 text-center">08:10</td>
                    <td class="text-green-400 text-center">08:40</td>
                    <td class="text-gray-500 text-center">—</td>
                    <td class="text-green-400 text-center">09:15</td>

                    <td class="text-center">
                        <span class="px-4 py-1 rounded-full bg-green-500/20 text-green-400">
                            READY
                        </span>
                    </td>

                    <td class="text-center border-l border-gray-800">1.5h</td>
                    <td class="text-center text-green-400 font-bold">1.3h</td>

                    <td class="text-green-300 text-center">
                        Normal
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>


    <!-- FOOTER INFO -->
    <div class="mt-6 flex justify-between text-gray-400 text-sm">
        <span>Updated every 10 seconds</span>
        <span>Status: LIVE MONITORING</span>
    </div>

</div>
@endsection
