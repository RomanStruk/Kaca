<x-app-layout>
    <section class="content-header">
        <div class="container-fluid d-sm-flex flex-column align-items-start block-title">
            <div class="row col-md-12 ml-0">
                <h1 class="col-10 mb-2">
                    Активність
                </h1>
                <div class="col-2">
                    @include('kaca::layouts.navigation')
                </div>
            </div>
            <ol class="breadcrumb row mb-2 ml-3">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.index') }}">Kaca</a></li>
                <li class="breadcrumb-item">Активність</li>
            </ol>
        </div>
    </section>
    <div class="content">
        <div class="container-fluid">

            @include('kaca::include.alerts')
            @include('kaca::include.validation-errors')

            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">Активність</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Теґ</th>
                            <th>Користувач</th>
                            <th>Ціль</th>
                            <th>Дата створення</th>
                            <th>Дія</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($actions as $action)
                            <tr class="odd">
                                <td>{{ $action->id }}</td>
                                <td>{{ $action->tag }}</td>
                                <td>{{ $action->getCreatorName() }}</td>
                                <td>{{ $action->target }}</td>
                                <td>{{ $action->created_at->format(config('kaca.date_format')) }}</td>
                                <td>
                                    <a href="{{ route('kaca.entries.index', ['search' => $action->target]) }}" class="btn btn-link btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-6 py-2">Не знайдено чеків</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $actions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
