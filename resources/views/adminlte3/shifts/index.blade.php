<x-app-layout>
    <section class="content-header">
        <div class="container-fluid d-sm-flex flex-column align-items-start block-title">
            <div class="row col-md-12 ml-0">
                <h1 class="col-10 mb-2">
                    Робочі зміни
                </h1>
                <div class="col-2">
                    @include('kaca::layouts.navigation')
                </div>
            </div>
            <ol class="breadcrumb row mb-2 ml-3">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.index') }}">Kaca</a></li>
                <li class="breadcrumb-item">Робочі зміни</li>
            </ol>
        </div>
    </section>
    <div class="content">
        <div class="container-fluid">

            @include('kaca::include.alerts')
            @include('kaca::include.validation-errors')

            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">Робочі зміни</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Статус</th>
                                <th>Номер зміни</th>
                                <th>Баланс</th>
                                <th>Касир</th>
                                <th>Каса</th>
                                <th>Синхронізація</th>
                                <th>Відкриття</th>
                                <th>Закриття</th>
                                <th>Дія</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shifts as $shift)
                                <tr style="color: {{$shift->isClosed() ? 'gray' : 'black'}}">
                                    <td>@include('kaca::shifts._status', ['status' => $shift->status])</td>
                                    <td>{{ $shift->serial }}</td>
                                    <td>
                                        <span class="font-bold rounded  px-2 ">{{ $shift->getBalance()->getPriceInUAHFormat() }} ₴</span>
                                    </td>
                                    <td>{{ $shift->cashier->full_name }}</td>
                                    <td>{{ $shift->cashRegister->title }}</td>
                                    <td>
                                        @include('kaca::include.synchronization-status', ['status' => $shift->synchronization->status])
                                    </td>
                                    <td>
                                        @if(!is_null($shift->opened_at))
                                            {{ $shift->opened_at->format(config('kaca.date_format')) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!is_null($shift->closed_at))
                                            {{ $shift->closed_at->format(config('kaca.date_format')) }}
                                        @endif
                                    </td>
                                    <td>
                                        @can('close', $shift)
                                            <form action="{{route('kaca.shifts.destroy')}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger btn-sm" onclick="return confirm('Ви впевнені що хочете закрити зміну?');">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="px-6 py-2">Не знайдено чеків</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $shifts->links() }}
                </div>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
