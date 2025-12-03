<button {{ $attributes->merge(['type' => 'submit', 'class' => 'flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-red-600 text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
