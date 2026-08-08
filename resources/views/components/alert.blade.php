@php
    $types = [
        'success' => 'bg-green-100 text-green-800 border-green-300 dark:bg-green-900/40 dark:text-green-200 dark:border-green-700',
        'error'   => 'bg-red-100 text-red-800 border-red-300 dark:bg-red-900/40 dark:text-red-200 dark:border-red-700',
        'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-300 dark:bg-yellow-900/40 dark:text-yellow-200 dark:border-yellow-700',
        'info'    => 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-900/40 dark:text-blue-200 dark:border-blue-700',
    ];

    $icons = [
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
        'error'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
        'info'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ];
@endphp

@foreach(['success', 'error', 'warning', 'info'] as $type)
    @if(session($type))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mb-4 flex items-start gap-3 border rounded-lg px-4 py-3 {{ $types[$type] }}"
            role="alert"
        >
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icons[$type] !!}
            </svg>
            <span class="flex-1 text-sm font-medium">{{ session($type) }}</span>
            <button
                @click="show = false"
                class="flex-shrink-0 ml-auto text-current opacity-60 hover:opacity-100 transition-opacity"
                aria-label="Tutup"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif
@endforeach
