@extends('layouts.app')

@section('title', 'User List - MONSWHEEL')

@section('topbar')
    <x-topbar />
@endsection

@section('content')
    <div class="p-4 space-y-6">

        <!-- HEADER ACTION -->
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold">User List</h1>

            <button onclick="openAddUser()" class="flex items-center gap-2 px-5 py-2 rounded-xl
               bg-green-600 hover:bg-green-500
               text-white font-semibold transition">
                <i class="fa-solid fa-plus"></i>
                Add User
            </button>
        </div>
        <!-- user TABLE -->
        <div class="bg-gray-900 rounded-xl shadow-md">

            <!-- MOBILE SAFE SCROLL -->
            <div class="overflow-x-auto">
                <table id="userTable" class="min-w-[700px] w-full text-sm">
                    <thead class="bg-gray-800 text-gray-300">
                        <tr>
                            <th class="px-3 py-2 text-left">#</th>
                            <th class="px-3 py-2 text-left">Username</th>
                            <th class="px-3 py-2 text-left">Name</th>
                            <th class="px-3 py-2 text-left">Email</th>
                            <th class="px-3 py-2 text-left">Role</th>
                            <th class="px-3 py-2 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-800">
                        @foreach ($users as $index => $user)
                                        <tr class="hover:bg-gray-800 transition">
                                            <td class="px-3 py-3">
                                                {{ $index + 1 }}
                                            </td>

                                            <td class="px-3 py-3 font-semibold">
                                                {{ $user->username }}
                                            </td>

                                            <td class="px-3 py-3 font-semibold">
                                                {{ $user->name }}
                                            </td>

                                            <td class="px-3 py-3">
                                                {{ $user->email }}
                                            </td>

                                            <td class="px-3 py-3">
                                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                                                            {{
                            $user->role === 'site'
                            ? 'bg-green-600/20 text-green-400'
                            : ($user->role === 'office'
                                ? 'bg-blue-600/20 text-blue-400'
                                : 'bg-red-600/20 text-red-400')
                                                                            }}">
                                                    {{ strtoupper($user->role) }}
                                                </span>
                                            </td>

                                            <td class="px-3 py-3">
                                                <div class="flex items-center justify-center gap-3">

                                                    <button onclick="viewUser({{ $user->id }})"
                                                        class="p-2 rounded-lg bg-blue-600/20 text-blue-400 hover:bg-blue-600/30"
                                                        title="View">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>

                                                    <button onclick="editUser({{ $user->id }})"
                                                        class="p-2 rounded-lg bg-yellow-600/20 text-yellow-400 hover:bg-yellow-600/30"
                                                        title="Edit">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </button>

                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                        onsubmit="return confirmDelete()">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                            class="p-2 rounded-lg bg-red-600/20 text-red-400 hover:bg-red-600/30"
                                                            title="Delete">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
        <div id="addUserModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50"
            onclick="closeAddUser()">
            <div class="bg-gray-900 rounded-xl w-full max-w-md p-6" onclick="event.stopPropagation()">
                <h3 class="text-lg font-semibold mb-4">Add User</h3>

                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <input name="username" placeholder="Username" required
                        class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">

                    <input name="name" placeholder="Name" required
                        class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">

                    <input name="email" type="email" placeholder="Email" required
                        class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">

                    <input name="password" type="password" placeholder="Password" required
                        class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">

                    <select name="role" class="w-full mb-4 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        <option value="admin">Admin</option>
                        <option value="office">Office</option>
                        <option value="site">Site</option>
                    </select>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeAddUser()"
                            class="px-4 py-2 bg-gray-700 rounded-lg">Cancel</button>
                        <button class="px-4 py-2 bg-green-600 rounded-lg text-black font-semibold">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div id="userModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50"
            onclick="closeUserModal()">
            <div class="bg-gray-900 rounded-xl w-full max-w-md p-6" onclick="event.stopPropagation()">
                <h3 id="userModalTitle" class="text-lg font-semibold mb-4"></h3>

                <form id="userForm" method="POST">
                    @csrf
                    @method('PUT')

                    <input id="user_username" name="username"
                        class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">

                    <input id="user_name" name="name"
                        class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">

                    <input id="user_email" name="email"
                        class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">

                    <select id="user_role" name="role"
                        class="w-full mb-4 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">
                        <option value="admin">Admin</option>
                        <option value="office">Office</option>
                        <option value="site">Site</option>
                    </select>
                    <input id="user_password" name="password" type="password" placeholder="New Password (optional)"
                        class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2">

                    <div id="userModalActions" class="flex justify-end gap-2"></div>
                </form>
            </div>
        </div>


    </div>
    <script>
        function openAddUser() {
            toggleModal('addUserModal', true)
        }
        function closeAddUser() {
            toggleModal('addUserModal', false)
        }

        async function viewUser(id) {
            const res = await fetch(`/users/${id}`);
            const u = await res.json();
            fillUserForm(u, false);
        }

        async function editUser(id) {
            const res = await fetch(`/users/${id}`);
            const u = await res.json();
            fillUserForm(u, true);
        }

        function fillUserForm(u, editable) {
            document.getElementById('userModalTitle').innerText =
                editable ? 'Edit User' : 'View User';

            user_username.value = u.username;
            user_name.value = u.name;
            user_email.value = u.email;
            user_role.value = u.role;
            user_password.value = '';

            ['user_username', 'user_name', 'user_email', 'user_role', 'user_password']
                .forEach(id => document.getElementById(id).disabled = !editable);

            // Password hanya tampil saat edit
            document.getElementById('user_password').classList.toggle('hidden', !editable);

            userModalActions.innerHTML = editable
                ? `
                    <button type="button" onclick="closeUserModal()"
                            class="px-4 py-2 bg-gray-700 rounded-lg">Cancel</button>
                    <button type="submit"
                            class="px-4 py-2 bg-yellow-600 rounded-lg text-black font-semibold">
                        Update
                    </button>
                  `
                : `
                    <button type="button" onclick="closeUserModal()"
                            class="px-4 py-2 bg-gray-700 rounded-lg">Close</button>
                  `;

            userForm.action = `/users/${u.id}`;
            toggleModal('userModal', true);
        }

        function closeUserModal() {
            toggleModal('userModal', false);
        }

        function toggleModal(id, show) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden', !show);
            el.classList.toggle('flex', show);
        }

        function confirmDelete() {
            return confirm('Delete this user?');
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#userTable').DataTable({
                scrollX: true,
                autoWidth: false,
                paging: true,3
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
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this user?');
        }
    </script>

@endsection