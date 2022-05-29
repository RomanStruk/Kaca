<table class="table table-striped">
    <thead>
    <tr>
        <th>Статуси</th>
        <th>Тип</th>
        <th>Замовлення</th>
        <th>Сума</th>
        <th>Користувач</th>
        <th>Дата створення</th>
        <th>Дія</th>
    </tr>
    </thead>
    <tbody>
    @forelse($receipts as $receipt)
        <tr class="odd">
            <td class="dtr-control sorting_1" tabindex="0">
                @include('kaca::include.synchronization-status', ['status' => $receipt->synchronization->status])
                @include('kaca::receipts._status', ['receipt' => $receipt])
            </td>
            <td>
                @if($receipt->wasSold())
                    <div  style="color: green" title="Продаж">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                        </svg>
                    </div>
                @else
                    <div style="color: red" title="Повернення">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                        </svg>
                    </div>
                @endif
            </td>
            <td style="">
                @if($receipt->order_id)
                    <a href="{{route('kaca.receipts.index', ['order_id' => $receipt->order_id])}}">{{$receipt->order_id}}</a>
                @else
                    Не вказано
                @endif
            </td>
            <td style="">{{ $receipt->getTotalSum()->getPriceInUAH() }} ГРН</td>
            <td style="">{{ $receipt->creator->getCreatorName() }}</td>
            <td style="">{{ $receipt->created_at->format(config('kaca.date_format')) }}</td>
            <td style="">
                <a href="{{ route('kaca.receipts.show', $receipt) }}" class="btn btn-link">
                    <svg xmlns="http://www.w3.org/2000/svg"  width="24px" height="24px" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </td>
        </tr>
    @empty
        <tr><td colspan="9" class="px-6 py-2">Не знайдено чеків</td></tr>
    @endforelse
    </tbody>
</table>
<div class="px-6 py-2">
    {{ $receipts->links() }}
</div>
