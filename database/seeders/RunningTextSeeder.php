<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\RunningText;
use Illuminate\Database\Seeder;

class RunningTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indonesian = Language::where('code', 'id')->first();
        $english = Language::where('code', 'en')->first();

        if ($indonesian) {
            RunningText::create([
                'language_id' => $indonesian->id,
                'content' => 'Selamat datang di Portal Kemahasiswaan Politeknik Negeri Bandung',
                'duration_seconds' => 8,
                'is_active' => true,
                'sort_order' => 0,
            ]);

            RunningText::create([
                'language_id' => $indonesian->id,
                'content' => 'Daftarkan diri Anda untuk mengikuti berbagai kompetisi dan kegiatan mahasiswa',
                'duration_seconds' => 8,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            RunningText::create([
                'language_id' => $indonesian->id,
                'content' => 'Bergabunglah dengan organisasi mahasiswa dan kembangkan potensi Anda',
                'duration_seconds' => 8,
                'is_active' => true,
                'sort_order' => 2,
            ]);
        }

        if ($english) {
            RunningText::create([
                'language_id' => $english->id,
                'content' => 'Welcome to Bandung State Polytechnic Student Portal',
                'duration_seconds' => 8,
                'is_active' => true,
                'sort_order' => 0,
            ]);

            RunningText::create([
                'language_id' => $english->id,
                'content' => 'Register yourself to participate in various student competitions and activities',
                'duration_seconds' => 8,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            RunningText::create([
                'language_id' => $english->id,
                'content' => 'Join student organizations and develop your potential',
                'duration_seconds' => 8,
                'is_active' => true,
                'sort_order' => 2,
            ]);
        }
    }
}
