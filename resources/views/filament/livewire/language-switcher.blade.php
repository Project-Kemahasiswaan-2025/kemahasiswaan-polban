<div class="fi-topbar-item">
    {{-- <label for="language-select" class="sr-only">{{ __('Select Language') }}</label> --}}
    <select 
        id="language-select"
        wire:model.live="selectedLanguage"
        class="fi-select-input block w-full min-w-[140px] rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:text-sm"
    >
        @foreach($languages as $language)
            <option value="{{ $language->id }}">
                {{ $language->icon }} {{ strtoupper($language->code) }}
            </option>
        @endforeach
    </select>
</div>