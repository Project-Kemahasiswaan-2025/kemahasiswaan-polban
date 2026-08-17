<?php

namespace App\Services\Chatbot\Modules;

use App\Contracts\ChatbotModuleInterface;
use App\Models\ChatbotNode;
use App\Models\ChatbotSession;
use App\Models\StudentOrganization;
use Illuminate\Support\Str;

class OrmawaKnowledgeModule implements ChatbotModuleInterface
{
    public static function getKey(): string
    {
        return 'ormawa';
    }

    public static function getLabel(): string
    {
        return 'Modul Dinamis: Organisasi Mahasiswa (Ormawa)';
    }

    public function renderResponse(ChatbotSession $session, array $params = []): array
    {
        $subAction = $params['sub_action'] ?? null;
        $param = $params['param'] ?? null;
        $page = (int) ($params['page'] ?? 1);
        if ($page < 1) {
            $page = 1;
        }

        // Detail sub-action
        if ($subAction === 'detail' || ($param && is_numeric($param) && $subAction !== 'group')) {
            return $this->renderOrmawaDetail((int) $param);
        }

        // Group sub-action (HMJ / UKM)
        if ($subAction === 'group' && $param && is_numeric($param)) {
            return $this->renderOrmawaGroup((int) $param, $page);
        }

        // Default / Root: 4 Main Root Categories (MPM, BEM, HMJ, UKM)
        return $this->renderRootCategories();
    }

    /**
     * Render Step 1: 4 Main Root Categories (MPM, BEM, HMJ, UKM).
     */
    protected function renderRootCategories(): array
    {
        $roots = StudentOrganization::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        if ($roots->isEmpty()) {
            return [
                'title' => 'Organisasi & UKM Mahasiswa',
                'message' => 'Saat ini belum ada data Organisasi Mahasiswa yang terdaftar.',
                'options' => [
                    [
                        'id' => 'root',
                        'title' => 'Menu Utama',
                        'icon' => 'bi-house',
                        'action_type' => 'root',
                    ],
                ],
                'action_url' => route('ormawa.index'),
                'action_label' => 'Buka Katalog Seluruh Ormawa',
                'action_icon' => 'bi-people',
            ];
        }

        $options = [];
        foreach ($roots as $root) {
            if ($root->is_group) {
                // Group Container (HMJ or UKM) -> Sub action group
                $options[] = [
                    'id' => "module:ormawa:group:{$root->id}:page:1",
                    'title' => $root->name,
                    'icon' => $root->slug === 'ukm' ? 'bi-trophy-fill' : 'bi-building',
                    'action_type' => 'module_sub',
                    'module_key' => 'ormawa',
                    'module_param' => (string) $root->id,
                ];
            } else {
                // Single Instance (MPM or BEM) -> Sub action detail
                $options[] = [
                    'id' => "module:ormawa:detail:{$root->id}",
                    'title' => $root->name,
                    'icon' => 'bi-award-fill',
                    'action_type' => 'module_sub',
                    'module_key' => 'ormawa',
                    'module_param' => (string) $root->id,
                ];
            }
        }

        // Add back to root option
        $options[] = [
            'id' => 'root',
            'title' => 'Menu Utama',
            'icon' => 'bi-house',
            'action_type' => 'root',
        ];

        return [
            'title' => 'Organisasi & UKM Mahasiswa',
            'message' => "Berikut adalah 4 kategori utama Organisasi & UKM Mahasiswa di POLBAN:\nSilakan pilih salah satu kategori di bawah ini untuk melihat informasi lebih lanjut:",
            'options' => $options,
            'action_url' => route('ormawa.index'),
            'action_label' => 'Lihat Seluruh Katalog Ormawa',
            'action_icon' => 'bi-people',
            'action_icon_position' => 'left',
        ];
    }

