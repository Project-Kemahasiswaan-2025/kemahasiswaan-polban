Ok itu aman sih, paling kamu harus tau dulu nih. Jadi yang lagi aku develop itu CMS web kemahasiswaan, dan yang mana yang sekarang itu konten kontenya sangat berantakan. Mungkin kamu harus tau menu saat ini dan menu yang direncanakan:
Sip, aku rapikan dulu dulu menu yang ada sekarang berdasarkan HTML itu. Ini bentuknya tree biar kebaca.

## Menu website saat ini

### Profil

-   Sekapur Sirih
-   Struktur Organisasi
-   Karakter Mahasiswa
-   Misi Kemahasiswaan
-   Peraturan Kedisiplinan Mahasiswa
-   Kode Etik Mahasiswa
-   Pedoman Kegiatan Mahasiswa

### Layanan

-   Perkembangan Karir dan Kewirausahaan

    -   Tracer Study link ke penelusuranalumni.polban.ac.id
    -   Pusat Karir link ke karir.polban.ac.id
    -   Kealumnian link belum jelas

-   Badan Konseling dan Pendampingan

    -   Form Review Planning

-   Form Klaim Asuransi
-   Pembinaan Kewirausahaan

    -   Rekap Pemenang KBMP
    -   Rekap Chart Pemenang
    -   Berita Acara Seleksi KBMI Polban
    -   KKN TKWU Polban 2023

-   Beasiswa

    -   Rekap Beasiswa
    -   JFLS link eksternal
    -   Djarum link eksternal
    -   Paragon link eksternal
    -   Adaro link eksternal
    -   KIPK link eksternal

-   Pengecekan Sapras link ke sipr.polban.ac.id

### Ormawa

-   MPM
-   BEM
-   Himpunan Jurusan Mahasiswa

    -   HMJ Teknik Refrigasi dan Tata Udara
    -   HMJ Teknik Konversi Energi
    -   HMJ Teknik Elektro
    -   HMJ Bahasa Inggris
    -   HMJ Akuntansi
    -   HMJ Administrasi Niaga
    -   HMJ Teknik Komputer dan Informatika
    -   HMJ Teknik Sipil
    -   HMJ Teknik Mesin
    -   HMJ Teknik Kimia

### UKM

-   UKM Otomotif
-   UKM Eltras Radio
-   UKM Assalam
-   UKM PMK
-   UKM KMK
-   UKM Paduan Suara Mahasiswa
-   UKM Kabayan
-   UKM Musik Kingdom
-   UKM Unit Kesenian Budaya Minang
-   UKM Unit Budaya dan Seni Sumatera Utara
-   UKM Unit Sepak Bola dan Futsal
-   UKM Basket
-   Robotika
-   UKM Kewirausahaan
-   UKM PPRPG SAGA
-   UKM Volley
-   UKM Tenis Meja
-   UKM Catur
-   UKM Beladiri
-   UKM KSR PMI
-   UKM Fellas
-   UKM Pramuka
-   UKM Flag Football
-   UKM Bulu Tangkis

### SK DIR

-   SK DIR PEMBINA
-   SK DIR ORMAWA

### Sarana Prasarana link placeholder

### KAK link placeholder

### Panduan

-   Panduan Sertifikat linknya ke panduan publikasi ormawa tapi labelnya sertifikat
-   Pedoman Kegiatan Mahasiswa duplikat dari menu Profil

### Kompetisi

-   Puspresnas BPTI

    -   NUDC KDMI link eksternal
    -   Pilmapres Dikti link eksternal
    -   KBMI Belmawa link placeholder
    -   PKM Belmawa link eksternal
    -   KMLI link placeholder
    -   KMHE link placeholder
    -   KJI dan KBGI link placeholder
    -   Porseni link placeholder
    -   KRTI link placeholder

-   Mahasiswa Berprestasi

    -   Rekap Juara link placeholder

        -   Mapres 2022
        -   Mapres 2023

