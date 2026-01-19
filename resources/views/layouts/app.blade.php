<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>@yield('title', 'MONSWHEEL')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('img/monswheel.png') }}">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome 6 Free (CSS ONLY) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<link rel="stylesheet"
      href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">


</head>

<body class="bg-black text-white min-h-screen overflow-x-hidden">
<style>
    /* Dark mode tweak biar nyatu */
    .dataTables_wrapper {
        color: #e5e7eb;
    }

    table.dataTable tbody tr {
        background-color: transparent;
    }

    table.dataTable thead th {
        border-bottom: 1px solid #374151;
    }

    .dataTables_filter input,
    .dataTables_length select {
        background-color: #111827;
        color: #e5e7eb;
        border: 1px solid #374151;
        border-radius: 0.5rem;
        padding: 4px 8px;
    }

    .dataTables_paginate .paginate_button {
        color: #e5e7eb !important;
    }
    /* ===== DataTables Dark Mode Fix ===== */

/* Wrapper text */
.dataTables_wrapper {
    color: #e5e7eb;
}

/* Length menu label */
.dataTables_length label {
    color: #e5e7eb;
}

/* Length select */
.dataTables_length select {
    background-color: #111827 !important; /* gray-900 */
    color: #e5e7eb !important;
    border: 1px solid #374151; /* gray-700 */
    border-radius: 0.5rem;
    padding: 4px 8px;
}

/* Dropdown options */
.dataTables_length select option {
    background-color: #111827;
    color: #e5e7eb;
}

/* Search input */
.dataTables_filter input {
    background-color: #111827;
    color: #e5e7eb;
    border: 1px solid #374151;
    border-radius: 0.5rem;
    padding: 4px 8px;
}

/* Pagination */
.dataTables_paginate .paginate_button {
    color: #e5e7eb !important;
}

.dataTables_paginate .paginate_button.current {
    background: #1f2937 !important; /* gray-800 */
    border-color: #374151 !important;
    color: #fff !important;
}

.dataTables_paginate .paginate_button:hover {
    background: #374151 !important;
    color: #fff !important;
}

</style>

    <!-- APP WRAPPER -->
    <div class="min-h-screen flex flex-col">
        <style>
            .menu-item {
                display: flex;
                gap: 0.75rem;
                padding: 1rem 1.5rem;
                color: white;
            }

            .menu-item:hover {
                background-color: rgb(31 41 55);x
            }
        </style>

        {{-- TOP BAR SLOT --}}
        @yield('topbar')

        {{-- MAIN CONTENT --}}
        <main class="flex-1">
            @yield('content')
        </main>
        <div id="logoutModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">

            <!-- OVERLAY -->
            <div id="modalOverlay" class="absolute inset-0 bg-black/70"></div>

            <!-- MODAL BOX -->
            <div class="relative bg-gray-900 rounded-2xl p-6 w-[90%] max-w-sm shadow-xl">

                <h2 class="text-lg font-bold text-white mb-2">
                    Confirm Logout
                </h2>

                <p class="text-gray-400 text-sm mb-6">
                    Are you sure you want to logout from MONSWHEEL?
                </p>

                <div class="flex justify-end gap-3">
                    <button id="cancelLogout" class="px-4 py-2 rounded-lg bg-gray-700 text-white hover:bg-gray-600">
                        Cancel
                    </button>

                    <!-- FRONTEND ONLY -->
                    <a href="/logout" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-500">
                        Logout
                    </a>
                </div>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const menuBtn = document.getElementById('menuBtn');
            const menu = document.getElementById('menu');
            const logoutBtn = document.getElementById('logoutBtn');

            const logoutModal = document.getElementById('logoutModal');
            const modalOverlay = document.getElementById('modalOverlay');
            const cancelLogout = document.getElementById('cancelLogout');

            // Toggle Menu
            menuBtn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });

            // Open Logout Modal
            logoutBtn.addEventListener('click', () => {
                menu.classList.add('hidden');
                logoutModal.classList.remove('hidden');
            });

            // Close Modal
            modalOverlay.addEventListener('click', closeModal);
            cancelLogout.addEventListener('click', closeModal);

            function closeModal() {
                logoutModal.classList.add('hidden');
            }

            // ESC to close modal
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeModal();
            });
        });
    </script>

    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

</body>

</html>