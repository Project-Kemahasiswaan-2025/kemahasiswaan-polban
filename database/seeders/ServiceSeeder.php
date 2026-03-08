<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Beasiswa',
                'icon' => 'bi-mortarboard',
                'excerpt' => 'Informasi dan pendaftaran berbagai program beasiswa mahasiswa.',
                'content' => '<p>Bagian Kemahasiswaan POLBAN menyediakan berbagai informasi beasiswa baik yang bersumber dari pemerintah maupun swasta.</p><ul><li>Beasiswa KIP Kuliah</li><li>Beasiswa PPA</li><li>Beasiswa CSR</li></ul>',
                'sort_order' => 1,
            ],
            [
                'name' => 'Kesejahteraan Mahasiswa',
                'icon' => 'bi-heart-pulse',
                'excerpt' => 'Layanan asuransi kesehatan dan bantuan kesejahteraan mahasiswa.',
                'content' => '<p>Pelayanan kesejahteraan mahasiswa mencakup bantuan biaya pendidikan, klaim asuransi kecelakaan, dan administrasi SKTM.</p>',
                'sort_order' => 2,
                'children' => [
                    [
                        'name' => 'Form Klaim Asuransi',
                        'cta_label' => 'Unduh Formulir',
                        'cta_url' => 'https://kemahasiswaan.polban.ac.id/unduhan/form-asuransi',
                    ],
                ]
            ],
            [
                'name' => 'Perkembangan Karir & Kewirausahaan',
                'icon' => 'bi-briefcase',
                'excerpt' => 'Pendampingan karir lulusan, tracer study, dan pembinaan kewirausahaan.',
                'content' => '<p>Membantu mahasiswa dan alumni dalam mempersiapkan diri memasuki dunia kerja serta mengembangkan jiwa kewirausahaan.</p>',
                'sort_order' => 3,
                'children' => [
                    [
                        'name' => 'Tracer Study Polban',
                        'excerpt' => 'Survei pelacakan alumni untuk evaluasi hasil pendidikan.',
                        'cta_label' => 'Buka Tracer Study',
                        'cta_url' => 'https://tracer.polban.ac.id/',
                    ],
                    [
                        'name' => 'Career Center (Kealumnian)',
                        'excerpt' => 'Pusat informasi lowongan kerja dan pengembangan karir.',
                        'cta_label' => 'Kunjungi Career Center',
                        'cta_url' => 'https://career.polban.ac.id/',
                    ],
                ]
            ],
            [
                'name' => 'Bimbingan Konseling',
                'icon' => 'bi-chat-dots',
                'excerpt' => 'Layanan konsultasi psikologis dan akademik mahasiswa.',
                'content' => '<p>Kami menyediakan layanan konseling bagi mahasiswa yang membutuhkan bantuan terkait masalah akademik maupun pribadi.</p>',
                'sort_order' => 4,
            ],
            [
                'name' => 'Sarana Prasarana',
                'icon' => 'bi-building',
                'excerpt' => 'Peminjaman fasilitas gedung dan peralatan untuk kegiatan mahasiswa.',
                'content' => '<p>Prosedur peminjaman fasilitas ruang rapat, aula, dan perlengkapan lainnya untuk mendukung kegiatan organisasi mahasiswa.</p>',
                'sort_order' => 5,
            ],
        ];

        foreach ($services as $serviceData) {
            $links = $serviceData['children'] ?? [];
            unset($serviceData['children']);

            $serviceData['slug'] = Str::slug($serviceData['name']);
            $service = Service::create($serviceData);

            foreach ($links as $index => $linkData) {
                $service->links()->create([
                    'name' => $linkData['name'],
                    'url' => $linkData['cta_url'] ?? null,
                    'cta_label' => $linkData['cta_label'] ?? null,
                    'description' => $linkData['excerpt'] ?? null,
                    'sort_order' => $index,
                ]);
            }
        }
    }
}
