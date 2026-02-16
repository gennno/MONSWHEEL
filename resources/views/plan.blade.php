@extends('layouts.app')

@section('title', 'Plan - MONSWHEEL')

@section('topbar')
    <x-topbar />
@endsection

@section('content')
    <div class="p-4 space-y-6">

        <!-- HEADER ACTION -->
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold">Plan List</h1>

            <!-- ADD Plan BUTTON -->
            <button onclick="openAddPlan()"
                class="flex items-center gap-2 px-5 py-2 rounded-xl bg-green-600 text-white font-semibold">
                <i class="fa-solid fa-plus"></i> Add Plan
            </button>


        </div>
        <!-- Plan TABLE -->
        <div class="bg-gray-900 rounded-xl shadow-md">

            <!-- MOBILE SAFE SCROLL -->
            <div class="overflow-x-auto">
                <table id="PlanTable" class="min-w-[700px] w-full text-lg">
                    <thead class="bg-gray-800 text-gray-300">
                        <tr>
                            <th class="px-4 py-4 border border-gray-700 text-center">#</th>
                            <th class="px-4 py-4 border border-gray-700 text-center">Date</th>
                            <th class="px-4 py-4 border border-gray-700 text-center">CN</th>
                            <th class="px-4 py-4 border border-gray-700 text-center">Plan Start Time</th>
                            <th class="px-4 py-4 border border-gray-700 text-center">QA 1</th>
                            <th class="px-4 py-4 border border-gray-700 text-center">Washing</th>
                            <th class="px-4 py-4 border border-gray-700 text-center">Action Service</th>
                            <th class="px-4 py-4 border border-gray-700 text-center">Action Backlog</th>
                            <th class="px-4 py-4 border border-gray-700 text-center">Backlog Unit</th>
                            <th class="px-4 py-4 border border-gray-700 text-center">QA 7</th>
                            <th class="px-4 py-4 border border-gray-700 text-center">Total Downtime</th>
                            <th class="px-4 py-4 border border-gray-700 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-800">
                        @forelse ($services as $index => $service)
                            <tr class="hover:bg-gray-800 transition">

                                <td class="px-3 py-3 text-center border-r border-gray-700">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-center border-r border-gray-700 ">
                                    {{ $service->service_date->format('Y-m-d') }}
                                </td>

                                <td class="px-3 py-3 font-semibold border-r border-gray-700 text-center">
                                    {{ $service->unit->code ?? '-' }}
                                </td>

                                <td class="px-3 py-3 border-r border-gray-700 text-center">
                                    {{ $service->in_plan?->format('H:i') ?? '-' }}
                                </td>
                                <td class="px-3 py-3 border-r border-gray-700 text-center">
                                    {{ $service->qa1_plan?->format('H:i') ?? '-' }}
                                </td>
                                <td class="px-3 py-3 border-r border-gray-700 text-center">
                                    {{ $service->washing_plan?->format('H:i') ?? '-' }}
                                </td>
                                <td class="px-3 py-3 border-r border-gray-700 text-center">
                                    {{ $service->action_service_plan?->format('H:i') ?? '-' }}
                                </td>
                                <td class="px-3 py-3 border-r border-gray-700 text-center">
                                    {{ $service->action_backlog_plan?->format('H:i') ?? '-' }}
                                </td>
                                <td class="px-3 py-3 border-r border-gray-700 text-center">
                                    {{ $service->backlog_item ?? '-' }}
                                </td>
                                <td class="px-3 py-3 border-r border-gray-700 text-center">
                                    {{ $service->qa7_plan?->format('H:i') ?? '-' }}
                                </td>
                                <td class="px-3 py-3 border-r border-gray-700 text-center">
                                    {{ $service->downtime_plan ?? '-' }}
                                </td>


                                <td class="px-3 py-3 border-r border-gray-700 text-center">
                                    <div class="flex justify-center gap-2">

                                        {{-- EDIT --}}
                                        @if(auth()->user()->role === 'admin')
                                            <button onclick="openEditPlan({{ $service->id }})"
                                                class="p-2 bg-yellow-600/20 text-yellow-400 rounded-lg">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                        @endif

                                        {{-- DELETE --}}
                                        @if(auth()->user()->role === 'admin')
                                            <form action="{{ route('plans.destroy', $service->id) }}" method="POST"
                                                onsubmit="return confirmDeletePlan('{{ $service->unit->code ?? 'this plan' }}')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="p-2 bg-red-600/20 text-red-400 rounded-lg">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>


                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-gray-500">
                                    No planned services found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>


                </table>
            </div>

        </div>

        <div id="addPlanModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

            <div class="bg-gray-900 rounded-xl w-full max-w-3xl p-6" onclick="event.stopPropagation()">

                <h3 class="text-lg font-semibold mb-4">
                    Add Plan Service
                </h3>

                <form method="POST" action="{{ route('plans.store') }}" id="planForm">
                    @csrf

                    <!-- ROW 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

                        <div>
                            <label class="text-sm text-gray-400">Service Date</label>
                            <input type="date" name="service_date" required
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Unit</label>
                            <select name="unit_id" required
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                                <option value="">-- Select Unit --</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">
                                        {{ $unit->code }} - {{ $unit->type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Backlog Item</label>
                            <input name="backlog_item"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                    </div>

                    <!-- ROW 2 -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">

                        <div>
                            <label class="text-sm text-gray-400">IN Plan</label>
                            <input id="in_plan" type="time" name="in_plan" step="60"
                                class="time-input w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">QA1 Plan</label>
                            <input type="time" name="qa1_plan" id="qa1_plan" step="60"
                                class="time-input w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Washing Plan</label>
                            <input type="time" name="washing_plan" step="60"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Action Service</label>
                            <input type="time" name="action_service_plan" step="60"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                    </div>

                    <!-- ROW 3 -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">

                        <div>
                            <label class="text-sm text-gray-400">Action Backlog</label>
                            <input type="time" name="action_backlog_plan" step="60"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">QA7 Plan</label>
                            <input type="time" name="qa7_plan" id="qa7_plan" step="60"
                                class="time-input w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Downtime (minutes)</label>
                            <input type="number" id="downtime_plan"
                                class="w-full rounded-lg bg-gray-700 border border-gray-700 px-3 py-2" readonly>
                        </div>

                    </div>

                    <!-- hidden -->
                    <input type="hidden" name="status" value="plan">
                    <input type="hidden" name="downtime_plan" id="downtime_plan_hidden">

                    <!-- ACTION -->
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" onclick="closeAddPlan()" class="px-4 py-2 bg-gray-700 rounded-lg">
                            Cancel
                        </button>

                        <button class="px-4 py-2 bg-green-600 rounded-lg text-black font-semibold">
                            Save Plan
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <div id="editPlanModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

            <div class="bg-gray-900 rounded-xl w-full max-w-3xl p-6" onclick="event.stopPropagation()">

                <h3 class="text-lg font-semibold mb-4">
                    Edit Plan Service
                </h3>

                <form method="POST" id="editPlanForm">
                    @csrf
                    @method('PUT')

                    <!-- ROW 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

                        <div>
                            <label class="text-sm text-gray-400">Service Date</label>
                            <input type="date" name="service_date" required
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Unit</label>
                            <select name="unit_id" required
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                                <option value="">-- Select Unit --</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">
                                        {{ $unit->code }} - {{ $unit->type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Backlog Item</label>
                            <input name="backlog_item"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                    </div>

                    <!-- ROW 2 -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">

                        <div>
                            <label class="text-sm text-gray-400">IN Plan</label>
                            <input id="in_plan" type="time" name="in_plan" step="60"
                                class="time-input w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">QA1 Plan</label>
                            <input type="time" name="qa1_plan" id="qa1_plan" step="60"
                                class="time-input w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Washing Plan</label>
                            <input type="time" name="washing_plan" step="60"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Action Service</label>
                            <input type="time" name="action_service_plan" step="60"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                    </div>

                    <!-- ROW 3 -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">

                        <div>
                            <label class="text-sm text-gray-400">Action Backlog</label>
                            <input type="time" name="action_backlog_plan" step="60"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">QA7 Plan</label>
                            <input type="time" name="qa7_plan" id="qa7_plan" step="60"
                                class="time-input w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Downtime (minutes)</label>
                            <input type="number" id="edit_downtime_plan"
                                class="w-full rounded-lg bg-gray-700 border border-gray-700 px-3 py-2" readonly>
                        </div>

                    </div>

                    <!-- hidden -->
                    <input type="hidden" name="status" value="plan">
                    <input type="hidden" name="downtime_plan" id="edit_downtime_plan_hidden">

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" onclick="closeEditPlan()" class="px-4 py-2 bg-gray-700 rounded-lg">
                            Cancel
                        </button>

                        <button class="px-4 py-2 bg-yellow-600 rounded-lg text-black font-semibold">
                            Update Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>


    </div>
    <script>
        function openAddPlan() {
            toggleModal('addPlanModal', true)
        }
        function closeAddPlan() {
            toggleModal('addPlanModal', false)
        }

        async function viewPlan(id) {
            const res = await fetch(`/Plans/${id}`);
            const u = await res.json();

            fillPlanForm(u, false);
        }

        async function editPlan(id) {
            const res = await fetch(`/Plans/${id}`);
            const u = await res.json();

            fillPlanForm(u, true);
        }

        function fillPlanForm(u, editable) {
            document.getElementById('PlanModalTitle').innerText =
                editable ? 'Edit Plan' : 'View Plan';

            document.getElementById('Plan_code').value = u.code;
            document.getElementById('Plan_type').value = u.type ?? '';
            document.getElementById('Plan_status').value = u.status;

            ['Plan_code', 'Plan_type', 'Plan_status']
                .forEach(id => document.getElementById(id).disabled = !editable);

            const actions = document.getElementById('PlanModalActions');
            actions.innerHTML = editable
                ? `
                            <button
                                type="button"
                                onclick="closePlanModal()"
                                class="px-4 py-2 bg-gray-700 rounded-lg">
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="px-4 py-2 bg-yellow-600 rounded-lg text-black font-semibold">
                                Update
                            </button>
                          `
                : `
                            <button
                                type="button"
                                onclick="closePlanModal()"
                                class="px-4 py-2 bg-gray-700 rounded-lg">
                                Close
                            </button>
                          `;


            document.getElementById('PlanForm').action = `/Plans/${u.id}`;
            toggleModal('PlanModal', true);
        }

        function closePlanModal() {
            toggleModal('PlanModal', false);
        }

        function toggleModal(id, show) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden', !show);
            el.classList.toggle('flex', show);
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            
        $.fn.dataTable.ext.errMode = 'none';
            $('#PlanTable').DataTable({
                scrollX: true,
                autoWidth: false,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50, 100],

                order: [[3, 'asc']], // default sort by "Plan Masuk"

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

    <script>
        function confirmDeletePlan(code) {
            return confirm(`Delete Plan "${code}"?\nThis action cannot be undone.`);
        }
    </script>
    <script>
        function timeToMinutes(time) {
            if (!time) return null;
            const [h, m] = time.split(':').map(Number);
            return h * 60 + m;
        }

        function calculateDowntime() {

            const steps = [
                document.querySelector('[name="in_plan"]')?.value,
                document.querySelector('[name="qa1_plan"]')?.value,
                document.querySelector('[name="washing_plan"]')?.value,
                document.querySelector('[name="action_service_plan"]')?.value,
                document.querySelector('[name="action_backlog_plan"]')?.value,
                document.querySelector('[name="qa7_plan"]')?.value,
            ];

            // convert to minutes, skip empty
            const times = steps
                .map(timeToMinutes)
                .filter(t => t !== null);

            if (times.length < 2) return;

            let total = 0;
            let prev = times[0];

            for (let i = 1; i < times.length; i++) {
                let current = times[i];

                // ⏭️ LINTAS HARI
                if (current <= prev) {
                    current += 24 * 60;
                }

                total += current - prev;
                prev = current;
            }

            document.getElementById('downtime_plan').value = total;
            document.getElementById('downtime_plan_hidden').value = total;
        }

        // realtime update
        document.querySelectorAll('input[type="time"]')
            .forEach(el => el.addEventListener('change', calculateDowntime));
    </script>

    <script>
        async function openEditPlan(id) {
            const res = await fetch(`/plans/${id}`);
            const data = await res.json();

            const form = document.getElementById('editPlanForm');
            form.action = `/plans/${id}`;

            for (const key in data) {
                const input = form.querySelector(`[name="${key}"]`);
                if (!input) continue;

                if (input.type === 'time' && data[key]) {
                    input.value = data[key].substring(0, 5); // HH:mm
                } else if (input.type === 'date' && data[key]) {
                    input.value = data[key].substring(0, 10);
                } else {
                    input.value = data[key] ?? '';
                }
            }

            document.getElementById('edit_downtime_plan').value = data.downtime_plan ?? 0;
            document.getElementById('edit_downtime_plan_hidden').value = data.downtime_plan ?? 0;

            toggleModal('editPlanModal', true);
            bindEditDowntime();
        }



        function closeEditPlan() {
            toggleModal('editPlanModal', false);
        }
    </script>

    <script>
        function calculateEditDowntime() {
            const form = document.getElementById('editPlanForm');

            const steps = [
                form.querySelector('[name="in_plan"]')?.value,
                form.querySelector('[name="qa1_plan"]')?.value,
                form.querySelector('[name="washing_plan"]')?.value,
                form.querySelector('[name="action_service_plan"]')?.value,
                form.querySelector('[name="action_backlog_plan"]')?.value,
                form.querySelector('[name="qa7_plan"]')?.value,
            ];

            const times = steps
                .map(t => t ? timeToMinutes(t) : null)
                .filter(t => t !== null);

            if (times.length < 2) return;

            let total = 0;
            let prev = times[0];

            for (let i = 1; i < times.length; i++) {
                let cur = times[i];
                if (cur <= prev) cur += 1440; // lintas hari
                total += cur - prev;
                prev = cur;
            }

            document.getElementById('edit_downtime_plan').value = total;
            document.getElementById('edit_downtime_plan_hidden').value = total;
        }

        document.querySelectorAll('#editPlanForm input[type="time"]')
            .forEach(el => el.addEventListener('change', calculateEditDowntime));
    </script>


@endsection