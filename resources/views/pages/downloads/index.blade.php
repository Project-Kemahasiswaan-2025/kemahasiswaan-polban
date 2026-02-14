<x-layout.app title="Pusat Unduhan & Dokumen">
    <!-- Page Header -->
    <section class="page-header bg-navy text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">Unduhan</h1>
            <p class="lead opacity-75">Temukan berbagai dokumen, formulir, dan sertifikat yang Anda butuhkan di sini.</p>
        </div>
    </section>

    <!-- Downloads Sections -->
    <section class="py-5 bg-light min-vh-100">
        <div class="container">
            @forelse($categories as $category)
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary text-white p-2 rounded me-3">
                        <i class="bi bi-folder2-open fs-4"></i>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $category->name }}</h2>
                    <div class="ms-auto">
                        <span class="badge bg-secondary rounded-pill">{{ $category->downloads->count() }} Dokumen</span>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach($category->downloads as $download)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 transition-hover">
                            <div class="card-body p-4 d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-light-primary p-3 rounded">
                                        <i class="bi bi-file-earmark-arrow-down text-primary fs-3"></i>
                                    </div>
                                </div>
                                <div class="ms-4 flex-grow-1">
                                    <h5 class="fw-bold mb-1 line-clamp-2">{{ $download->name }}</h5>
                                    <p class="text-muted small mb-3">
                                        <i class="bi bi-clock me-1"></i> {{ $download->updated_at->format('d M Y') }}
                                        @if($download->file_size)
                                        <span class="mx-2">|</span>
                                        <i class="bi bi-hdd me-1"></i> {{ number_format($download->file_size / 1024, 1) }} KB
                                        @endif
                                    </p>
                                    <a href="{{ asset('storage/' . $download->file_path) }}"
                                        class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                        target="_blank">
                                        <i class="bi bi-download me-2"></i> Unduh Dokumen
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="bi bi-cloud-slash display-1 text-muted mb-4"></i>
                <h3>Belum Ada Dokumen Tersedia</h3>
                <p class="text-muted">Maaf, saat ini belum ada dokumen yang dapat diunduh. Silakan kembali lagi nanti.</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
            </div>
            @endforelse
        </div>
    </section>

    <style>
        .bg-light-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .transition-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-layout.app>