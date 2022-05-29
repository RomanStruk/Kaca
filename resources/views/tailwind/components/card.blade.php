<div class="overflow-hidden shadow-md rounded-lg my-2 bg-white border border-gray-200 {{ $class ?? '' }}">
    <div class="px-6 py-4 bg-white border-b border-gray-200 text-lg">
        {{ $header }}
    </div>
    <div class="{{ $classBody ?? 'p-6' }}">
        {{ $slot }}
    </div>
    @isset($footer)
    <div class="p-6 bg-white border-gray-200 text-right border-t">
        {{ $footer }}
    </div>
    @endisset
</div>