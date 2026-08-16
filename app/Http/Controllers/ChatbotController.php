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
            'welcome_message' => 'Halo! Selamat datang di Pusat Layanan Kemahasiswaan POLBAN. Ada yang bisa kami bantu? Silakan pilih topik di bawah ini:',
            'options' => $roots,
        ]);
    }

    /**
     * Select a chatbot node / option.
     */
    public function select(Request $request): JsonResponse
    {
        $request->validate([
            'node_id' => 'nullable|integer',
            'action' => 'nullable|string',
        ]);

        $session = $this->getOrCreateSession($request);
        $action = $request->input('action');
        $nodeId = $request->input('node_id');

        // Handle 'root' or 'reset' action
        if ($action === 'root') {
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

        if (!$nodeId) {
            return response()->json(['status' => 'error', 'message' => 'Invalid node ID'], 422);
        }

        $node = ChatbotNode::query()
            ->active()
            ->find($nodeId);

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
        $actionUrl = $node->getResolvedActionUrl();
        $actionLabel = $node->action_label;
        $actionIcon = $node->action_icon;
        $actionIconPosition = $node->action_icon_position ?: 'left';

        // Handle 'module' action type
        if ($node->action_type === 'module' && $node->module_key) {
            $module = ChatbotModuleManager::getModule($node->module_key);
            if ($module) {
                $rendered = $module->renderResponse($session);
                if (!empty($rendered['message'])) {
                    $botMessage = trim($rendered['message']);
                }
                if (!empty($rendered['options'])) {
                    $childOptions = $rendered['options'];
                }
                if (!empty($rendered['action_url'])) {
                    $rawUrl = $rendered['action_url'];
                    $actionUrl = preg_match('/^https?:\/\//i', $rawUrl) ? $rawUrl : url($rawUrl);
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
            'action_url' => $actionUrl,
            'action_label' => $actionLabel,
            'action_icon' => $actionIcon,
            'action_icon_position' => $actionIconPosition,
        ]);
    }
}
