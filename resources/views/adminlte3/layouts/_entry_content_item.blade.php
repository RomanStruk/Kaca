<div class="ml-4">
@if(is_array($content))
    @foreach($content as $key => $item)
        <div  x-data="{ expanded: false }">
        <span class="">"{{ $key }}": @if(is_array($item)) <span @click="expanded = ! expanded" class="">▶</span> @endif</span>
            @if(is_array($item))
                <span  x-show="expanded" x-collapse>
                    @include('kaca::layouts._entry_content_item', ['content' => $item])
                </span>
            @else
                <span class="">"{{ $item }}"</span>
            @endif
        </div>
    @endforeach
@else
    <span class="">{{ $content }}</span>
@endif
</div>

