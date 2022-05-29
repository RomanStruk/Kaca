@if (session()->has('message'))
    <div class="overflow-hidden shadow-md rounded-lg bg-white border border-gray-200 px-6 py-4 mb-4">
        <div {{ $attributes }}>
            <div class="font-medium text-green-600">Успіх!</div>
            <ul class="mt-3 list-disc list-inside text-sm text-green-600">
                <li>{{ session()->get('message') }}</li>
            </ul>
        </div>
    </div>
@endif
