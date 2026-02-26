<x-layout.app title="Pusat Unduhan & Dokumen">
    <!-- Page Header -->
    <section class="page-header bg-navy text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Unduhan</h1>
            <p class="lead opacity-75">Temukan berbagai dokumen, formulir, dan sertifikat yang Anda butuhkan di sini.</p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-5 bg-light min-vh-100">
        <div class="container">
            <div class="row g-4">
                <!-- Sidebar Categories -->
                <div class="col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 6rem; z-index: 1010;">
                        <div class="card-header bg-navy text-white py-3 border-0">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-folder-fill me-2"></i>Kategori</h5>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('download.index') }}"
                                class="list-group-item list-group-item-action py-3 border-0 d-flex align-items-center {{ !$selectedCategory ? 'active bg-primary-subtle border-start border-primary border-4 text-primary fw-bold' : '' }}">
                                <i class="bi bi-collection-fill me-3 {{ !$selectedCategory ? 'text-primary' : 'text-muted' }}"></i>
                                Semua Dokumen
                            </a>
                            @foreach($categories as $category)
                            <a href="{{ route('download.index', ['category' => $category->slug]) }}"
                                class="list-group-item list-group-item-action py-3 border-0 d-flex align-items-center {{ $selectedCategory && $selectedCategory->id === $category->id ? 'active bg-primary-subtle border-start border-primary border-4 text-primary fw-bold' : '' }}">
                                <i class="bi bi-folder2 me-3 {{ $selectedCategory && $selectedCategory->id === $category->id ? 'text-primary' : 'text-muted' }}"></i>
                                {{ $category->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="d-flex align-items-center mb-4">
                        <h2 class="fw-bold mb-0">
                            {{ $selectedCategory ? $selectedCategory->name : 'Semua Dokumen' }}
                        </h2>
                        <div class="ms-auto text-muted small">
                            Menampilkan {{ $downloads->firstItem() ?? 0 }} - {{ $downloads->lastItem() ?? 0 }} dari {{ $downloads->total() }} dokumen
                        </div>
                    </div>

                    <div class="row g-4">
                        @forelse($downloads as $download)
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm border-0 transition-hover rounded-4">
                                <div class="card-body p-4 d-flex">
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('download.show', $download->id) }}" class="text-decoration-none">
                                            <div class="bg-light-primary p-3 rounded-4 transition-hover">
                                                <i class="bi bi-file-earmark-arrow-down text-primary fs-3"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="ms-4 flex-grow-1">
                                        <a href="{{ route('download.show', $download->id) }}" class="text-decoration-none text-navy">
                                            <h5 class="fw-bold mb-1 line-clamp-2">{{ $download->name }}</h5>
                                        </a>
                                        <div class="d-flex flex-wrap gap-2 text-muted small mb-3">
                                            <span><i class="bi bi-clock me-1"></i> {{ $download->updated_at->format('d M Y') }}</span>
                                            @if($download->file_size)
                                            <span class="text-secondary opacity-50">|</span>
                                            <span><i class="bi bi-hdd me-1"></i> {{ number_format($download->file_size / 1024, 1) }} KB</span>
                                            @endif
                                            @if(!$selectedCategory && $download->category)
                                            <span class="text-secondary opacity-50">|</span>
                                            <span class="badge bg-light text-primary border border-primary-subtle">{{ $download->category->name }}</span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ asset('storage/' . $download->file_path) }}"
                                                class="btn btn-primary btn-sm rounded-pill px-4"
                                                target="_blank" download>
                                                <i class="bi bi-download me-2"></i> Unduh
                                            </a>
                                            <a href="{{ route('download.show', $download->id) }}"
                                                class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                                <i class="bi bi-eye me-2"></i> Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                                <i class="bi bi-cloud-slash display-1 text-muted mb-4"></i>
                                <h3>Belum Ada Dokumen</h3>
                                <p class="text-muted">Maaf, saat ini belum ada dokumen tersedia untuk kategori ini.</p>
                                @if($selectedCategory)
                                <a href="{{ route('download.index') }}" class="btn btn-navy mt-3 rounded-pill px-4">Lihat Semua Dokumen</a>
                                @endif
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-5 d-flex justify-content-center">
                        {{ $downloads->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .bg-light-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .bg-navy {
            background-color: #001f3f;
        }

        .transition-hover {
            transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
        }

        .transition-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .list-group-item:hover:not(.active) {
            background-color: #f8f9fa;
        }

        .list-group-item.active {
            z-index: 2;
        }

        @media (max-width: 991.98px) {
            .col-lg-3 .sticky-top {
                position: relative !important;
                top: 0 !important;
                margin-bottom: 1.5rem;
            }
        }
    </style>
</x-layout.app>