<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@polban.ac.id',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            LanguageSeeder::class,
            ServiceSeeder::class,
            BannerSeeder::class,
            VideoSeeder::class,
            StudentOrganizationSeeder::class,
            CompetitionSeeder::class,
            RunningTextConfigSeeder::class,
            RunningTextSeeder::class,
            DownloadSeeder::class,
            CompetitionThreadSeeder::class,
        ]);
    }
}
