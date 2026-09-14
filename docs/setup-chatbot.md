# Panduan Aktivasi & Setup Chatbot Kemahasiswaan POLBAN

Dokumen ini menjelaskan langkah-langkah aktivasi fitur chatbot pada proyek Kemahasiswaan POLBAN berdasarkan branch `feature/chatbot`, arsitektur yang digunakan, serta dependensi data yang diperlukan.

---

## 1. Ringkasan Fitur Chatbot

Fitur chatbot ini dirancang dengan pendekatan **Rule-Based Decision Tree & Dynamic Knowledge Base**:

- **Bukan Chatbot Berbasis AI/LLM Berbayar**: Tidak membutuhkan konfigurasi API Key pihak ketiga (seperti OpenAI atau Google Gemini) maupun variabel `.env` tambahan.
- **Dinamis & Terintegrasi Data Aplikasi**: Memiliki modul bawaan yang otomatis mengambil data langsung dari tabel database (Layanan, Ormawa/UKM, Dokumen Unduhan, dan Kompetisi).
- **Admin Flow Builder**: Dilengkapi visual builder berbasis Filament Livewire (`ChatbotBuilder`) untuk mengelola pohon pertanyaan, variasi pesan sambutan, aksi navigasi/CTA, dan simulator interaktif.
- **Widget Publik**: Komponen widget mengambang (*floating widget*) terpasang otomatis di seluruh halaman publik melalui layout utama.

---

## 2. Setup Database (Wajib)

Untuk mengaktifkan fitur chatbot, setup database yang perlu dilakukan adalah menjalankan **migration** dan **seeder**.

### A. Menjalankan Migration

Migration chatbot membuat 4 tabel utama:

1. `chatbot_nodes`: Menyimpan struktur pohon percakapan, pertanyaan, variasi jawaban, tipe aksi, link CTA, dan ikon.
2. `chatbot_sessions`: Menyimpan sesi pengguna tamu (menggunakan token UUID dan tracking IP/User-Agent).
3. `chatbot_logs`: Mencatat histori interaksi dan pilihan yang diklik pengguna untuk analitik.
4. `chatbot_settings`: Menyimpan pengaturan global chatbot (seperti variasi *welcome message*).

Jalankan perintah berikut:

```bash
php artisan migrate
```

File migrasi yang terlibat:

- `database/migrations/2026_08_16_200000_create_chatbot_tables.php`
- `database/migrations/2026_08_17_000000_add_icon_and_cta_fields_to_chatbot_nodes_table.php`
- `database/migrations/2026_08_17_010000_create_chatbot_settings_table.php`

---

### B. Menjalankan Seeder

Seeder chatbot (`ChatbotSeeder`) akan membuat node induk (*root nodes*) awal sebagai menu pembuka ketika pengguna membuka widget chat:

1. **Layanan Kemahasiswaan** (Aksi: `module`, Target: `services`)
2. **Organisasi & UKM (Ormawa)** (Aksi: `module`, Target: `ormawa`)
3. **Pusat Unduhan Dokumen** (Aksi: `module`, Target: `downloads`)
4. **Kontak & Jam Kerja Admin** (Aksi: `info`, Target URL: `/kontak`)

Jalankan seeder chatbot secara spesifik:

```bash
php artisan db:seed --class=ChatbotSeeder
```

*Catatan: `ChatbotSeeder` sudah didaftarkan ke dalam `DatabaseSeeder.php`, sehingga jika Anda menjalankan `php artisan db:seed` atau `php artisan migrate:fresh --seed`, seeder chatbot otomatis ikut dieksekusi.*

---

## 3. Dependensi Data Master (Modul Dinamis)

Karena menu chatbot di atas menghubungkan pengguna langsung ke modul dinamis data riil, pastikan tabel-tabel data master berikut memiliki data agar menu tidak kosong:

| Modul Dinamis                          | Model & Tabel Terkait                                                  | Seeder Terkait                                     | Keterangan                                                                 |
| :------------------------------------- | :--------------------------------------------------------------------- | :------------------------------------------------- | :------------------------------------------------------------------------- |
| **Layanan** (`services`)       | `App\Models\Service` (`services`)                                  | `ServiceSeeder`                                  | Menyajikan daftar layanan aktif, deskripsi, link form, dan dokumen syarat. |
| **Ormawa** (`ormawa`)          | `App\Models\StudentOrganization` (`student_organizations`)         | `StudentOrganizationSeeder`                      | Menyajikan daftar BEM, MPM, Himpunan Jurusan, dan UKM beserta detailnya.   |
| **Unduhan** (`downloads`)      | `App\Models\Download` (`downloads`)                                | `DownloadSeeder`                                 | Menampilkan kategori unduhan dan link berkas dokumen resmi.                |
| **Kompetisi** (`competitions`) | `App\Models\Competition` (`competitions`, `competition_threads`) | `CompetitionSeeder`, `CompetitionThreadSeeder` | Menampilkan daftar lomba, timeline, dan link pendaftaran lomba.            |

