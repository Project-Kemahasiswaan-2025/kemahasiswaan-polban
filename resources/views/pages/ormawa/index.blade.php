<x-layout.app :title="__('menu.student_organizations')">
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <h1 class="display-4 fw-bold">{{ __('menu.student_organizations') }}</h1>
            <p class="lead">Organisasi Mahasiswa dan Unit Kegiatan Mahasiswa</p>
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
                        <option value="hmj" {{ request('category') === 'hmj' ? 'selected' : '' }}>HMJ</option>
                        <option value="ukm" {{ request('category') === 'ukm' ? 'selected' : '' }}>UKM</option>
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
    <script>
        $(document).ready(function() {
            let searchTimeout;
            
            // Get initial category from URL
            const urlParams = new URLSearchParams(window.location.search);
            const initialCategory = urlParams.get('category');
            if (initialCategory) {
                $('#category-filter').val(initialCategory);
            }
            
            // Load organizations
            loadOrmawa();
            
            // Search functionality
            $('#search-input').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    loadOrmawa();
                }, 500);
            });
            
            // Category filter
            $('#category-filter').on('change', function() {
                loadOrmawa();
            });
        });
    </script>
    @endpush
</x-layout.app>
