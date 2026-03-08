<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\CompetitionThread;
use App\Models\CompetitionTimeline;
use App\Models\Poster;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompetitionThreadSeeder extends Seeder
{
    public function run(): void
    {
        // Get all competitions that are NOT groups
        $competitions = Competition::where('is_group', false)->get();

        // Get all posters to use randomly
        $posters = Poster::all();

        foreach ($competitions as $index => $competition) {
            // Decide a random status
            $statuses = ['ongoing', 'registration_closed', 'completed'];
            $status = $statuses[array_rand($statuses)];

            // Create a thread
            $thread = CompetitionThread::updateOrCreate(
                ['slug' => $competition->slug . '-thread'],
                [
                    'competition_id' => $competition->id,
                    'poster_id' => $posters->isNotEmpty() ? $posters->random()->id : null,
                    'title' => 'Pengumuman ' . $competition->name,
                    'content' => '<p>Pendaftaran untuk ' . $competition->name . ' telah dibuka! Silakan baca panduan lengkap dan lakukan pendaftaran melalui link yang disediakan.</p>',
                    'registration_start' => now()->subDays(rand(0, 10)),
                    'registration_end' => now()->addDays(rand(10, 30)),
                    'status' => $status,
                    'post_url' => 'https://polban.ac.id/kompetisi/' . $competition->slug,
                    'registration_url' => 'https://bit.ly/daftar-' . $competition->slug,
                    'guidebook_url' => 'https://bit.ly/juknis-' . $competition->slug,
                    'contact_info' => '08123456789 (CP: Admin)',
                    'location' => 'Online / Kampus Polban',
                    'is_active' => true,
                    'is_featured' => $index < 3,
                ]
            );

            // Add some timeline stages
            $stages = [
                ['label' => 'Pendaftaran', 'days' => 0],
                ['label' => 'Technical Meeting', 'days' => 15],
                ['label' => 'Penyisihan', 'days' => 20],
                ['label' => 'Final', 'days' => 30],
            ];

            foreach ($stages as $sort => $stage) {
                CompetitionTimeline::updateOrCreate(
                    [
                        'competition_thread_id' => $thread->id,
                        'label' => $stage['label']
                    ],
                    [
                        'date' => now()->addDays($stage['days']),
                        'sort_order' => $sort,
                    ]
                );
            }
        }
    }
}
