@props(['type' => 'button', 'variant' => 'primary', 'size' => 'md', 'href' => null, 'disabled' => false])

@php
    $variantClasses = [
        'primary' => 'bg-green-600 hover:bg-green-700 text-white border-transparent focus:ring-green-500',
        'secondary' => 'bg-white hover:bg-gray-50 text-gray-700 border-gray-300 focus:ring-green-500',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white border-transparent focus:ring-red-500',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white border-transparent focus:ring-yellow-500',
        'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white border-transparent focus:ring-emerald-500',
        'outline' => 'bg-transparent hover:bg-gray-50 text-gray-700 border-gray-300 focus:ring-gray-500',
        'ghost' => 'bg-transparent hover:bg-gray-100 text-gray-700 border-transparent focus:ring-gray-500',
    ];

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg',
    ];

    $variantClass = $variantClasses[$variant] ?? $variantClasses['primary'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];

    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg border transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $classes = $baseClasses . ' ' . $variantClass . ' ' . $sizeClass;
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if(isset($icon))
            <span class="mr-2">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'disabled' : '' }}>
        @if(isset($icon))
            <span class="mr-2">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </button>
@endif