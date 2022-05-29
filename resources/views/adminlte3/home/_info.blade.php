@if($cashier->isTest() || $cashRegister->isTest())
    <div class="callout callout-warning">
        <h4>Важливо!</h4>
        <p><ul class="">
            @if($cashier->isTest())
                <li>Ви працюєте з тестовим касиром</li>
            @endif
            @if($cashRegister->isTest())
                <li>Ви працюєте з тестовою касою</li>
            @endif
        </ul></p>
    </div>
@endif
