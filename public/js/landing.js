// Global AJAX setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Load Banners
function loadBanners() {
    $.ajax({
        url: '/api/banners',
        method: 'GET',
        success: function(response) {
            if (response.data && response.data.length > 0) {
                renderBanners(response.data);
            } else {
                $('#banner-container').html('<div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 500px;"><p class="text-white">No banners available</p></div>');
            }
        },
        error: function() {
            $('#banner-container').html('<div class="bg-danger d-flex align-items-center justify-content-center" style="height: 500px;"><p class="text-white">Failed to load banners</p></div>');
        }
    });
}

// Render Banners
function renderBanners(banners) {
    let indicators = '';
    let items = '';
    
    banners.forEach((banner, index) => {
        indicators += `<button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="${index}" class="${index === 0 ? 'active' : ''}"></button>`;
        
        const imageHtml = `<img src="${banner.image_url}" class="d-block w-100" alt="${banner.title}">`;
        const content = banner.link ? `<a href="${banner.link}" class="d-block">${imageHtml}</a>` : imageHtml;
        
        items += `
            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                ${content}
                <div class="carousel-caption d-none d-md-block">
                    <h5>${banner.title}</h5>
                </div>
            </div>
        `;
    });
    
    const carousel = `
        <div id="bannerCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                ${indicators}
            </div>
            <div class="carousel-inner">
                ${items}
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    `;
    
    $('#banner-container').html(carousel);
}

// Load Videos
function loadVideos(categoryId = null) {
    const videoContainer = $('#video-container');
    const tabsContainer = $('#video-tabs');

    videoContainer.css('opacity', '0.5');

    $.ajax({
        url: '/api/videos',
        method: 'GET',
        data: { category_id: categoryId },
        success: function(response) {
            videoContainer.css('opacity', '1');

            if (response.categories) {
                renderVideoTabs(response.categories, response.active_category_id);
            }

            if (response.videos && response.videos.length > 0) {
                renderVideos(response.videos);
            } else {
                videoContainer.html('<div class="col-12 text-center py-5"><p class="text-muted">No videos available for this category</p></div>');
            }
        },
        error: function() {
            videoContainer.css('opacity', '1');
            videoContainer.html('<div class="col-12 text-center py-5"><p class="text-danger">Failed to load videos</p></div>');
        }
    });
}

// Render Video Tabs
function renderVideoTabs(categories, activeId) {
    let html = '';
    categories.forEach(category => {
        const activeClass = category.id === activeId ? 'active' : '';
        html += `<button class="btn btn-outline-primary rounded-pill px-4 video-tab ${activeClass}" 
                        onclick="loadVideos(${category.id})">
                    Video ${category.name}
                 </button>`;
    });
    $('#video-tabs').html(html);
}

