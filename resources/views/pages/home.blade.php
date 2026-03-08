<x-layout.app :title="__('menu.home')">
    <!-- Hero Section with Banner Slider -->
    <section class="hero-section">
        <div id="banner-container">
            <!-- Banners will be loaded via AJAX -->
        </div>
    </section>

    <!-- Running Text Section -->
    <x-running-text />

    <!-- Featured Video Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <!-- <h2 class="text-center mb-5 fw-bold">Featured Videos</h2> -->
            <div id="video-tabs" class="d-flex flex-wrap justify-content-center gap-2 mb-5">
                <!-- Video category tabs will be loaded via AJAX -->
            </div>
            <div class="row" id="video-container">
                <!-- Videos will be loaded via AJAX -->
            </div>
        </div>
    </section>

    <!-- Poster Section -->
    <section class="py-5 bg-white" id="poster-section" style="display: none;">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">{{ __('landing.home.latest_info') }}</h2>
            <div id="poster-tabs" class="d-flex flex-wrap justify-content-center gap-2 mb-5">
                <!-- Category tabs will be loaded via AJAX -->
            </div>
            <div id="poster-container" class="row g-4">
                <!-- Posters will be loaded via AJAX -->
            </div>
        </div>
    </section>

    <!-- Latest Competition Section -->
    <section class="py-5 bg-light" id="latest-competition-section" style="display: none;">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 gap-3">
                <div>
                    <h2 class="fw-bold mb-2">{{ __('landing.home.latest_competitions') }}</h2>
                    <p class="text-muted mb-0">{{ __('landing.home.competitions_desc') }}</p>
                </div>
                <a href="{{ route('competition.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                    {{ __('landing.home.view_all_competitions') }} <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
            <div id="latest-competition-container">
                <!-- Latest competition will be loaded via AJAX -->
            </div>
        </div>
    </section>

    <!-- Latest Scholarship Section -->
    <section class="py-5 bg-white" id="latest-scholarship-section" style="display: none;">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 gap-3">
                <div>
                    <h2 class="fw-bold mb-2">{{ __('landing.home.scholarship_info') }}</h2>
                    <p class="text-muted mb-0">{{ __('landing.home.scholarship_desc') }}</p>
                </div>
                <a href="{{ route('beasiswa.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                    {{ __('landing.home.view_all_scholarship') }} <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
            <div class="row g-4" id="latest-scholarship-container">
                <!-- Latest scholarships will be loaded via AJAX -->
            </div>
        </div>
    </section>

    <!-- Quick Links Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2">{{ __('landing.home.quick_access') }}</h2>
                <p class="text-muted">{{ __('landing.home.quick_access_desc') }}</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4">
                    <a href="{{ route('ormawa.index') }}" class="text-decoration-none">
                        <div class="card quick-link-card h-100 p-4 shadow-sm border-0">
                            <div class="card-body p-3 text-center">
                                <div class="quick-link-icon-wrapper text-primary">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <h4 class="card-title text-dark">{{ __('menu.student_organizations') }}</h4>
                                <p class="card-text text-muted">{{ __('landing.home.ormawa_desc') }}</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4">
                    <a href="{{ route('competition.index') }}" class="text-decoration-none">
                        <div class="card quick-link-card h-100 p-4 shadow-sm border-0">
                            <div class="card-body p-3 text-center">
                                <div class="quick-link-icon-wrapper text-warning">
                                    <i class="bi bi-trophy-fill"></i>
                                </div>
                                <h4 class="card-title text-dark">{{ __('menu.competitions') }}</h4>
                                <p class="card-text text-muted">{{ __('landing.home.competitions_card_desc') }}</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4">
                    <a href="{{ route('beasiswa.index') }}" class="text-decoration-none">
                        <div class="card quick-link-card h-100 p-4 shadow-sm border-0">
                            <div class="card-body p-3 text-center">
                                <div class="quick-link-icon-wrapper text-success">
                                    <i class="bi bi-mortarboard-fill"></i>
                                </div>
                                <h4 class="card-title text-dark">{{ __('menu.scholarship') }}</h4>
                                <p class="card-text text-muted">{{ __('landing.home.scholarship_card_desc') }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script src="{{ asset('js/pages/home.js') }}"></script>
    @endpush
</x-layout.app>