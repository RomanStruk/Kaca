<x-app-layout>
    <section class="content-header">
        <div class="container-fluid d-sm-flex flex-column align-items-start block-title">
            <div class="row col-md-12 ml-0">
                <h1 class="col-10 mb-2">
                    Додати касира з сервісу checkbox.ua
                </h1>
                <div class="col-2">
                    @include('kaca::layouts.navigation')
                </div>
            </div>
            <ol class="breadcrumb row mb-2 ml-3">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.index') }}">Kaca</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.cashiers.index') }}">Всі касири з сервісу checkbox.ua</a></li>
                <li class="breadcrumb-item">Додати касира з сервісу checkbox.ua</li>
            </ol>
        </div>
    </section>
    <div class="content">
        <div class="container-fluid">

            @include('kaca::include.alerts')
            @include('kaca::include.validation-errors')

            <form action="{{route('kaca.cashiers.store')}}" method="post">@csrf
                <div class="card card-default color-palette-box">
                    <div class="card-header">
                        <h3 class="card-title">Додати касира з сервісу checkbox.ua</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Логін касира checkbox.ua</label>
                            <input class="form-control" name="login" placeholder="Введіть логін">
                        </div>
                        <div class="form-group" >
                            <label>Пароль касира checkbox.ua</label>
                            <input class="form-control" type="password" placeholder="Введіть пароль" name="password">
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
