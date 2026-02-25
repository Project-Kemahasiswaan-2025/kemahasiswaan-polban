$(document).ready(function() {
    loadBanners();
    loadVideos();
    loadPosters();
    loadRunningText();
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
            console.log('Failed to load running texts');
        }
    });
}

// Render Running Text
function renderRunningText(config, texts) {
    const container = $('#running-text-container');
    const iconElement = $('#running-text-icon-content');
    const scrollElement = $('#running-text-scroll');
    
    iconElement.text(config.icon_text);
    
    let content = '';
    texts.forEach((text, index) => {
        content += '<span class="running-text-item">' + text.content + '</span>';
        if (index < texts.length - 1) {
            content += '<span class="running-text-separator"> ' + config.separator_text + ' </span>';
        }
    });
    
    scrollElement.html(content + content);
    container.fadeIn();
    
    const contentWidth = scrollElement[0].scrollWidth / 2;
    const duration = contentWidth / 50; 
    
    scrollElement.css('animation-duration', duration + 's');
}

// Load Posters
function loadPosters(categoryId = null) {
    const posterContainer = $('#poster-container');
    const tabsContainer = $('#poster-tabs');

    posterContainer.css('opacity', '0.5');

    $.ajax({
        url: '/api/posters',
        method: 'GET',
        data: { category_id: categoryId },
        success: function(response) {
            posterContainer.css('opacity', '1');
            
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
