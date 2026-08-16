<?php

namespace App\Services\Chatbot\Modules;

use App\Contracts\ChatbotModuleInterface;
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
        $downloads = Download::where('is_active', true)
            ->whereNotNull('category_id')
            ->orderBy('sort_order')
            ->take(5)
            ->get();

        if ($downloads->isEmpty()) {
            return [
                'message' => 'Saat ini belum ada dokumen unduhan publik tersedia.',
                'options' => [],
                'action_url' => route('download.index'),
            ];
        }

        $summary = "Berikut adalah beberapa dokumen unduhan terbaru yang tersedia di Pusat Unduhan:\n";
        foreach ($downloads as $doc) {
            $summary .= "• **{$doc->name}**\n";
        }

        return [
            'message' => trim($summary),
            'options' => [],
            'action_url' => route('download.index'),
            'action_label' => 'Buka Pusat Unduhan Dokumen',
        ];
    }
}
