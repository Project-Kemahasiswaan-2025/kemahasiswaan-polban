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
                let options = '<option value="">Semua Organisasi Mahasiswa</option>';
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
        const homeLabel = document.documentElement.lang === 'id' ? 'Beranda' : 'Home';
        const ormawaLabel = document.documentElement.lang === 'id' ? 'Organisasi Mahasiswa' : 'Student Organizations';
        const homeUrl = '/';
        const ormawaUrl = '/ormawa';

        if (!categorySlug) {
            $('#header-title').text(ormawaLabel);
            $('#header-lead').text('Organisasi Mahasiswa dan Unit Kegiatan Mahasiswa');
            
            // Reset breadcrumb
            $('#breadcrumb-container ol').html(`
                <li class="breadcrumb-item"><a href="${homeUrl}" class="text-white">${homeLabel}</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">${ormawaLabel}</li>
            `);
            return;
        }
        
        const group = categoryGroups.find(g => g.slug === categorySlug);
        if (group) {
            $('#header-title').text(group.name);
            $('#header-lead').text(group.excerpt || 'Organisasi Mahasiswa dan Unit Kegiatan Mahasiswa');
            
            // Update breadcrumb
            $('#breadcrumb-container ol').html(`
                <li class="breadcrumb-item"><a href="${homeUrl}" class="text-white">${homeLabel}</a></li>
                <li class="breadcrumb-item"><a href="${ormawaUrl}" class="text-white">${ormawaLabel}</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">${group.name}</li>
            `);
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

// Load Ormawa
function loadOrmawa() {
    const search = $('#search-input').val();
    const category = $('#category-filter').val();
    
    $('#loading').removeClass('d-none');
    $('#ormawa-container').html('');
    $('#no-results').addClass('d-none');
    
    $.ajax({
        url: '/api/ormawa',
        method: 'GET',
        data: {
            search: search,
            category: category
        },
        success: function(response) {
            $('#loading').addClass('d-none');
            
            if (response.data && response.data.length > 0) {
                renderOrmawa(response.data);
            } else {
                $('#no-results').removeClass('d-none');
            }
        },
        error: function() {
            $('#loading').addClass('d-none');
            $('#ormawa-container').html('<div class="col-12 text-center"><p class="text-danger">Failed to load organizations</p></div>');
        }
    });
}

// Render Ormawa
function renderOrmawa(organizations) {
    let html = '';
    
    organizations.forEach(org => {
        let readMoreLink = '';
        if (org.is_group) {
            readMoreLink = `/ormawa?category=${org.slug}`;
        } else {
            readMoreLink = `/ormawa/${org.slug}`;
        }
        
        html += `
            <div class="col-md-4 col-lg-3 fade-in">
                <div class="card h-100 shadow-sm hover-card">
                    ${org.logo ? 
                        `<div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <img src="${org.logo}" alt="${org.name}" class="img-fluid" style="max-height: 180px;">
                        </div>` :
                        `<div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-building text-white" style="font-size: 4rem;"></i>
                        </div>`
                    }
                    <div class="card-body">
                        <h5 class="card-title">${org.name}</h5>
                        ${org.description ? `<p class="card-text text-muted">${org.description.substring(0, 100)}${org.description.length > 100 ? '...' : ''}</p>` : ''}
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="${readMoreLink}" class="btn btn-primary btn-sm w-100">Read More</a>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#ormawa-container').html(html);
}
