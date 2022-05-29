<div>
    <div class="bg-zinc-900 p-2 text-sm">
        @forelse($entries as $entry)
            <div class="py-2 text-left block flex">
                <span class="text-blue-500 mr-2 min-w-2">{{ $entry->id }}</span>
                <span class="text-emerald-500 mr-2 min-w-[10em]">{{ $entry->created_at }}</span>
                <span class="text-blue-500 min-w-[4em]">{{ $entry->type }}</span>
                <div class="">
                    @include('kaca::layouts._entry_content_item', ['content' => $entry->content])
                </div>
            </div>
        @empty
            <div class="py-2 text-left block text-yellow-500">Не знайдено інформації</div>
        @endforelse
    </div>
    <div class="mt-2">
        {{ $entries->withQueryString()->links() }}
    </div>
</div>
