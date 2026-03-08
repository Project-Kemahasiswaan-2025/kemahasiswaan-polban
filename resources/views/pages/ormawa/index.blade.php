<x-layout.app :title="__('menu.student_organizations')">
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5" id="page-header">
        <div class="container">
            <nav aria-label="breadcrumb" id="breadcrumb-container">
                <ol class="breadcrumb text-white mb-3">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">{{ __('menu.home') }}</a></li>
                    <li class="breadcrumb-item active text-white" id="breadcrumb-current" aria-current="page">{{ __('menu.student_organizations') }}</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold" id="header-title">{{ __('menu.student_organizations') }}</h1>
            <p class="lead" id="header-lead">{{ __('landing.ormawa.subtitle') }}</p>
        </div>
    </section>

    <!-- Filter and Search Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-search-box id="search-input" />
                </div>
                <div class="col-md-6">
                    <select class="form-select" id="category-filter">
                        <option value="">{{ __('app.all') }} {{ __('menu.student_organizations') }}</option>
                        <!-- Dynamic options will be loaded via AJAX -->
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Organizations List -->
    <section class="py-5">
        <div class="container">
            <div id="loading" class="text-center py-5 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{ __('app.loading') }}</span>
                </div>
            </div>

            <div class="row g-4" id="ormawa-container">
                <!-- Organizations will be loaded via AJAX -->
            </div>

            <div id="no-results" class="text-center py-5 d-none">
                <p class="text-muted">{{ __('app.no_results') }}</p>
            </div>
        </div>
    </section>

    @push('scripts')
    <script src="{{ asset('js/pages/ormawa.js') }}"></script>
    @endpush
</x-layout.app>