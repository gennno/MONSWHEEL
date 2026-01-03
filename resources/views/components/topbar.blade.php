<div x-data="{ open: false }" class="bg-gray-950 border-b border-gray-800">

    <!-- TOP BAR -->
    <div class="flex items-center justify-between px-4 py-3">

        <!-- LEFT: HAMBURGER -->
        <button
            @click="open = !open"
            class="w-11 h-11 flex items-center justify-center rounded-lg bg-gray-800 text-white active:bg-gray-700"
            aria-label="Open Menu"
        >
            <!-- Hamburger Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- CENTER: TITLE -->
        <div class="text-white font-bold tracking-wide text-lg">
            MONSWHEEL
        </div>

        <!-- RIGHT: TIME -->
        <div class="text-gray-400 text-sm">
            {{ now()->format('H:i') }}
        </div>
    </div>

    <!-- DROPDOWN MENU -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        @click.outside="open = false"
        class="bg-gray-900 border-t border-gray-800"
    >

        <ul class="flex flex-col divide-y divide-gray-800 text-base">

            <li>
                <a href="#"
                   class="flex items-center gap-3 px-6 py-4 text-white hover:bg-gray-800">
                    📊 Dashboard
                </a>
            </li>

            <li>
                <a href="#"
                   class="flex items-center gap-3 px-6 py-4 text-white hover:bg-gray-800">
                    🖥 Monitor
                </a>
            </li>

            <li>
                <a href="#"
                   class="flex items-center gap-3 px-6 py-4 text-white hover:bg-gray-800">
                    ⚙️ Settings
                </a>
            </li>

            <li>
                <a href="#"
                   class="flex items-center gap-3 px-6 py-4 text-red-400 hover:bg-gray-800">
                    🚪 Logout
                </a>
            </li>

        </ul>
    </div>

</div>
