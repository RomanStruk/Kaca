<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
            <div>Меню Kaca</div>

            <div class="ml-1">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </button>
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"
         style="display: none;"
         @click="open = false"
    >
        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
            <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
               href="{{ route('kaca.index') }}"
            >Інформація</a>
            <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
               href="{{ route('kaca.receipts.index') }}"
            >Чеки</a>
            @can('seniorPermission')
            <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
               href="{{ route('kaca.shifts.index') }}"
            >Зміни</a>
            @endcan
            <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
               href="{{ route('kaca.reports.index') }}"
            >Звіти</a>
            @can('seniorPermission')
                <div class="block px-4 py-2 text-xs text-gray-400">
                    Налаштування
                </div>
                <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                   href="{{ route('kaca.cashiers.index') }}"
                >Касири checkbox.ua</a>
                <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                   href="{{ route('kaca.cash-registers.index') }}"
                >Каси checkbox.ua</a>
                <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                   href="{{ route('kaca.cashier-users.index') }}"
                >Користувачі сервісом</a>
            @endcan
            @can('developerPermission')
                <div class="block px-4 py-2 text-xs text-gray-400">
                    Розробнику
                </div>
                <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                   href="{{ route('kaca.entries.index') }}"
                >Налагодження</a>
                <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                   href="{{ route('kaca.action-recorders.index') }}"
                >Активність</a>
            @endcan
        </div>
    </div>
</div>