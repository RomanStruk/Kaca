
<div class="ml-4">
@if(is_array($content))
    @foreach($content as $key => $item)
        <div  x-data="{ expanded: false }">
        <span class="text-orange-500">"{{ $key }}": @if(is_array($item)) <span @click="expanded = ! expanded" class="cursor-pointer text-violet-800 text-xs">▶</span> @endif</span>
            @if(is_array($item))
                <span  x-show="expanded" x-collapse>
                    @include('kaca::layouts._entry_content_item', ['content' => $item])
                </span>
            @else
                <span class="text-green-500 font-bold">"{{ $item }}"</span>
            @endif
        </div>
    @endforeach
@else
    <span class="text-green-500">{{ $content }}</span>
@endif
</div>

