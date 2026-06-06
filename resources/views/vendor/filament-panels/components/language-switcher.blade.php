@php
    $current = session('locale', config('app.fallback_locale'));
    $locales = ['en' => 'English', 'ar' => 'العربية'];
@endphp

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" type="button" class="filament-button flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg w-full">
        <x-filament::icon icon="heroicon-o-language" class="w-5 h-5" />
        <span>{{ $locales[$current] ?? 'English' }}</span>
    </button>
    <div x-show="open" @click.away="open = false" class="absolute bottom-full left-0 mb-1 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
        @foreach ($locales as $code => $label)
            <a href="{{ route('locale.switch', $code) }}"
               class="block px-4 py-2 text-sm {{ $current === $code ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300' }} hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>
