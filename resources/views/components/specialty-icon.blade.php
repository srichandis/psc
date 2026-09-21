@props(['name' => 'brain'])

@php
    $base = 'w-11 h-11';
@endphp

@switch($name)
    @case('brain')
        <svg {{ $attributes->merge(['class' => $base]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z" />
            <path d="M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z" />
            <path d="M12 5v13" />
            <path d="M7 11h2" />
            <path d="M15 11h2" />
        </svg>
        @break

    @case('psychology')
        <svg {{ $attributes->merge(['class' => $base]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M15 2a4 4 0 0 0-4 4v1H8a3 3 0 0 0-3 3v2a3 3 0 0 0 2 2.82V16a4 4 0 0 0 4 4h1v2" />
            <circle cx="16" cy="9" r="2.5" />
            <path d="M18 14c1.5 0 3 .8 3 2.5V19" />
        </svg>
        @break

    @case('psychiatry')
        <svg {{ $attributes->merge(['class' => $base]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 21a9 9 0 0 0 9-9c0-4.97-4.03-9-9-9s-9 4.03-9 9a9 9 0 0 0 9 9z" />
            <path d="M8 12c0-2.2 1.8-4 4-4s4 1.8 4 4" />
            <path d="M12 12v4" />
            <circle cx="12" cy="12" r="1" />
        </svg>
        @break

    @case('endocrinology')
        <svg {{ $attributes->merge(['class' => $base]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 15V9" />
            <path d="M8 8c-3-2-6 0-6 4s3 6 6 4c1.5-.7 3-3 4-7" />
            <path d="M16 8c3-2 6 0 6 4s-3 6-6 4c-1.5-.7-3-3-4-7" />
        </svg>
        @break

    @case('nephrology')
        <svg {{ $attributes->merge(['class' => $base]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M7 6c-2.5 0-4.5 2.5-4.5 6s2 6.5 4.5 6.5c3 0 4-3 4-6.5S9.5 6 7 6Z" />
            <path d="M17 6c2.5 0 4.5 2.5 4.5 6s-2 6.5-4.5 6.5c-3 0-4-3-4-6.5S14.5 6 17 6Z" />
            <path d="M11 12h2" />
        </svg>
        @break

    @case('geriatrics')
        <svg {{ $attributes->merge(['class' => $base]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M9 21V9a3.5 3.5 0 0 1 7 0v2" />
            <path d="M6 21h7" />
        </svg>
        @break

    @default
        <svg {{ $attributes->merge(['class' => $base]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 8v8" />
            <path d="M8 12h8" />
            <circle cx="12" cy="12" r="9" />
        </svg>
@endswitch
