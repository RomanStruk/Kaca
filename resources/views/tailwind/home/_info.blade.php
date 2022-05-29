@if($cashier->isTest() || $cashRegister->isTest())
    <div class="overflow-hidden shadow-md rounded-lg bg-white border border-gray-200 px-6 py-4 mb-4">
        <div>
            <div class="font-medium text-yellow-500">Важливо!</div>
            <ul class="mt-3 list-disc list-inside text-sm text-yellow-500">
                @if($cashier->isTest())
                    <li>Ви працюєте з тестовим касиром</li>
                @endif
                @if($cashRegister->isTest())
                    <li>Ви працюєте з тестовою касою</li>
                @endif
            </ul>
        </div>
    </div>
@endif