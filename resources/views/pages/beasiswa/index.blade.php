@push('styles')
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
@endpush

<x-layout.app :title="__('menu.scholarships')">
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <h1 class="display-4 fw-bold">{{ __('menu.scholarships') ?? 'Beasiswa' }}</h1>
            <p class="lead">{{ __('landing.beasiswa.subtitle') }}</p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="card border-0 shadow-sm p-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">{{ __('landing.beasiswa.search_label') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control border-start-0" id="beasiswa-search" placeholder="{{ __('landing.beasiswa.search_placeholder') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">{{ __('landing.beasiswa.major_label') }}</label>
                        <input type="text" class="form-control" id="beasiswa-jurusan" placeholder="{{ __('landing.beasiswa.major_placeholder') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">{{ __('landing.beasiswa.type_label') }}</label>
                        <select class="form-select" id="beasiswa-tipe">
                            <option value="">{{ __('app.all') }}</option>
                            <option value="kipk">KIP-K</option>
                            <option value="internal">Internal</option>
                            <option value="eksternal">Eksternal</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">{{ __('landing.beasiswa.kind_label') }}</label>
                        <select class="form-select" id="beasiswa-jenis">
                            <option value="">{{ __('app.all') }}</option>
                            <option value="full">Full</option>
                            <option value="half">Half</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">{{ __('landing.beasiswa.status_label') }}</label>
                        <select class="form-select" id="beasiswa-status">
                            <option value="">{{ __('app.all') }}</option>
                            <option value="berjalan">{{ __('landing.beasiswa.status_ongoing') }}</option>
                            <option value="akan-datang">{{ __('landing.beasiswa.status_upcoming') }}</option>
                            <option value="selesai">{{ __('landing.beasiswa.status_ended') }}</option>
                        </select>
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
                    <span class="visually-hidden">{{ __('app.loading') }}</span>
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
                <p class="text-muted fs-4">{{ __('landing.beasiswa.no_results') }}</p>
            </div>
        </div>
    </section>

    <!-- Template for Beasiswa Card (Horizontal Thread Style) -->
    <template id="beasiswa-card-template">
        <div class="col-12 fade-in mb-4">
            <div class="card shadow-sm beasiswa-card border-0 overflow-hidden hover-shadow transition">
                <div class="row g-0">
                    <!-- Image Section -->
                    <div class="col-md-3 bg-light d-flex align-items-center justify-content-center overflow-hidden position-relative" style="min-height: 180px;">
                        <img src="" class="beasiswa-poster img-fluid w-100 h-100 d-none" style="object-fit: cover;" alt="{{ __('landing.beasiswa.poster_alt') }}">
                        <div class="beasiswa-poster-fallback w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                            <i class="bi bi-mortarboard" style="font-size: 3.5rem;"></i>
                        </div>
                        <span class="beasiswa-status-badge badge position-absolute top-0 end-0 m-3 rounded-pill px-3 py-2 shadow-sm"></span>
                    </div>

                    <!-- Content Section -->
                    <div class="col-md-9">
                        <div class="card-body p-4 d-flex flex-column h-100">
                            <!-- Header Info -->
                            <div class="d-flex justify-content-between align-items-start mb-2 gap-3">
                                <div class="d-flex gap-2">
                                    <span class="beasiswa-tipe-badge badge bg-primary bg-opacity-10 text-white border border-primary-subtle rounded-pill px-3"></span>
                                    <span class="beasiswa-jenis-badge badge bg-info bg-opacity-10 text-info border border-info-subtle rounded-pill px-3"></span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-x text-danger me-2 small"></i>
                                    <span class="beasiswa-deadline small text-danger fw-bold"></span>
                                </div>
                            </div>

                            <!-- Title & Source -->
                            <h4 class="beasiswa-title fw-bold text-dark mb-1"></h4>
                            <div class="d-flex align-items-center mb-3 text-muted small">
                                <i class="bi bi-building me-2"></i>
                                <span class="beasiswa-sumber"></span>
                            </div>

                            <!-- Description -->
                            <p class="beasiswa-description card-text text-muted small mb-4 line-clamp-2"></p>

                            <!-- Action -->
                            <div class="mt-auto pt-3 border-top d-flex justify-content-end gap-2">
                                <a href="" target="_blank" class="beasiswa-register-link btn btn-primary px-4 rounded-pill fw-bold d-none">
                                    <i class="bi bi-pencil-square me-2"></i>{{ __('landing.beasiswa.register_now') }}
                                </a>
                                <a href="" class="beasiswa-detail-link btn btn-outline-primary px-4 rounded-pill fw-bold">{{ __('landing.beasiswa.view_detail') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    @push('scripts')
    <script src="{{ asset('js/pages/beasiswa.js') }}"></script>
    @endpush
</x-layout.app>