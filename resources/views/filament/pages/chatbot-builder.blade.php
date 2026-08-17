<x-filament-panels::page>
    @push('styles')
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @endpush

    <style>
        .cb-builder-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 1024px) {
            .cb-builder-grid {
                grid-template-columns: repeat(12, minmax(0, 1fr));
            }

            .cb-col-left {
                grid-column: span 7 / span 7;
            }

            .cb-col-right {
                grid-column: span 5 / span 5;
            }
        }

        .cb-modal-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 99999;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .cb-btn-primary {
            background-color: #f59e0b;
            color: #ffffff;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .cb-btn-primary:hover {
            background-color: #d97706;
        }

        .icon-grid {
            display: grid;
            grid-template-columns: repeat(9, minmax(0, 1fr));
            gap: 0.4rem;
            max-height: 180px;
            overflow-y: auto;
        }

        .icon-grid-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            color: #d97706 !important;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .icon-grid-btn i {
            color: #d97706 !important;
        }

        .icon-grid-btn:hover {
            background-color: #f59e0b !important;
            color: #ffffff !important;
            border-color: #d97706 !important;
            transform: scale(1.1);
        }

        .icon-grid-btn:hover i {
            color: #ffffff !important;
        }

        .icon-grid-btn.active-icon {
            background-color: #d97706 !important;
            color: #ffffff !important;
            border-color: #b45309 !important;
        }

        .icon-grid-btn.active-icon i {
            color: #ffffff !important;
        }
    </style>

    <div class="cb-builder-grid">
        <!-- KIRI: Tree Flow Builder -->
        <div class="cb-col-left space-y-4">
            <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Alur Percakapan Bot</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Kelola hierarki pertanyaan, jawaban, lompatan (jump), icon opsi, dan modul dinamis.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="openWelcomeModal" type="button" class="px-3 py-2 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/40 dark:hover:bg-amber-900/60 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-700 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition" title="Konfigurasi Pesan Pengantar Awal Chatbot">
                        <i class="bi bi-chat-quote-fill text-amber-600"></i>
                        <span>Pesan Pengantar Awal</span>
                    </button>
                    <button wire:click="openCreateModal(null)" type="button" class="cb-btn-primary text-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Topik Utama
                    </button>
                </div>
            </div>

            <!-- List Root Nodes -->
            <div class="space-y-3">
                @forelse($nodes as $node)
                @include('filament.pages.partials.chatbot-node-item', ['node' => $node, 'level' => 0])
                @empty
                <div class="p-8 text-center bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h4 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">Belum Ada Topik Percakapan</h4>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Klik tombol "Tambah Topik Utama" di atas untuk mulai membuat alur percakapan bot.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- KANAN: Live Simulator Panel -->
        <div class="cb-col-right">
            <div class="sticky top-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 bg-gray-900 text-white flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></div>
                        <span class="font-bold text-sm">Simulator Live Chatbot</span>
                    </div>
                    <button wire:click="resetSimulator" type="button" class="text-xs bg-gray-800 hover:bg-gray-700 px-3 py-1 rounded text-gray-300 transition">
                        Reset Sesi
                    </button>
                </div>

                <!-- Chat Body -->
                <div class="p-4 space-y-3 h-[480px] overflow-y-auto bg-gray-50 dark:bg-gray-900/50 text-xs">
                    @foreach($simulatorMessages as $msg)
                    @if($msg['sender'] === 'bot')
                    @php $isLatestBot = $loop->last; @endphp
                    <div class="flex flex-col items-start space-y-2">
                        <div class="max-w-[85%] bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 dark:border-gray-700 leading-relaxed text-xs">
                            {!! \App\Filament\Pages\ChatbotBuilder::formatMarkdown($msg['text']) !!}
                        </div>

                        @if(!empty($msg['documents']))
                        <div class="w-full space-y-1.5 pt-1">
                            @foreach($msg['documents'] as $doc)
                            <div class="flex items-start justify-between gap-2 p-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm w-full">
                                <div class="flex items-start space-x-2 min-w-0 flex-1">
                                    <i class="bi bi-file-earmark-pdf-fill text-rose-500 text-sm shrink-0 mt-0.5"></i>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-[11px] text-gray-800 dark:text-gray-200 break-words leading-tight">{{ $doc['name'] }}</div>
                                        <div class="text-[9px] text-gray-500 mt-0.5">{{ $doc['file_type'] }} {{ !empty($doc['file_size_formatted']) ? '• ' . $doc['file_size_formatted'] : '' }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    @if(!empty($doc['can_preview']))
                                    <a href="{{ $doc['preview_url'] }}" target="_blank" class="w-6 h-6 inline-flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md text-xs hover:bg-gray-200 transition" title="Pratinjau Dokumen">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @endif
                                    <a href="{{ $doc['download_url'] }}" target="_blank" class="w-6 h-6 inline-flex items-center justify-center bg-amber-600 hover:bg-amber-700 text-white rounded-md text-xs transition" title="Unduh Dokumen">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @if(!empty($msg['action_url']))
                        <div class="pt-1">
                            <a href="{{ $msg['action_url'] }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 rounded-full font-semibold border border-amber-200 dark:border-amber-700 hover:bg-amber-100 transition text-xs">
                                @if(($msg['action_icon_position'] ?? 'left') === 'left' && !empty($msg['action_icon']))
                                <i class="bi {{ $msg['action_icon'] }}"></i>
                                @endif
                                <span>{{ $msg['action_label'] ?? 'Buka Link' }}</span>
                                @if(($msg['action_icon_position'] ?? 'left') === 'right' && !empty($msg['action_icon']))
                                <i class="bi {{ $msg['action_icon'] }}"></i>
                                @elseif(empty($msg['action_icon']))
                                <i class="bi bi-box-arrow-up-right"></i>
                                @endif
                            </a>
                        </div>
                        @endif

                        @if(!empty($msg['options']))
                        <div class="w-full flex flex-wrap gap-1.5 pt-1">
                            @foreach($msg['options'] as $opt)
                            @if($isLatestBot)
                            <button wire:click="simulatorSelect('{{ $opt['id'] }}', '{{ addslashes($opt['title']) }}')" type="button" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-full text-xs transition shadow-sm font-medium inline-flex items-center gap-1.5">
                                @if(!empty($opt['icon']))
                                <i class="bi {{ $opt['icon'] }}"></i>
                                @endif
                                <span>{{ $opt['title'] }}</span>
                            </button>
                            @else
                            <button type="button" disabled class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded-full text-xs font-medium inline-flex items-center gap-1.5 cursor-not-allowed opacity-60">
                                @if(!empty($opt['icon']))
                                <i class="bi {{ $opt['icon'] }}"></i>
                                @endif
                                <span>{{ $opt['title'] }}</span>
                            </button>
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="flex justify-end">
                        <div class="max-w-[85%] bg-amber-600 text-white p-3 rounded-2xl rounded-tr-none shadow-sm font-medium">
                            {{ $msg['text'] }}
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL FORM NODE EDITOR -->
    @if($isModalOpen)
    <div class="cb-modal-backdrop">
        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-gray-200 dark:border-gray-700 space-y-4 text-gray-900 dark:text-gray-100 overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-3">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">
                    {{ $editingNodeId ? 'Edit Node Percakapan' : 'Tambah Node Percakapan' }}
                </h3>
                <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="saveNode" class="space-y-4 text-xs">
                <!-- Title & Icon Input Group -->
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Label Opsi & Icon (Diklik Pengguna)</label>
                    <div class="flex items-center gap-2">
                        <!-- Icon Selector Trigger Button -->
                        <button wire:click="toggleIconPicker" type="button" class="px-3 py-2 border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-lg flex items-center gap-1.5 hover:bg-gray-100 transition shrink-0" title="Pilih Icon Opsi">
                            @if($icon)
                            <i class="bi {{ $icon }} text-amber-600 text-sm"></i>
                            <span class="text-[10px] text-gray-600 dark:text-gray-300 font-mono">{{ $icon }}</span>
                            @else
                            <i class="bi bi-emoji-smile text-amber-600"></i>
                            <span class="text-[11px] text-gray-600 dark:text-gray-300 font-semibold">+ Icon</span>
                            @endif
                        </button>

                        @if($icon)
                        <button wire:click="selectIcon(null)" type="button" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Icon">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                        @endif

                        <!-- Input Title -->
                        <input type="text" wire:model="nodeTitle" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 dark:text-white text-xs p-2.5 border" placeholder="misal: Syarat Pendaftaran Beasiswa">
                    </div>

                    <!-- Icon Picker Grid -->
                    @if($isIconPickerOpen)
                    <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-amber-700 dark:text-amber-400">Pilih Icon Opsi:</span>
                            <button wire:click="selectIcon(null)" type="button" class="text-[10px] text-rose-600 hover:underline">Tanpa Icon</button>
                        </div>
                        <div class="icon-grid">
                            @foreach($availableIcons as $ic)
                            <button wire:click="selectIcon('{{ $ic }}')" type="button" class="icon-grid-btn {{ $icon === $ic ? 'active-icon' : '' }}" title="{{ $ic }}">
                                <i class="bi {{ $ic }} text-sm"></i>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Action Type -->
                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Tipe Aksi Node</label>
                    <select wire:model.live="action_type" class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 dark:text-white text-xs p-2.5 border">
                        <option value="node">Opsi Bercabang (Child Nodes)</option>
                        <option value="info">Pesan Informasi / Jawaban Akhir</option>
                        <option value="module">Dynamic Knowledge Module (Database Query)</option>
                        <option value="jump">Lompatan ke Node Lain (Jump Target)</option>
                    </select>
                </div>

                <!-- Dynamic Module Selection -->
                @if($action_type === 'module')
                <div class="p-3 bg-amber-50 dark:bg-amber-950/30 rounded-xl border border-amber-200 dark:border-amber-800 space-y-2">
                    <label class="block font-semibold text-amber-900 dark:text-amber-300">Pilih Modul Dinamis</label>
                    <select wire:model="module_key" class="w-full rounded-lg border-amber-300 dark:border-amber-700 bg-white dark:bg-gray-900 dark:text-white text-xs p-2.5 border">
                        <option value="">-- Pilih Modul --</option>
                        @foreach($modules as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-amber-700 dark:text-amber-400">Modul ini akan secara otomatis mengambil data aktif dari database saat diklik pengguna.</p>
                </div>
                @endif

                <!-- Jump Target Selection -->
                @if($action_type === 'jump')
                <div class="p-3 bg-purple-50 dark:bg-purple-950/30 rounded-xl border border-purple-200 dark:border-purple-800 space-y-2">
                    <label class="block font-semibold text-purple-900 dark:text-purple-300">Lompat ke Node Target</label>
                    <select wire:model="target_node_id" class="w-full rounded-lg border-purple-300 dark:border-purple-700 bg-white dark:bg-gray-900 dark:text-white text-xs p-2.5 border">
                        <option value="">-- Pilih Node Target --</option>
                        @foreach($allNodes as $n)
                        @if($n->id !== $editingNodeId)
                        <option value="{{ $n->id }}">{{ $n->title }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Bot Response Variations -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="font-semibold text-gray-700 dark:text-gray-300">
                            Teks Balasan Bot
                            @if(count($bot_responses) > 1)
                                <span class="text-[10px] bg-amber-100 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 px-2 py-0.5 rounded-full font-bold ml-1">
                                    🔀 {{ count($bot_responses) }} Variasi Acak
                                </span>
                            @endif
                        </label>
                        <button wire:click="addResponseVariation" type="button" class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 hover:underline">
                            <i class="bi bi-plus-circle-fill"></i> Tambah Variasi
                        </button>
                    </div>

                    <div class="space-y-2">
                        @foreach($bot_responses as $index => $resp)
                        <div class="p-2.5 bg-gray-50 dark:bg-gray-900/60 rounded-xl border border-gray-200 dark:border-gray-700 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="bi bi-chat-text text-amber-500"></i> Variasi Respon #{{ $index + 1 }}
                                </span>
                                @if(count($bot_responses) > 1)
                                <button wire:click="removeResponseVariation({{ $index }})" type="button" class="text-rose-500 hover:text-rose-700 p-0.5 rounded hover:bg-rose-50 dark:hover:bg-rose-950/40 transition flex items-center gap-1 text-[10px] font-semibold" title="Hapus Variasi Ini">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                                @endif
                            </div>
                            <textarea wire:model="bot_responses.{{ $index }}" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 dark:text-white text-xs p-2 border" placeholder="Tuliskan variasi balasan bot..."></textarea>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-gray-400">Tips: Jika Anda membuat lebih dari 1 variasi, sistem akan memilih salah satu kalimat secara acak saat pengguna memilih opsi ini.</p>
                </div>

                <!-- Custom External Link / CTA Button Toggle & Settings -->
                <div class="p-3 bg-blue-50 dark:bg-blue-950/30 rounded-xl border border-blue-200 dark:border-blue-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="enableCta" class="rounded border-gray-300 text-amber-600">
                            <span class="font-bold text-blue-900 dark:text-blue-300">Aktifkan Tombol Link CTA / Action URL</span>
                        </label>
                        @if($enableCta)
                        <span class="text-[10px] bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-0.5 rounded-full font-semibold">Aktif</span>
                        @endif
                    </div>

                    @if($enableCta)
                    <div class="space-y-3 pt-2 border-t border-blue-100 dark:border-blue-900/50">
                        <!-- Smart URL -->
                        <div>
                            <label class="block font-semibold text-blue-900 dark:text-blue-300 mb-1">Target URL (Smart Resolution)</label>
                            <input type="text" wire:model="action_url" class="w-full rounded-lg border-blue-300 dark:border-blue-700 bg-white dark:bg-gray-900 dark:text-white text-xs p-2 border" placeholder="misal: /ormawa atau /beasiswa atau https://google.com">
                            <p class="text-[10px] text-blue-600 dark:text-blue-400 mt-1">Cerdas: Masukkan <code>/ormawa</code> atau <code>/beasiswa</code> untuk link internal (otomatis prepend base URL) atau link penuh <code>https://...</code></p>
                        </div>

                        <!-- CTA Label -->
                        <div>
                            <label class="block font-semibold text-blue-900 dark:text-blue-300 mb-1">Label Tombol CTA</label>
                            <input type="text" wire:model="action_label" class="w-full rounded-lg border-blue-300 dark:border-blue-700 bg-white dark:bg-gray-900 dark:text-white text-xs p-2 border" placeholder="misal: Buka Halaman Beasiswa">
                        </div>

                        <!-- Posisi Icon (Dulu) & Icon CTA Trigger (Flex Row) -->
                        <div class="flex items-center gap-4 flex-wrap pt-1 border-t border-blue-100 dark:border-blue-900/40">
                            <!-- 1. Posisi Icon First -->
                            <div class="flex items-center gap-2 shrink-0">
                                <label class="font-semibold text-blue-900 dark:text-blue-300 text-xs whitespace-nowrap">Posisi Icon CTA:</label>
                                <select wire:model="action_icon_position" class="rounded-lg border-blue-300 dark:border-blue-700 bg-white dark:bg-gray-900 dark:text-white text-xs p-1.5 border">
                                    <option value="left">Kiri Teks</option>
                                    <option value="right">Kanan Teks</option>
                                </select>
                            </div>

                            <!-- 2. Icon CTA Trigger Next -->
                            <div class="flex items-center gap-2">
                                <label class="font-semibold text-blue-900 dark:text-blue-300 text-xs whitespace-nowrap">Icon CTA:</label>
                                <button wire:click="toggleCtaIconPicker" type="button" class="px-2.5 py-1.5 border border-blue-300 dark:border-blue-700 bg-white dark:bg-gray-900 rounded-lg flex items-center gap-1.5 hover:bg-blue-100 transition text-xs shrink-0">
                                    @if($action_icon)
                                    <i class="bi {{ $action_icon }} text-blue-600 text-sm"></i>
                                    <span class="text-[10px] font-mono text-gray-600 dark:text-gray-300">{{ $action_icon }}</span>
                                    @else
                                    <i class="bi bi-emoji-smile text-gray-400"></i>
                                    <span class="text-[11px] text-gray-500">+ Icon CTA</span>
                                    @endif
                                </button>
                                @if($action_icon)
                                <button wire:click="selectCtaIcon(null)" type="button" class="text-[10px] text-rose-600 hover:underline">Hapus</button>
                                @endif
                            </div>
                        </div>

                        <!-- Full 1 Row Width CTA Icon Picker Grid -->
                        @if($isCtaIconPickerOpen)
                        <div class="mt-2 p-3 bg-white dark:bg-gray-900 rounded-xl border border-blue-200 dark:border-blue-800 space-y-2 w-full">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-semibold text-blue-900 dark:text-blue-300">Pilih Icon CTA:</span>
                                <button wire:click="selectCtaIcon(null)" type="button" class="text-[10px] text-rose-600 hover:underline">Tanpa Icon</button>
                            </div>
                            <div class="icon-grid">
                                @foreach($availableIcons as $ic)
                                <button wire:click="selectCtaIcon('{{ $ic }}')" type="button" class="icon-grid-btn {{ $action_icon === $ic ? 'active-icon' : '' }}" title="{{ $ic }}">
                                    <i class="bi {{ $ic }} text-sm"></i>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Sort Order & Is Active -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Urutan</label>
                        <input type="number" wire:model="sort_order" class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 dark:text-white text-xs p-2.5 border">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-amber-600">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Aktifkan Node</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-2 border-t border-gray-100 dark:border-gray-700 pt-3">
                    <button wire:click="closeModal" type="button" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">Batal</button>
                    <button type="submit" class="cb-btn-primary text-xs">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- MODAL FORM INITIAL WELCOME MESSAGE SETTING -->
    @if($isWelcomeModalOpen)
    <div class="cb-modal-backdrop">
        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-200 dark:border-gray-700 space-y-4 text-gray-900 dark:text-gray-100 overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-3">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="bi bi-chat-quote-fill text-amber-600"></i> Konfigurasi Pesan Pengantar Awal
                    </h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Pesan ini akan langsung dikirim oleh bot saat pertama kali pengguna membuka widget chatbot.</p>
                </div>
                <button wire:click="closeWelcomeModal" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="saveWelcomeSetting" class="space-y-4 text-xs">
                <!-- Welcome Response Variations -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="font-semibold text-gray-700 dark:text-gray-300">
                            Kalimat Salam & Pengantar
                            @if(count($welcome_responses) > 1)
                                <span class="text-[10px] bg-amber-100 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 px-2 py-0.5 rounded-full font-bold ml-1">
                                    🔀 {{ count($welcome_responses) }} Variasi Acak
                                </span>
                            @endif
                        </label>
                        <button wire:click="addWelcomeVariation" type="button" class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 hover:underline">
                            <i class="bi bi-plus-circle-fill"></i> Tambah Variasi
                        </button>
                    </div>

                    <div class="space-y-2">
                        @foreach($welcome_responses as $index => $resp)
                        <div class="p-2.5 bg-gray-50 dark:bg-gray-900/60 rounded-xl border border-gray-200 dark:border-gray-700 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="bi bi-chat-dots text-amber-500"></i> Variasi Pengantar #{{ $index + 1 }}
                                </span>
                                @if(count($welcome_responses) > 1)
                                <button wire:click="removeWelcomeVariation({{ $index }})" type="button" class="text-rose-500 hover:text-rose-700 p-0.5 rounded hover:bg-rose-50 dark:hover:bg-rose-950/40 transition flex items-center gap-1 text-[10px] font-semibold" title="Hapus Variasi Ini">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                                @endif
                            </div>
                            <textarea wire:model="welcome_responses.{{ $index }}" rows="2.5" class="w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 dark:text-white text-xs p-2 border" placeholder="misal: Halo! Selamat datang di Pusat Layanan Kemahasiswaan POLBAN..."></textarea>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-gray-400">Tips: Jika Anda membuat beberapa variasi, sistem akan memilih salah satu pesan secara acak setiap kali pengunjung baru membuka widget chatbot.</p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <button wire:click="closeWelcomeModal" type="button" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="cb-btn-primary text-xs">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</x-filament-panels::page>