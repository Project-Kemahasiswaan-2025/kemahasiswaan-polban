<x-layout.app :title="__('menu.student_organizations')">
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5" id="page-header">
        <div class="container">
            <h1 class="display-4 fw-bold" id="header-title">{{ __('menu.student_organizations') }}</h1>
            <p class="lead" id="header-lead">Organisasi Mahasiswa dan Unit Kegiatan Mahasiswa</p>
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
    <script>
        $(document).ready(function() {
            let searchTimeout;
            let categoryGroups = [];
            
            // Load category groups first
            loadCategoryGroups();
            
            // Get initial category from URL
            const urlParams = new URLSearchParams(window.location.search);
            const initialCategory = urlParams.get('category');
            
            function loadCategoryGroups() {
                $.ajax({
                    url: '/api/ormawa/groups',
                    method: 'GET',
                    success: function(response) {
                        categoryGroups = response.data || [];
                        
                        // Populate select options
                        let options = '<option value="">{{ __("app.all") }} {{ __("menu.student_organizations") }}</option>';
                        categoryGroups.forEach(group => {
                            const selected = initialCategory === group.slug ? 'selected' : '';
                            options += `<option value="${group.slug}" ${selected}>${group.name}</option>`;
                        });
                        $('#category-filter').html(options);
                        
                        // Update header if category is selected
                        if (initialCategory) {
                            updateHeader(initialCategory);
                        }
                        
                        // Load organizations
                        loadOrmawa();
                    },
                    error: function() {
                        console.error('Failed to load category groups');
                        loadOrmawa();
                    }
                });
            }
            
            function updateHeader(categorySlug) {
                if (!categorySlug) {
                    $('#header-title').text('{{ __("menu.student_organizations") }}');
                    $('#header-lead').text('Organisasi Mahasiswa dan Unit Kegiatan Mahasiswa');
                    return;
                }
                
                const group = categoryGroups.find(g => g.slug === categorySlug);
                if (group) {
                    $('#header-title').text(group.name);
                    $('#header-lead').text(group.excerpt || 'Organisasi Mahasiswa dan Unit Kegiatan Mahasiswa');
                }
            }
            
            // Search functionality
            $('#search-input').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    loadOrmawa();
                }, 500);
            });
            
            // Category filter
            $('#category-filter').on('change', function() {
                const selectedCategory = $(this).val();
                updateHeader(selectedCategory);
                loadOrmawa();
            });
        });
    </script>
    @endpush
</x-layout.app>
