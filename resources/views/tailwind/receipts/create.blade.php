<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Створити чек</h2>
            @include('kaca::layouts.navigation')
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-kaca-breadcrumbs>
                <x-kaca-breadcrumb-item>
                    <a href="{{ route('kaca.index') }}"
                       class="text-grey-700 hover:text-grey-900 ml-1 md:ml-2 text-sm font-medium">Kaca</a>
                </x-kaca-breadcrumb-item>
                <x-kaca-breadcrumb-item>
                    <a href="{{ route('kaca.receipts.index') }}"
                       class="text-grey-700 hover:text-grey-900 ml-1 md:ml-2 text-sm font-medium">Чеки</a>
                </x-kaca-breadcrumb-item>
                <x-kaca-breadcrumb-item>
                    <span class="text-grey-200 ml-1 md:ml-2 text-sm font-medium">Створити чек</span>
                </x-kaca-breadcrumb-item>
            </x-kaca-breadcrumbs>

            <x-kaca-validation-errors></x-kaca-validation-errors>

            <x-kaca-alerts></x-kaca-alerts>

            <div class="grid gap-4 md:grid-cols-2 grid-cols-1">
                <x-kaca-card>
                    <x-slot name="header">Продаж товару</x-slot>
                    <form action="{{ route('kaca.receipts.create') }}">
                        @foreach(request('goods', []) as $key => $receiptGood)
                            <input type="hidden" name="goods[{{ $key }}][code]"
                                   value="{{ $receiptGood['code'] ?? '' }}">
                            <input type="hidden" name="goods[{{ $key }}][name]"
                                   value="{{ $receiptGood['name'] ?? '' }}">
                            <input type="hidden" name="goods[{{ $key }}][quantity]"
                                   value="{{ $receiptGood['quantity'] ?? 1 }}">
                            <input type="hidden" name="goods[{{ $key }}][price]"
                                   value="{{ $receiptGood['price'] ?? 1 }}">
                        @endforeach
                        <div class="flex gap gap-x-2">
                            <div class="w-20">Код</div>
                            <div class="w-full">Найменування</div>
                            <div class="w-16">К-сть</div>
                            <div class="w-20">Ціна</div>
                        </div>
                        <div class="flex gap gap-x-2">
                            <div class="w-20">
                                <input type="text" name="goods[{{ ($key ?? 0) + 1 }}][code]"
                                       class="px-2 py-2 border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                            <div class="w-full">
                                <input type="text" name="goods[{{ ($key ?? 0) + 1 }}][name]"
                                       class="px-2 py-2 border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                            <div class="w-16">
                                <input type="text" name="goods[{{ ($key ?? 0) + 1 }}][quantity]"
                                       class="px-2 py-2 border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                            <div class="w-20">
                                <input type="text" name="goods[{{ ($key ?? 0) + 1 }}][price]"
                                       class="px-2 py-2 border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Додати
                            </button>
                        </div>
                    </form>
                </x-kaca-card>
                <x-kaca-card>
                    <x-slot name="header">Перегляд чеку</x-slot>
                    <form action="{{ route('kaca.receipts.store') }}" method="post"> @csrf
                        <div class="bg-gray-200 p-2 rounded grid gap-y-4 grid-cols-1">
                            <div class="font-bold text-center mt-4 uppercase">Чек</div>
                            <div class="overflow-hidden text-clip h-6 text-gray-500 my-2">* * * * * * * * * * * * * * * * *
                                * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
                            </div>
                            <ul>
                                @foreach(request('goods', []) as $key => $good)
                                    <input type="hidden" name="goods[{{ $key }}][code]"
                                           value="{{ $good['code'] ?? '' }}">
                                    <input type="hidden" name="goods[{{ $key }}][name]"
                                           value="{{ $good['name'] ?? '' }}">
                                    <input type="hidden" name="goods[{{ $key }}][quantity]"
                                           value="{{ $good['quantity'] ?? 1 }}">
                                    <input type="hidden" name="goods[{{ $key }}][price]"
                                           value="{{ $good['price'] ?? 1 }}">
                                    <li class="flex justify-between">
                                        <div class="">{{ $good['name'] ?? '' }}</div>
                                        <div class="flex">
                                            <div class="whitespace-nowrap mx-2 text-gray-500">
                                                x {{ $good['quantity'] ?? 1 }}</div>
                                            <div class="whitespace-nowrap">{{ $good['price'] ?? 1 }} ГРН</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="overflow-hidden text-clip h-6 text-gray-500 my-2">* * * * * * * * * * * * * * * * *
                                * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
                            </div>
                            <div class="flex justify-between font-bold"><span>Сума</span><span>{{ $totalSum }} ГРН</span></div>
                            <div class="overflow-hidden text-clip h-6 text-gray-500 my-2">* * * * * * * * * * * * * * * * *
                                * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
                            </div>
                            <div class="flex justify-between">
                                <x-kaca-input type="email" name="deliveries[emails][]" placeholder="Вкажіть email отримувача"></x-kaca-input>
                            </div>

                            <div class="overflow-hidden text-clip h-6 text-gray-500 my-2">* * * * * * * * * * * * * * * * *
                                * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
                            </div>
                            <input type="hidden" name="reverse_compatibility_data" value="">
                            <input type="hidden" name="order_id" value="">
                            <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            @cannot('create', \Kaca\Models\Receipt::class)
                                disabled
                            @endcannot
                            >Видати чек</button>
                        </div>
                    </form>
                </x-kaca-card>
            </div>
        </div>
    </div>
</x-app-layout>