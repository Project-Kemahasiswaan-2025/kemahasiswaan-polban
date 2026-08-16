<?php

namespace App\Services\Chatbot;

use App\Contracts\ChatbotModuleInterface;
use App\Services\Chatbot\Modules\DownloadKnowledgeModule;
use App\Services\Chatbot\Modules\OrmawaKnowledgeModule;
use App\Services\Chatbot\Modules\ServiceKnowledgeModule;

class ChatbotModuleManager
{
    /**
     * Registered modules mapped by key => ClassName
     */
    protected static array $modules = [
        'ormawa' => OrmawaKnowledgeModule::class,
        'services' => ServiceKnowledgeModule::class,
        'downloads' => DownloadKnowledgeModule::class,
    ];

    public static function getModules(): array
    {
        $list = [];
        foreach (self::$modules as $key => $class) {
            if (class_exists($class)) {
                $list[$key] = $class::getLabel();
            }
        }
        return $list;
    }

    public static function getModule(string $key): ?ChatbotModuleInterface
    {
        if (isset(self::$modules[$key]) && class_exists(self::$modules[$key])) {
            return new self::$modules[$key]();
        }
        return null;
    }
}
