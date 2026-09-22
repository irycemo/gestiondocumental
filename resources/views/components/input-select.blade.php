@props([
    'placeholder' => null,
    'trailingAddOn' => null,
])

<div class="w-full">

  <select {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-900 dark:text-gray-100 rounded text-sm w-full' . ($trailingAddOn ? ' rounded-r-none' : '')]) }}>

    @if ($placeholder)

        <option disabled value="">{{ $placeholder }}</option>

    @endif

    {{ $slot }}

  </select>

  @if ($trailingAddOn)

    {{ $trailingAddOn }}

  @endif

</div>
