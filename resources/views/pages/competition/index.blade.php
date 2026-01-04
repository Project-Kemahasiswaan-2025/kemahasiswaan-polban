<x-layout.app :title="__('menu.competitions')">
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <h1 class="display-4 fw-bold">{{ __('menu.competitions') }}</h1>
            <p class="lead">Kompetisi Nasional dan Internasional</p>
        </div>
    </section>
    
    <!-- Filter Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <select class="form-select" id="category-filter">
                        <option value="">{{ __('app.all') }} {{ __('menu.competitions') }}</option>
                        <option value="puspresnas">Puspresnas BPTI</option>
                        <option value="bakorma">BAKORMA</option>
                        <option value="internal">Internal Polban</option>
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
            
            <div class="row g-4" id="competition-container">
                <!-- Competitions will be loaded via AJAX -->
            </div>
            
            <div id="no-results" class="text-center py-5 d-none">
                <p class="text-muted">{{ __('app.no_results') }}</p>
            </div>
        </div>
    </section>
    
    @push('scripts')
    <script>
        $(document).ready(function() {
            // Load competitions
            loadCompetitions();
            
            // Category filter
            $('#category-filter').on('change', function() {
                loadCompetitions();
            });
        });
    </script>
    @endpush
</x-layout.app>
