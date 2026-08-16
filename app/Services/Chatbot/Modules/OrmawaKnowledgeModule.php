<?php

namespace App\Services\Chatbot\Modules;

use App\Contracts\ChatbotModuleInterface;
use App\Models\ChatbotSession;
use App\Models\StudentOrganization;

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
        $orgs = StudentOrganization::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        if ($orgs->isEmpty()) {
            return [
                'message' => 'Saat ini belum ada data Ormawa yang terdaftar.',
                'options' => [],
                'action_url' => route('ormawa.index'),
            ];
        }

        $summary = "Berikut adalah daftar Organisasi & UKM Mahasiswa POLBAN yang sedang aktif:\n";
        foreach ($orgs->take(8) as $org) {
            $summary .= "• **{$org->name}**\n";
        }

        if ($orgs->count() > 8) {
            $summary .= "\n...dan " . ($orgs->count() - 8) . " Ormawa/UKM lainnya.";
        }

        return [
            'message' => trim($summary),
            'options' => [],
            'action_url' => route('ormawa.index'),
            'action_label' => 'Lihat Seluruh Katalog Ormawa',
        ];
    }
}
