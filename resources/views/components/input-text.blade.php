@props([
    'leadingAddOn' => false
])

<div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus-within:ring-2 focus-within:ring-inset">

    @if($leadingAddOn)

        <span class="flex select-none items-center pl-3 text-gray-500 dark:text-gray-400 sm:text-sm pr-2">
            {{ $leadingAddOn }}
        </span>

    @endif

    <input
        {{ $attributes }}
        class="{{ 'leadingAddOn' ? 'rounded-r-md' : '' }} bg-white dark:bg-gray-900 dark:text-gray-100 dark:placeholder-gray-500 rounded text-sm w-full">

</div>
