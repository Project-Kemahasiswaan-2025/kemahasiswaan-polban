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
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <select class="form-select" id="category-filter">
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
    <script src="{{ asset('js/pages/competition.js') }}"></script>
    @endpush
</x-layout.app>