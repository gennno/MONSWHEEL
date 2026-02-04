@extends('layouts.app')

@section('title', 'Unit - MONSWHEEL')

@section('topbar')
    <x-topbar />
@endsection

@section('content')
    <div class="p-4 space-y-6">

        <!-- HEADER ACTION -->
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold">Unit List</h1>

            <!-- ADD UNIT BUTTON -->
            <button onclick="openAddUnit()"
                class="flex items-center gap-2 px-5 py-2 rounded-xl bg-green-600 text-white font-semibold">
                <i class="fa-solid fa-plus"></i> Add Unit
            </button>


        </div>
        <!-- UNIT TABLE -->
        <div class="bg-gray-900 rounded-xl shadow-md">

            <!-- MOBILE SAFE SCROLL -->
            <div class="overflow-x-auto">
                <table id="unitTable" class="min-w-[700px] w-full text-lg">
                    <thead class="bg-gray-800 text-gray-300">
                        <tr>
                            <th class="px-3 py-2 border border-gray-700 text-center">#</th>
                            <th class="px-3 py-2 border border-gray-700 text-center">Photo</th>
                            <th class="px-3 py-2 border border-gray-700 text-center">Unit Code</th>
                            <th class="px-3 py-2 border border-gray-700 text-center">Type</th>
                            <th class="px-3 py-2 border border-gray-700 text-center">Status</th>
                            <th class="px-3 py-2 border border-gray-700 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-800">
                        @forelse ($units as $index => $unit)
                            <tr class="hover:bg-gray-800 transition">
                                <td class="px-3 py-3 text-center border-r border-gray-700">
                                    {{ $index + 1 }}
                                </td>
                                
                                <td class="px-3 py-3 text-center border-r border-gray-700">
                                    {{ $unit->img ?? '-' }}
                                </td>

                                <td class="px-3 py-3 font-semibold text-center border-r border-gray-700">
                                    {{ $unit->code }}
                                </td>

                                <td class="px-3 py-3 text-center border-r border-gray-700">
                                    {{ $unit->type ?? '-' }}
                                </td>

                                <td class="px-3 py-3 text-center border-r border-gray-700">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-xs font-semibold
                                                                    {{ $unit->status === 'Active' ? 'bg-green-600/20 text-green-400' : 'bg-red-600/20 text-red-400' }}">
                                        {{ $unit->status }}
                                    </span>
                                </td>

                                <td class="px-3 py-3 text-center border-r border-gray-700">
                                    <div class="flex items-center justify-center gap-3">

                                        <a onclick="viewUnit({{ $unit->id }})"
                                            class="p-2 bg-blue-600/20 text-blue-400 rounded-lg">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        @if(auth()->user()->role === 'admin')
                                            <a onclick="editUnit({{ $unit->id }})"
                                                class="p-2 bg-yellow-600/20 text-yellow-400 rounded-lg">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>

                                            <form action="{{ route('units.destroy', $unit) }}" method="POST"
                                                onsubmit="return confirmDeleteUnit('{{ $unit->code }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="p-2 bg-red-600/20 text-red-400 rounded-lg">
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
                                    No unit data found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

        <div id="addUnitModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
            <div class="bg-gray-900 rounded-xl w-full max-w-md p-6" onclick="event.stopPropagation()">
                <h3 class="text-lg font-semibold mb-4">Add Unit</h3>

                <form method="POST" action="{{ route('units.store') }}">
                    @csrf

                    <input name="code" placeholder="Unit Code" required
                        class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">

                    <input name="type" placeholder="Type"
                        class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">

                    <select name="status" class="w-full mb-4 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeAddUnit()"
                            class="px-4 py-2 bg-gray-700 rounded-lg">Cancel</button>
                        <button class="px-4 py-2 bg-green-600 rounded-lg text-black font-semibold">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div id="unitModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
            <div class="bg-gray-900 rounded-xl w-full max-w-md p-6" onclick="event.stopPropagation()">
                <h3 id="unitModalTitle" class="text-lg font-semibold mb-4"></h3>

                <form id="unitForm" method="POST">
                    @csrf
                    @method('PUT')

                    <input id="unit_code" name="code"
                        class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">

                    <input id="unit_type" name="type"
                        class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">

                    <select id="unit_status" name="status"
                        class="w-full mb-4 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <div id="unitModalActions" class="flex justify-end gap-2"></div>
                </form>
            </div>
        </div>

    </div>
    <script>
        function openAddUnit() {
            toggleModal('addUnitModal', true)
        }
        function closeAddUnit() {
            toggleModal('addUnitModal', false)
        }

        async function viewUnit(id) {
            const res = await fetch(`/units/${id}`);
            const u = await res.json();

            fillUnitForm(u, false);
        }

        async function editUnit(id) {
            const res = await fetch(`/units/${id}`);
            const u = await res.json();

            fillUnitForm(u, true);
        }

        function fillUnitForm(u, editable) {
            document.getElementById('unitModalTitle').innerText =
                editable ? 'Edit Unit' : 'View Unit';

            document.getElementById('unit_code').value = u.code;
            document.getElementById('unit_type').value = u.type ?? '';
            document.getElementById('unit_status').value = u.status;

            ['unit_code', 'unit_type', 'unit_status']
                .forEach(id => document.getElementById(id).disabled = !editable);

            const actions = document.getElementById('unitModalActions');
            actions.innerHTML = editable
                ? `
            <button
                type="button"
                onclick="closeUnitModal()"
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
                onclick="closeUnitModal()"
                class="px-4 py-2 bg-gray-700 rounded-lg">
                Close
            </button>
          `;


            document.getElementById('unitForm').action = `/units/${u.id}`;
            toggleModal('unitModal', true);
        }

        function closeUnitModal() {
            toggleModal('unitModal', false);
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
            $('#unitTable').DataTable({
                scrollX: true,
                autoWidth: false,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50, 100],

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

    <script>
        function confirmDeleteUnit(code) {
            return confirm(`Delete unit "${code}"?\nThis action cannot be undone.`);
        }
    </script>

@endsection