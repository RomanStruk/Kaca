<x-app-layout>
    <section class="content-header">
        <div class="container-fluid d-sm-flex flex-column align-items-start block-title">
            <div class="row col-md-12 ml-0">
                <h1 class="col-10 mb-2">
                    Додати нову касу
                </h1>
                <div class="col-2">
                    @include('kaca::layouts.navigation')
                </div>
            </div>
            <ol class="breadcrumb row mb-2 ml-3">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.index') }}">Kaca</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.cash-registers.index') }}">Всі каси</a></li>
                <li class="breadcrumb-item">Додати нову касу</li>
            </ol>
        </div>
    </section>
    <div class="content">
        <div class="container-fluid">

            @include('kaca::include.alerts')
            @include('kaca::include.validation-errors')

            <form action="{{route('kaca.cash-registers.store')}}" method="post">@csrf
                <div class="card card-default color-palette-box">
                    <div class="card-header">
                        <h3 class="card-title">Додати нову касу</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Ключ ліцензії каси</label>
                            <input class="form-control" placeholder="Введіть ключ ліцензії" name="licence_key" value="{{ old('licence_key') }}">
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