-   BAKORMA

    -   National Polythecic English Olympics NPEO link placeholder
    -   National Tourism Vocational Skill Competition NTVSC link placeholder
    -   Kompetisi Kekuatan Beton link placeholder
    -   Olimpiade Akuntansi Vokasi OAV link placeholder
    -   Business Administration Competition BAC muncul dua kali
    -   Kompetisi Mahasiswa Bidang Informatika Politeknik Nasional KMIPN link placeholder
    -   National CAD CAM Competition link placeholder
    -   Polytechnic Creative Festival PC Fest link placeholder
    -   Agricultural Innovation Technology Competition AITEC link placeholder

-   POLBAN link placeholder
-   Form Pengajuan Prestasi link ke form pengajuan penghargaan

### Unduhan

-   Sertifikat Polban

    -   SFT Kegiatan Kemahasiswaan
    -   SFT Kegiatan Ormawa
    -   SK Mahasiswa Berprestasi
    -   Sertifikat PMW KBMP
    -   Sertifikat PKM

-   Mahasiswa Berprestasi link ke halaman mahasiswa berprestasi
-   SK DIR

    -   SK DIR ORMAWA
    -   SK DIR PEMBINA
    -   SK DIR PMW

-   Tim Publikasi Kemahasiswaan
-   Kotak Saran email

## Masalah utama struktur sekarang

1. Terlalu banyak item level atas, dan banyak submenu bertingkat sampai 3 level.
2. Ada konten dobel, misalnya Pedoman Kegiatan Mahasiswa muncul di Profil dan Panduan.
3. Campur aduk antara halaman informasi, form layanan, dan tautan eksternal.
4. Banyak link placeholder yang bikin user bingung saat klik.
5. SK DIR muncul di dua tempat, dan Unduhan juga jadi campur aduk.

## Rekomendasi struktur menu baru yang lebih simple

Targetnya menu atas maksimal 6 sampai 7 item. Sisanya masuk halaman kategori.

### Struktur menu versi baru

1. Beranda
2. Profil

    - Sekapur Sirih
    - Struktur Organisasi
    - Regulasi dan Pedoman

        - Karakter Mahasiswa
        - Misi Kemahasiswaan
        - Kode Etik
        - Peraturan Kedisiplinan
        - Pedoman Kegiatan Mahasiswa

3. Layanan

    - Konseling dan Pendampingan

        - Form Review Planning

    - Asuransi

        - Form Klaim Asuransi

    - Karir dan Alumni

        - Pusat Karir eksternal
        - Tracer Study eksternal
        - Kealumnian internal kalau ada, kalau belum mending hilangkan dulu

    - Sarana Prasarana

        - Pengecekan Sapras eksternal

4. Beasiswa

    - Info dan Timeline Beasiswa ini nanti dari CMS
    - Arsip dan Rekap Beasiswa
    - Pengumuman Lolos nanti poster hasil lolos
    - Tautan Beasiswa Eksternal JFLS, Djarum, Paragon, Adaro, KIPK

5. Organisasi Mahasiswa

    - Ormawa

        - MPM
        - BEM
        - Himpunan Jurusan

    - UKM

6. Prestasi dan Kompetisi

    - Kompetisi Nasional

        - Puspresnas BPTI
        - BAKORMA

    - Mahasiswa Berprestasi

        - Mapres per tahun
        - Rekap Juara

    - Form Pengajuan Prestasi

7. Dokumen

    - SK DIR

        - Pembina
        - Ormawa
        - PMW

    - Sertifikat

        - SFT Kemahasiswaan
        - SFT Ormawa
        - Sertifikat PKM
        - Sertifikat PMW KBMP
        - SK Mahasiswa Berprestasi

    - Tim Publikasi Kemahasiswaan

8. Kontak

    - Kotak Saran
    - Email dan info kontak unit

Kalau 8 item terasa kebanyakan, Dokumen bisa digabung ke Profil atau Kontak, tapi biasanya kampus enak punya Dokumen sendiri.

## Catatan biar UX nya jauh lebih enak

-   Semua link eksternal kasih ikon dan label eksternal supaya user sadar pindah situs
-   Hilangkan item placeholder dari menu, taruh di draft CMS dulu sampai siap
-   Batasi dropdown maksimal 2 level, jangan sampai 3 level
-   Untuk Ormawa dan UKM, buat halaman list dengan search, lalu menu cuma masuk ke list, tidak perlu memuat semua UKM di dropdown
