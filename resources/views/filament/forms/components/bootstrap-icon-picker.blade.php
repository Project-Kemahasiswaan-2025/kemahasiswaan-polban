@php
$icons = $getIcons();
$statePath = $getStatePath();
@endphp

<style>
    .fi-bootstrap-icon-picker .fi-bip-tile {
        border: 1px solid rgb(229 231 235);
        border-radius: 0.75rem;
        padding: 0.75rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .fi-bootstrap-icon-picker .fi-bip-tile--active {
        border-color: rgb(59 130 246);
        box-shadow: 0 0 0 3px rgb(59 130 246 / 0.2);
    }
</style>

<div
    class="fi-bootstrap-icon-picker"
    x-data="{ value: @entangle($statePath) }">
    @if ($getLabel())
    <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-2">
        <span class="text-sm font-medium text-gray-950 dark:text-white">
            {{ $getLabel() }}
        </span>
    </label>
    @endif

    <div class="mt-2 grid grid-cols-6 gap-2">
        @foreach ($icons as $iconClass => $hint)
        <button
            type="button"
            class="fi-bip-tile"
            :class="{ 'fi-bip-tile--active': value === @js($iconClass) }"
            @click="value = @js($iconClass)"
            title="{{ $hint ?: $iconClass }}">
            <i class="bi {{ $iconClass }} text-2xl"></i>
        </button>
        @endforeach
    </div>

    @error($statePath)
    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
    @enderror
</div>