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

// Load Competitions
function loadCompetitions() {
    const category = $('#category-filter').val();
    
    $('#loading').removeClass('d-none');
    $('#competition-container').html('');
    $('#no-results').addClass('d-none');
    
    $.ajax({
        url: '/api/competitions',
        method: 'GET',
        data: {
            category: category
        },
        success: function(response) {
            $('#loading').addClass('d-none');
            
            if (response.data && response.data.length > 0) {
                renderCompetitions(response.data);
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

// Render Competitions
function renderCompetitions(competitions) {
    let html = '';
    
    competitions.forEach(comp => {
        html += `
            <div class="col-md-6 col-lg-4 fade-in">
                <div class="card h-100 shadow-sm hover-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">${comp.name}</h5>
                            ${comp.is_external ? 
                                `<span class="badge bg-warning text-dark">
                                    <i class="bi bi-box-arrow-up-right"></i> External
                                </span>` : ''
                            }
                        </div>
                        ${comp.category ? `<span class="badge bg-secondary mb-2">${comp.category.toUpperCase()}</span>` : ''}
                        ${comp.description ? `<p class="card-text text-muted">${comp.description.substring(0, 150)}${comp.description.length > 150 ? '...' : ''}</p>` : ''}
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        ${comp.link ? 
                            `<a href="${comp.link}" 
                               target="${comp.is_external ? '_blank' : '_self'}" 
                               class="btn btn-primary btn-sm w-100">
                                Read More
                                ${comp.is_external ? '<i class="bi bi-box-arrow-up-right ms-1"></i>' : ''}
                            </a>` :
                            `<button class="btn btn-primary btn-sm w-100">Read More</button>`
                        }
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
