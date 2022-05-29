<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Перегляд звіту</h2>
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
                    <a href="{{ route('kaca.reports.index') }}" class="text-grey-700 hover:text-grey-900 ml-1 md:ml-2 text-sm font-medium">Звіти</a>
                </x-kaca-breadcrumb-item>
                <x-kaca-breadcrumb-item>
                    <span class="text-grey-200 ml-1 md:ml-2 text-sm font-medium">Перегляд звіту</span>
                </x-kaca-breadcrumb-item>
            </x-kaca-breadcrumbs>

            <x-kaca-validation-errors></x-kaca-validation-errors>

            <x-kaca-alerts></x-kaca-alerts>

            <div class="flex flex-row gap-4">
                <div class="w-3/4">
                    <x-kaca-card>
                        <x-slot name="header">Текстове представлення чеку</x-slot>
                        <div class="bg-gray-200 p-2 rounded grid gap-y-4 grid-cols-1 w-[28rem]">
                            {!! str_replace("\n", '<br>', $text) !!}
                        </div>
                    </x-kaca-card>
                </div>
                <div class="w-1/3">
                    <x-kaca-card>
                        <x-slot name="header">Деталі зміни</x-slot>
                        @if($report->shift->isOpen())
                            <div class="py-2 text-left block ">
                                <span class="inline-block w-1/3 font-bold">Зміну відкрито о: </span>
                                <div class="inline-block">
                                    {{ $report->shift->opened_at->format(config('kaca.date_format')) }}
                                </div>
                            </div>
                        @endif
                        @if($report->shift->isClosed() && !is_null($report->shift->closed_at))
                            <div class="py-2 text-left block ">
                                <span class="inline-block w-1/3 font-bold">Зміну закрито о: </span>
                                <div class="inline-block">
                                    {{ $report->shift->closed_at->format(config('kaca.date_format')) }}
                                </div>
                            </div>
                        @endif
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Касир:</span>
                            <div class="inline-block">
                                {{ $report->shift->cashier->full_name }}
                            </div>
                        </div>
                        <div class="py-2 text-left block ">
                            <span class="inline-block w-1/3 font-bold">Каса:</span>
                            <div class="inline-block">
                                {{ $report->shift->cashRegister->title }}
                            </div>
                        </div>
                    </x-kaca-card>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
