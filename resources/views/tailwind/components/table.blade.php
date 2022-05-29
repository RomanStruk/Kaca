<table class="rounded-md min-w-full border-collapse block md:table">
    <thead class="block md:table-header-group">
    <tr class="font-bold text-left text-grey-700 border border-grey-200 md:border-none block md:table-row absolute -top-full md:top-auto -left-full md:left-auto  md:relative ">
        {{ $thead }}
    </tr>
    </thead>
    <tbody class="block md:table-row-group">
    {{ $slot }}
    </tbody>
</table>