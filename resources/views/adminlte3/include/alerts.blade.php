@if (session()->has('message'))
    <div class="callout callout-success">
        <h4><i class="icon fas fa-check"></i> Успіх!!</h4>
        <p>{{ session()->get('message') }}</p>
    </div>
@endif
