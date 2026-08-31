<?php

namespace App\Http\Controllers;

use App\Models\ChatbotLog;
use App\Models\ChatbotNode;
use App\Models\ChatbotSession;
use App\Services\Chatbot\ChatbotModuleManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Get or create browser session token.
     */
    protected function getOrCreateSession(Request $request): ChatbotSession
    {
        $token = $request->header('X-Chatbot-Session') ?: $request->input('session_token');

        if (!$token) {
            $token = (string) Str::uuid();
        }

        $session = ChatbotSession::firstOrCreate(
            ['session_token' => $token],
            [
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'last_activity_at' => now(),
            ]
        );

        $session->update(['last_activity_at' => now()]);

        return $session;
    }

    /**
     * Initialize chatbot widget (get welcome message & root options).
     */
    public function init(Request $request): JsonResponse
    {
        $session = $this->getOrCreateSession($request);

        $roots = ChatbotNode::query()
            ->roots()
            ->active()
            ->orderBy('sort_order')
            ->get(['id', 'title', 'icon', 'action_type']);

        return response()->json([
            'status' => 'success',
            'session_token' => $session->session_token,
            'welcome_message' => \App\Models\ChatbotSetting::getRandomWelcomeMessage(),
            'options' => $roots,
        ]);
    }

    /**
     * Select a chatbot node / option.
     */
    public function select(Request $request): JsonResponse
    {
        $request->validate([
            'node_id' => 'nullable',
            'action' => 'nullable|string',
            'module_key' => 'nullable|string',
            'module_param' => 'nullable|string',
            'sub_action' => 'nullable|string',
        ]);

        $session = $this->getOrCreateSession($request);
        $action = $request->input('action');
        $rawNodeId = $request->input('node_id');
        $moduleKey = $request->input('module_key');
        $moduleParam = $request->input('module_param');
        $subAction = $request->input('sub_action');

        // Handle 'root' or 'reset' action
        if ($action === 'root' || $rawNodeId === 'root') {
            $roots = ChatbotNode::query()
                ->roots()
                ->active()
                ->orderBy('sort_order')
                ->get(['id', 'title', 'icon', 'action_type']);

            return response()->json([
                'status' => 'success',
                'session_token' => $session->session_token,
                'message' => 'Silakan pilih topik utama di bawah ini:',
                'options' => $roots,
                'action_url' => null,
            ]);
        }

        // Parse module string format ID: module:{module_key}:{sub_action}:{param}(:page:{page_num})
        $pageParam = 1;
        if (is_string($rawNodeId) && str_starts_with($rawNodeId, 'module:')) {
            $parts = explode(':', $rawNodeId);
            $moduleKey = $parts[1] ?? $moduleKey;
            $subAction = $parts[2] ?? $subAction;
            $moduleParam = $parts[3] ?? $moduleParam;

            if (isset($parts[4]) && $parts[4] === 'page' && isset($parts[5])) {
                $pageParam = (int) $parts[5];
            }
        }

        // Direct Module Handling for Sub-actions
        if ($moduleKey) {
            $module = ChatbotModuleManager::getModule($moduleKey);
            if ($module) {
                $rendered = $module->renderResponse($session, [
                    'sub_action' => $subAction,
                    'param' => $moduleParam,
                    'service_id' => $moduleParam,
                    'page' => $pageParam,
                    'raw_node_id' => $rawNodeId,
                ]);

                $rawUrl = $rendered['action_url'] ?? null;
                $actionUrl = ChatbotNode::resolveSmartUrl($rawUrl);

                // Log the interaction
                ChatbotLog::create([
                    'session_id' => $session->id,
                    'node_id' => null,
                    'user_action' => 'Module: ' . $moduleKey . ($subAction ? ':' . $subAction : ''),
                    'bot_response_summary' => Str::limit(strip_tags($rendered['message'] ?? ''), 200),
                    'created_at' => now(),
                ]);

                return response()->json([
                    'status' => 'success',
                    'session_token' => $session->session_token,
                    'title' => $rendered['title'] ?? 'Dynamic Module',
                    'message' => trim($rendered['message'] ?? ''),
                    'options' => $rendered['options'] ?? [],
                    'pagination' => $rendered['pagination'] ?? null,
                    'documents' => $rendered['documents'] ?? [],
                    'action_url' => $actionUrl,
                    'action_label' => $rendered['action_label'] ?? null,
                    'action_icon' => $rendered['action_icon'] ?? null,
                    'action_icon_position' => $rendered['action_icon_position'] ?? 'left',
                ]);
            }
        }

        if (!$rawNodeId || !is_numeric($rawNodeId)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid node ID'], 422);
        }

        $node = ChatbotNode::query()
            ->active()
            ->find((int) $rawNodeId);

        if (!$node) {
            return response()->json(['status' => 'error', 'message' => 'Topik tidak ditemukan atau sudah tidak aktif.'], 444);
        }

        // If node action is 'jump', resolve to target node
        if ($node->action_type === 'jump' && $node->target_node_id) {
            $targetNode = ChatbotNode::query()->active()->find($node->target_node_id);
            if ($targetNode) {
                $node = $targetNode;
            }
        }

        $botMessage = $node->getRandomResponse();
        $childOptions = [];
        $documents = [];
        $actionUrl = $node->getResolvedActionUrl();
        $actionLabel = $node->action_label;
        $actionIcon = $node->action_icon;
        $actionIconPosition = $node->action_icon_position ?: 'left';

        // Handle 'module' action type on a ChatbotNode
        if ($node->action_type === 'module' && $node->module_key) {
            $module = ChatbotModuleManager::getModule($node->module_key);
            if ($module) {
                $rendered = $module->renderResponse($session, [
                    'sub_action' => 'root',
                ]);
                if (!empty($rendered['message'])) {
                    $botMessage = trim($rendered['message']);
                }
                if (!empty($rendered['options'])) {
                    $childOptions = $rendered['options'];
                }
                if (!empty($rendered['documents'])) {
                    $documents = $rendered['documents'];
                }
                if (!empty($rendered['action_url'])) {
                    $rawUrl = $rendered['action_url'];
                    $actionUrl = ChatbotNode::resolveSmartUrl($rawUrl);
                    $actionLabel = $rendered['action_label'] ?? 'Buka Halaman Related';
                    $actionIcon = $rendered['action_icon'] ?? 'bi-box-arrow-up-right';
                    $actionIconPosition = $rendered['action_icon_position'] ?? 'left';
                }
            }
        }

        // If normal node or info node, fetch child options if any
        if (empty($childOptions) && in_array($node->action_type, ['node', 'options', 'jump'])) {
            $childOptions = $node->activeChildren()
                ->get(['id', 'title', 'icon', 'action_type'])
                ->toArray();
        }

        // Log the interaction
        ChatbotLog::create([
            'session_id' => $session->id,
            'node_id' => $node->id,
            'user_action' => $node->title,
            'bot_response_summary' => Str::limit(strip_tags($botMessage), 200),
            'created_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'session_token' => $session->session_token,
            'node_id' => $node->id,
            'title' => $node->title,
            'icon' => $node->icon,
            'message' => $botMessage ?: 'Berikut informasi yang dapat kami sajikan:',
            'options' => $childOptions,
            'documents' => $documents,
            'action_url' => $actionUrl,
            'action_label' => $actionLabel,
            'action_icon' => $actionIcon,
            'action_icon_position' => $actionIconPosition,
        ]);
    }
}
