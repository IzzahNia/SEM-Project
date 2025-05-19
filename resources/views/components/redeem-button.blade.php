<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-orange-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-900 focus:bg-orange-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 drop-shadow-[0px_0px_12px_rgba(0,160,150,0.25)]']) }}>
    {{ $slot }}
</button>
