<x-app-layout>
    <section class="content-header">
        <div class="container-fluid d-sm-flex flex-column align-items-start block-title">
            <div class="row col-md-12 ml-0">
                <h1 class="col-10 mb-2">
                    Перегляд звіту
                </h1>
                <div class="col-2">
                    @include('kaca::layouts.navigation')
                </div>
            </div>
            <ol class="breadcrumb row mb-2 ml-3">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.index') }}">Kaca</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.reports.index') }}">Звіти</a></li>
                <li class="breadcrumb-item">Перегляд звіту</li>
            </ol>
        </div>
    </section>
    <div class="content">
        <div class="container-fluid">

            @include('kaca::include.alerts')
            @include('kaca::include.validation-errors')

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-default color-palette-box">
                        <div class="card-header">
                            <h3 class="card-title">Текстове представлення чеку</h3>
                        </div>
                        <div class="card-body">
                            {!! str_replace("\n", '<br>', $text) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-default color-palette-box">
                        <div class="card-header">
                            <h3 class="card-title">Деталі зміни</h3>
                        </div>
                        <div class="card-body">
                            @if($report->shift->isOpen())
                                <div class="row">
                                    <div class="col-md-4">Зміну відкрито о: </div>
                                    <div class="col-md-8">
                                        {{ $report->shift->opened_at->format(config('kaca.date_format')) }}
                                    </div>
                                </div>
                            @endif
                            @if($report->shift->isClosed() && !is_null($report->shift->closed_at))
                                <div class="row">
                                    <div class="col-md-4">Зміну закрито о: </div>
                                    <div class="col-md-8">
                                        {{ $report->shift->closed_at->format(config('kaca.date_format')) }}
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-4">Касир:</div>
                                <div class="col-md-8">
                                    {{ $report->shift->cashier->full_name }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">Каса:</div>
                                <div class="col-md-8">
                                    {{ $report->shift->cashRegister->title }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
