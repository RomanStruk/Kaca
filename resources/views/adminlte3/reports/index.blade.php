<x-app-layout>
    <section class="content-header">
        <div class="container-fluid d-sm-flex flex-column align-items-start block-title">
            <div class="row col-md-12 ml-0">
                <h1 class="col-10 mb-2">
                    Список звітів
                </h1>
                <div class="col-2">
                    @include('kaca::layouts.navigation')
                </div>
            </div>
            <ol class="breadcrumb row mb-2 ml-3">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.index') }}">Kaca</a></li>
                <li class="breadcrumb-item">Список звітів</li>
            </ol>
        </div>
    </section>
    <div class="content">
        <div class="container-fluid">

            @include('kaca::include.alerts')
            @include('kaca::include.validation-errors')

            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <div class="row">
                        <h3 class="card-title col-md-6">Звіти</h3>
                        <div class="col-md-6">
                            @can('create', \Kaca\Models\Report::class)
                                <form action="{{ route('kaca.reports.store') }}" method="post"> @csrf
                                    <button type="submit" class="btn btn-primary btn-sm float-right">
                                        Сформувати X-звіт
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Тип</th>
                            <th>Зміна</th>
                            <th>Каса</th>
                            <th>Касир</th>
                            <th>Дата створення</th>
                            <th>Дія</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>{{ $report->serial }}</td>
                                <td>{{ $report->is_z_report ? 'Z-звіт' : 'X-звіт' }}</td>
                                <td>{{ $report->shift->serial }}</td>
                                <td>{{ $report->shift->cashRegister->title }}</td>
                                <td>{{ $report->shift->cashier->full_name }}</td>
                                <td>{{ $report->created_at->format(config('kaca.date_format')) }}</td>
                                <td>
                                    <a href="{{ route('kaca.reports.show', $report) }}" class="btn btn-link">
                                        <svg xmlns="http://www.w3.org/2000/svg"  width="24px" height="24px" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
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
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
