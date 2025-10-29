@props(['type' => 'info', 'title' => '', 'message' => '', 'duration' => 5000])

@php
    $notificationClasses = [
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'error' => 'bg-red-50 border-red-200 text-red-800',
    ];

    $iconClasses = [
        'info' => 'text-blue-400',
        'success' => 'text-green-400',
        'warning' => 'text-yellow-400',
        'error' => 'text-red-400',
    ];

    $iconPaths = [
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
        'error' => 'M6 18L18 6M6 6l12 12',
    ];

    $alertClass = $notificationClasses[$type] ?? $notificationClasses['info'];
    $iconClass = $iconClasses[$type] ?? $iconClasses['info'];
    $iconPath = $iconPaths[$type] ?? $iconPaths['info'];
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-init="setTimeout(() => show = false, {{ $duration }})"
    class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden border-l-4 {{ str_replace(['bg-', 'border-', 'text-'], ['border-l-', 'border-l-', 'text-'], $alertClass) }}"
>
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
                </svg>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                @if($title)
                    <p class="text-sm font-medium text-gray-900">{{ $title }}</p>
                @endif
                @if($message)
                    <p class="text-sm text-gray-500">{{ $message }}</p>
                @endif
                @if(!$title && !$message)
                    <p class="text-sm text-gray-900">{{ $slot }}</p>
                @endif
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button 
                    x-on:click="show = false"
                    class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>