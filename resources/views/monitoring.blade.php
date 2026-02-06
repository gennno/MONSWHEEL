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

                    $status = $service?->status ?? null;

                    $statusRingClass = match ($status) {
                        'process' => 'hover:ring-blue-500',
                        'continue' => 'hover:ring-yellow-500',
                        'done' => 'hover:ring-green-500',
                        default => 'hover:ring-gray-600',
                    };

                    $statusBadgeClass = match ($status) {
                        'plan' => 'bg-gray-600/20 text-gray-300',
                        'process' => 'bg-blue-600/20 text-blue-400',
                        'continue' => 'bg-yellow-600/20 text-yellow-400',
                        'done' => 'bg-green-600/20 text-green-400',
                        default => 'bg-gray-700 text-gray-300',
                    };
                @endphp
                <div class="unit-card relative bg-gray-900 rounded-xl p-4 cursor-pointer
                                                                                     hover:bg-gray-800 hover:ring-2 {{ $statusRingClass }}
                                                                                     transition text-center flex flex-col items-center gap-3"
                    data-unit="{{ $unit->code }}" data-service-id="{{ $service?->id }}" data-status="{{ $status }}">

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

                    <!-- SERVICE PLAN (CN) -->
                    <div>
                        <label class="text-sm text-gray-400">CN / Plan Service</label>
                        <select name="service_id" class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2"
                            required>
                            <option value="">-- Select Plan --</option>
                            @foreach ($planServices as $plan)
                                <option value="{{ $plan->id }}">
                                    {{ $plan->unit->code }} — {{ $plan->service_date->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- PERSON IN CHARGE -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-400">GL</label>
                            <input type="text" name="gl"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Kapten</label>
                            <input type="text" name="kapten"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                    </div>


                    <!-- BAYS -->
                    <div>
                        <label class="text-sm text-gray-400">Bays</label>
                        <input type="number" name="bays" min="1"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                    </div>

                    <!-- TIME LOG -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-400">Unit Masuk</label>
                            <input type="time" name="in_actual"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">QA 1</label>
                            <input type="time" name="qa1_actual"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Washing</label>
                            <input type="time" name="washing_actual"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Action Service</label>
                            <input type="time" name="action_service_actual"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">Action Backlog</label>
                            <input type="time" name="action_backlog_actual"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>

                        <div>
                            <label class="text-sm text-gray-400">QA 7</label>
                            <input type="time" name="qa7_actual"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                    </div>


                    <!-- ACTION BUTTON -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="btnCancelModal"
                            class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-500 font-semibold">
                            Start Service
                        </button>
                    </div>
                </form>

            </div>
        </div>


        <!-- EDIT SERVICE MODAL -->
        <div id="editServiceModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
            <div class="bg-gray-900 rounded-2xl w-full max-w-3xl p-6 relative max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">

                <h2 class="text-lg font-bold mb-4">Service Progress</h2>

                <form id="editServiceForm" class="space-y-5">
                    @csrf

                    <!-- CN / UNIT -->
                    <div>
                        <label class="text-sm text-gray-400">CN / Unit</label>
                        <input type="text" id="edit_cn" readonly
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                    </div>

                    <!-- PIC -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-400">GL</label>
                            <input type="text" id="edit_gl"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Kapten</label>
                            <input type="text" id="edit_kapten"
                                class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        </div>
                    </div>

                    <!-- TIME LOG -->
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $times = [
                                'in_actual' => 'Unit Masuk',
                                'qa1_actual' => 'QA 1',
                                'washing_actual' => 'Washing',
                                'action_service_actual' => 'Action Service',
                                'action_backlog_actual' => 'Action Backlog',
                                'qa7_actual' => 'QA 7',
                            ];
                        @endphp

                        @foreach ($times as $field => $label)
                            <div>
                                <label class="text-sm text-gray-400">{{ $label }}</label>
                                <div class="flex gap-2">
                                    <input type="time" step="60" id="edit_{{ $field }}"
                                        class="flex-1 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                                    <button type="button" onclick="updateTime('{{ $field }}')"
                                        class="px-3 rounded-lg bg-green-600 hover:bg-green-500 font-bold">
                                        ✔
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- ACTION BUTTON -->
                    <div class="flex justify-between pt-6">
                        <div id="extraActionLeft"></div>

                        <div class="flex gap-2">
                            <div id="extraActionRight"></div>
                            <button type="button" id="btnCloseEditModal"
                                class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600">
                                Close
                            </button>
                        </div>
                    </div>
                </form>
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
    </script>
    <script>
        const editModal = document.getElementById('editServiceModal');
        const btnCloseEditModal = document.getElementById('btnCloseEditModal');
        const extraActionLeft = document.getElementById('extraActionLeft');
        const extraActionRight = document.getElementById('extraActionRight');

        let currentServiceId = null;

        function openEditModal() {
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        }

        btnCloseEditModal.addEventListener('click', closeEditModal);

        editModal.addEventListener('click', e => {
            if (e.target === editModal) closeEditModal();
        });
    </script>
    <script>
        document.querySelectorAll('.unit-card').forEach(card => {
            card.addEventListener('click', async () => {

                const serviceId = card.dataset.serviceId;
                const status = card.dataset.status;

                if (!serviceId || !['process', 'continue'].includes(status)) return;

                currentServiceId = serviceId;

                const res = await fetch(`/monitoring/service/${serviceId}/json`);
                const s = await res.json();

                // BASIC
                edit_cn.value = s.unit?.code ?? '';
                edit_gl.value = s.gl ?? '';
                edit_kapten.value = s.kapten ?? '';

                // TIME (HH:mm)
                [
                    'in_actual', 'qa1_actual', 'washing_actual',
                    'action_service_actual', 'action_backlog_actual', 'qa7_actual'
                ].forEach(f => {
                    const input = document.getElementById('edit_' + f);
                    const value = s[f];

                    if (!value) {
                        input.value = '';
                        return;
                    }

                    let timePart = '';

                    if (value.includes('T')) {
                        // ISO format: 2026-02-06T22:10:00.000000Z
                        timePart = value.split('T')[1].substring(0, 5);
                    } else if (value.includes(' ')) {
                        // SQL datetime: 2026-02-06 22:10:00
                        timePart = value.split(' ')[1].substring(0, 5);
                    } else {
                        // Time only: 22:10:00
                        timePart = value.substring(0, 5);
                    }

                    input.value = timePart;
                });

                // RESET ACTION
                extraActionLeft.innerHTML = '';
                extraActionRight.innerHTML = '';

                // ALWAYS SHOW BOTH BUTTONS
                extraActionLeft.innerHTML = `
                        <button onclick="handoverJob(${serviceId})"
                            class="px-4 py-2 bg-yellow-500 rounded-lg font-semibold text-black">
                            Handover Job
                        </button>
                    `;

                extraActionRight.innerHTML = `
                        <button onclick="endJob(${serviceId})"
                            class="px-4 py-2 bg-red-600 rounded-lg font-semibold">
                            End Job
                        </button>
                    `;

                openEditModal();
            });
        });
    </script>
    <script>
        async function updateTime(field) {
            const value = document.getElementById('edit_' + field).value;
            if (!value) return alert('Time belum diisi');

            await fetch(`/monitoring/service/${currentServiceId}/time`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({
                    field,
                    value
                })
            });

            alert('Time updated');
        }
    </script>
    <script>
        async function handoverJob(serviceId) {
            const gl = document.getElementById('edit_gl').value;
            const kapten = document.getElementById('edit_kapten').value;

            if (!gl && !kapten) {
                alert('GL atau Kapten harus diisi');
                return;
            }

            try {
                const res = await fetch(`/monitoring/service/${serviceId}/handover`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        gl,
                        kapten
                    })
                });

                if (!res.ok) throw new Error();

                alert('Handover berhasil, status lanjut');
                location.reload(); // supaya badge & warna update
            } catch {
                alert('Gagal handover');
            }
        }
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