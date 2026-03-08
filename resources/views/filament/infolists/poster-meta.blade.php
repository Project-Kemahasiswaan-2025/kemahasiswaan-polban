<div class="space-y-4">
    <div>
        <div class="text-xs opacity-70 font-semibold">Kategori</div>
        <div class="text-sm font-medium">{{ $record->category?->name ?? '-' }}</div>
    </div>

    <div>
        <div class="text-xs opacity-70 font-semibold">Judul</div>
        <div class="text-xl font-bold leading-tight">{{ $record->title ?? '-' }}</div>
    </div>

    <div>
        <div class="text-xs opacity-70 font-semibold">Slug</div>
        <div class="text-sm opacity-80">{{ $record->slug ?? '-' }}</div>
    </div>

    <div>
        <div class="text-xs opacity-70 font-semibold">Dibuat</div>
        <div class="text-sm font-medium">
            {{ optional($record->created_at)->format('d M Y') ?? '-' }}
        </div>
    </div>
</div>