<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Редагування користувача #{{ $cashierUser->id }}</h2>
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
                    <a href="{{ route('kaca.cashier-users.index') }}" class="text-grey-700 hover:text-grey-900 ml-1 md:ml-2 text-sm font-medium">Всі користувачі сервісу checkbox.ua</a>
                </x-kaca-breadcrumb-item>
                <x-kaca-breadcrumb-item>
                    <span class="text-grey-200 ml-1 md:ml-2 text-sm font-medium">Редагування користувача #{{ $cashierUser->id }}</span>
                </x-kaca-breadcrumb-item>
            </x-kaca-breadcrumbs>

            <x-kaca-validation-errors></x-kaca-validation-errors>

            <x-kaca-alerts></x-kaca-alerts>

            <div class="flex flex-row gap-4">
                <div class="w-full">
                    <x-kaca-card>
                        <x-slot name="header">Редагування користувача {{ $cashierUser->{\Kaca\Kaca::$userFieldName} }}</x-slot>
                        <form action="{{route('kaca.cashier-users.update', $cashierUser)}}" method="post">
                            @csrf
                            <div >
                                <div class="">
                                    <x-kaca-label>Касир з сервісу checkbox.ua</x-kaca-label>
                                    <select name="cashier_id" id="cashier_id" class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" aria-label="Default select example">
                                        @foreach($cashiers as $cashier)
                                            <option value="{{ $cashier->id }}" @if($cashier->id == $cashierUser->cashier_id) selected @endif>{{ $cashier->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-4">
                                    <x-kaca-label>Каса з сервісу checkbox.ua</x-kaca-label>
                                    <select name="cash_register_id" id="cash_register_id" class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" aria-label="Default select example">
                                        @foreach($cashRegisters as $cashRegister)
                                            <option value="{{ $cashRegister->id }}" @if($cashRegister->id == $cashierUser->cash_register_id) selected @endif>{{ $cashRegister->title }} - {{ $cashRegister->address }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4 flex flex-row-reverse">
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Зберегти</button>
                            </div>
                        </form>
                    </x-kaca-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
