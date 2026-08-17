<?php

namespace App\Services\Chatbot\Modules;

use App\Contracts\ChatbotModuleInterface;
use App\Models\ChatbotNode;
use App\Models\ChatbotSession;
use App\Models\Service;
use Illuminate\Support\Str;

class ServiceKnowledgeModule implements ChatbotModuleInterface
{
    public static function getKey(): string
    {
        return 'services';
    }

    public static function getLabel(): string
    {
        return 'Modul Dinamis: Layanan Kemahasiswaan';
    }

    public function renderResponse(ChatbotSession $session, array $params = []): array
    {
        $subAction = $params['sub_action'] ?? null;
        $serviceId = $params['service_id'] ?? $params['param'] ?? null;

        // STEP 2: Render Service Detail if service_id / detail sub-action is provided
        if ($subAction === 'detail' || ($serviceId && is_numeric($serviceId))) {
            return $this->renderServiceDetail((int) $serviceId);
        }

        // STEP 1: Render Root Listing of Active Services
        return $this->renderServicesList();
    }

    /**
     * Render Step 1: Listing of active services as option buttons.
     */
    protected function renderServicesList(): array
    {
        $services = Service::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        if ($services->isEmpty()) {
            return [
                'title' => 'Layanan Kemahasiswaan',
                'message' => 'Saat ini belum ada data layanan kemahasiswaan yang terdaftar.',
                'options' => [
                    [
                        'id' => 'root',
                        'title' => 'Menu Utama',
                        'icon' => 'bi-house',
                        'action_type' => 'root',
                    ],
                ],
                'action_url' => null,
            ];
        }

        $options = [];
        foreach ($services as $service) {
            $options[] = [
                'id' => 'module:services:detail:' . $service->id,
                'title' => $service->name,
                'icon' => $service->icon ?: 'bi-mortarboard',
                'action_type' => 'module_sub',
                'module_key' => 'services',
                'module_param' => (string) $service->id,
            ];
        }

        return [
            'title' => 'Layanan Kemahasiswaan',
            'message' => "Berikut adalah daftar layanan kemahasiswaan POLBAN yang dapat Anda akses:\nSilakan pilih salah satu layanan di bawah ini untuk melihat rincian informasi lengkap:",
            'options' => $options,
            'action_url' => null,
            'documents' => [],
        ];
    }

    /**
     * Render Step 2: Service Detail view with excerpt, filtered links, document cards, CTA, and simplified navigation.
     */
    protected function renderServiceDetail(int $serviceId): array
    {
        $service = Service::with([
            'links' => fn($q) => $q->where('is_active', true)->orderBy('sort_order'),
            'downloads' => fn($q) => $q->where('is_active', true)->orderBy('sort_order'),
        ])->active()->find($serviceId);

        if (!$service) {
            return [
                'title' => 'Layanan Tidak Ditemukan',
                'message' => 'Maaf, layanan yang Anda pilih tidak ditemukan atau sedang tidak aktif.',
                'options' => [
                    [
                        'id' => 'module:services:root',
                        'title' => 'Kembali ke Daftar Layanan',
                        'icon' => 'bi-arrow-left',
                        'action_type' => 'module_sub',
                        'module_key' => 'services',
                        'module_param' => 'root',
                    ],
                ],
                'action_url' => null,
                'documents' => [],
            ];
        }

        // Header with compact line break to excerpt
        $message = "### **{$service->name}**\n";

        // 1. Excerpt / Summary
        $excerpt = $service->excerpt ?: strip_tags(Str::limit($service->content, 220));
        $message .= trim($excerpt);

        // 2. Tautan Informasi Lanjutan (Filtered: Exclude main CTA URL)
        $mainCtaUrl = $service->cta_url ? ChatbotNode::resolveSmartUrl($service->cta_url) : route('service.show', $service->slug);
        $filteredLinks = $service->links->filter(function ($link) use ($mainCtaUrl) {
            if (empty($link->url)) return false;
            $resolved = ChatbotNode::resolveSmartUrl($link->url);
            return trim($resolved) !== trim($mainCtaUrl);
        });

        if ($filteredLinks->isNotEmpty()) {
            $message .= "\n\n🔗 **Tautan Informasi Lanjutan:**\n";
            foreach ($filteredLinks as $link) {
                $linkName = $link->name ?: 'Tautan Informasi';
                $linkUrl = ChatbotNode::resolveSmartUrl($link->url);
                $message .= "• [{$linkName}]({$linkUrl})\n";
            }
        }

        // 3. Dokumen Terkait (Structured Document Block Cards)
        $documents = [];
        if ($service->downloads->isNotEmpty()) {
            $message .= "\n\n📄 **Dokumen & Formulir Terkait:**";
            foreach ($service->downloads as $doc) {
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

                $docSizeFormatted = $doc->file_size ? round($doc->file_size / 1024) . ' KB' : '';
                $lowerType = strtolower($doc->file_type ?: 'pdf');
                $canPreview = in_array($lowerType, ['pdf', 'png', 'jpg', 'jpeg', 'svg', 'webp'])
                    || str_contains($lowerType, 'pdf')
                    || str_contains($lowerType, 'image')
                    || $doc->type === 'link';

                $documents[] = [
                    'id' => $doc->id,
                    'name' => $doc->name ?: 'Dokumen Terkait',
                    'file_type' => $docType,
                    'file_size_formatted' => $docSizeFormatted,
                    'download_url' => $doc->url,
                    'preview_url' => route('download.show', $doc->id),
                    'can_preview' => $canPreview,
                ];
            }
        }

        // 4. Simplified Navigation Back Options
        $options = [
            [
                'id' => 'module:services:root',
                'title' => 'Kembali ke Daftar Layanan',
                'icon' => 'bi-arrow-left',
                'action_type' => 'module_sub',
                'module_key' => 'services',
                'module_param' => 'root',
            ],
            [
                'id' => 'root',
                'title' => 'Menu Utama',
                'icon' => 'bi-house',
                'action_type' => 'root',
            ],
        ];

        // 5. Main CTA Button
        $targetCtaUrl = $service->cta_url ? ChatbotNode::resolveSmartUrl($service->cta_url) : route('service.show', $service->slug);
        $targetCtaLabel = $service->cta_label ?: "Buka Detail Layanan {$service->name}";

        return [
            'title' => $service->name,
            'message' => trim($message),
            'options' => $options,
            'documents' => $documents,
            'action_url' => $targetCtaUrl,
            'action_label' => $targetCtaLabel,
            'action_icon' => 'bi-box-arrow-up-right',
            'action_icon_position' => 'left',
        ];
    }
}
