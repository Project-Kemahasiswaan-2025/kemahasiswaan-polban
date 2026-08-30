<?php

namespace App\Services\Chatbot\Modules;

use App\Contracts\ChatbotModuleInterface;
use App\Models\ChatbotNode;
use App\Models\ChatbotSession;
use App\Models\CompetitionThread;
use Illuminate\Support\Str;

class CompetitionKnowledgeModule implements ChatbotModuleInterface
{
    public static function getKey(): string
    {
        return 'competitions';
    }

    public static function getLabel(): string
    {
        return 'Modul Dinamis: Kompetisi & Kejuaraan Mahasiswa';
    }

    public function renderResponse(ChatbotSession $session, array $params = []): array
    {
        $subAction = $params['sub_action'] ?? null;
        $param = $params['param'] ?? null;

        // Sub-action: Detail competition thread
        if ($subAction === 'detail' || ($param !== null && is_numeric($param) && !in_array($subAction, ['open', 'ongoing', 'latest']))) {
            return $this->renderCompetitionDetail((int) $param);
        }

        // Sub-action: Latest competitions list (max 3)
        if ($subAction === 'latest') {
            return $this->renderLatestCompetitions();
        }

        // Sub-action: Open registration competitions list (max 3)
        if ($subAction === 'open') {
            return $this->renderOpenCompetitions();
        }

        // Sub-action: Ongoing competitions list (max 3)
        if ($subAction === 'ongoing') {
            return $this->renderOngoingCompetitions();
        }

        // Default / Root: Main Competition Options
        return $this->renderRootOptions();
    }

    /**
     * Render Step 1: Root Main Options for Competitions (Latest Options First, No Pagination).
     */
    protected function renderRootOptions(): array
    {
        $options = [
            [
                'id' => 'module:competitions:latest',
                'title' => 'Kompetisi Terbaru',
                'icon' => 'bi-trophy-fill',
                'action_type' => 'module_sub',
                'module_key' => 'competitions',
                'module_param' => 'latest',
            ],
            [
                'id' => 'module:competitions:open',
                'title' => 'Pendaftaran Masih Dibuka',
                'icon' => 'bi-fire',
                'action_type' => 'module_sub',
                'module_key' => 'competitions',
                'module_param' => 'open',
            ],
            [
                'id' => 'module:competitions:ongoing',
                'title' => 'Sedang Berlangsung',
                'icon' => 'bi-lightning-charge-fill',
                'action_type' => 'module_sub',
                'module_key' => 'competitions',
                'module_param' => 'ongoing',
            ],
            [
                'id' => 'root',
                'title' => 'Menu Utama',
                'icon' => 'bi-house',
                'action_type' => 'root',
            ],
        ];

        $message = "### **Kompetisi & Kejuaraan Mahasiswa POLBAN**\n";
        $message .= "Pilih opsi informasi kompetisi di bawah ini untuk melihat pengumuman dan rincian pendaftaran:";

        return [
            'title' => 'Kompetisi & Kejuaraan Mahasiswa',
            'message' => trim($message),
            'options' => $options,
            'action_url' => route('competition.index'),
            'action_label' => 'Buka Informasi Kompetisi',
            'action_icon' => 'bi-trophy',
            'action_icon_position' => 'left',
        ];
    }

    /**
     * Determine smart status of competition thread based on dates & timelines.
     * Returns: 'open' | 'ongoing' | 'completed' | 'closed'
     */
    protected function getSmartThreadStatus(CompetitionThread $thread): string
    {
        $now = now();
        $maxTimelineDate = $thread->timelines ? $thread->timelines->max('date') : null;
        $maxDate = $maxTimelineDate ?: $thread->registration_end;

        // If max timeline date or registration end is strictly in the past, it is COMPLETED!
        if ($maxDate && $now->gt($maxDate)) {
            return 'completed';
        }

        if ($thread->status === 'completed') {
            return 'completed';
        }

        // Check if registration is open right now
        if ($thread->registration_end && $now->lte($thread->registration_end)) {
            if (!$thread->registration_start || $now->gte($thread->registration_start)) {
                return 'open';
            }
        }

        // If ongoing status or timeline date hasn't passed yet
        if ($thread->status === 'ongoing' || ($maxDate && $now->lte($maxDate))) {
            return 'ongoing';
        }

        return 'closed';
    }

