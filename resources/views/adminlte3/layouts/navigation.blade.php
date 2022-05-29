<div class="" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        <button class="btn btn-default">
            Меню Kaca

            <span class="">
                <svg class="fill-current" height="16" width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </span>
        </button>
    </div>

    <div x-show="open"
         style="display: none; position: absolute; z-index: 50; background-color: white; width: 200px"
         @click="open = false"
         class="border"
    >
        <div class="">
            <div>
                <a class="btn btn-link" href="{{ route('kaca.index') }}">Інформація</a>
            </div>
            <div>
                <a class="btn btn-link" href="{{ route('kaca.receipts.index') }}">Чеки</a>
            </div>
            @can('seniorPermission')
            <div>
                <a class="btn btn-link" href="{{ route('kaca.shifts.index') }}">Зміни</a>
            </div>
            @endcan
            <div>
                <a class="btn btn-link" href="{{ route('kaca.reports.index') }}">Звіти</a>
            </div>
            @can('seniorPermission')
                <div class="p-2">Налаштування</div>
                <a class="btn btn-link" href="{{ route('kaca.cashiers.index') }}">Касири checkbox.ua</a>
                <a class="btn btn-link" href="{{ route('kaca.cash-registers.index') }}">Каси checkbox.ua</a>
                <a class="btn btn-link" href="{{ route('kaca.cashier-users.index') }}">Користувачі сервісом</a>
            @endcan
            @can('developerPermission')
                <div class="p-2">
                    Розробнику
                </div>
                <a class="btn btn-link" href="{{ route('kaca.entries.index') }}">Налагодження</a>
                <a class="btn btn-link" href="{{ route('kaca.action-recorders.index') }}">Активність</a>
            @endcan
        </div>
    </div>
</div>
