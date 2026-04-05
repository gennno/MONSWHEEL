<div class="bg-gray-950 border-b border-gray-800 relative">

    <!-- TOP BAR -->
    <div class="flex items-center justify-between px-4 py-3">

        <!-- HAMBURGER -->
        <button id="menuBtn"
            class="w-11 h-11 flex items-center justify-center rounded-lg bg-gray-800 text-white active:bg-gray-700">
            ☰
        </button>

        <!-- TITLE -->
        <div class="text-white font-bold tracking-wide text-lg">
            MONSWHEEL
        </div>

        <!-- TIME -->
        <div class="text-gray-400 text-sm hidden sm:block">
            {{ now('Asia/Jakarta')->format('d M Y • H:i') }}
        </div>
    </div>

    <!-- DROPDOWN MENU -->
@php
    $user = Auth::user();
@endphp

<div id="menu"
     class="hidden bg-gray-900 border-t border-gray-800">

    <ul class="flex flex-col divide-y divide-gray-800 text-base">

        <li>
            <a href="/dashboard" class="menu-item">📊 Dashboard</a>
        </li>

                {{-- USER MANAGEMENT: HANYA ADMIN --}}
        @if ($user->role === 'admin')
            <li>
                <a href="/plans" class="menu-item">🗓️ Plan</a>
            </li>
        @endif

        <li>
            <a href="/monitoring" class="menu-item">🖥 Monitor</a>
        </li>

        <li>
            <a href="/units" class="menu-item">🚚 Unit</a>
        </li>

        <li>
            <a href="/history" class="menu-item">📜 Service History</a>
        </li>
        {{-- SETTINGS: SEMUA ROLE KECUALI ADMIN --}}
        @if ($user->role !== 'admin')
            <li>
                <a href="/settings" class="menu-item">⚙️ Settings</a>
            </li>
        @endif

        {{-- USER MANAGEMENT: HANYA ADMIN --}}
        @if ($user->role === 'admin')
            <li>
                <a href="/users" class="menu-item">👤 User</a>
            </li>
        @endif

        <li>
            <a id="logoutBtn" class="menu-item text-red-400 cursor-pointer">
                🚪 Logout
            </a>
        </li>

    </ul>
</div>

</div>
