<?php

namespace App\Filament\Pages;

use App\Models\ChatbotNode;
use App\Services\Chatbot\ChatbotModuleManager;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ChatbotBuilder extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static ?int $navigationSort = 55;
    protected string $view = 'filament.pages.chatbot-builder';

    // Form Modal State
    public bool $isModalOpen = false;
    public ?int $editingNodeId = null;
    public ?int $parentId = null;

    // Form Fields
    public string $nodeTitle = '';
    public ?string $icon = null;
    public string $bot_response = '';
    public string $action_type = 'node'; // node, jump, module, info
    public ?string $module_key = null;
    public ?int $target_node_id = null;
    
    // CTA Link Settings
    public bool $enableCta = false;
    public ?string $action_url = '';
    public ?string $action_label = '';
    public ?string $action_icon = 'bi-box-arrow-up-right';
    public string $action_icon_position = 'left';

    public int $sort_order = 0;
    public bool $is_active = true;

    // Icon Pickers Toggle State
    public bool $isIconPickerOpen = false;
    public bool $isCtaIconPickerOpen = false;

    // Available Popular Bootstrap Icons
    public array $availableIcons = [
        'bi-info-circle', 'bi-file-earmark-text', 'bi-people', 'bi-trophy',
        'bi-link-45deg', 'bi-telephone', 'bi-patch-question', 'bi-gear',
        'bi-star', 'bi-bell', 'bi-calendar-event', 'bi-folder2-open',
        'bi-journal-bookmark', 'bi-chat-left-dots', 'bi-patch-check', 'bi-award',
        'bi-lightbulb', 'bi-question-circle', 'bi-envelope', 'bi-building',
        'bi-globe', 'bi-box-arrow-up-right', 'bi-download', 'bi-arrow-right-circle',
        'bi-person-badge', 'bi-mortarboard', 'bi-shield-check'
    ];

    // Simulator State
    public array $simulatorMessages = [];

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group_services');
    }

    public static function getNavigationLabel(): string
    {
        return __('menu.nav_label_chatbot');
    }

    public function mount(): void
    {
        $this->resetSimulator();
    }

    public function resetSimulator(): void
    {
        $roots = ChatbotNode::query()->roots()->active()->orderBy('sort_order')->get();
        $this->simulatorMessages = [
            [
                'sender' => 'bot',
                'text' => 'Halo! Selamat datang di Pusat Layanan Kemahasiswaan POLBAN. Ada yang bisa kami bantu? Silakan pilih topik di bawah ini:',
                'options' => $roots->map(fn($r) => [
                    'id' => $r->id,
                    'title' => $r->title,
                    'icon' => $r->icon,
                ])->toArray(),
            ],
        ];
    }

    public function simulatorSelect($nodeId, ?string $userTitle = null): void
    {
        if ($nodeId === 'root') {
            $this->resetSimulator();
            return;
        }

        // Handle string format module IDs: module:{module_key}:{sub_action}:{param}
        if (is_string($nodeId) && str_starts_with($nodeId, 'module:')) {
            $parts = explode(':', $nodeId);
            $moduleKey = $parts[1] ?? null;
            $subAction = $parts[2] ?? null;
            $moduleParam = $parts[3] ?? null;

            $module = ChatbotModuleManager::getModule($moduleKey);
            if ($module) {
                $mockSession = new \App\Models\ChatbotSession();
                $rendered = $module->renderResponse($mockSession, [
                    'sub_action' => $subAction,
                    'param' => $moduleParam,
                    'service_id' => $moduleParam,
                ]);

                $this->simulatorMessages[] = [
                    'sender' => 'user',
                    'text' => $userTitle ?: $rendered['title'] ?? 'Menu Modul',
                ];

                $rawUrl = $rendered['action_url'] ?? null;
                $actionUrl = ChatbotNode::resolveSmartUrl($rawUrl);

                $this->simulatorMessages[] = [
                    'sender' => 'bot',
                    'text' => trim($rendered['message'] ?? ''),
                    'options' => $rendered['options'] ?? [],
                    'documents' => $rendered['documents'] ?? [],
                    'action_url' => $actionUrl,
                    'action_label' => $rendered['action_label'] ?? null,
                    'action_icon' => $rendered['action_icon'] ?? null,
                    'action_icon_position' => $rendered['action_icon_position'] ?? 'left',
                ];
                return;
            }
        }

        $node = ChatbotNode::find($nodeId);
        if (!$node) {
            return;
        }

        // Add user choice to simulator
        $this->simulatorMessages[] = [
            'sender' => 'user',
            'text' => $node->title,
        ];

        // Resolve jump target
        if ($node->action_type === 'jump' && $node->target_node_id) {
            $target = ChatbotNode::find($node->target_node_id);
            if ($target) {
                $node = $target;
            }
        }

        $botText = $node->getRandomResponse();
        $options = [];
        $documents = [];
        $actionUrl = $node->getResolvedActionUrl();
        $actionLabel = $node->action_label;
        $actionIcon = $node->action_icon;
        $actionIconPosition = $node->action_icon_position ?: 'left';

        if ($node->action_type === 'module' && $node->module_key) {
            $module = ChatbotModuleManager::getModule($node->module_key);
            if ($module) {
                $mockSession = new \App\Models\ChatbotSession();
                $rendered = $module->renderResponse($mockSession, ['sub_action' => 'root']);
                $botText = trim($rendered['message'] ?? '');
                $options = $rendered['options'] ?? [];
                $documents = $rendered['documents'] ?? [];
                if (!empty($rendered['action_url'])) {
                    $rawUrl = $rendered['action_url'];
                    $actionUrl = ChatbotNode::resolveSmartUrl($rawUrl);
                    $actionLabel = $rendered['action_label'] ?? 'Buka Halaman Related';
                    $actionIcon = $rendered['action_icon'] ?? 'bi-box-arrow-up-right';
                    $actionIconPosition = $rendered['action_icon_position'] ?? 'left';
                }
            }
        }

        if (empty($options) && in_array($node->action_type, ['node', 'options', 'jump'])) {
            $options = $node->activeChildren()
                ->get(['id', 'title', 'icon'])
                ->map(fn($c) => ['id' => $c->id, 'title' => $c->title, 'icon' => $c->icon])
                ->toArray();
        }

        $this->simulatorMessages[] = [
            'sender' => 'bot',
            'text' => $botText ?: 'Berikut informasi yang tersedia:',
            'options' => $options,
            'documents' => $documents,
            'action_url' => $actionUrl,
            'action_label' => $actionLabel,
            'action_icon' => $actionIcon,
            'action_icon_position' => $actionIconPosition,
        ];
    }

    public function toggleIconPicker(): void
    {
        $this->isIconPickerOpen = !$this->isIconPickerOpen;
    }

    public function selectIcon(?string $icon): void
    {
        $this->icon = $icon;
        $this->isIconPickerOpen = false;
    }

    public function toggleCtaIconPicker(): void
    {
        $this->isCtaIconPickerOpen = !$this->isCtaIconPickerOpen;
    }

    public function selectCtaIcon(?string $icon): void
    {
        $this->action_icon = $icon;
        $this->isCtaIconPickerOpen = false;
    }

    public function toggleCtaEnable(): void
    {
        $this->enableCta = !$this->enableCta;
        if (!$this->enableCta) {
            $this->action_url = '';
            $this->action_label = '';
            $this->action_icon = 'bi-box-arrow-up-right';
            $this->action_icon_position = 'left';
        }
    }

    public function openCreateModal(?int $parentId = null): void
    {
        $this->resetForm();
        $this->parentId = $parentId;
        $this->isModalOpen = true;
    }

    public function openEditModal(int $id): void
    {
        $node = ChatbotNode::findOrFail($id);
        $this->editingNodeId = $node->id;
        $this->parentId = $node->parent_id;
        $this->nodeTitle = $node->title;
        $this->icon = $node->icon;
        $this->bot_response = $node->bot_response ?? '';
        $this->action_type = $node->action_type ?? 'node';
        $this->module_key = $node->module_key;
        $this->target_node_id = $node->target_node_id;

        $this->action_url = $node->action_url ?? '';
        $this->action_label = $node->action_label ?? '';
        $this->action_icon = $node->action_icon ?? 'bi-box-arrow-up-right';
        $this->action_icon_position = $node->action_icon_position ?? 'left';
        $this->enableCta = !empty($this->action_url);

        $this->sort_order = $node->sort_order ?? 0;
        $this->is_active = $node->is_active ?? true;

        $this->isModalOpen = true;
    }

    public function saveNode(): void
    {
        $this->validate([
            'nodeTitle' => 'required|string|max:255',
            'action_type' => 'required|string',
            'bot_response' => 'nullable|string',
            'action_url' => 'nullable|string',
            'action_label' => 'nullable|string|max:255',
            'action_icon_position' => 'required|string|in:left,right',
        ]);

        $payload = [
            'parent_id' => $this->parentId,
            'title' => $this->nodeTitle,
            'icon' => $this->icon,
            'bot_response' => $this->bot_response,
            'action_type' => $this->action_type,
            'module_key' => $this->action_type === 'module' ? $this->module_key : null,
            'target_node_id' => $this->action_type === 'jump' ? $this->target_node_id : null,
            'action_url' => $this->enableCta ? $this->action_url : null,
            'action_label' => $this->enableCta ? $this->action_label : null,
            'action_icon' => $this->enableCta ? $this->action_icon : null,
            'action_icon_position' => $this->enableCta ? $this->action_icon_position : 'left',
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];

        if ($this->editingNodeId) {
            $node = ChatbotNode::findOrFail($this->editingNodeId);
            $node->update($payload);
            Notification::make()->title('Node percakapan berhasil diperbarui.')->success()->send();
        } else {
            ChatbotNode::create($payload);
            Notification::make()->title('Node percakapan baru berhasil ditambahkan.')->success()->send();
        }

        $this->closeModal();
        $this->resetSimulator();
    }

    public function deleteNode(int $id): void
    {
        $node = ChatbotNode::findOrFail($id);
        $node->delete();

        Notification::make()->title('Node percakapan dihapus.')->warning()->send();
        $this->resetSimulator();
    }

    public function toggleActive(int $id): void
    {
        $node = ChatbotNode::findOrFail($id);
        $node->update(['is_active' => !$node->is_active]);

        Notification::make()->title('Status node diperbarui.')->success()->send();
        $this->resetSimulator();
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->editingNodeId = null;
        $this->parentId = null;
        $this->nodeTitle = '';
        $this->icon = null;
        $this->bot_response = '';
        $this->action_type = 'node';
        $this->module_key = null;
        $this->target_node_id = null;

        $this->enableCta = false;
        $this->action_url = '';
        $this->action_label = '';
        $this->action_icon = 'bi-box-arrow-up-right';
        $this->action_icon_position = 'left';

        $this->isIconPickerOpen = false;
        $this->isCtaIconPickerOpen = false;

        $this->sort_order = 0;
        $this->is_active = true;
    }

    public function getViewData(): array
    {
        $nodes = ChatbotNode::with('children')->roots()->orderBy('sort_order')->get();
        $allNodes = ChatbotNode::orderBy('title')->get(['id', 'title']);
        $modules = ChatbotModuleManager::getModules();

        return [
            'nodes' => $nodes,
            'allNodes' => $allNodes,
            'modules' => $modules,
        ];
    }
}
