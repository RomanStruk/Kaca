<x-app-layout>
    <section class="content-header">
        <div class="container-fluid d-sm-flex flex-column align-items-start block-title">
            <div class="row col-md-12 ml-0">
                <h1 class="col-10 mb-2">
                    Каса
                </h1>
                <div class="col-2">
                    @include('kaca::layouts.navigation')
                </div>
            </div>
            <ol class="breadcrumb row mb-2 ml-3">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.index') }}">Kaca</a></li>
                <li class="breadcrumb-item">{{ __('Інформація') }}</li>
            </ol>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">

            @include('kaca::include.alerts')
            @include('kaca::include.validation-errors')

            @include('kaca::home._info')
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">Каса</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <span class="p-2">Зміна:</span>
                                <div class="p-2">
                                    @include('kaca::include.synchronization-status', ['status' => $shift->synchronization->status])
                                </div>
                                @if($shift->isOpen())
                                    <span class="p-2">відкрита</span>
                                    <span class="rounded p-2 ">Каса: {{ $shift->getBalance()->getPriceInUAHFormat() }} ₴</span>
                                @else
                                    <span class="p-2">закрита</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <form action="{{ route('kaca.receipts.index') }}" method="get">
                                <div class="form-inline">
                                    <input class="form-control " id="order_id" type="text" placeholder="Введіть номер замовлення" name="order_id" value="">
                                    <button type="submit" class="btn btn-default">
                                        <svg xmlns="http://www.w3.org/2000/svg"  style="width: 16px; height: 16px; color: blue" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="flex float-right">
                                @can('open', $shift)
                                    <form action="{{route('kaca.shifts.store')}}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-success"> Відкрити зміну</button>
                                    </form>
                                @endcan
                                @can('close', $shift)
                                    <form action="{{route('kaca.shifts.destroy')}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"  onclick="return confirm('Ви впевнені що хочете закрити зміну?');">Закрити зміну</button>
                                    </form>
                                @endcan
                                @if($shift->id && ! \Kaca\Synchronization::isAvailable($shift->getUuid()))
                                    <div class="badge badge-warning">Зачекайте...</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="card card-default color-palette-box">
                        <div class="card-header">
                            <div class="row">
                                <h3 class="card-title col-md-6">Чеки за зміну</h3>
                                <span class="col-md-6">
                                    <a href="{{ route('kaca.receipts.create') }}" class="btn btn-default btn-sm float-right">Додати</a>
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @include('kaca::home._receipts')
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <dd class="card card-default color-palette-box">
                        <div class="card-header">
                            <h3 class="card-title">Деталі</h3>
                        </div>
                        <div class="card-body">
                            @if($shift)
                                @if($shift->isOpen())
                                    <dl class="row">
                                        <dt class="col-sm-4">Зміну відкрито о: </dt>
                                        <dd class="col-sm-8">
                                            {{ $shift->opened_at->format(config('kaca.date_format')) }}
                                        </dd>
                                    </dl>
                                @endif
                                @if($shift->isClosed() && !is_null($shift->closed_at))
                                    <dl class="row">
                                        <dt class="col-sm-4">Зміну закрито о: </dt>
                                        <dd class="col-sm-8">
                                            {{ $shift->closed_at->format(config('kaca.date_format')) }}
                                        </dd>
                                    </dl>
                                @endif
                            @endif
                            <dl class="row">
                                <dt class="col-sm-4">Касир:</dt>
                                <dd class="col-sm-8">{{ $cashier->full_name }}</dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-4">Каса:</dt>
                                <dd class="col-sm-8">
                                    {{ empty($cashRegister->title) ? $cashRegister->fiscal_number : $cashRegister->title }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
