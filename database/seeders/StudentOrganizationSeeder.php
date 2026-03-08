<?php

namespace Database\Seeders;

use App\Models\StudentOrganization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentOrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // Create root categories
        $roots = [
            ['name' => 'MPM', 'slug' => 'mpm', 'sort_order' => 10, 'is_group' => false],
            ['name' => 'BEM', 'slug' => 'bem', 'sort_order' => 20, 'is_group' => false],
            ['name' => 'HMJ', 'slug' => 'hmj', 'sort_order' => 30, 'is_group' => true],
            ['name' => 'UKM', 'slug' => 'ukm', 'sort_order' => 40, 'is_group' => true],
        ];

        foreach ($roots as $r) {
            StudentOrganization::updateOrCreate(
                ['slug' => $r['slug']],
                [
                    'parent_id' => null,
                    'name' => $r['name'],
                    'sort_order' => $r['sort_order'],
                    'is_active' => true,
                    'is_group' => $r['is_group'],
                ]
            );
        }

        // Get parent categories
        $hmjParent = StudentOrganization::where('slug', 'hmj')->first();
        $ukmParent = StudentOrganization::where('slug', 'ukm')->first();

        // HMJ organizations
        $hmjList = [
            'HMJ Teknik Refrigasi dan Tata Udara',
            'HMJ Teknik Konversi Energi',
            'HMJ Teknik Elektro',
            'HMJ Bahasa Inggris',
            'HMJ Akuntansi',
            'HMJ Administrasi Niaga',
            'HMJ Teknik Komputer dan Informatika',
            'HMJ Teknik Sipil',
            'HMJ Teknik Mesin',
            'HMJ Teknik Kimia',
        ];

        foreach ($hmjList as $index => $hmj) {
            StudentOrganization::updateOrCreate(
                [
                    'slug' => Str::slug($hmj)
                ],
                [
                    'parent_id' => $hmjParent->id,
                    'name' => $hmj,
                    'is_group' => false,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'excerpt' => 'Himpunan Mahasiswa Jurusan yang menaungi mahasiswa ' . str_replace('HMJ ', '', $hmj),
                ]
            );
        }

        // UKM organizations
        $ukmList = [
            'UKM Otomotif',
            'UKM Eltras Radio',
            'UKM Assalam',
            'UKM PMK',
            'UKM KMK',
            'UKM Paduan Suara Mahasiswa',
            'UKM Kabayan',
            'UKM Musik Kingdom',
            'UKM Unit Kesenian Budaya Minang',
            'UKM Unit Budaya dan Seni Sumatera Utara',
            'UKM Unit Sepak Bola dan Futsal',
            'UKM Basket',
            'UKM Robotika',
            'UKM Kewirausahaan',
            'UKM PPRPG SAGA',
            'UKM Volley',
            'UKM Tenis Meja',
            'UKM Catur',
            'UKM Beladiri',
            'UKM KSR PMI',
            'UKM Fellas',
            'UKM Pramuka',
            'UKM Flag Football',
            'UKM Bulu Tangkis',
        ];

        foreach ($ukmList as $index => $ukm) {
            StudentOrganization::updateOrCreate(
                [
                    'slug' => Str::slug($ukm)
                ],
                [
                    'parent_id' => $ukmParent->id,
                    'name' => $ukm,
                    'is_group' => false,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'excerpt' => 'Unit Kegiatan Mahasiswa yang fokus pada pengembangan minat dan bakat mahasiswa',
                ]
            );
        }
    }
}
