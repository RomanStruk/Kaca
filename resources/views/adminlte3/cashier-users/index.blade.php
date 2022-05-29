<x-app-layout>
    <section class="content-header">
        <div class="container-fluid d-sm-flex flex-column align-items-start block-title">
            <div class="row col-md-12 ml-0">
                <h1 class="col-10 mb-2">
                    Всі користувачі сервісу checkbox.ua
                </h1>
                <div class="col-2">
                    @include('kaca::layouts.navigation')
                </div>
            </div>
            <ol class="breadcrumb row mb-2 ml-3">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.index') }}">Kaca</a></li>
                <li class="breadcrumb-item">Всі користувачі сервісу checkbox.ua</li>
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
                        <h3 class="card-title col-md-6">Всі користувачі сервісу checkbox.ua</h3>
                        <span class="col-md-6">
                            <a href="{{ route('kaca.cashier-users.create') }}" class="btn btn-default btn-sm float-right">Додати</a>
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Ім'я користувача</th>
                            <th>Права</th>
                            <th>Закріплений касир checkbox.ua</th>
                            <th>Закріплена каса checkbox.ua</th>
                            <th>Дії</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($cashierUsers as $cashierUser)
                            <tr class="odd">
                                <td >{{ $cashierUser->{\Kaca\Kaca::$userFieldName} }}</td>
                                <td>
                                    @if($cashierUser->can('seniorPermission'))
                                        <span class="badge bg-success">Адміністратор</span>
                                    @else
                                        <span class="badge bg-warning">Робота з чеками</span>
                                    @endif
                                </td>
                                <td>{{ $cashierUser->cashier->full_name }}</td>
                                <td>{{ $cashierUser->cashRegister->title }}</td>
                                <td>
                                    <div style="display: flex">
                                        <a class="btn btn-link btn-sm" href="{{ route('kaca.cashier-users.edit', $cashierUser) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg"  width="24px" height="24px" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('kaca.cashier-users.destroy', $cashierUser->id) }}" method="post"> @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-2">Не знайдено касирів</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $cashierUsers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
