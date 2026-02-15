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
    <section class="py-5 bg-white">
        <div class="container">
            <div id="poster-tabs" class="d-flex flex-wrap gap-2 mb-5">
                <!-- Category tabs will be loaded via AJAX -->
            </div>
            <div id="poster-container" class="row g-4">
                <!-- Posters will be loaded via AJAX -->
            </div>
        </div>
    </section>

    <!-- Quick Links Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Quick Links</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <a href="{{ route('ormawa.index') }}" class="text-decoration-none">
                        <div class="card quick-link-card h-100 text-center p-4 border-0 shadow-sm hover-card">
                            <i class="bi bi-people-fill text-primary mb-3" style="font-size: 3rem;"></i>
                            <h4 class="card-title">{{ __('menu.student_organizations') }}</h4>
                            <p class="card-text text-muted">Temukan berbagai organisasi mahasiswa dan UKM</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('competition.index') }}" class="text-decoration-none">
                        <div class="card quick-link-card h-100 text-center p-4 border-0 shadow-sm hover-card">
                            <i class="bi bi-trophy-fill text-warning mb-3" style="font-size: 3rem;"></i>
                            <h4 class="card-title">{{ __('menu.competitions') }}</h4>
                            <p class="card-text text-muted">Lihat kompetisi yang tersedia untuk mahasiswa</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="#" class="text-decoration-none">
                        <div class="card quick-link-card h-100 text-center p-4 border-0 shadow-sm hover-card">
                            <i class="bi bi-bookmark-fill text-success mb-3" style="font-size: 3rem;"></i>
                            <h4 class="card-title">{{ __('menu.scholarship') }}</h4>
                            <p class="card-text text-muted">Informasi beasiswa untuk mahasiswa</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Load banners
            loadBanners();

            // Load videos
            loadVideos();

            // Load running text
            loadRunningText();

            // Load posters
            loadPosters();
        });
    </script>
    @endpush
</x-layout.app>