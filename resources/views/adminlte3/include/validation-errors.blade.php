@if ($errors->any())
    <div class="callout callout-warning">
        <h4><i class="icon fas fa-exclamation-triangle"></i> {{ __('Whoops! Something went wrong.') }}!</h4>
        <p>
            <ul class="">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </p>
    </div>
@endif
