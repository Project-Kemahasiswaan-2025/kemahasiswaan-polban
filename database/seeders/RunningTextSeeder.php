<?php

namespace Database\Seeders;

use App\Models\RunningText;
use Illuminate\Database\Seeder;

class RunningTextSeeder extends Seeder
{
    public function run(): void
    {
        RunningText::create([
            'content' => 'Selamat datang di Portal Kemahasiswaan Politeknik Negeri Bandung',
            'duration_seconds' => 8,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        RunningText::create([
            'content' => 'Daftarkan diri Anda untuk mengikuti berbagai kompetisi dan kegiatan mahasiswa',
            'duration_seconds' => 8,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        RunningText::create([
            'content' => 'Bergabunglah dengan organisasi mahasiswa dan kembangkan potensi Anda',
            'duration_seconds' => 8,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        RunningText::create([
            'content' => 'Welcome to Bandung State Polytechnic Student Portal',
            'duration_seconds' => 8,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        RunningText::create([
            'content' => 'Register yourself to participate in various student competitions and activities',
            'duration_seconds' => 8,
            'is_active' => true,
            'sort_order' => 4,
        ]);

        RunningText::create([
            'content' => 'Join student organizations and develop your potential',
            'duration_seconds' => 8,
            'is_active' => true,
            'sort_order' => 5,
        ]);
    }
}
