<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Список чеків</h2>
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
                    <span class="text-grey-200 ml-1 md:ml-2 text-sm font-medium">Чеки</span>
                </x-kaca-breadcrumb-item>
            </x-kaca-breadcrumbs>

            <x-kaca-validation-errors></x-kaca-validation-errors>

            <x-kaca-alerts></x-kaca-alerts>

            <x-kaca-card classBody="p-0">
                <x-slot name="header">
                    <div class="flex justify-between">
                        <div>Чеки</div>
                        <a href="{{ route('kaca.receipts.create') }}"
                           class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Додати</a>
                    </div>
                </x-slot>
                <x-kaca-table>
                    <x-slot name="thead">
                        <x-kaca-table-th>Користувач</x-kaca-table-th>
                        <x-kaca-table-th>Тип</x-kaca-table-th>
                        <x-kaca-table-th>Фіскальний код</x-kaca-table-th>
                        <x-kaca-table-th>Замовлення</x-kaca-table-th>
                        <x-kaca-table-th>Товарів</x-kaca-table-th>
                        <x-kaca-table-th>Синхронізація</x-kaca-table-th>
                        <x-kaca-table-th>Відправка</x-kaca-table-th>
                        <x-kaca-table-th>Сума</x-kaca-table-th>
                        <x-kaca-table-th>Дата створення</x-kaca-table-th>
                        <x-kaca-table-th>Дія</x-kaca-table-th>
                    </x-slot>
                    @forelse($receipts as $receipt)
                        <x-kaca-table-tr :class="$receipt->status == 'CREATED' ? 'bg-yellow-50': ($receipt->status == 'ERROR' ? 'bg-gray-100 text-gray-500' : '')">
                            <x-kaca-table-td thead="Користувач">{{ $receipt->creator->getCreatorName() }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Тип">
                                @if($receipt->wasSold())
                                    <div class="text-green-600 flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                                        </svg>
                                        <span class="">Продаж</span>
                                    </div>
                                @else
                                    <div class="text-red-600 flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                                        </svg>
                                        <span>Повернення</span>
                                    </div>
                                @endif
                            </x-kaca-table-td>
                            <x-kaca-table-td thead="Фіскальний код">{{ $receipt->fiscal_code ?? 'не отримано' }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Замовлення">
                                @if($receipt->order_id)
                                    <a href="{{route('kaca.receipts.index', ['order_id' => $receipt->order_id])}}">{{ $receipt->order_id }}</a>
                                @else
                                    не вказано
                                @endif
                            </x-kaca-table-td>
                            <x-kaca-table-td thead="Товарів">{{ $receipt->receiptGoods->count() }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Синхронізація">
                                @include('kaca::include.synchronization-status', ['status' => $receipt->synchronization->status])
                            </x-kaca-table-td>
                            <x-kaca-table-td thead="Відправка">
                                @include('kaca::receipts._status', ['receipt' => $receipt])
                            </x-kaca-table-td>
                            <x-kaca-table-td thead="Сума">
                                <div class="flex">
                                    <span>{{ $receipt->getTotalSum()->getPriceInUAH() }}</span><span>&nbsp;ГРН</span>
                                </div>
                            </x-kaca-table-td>
                            <x-kaca-table-td thead="Дата створення">{{ $receipt->created_at->format(config('kaca.date_format')) }}</x-kaca-table-td>
                            <x-kaca-table-td thead="Дія">
                                <a href="{{ route('kaca.receipts.show', $receipt) }}" class="text-blue-700 hover:text-blue-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                @if($receipt->isValid())
                                    <a href="{{ $receipt->getPath('pdf') }}" target="_blank" class="text-yellow-700 hover:text-blue-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center">
                                        <svg class="h-5 w-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                             width="550.801px" height="550.801px" viewBox="0 0 550.801 550.801" xml:space="preserve">
                                            <g>
                                                <path d="M160.381,282.225c0-14.832-10.299-23.684-28.474-23.684c-7.414,0-12.437,0.715-15.071,1.432V307.6 c3.114,0.707,6.942,0.949,12.192,0.949C148.419,308.549,160.381,298.74,160.381,282.225z"/>
                                                <path d="M272.875,259.019c-8.145,0-13.397,0.717-16.519,1.435v105.523c3.116,0.729,8.142,0.729,12.69,0.729 c33.017,0.231,54.554-17.946,54.554-56.474C323.842,276.719,304.215,259.019,272.875,259.019z"/>
                                                <path d="M488.426,197.019H475.2v-63.816c0-0.398-0.063-0.799-0.116-1.202c-0.021-2.534-0.827-5.023-2.562-6.995L366.325,3.694
                                                    c-0.032-0.031-0.063-0.042-0.085-0.076c-0.633-0.707-1.371-1.295-2.151-1.804c-0.231-0.155-0.464-0.285-0.706-0.419
                                                    c-0.676-0.369-1.393-0.675-2.131-0.896c-0.2-0.056-0.38-0.138-0.58-0.19C359.87,0.119,359.037,0,358.193,0H97.2
                                                    c-11.918,0-21.6,9.693-21.6,21.601v175.413H62.377c-17.049,0-30.873,13.818-30.873,30.873v160.545
                                                    c0,17.043,13.824,30.87,30.873,30.87h13.224V529.2c0,11.907,9.682,21.601,21.6,21.601h356.4c11.907,0,21.6-9.693,21.6-21.601
                                                    V419.302h13.226c17.044,0,30.871-13.827,30.871-30.87v-160.54C519.297,210.838,505.47,197.019,488.426,197.019z M97.2,21.605
                                                    h250.193v110.513c0,5.967,4.841,10.8,10.8,10.8h95.407v54.108H97.2V21.605z M362.359,309.023c0,30.876-11.243,52.165-26.82,65.333
                                                    c-16.971,14.117-42.82,20.814-74.396,20.814c-18.9,0-32.297-1.197-41.401-2.389V234.365c13.399-2.149,30.878-3.346,49.304-3.346
                                                    c30.612,0,50.478,5.508,66.039,17.226C351.828,260.69,362.359,280.547,362.359,309.023z M80.7,393.499V234.365
                                                    c11.241-1.904,27.042-3.346,49.296-3.346c22.491,0,38.527,4.308,49.291,12.928c10.292,8.131,17.215,21.534,17.215,37.328
                                                    c0,15.799-5.25,29.198-14.829,38.285c-12.442,11.728-30.865,16.996-52.407,16.996c-4.778,0-9.1-0.243-12.435-0.723v57.67H80.7
                                                    V393.499z M453.601,523.353H97.2V419.302h356.4V523.353z M484.898,262.127h-61.989v36.851h57.913v29.674h-57.913v64.848h-36.593
                                                    V232.216h98.582V262.127z"/>
                                            </g>
                                        </svg>
                                    </a>
                                @endif
                            </x-kaca-table-td>
                        </x-kaca-table-tr>
                    @empty
                        <tr><td colspan="9" class="px-6 py-2">Не знайдено чеків</td></tr>
                    @endforelse
                </x-kaca-table>
                <div class="px-6 py-2">
                    {{ $receipts->links() }}
                </div>
            </x-kaca-card>
        </div>
    </div>
</x-app-layout>