Jika sedang melakukan setup di environment lokal baru dari awal, disarankan menjalankan seeder lengkap:

```bash
php artisan db:seed
```

---

## 4. Komponen & Aset yang Terlibat

### A. Frontend (Halaman Publik)

- **Komponen Blade Widget**: `resources/views/components/chatbot-widget.blade.php`
- **Layout Utama**: `resources/views/components/layout/app.blade.php` (sudah memuat tag `<x-chatbot-widget />` sebelum penutup `</body>`).
- **Aset CSS/JS**: Widget dibuat menggunakan CSS mandiri dan Vanilla JavaScript (AJAX `fetch`). Tidak memerlukan kompilasi `npm run build` khusus untuk widget chatbot.
- **Ikon**: Membutuhkan Bootstrap Icons (`bi bi-*`), yang sudah dimuat melalui CDN di file layout utama.

### B. Route / Endpoint API

Endpoint berikut sudah terdaftar di `routes/web.php` dalam grup prefix `/kemahasiswaan`:

- `GET /kemahasiswaan/chatbot/init` (`chatbot.init`) -> Inisialisasi sesi, pesan sambutan, dan opsi root awal.
- `POST /kemahasiswaan/chatbot/select` (`chatbot.select`) -> Navigasi pemilihan node/modul, dilengkapi `middleware('throttle:30,1')` untuk proteksi *rate-limiting*.

### C. Backend & Business Logic

- `app/Http/Controllers/ChatbotController.php` -> Controller API widget chatbot.
- `app/Services/Chatbot/ChatbotModuleManager.php` -> Registry modul dynamic knowledge.
- `app/Services/Chatbot/Modules/*` -> Handler masing-masing modul dinamis (`ServiceKnowledgeModule`, `OrmawaKnowledgeModule`, `DownloadKnowledgeModule`, `CompetitionKnowledgeModule`).
- `app/Models/ChatbotNode.php` -> Model node percakapan & resolver smart URL.
- `app/Models/ChatbotSetting.php` -> Model konfigurasi welcome message & variasi respons.
- `app/Models/ChatbotSession.php` & `app/Models/ChatbotLog.php` -> Pencatatan sesi dan aktivitas user.

### D. Panel Admin (Filament)

- **Halaman**: `App\Filament\Pages\ChatbotBuilder`
- **Navigasi**: Menu **"Konfigurasi Chatbot"** di sidebar Filament.
- **Fitur Admin**:
  - Menambah sub-topik / pertanyaan baru di bawah node manapun.
  - Memilih aksi node: Navigasi Child Node, Dynamic Module, Jump Node, atau Info (Direct Link/CTA).
  - Mengatur variasi respons bot (menggunakan pemisah `---`).
  - Mengatur teks, warna, dan posisi tombol Call-To-Action (CTA).
  - Pemilihan ikon Bootstrap secara visual.
  - Simulator interaktif di sebelah kanan layar builder untuk menguji alur sebelum disimpan.

---

## 5. Checklist Aktivasi Cepat (Quick Start)

Untuk mengaktifkan chatbot di environment baru atau server staging/production:

1. **Jalankan migrasi database**:
   ```bash
   php artisan migrate
   ```
2. **Jalankan seeder chatbot**:
   ```bash
   php artisan db:seed --class=ChatbotSeeder
   ```
3. **Pastikan data master (Layanan, Ormawa, Unduhan) sudah terisi** (baik via seeder atau via Filament Admin).
4. **Buka halaman depan website** (`/kemahasiswaan`):
   - Cek tombol mengambang bulat (ikon chat) di sudut kanan bawah.
   - Klik tombol untuk membuka widget dan memastikan opsi menu awal muncul dengan responsivitas baik.
5. **(Opsional) Kustomisasi via Admin Panel**:
   - Login ke Filament Admin (`/admin`).
   - Masuk ke menu **Konfigurasi Chatbot**.
   - Sesuaikan pesan pembuka (*Welcome Message*) dan struktur topik sesuai kebutuhan.
