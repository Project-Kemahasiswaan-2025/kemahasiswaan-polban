<div class="bg-white dark:bg-gray-800 rounded-xl p-3 shadow-sm border border-gray-200 dark:border-gray-700 space-y-2 transition-all" style="margin-left: {{ $level * 1.5 }}rem;">
    <div class="flex items-center justify-between gap-2">
        <div class="flex items-center space-x-2 flex-wrap gap-y-1">
            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                #{{ $node->sort_order }}
            </span>
            @if($node->icon)
                <i class="bi {{ $node->icon }} text-amber-500 text-sm"></i>
            @endif
            <span class="font-bold text-xs text-gray-900 dark:text-white">{{ $node->title }}</span>

            @if(!$node->is_active)
                <span class="px-2 py-0.5 text-[10px] font-semibold bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 rounded-full">Non-Aktif</span>
            @endif

            @if($node->action_type === 'module')
                <span class="px-2 py-0.5 text-[10px] font-semibold bg-amber-100 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 rounded-full flex items-center gap-1">
                    ⚡ Modul: {{ $node->module_key }}
                </span>
            @elseif($node->action_type === 'jump')
                <span class="px-2 py-0.5 text-[10px] font-semibold bg-purple-100 dark:bg-purple-950/40 text-purple-800 dark:text-purple-300 rounded-full flex items-center gap-1">
                    ↪ Jump Target
                </span>
            @elseif($node->action_type === 'info')
                <span class="px-2 py-0.5 text-[10px] font-semibold bg-blue-100 dark:bg-blue-950/40 text-blue-800 dark:text-blue-300 rounded-full">
                    ℹ️ Info/Jawaban
                </span>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-1 shrink-0">
            <button wire:click="openCreateModal({{ $node->id }})" title="Tambah Sub Opsi" type="button" class="p-1 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/50 rounded transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </button>
            <button wire:click="openEditModal({{ $node->id }})" title="Edit" type="button" class="p-1 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/50 rounded transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </button>
            <button wire:click="toggleActive({{ $node->id }})" title="Toggle Active" type="button" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </button>
            <button wire:click="deleteNode({{ $node->id }})" wire:confirm="Yakin ingin menghapus node ini beserta seluruh cabangnya?" title="Hapus" type="button" class="p-1 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>
    </div>

    @if($node->bot_response)
        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 italic bg-gray-50 dark:bg-gray-900/50 p-1.5 rounded">
            "{{ Str::limit(strip_tags($node->bot_response), 80) }}"
        </p>
    @endif

    <!-- Recursive Children -->
    @if($node->children->count() > 0)
        <div class="space-y-2 pt-1 border-t border-gray-100 dark:border-gray-700/50">
            @foreach($node->children as $child)
                @include('filament.pages.partials.chatbot-node-item', ['node' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
