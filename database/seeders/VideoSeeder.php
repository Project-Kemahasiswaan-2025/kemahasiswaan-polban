<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        // Video Categories
        $categories = [
            [
                'name' => 'Profil & Fasilitas',
                'slug' => 'profil-fasilitas',
                'type' => 'video',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Kegiatan Mahasiswa',
                'slug' => 'kegiatan-mahasiswa',
                'type' => 'video',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Tutorial & Panduan',
                'slug' => 'tutorial-panduan',
                'type' => 'video',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['slug']] = \App\Models\Category::updateOrCreate(
                ['slug' => $cat['slug'], 'type' => 'video'],
                $cat
            );
        }

        $videos = [
            [
                'category_id' => $categoryModels['profil-fasilitas']->id,
                'title' => 'Profil Kemahasiswaan Polban',
                'description' => 'Video profil Direktorat Kemahasiswaan dan Alumni Polban',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'thumbnail_url' => null,
                'is_active' => true,
                'is_pinned' => true,
                'active_from' => now(),
                'sort_order' => 1,
            ],
            [
                'category_id' => $categoryModels['kegiatan-mahasiswa']->id,
                'title' => 'Malam Inagurasi 2024',
                'description' => 'Keseruan malam inagurasi mahasiswa baru Polban 2024',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'thumbnail_url' => null,
                'is_active' => true,
                'is_pinned' => false,
                'active_from' => now(),
                'sort_order' => 2,
            ],
            [
                'category_id' => $categoryModels['tutorial-panduan']->id,
                'title' => 'Panduan KRS Online',
                'description' => 'Langkah-langkah pengisian KRS online bagi mahasiswa',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'thumbnail_url' => null,
                'is_active' => true,
                'is_pinned' => false,
                'active_from' => now(),
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
