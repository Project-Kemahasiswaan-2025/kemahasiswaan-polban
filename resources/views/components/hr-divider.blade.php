<div style="display: flex; align-items: center; margin: 8px 0;">
    @if(isset($icon))
    <i class="{{ $icon }}" style="margin-right: 10px; font-size: 16px; color: #333;"></i>
    @endif
    <span style="margin-right: 10px; font-weight: bold; color: #333; white-space: nowrap; font-size: 14px;">
        {{ $label }}
    </span>
    <hr style="flex-grow: 1; border: 0; border-top: 1px solid #ccc; margin: 0;">
</div>