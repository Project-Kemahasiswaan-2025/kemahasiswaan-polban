<x-layout.app :title="__('menu.scholarships')">
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <h1 class="display-4 fw-bold">{{ __('menu.scholarships') ?? 'Beasiswa' }}</h1>
            <p class="lead">Informasi Beasiswa untuk Mahasiswa Polban</p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="card border-0 shadow-sm p-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Cari Beasiswa</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control border-start-0" id="beasiswa-search" placeholder="Nama beasiswa...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Tipe Beasiswa</label>
                        <select class="form-select" id="beasiswa-tipe">
                            <option value="">Semua Tipe</option>
                            <option value="kipk">KIP-K</option>
                            <option value="internal">Internal</option>
                            <option value="eksternal">Eksternal</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Jenis Beasiswa</label>
                        <select class="form-select" id="beasiswa-jenis">
                            <option value="">Semua Jenis</option>
                            <option value="full">Full</option>
                            <option value="half">Half</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Jurusan</label>
                        <input type="text" class="form-control" id="beasiswa-jurusan" placeholder="Filter jurusan...">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button class="btn btn-primary w-100" id="beasiswa-filter-btn">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Beasiswa List -->
    <section class="py-5">
        <div class="container">
            <div id="beasiswa-loading" class="text-center py-5 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div class="row g-4" id="beasiswa-container">
                <!-- Beasiswa will be loaded via JS -->
            </div>

            <!-- Pagination -->
            <div id="beasiswa-pagination-container" class="mt-5 d-flex justify-content-center">
                <!-- Pagination will be loaded via JS -->
            </div>

            <div id="beasiswa-no-results" class="text-center py-5 d-none">
                <i class="bi bi-search display-1 text-muted mb-4 d-block"></i>
                <p class="text-muted fs-4">Tidak ada beasiswa ditemukan</p>
            </div>
        </div>
    </section>

    <style>
        .beasiswa-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 15px;
        }

        .beasiswa-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .poster-wrapper {
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
        }

        .beasiswa-meta i {
            width: 20px;
        }
    </style>

    @push('scripts')
    <script>
        $(document).ready(function() {
            loadBeasiswa();

            $('#beasiswa-filter-btn').on('click', function() {
                loadBeasiswa();
            });

            // Trigger search on enter
            $('#beasiswa-search, #beasiswa-jurusan').on('keypress', function(e) {
                if (e.which == 13) loadBeasiswa();
            });

            // Auto reload on select change
            $('#beasiswa-tipe, #beasiswa-jenis').on('change', function() {
                loadBeasiswa();
            });
        });
    </script>
    @endpush
</x-layout.app>