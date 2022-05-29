<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Каса</h2>
            @include('kaca::layouts.navigation')
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-kaca-breadcrumbs>
                <x-kaca-breadcrumb-item>
                    <a href="{{ route('kaca.index') }}" class="text-grey-700 hover:text-grey-900 ml-1 md:ml-2 text-sm font-medium">Kaca</a>
                </x-kaca-breadcrumb-item>
                <x-kaca-breadcrumb-item>
                    <span class="text-grey-200 ml-1 md:ml-2 text-sm font-medium">{{ __('Інформація') }}</span>
                </x-kaca-breadcrumb-item>
            </x-kaca-breadcrumbs>

            <x-kaca-validation-errors></x-kaca-validation-errors>

            <x-kaca-alerts></x-kaca-alerts>

            @include('kaca::home._info')

            <div class="flex justify-between shadow-md rounded-lg bg-white border-b border-gray-200 p-3 my-3">
                <div class="flex content-center">
                    <div class="py-2 flex gap gap-x-2">
                        <span>Зміна:</span>
                        @include('kaca::include.synchronization-status', ['status' => $shift->synchronization->status])
                        @if($shift->isOpen())
                            <span class="font-bold rounded bg-green-600 px-2 text-white">відкрита</span>
                            <span class="font-bold rounded  px-2 ">Каса: {{ $shift->getBalance()->getPriceInUAHFormat() }} ₴</span>
                        @else
                            <span class="font-bold rounded bg-red-600 px-2 text-white">закрита</span>
                        @endif
                    </div>
                </div>
                <div>
                    <form action="{{ route('kaca.receipts.index') }}" method="get">
                        <div class="flex">
                            <input class="ml-2 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm" id="order_id" type="text" placeholder="Введіть номер замовлення" name="order_id" value="">
                            <button type="submit" class="ml-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="flex">
                    @can('open', $shift)
                        <form action="{{route('kaca.shifts.store')}}" method="post">
                            @csrf
                            <div class="box-body">
                                <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 font-bold"> Відкрити зміну</button>
                            </div>
                        </form>
                    @endcan
                    @can('close', $shift)
                        <form action="{{route('kaca.shifts.destroy')}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 font-bold"  onclick="return confirm('Ви впевнені що хочете закрити зміну?');">Закрити зміну</button>
                        </form>
                    @endcan
                    @if($shift->id && ! \Kaca\Synchronization::isAvailable($shift->getUuid()))
                        <div class="content-center text-white bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2 text-center font-bold"><span class="inline-block align-middle">Зачекайте...</span></div>
                    @endif
                </div>
            </div>
            <div class="flex flex-row gap-4">
                <div class="w-3/4">
                    <x-kaca-card classBody="p-0">
                        <x-slot name="header">
                            <div class="flex justify-between">
                                <div>Чеки за зміну</div>
                                <a href="{{ route('kaca.receipts.create') }}"
                                   class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Додати</a>
                            </div>
                        </x-slot>
                        @include('kaca::home._receipts')
                    </x-kaca-card>
                </div>
                <div class="w-1/3">
                    <x-kaca-card>
                        <x-slot name="header">Деталі</x-slot>
                        @if($shift)
                            @if($shift->isOpen())
                                <div class="py-2 text-left block ">
                                    <span class="inline-block w-1/3 font-bold">Зміну відкрито о: </span>
                                    <div class="inline-block">
                                        {{ $shift->opened_at->format(config('kaca.date_format')) }}
                                    </div>
                                </div>
                            @endif
                            @if($shift->isClosed() && !is_null($shift->closed_at))
                                <div class="py-2 text-left block ">
                                    <span class="inline-block w-1/3 font-bold">Зміну закрито о: </span>
                                    <div class="inline-block">
                                        {{ $shift->closed_at->format(config('kaca.date_format')) }}
                                    </div>
                                </div>
                            @endif
                        @endif
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Касир:</span>
                            <div class="inline-block">
                                {{ $cashier->full_name }}
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Каса:</span>
                            <div class="inline-block">
                                {{ empty($cashRegister->title) ? $cashRegister->fiscal_number : $cashRegister->title }}
                            </div>
                        </div>
                    </x-kaca-card>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
