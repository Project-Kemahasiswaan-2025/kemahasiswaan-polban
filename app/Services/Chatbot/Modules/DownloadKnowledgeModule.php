<?php

namespace App\Services\Chatbot\Modules;

use App\Contracts\ChatbotModuleInterface;
use App\Models\Category;
use App\Models\ChatbotSession;
use App\Models\Download;

class DownloadKnowledgeModule implements ChatbotModuleInterface
{
    public static function getKey(): string
    {
        return 'downloads';
    }

    public static function getLabel(): string
    {
        return 'Modul Dinamis: Pusat Unduhan & Dokumen';
    }

    public function renderResponse(ChatbotSession $session, array $params = []): array
    {
        $subAction = $params['sub_action'] ?? null;
        $param = $params['param'] ?? null;
        $page = (int) ($params['page'] ?? 1);
        if ($page < 1) {
            $page = 1;
        }

        // Sub-action category selected
        if ($subAction === 'category' || ($param !== null && is_numeric($param) && $subAction !== 'root')) {
            return $this->renderCategoryDocuments((int) $param, $page);
        }

        // Default / Root: Render Download Categories List (max 5 per page)
        return $this->renderDownloadCategories($page);
    }

    /**
     * Render Step 1: Download Categories List (max 5 items per page pagination).
     */
    protected function renderDownloadCategories(int $page = 1): array
    {
        $query = Category::query()
            ->ofType('download')
            ->active()
            ->orderBy('sort_order');

        $total = $query->count();

        if ($total === 0) {
            return [
                'title' => 'Pusat Unduhan & Dokumen',
                'message' => 'Saat ini belum ada kategori atau dokumen unduhan publik yang tersedia.',
                'options' => [
                    [
                        'id' => 'root',
                        'title' => 'Menu Utama',
                        'icon' => 'bi-house',
                        'action_type' => 'root',
                    ],
                ],
                'action_url' => route('download.index'),
                'action_label' => 'Buka Pusat Unduhan',
                'action_icon' => 'bi-folder2-open',
            ];
        }

        $perPage = 5;
        $totalPages = (int) ceil($total / $perPage) ?: 1;
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $categories = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        $options = [];
        foreach ($categories as $cat) {
            $docCount = Download::where('is_active', true)->where('category_id', $cat->id)->count();
            $options[] = [
                'id' => "module:downloads:category:{$cat->id}:page:1",
                'title' => "{$cat->name} ({$docCount})",
                'icon' => 'bi-folder-symlink',
                'action_type' => 'module_sub',
                'module_key' => 'downloads',
                'module_param' => (string) $cat->id,
            ];
        }

        // Navigation back
        $options[] = [
            'id' => 'root',
            'title' => 'Menu Utama',
            'icon' => 'bi-house',
            'action_type' => 'root',
        ];

        $startNum = (($page - 1) * $perPage) + 1;
        $endNum = min($page * $perPage, $total);

        $message = "### **Pusat Unduhan & Dokumen POLBAN**\n";
        $message .= "Berikut adalah kategori dokumen publik yang tersedia ({$startNum}-{$endNum} dari total {$total} kategori):\n";
        $message .= "Silakan pilih salah satu kategori di bawah ini untuk mengunduh atau mempratinjau dokumen:";

        return [
            'title' => 'Pusat Unduhan & Dokumen',
            'message' => trim($message),
            'options' => $options,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'base_id' => 'module:downloads:root:page:',
                'module_key' => 'downloads',
                'module_param' => 'root',
            ],
            'action_url' => route('download.index'),
            'action_label' => 'Buka Pusat Unduhan Dokumen',
            'action_icon' => 'bi-folder2-open',
            'action_icon_position' => 'left',
        ];
    }

    /**
     * Render Step 2: Documents list per Category in Block Document Card format with pagination (max 5 per page).
     */
    protected function renderCategoryDocuments(int $catId, int $page = 1): array
    {
        $catName = 'Dokumen Unduhan';

        if ($catId > 0) {
            $cat = Category::ofType('download')->active()->find($catId);
            if ($cat) {
                $catName = $cat->name;
            }
        } else {
            $catName = 'Dokumen Lainnya';
        }

        $query = Download::query()
            ->where('is_active', true);

        if ($catId > 0) {
            $query->where('category_id', $catId);
        } else {
            $query->whereNull('category_id');
        }

        $query->orderBy('sort_order');

        $total = $query->count();

        if ($total === 0) {
            return [
                'title' => $catName,
                'message' => "Saat ini belum ada dokumen aktif yang tersedia dalam kategori **{$catName}**.",
                'options' => [
                    [
                        'id' => 'module:downloads:root',
                        'title' => 'Kembali ke Kategori Unduhan',
                        'icon' => 'bi-arrow-left',
                        'action_type' => 'module_sub',
                        'module_key' => 'downloads',
                        'module_param' => 'root',
                    ],
                    [
                        'id' => 'root',
                        'title' => 'Menu Utama',
                        'icon' => 'bi-house',
                        'action_type' => 'root',
                    ],
                ],
                'action_url' => route('download.index'),
                'action_label' => 'Buka Pusat Unduhan Dokumen',
            ];
        }

        $perPage = 5;
        $totalPages = (int) ceil($total / $perPage) ?: 1;
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $downloads = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        $documents = [];
        foreach ($downloads as $doc) {
            $rawType = strtolower($doc->file_type ?: 'pdf');
            if (str_contains($rawType, 'pdf')) {
                $docType = 'PDF';
            } elseif (str_contains($rawType, 'word') || str_contains($rawType, 'doc')) {
                $docType = 'DOCX';
            } elseif (str_contains($rawType, 'excel') || str_contains($rawType, 'sheet') || str_contains($rawType, 'xls')) {
                $docType = 'XLSX';
            } elseif (str_contains($rawType, 'image') || in_array($rawType, ['png', 'jpg', 'jpeg', 'svg', 'webp'])) {
                $docType = 'IMG';
            } else {
                $docType = strtoupper(pathinfo($rawType, PATHINFO_EXTENSION) ?: 'FILE');
            }

            $docSizeFormatted = $this->formatFileSize($doc->file_size);
            $lowerType = strtolower($doc->file_type ?: 'pdf');
            $canPreview = in_array($lowerType, ['pdf', 'png', 'jpg', 'jpeg', 'svg', 'webp'])
                || str_contains($lowerType, 'pdf')
                || str_contains($lowerType, 'image')
                || $doc->type === 'link';

            $documents[] = [
                'id' => $doc->id,
                'name' => $doc->name ?: 'Dokumen Unduhan',
                'file_type' => $docType,
                'file_size_formatted' => $docSizeFormatted,
                'download_url' => $doc->url,
                'preview_url' => route('download.show', $doc->id),
                'can_preview' => $canPreview,
            ];
        }

        // Navigation back options
        $options = [
            [
                'id' => 'module:downloads:root',
                'title' => 'Kembali ke Kategori Unduhan',
                'icon' => 'bi-arrow-left',
                'action_type' => 'module_sub',
                'module_key' => 'downloads',
                'module_param' => 'root',
            ],
            [
                'id' => 'root',
                'title' => 'Menu Utama',
                'icon' => 'bi-house',
                'action_type' => 'root',
            ],
        ];

        $startNum = (($page - 1) * $perPage) + 1;
        $endNum = min($page * $perPage, $total);

        $message = "### **{$catName}**\n";
        $message .= "Berikut adalah daftar dokumen dalam kategori **{$catName}** ({$startNum}-{$endNum} dari total {$total} dokumen, Hal. {$page}/{$totalPages}):\n";
        $message .= "📄 **Dokumen & Formulir Terkait:**";

        return [
            'title' => $catName,
            'message' => trim($message),
            'documents' => $documents,
            'options' => $options,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'base_id' => "module:downloads:category:{$catId}:page:",
                'module_key' => 'downloads',
                'module_param' => (string) $catId,
            ],
            'action_url' => route('download.index'),
            'action_label' => 'Buka Pusat Unduhan Dokumen',
            'action_icon' => 'bi-folder2-open',
            'action_icon_position' => 'left',
        ];
    }

    /**
     * Format bytes into readable KB / MB.
     */
    protected function formatFileSize(?int $bytes): string
    {
        if (!$bytes || $bytes <= 0) {
            return '';
        }

        $kb = $bytes / 1024;
        if ($kb >= 1024) {
            $mb = $kb / 1024;
            return round($mb, 1) . ' MB';
        }

        return round($kb) . ' KB';
    }
}
