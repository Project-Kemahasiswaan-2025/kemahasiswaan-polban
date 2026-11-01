<?php

namespace Database\Seeders;

use App\Models\StudentOrganization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class HimpunanSeeder extends Seeder
{
    public function run(): void
    {
        $logo = 'org/logos/himakom-polban.png';

        if (! Storage::disk('public')->put($logo, file_get_contents(database_path('seeders/assets/himakom-polban.png')))) {
            throw new RuntimeException('Gagal menyimpan logo HIMAKOM POLBAN.');
        }

        $hmj = StudentOrganization::firstOrCreate(
            ['slug' => 'hmj'],
            [
                'name' => 'HMJ',
                'parent_id' => null,
                'is_group' => true,
                'is_active' => true,
                'sort_order' => 30,
            ]
        );

        StudentOrganization::updateOrCreate(
            ['slug' => 'hmj-teknik-komputer-dan-informatika'],
            [
                'parent_id' => $hmj->id,
                'name' => 'HMJ Teknik Komputer dan Informatika',
                'is_group' => false,
                'is_active' => true,
                'sort_order' => 7,
                'logo' => $logo,
                'excerpt' => 'Himpunan Mahasiswa Komputer Politeknik Negeri Bandung (HIMAKOM POLBAN), wadah bagi mahasiswa aktif Jurusan Teknik Komputer dan Informatika.',
                'content' => <<<'HTML'
<h2>Profil Himpunan Mahasiswa Komputer (HIMAKOM POLBAN)</h2>
<p>Himpunan Mahasiswa Komputer Politeknik Negeri Bandung (HIMAKOM POLBAN) merupakan organisasi kemahasiswaan tingkat jurusan yang menjadi wadah bagi seluruh mahasiswa aktif Jurusan Teknik Komputer dan Informatika (JTK) Politeknik Negeri Bandung. HIMAKOM hadir sebagai ruang bagi mahasiswa untuk berhimpun, mengembangkan potensi diri, menyalurkan aspirasi, serta membangun rasa kekeluargaan dan solidaritas antarmahasiswa.</p>
<p>Berlandaskan Pancasila, Undang-Undang Dasar 1945, dan asas Tridarma Perguruan Tinggi, HIMAKOM menjunjung tinggi nilai kekeluargaan, demokrasi, dan independensi dalam menjalankan kehidupan organisasinya. HIMAKOM berperan dalam mendukung peningkatan kualitas pendidikan, mengembangkan kemampuan akademik maupun nonakademik, memperjuangkan kepentingan dan kesejahteraan mahasiswa, serta mendorong lahirnya karya dan inovasi yang bermanfaat bagi lingkungan kampus maupun masyarakat.</p>
<p>Dalam menjalankan perannya, HIMAKOM tidak hanya menjadi sarana pengembangan kemampuan di bidang komputer dan informatika, tetapi juga menjadi tempat bagi mahasiswa untuk mengasah kepemimpinan, kemampuan berorganisasi, tanggung jawab, serta kepedulian sosial. Melalui berbagai kegiatan dan kolaborasi, HIMAKOM berupaya membentuk mahasiswa yang kompeten secara keilmuan, berkarakter, dan mampu memberikan kontribusi nyata bagi almamater, masyarakat, serta bangsa.</p>
<h2>Latar Belakang dan Sejarah HIMAKOM POLBAN</h2>
<p>Himpunan Mahasiswa Komputer Politeknik Negeri Bandung (HIMAKOM POLBAN) didirikan pada 8 Agustus 1987 dan telah menjadi bagian dari perjalanan panjang kehidupan kemahasiswaan di Jurusan Teknik Komputer dan Informatika Politeknik Negeri Bandung. Keberadaan HIMAKOM dilandasi oleh pentingnya sebuah wadah yang mampu mempersatukan mahasiswa, mempererat hubungan kekeluargaan, serta memberikan ruang bagi pengembangan potensi, aspirasi, dan kontribusi mahasiswa di lingkungan jurusan.</p>
<p>Seiring dengan perkembangan Jurusan Teknik Komputer dan Informatika serta kemajuan ilmu pengetahuan dan teknologi, HIMAKOM terus menjalankan perannya sebagai organisasi yang mendukung pengembangan mahasiswa, baik dalam bidang akademik, keorganisasian, maupun sosial kemasyarakatan. Perjalanan tersebut diwujudkan melalui berbagai kegiatan pengembangan keilmuan, pembinaan dan regenerasi anggota, penyaluran minat dan bakat, serta kerja sama dengan berbagai pihak, termasuk alumni dan organisasi kemahasiswaan lainnya.</p>
<p>Dalam perjalanan organisasinya, HIMAKOM terus menjaga nilai-nilai kekeluargaan, demokrasi, dan independensi sebagai fondasi yang diwariskan dari satu generasi kepengurusan kepada generasi berikutnya. Melalui proses regenerasi dan keberlanjutan organisasi, HIMAKOM senantiasa berupaya untuk tetap relevan terhadap perkembangan zaman serta kebutuhan mahasiswa. Hingga kini, HIMAKOM akan terus menjadi ruang bagi mahasiswa JTK POLBAN untuk bertumbuh, berkarya, dan berkontribusi bersama, sekaligus melanjutkan semangat pengabdian yang telah dibangun sejak tahun 1987.</p>
HTML,
            ]
        );
    }
}
