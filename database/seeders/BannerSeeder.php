<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Selamat Datang di Kemahasiswaan Polban',
                'image_path' => 'https://placehold.co/1200x500/001f3f/ffffff?text=Kemahasiswaan+Polban',
                'url' => null,
                'url_target' => '_self',
                'is_active' => true,
                'is_pinned' => true,
                'sort_order' => 1,
                'active_from' => now(),
                'active_to' => null,
            ],
            [
                'title' => 'Bergabung dengan Organisasi Mahasiswa',
                'image_path' => 'https://placehold.co/1200x500/ff6b35/ffffff?text=Organisasi+Mahasiswa',
                'url' => route('ormawa.index'),
                'url_target' => '_self',
                'is_active' => true,
                'is_pinned' => false,
                'sort_order' => 2,
                'active_from' => now(),
                'active_to' => null,
            ],
            [
                'title' => 'Ikuti Kompetisi Mahasiswa',
                'image_path' => 'https://placehold.co/1200x500/0d47a1/ffffff?text=Kompetisi+Mahasiswa',
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
