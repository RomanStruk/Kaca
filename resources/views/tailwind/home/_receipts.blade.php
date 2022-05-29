<x-kaca-table>
    <x-slot name="thead">
        <x-kaca-table-th>Статуси</x-kaca-table-th>
        <x-kaca-table-th>Тип</x-kaca-table-th>
        <x-kaca-table-th>Замовлення</x-kaca-table-th>
        <x-kaca-table-th>Сума</x-kaca-table-th>
        <x-kaca-table-th>Користувач</x-kaca-table-th>
        <x-kaca-table-th>Дата створення</x-kaca-table-th>
        <x-kaca-table-th>Дія</x-kaca-table-th>
    </x-slot>
    @forelse($receipts as $receipt)
        <x-kaca-table-tr>
            <x-kaca-table-td thead="Статуси">
                <div class="flex gap gap-x-2">
                    @include('kaca::include.synchronization-status', ['status' => $receipt->synchronization->status])
                    @include('kaca::receipts._status', ['receipt' => $receipt])
                </div>
            </x-kaca-table-td>
            <x-kaca-table-td thead="Тип">
                @if($receipt->wasSold())
                    <div class="text-green-600" title="Продаж">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                        </svg>
                    </div>
                @else
                    <div class="text-red-600" title="Повернення">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                        </svg>
                    </div>
                @endif
            </x-kaca-table-td>
            <x-kaca-table-td thead="Замовлення">
                @if($receipt->order_id)
                    <a href="{{route('kaca.receipts.index', ['order_id' => $receipt->order_id])}}">{{$receipt->order_id}}</a>
                @else
                    Не вказано
                @endif
            </x-kaca-table-td>
            <x-kaca-table-td thead="Сума">{{ $receipt->getTotalSum()->getPriceInUAH() }} ГРН</x-kaca-table-td>
            <x-kaca-table-td thead="Користувач">{{ $receipt->creator->getCreatorName() }}</x-kaca-table-td>
            <x-kaca-table-td thead="Дата створення">{{ $receipt->created_at->format(config('kaca.date_format')) }}</x-kaca-table-td>
            <x-kaca-table-td thead="Дія">
                <a href="{{ route('kaca.receipts.show', $receipt) }}" class="text-blue-700 hover:text-blue-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center">
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
    {{ $receipts->links() }}
</div>