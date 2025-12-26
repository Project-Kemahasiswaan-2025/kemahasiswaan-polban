<x-filament-widgets::widget>
    <div class="fi-wi-language-switcher">
        <div class="flex items-center gap-3 justify-end">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Language') }}:
            </span>
            
            <div class="flex gap-2">
                @foreach($this->getAvailableLanguages() as $language)
                    <button
                        wire:click="switchLanguage({{ $language->id }})"
                        type="button"
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg transition-colors
                            @if($this->getCurrentLanguage()?->id === $language->id)
                                bg-primary-600 text-white ring-2 ring-primary-600
                            @else
                                bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700
                            @endif
                        "
                        title="{{ $language->name }}"
                    >
                        <span class="text-lg">{{ $language->icon }}</span>
                        <span>{{ $language->code }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
