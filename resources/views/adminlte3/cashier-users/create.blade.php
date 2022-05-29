<x-app-layout>
    <section class="content-header">
        <div class="container-fluid d-sm-flex flex-column align-items-start block-title">
            <div class="row col-md-12 ml-0">
                <h1 class="col-10 mb-2">
                    Додати нового користувача касою
                </h1>
                <div class="col-2">
                    @include('kaca::layouts.navigation')
                </div>
            </div>
            <ol class="breadcrumb row mb-2 ml-3">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.index') }}">Kaca</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.cashier-users.index') }}">Всі користувачі сервісу checkbox.ua</a></li>
                <li class="breadcrumb-item">Додати нового користувача касою</li>
            </ol>
        </div>
    </section>
    <div class="content">
        <div class="container-fluid">

            @include('kaca::include.alerts')
            @include('kaca::include.validation-errors')

            <form action="{{route('kaca.cashier-users.store')}}" method="post">@csrf
                <div class="card card-default color-palette-box">
                    <div class="card-header">
                        <h3 class="card-title">Додати нового користувача касою</h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label>Користувач</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="-1">--</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @if($user->id == old('user_id')) selected @endif>{{ $user->{\Kaca\Kaca::$userFieldName} }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Касир з сервісу checkbox.ua</label>
                            <select name="cashier_id" id="cashier_id" class="form-control">
                                <option value="-1">--</option>
                                @foreach($cashiers as $cashier)
                                    <option value="{{ $cashier->id }}" @if($cashier->id == old('cashier_id')) selected @endif>{{ $cashier->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Каса з сервісу checkbox.ua</label>
                            <select name="cash_register_id" id="cash_register_id" class="form-control">
                                <option value="-1">--</option>
                                @foreach($cashRegisters as $cashRegister)
                                    <option value="{{ $cashRegister->id }}" @if($cashRegister->id == old('cash_register_id')) selected @endif>{{ $cashRegister->title }} - {{ $cashRegister->address }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Зберегти</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
