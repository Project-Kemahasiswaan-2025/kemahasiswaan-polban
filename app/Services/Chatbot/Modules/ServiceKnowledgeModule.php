<?php

namespace App\Services\Chatbot\Modules;

use App\Contracts\ChatbotModuleInterface;
use App\Models\ChatbotSession;
use App\Models\Service;

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
        $services = Service::orderBy('sort_order')->get();

        if ($services->isEmpty()) {
            return [
                'message' => 'Saat ini belum ada layanan yang terdaftar.',
                'options' => [],
                'action_url' => route('home'),
            ];
        }

        $summary = "Berikut adalah layanan kemahasiswaan POLBAN yang dapat Anda akses:\n";
        $options = [];

        foreach ($services as $service) {
            $summary .= "• **{$service->name}**\n";
        }

        return [
            'message' => trim($summary),
            'options' => $options,
            'action_url' => route('home') . '#layanan',
            'action_label' => 'Buka Halaman Layanan',
        ];
    }
}
