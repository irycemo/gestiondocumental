<a
    {{ $attributes->exceptProps(['class']) }}
    {{ $attributes->merge([
        'class' => '
            bg-blue-500
            hover:bg-blue-700
            dark:bg-blue-600
            dark:hover:bg-blue-500
            text-white
            px-4
            py-1
            rounded-full
            text-sm
            hover:shadow-lg
            dark:hover:shadow-blue-900/30
            flex
            items-center
            justify-center
            focus:outline-none
            focus:ring-2
            focus:ring-blue-400
            focus:ring-offset-2
            dark:focus:ring-blue-500
            dark:focus:ring-offset-gray-900
            active:scale-95
            transition
            duration-200
        '
    ]) }}
>
    {{ $slot }}
</a>
