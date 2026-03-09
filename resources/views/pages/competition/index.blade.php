<x-layout.app :title="__('menu.competitions')">
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <h1 class="display-4 fw-bold">{{ __('menu.competitions') }}</h1>
            <p class="lead">{{ __('landing.competition.subtitle') }}</p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group search-box shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-primary"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" id="search-input" placeholder="{{ __('landing.competition.search_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select shadow-sm h-100" id="category-filter">
                        <option value="">{{ __('app.all') }} {{ __('menu.competitions') }}</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Competitions List -->
    <section class="py-5">
        <div class="container">
            <div id="loading" class="text-center py-5 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{ __('app.loading') }}</span>
                </div>
            </div>

            <!-- Competitions List -->
            <div class="row g-4" id="competition-container">
                <!-- Competitions will be loaded via AJAX -->
            </div>

            <!-- Pagination -->
            <div id="pagination-container" class="mt-5 d-flex justify-content-center">
                <!-- Pagination will be loaded via AJAX -->
            </div>

            <div id="no-results" class="text-center py-5 d-none">
                <i class="bi bi-search display-1 text-muted mb-4 d-block"></i>
                <p class="text-muted fs-4">{{ __('app.no_results') }}</p>
            </div>
        </div>
    </section>

    <style>
        .timeline-simple {
            position: relative;
            padding-left: 5px;
        }

        .card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>

    @push('scripts')
    <script>
        window.competitionTranslations = {
            status_ongoing: "{{ __('landing.competition.status_ongoing') }}",
            status_closed: "{{ __('landing.competition.status_closed') }}",
            status_completed: "{{ __('landing.competition.status_completed') }}",
            registration: "{{ __('landing.competition.registration') }}",
            timeline_heading: "{{ __('landing.competition.timeline_heading') }}",
            location: "{{ __('landing.competition.location') }}",
            contact: "{{ __('landing.competition.contact') }}",
            btn_detail: "{{ __('landing.competition.btn_detail') }}",
            btn_register: "{{ __('landing.competition.btn_register') }}",
            btn_guidebook: "{{ __('landing.competition.btn_guidebook') }}",
            load_error: "{{ __('landing.competition.load_error') }}",
        };
    </script>
    <script src="{{ asset('js/pages/competition.js') }}"></script>
    @endpush
</x-layout.app>