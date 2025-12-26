<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'code' => 'id',
                'name' => 'Bahasa Indonesia',
                'icon' => '🇮🇩',
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'icon' => '🇬🇧',
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
