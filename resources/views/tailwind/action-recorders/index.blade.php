<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Активність</h2>
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
                    <span class="text-grey-200 ml-1 md:ml-2 text-sm font-medium">Активність</span>
                </x-kaca-breadcrumb-item>
            </x-kaca-breadcrumbs>

            <x-kaca-validation-errors></x-kaca-validation-errors>

            <x-kaca-alerts></x-kaca-alerts>

            <x-kaca-card classBody="p-0">
                <x-slot name="header">Активність</x-slot>
                <x-kaca-table>
                    <x-slot name="thead">
                        <x-kaca-table-th>#</x-kaca-table-th>
                        <x-kaca-table-th>Теґ</x-kaca-table-th>
                        <x-kaca-table-th>Користувач</x-kaca-table-th>
                        <x-kaca-table-th>Ціль</x-kaca-table-th>
                        <x-kaca-table-th>Дата створення</x-kaca-table-th>
                        <x-kaca-table-th>Дія</x-kaca-table-th>
                    </x-slot>
                    @forelse($actions as $action)
                        <x-kaca-table-tr>
                            <x-kaca-table-td thead="#">{{ $action->id }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Теґ">{{ $action->tag }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Користувач">{{ $action->getCreatorName() }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Ціль">{{ $action->target }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Дата створення">{{ $action->created_at->format(config('kaca.date_format')) }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Дія">
                                <a href="{{ route('kaca.entries.index', ['search' => $action->target]) }}" class="text-blue-700 hover:text-blue-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </a>
                            </x-kaca-table-td>
                        </x-kaca-table-tr>
                    @empty
                        <tr><td colspan="9" class="px-6 py-2">Не знайдено чеків</td></tr>
                    @endforelse
                </x-kaca-table>
                <div class="px-6 py-2">
                    {{ $actions->links() }}
                </div>
            </x-kaca-card>
        </div>
    </div>
</x-app-layout>
