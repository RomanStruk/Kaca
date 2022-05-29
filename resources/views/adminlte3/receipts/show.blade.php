<x-app-layout>
    <section class="content-header">
        <div class="container-fluid d-sm-flex flex-column align-items-start block-title">
            <div class="row col-md-12 ml-0">
                <h1 class="col-10 mb-2">
                    Перегляд чеку
                </h1>
                <div class="col-2">
                    @include('kaca::layouts.navigation')
                </div>
            </div>
            <ol class="breadcrumb row mb-2 ml-3">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.index') }}">Kaca</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.receipts.index') }}">Чеки</a></li>
                <li class="breadcrumb-item">Перегляд чеку</li>
            </ol>
        </div>
    </section>

    <div class="content">
        <div class="container-fluid">

            @include('kaca::include.alerts')
            @include('kaca::include.validation-errors')

            <div class="row">
                <div class="col-md-4">
                    <div class="card card-default color-palette-box">
                        <div class="card-header">
                            <h3 class="card-title">Перегляд чеку</h3>
                        </div>
                        <div class="card-body">
                            <div class="font-bold text-center mt-4">{{ $receipt->shift->cashier->full_name }}</div>
                            <div class="">* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *</div>
                            <ul>
                                @foreach($receipt->receiptGoods as $receiptGood)
                                    <li  style="display: flex; justify-content: space-between;">
                                        <div class="">{{$receiptGood->getName()}}</div>
                                        <div style="display: flex;">
                                            <div class="whitespace-nowrap mx-2 text-gray-500">x {{$receiptGood->getQuantity()->getQuantity()}}</div>
                                            <div class="whitespace-nowrap">{{ $receiptGood->getPrice()->getPriceInUAH() }} ГРН</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="">* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *</div>
                            @foreach($receipt->receiptPayments as $payment)
                                <div style="display: flex; justify-content: space-between;">
                                    <span>{{ $payment->getPaymentLabel() }}</span>
                                    <span>@if($receipt->wasRefunded()) - @endif
                                        {{ $payment->getPaymentValue()->getPriceInUAH() }} ГРН</span>
                                </div>
                            @endforeach
                            <div class="text-sm">
                                @if($receipt->wasSold()) Продаж @else Повернення @endif
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Сума</span>
                                <span>@if($receipt->wasRefunded()) - @endif
                                    {{ $receipt->getTotalSum()->getPriceInUAH() }} ГРН</span>
                            </div>
                            <div class="">* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-default color-palette-box">
                        <div class="card-header">
                            <h3 class="card-title">Деталі по чеку</h3>
                        </div>
                        <div class="card-body">
                            <div class="py-2 text-left block " style="display: block">
                                <span style="width: 33.333333%; font-weight: bold">Ідентифікатор</span>
                                <span>
                                    {{ $receipt->id }}
                                </span>
                            </div>
                            <div class="py-2 text-left block ">
                                <span style="width: 33.333333%; font-weight: bold">Користувач</span>
                                <span>
                                    {{ $receipt->creator->getCreatorName() }}
                                </span>
                            </div>
                            <div class="py-2 text-left block ">
                                <span style="width: 33.333333%; font-weight: bold">Фіскальний код</span>
                                <span>
                                    {{ $receipt->fiscal_code ?? 'не отримано' }}
                                </span>
                            </div>
                            <div class="py-2 text-left block ">
                                <span style="width: 33.333333%; font-weight: bold">Серійний номер</span>
                                <span>
                                    {{ $receipt->serial }}
                                </span>
                            </div>
                            <div class="py-2 text-left block ">
                                <span style="width: 33.333333%; font-weight: bold">Замовлення</span>
                                <span>
                                    @if($receipt->order_id)
                                        <a href="{{route('kaca.receipts.index', ['order_id' => $receipt->order_id])}}">{{ $receipt->order_id }}</a>
                                    @else
                                        Відсутньо
                                    @endif
                                </span>
                            </div>
                            <div class="py-2 text-left block ">
                                <span style="width: 33.333333%; font-weight: bold">Дата створення</span>
                                <span>{{ $receipt->created_at->format(config('kaca.date_format')) }}</span>
                            </div>
                            <div class="py-2 text-left block ">
                                <span style="width: 33.333333%; font-weight: bold">Отримувач</span>
                                <span >
                                    @foreach($receipt->delivery as $type => $deliveries)
                                        @foreach($deliveries as $delivery)
                                            <div class="text-gray-800">{{ $delivery }}</div>
                                        @endforeach
                                    @endforeach
                                </span>
                            </div>
                            <div class="py-2 text-left block ">
                                <span style="width: 33.333333%; font-weight: bold">Синхронізація</span>
                                <span style="display: inline-block">
                                    @include('kaca::include.synchronization-status', ['status' => $receipt->synchronization->status])
                                </span>
                            </div>
                            <div class="py-2 text-left block ">
                                <span style="width: 33.333333%; font-weight: bold">Відправка</span>
                                <span style="display: inline-block">
                                    @include('kaca::receipts._status', ['receipt' => $receipt])
                                </span>
                            </div>
                            <div class="py-2 text-left block ">
                                <span style="width: 33.333333%; font-weight: bold">Дії</span>
                                <span style="display: inline-block">
                                        @can('beReturned', $receipt)
                                            <form action="{{ route('kaca.refund-receipts.store', $receipt) }}" method="post" style="display: inline-block"> @csrf
                                                <button type="submit" class="btn btn-danger" style="display: inline-block"
                                                        onclick="return confirm('Ви впевнені що хочете повернути чек?');"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                                                    </svg>
                                                    Повернути
                                                </button>
                                            </form>
                                        @endcan
                                        @if($receipt->isValid())
                                            <a href="{{ $receipt->getPath('pdf') }}" target="_blank" class="btn btn-link" style="display: inline-block">
                                                <svg  width="24px" height="24px" fill="currentColor" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                      viewBox="0 0 550.801 550.801" xml:space="preserve">
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
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    @can('seniorPermission')
                        <div class="card card-default color-palette-box">
                            <div class="card-header">
                                <h3 class="card-title">Зміна з сервісу checkbox.ua</h3>
                            </div>
                            <div class="card-body">
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
                            </div>
                        </div>
                        <div class="card card-default color-palette-box">
                            <div class="card-header">
                                <h3 class="card-title">Касир з сервісу checkbox.ua</h3>
                            </div>
                            <div class="card-body">
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
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
                @can('developerPermission')
                <div class="card card-default color-palette-box">
                    <div class="card-header">
                        <h3 class="card-title">Інформація для налагодження</h3>
                    </div>
                    <div class="card-body">
                        @include('kaca::layouts.checkbox_entries', ['entries' => $entries])
                    </div>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>