    /**
     * Render Latest Competitions list (max 3 items from CompetitionThread).
     */
    protected function renderLatestCompetitions(): array
    {
        $threads = CompetitionThread::query()
            ->with(['competition.parent', 'poster', 'timelines'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        if ($threads->isEmpty()) {
            return [
                'title' => 'Kompetisi Terbaru',
                'message' => 'Saat ini belum ada pengumuman kompetisi yang terdaftar.',
                'options' => [
                    [
                        'id' => 'module:competitions:root',
                        'title' => 'Kembali ke Pilihan Kompetisi',
                        'icon' => 'bi-arrow-left',
                        'action_type' => 'module_sub',
                        'module_key' => 'competitions',
                        'module_param' => 'root',
                    ],
                    [
                        'id' => 'root',
                        'title' => 'Menu Utama',
                        'icon' => 'bi-house',
                        'action_type' => 'root',
                    ],
                ],
                'action_url' => route('competition.index'),
                'action_label' => 'Lihat Informasi Kompetisi',
            ];
        }

        $options = [];
        foreach ($threads as $t) {
            $compName = $t->competition ? $t->competition->name : 'Kompetisi';
            $options[] = [
                'id' => "module:competitions:detail:{$t->id}",
                'title' => $t->title ?: $compName,
                'icon' => 'bi-trophy-fill',
                'action_type' => 'module_sub',
                'module_key' => 'competitions',
                'module_param' => (string) $t->id,
            ];
        }

        $options[] = [
            'id' => 'module:competitions:root',
            'title' => 'Kembali ke Pilihan Kompetisi',
            'icon' => 'bi-arrow-left',
            'action_type' => 'module_sub',
            'module_key' => 'competitions',
            'module_param' => 'root',
        ];

        $options[] = [
            'id' => 'root',
            'title' => 'Menu Utama',
            'icon' => 'bi-house',
            'action_type' => 'root',
        ];

        $message = "### **Pengumuman Kompetisi Terbaru**\n";
        $message .= "Berikut 3 pengumuman kompetisi terbaru di POLBAN. Silakan pilih salah satu di bawah ini:";

        return [
            'title' => 'Kompetisi Terbaru',
            'message' => trim($message),
            'options' => $options,
            'action_url' => route('competition.index'),
            'action_label' => 'Buka Informasi Kompetisi',
            'action_icon' => 'bi-trophy',
        ];
    }

    /**
     * Render Open Registration Competitions list (max 3 items with smart date filter).
     */
    protected function renderOpenCompetitions(): array
    {
        $allThreads = CompetitionThread::query()
            ->with(['competition.parent', 'poster', 'timelines'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $openThreads = $allThreads->filter(function ($t) {
            return $this->getSmartThreadStatus($t) === 'open';
        })->take(3);

        if ($openThreads->isEmpty()) {
            return [
                'title' => 'Pendaftaran Masih Dibuka',
                'message' => "Saat ini belum ada pengumuman kompetisi yang sedang membuka pendaftaran.",
                'options' => [
                    [
                        'id' => 'module:competitions:root',
                        'title' => 'Kembali ke Pilihan Kompetisi',
                        'icon' => 'bi-arrow-left',
                        'action_type' => 'module_sub',
                        'module_key' => 'competitions',
                        'module_param' => 'root',
                    ],
                    [
                        'id' => 'root',
                        'title' => 'Menu Utama',
                        'icon' => 'bi-house',
                        'action_type' => 'root',
                    ],
                ],
                'action_url' => route('competition.index'),
                'action_label' => 'Lihat Informasi Kompetisi',
            ];
        }

        $options = [];
        foreach ($openThreads as $t) {
            $compName = $t->competition ? $t->competition->name : 'Kompetisi';
            $options[] = [
                'id' => "module:competitions:detail:{$t->id}",
                'title' => $t->title ?: $compName,
                'icon' => 'bi-fire',
                'action_type' => 'module_sub',
                'module_key' => 'competitions',
                'module_param' => (string) $t->id,
            ];
        }

        $options[] = [
            'id' => 'module:competitions:root',
            'title' => 'Kembali ke Pilihan Kompetisi',
            'icon' => 'bi-arrow-left',
            'action_type' => 'module_sub',
            'module_key' => 'competitions',
            'module_param' => 'root',
        ];

        $options[] = [
            'id' => 'root',
            'title' => 'Menu Utama',
            'icon' => 'bi-house',
            'action_type' => 'root',
        ];

        $message = "### **Kompetisi Pendaftaran Masih Dibuka**\n";
        $message .= "Berikut pengumuman kompetisi yang pendaftarannya saat ini masih aktif. Silakan pilih untuk melihat rincian & deadline:";

        return [
            'title' => 'Pendaftaran Masih Dibuka',
            'message' => trim($message),
            'options' => $options,
            'action_url' => route('competition.index'),
            'action_label' => 'Buka Informasi Kompetisi',
            'action_icon' => 'bi-trophy',
        ];
    }

    /**
     * Render Ongoing Competitions list (max 3 items with smart date filter).
     */
    protected function renderOngoingCompetitions(): array
    {
        $allThreads = CompetitionThread::query()
            ->with(['competition.parent', 'poster', 'timelines'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $ongoingThreads = $allThreads->filter(function ($t) {
            return $this->getSmartThreadStatus($t) === 'ongoing';
        })->take(3);

        if ($ongoingThreads->isEmpty()) {
            return [
                'title' => 'Kompetisi Berlangsung',
                'message' => "Saat ini belum ada pengumuman kompetisi yang sedang berlangsung.",
                'options' => [
                    [
                        'id' => 'module:competitions:root',
                        'title' => 'Kembali ke Pilihan Kompetisi',
                        'icon' => 'bi-arrow-left',
                        'action_type' => 'module_sub',
                        'module_key' => 'competitions',
                        'module_param' => 'root',
                    ],
                    [
                        'id' => 'root',
                        'title' => 'Menu Utama',
                        'icon' => 'bi-house',
                        'action_type' => 'root',
                    ],
                ],
                'action_url' => route('competition.index'),
                'action_label' => 'Lihat Informasi Kompetisi',
            ];
        }

        $options = [];
        foreach ($ongoingThreads as $t) {
            $compName = $t->competition ? $t->competition->name : 'Kompetisi';
            $options[] = [
                'id' => "module:competitions:detail:{$t->id}",
                'title' => $t->title ?: $compName,
                'icon' => 'bi-lightning-charge-fill',
                'action_type' => 'module_sub',
                'module_key' => 'competitions',
                'module_param' => (string) $t->id,
            ];
        }

        $options[] = [
            'id' => 'module:competitions:root',
            'title' => 'Kembali ke Pilihan Kompetisi',
            'icon' => 'bi-arrow-left',
            'action_type' => 'module_sub',
            'module_key' => 'competitions',
            'module_param' => 'root',
        ];

        $options[] = [
            'id' => 'root',
            'title' => 'Menu Utama',
            'icon' => 'bi-house',
            'action_type' => 'root',
        ];

        $message = "### **Kompetisi Sedang Berlangsung (Ongoing)**\n";
        $message .= "Berikut pengumuman kompetisi yang saat ini sedang berlangsung:";

        return [
            'title' => 'Kompetisi Berlangsung',
            'message' => trim($message),
            'options' => $options,
            'action_url' => route('competition.index'),
            'action_label' => 'Buka Informasi Kompetisi',
            'action_icon' => 'bi-trophy',
        ];
    }

    /**
     * Render Step 2: Rich Competition Thread Detail Card with Smart Timeline Status.
     */
    protected function renderCompetitionDetail(int $threadId): array
    {
        $thread = CompetitionThread::with(['competition.parent', 'poster', 'timelines'])
            ->where('is_active', true)
            ->find($threadId);

        if (!$thread) {
            return $this->renderRootOptions();
        }

        $message = '';

        // 1. Cover / Poster Image (Matching Api/CompetitionController logic)
        $imageUrl = null;
        if ($thread->poster && $thread->poster->image_path) {
            $imageUrl = asset('storage/' . $thread->poster->image_path);
        } elseif ($thread->custom_image) {
            $imageUrl = preg_match('/^https?:\/\//i', $thread->custom_image) ? $thread->custom_image : asset('storage/' . ltrim($thread->custom_image, '/'));
        } elseif ($thread->competition && $thread->competition->cover_image) {
            $imageUrl = preg_match('/^https?:\/\//i', $thread->competition->cover_image) ? $thread->competition->cover_image : asset('storage/' . ltrim($thread->competition->cover_image, '/'));
        }

        if (!empty($imageUrl)) {
            $message .= "![Cover {$thread->title}]({$imageUrl})\n\n";
        }

        // 2. Title & Category Name
        $message .= "### **{$thread->title}**\n";
        if ($thread->competition && $thread->competition->name) {
            $message .= "🏆 **Kategori:** {$thread->competition->name}\n";
        }

        // 3. Smart Status Badge
        $smartStatus = $this->getSmartThreadStatus($thread);
        $statusLabel = '❌ Pendaftaran Ditutup';
        if ($smartStatus === 'open') {
            $statusLabel = '🔥 Pendaftaran Masih Dibuka';
        } elseif ($smartStatus === 'ongoing') {
            $statusLabel = '⚡ Sedang Berlangsung (Ongoing)';
        } elseif ($smartStatus === 'completed') {
            $statusLabel = '✅ Selesai (Completed)';
        }
        $message .= "**Status:** {$statusLabel}\n";

        // 4. Registration Timeline (Deadline) & Location
        if ($thread->registration_start || $thread->registration_end) {
            $start = $thread->registration_start ? $thread->registration_start->format('d M Y') : '-';
            $end = $thread->registration_end ? $thread->registration_end->format('d M Y') : '-';
            $message .= "📅 **Buka Pendaftaran:** {$start}\n";
            $message .= "⏰ **Batas Deadline:** {$end}\n";
        }

        if (!empty($thread->location)) {
            $message .= "📍 **Tingkat/Lokasi:** {$thread->location}\n";
        }

        // 5. Excerpt / Summary Content
        $excerpt = strip_tags(Str::limit($thread->content ?: ($thread->competition->content ?? ''), 280));
        if ($excerpt) {
            $message .= "\n" . trim($excerpt);
        }

        // 6. Options back navigation
        $options = [
            [
                'id' => 'module:competitions:root',
                'title' => 'Kembali ke Pilihan Kompetisi',
                'icon' => 'bi-arrow-left',
                'action_type' => 'module_sub',
                'module_key' => 'competitions',
                'module_param' => 'root',
            ],
            [
                'id' => 'root',
                'title' => 'Menu Utama',
                'icon' => 'bi-house',
                'action_type' => 'root',
            ],
        ];

        // 7. Main CTA Link Direct
        $targetCtaUrl = $thread->registration_url ? ChatbotNode::resolveSmartUrl($thread->registration_url) : ($thread->post_url ? ChatbotNode::resolveSmartUrl($thread->post_url) : route('competition.index'));
        $targetCtaLabel = $thread->registration_url ? 'Daftar Sekarang' : 'Buka Detail Kompetisi';

        return [
            'title' => $thread->title,
            'message' => trim($message),
            'options' => $options,
            'action_url' => $targetCtaUrl,
            'action_label' => $targetCtaLabel,
            'action_icon' => 'bi-box-arrow-up-right',
            'action_icon_position' => 'left',
        ];
    }
}
