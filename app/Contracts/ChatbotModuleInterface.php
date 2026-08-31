<?php

namespace App\Contracts;

use App\Models\ChatbotSession;

interface ChatbotModuleInterface
{
    /**
     * Get the unique key identifier for this module.
     */
    public static function getKey(): string;

    /**
     * Get the human-readable label for this module.
     */
    public static function getLabel(): string;

    /**
     * Render the chatbot response for this module.
     *
     * @return array Structure: ['message' => string, 'options' => array, 'action_url' => ?string]
     */
    public function renderResponse(ChatbotSession $session, array $params = []): array;
}
