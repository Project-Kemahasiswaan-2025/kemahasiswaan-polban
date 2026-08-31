# Plan Lanjutan: Integrasi Chatbot dengan Sistem Tiket Bantuan & Anti-Spam (Roadmap Masa Depan)

Dokumen ini mencatat ide dan rancangan pengembangan tahap selanjutnya untuk memfasilitasi integrasi antara **Configured Chatbot Widget** dengan sistem **Contact Tickets (Tiket Bantuan)** yang sudah ada pada aplikasi.

---

## 1. Integrasi Form Open Question (Buka Tiket via Chatbot)

### Skema Alur
1. **Trigger Opsi Form**:
   - Pada node chatbot tertentu (misal: "Pertanyaan Saya Tidak Terdaftar" atau "Butuh Bantuan Petugas"), jenis node dapat diset sebagai `type = 'ticket_form'`.
2. **Form Interaktif di Widget Chat**:
   - Chatbot akan menampilkan form singkat di dalam jendela chat:
     - Nama Pengirim
     - Email / WA
     - Subjek & Isi Pesan (Open Question)
3. **Penyimpanan Tiket**:
   - Data yang diisi pengguna akan langsung membuat record baru di tabel `contact_tickets` (status default: `pending` / `open`).
   - Jendela chat menyimpan referensi `ticket_code` sehingga pengguna dapat mengecek status tiket tersebut langsung di jendela chat.

---

## 2. Pengecekan & Tracking Status Tiket via Chat

1. Pengguna dapat memilih menu "Cek Status Tiket Saya" atau memasukkan `ticket_code`.
2. Bot akan menampilkan status terkini (`Pending`, `In Progress`, `Resolved`, `Closed`) beserta catatan admin jika ada.

---

## 3. Sistem Pembatasan & Anti-Spam (Limit Max 3 Pending Tickets)

Untuk mencegah *spamming* submit tiket berulang kali dari satu pengguna/perangkat:

### A. Aturan Pembatasan
- Satu pengguna/sesi **maksimal hanya boleh memiliki 3 tiket dengan status `pending` / `in_progress`** (belum ditindaklanjuti).
- Jika pengguna mencoba membuat tiket ke-4 padahal 3 tiket sebelumnya belum selesai, bot akan menolak submit dan menampilkan pesan:
  > *"Maaf, Anda masih memiliki 3 tiket bantuan yang sedang dalam proses penanganan. Mohon tunggu hingga tiket sebelumnya ditindaklanjuti oleh admin."*

### B. Mekanisme Tracking Pengguna (Anti Bypass Clear Cache)
Karena `session` atau `local storage` browser dapat dengan mudah di-*clear* oleh pengguna:
1. **IP Address & User-Agent Hashing**:
   - Menyimpan `ip_address` dan hash `user_agent` pada setiap tiket yang dibuat.
   - Pengecekan limit 3 tiket dilakukan berdasarkan `ip_address` + `user_agent` di tabel `contact_tickets` untuk tiket berkategori `pending`/`in_progress`.
2. **Browser Fingerprinting & Client Cookie (Opsional Tambahan)**:
   - Menyimpan cookie persisten dengan identitas unik perangkat.
   - Mengombinasikan pengecekan IP + Fingerprint cookie agar tidak mudah dimanipulasi dengan sekadar menghapus *session storage*.

---

> [!NOTE]
> Catatan ini disimpan di workspace untuk diimplementasikan pada fase pengembangan berikutnya setelah objektif utama (Chatbot Flow Configurator & Chatbot Widget) selesai dibangun.
