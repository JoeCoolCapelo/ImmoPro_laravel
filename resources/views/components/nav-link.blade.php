@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 text-[11px] font-black uppercase tracking-widest leading-5 text-white bg-white/20 rounded-xl shadow-inner transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-2 text-[11px] font-black uppercase tracking-widest leading-5 text-white hover:text-white hover:bg-white/10 rounded-xl transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
