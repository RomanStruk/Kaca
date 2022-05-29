<x-app-layout>
    <section class="content-header">
        <div class="container-fluid d-sm-flex flex-column align-items-start block-title">
            <div class="row col-md-12 ml-0">
                <h1 class="col-10 mb-2">
                    Інформація для налагодження
                </h1>
                <div class="col-2">
                    @include('kaca::layouts.navigation')
                </div>
            </div>
            <ol class="breadcrumb row mb-2 ml-3">
                <li class="breadcrumb-item"><a href="/">Головна</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kaca.index') }}">Kaca</a></li>
                <li class="breadcrumb-item">Інформація для налагодження</li>
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
                        <h3 class="card-title col-md-6">Інформація для налагодження</h3>
                        <div class="col-md-6">
                            <form action="{{ route('kaca.entries.index') }}" method="get" class="">
                                <div style="display: flex;">
                                    <input class="form-control form-control-sm"
                                           id="search"
                                           type="text"
                                           placeholder="Search content..."
                                           name="search"
                                           value="{{ request('search') }}"
                                    >
                                    <div class="input-group pl-2 input-group-sm">
                                        <input class="form-control "
                                               id="tag"
                                               type="text"
                                               placeholder="Enter Tag"
                                               name="tag"
                                               value="{{ request('tag') }}"
                                        >
                                    </div>
                                    <button type="submit" class="btn btn-link btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg"  width="24px" height="24px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('kaca::layouts.checkbox_entries', ['entries' => $entries])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
