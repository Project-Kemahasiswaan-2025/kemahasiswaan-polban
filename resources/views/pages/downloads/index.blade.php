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
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 gap-3">
                        <h2 class="fw-bold mb-0">
                            {{ $selectedCategory ? $selectedCategory->name : 'Semua Dokumen' }}
                        </h2>

                        <div class="search-box position-relative" style="min-width: 300px;">
                            <form action="{{ route('download.index') }}" method="GET" id="searchForm">
                                @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                <div class="input-group overflow-hidden rounded-pill">
                                    <span class="input-group-text bg-white border-end-0 ps-3">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" id="searchInput"
                                        class="form-control border-start-0 py-2 shadow-none"
                                        placeholder="Cari dokumen..."
                                        value="{{ request('search') }}"
                                        autocomplete="off">
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4 text-muted small">
                        <i class="bi bi-info-circle me-2"></i>
                        Menampilkan {{ $downloads->firstItem() ?? 0 }} - {{ $downloads->lastItem() ?? 0 }} dari {{ $downloads->total() }} dokumen
                        @if(request('search'))
                        <span class="ms-2 badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                            Kata kunci: "{{ request('search') }}"
                        </span>
                        @endif
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
                            <div class="text-center py-5 bg-white rounded-4 shadow-sm border">
                                <i class="bi bi-search display-1 text-muted mb-4 opacity-25"></i>
                                <h3>{{ request('search') ? 'Dokumen Tidak Ditemukan' : 'Belum Ada Dokumen' }}</h3>
                                <p class="text-muted">
                                    {{ request('search')
                                        ? 'Maaf, tidak ada dokumen yang cocok dengan kata kunci "' . request('search') . '".'
                                        : 'Maaf, saat ini belum ada dokumen tersedia untuk kategori ini.'
                                    }}
                                </p>
                                @if(request('search'))
                                <a href="{{ route('download.index', ['category' => request('category')]) }}" class="btn btn-outline-primary rounded-pill mt-3 px-4">
                                    Bersihkan Pencarian
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-5 d-flex justify-content-center justify-content-md-end">
                        {{ $downloads->links('vendor.pagination.bootstrap-5-clean') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .bg-light-primary {
            background-color: #f0f7ff;
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
            transition: all 0.3s ease;
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');
            let timeout = null;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        searchForm.submit();
                    }, 800);
                });

                // Auto focus and move cursor to end
                if (searchInput.value !== '') {
                    searchInput.focus();
                    const val = searchInput.value;
                    searchInput.value = '';
                    searchInput.value = val;
                }
            }
        });
    </script>
</x-layout.app>