    /**
     * Render Step 2A: Group Listing (HMJ / UKM) with 5 items per page pagination.
     */
    protected function renderOrmawaGroup(int $groupId, int $page = 1): array
    {
        $group = StudentOrganization::where('is_active', true)->find($groupId);

        if (!$group) {
            return $this->renderRootCategories();
        }

        $query = StudentOrganization::query()
            ->where('parent_id', $groupId)
            ->where('is_active', true)
            ->orderBy('sort_order');

        $total = $query->count();
        $perPage = 5;
        $totalPages = (int) ceil($total / $perPage) ?: 1;
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $children = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        $options = [];
        foreach ($children as $child) {
            $options[] = [
                'id' => "module:ormawa:detail:{$child->id}",
                'title' => $child->name,
                'icon' => $group->slug === 'ukm' ? 'bi-trophy' : 'bi-mortarboard',
                'action_type' => 'module_sub',
                'module_key' => 'ormawa',
                'module_param' => (string) $child->id,
            ];
        }

        // Navigation back
        $options[] = [
            'id' => 'module:ormawa:root',
            'title' => 'Kembali ke Kategori Ormawa',
            'icon' => 'bi-arrow-left',
            'action_type' => 'module_sub',
            'module_key' => 'ormawa',
            'module_param' => 'root',
        ];

        $options[] = [
            'id' => 'root',
            'title' => 'Menu Utama',
            'icon' => 'bi-house',
            'action_type' => 'root',
        ];

        $startNum = (($page - 1) * $perPage) + 1;
        $endNum = min($page * $perPage, $total);

        $message = "### **{$group->name} POLBAN**\n";
        $message .= "Daftar unit {$group->name} ({$startNum}-{$endNum} dari total {$total} unit, Hal. {$page}/{$totalPages}):\n";
        $message .= "Silakan pilih salah satu unit di bawah ini untuk melihat rincian profil:";

        return [
            'title' => $group->name,
            'message' => trim($message),
            'options' => $options,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'base_id' => "module:ormawa:group:{$groupId}:page:",
                'module_key' => 'ormawa',
                'module_param' => (string) $groupId,
            ],
            'action_url' => route('ormawa.index'),
            'action_label' => "Lihat Seluruh Katalog {$group->name}",
            'action_icon' => 'bi-people',
            'action_icon_position' => 'left',
        ];
    }

    /**
     * Render Step 2B: Ormawa Detail View with Logo, Excerpt, Content, CTA, and Back Navigation.
     */
    protected function renderOrmawaDetail(int $orgId): array
    {
        $org = StudentOrganization::with('parent')->where('is_active', true)->find($orgId);

        if (!$org) {
            return $this->renderRootCategories();
        }

        $message = '';

        // 1. Logo (if exists)
        if (!empty($org->logo)) {
            $logoUrl = preg_match('/^https?:\/\//i', $org->logo) ? $org->logo : asset('storage/' . ltrim($org->logo, '/'));
            $message .= "![Logo {$org->name}]({$logoUrl})\n\n";
        }

        // 2. Header Title
        $message .= "### **{$org->name}**\n";

        // 3. Excerpt / Content Summary
        $excerpt = $org->excerpt ?: strip_tags(Str::limit($org->content, 260));
        if ($excerpt) {
            $message .= trim($excerpt);
        } else {
            $message .= "Organisasi Mahasiswa resmi yang terdaftar di lingkungan Politeknik Negeri Bandung.";
        }

        // 4. Navigation Back Options
        $options = [];

        if ($org->parent_id && $org->parent) {
            $options[] = [
                'id' => "module:ormawa:group:{$org->parent_id}:page:1",
                'title' => "Kembali ke Daftar {$org->parent->name}",
                'icon' => 'bi-arrow-left',
                'action_type' => 'module_sub',
                'module_key' => 'ormawa',
                'module_param' => (string) $org->parent_id,
            ];
        }

        $options[] = [
            'id' => 'module:ormawa:root',
            'title' => 'Kembali ke Kategori Ormawa',
            'icon' => 'bi-arrow-left',
            'action_type' => 'module_sub',
            'module_key' => 'ormawa',
            'module_param' => 'root',
        ];

        $options[] = [
            'id' => 'root',
            'title' => 'Menu Utama',
            'icon' => 'bi-house',
            'action_type' => 'root',
        ];

        // 5. Main CTA Link
        $targetCtaUrl = $org->cta_url ? ChatbotNode::resolveSmartUrl($org->cta_url) : route('ormawa.show', $org->slug);
        $targetCtaLabel = $org->cta_label ?: "Buka Profil {$org->name}";

        return [
            'title' => $org->name,
            'message' => trim($message),
            'options' => $options,
            'action_url' => $targetCtaUrl,
            'action_label' => $targetCtaLabel,
            'action_icon' => 'bi-box-arrow-up-right',
            'action_icon_position' => 'left',
        ];
    }
}
