@extends('layouts.app')

@section('title', 'Monitor - MONSWHEEL')

@section('topbar')
    <x-topbar />
@endsection

@section('content')
    <div class="p-4 space-y-4">

        <!-- HEADER ACTION -->
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold">Monitoring Service</h1>
        </div>

        <!-- UNIT CARDS -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-4">

            <!-- ADD SERVICE CARD -->
            <div id="addServiceCard" class="bg-gray-900 rounded-xl p-4 cursor-pointer
                            flex flex-col items-center justify-center gap-3
                            border-2 border-dashed border-green-500
                            hover:bg-gray-800 hover:border-green-400
                            transition text-center">
                <div class="w-20 h-20 flex items-center justify-center rounded-full
                                bg-green-600/20 text-green-400 text-4xl">
                    <i class="fa-solid fa-plus"></i>
                </div>

                <div class="text-lg font-bold text-green-400">
                    Add Service
                </div>
            </div>

            @foreach ($units as $unit)
                @php
                    $service = $unit->activeService;
                    $shift = $service?->shift;
                    $status = $service?->status ?? null;

                    $shiftClass = match ($shift) {
                        1 => 'bg-blue-600',
                        2 => 'bg-purple-600',
                        default => 'bg-gray-600',
                    };

                    $statusRingClass = match ($status) {
                        'open' => 'hover:ring-yellow-500',
                        'handover' => 'hover:ring-red-500',
                        'on_process' => 'hover:ring-blue-500',
                        'done' => 'hover:ring-green-500',
                        default => 'hover:ring-gray-600',
                    };

                    $statusBadgeClass = match ($status) {
                        'open' => 'bg-green-600/20 text-yellow-400',
                        'handover' => 'bg-yellow-600/20 text-red-400',
                        'on_process' => 'bg-blue-600/20 text-blue-400',
                        'done' => 'bg-gray-600/20 text-green-400',
                        default => 'bg-gray-700 text-gray-300',
                    };
                @endphp

                <div class="unit-card relative bg-gray-900 rounded-xl p-4 cursor-pointer
                                             hover:bg-gray-800 hover:ring-2 {{ $statusRingClass }}
                                             transition text-center flex flex-col items-center gap-3"
                    data-unit="{{ $unit->code }}" data-service-id="{{ $service?->id }}" data-status="{{ $status }}"
                    data-shift="{{ $shift }}">

                    {{-- SHIFT TAG --}}
                    @if ($shift)
                        <span class="absolute top-2 right-2 z-10
                                                                            text-xs font-semibold px-2 py-0.5 rounded-full text-white
                                                                            {{ $shiftClass }}">
                            Shift {{ $shift }}
                        </span>
                    @endif

                    {{-- STATUS BADGE --}}
                    @if ($status)
                        <span
                            class="absolute top-2 left-2 z-10
                                                                            text-xs font-semibold px-2 py-0.5 rounded-full {{ $statusBadgeClass }}">
                            {{ strtoupper(str_replace('_', ' ', $status)) }}
                        </span>
                    @endif

                    {{-- UNIT IMAGE --}}
                    <img src="{{ asset('img/monswheel.png') }}" class="w-20 h-20 object-contain drop-shadow-md">

                    {{-- UNIT CODE --}}
                    <div class="text-xl font-bold tracking-wider">
                        {{ $unit->code }}
                    </div>
                </div>
            @endforeach
        </div>




        <!-- ADD SERVICE MODAL -->
        <div id="addServiceModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
            <div class="bg-gray-900 rounded-2xl w-full max-w-3xl p-6 relative max-h-[90vh] overflow-y-auto">

                <h2 class="text-lg font-bold mb-4">Add Service</h2>

                <form method="POST" action="{{ route('monitoring.service.store') }}" class="space-y-5">
                    @csrf

                    <!-- DATE & UNIT -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-400">Date</label>
                            <input type="date" name="date"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">CN / Code Unit</label>
                            <input type="text" name="cn"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                    </div>

                    <!-- PERSON IN CHARGE -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm text-gray-400">Kapten</label>
                            <input type="text" name="kapten"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">GL</label>
                            <input type="text" name="gl"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">QA1</label>
                            <input type="text" name="qa1"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Shift</label>
                        <select name="shift" class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                            <option value="">-- Select --</option>
                            <option value="1">Shift 1</option>
                            <option value="2">Shift 2</option>
                        </select>
                    </div>

                    <!-- NOTE 1 -->
                    <div>
                        <label class="text-sm text-gray-400">Note 1</label>
                        <textarea name="note1" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- WASHING -->
                    <div>
                        <label class="text-sm text-gray-400">Washing</label>
                        <select name="washing" class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                            <option value="">-- Select --</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>

                    <!-- NOTE 2 -->
                    <div>
                        <label class="text-sm text-gray-400">Note 2</label>
                        <textarea name="note2" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- ACTION SERVICE -->
                    <div>
                        <label class="text-sm text-gray-400">Action Service</label>
                        <textarea name="action_service" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- NOTE 3 -->
                    <div>
                        <label class="text-sm text-gray-400">Note 3</label>
                        <textarea name="note3" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- BAYS -->
                    <div>
                        <label class="text-sm text-gray-400">Bays</label>
                        <input type="text" name="bays"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                    </div>

                    <!-- ACTION BACKLOG -->
                    <div>
                        <label class="text-sm text-gray-400">Action Backlog</label>
                        <textarea name="action_backlog" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- NOTE 4 -->
                    <div>
                        <label class="text-sm text-gray-400">Note 4</label>
                        <textarea name="note4" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- RFU -->
                    <div>
                        <label class="text-sm text-gray-400">RFU</label>
                        <select name="rfu" class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                            <option value="">-- Select --</option>
                            <option value="ready">Ready</option>
                            <option value="not_ready">Not Ready</option>
                        </select>
                    </div>

                    <!-- DOWNTIME -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-400">Downtime Plan</label>
                            <input type="datetime-local" name="downtime_plan"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Downtime Actual</label>
                            <input type="datetime-local" name="downtime_actual"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                    </div>

                    <!-- NOTE 5 -->
                    <div>
                        <label class="text-sm text-gray-400">Note 5</label>
                        <textarea name="note5" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- ACTION BUTTON -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="btnCancelModal"
                            class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-500 font-semibold">
                            Add Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- EDIT SERVICE MODAL -->
        <div id="editServiceModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
            <div class="bg-gray-900 rounded-2xl w-full max-w-3xl p-6 relative max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">

                <h2 class="text-lg font-bold mb-4">Edit Service</h2>

                <form id="editServiceForm" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- DATE & UNIT -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-400">Date</label>
                            <input type="date" id="edit_date" name="date"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">CN / Code Unit</label>
                            <input type="text" id="edit_cn" name="cn" readonly
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                    </div>

                    <!-- PERSON IN CHARGE -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm text-gray-400">Kapten</label>
                            <input type="text" id="edit_kapten" name="kapten"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">GL</label>
                            <input type="text" id="edit_gl" name="gl"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">QA1</label>
                            <input type="text" id="edit_qa1" name="qa1"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                    </div>

                    <!-- SHIFT -->
                    <div>
                        <label class="text-sm text-gray-400">Shift</label>
                        <select id="edit_shift" name="shift"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                            <option value="">-- Select --</option>
                            <option value="1">Shift 1</option>
                            <option value="2">Shift 2</option>
                        </select>
                    </div>

                    <!-- NOTE 1 -->
                    <div>
                        <label class="text-sm text-gray-400">Note 1</label>
                        <textarea id="edit_note1" name="note1" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- WASHING -->
                    <div>
                        <label class="text-sm text-gray-400">Washing</label>
                        <select id="edit_washing" name="washing"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                            <option value="">-- Select --</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>

                    <!-- NOTE 2 -->
                    <div>
                        <label class="text-sm text-gray-400">Note 2</label>
                        <textarea id="edit_note2" name="note2" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- ACTION SERVICE -->
                    <div>
                        <label class="text-sm text-gray-400">Action Service</label>
                        <textarea id="edit_action_service" name="action_service" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- NOTE 3 -->
                    <div>
                        <label class="text-sm text-gray-400">Note 3</label>
                        <textarea id="edit_note3" name="note3" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- BAYS -->
                    <div>
                        <label class="text-sm text-gray-400">Bays</label>
                        <input type="text" id="edit_bays" name="bays"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                    </div>

                    <!-- ACTION BACKLOG -->
                    <div>
                        <label class="text-sm text-gray-400">Action Backlog</label>
                        <textarea id="edit_action_backlog" name="action_backlog" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- NOTE 4 -->
                    <div>
                        <label class="text-sm text-gray-400">Note 4</label>
                        <textarea id="edit_note4" name="note4" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- RFU -->
                    <div>
                        <label class="text-sm text-gray-400">RFU</label>
                        <select id="edit_rfu" name="rfu"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                            <option value="">-- Select --</option>
                            <option value="ready">Ready</option>
                            <option value="not_ready">Not Ready</option>
                        </select>
                    </div>

                    <!-- DOWNTIME -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-400">Downtime Plan</label>
                            <input type="datetime-local" id="edit_downtime_plan" name="downtime_plan"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Downtime Actual</label>
                            <input type="datetime-local" id="edit_downtime_actual" name="downtime_actual"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                    </div>

                    <!-- NOTE 5 -->
                    <div>
                        <label class="text-sm text-gray-400">Note 5</label>
                        <textarea id="edit_note5" name="note5" rows="2"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"></textarea>
                    </div>

                    <!-- ACTION BUTTON -->
                    <div class="flex justify-between gap-3 pt-4">
                        <div id="extraAction"></div>

                        <div class="flex gap-2">
                            <button type="button" id="btnCloseEditModal"
                                class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-500 font-semibold">
                                Save
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div id="handoverModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[60]">
            <div class="bg-gray-900 rounded-xl w-full max-w-sm p-6" onclick="event.stopPropagation()">
                <h3 class="text-lg font-semibold mb-4">Handover To</h3>

                <select id="handoverUser"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 mb-4">
                    <option value="">-- Select User --</option>
                </select>

                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        onclick="closeHandoverModal()"
                        class="px-4 py-2 bg-gray-700 rounded-lg">
                        Cancel
                    </button>

                    <button
                        type="button"
                        onclick="confirmHandover()"
                        class="px-4 py-2 bg-yellow-600 rounded-lg text-black font-semibold">
                        Handover
                    </button>
                </div>
            </div>
        </div>



    </div>

    <script>
        const addServiceCard = document.getElementById('addServiceCard');
        const modal = document.getElementById('addServiceModal');
        const btnCancel = document.getElementById('btnCancelModal');

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // OPEN MODAL DARI CARD
        addServiceCard.addEventListener('click', openModal);

        // CLOSE MODAL
        btnCancel.addEventListener('click', closeModal);

        // CLOSE KETIKA KLIK BACKDROP
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // UNIT CARD CLICK
        document.querySelectorAll('.unit-card').forEach(card => {
            card.addEventListener('click', () => {
                console.log('Unit card clicked:', card.dataset.unit);
            });
        });
    </script>
    <script>
        // =============================
        // EDIT MODAL ELEMENTS
        // =============================
        const editModal = document.getElementById('editServiceModal');
        const editForm = document.getElementById('editServiceForm');
        const extraAction = document.getElementById('extraAction');
        const btnCloseEditModal = document.getElementById('btnCloseEditModal');

        // =============================
        // OPEN & CLOSE MODAL
        // =============================
        function openEditModal() {
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        }

        // CLOSE VIA CANCEL BUTTON
        btnCloseEditModal.addEventListener('click', closeEditModal);

        // CLOSE WHEN CLICK BACKDROP
        editModal.addEventListener('click', (e) => {
            if (e.target === editModal) {
                closeEditModal();
            }
        });

        // =============================
        // UNIT CARD CLICK
        // =============================
        document.querySelectorAll('.unit-card').forEach(card => {
            card.addEventListener('click', async () => {

                const serviceId = card.dataset.serviceId;
                const status = card.dataset.status;
                const shift = card.dataset.shift;

                if (!serviceId) return;

                // SET FORM ACTION
                editForm.action = `/monitoring/service/${serviceId}`;

                // FETCH DATA
                const res = await fetch(`/monitoring/service/${serviceId}/json`);
                const s = await res.json();

                // FILL FORM
                document.getElementById('edit_date').value = s.service_date ?? '';
                document.getElementById('edit_cn').value = s.unit?.code ?? '';
                document.getElementById('edit_kapten').value = s.kapten ?? '';
                document.getElementById('edit_gl').value = s.gl ?? '';
                document.getElementById('edit_qa1').value = s.qa1 ?? '';
                document.getElementById('edit_shift').value = s.shift ?? '';

                document.getElementById('edit_note1').value = s.note1 ?? '';
                document.getElementById('edit_washing').value = s.washing ?? '';
                document.getElementById('edit_note2').value = s.note2 ?? '';
                document.getElementById('edit_action_service').value = s.action_service ?? '';
                document.getElementById('edit_note3').value = s.note3 ?? '';
                document.getElementById('edit_bays').value = s.bays ?? '';
                document.getElementById('edit_action_backlog').value = s.action_backlog ?? '';
                document.getElementById('edit_note4').value = s.note4 ?? '';
                document.getElementById('edit_rfu').value = s.rfu ?? '';
                document.getElementById('edit_downtime_plan').value = s.downtime_plan ?? '';
                document.getElementById('edit_downtime_actual').value = s.downtime_actual ?? '';
                document.getElementById('edit_note5').value = s.note5 ?? '';

                // EXTRA ACTION BUTTON
                extraAction.innerHTML = '';

                if (status === 'open') {
                    extraAction.innerHTML = `
                            <button
                                type="button"
                                onclick="handoverJob(${serviceId})"
                                class="px-4 py-2 rounded-lg bg-yellow-600 hover:bg-yellow-500 text-black font-semibold">
                                Handover Job
                            </button>
                        `;
                }

                if (status === 'handover' && shift == 2) {
                    extraAction.innerHTML = `
                            <button
                                type="button"
                                onclick="endJob(${serviceId})"
                                class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-500 text-white font-semibold">
                                End Job
                            </button>
                        `;
                }

                openEditModal();
            });
        });
    </script>
    <script>
        let currentServiceId = null;

        async function handoverJob(serviceId) {
            currentServiceId = serviceId;

            // TUTUP EDIT MODAL
            closeEditModal();

            // FETCH USER
            const res = await fetch('/monitoring/handover-users');
            const users = await res.json();

            const select = document.getElementById('handoverUser');
            select.innerHTML = '<option value="">-- Select User --</option>';

            users.forEach(u => {
                select.innerHTML += `<option value="${u.id}">${u.name}</option>`;
            });

            // BUKA HANDOVER MODAL
            const modal = document.getElementById('handoverModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }


        function closeHandoverModal() {
            document.getElementById('handoverModal').classList.add('hidden');
            document.getElementById('handoverModal').classList.remove('flex');
            currentServiceId = null;
        }

        async function confirmHandover() {
            if (!currentServiceId) return;

            const userId = document.getElementById('handoverUser').value;

            if (!userId) {
                alert('Please select user');
                return;
            }

            try {
                const res = await fetch(`/monitoring/service/${currentServiceId}/handover`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ user_id: userId })
                });

                if (!res.ok) throw new Error();

                location.reload();
            } catch {
                alert('Failed to handover');
            }
            
        }

        document.getElementById('handoverModal').addEventListener('click', (e) => {
            if (e.target.id === 'handoverModal') {
                closeHandoverModal();
            }
        });

    </script>

<script>
async function endJob(serviceId) {
    if (!confirm('End this job?')) return;

    try {
        const res = await fetch(`/monitoring/service/${serviceId}/end-job`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error();

        location.reload();
    } catch {
        alert('Failed to end job');
    }
}
</script>


@endsection