<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        $indonesian = Language::where('code', 'id')->first();
        
        // Create categories first
        $puspresnas = Competition::updateOrCreate(
            ['language_id' => $indonesian->id, 'slug' => 'puspresnas'],
            [
                'name' => 'Puspresnas BPTI',
                'is_group' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
        
        $bakorma = Competition::updateOrCreate(
            ['language_id' => $indonesian->id, 'slug' => 'bakorma'],
            [
                'name' => 'BAKORMA',
                'is_group' => true,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );
        
        // Puspresnas competitions
        $pusprenasCompetitions = [
            'NUDC KDMI',
            'Pilmapres Dikti',
            'KBMI Belmawa',
            'PKM Belmawa',
            'KMLI',
            'KMHE',
            'KJI',
            'KBGI',
            'Porseni',
            'KRTI',
        ];
        
        foreach ($pusprenasCompetitions as $index => $comp) {
            Competition::updateOrCreate(
                [
                    'language_id' => $indonesian->id,
                    'slug' => Str::slug($comp)
                ],
                [
                    'parent_id' => $puspresnas->id,
                    'name' => $comp,
                    'is_group' => false,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'url' => 'https://puspresnas.id',
                    'url_target' => '_blank',
                    'content' => '<p>Kompetisi ' . $comp . ' adalah kompetisi tingkat nasional yang diselenggarakan oleh Puspresnas BPTI.</p>',
                ]
            );
        }
        
        // BAKORMA competitions
        $bakormaCompetitions = [
            'National Polytechnic English Olympics (NPEO)',
            'National Tourism Vocational Skill Competition (NTVSC)',
            'Kompetisi Kekuatan Beton',
            'Olimpiade Akuntansi Vokasi (OAV)',
            'Business Administration Competition (BAC)',
            'Kompetisi Mahasiswa Bidang Informatika Politeknik Nasional (KMIPN)',
            'National CAD CAM Competition',
            'Polytechnic Creative Festival (PC Fest)',
            'Agricultural Innovation Technology Competition (AITEC)',
        ];
        
        foreach ($bakormaCompetitions as $index => $comp) {
            Competition::updateOrCreate(
                [
                    'language_id' => $indonesian->id,
                    'slug' => Str::slug($comp)
                ],
                [
                    'parent_id' => $bakorma->id,
                    'name' => $comp,
                    'is_group' => false,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'url' => 'https://bakorma.polban.ac.id',
                    'url_target' => '_blank',
                    'content' => '<p>Kompetisi ' . $comp . ' adalah kompetisi antar politeknik se-Indonesia yang diselenggarakan oleh BAKORMA.</p>',
                ]
            );
        }
    }
}
