<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Download;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DownloadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Formulir Administrasi',
                'downloads' => [
                    ['name' => 'Formulir Pengajuan Beasiswa', 'file_path' => 'downloads/seeds/form-beasiswa.pdf'],
                    ['name' => 'Formulir Cuti Akademik', 'file_path' => 'downloads/seeds/form-cuti.pdf'],
                    ['name' => 'Formulir Penggantian KTM', 'file_path' => 'downloads/seeds/form-ktm.pdf'],
                ]
            ],
            [
                'name' => 'Panduan & Pedoman',
                'downloads' => [
                    ['name' => 'Buku Panduan Akademik 2025', 'file_path' => 'downloads/seeds/panduan-akademik.pdf'],
                    ['name' => 'Pedoman Etika Mahasiswa', 'file_path' => 'downloads/seeds/pedoman-etika.pdf'],
                    ['name' => 'Panduan Penggunaan Portal Student', 'file_path' => 'downloads/seeds/panduan-portal.pdf'],
                ]
            ],
            [
                'name' => 'Sertifikat & Piagam',
                'downloads' => [
                    ['name' => 'Template Sertifikat Kegiatan', 'file_path' => 'downloads/seeds/template-sertifikat.docx'],
                    ['name' => 'Piagam Penghargaan Mahasiswa Berprestasi', 'file_path' => 'downloads/seeds/piagam-mawapres.pdf'],
                ]
            ],
        ];

        foreach ($data as $index => $categoryData) {
            $category = Category::create([
                'type' => 'download',
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);

            foreach ($categoryData['downloads'] as $downloadIndex => $downloadData) {
                Download::create([
                    'category_id' => $category->id,
                    'name' => $downloadData['name'],
                    'file_path' => $downloadData['file_path'],
                    'file_type' => 'pdf',
                    'file_size' => rand(500, 5000) * 1024, // Random size between 500KB and 5MB
                    'sort_order' => $downloadIndex + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}