// Render Videos
function renderVideos(videos) {
    let html = '';
    
    videos.forEach(video => {
        const videoId = extractYouTubeId(video.video_url);
        
        html += `
            <div class="col-md-4 mb-4 fade-in">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <div class="ratio ratio-16x9">
                        ${videoId ? 
                            `<iframe src="https://www.youtube.com/embed/${videoId}" 
                                    title="${video.title}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                            </iframe>` : 
                            `<div class="bg-secondary d-flex align-items-center justify-content-center">
                                <p class="text-white">Invalid video URL</p>
                            </div>`
                        }
                    </div>
                    <div class="card-body p-3">
                        <h5 class="card-title fs-6 fw-bold mb-0 text-dark line-clamp-2">${video.title}</h5>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#video-container').html(html);
}

// Extract YouTube ID
function extractYouTubeId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}

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
        // Determine the correct link based on is_group
        let readMoreLink = '';
        if (org.is_group) {
            // For groups (HMJ, UKM), link to filtered list
            readMoreLink = `/ormawa?category=${org.slug}`;
        } else {
            // For single organizations (MPM, BEM) or children, link to detail page
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

// Pagination State
let competitionCurrentPage = 1;

// Load Competitions
function loadCompetitions(page = 1) {
    const category = $('#category-filter').val();
    competitionCurrentPage = page;
    
    $('#loading').removeClass('d-none');
    $('#competition-container').html('');
    $('#no-results').addClass('d-none');
    $('#pagination-container').html('');
    
    $.ajax({
        url: '/api/competitions',
        method: 'GET',
        data: {
            category: category,
            page: page
        },
        success: function(response) {
            $('#loading').addClass('d-none');
            
            if (response.data && response.data.length > 0) {
                renderCompetitions(response.data);
                renderPagination(response.meta);
            } else {
                $('#no-results').removeClass('d-none');
            }
        },
        error: function() {
            $('#loading').addClass('d-none');
            $('#competition-container').html('<div class="col-12 text-center"><p class="text-danger">Failed to load competitions</p></div>');
        }
    });
}

// Render Pagination
function renderPagination(meta) {
    if (meta.last_page <= 1) return;

    let html = '<nav aria-label="Competition pagination"><ul class="pagination pagination-primary mb-0">';
    
    // Previous
    html += `
        <li class="page-item ${meta.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="loadCompetitions(${meta.current_page - 1})" aria-label="Previous">
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>
    `;

    // Pages
    for (let i = 1; i <= meta.last_page; i++) {
        if (i === 1 || i === meta.last_page || (i >= meta.current_page - 1 && i <= meta.current_page + 1)) {
            html += `
                <li class="page-item ${meta.current_page === i ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="loadCompetitions(${i})">${i}</a>
                </li>
            `;
        } else if (i === meta.current_page - 2 || i === meta.current_page + 2) {
            html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    // Next
    html += `
        <li class="page-item ${meta.current_page === meta.last_page ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="loadCompetitions(${meta.current_page + 1})" aria-label="Next">
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    `;

    html += '</ul></nav>';
    $('#pagination-container').html(html);
}

// Render Competitions
function renderCompetitions(competitions) {
    let html = '';
    
    competitions.forEach(comp => {
        // Status Badge Mapping
        let statusClass = 'bg-secondary';
        let statusLabel = comp.status ? comp.status.charAt(0).toUpperCase() + comp.status.slice(1) : 'Unknown';
        
        if (comp.status === 'ongoing') {
            statusClass = 'bg-primary';
            statusLabel = 'Sedang Berlangsung';
        } else if (comp.status === 'registration_closed') {
            statusClass = 'bg-danger';
            statusLabel = 'Pendaftaran Ditutup';
        } else if (comp.status === 'completed') {
            statusClass = 'bg-info text-dark';
            statusLabel = 'Selesai';
        } else if (comp.status === 'draft') {
            statusLabel = 'Draft';
        }

        const imageHtml = comp.image_url 
            ? `<div class="col-lg-4 p-0">
                   <div class="h-100 bg-light d-flex align-items-center justify-content-center overflow-hidden" style="min-height: 300px;">
                       <img src="${comp.image_url}" alt="${comp.title}" class="img-fluid w-100 h-100" style="object-fit: cover;"
                            onerror="this.parentElement.innerHTML='<div class=\'h-100 w-100 bg-secondary d-flex align-items-center justify-content-center\'><i class=\'bi bi-trophy text-white\' style=\'font-size: 6rem;\'></i></div>'">
                   </div>
               </div>`
            : `<div class="col-lg-4 p-0">
                   <div class="h-100 bg-secondary d-flex align-items-center justify-content-center" style="min-height: 300px;">
                       <i class="bi bi-trophy text-white" style="font-size: 6rem;"></i>
                   </div>
               </div>`;

        // Timeline rendering
        let timelineHtml = '';
        if (comp.timelines && comp.timelines.length > 0) {
            timelineHtml = `
                <div class="mt-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Timeline Kompetisi</h6>
                    <div class="timeline-simple">
                        ${comp.timelines.map(t => `
                            <div class="timeline-item d-flex mb-2 pb-2 border-bottom border-light">
                                <span class="fw-semibold small" style="min-width: 120px;">${t.date || '-'}</span>
                                <span class="small text-muted">${t.label}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }
        
        html += `
            <div class="col-12 fade-in mb-4">
                <div class="card h-100 shadow-sm border-0 overflow-hidden">
                    <div class="row g-0">
                        ${imageHtml}
                        <div class="col-lg-8">
                            <div class="card-body p-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
                                    <div>
                                        <span class="badge ${statusClass} rounded-pill px-3 mb-2 me-1">${statusLabel}</span>
                                        <span class="badge bg-light text-dark border rounded-pill px-3 mb-2">${comp.category_name}</span>
                                    </div>
                                    ${comp.registration_range ? `
                                    <div class="text-muted small fw-semibold">
                                        <i class="bi bi-calendar-event me-2"></i>Registrasi: ${comp.registration_range}
                                    </div>` : ''}
                                </div>
                                
                                <h3 class="card-title fw-bold mb-1">${comp.title}</h3>
                                <p class="text-primary fw-bold mb-3 fs-5">${comp.competition_name}</p>
                                
                                <div class="card-text text-muted mb-4 text-justify">
                                    ${comp.content || ''}
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        ${comp.location ? `
                                        <div class="d-flex align-items-start mb-2">
                                            <i class="bi bi-geo-alt text-primary me-2 mt-1"></i>
                                            <div>
                                                <small class="text-muted d-block lh-1 mb-1">Lokasi</small>
                                                <span class="small fw-semibold text-dark">${comp.location}</span>
                                            </div>
                                        </div>` : ''}
                                        
                                        ${comp.contact_info ? `
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-person-badge text-primary me-2 mt-1"></i>
                                            <div>
                                                <small class="text-muted d-block lh-1 mb-1">Kontak Persona</small>
                                                <span class="small fw-semibold text-dark">${comp.contact_info}</span>
                                            </div>
                                        </div>` : ''}
                                    </div>
                                    <div class="col-md-6">
                                        ${timelineHtml}
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2">
                                    ${comp.post_url ? `
                                    <a href="${comp.post_url}" target="_blank" class="btn btn-primary px-4">
                                        <i class="bi bi-info-circle me-2"></i>Detail Info
                                    </a>` : ''}
                                    
                                    ${comp.registration_url ? `
                                    <a href="${comp.registration_url}" target="_blank" class="btn btn-success px-4">
                                        <i class="bi bi-pencil-square me-2"></i>Link Pendaftaran
                                    </a>` : ''}
                                    
                                    ${comp.guidebook_url ? `
                                    <a href="${comp.guidebook_url}" target="_blank" class="btn btn-outline-info px-4">
                                        <i class="bi bi-file-earmark-pdf me-2"></i>Guidebook / Juknis
                                    </a>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#competition-container').html(html);
}

// Load Running Text
function loadRunningText() {
    $.ajax({
        url: '/api/running-texts',
        method: 'GET',
        success: function(response) {
            if (response.config && response.config.is_enabled && response.data && response.data.length > 0) {
                renderRunningText(response.config, response.data);
            }
        },
        error: function() {
            // Silently fail - running text is not critical
            console.log('Failed to load running texts');
        }
    });
}

// Render Running Text
function renderRunningText(config, texts) {
    const container = $('#running-text-container');
    const iconElement = $('#running-text-icon-content');
    const scrollElement = $('#running-text-scroll');
    
    // Set icon
    iconElement.text(config.icon_text);
    
    // Build running text content
    let content = '';
    texts.forEach((text, index) => {
        content += '<span class="running-text-item">' + text.content + '</span>';
        if (index < texts.length - 1) {
            content += '<span class="running-text-separator"> ' + config.separator_text + ' </span>';
        }
    });
    
    // Duplicate content for seamless scrolling
    scrollElement.html(content + content);
    
    // Show container
    container.fadeIn();
    
    // Calculate animation duration based on content length (adjust speed as needed)
    const contentWidth = scrollElement[0].scrollWidth / 2; // Divide by 2 because we duplicated
    const duration = contentWidth / 50; // 50 pixels per second
    
    scrollElement.css('animation-duration', duration + 's');
}

// Load Posters
function loadPosters(categoryId = null) {
    const posterContainer = $('#poster-container');
    const tabsContainer = $('#poster-tabs');

    // Add loading state to container only (to keep tabs visible)
    posterContainer.css('opacity', '0.5');

    $.ajax({
        url: '/api/posters',
        method: 'GET',
        data: { category_id: categoryId },
        success: function(response) {
            posterContainer.css('opacity', '1');
            
            // Render tabs only if categoryId is null (initial load) or refresh them
            if (response.categories) {
                renderPosterTabs(response.categories, response.active_category_id);
            }

            if (response.posters && response.posters.length > 0) {
                renderPosters(response.posters);
            } else {
                posterContainer.html('<div class="col-12 text-center py-5"><p class="text-muted">No posters available for this category</p></div>');
            }
        },
        error: function() {
            posterContainer.css('opacity', '1');
            posterContainer.html('<div class="col-12 text-center py-5"><p class="text-danger">Failed to load posters</p></div>');
        }
    });
}

// Render Poster Tabs
function renderPosterTabs(categories, activeId) {
    let html = '';
    categories.forEach(category => {
        const activeClass = category.id === activeId ? 'active' : '';
        html += `<button class="btn btn-outline-primary rounded-pill px-4 poster-tab ${activeClass}" 
                        onclick="loadPosters(${category.id})">
                    ${category.name}
                 </button>`;
    });
    $('#poster-tabs').html(html);
}

// Render Posters
function renderPosters(posters) {
    let html = '';
    posters.forEach(poster => {
        html += `
            <div class="col-sm-6 col-md-4 col-lg-3 fade-in">
                <div class="card h-100 border-0 shadow-sm poster-card">
                    <div class="poster-image-wrapper overflow-hidden position-relative">
                        <img src="${poster.image_url}" 
                             class="card-img-top poster-image" 
                             alt="${poster.title}"
                             onerror="this.src='https://placehold.co/600x800?text=Poster+Not+Found'">
                        <div class="poster-overlay">
                            <span class="badge bg-primary rounded-pill">${poster.created_at || ''}</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h5 class="card-title fs-6 fw-bold mb-0 text-dark line-clamp-2">
                            ${poster.title}
                        </h5>
                    </div>
                </div>
            </div>
        `;
    });
    $('#poster-container').html(html);
}
