<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Додати касира з сервісу checkbox.ua</h2>
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
                    <a href="{{ route('kaca.cashiers.index') }}" class="text-grey-700 hover:text-grey-900 ml-1 md:ml-2 text-sm font-medium">Всі касири з сервісу checkbox.ua</a>
                </x-kaca-breadcrumb-item>
                <x-kaca-breadcrumb-item>
                    <span class="text-grey-200 ml-1 md:ml-2 text-sm font-medium">Додати касира з сервісу checkbox.ua</span>
                </x-kaca-breadcrumb-item>
            </x-kaca-breadcrumbs>

            <x-kaca-validation-errors></x-kaca-validation-errors>

            <x-kaca-alerts></x-kaca-alerts>

            <div class="flex flex-row gap-4">
                <div class="w-full">
                    <x-kaca-card>
                        <x-slot name="header">Додати касира з сервісу checkbox.ua</x-slot>
                        <form action="{{route('kaca.cashiers.store')}}" method="post">
                            @csrf
                            <div >
                                <div>
                                    <x-kaca-label>Логін касира checkbox.ua</x-kaca-label>
                                    <x-kaca-input name="login" placeholder="Введіть логін"></x-kaca-input>
                                </div>
                                <div class="mt-2" >
                                    <x-kaca-label>Пароль касира checkbox.ua</x-kaca-label>
                                    <x-kaca-input type="password" placeholder="Введіть пароль" name="password"></x-kaca-input>
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
