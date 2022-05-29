<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Перегляд чеку</h2>
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
                    <a href="{{ route('kaca.receipts.index') }}" class="text-grey-700 hover:text-grey-900 ml-1 md:ml-2 text-sm font-medium">Чеки</a>
                </x-kaca-breadcrumb-item>
                <x-kaca-breadcrumb-item>
                    <span class="text-grey-200 ml-1 md:ml-2 text-sm font-medium">Перегляд чеку</span>
                </x-kaca-breadcrumb-item>
            </x-kaca-breadcrumbs>

            <x-kaca-validation-errors></x-kaca-validation-errors>

            <x-kaca-alerts></x-kaca-alerts>

            <div class="grid gap-4 md:grid-cols-2 grid-cols-1">
                <x-kaca-card>
                    <x-slot name="header">Перегляд чеку</x-slot>
                    <div class="bg-gray-200 p-2 rounded grid gap-y-4 grid-cols-1">
                        <div class="font-bold text-center mt-4 uppercase">{{ $receipt->shift->cashier->full_name }}</div>
                        <div class="overflow-hidden text-clip h-6 text-gray-500 my-2">* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *</div>
                        <ul>
                            @foreach($receipt->receiptGoods as $receiptGood)
                                <li class="flex justify-between">
                                    <div class="">{{$receiptGood->getName()}}</div>
                                    <div class="flex">
                                        <div class="whitespace-nowrap mx-2 text-gray-500">x {{$receiptGood->getQuantity()->getQuantity()}}</div>
                                        <div class="whitespace-nowrap">{{ $receiptGood->getPrice()->getPriceInUAH() }} ГРН</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="overflow-hidden text-clip h-6 text-gray-500 my-2">* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *</div>
                        @foreach($receipt->receiptPayments as $payment)
                            <div class="flex justify-between">
                                <span>{{ $payment->getPaymentLabel() }}</span>
                                <span>@if($receipt->wasRefunded()) - @endif
                                    {{ $payment->getPaymentValue()->getPriceInUAH() }} ГРН</span>
                            </div>
                        @endforeach
                        <div class="text-sm">
                            @if($receipt->wasSold()) Продаж @else Повернення @endif
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>Сума</span>
                            <span>@if($receipt->wasRefunded()) - @endif
                                {{ $receipt->getTotalSum()->getPriceInUAH() }} ГРН</span>
                        </div>
                        <div class="overflow-hidden text-clip h-6 text-gray-500 my-2">* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *</div>
                    </div>
                </x-kaca-card>
                <x-kaca-card>
                    <x-slot name="header">Деталі по чеку</x-slot>
                    <div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Ідентифікатор</span>
                            <div class="inline-block">
                                {{ $receipt->id }}
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Користувач</span>
                            <div class="inline-block">
                                {{ $receipt->creator->getCreatorName() }}
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Фіскальний код</span>
                            <div class="inline-block">
                                {{ $receipt->fiscal_code ?? 'не отримано' }}
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Серійний номер</span>
                            <div class="inline-block">
                                {{ $receipt->serial }}
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Замовлення</span>
                            <div class="inline-block">
                                @if($receipt->order_id)
                                    <a href="{{route('kaca.receipts.index', ['order_id' => $receipt->order_id])}}">{{ $receipt->order_id }}</a>
                                @else
                                    Відсутньо
                                @endif
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Дата створення</span>
                            <div class="inline-block">{{ $receipt->created_at->format(config('kaca.date_format')) }}</div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Отримувач</span>
                            <div class="inline-block">
                                @foreach($receipt->delivery as $type => $deliveries)
                                    @foreach($deliveries as $delivery)
                                        <div class="text-gray-800">{{ $delivery }}</div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Синхронізація</span>
                            <div class="inline-block">
                                @include('kaca::include.synchronization-status', ['status' => $receipt->synchronization->status])
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Відправка</span>
                            <div class="inline-block">
                                @include('kaca::receipts._status', ['receipt' => $receipt])
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Дії</span>
                            <div class="inline-block">
                                <div class="flex gap gap-x-2">
                                    @can('beReturned', $receipt)
                                        <form action="{{ route('kaca.refund-receipts.store', $receipt) }}" method="post"> @csrf
                                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex gap gap-x-2"
                                                    onclick="return confirm('Ви впевнені що хочете повернути чек?');"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                                                </svg>
                                                Повернути
                                            </button>
                                        </form>
                                    @endcan
                                    @if($receipt->isValid())
                                        <a href="{{ $receipt->getPath('pdf') }}" target="_blank" class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800 flex gap gap-x-2">
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
                                            Завантажити
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </x-kaca-card>
                @can('seniorPermission')
                    <x-kaca-card>
                        <x-slot name="header">Зміна з сервісу checkbox.ua </x-slot>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Ідентифікатор</span>
                            <div class="inline-block">
                                {{ $shift->id }}
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Номер зміни</span>
                            <div class="inline-block">
                                {{ $shift->serial }}
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Відкриття</span>
                            <div class="inline-block">
                                @if(!is_null($shift->opened_at))
                                    {{ $shift->opened_at->format(config('kaca.date_format')) }}
                                @endif
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Закриття</span>
                            <div class="inline-block">
                                @if(!is_null($shift->closed_at))
                                    {{ $shift->closed_at->format(config('kaca.date_format')) }}
                                @endif
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Каса</span>
                            <div class="inline-block">
                                {{ empty($shift->cashRegister->title) ? $shift->cashRegister->address : $shift->cashRegister->title }}
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Синхронізація</span>
                            <div class="inline-block">
                                @include('kaca::include.synchronization-status', ['status' => $shift->synchronization->status])
                            </div>
                        </div>
                    </x-kaca-card>
                    <x-kaca-card>
                        <x-slot name="header">Касир з сервісу checkbox.ua</x-slot>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Ім'я касира</span>
                            <div class="inline-block">
                                {{ $cashier->full_name }}
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Дата закінчення сертифікату </span>
                            <div class="inline-block">
                                @if(!is_null($cashier->certificate_end))
                                    {{ $cashier->certificate_end->format(config('kaca.date_format')) }}
                                @endif
                            </div>
                        </div>
                    </x-kaca-card>
                @endcan
            </div>
            @can('developerPermission')
                <x-kaca-card>
                    <x-slot name="header">Інформація для налагодження</x-slot>
                    @include('kaca::layouts.checkbox_entries', ['entries' => $entries])
                </x-kaca-card>
            @endcan
        </div>
    </div>
</x-app-layout>