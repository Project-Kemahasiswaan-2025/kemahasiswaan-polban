<?php

namespace Database\Seeders;

use App\Models\RunningTextConfig;
use Illuminate\Database\Seeder;

class RunningTextConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RunningTextConfig::create([
            'icon_text' => '🔊',
            'separator_text' => '•',
            'is_enabled' => true,
        ]);
    }
}
