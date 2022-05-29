<x-app-layout>
    <section class="content-header">
        <div class="container-fluid d-sm-flex flex-column align-items-start block-title">
            <div class="row col-md-12 ml-0">
                <h1 class="col-10 mb-2">
                    Створити чек
                </h1>
                <div class="col-2">
                    @include('kaca::layouts.navigation')
                </div>
            </div>
            <ol class="breadcrumb row mb-2 ml-3">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.index') }}">Kaca</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.receipts.index') }}">Чеки</a></li>
                <li class="breadcrumb-item">Створити чек</li>
            </ol>
        </div>
    </section>
    <div class="content">
        <div class="container-fluid">

            @include('kaca::include.alerts')
            @include('kaca::include.validation-errors')

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-default color-palette-box">
                        <div class="card-header">
                            <h3 class="card-title">Продаж товару</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('kaca.receipts.create') }}">
                                @foreach(request('goods', []) as $key => $receiptGood)
                                    <input type="hidden" name="goods[{{ $key }}][code]"
                                           value="{{ $receiptGood['code'] ?? '' }}">
                                    <input type="hidden" name="goods[{{ $key }}][name]"
                                           value="{{ $receiptGood['name'] ?? '' }}">
                                    <input type="hidden" name="goods[{{ $key }}][quantity]"
                                           value="{{ $receiptGood['quantity'] ?? 1 }}">
                                    <input type="hidden" name="goods[{{ $key }}][price]"
                                           value="{{ $receiptGood['price'] ?? 1 }}">
                                @endforeach
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Код</th>
                                            <th>Найменування</th>
                                            <th>К-сть</th>
                                            <th>Ціна</th>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td>
                                                <input type="text" name="goods[{{ ($key ?? 0) + 1 }}][code]"
                                                       class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="goods[{{ ($key ?? 0) + 1 }}][name]"
                                                       class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="goods[{{ ($key ?? 0) + 1 }}][quantity]"
                                                       class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="goods[{{ ($key ?? 0) + 1 }}][price]"
                                                       class="form-control">
                                            </td>
                                        </tr>
                                        <tbody>
                                        </tbody>
                                    </table>
                                <div>
                                    <button type="submit" class="btn btn-primary">Додати</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-default color-palette-box">
                        <div class="card-header">
                            <h3 class="card-title">Перегляд чеку</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('kaca.receipts.store') }}" method="post"> @csrf
                                <div class="bg-gray-200 p-2 rounded grid gap-y-4 grid-cols-1">
                                    <div class="font-bold text-center mt-4">Чек</div>
                                    <div class="">* * * * * * * * * * * * * * *
                                        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
                                    </div>
                                    <ul>
                                        @foreach(request('goods', []) as $key => $good)
                                            <input type="hidden" name="goods[{{ $key }}][code]" value="{{ $good['code'] ?? '' }}">
                                            <input type="hidden" name="goods[{{ $key }}][name]" value="{{ $good['name'] ?? '' }}">
                                            <input type="hidden" name="goods[{{ $key }}][quantity]" value="{{ $good['quantity'] ?? 1 }}">
                                            <input type="hidden" name="goods[{{ $key }}][price]" value="{{ $good['price'] ?? 1 }}">
                                            <li style="display: flex; justify-content: space-between;">
                                                <div class="">{{ $good['name'] ?? '' }}</div>
                                                <div style="display: flex;">
                                                    <div class="">
                                                        x {{ $good['quantity'] ?? 1 }}</div>
                                                    <div class="">{{ $good['price'] ?? 1 }} ГРН</div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="">* * * * * * * * * * * * * * * * *
                                        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
                                    </div>
                                    <div style="display: flex; justify-content: space-between;"><span>Сума</span><span>{{ $totalSum }} ГРН</span></div>
                                    <div class="overflow-hidden text-clip h-6 text-gray-500 my-2">* * * * * * * * * * * * * * * * *
                                        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="email" name="deliveries[emails][]" placeholder="Вкажіть email отримувача">
                                    </div>

                                    <div class="">* * * * * * * * * * * * * * * * *
                                        * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
                                    </div>
                                    <input type="hidden" name="reverse_compatibility_data" value="">
                                    <input type="hidden" name="order_id" value="">
                                    <button class="btn btn-success" style="width: 100%"
                                        @cannot('create', \Kaca\Models\Receipt::class)
                                            disabled
                                        @endcannot
                                    >Видати чек</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
