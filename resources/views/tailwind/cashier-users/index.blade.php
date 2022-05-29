<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Всі користувачі сервісу checkbox.ua</h2>
            @include('kaca::layouts.navigation')
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-kaca-breadcrumbs>
                <x-kaca-breadcrumb-item>
                    <a href="{{ route('kaca.index') }}"
                       class="text-grey-700 hover:text-grey-900 ml-1 md:ml-2 text-sm font-medium">Kaca</a>
                </x-kaca-breadcrumb-item>
                <x-kaca-breadcrumb-item>
                    <span class="text-grey-200 ml-1 md:ml-2 text-sm font-medium">Всі користувачі сервісу checkbox.ua</span>
                </x-kaca-breadcrumb-item>
            </x-kaca-breadcrumbs>

            <x-kaca-validation-errors></x-kaca-validation-errors>

            <x-kaca-alerts></x-kaca-alerts>

            <div class="flex flex-row gap-4">
                <div class="w-full">
                    <x-kaca-card classBody="p-0">
                        <x-slot name="header">
                            <div class="flex justify-between">
                                <div>Всі користувачі сервісу checkbox.ua</div>
                                <a href="{{ route('kaca.cashier-users.create') }}"
                                   class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Додати</a>
                            </div>
                        </x-slot>
                        <x-kaca-table>
                            <x-slot name="thead">
                                <x-kaca-table-th>Ім'я користувача</x-kaca-table-th>
                                <x-kaca-table-th>Права</x-kaca-table-th>
                                <x-kaca-table-th>Закріплений касир checkbox.ua</x-kaca-table-th>
                                <x-kaca-table-th>Закріплена каса checkbox.ua</x-kaca-table-th>
                                <x-kaca-table-th>Дії</x-kaca-table-th>
                            </x-slot>
                            @forelse($cashierUsers as $cashierUser)
                                <x-kaca-table-tr>
                                    <x-kaca-table-td thead="Ім'я менеджера">{{ $cashierUser->{\Kaca\Kaca::$userFieldName} }}</x-kaca-table-td>
                                    <x-kaca-table-td thead="Права">
                                        @if($cashierUser->can('seniorPermission'))
                                            <span class="bg-green-500 text-white px-2.5 rounded-lg shadow-md text-sm">Адміністратор</span>
                                        @else
                                            <span class="bg-yellow-500 text-white px-2.5 rounded-lg shadow-md text-sm">Робота з чеками</span>
                                        @endif
                                    </x-kaca-table-td>
                                    <x-kaca-table-td thead="Закріплений касир checkbox.ua">{{ $cashierUser->cashier->full_name }}</x-kaca-table-td>
                                    <x-kaca-table-td thead="Закріплена каса checkbox.ua">{{ $cashierUser->cashRegister->title }}</x-kaca-table-td>
                                    <x-kaca-table-td thead="Дії">
                                        <div class="gap-4 flex flex-row">
                                            <a class="text-blue-700 hover:text-blue-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center"
                                               href="{{ route('kaca.cashier-users.edit', $cashierUser) }}"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                            </a>
                                            <div>
                                                <form action="{{ route('kaca.cashier-users.destroy', $cashierUser->id) }}" method="post"> @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-700 hover:text-red-800 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm text-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>

                                            </div>
                                        </div>

                                    </x-kaca-table-td>
                                </x-kaca-table-tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-2">Не знайдено касирів</td></tr>
                            @endforelse
                        </x-kaca-table>
                        <div class="px-6 py-2">
                            {{ $cashierUsers->links() }}
                        </div>
                    </x-kaca-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
