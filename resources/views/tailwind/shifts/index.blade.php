<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Робочі зміни</h2>
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
                    <span class="text-grey-200 ml-1 md:ml-2 text-sm font-medium">Робочі зміни</span>
                </x-kaca-breadcrumb-item>
            </x-kaca-breadcrumbs>

            <x-kaca-validation-errors></x-kaca-validation-errors>

            <x-kaca-alerts></x-kaca-alerts>

            <x-kaca-card classBody="p-0">
                <x-slot name="header">Робочі зміни</x-slot>
                <x-kaca-table>
                    <x-slot name="thead">
                        <x-kaca-table-th>Статус</x-kaca-table-th>
                        <x-kaca-table-th>Номер зміни</x-kaca-table-th>
                        <x-kaca-table-th>Баланс</x-kaca-table-th>
                        <x-kaca-table-th>Касир</x-kaca-table-th>
                        <x-kaca-table-th>Каса</x-kaca-table-th>
                        <x-kaca-table-th>Синхронізація</x-kaca-table-th>
                        <x-kaca-table-th>Відкриття</x-kaca-table-th>
                        <x-kaca-table-th>Закриття</x-kaca-table-th>
                        <x-kaca-table-th>Дія</x-kaca-table-th>
                    </x-slot>
                    @forelse($shifts as $shift)
                        <x-kaca-table-tr :class="$shift->isClosed() ? 'text-gray-500' : ''">
                            <x-kaca-table-td thead="Статус">@include('kaca::shifts._status', ['status' => $shift->status])</x-kaca-table-td>
                            <x-kaca-table-td thead="Номер зміни">{{ $shift->serial }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Баланс">
                                <span class="font-bold rounded  px-2 ">{{ $shift->getBalance()->getPriceInUAHFormat() }} ₴</span>
                            </x-kaca-table-td>
                            <x-kaca-table-td thead="Касир">{{ $shift->cashier->full_name }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Каса">{{ $shift->cashRegister->title }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Синхронізація">
                                @include('kaca::include.synchronization-status', ['status' => $shift->synchronization->status])
                            </x-kaca-table-td>
                            <x-kaca-table-td thead="Відкриття">
                                @if(!is_null($shift->opened_at))
                                    {{ $shift->opened_at->format(config('kaca.date_format')) }}
                                @endif
                            </x-kaca-table-td>
                            <x-kaca-table-td thead="Закриття">
                                @if(!is_null($shift->closed_at))
                                    {{ $shift->closed_at->format(config('kaca.date_format')) }}
                                @endif
                            </x-kaca-table-td>
                            <x-kaca-table-td thead="Дія">
                                @can('close', $shift)
                                    <form action="{{route('kaca.shifts.destroy')}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-700 hover:text-red-800 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm text-center" onclick="return confirm('Ви впевнені що хочете закрити зміну?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endcan
                            </x-kaca-table-td>
                        </x-kaca-table-tr>
                    @empty
                        <tr><td colspan="9" class="px-6 py-2">Не знайдено чеків</td></tr>
                    @endforelse
                </x-kaca-table>
                <div class="px-6 py-2">
                    {{ $shifts->links() }}
                </div>
            </x-kaca-card>
        </div>
    </div>
</x-app-layout>
