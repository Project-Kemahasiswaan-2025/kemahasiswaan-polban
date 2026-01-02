<?php

namespace Database\Seeders;

use App\Models\Video;
use App\Models\Language;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        $indonesian = Language::where('code', 'id')->first();
        
        $videos = [
            [
                'language_id' => $indonesian->id,
                'title' => 'Profil Kemahasiswaan Polban',
                'description' => 'Video profil Direktorat Kemahasiswaan dan Alumni Polban',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'thumbnail_url' => null,
                'is_active' => true,
                'is_pinned' => true,
                'published_at' => now(),
                'sort_order' => 1,
            ],
            [
                'language_id' => $indonesian->id,
                'title' => 'Kegiatan Mahasiswa Polban',
                'description' => 'Berbagai kegiatan mahasiswa di Polban',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'thumbnail_url' => null,
                'is_active' => true,
                'is_pinned' => false,
                'published_at' => now(),
                'sort_order' => 2,
            ],
            [
                'language_id' => $indonesian->id,
                'title' => 'Prestasi Mahasiswa Polban',
                'description' => 'Pencapaian dan prestasi mahasiswa Polban',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'thumbnail_url' => null,
                'is_active' => true,
                'is_pinned' => false,
                'published_at' => now(),
                'sort_order' => 3,
            ],
        ];

        foreach ($videos as $video) {
            Video::updateOrCreate(
                ['title' => $video['title']],
                $video
            );
        }
    }
}
