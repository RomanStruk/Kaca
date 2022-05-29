<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Список звітів</h2>
            @include('kaca::layouts.navigation')
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-kaca-breadcrumbs>
                <x-kaca-breadcrumb-item>
                    <a href="{{ route('kaca.index') }}" class="text-grey-700 hover:text-grey-900 ml-1 md:ml-2 text-sm font-medium">Kaca</a>
                </x-kaca-breadcrumb-item>
                <x-kaca-breadcrumb-item>
                    <span class="text-grey-200 ml-1 md:ml-2 text-sm font-medium">Звіти</span>
                </x-kaca-breadcrumb-item>
            </x-kaca-breadcrumbs>

            <x-kaca-validation-errors></x-kaca-validation-errors>

            <x-kaca-alerts></x-kaca-alerts>

            <x-kaca-card classBody="p-0">
                <x-slot name="header">
                    <div class="flex justify-between">
                        <div>Звіти</div>
                        @can('create', \Kaca\Models\Report::class)
                            <form action="{{ route('kaca.reports.store') }}" method="post"> @csrf
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Сформувати X-звіт
                                </button>
                            </form>
                        @endcan
                    </div>
                </x-slot>
                <x-kaca-table>
                    <x-slot name="thead">
                        <x-kaca-table-th>№</x-kaca-table-th>
                        <x-kaca-table-th>Тип</x-kaca-table-th>
                        <x-kaca-table-th>Зміна</x-kaca-table-th>
                        <x-kaca-table-th>Каса</x-kaca-table-th>
                        <x-kaca-table-th>Касир</x-kaca-table-th>
                        <x-kaca-table-th>Дата створення</x-kaca-table-th>
                        <x-kaca-table-th>Дія</x-kaca-table-th>
                    </x-slot>
                    @forelse($reports as $report)
                        <x-kaca-table-tr>
                            <x-kaca-table-td thead="№">{{ $report->serial }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Тип">{{ $report->is_z_report ? 'Z-звіт' : 'X-звіт' }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Зміна">{{ $report->shift->serial }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Каса">{{ $report->shift->cashRegister->title }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Касир">{{ $report->shift->cashier->full_name }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Дата створення">{{ $report->created_at->format(config('kaca.date_format')) }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Дія">
                                <a href="{{ route('kaca.reports.show', $report) }}" class="text-blue-700 hover:text-blue-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </x-kaca-table-td>
                        </x-kaca-table-tr>
                    @empty
                        <tr><td colspan="9" class="px-6 py-2">Не знайдено чеків</td></tr>
                    @endforelse
                </x-kaca-table>
                <div class="px-6 py-2">
                    {{ $reports->links() }}
                </div>
            </x-kaca-card>
        </div>
    </div>
</x-app-layout>
