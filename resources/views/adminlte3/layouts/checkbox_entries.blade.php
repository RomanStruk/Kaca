<div>
    <div class="p-2">
        @forelse($entries as $entry)
            <div class="py-2" style="display: flex">
                <span class="p-2">{{ $entry->id }}</span>
                <span class="p-2">{{ $entry->created_at }}</span>
                <span class="p-2">{{ $entry->type }}</span>
                <div class="p-2">
                    @include('kaca::layouts._entry_content_item', ['content' => $entry->content])
                </div>
            </div>
        @empty
            <div class="p-2">Не знайдено інформації</div>
        @endforelse
    </div>
    <div class="mt-2">
        {{ $entries->withQueryString()->links() }}
    </div>
</div>
