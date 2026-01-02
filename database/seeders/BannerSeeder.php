<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Language;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $indonesian = Language::where('code', 'id')->first();
        
        $banners = [
            [
                'language_id' => $indonesian->id,
                'title' => 'Selamat Datang di Kemahasiswaan Polban',
                'image_path' => 'https://via.placeholder.com/1200x500/001f3f/ffffff?text=Kemahasiswaan+Polban',
                'url' => null,
                'url_target' => '_self',
                'is_active' => true,
                'is_pinned' => true,
                'sort_order' => 1,
                'active_from' => now(),
                'active_to' => null,
            ],
            [
                'language_id' => $indonesian->id,
                'title' => 'Bergabung dengan Organisasi Mahasiswa',
                'image_path' => 'https://via.placeholder.com/1200x500/ff6b35/ffffff?text=Organisasi+Mahasiswa',
                'url' => route('ormawa.index'),
                'url_target' => '_self',
                'is_active' => true,
                'is_pinned' => false,
                'sort_order' => 2,
                'active_from' => now(),
                'active_to' => null,
            ],
            [
                'language_id' => $indonesian->id,
                'title' => 'Ikuti Kompetisi Mahasiswa',
                'image_path' => 'https://via.placeholder.com/1200x500/0d47a1/ffffff?text=Kompetisi+Mahasiswa',
                'url' => route('competition.index'),
                'url_target' => '_self',
                'is_active' => true,
                'is_pinned' => false,
                'sort_order' => 3,
                'active_from' => now(),
                'active_to' => null,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['title' => $banner['title']],
                $banner
            );
        }
    }
}
