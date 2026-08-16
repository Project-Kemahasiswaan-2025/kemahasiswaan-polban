<?php

namespace Database\Seeders;

use App\Models\ChatbotNode;
use Illuminate\Database\Seeder;

class ChatbotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (ChatbotNode::count() > 0) {
            return;
        }

        // Root 1: Informasi Layanan Kemahasiswaan (Dynamic Module)
        $servicesRoot = ChatbotNode::create([
            'parent_id' => null,
            'title' => 'Layanan Kemahasiswaan',
            'icon' => 'bi-mortarboard',
            'bot_response' => "Berikut adalah daftar layanan kemahasiswaan POLBAN yang dapat Anda akses:\n--- \nHalo! Ini dia daftar layanan kemahasiswaan aktif di POLBAN:",
            'action_type' => 'module',
            'module_key' => 'services',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Root 2: Organisasi & UKM (Dynamic Module)
        $ormawaRoot = ChatbotNode::create([
            'parent_id' => null,
            'title' => 'Organisasi & UKM (Ormawa)',
            'icon' => 'bi-people',
            'bot_response' => "Berikut adalah informasi seputar Organisasi Mahasiswa dan UKM di POLBAN:\n--- \nHai! Berikut daftar Organisasi dan UKM Mahasiswa yang terdaftar di POLBAN:",
            'action_type' => 'module',
            'module_key' => 'ormawa',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Root 3: Pusat Unduhan Dokumen (Dynamic Module)
        $downloadRoot = ChatbotNode::create([
            'parent_id' => null,
            'title' => 'Pusat Unduhan Dokumen',
            'icon' => 'bi-file-earmark-text',
            'bot_response' => "Berikut beberapa formulir dan pedoman yang tersedia di Pusat Unduhan POLBAN:\n--- \nSilakan cek dokumen dan formulir resmi POLBAN di bawah ini:",
            'action_type' => 'module',
            'module_key' => 'downloads',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Root 4: Informasi Umum & Kontak Admin
        $contactRoot = ChatbotNode::create([
            'parent_id' => null,
            'title' => 'Kontak & Jam Kerja Admin',
            'icon' => 'bi-telephone',
            'bot_response' => "Layanan Kemahasiswaan POLBAN berlokasi di Gedung Direktorat POLBAN.\n\n**Jam Operasional:**\nSenin - Jumat: 08.00 - 16.00 WIB\n\nUntuk pertanyaan lebih lanjut, Anda dapat menghubungi kami melalui halaman Kontak.",
            'action_type' => 'info',
            'action_url' => '/kontak',
            'action_label' => 'Buka Halaman Kontak',
            'action_icon' => 'bi-envelope',
            'action_icon_position' => 'left',
            'sort_order' => 4,
            'is_active' => true,
        ]);
    }
}
