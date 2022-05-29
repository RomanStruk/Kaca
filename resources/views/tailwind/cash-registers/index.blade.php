<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Всі каси з сервісу checkbox.ua</h2>
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
                    <span class="text-grey-200 ml-1 md:ml-2 text-sm font-medium">Всі каси з сервісу checkbox.ua</span>
                </x-kaca-breadcrumb-item>
            </x-kaca-breadcrumbs>

            <x-kaca-validation-errors></x-kaca-validation-errors>

            <x-kaca-alerts></x-kaca-alerts>

            <div class="flex flex-row gap-4">
                <div class="w-full">
                    <x-kaca-card classBody="p-0">
                        <x-slot name="header">
                            <div class="flex justify-between">
                                <div>Всі каси з сервісу checkbox.ua</div>
                                <a href="{{ route('kaca.cash-registers.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Додати</a>
                            </div>
                        </x-slot>
                        <x-kaca-table>
                            <x-slot name="thead">
                                <x-kaca-table-th>Адреса</x-kaca-table-th>
                                <x-kaca-table-th>Назва</x-kaca-table-th>
                                <x-kaca-table-th>Фіскальний номер</x-kaca-table-th>
                                <x-kaca-table-th>Ключ ліцензії каси</x-kaca-table-th>
                                <x-kaca-table-th>Дата створення</x-kaca-table-th>
                                <x-kaca-table-th>Дії</x-kaca-table-th>
                            </x-slot>
                            @forelse($cashRegisters as $cashRegister)
                                <x-kaca-table-tr>
                                    <x-kaca-table-td thead="Адреса">{{ $cashRegister->address }}</x-kaca-table-td>
                                    <x-kaca-table-td thead="Назва">{{ $cashRegister->title }}</x-kaca-table-td>
                                    <x-kaca-table-td thead="Фіскальний номер">{{ $cashRegister->fiscal_number }}</x-kaca-table-td>
                                    <x-kaca-table-td thead="Ключ ліцензії каси">{{ $cashRegister->licence_key }}</x-kaca-table-td>
                                    <x-kaca-table-td thead="Дата створення">{{ $cashRegister->created_at->format('Y-m-d H:m') }}</x-kaca-table-td>
                                    <x-kaca-table-td thead="Дії">
                                        <div class="min-w-auto flex">
                                            @can('delete', $cashRegister)
                                                <form action="{{ route('kaca.cash-registers.destroy', $cashRegister) }}" method="post"> @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-700 hover:text-red-800 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm text-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </x-kaca-table-td>
                                </x-kaca-table-tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-2">Не знайдено касирів</td></tr>
                            @endforelse
                        </x-kaca-table>

                        <div class="px-6 py-2">
                            {{ $cashRegisters->links() }}
                        </div>
                    </x-kaca-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
