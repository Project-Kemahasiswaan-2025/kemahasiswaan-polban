    loadBanners();
    loadVideos();
    loadPosters();
    loadLatestCompetition();
    loadLatestScholarships();
    loadRunningText();

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
            
            if (response.categories && response.categories.length > 0) {
                renderPosterTabs(response.categories, response.active_category_id);
                $('#poster-tabs').show();
            } else {
                $('#poster-tabs').hide();
            }

            if (response.posters && response.posters.length > 0) {
                renderPosters(response.posters);
                $('#poster-section').show();
            } else if (!categoryId && (!response.categories || response.categories.length === 0)) {
                $('#poster-section').hide();
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

// Load Latest Competition
function loadLatestCompetition() {
    $.ajax({
        url: '/api/competitions',
        method: 'GET',
        data: { page: 1 },
        success: function(response) {
            if (response.data && response.data.length > 0) {
                renderLatestCompetition(response.data[0]);
                $('#latest-competition-section').fadeIn();
            } else {
                $('#latest-competition-section').hide();
            }
        },
        error: function() {
            $('#latest-competition-section').hide();
        }
    });
}

// Render Latest Competition
function renderLatestCompetition(comp) {
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
    }

    const imageHtml = comp.image_url 
        ? `<img src="${comp.image_url}" class="featured-comp-image" alt="${comp.title}">`
        : `<div class="featured-comp-placeholder"><i class="bi bi-trophy text-white-50"></i></div>`;

    const html = `
        <div class="featured-comp-card fade-in">
            <div class="row g-0">
                <div class="col-lg-4 p-0">
                    <div class="featured-comp-image-wrapper h-100">
                        ${imageHtml}
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="featured-comp-body p-4 p-md-5 h-100 d-flex flex-column">
                        <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
                            <div>
                                <span class="badge ${statusClass} rounded-pill px-3 mb-2 me-1">${statusLabel}</span>
                                <span class="badge bg-light text-dark border rounded-pill px-3 mb-2">${comp.category_name}</span>
                            </div>
                            ${comp.registration_range ? `
                            <div class="text-muted small fw-semibold">
                                <i class="bi bi-calendar-event me-2 text-primary"></i>Registrasi: ${comp.registration_range}
                            </div>` : ''}
                        </div>
                        
                        <div class="mb-4">
                            <h2 class="featured-comp-title mb-2 fw-bold text-dark">${comp.title}</h2>
                            <h4 class="text-primary fw-bold mb-0 fs-5">${comp.competition_name}</h4>
                        </div>
                        
                        <div class="featured-comp-text text-muted mb-4 text-justify line-clamp-3">
                            ${comp.content || ''}
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                ${comp.location ? `
                                <div class="d-flex align-items-start mb-2">
                                    <div class="icon-circle-mini bg-primary text-white me-2 mt-1"><i class="bi bi-geo-alt"></i></div>
                                    <div>
                                        <small class="text-muted d-block lh-1 mb-1">Lokasi</small>
                                        <span class="small fw-semibold text-dark">${comp.location}</span>
                                    </div>
                                </div>` : ''}
                                
                                ${comp.contact_info ? `
                                <div class="d-flex align-items-start">
                                    <div class="icon-circle-mini bg-orange text-white me-2 mt-1"><i class="bi bi-person-badge"></i></div>
                                    <div>
                                        <small class="text-muted d-block lh-1 mb-1">Kontak Persona</small>
                                        <span class="small fw-semibold text-dark">${comp.contact_info}</span>
                                    </div>
                                </div>` : ''}
                            </div>
                            <div class="col-md-6">
                                ${comp.timelines && comp.timelines.length > 0 ? `
                                <div class="timeline-simple-home">
                                    ${comp.timelines.slice(0, 2).map(t => `
                                        <div class="timeline-item d-flex mb-2 pb-2 border-bottom border-light">
                                            <span class="fw-semibold small" style="min-width: 100px;">${t.date || '-'}</span>
                                            <span class="small text-muted">${t.label}</span>
                                        </div>
                                    `).join('')}
                                </div>` : ''}
                            </div>
                        </div>
                        
                        <div class="mt-auto pt-3 border-top d-flex flex-wrap gap-2">
                            ${comp.post_url ? `
                            <a href="${comp.post_url}" target="_blank" class="btn btn-primary px-4 rounded-pill">
                                <i class="bi bi-info-circle me-2"></i>Detail Info
                            </a>` : ''}
                            
                            ${comp.registration_url ? `
                            <a href="${comp.registration_url}" target="_blank" class="btn btn-success px-4 rounded-pill text-white">
                                <i class="bi bi-pencil-square me-2"></i>Link Pendaftaran
                            </a>` : ''}
                            
                            ${comp.guidebook_url ? `
                            <a href="${comp.guidebook_url}" target="_blank" class="btn btn-outline-info px-4 rounded-pill">
                                <i class="bi bi-file-earmark-pdf me-2"></i>Guidebook / Juknis
                            </a>` : ''}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#latest-competition-container').html(html);
}

// Load Latest Scholarships
function loadLatestScholarships() {
    let allScholarships = [];
    
    // Fetch Berjalan Status First
    $.ajax({
        url: '/api/beasiswa',
        method: 'GET',
        data: { status: 'berjalan' },
        success: function(response) {
            if (response.data && response.data.data) {
                allScholarships = response.data.data;
            }
            
            // If less than 3, fetch "akan-datang"
            if (allScholarships.length < 3) {
                $.ajax({
                    url: '/api/beasiswa',
                    method: 'GET',
                    data: { status: 'akan-datang' },
                    success: function(upcomingResponse) {
                        if (upcomingResponse.data && upcomingResponse.data.data) {
                            allScholarships = allScholarships.concat(upcomingResponse.data.data);
                        }
                        
                        if (allScholarships.length > 0) {
                            renderLatestScholarships(allScholarships.slice(0, 3));
                            $('#latest-scholarship-section').fadeIn();
                        } else {
                            $('#latest-scholarship-section').hide();
                        }
                    },
                    error: function() {
                        if (allScholarships.length > 0) {
                            renderLatestScholarships(allScholarships.slice(0, 3));
                            $('#latest-scholarship-section').fadeIn();
                        } else {
                            $('#latest-scholarship-section').hide();
                        }
                    }
                });
            } else {
                renderLatestScholarships(allScholarships.slice(0, 3));
                $('#latest-scholarship-section').fadeIn();
            }
        },
        error: function() {
            $('#latest-scholarship-section').hide();
        }
    });
}

// Render Latest Scholarships
function renderLatestScholarships(scholarships) {
    let html = '';
    const today = new Date().setHours(0, 0, 0, 0);

    scholarships.forEach(s => {
        // Use tanggal_berakhir as per beasiswa.js
        const deadlineDate = s.tanggal_berakhir ? new Date(s.tanggal_berakhir) : null;
        const deadlineStr = deadlineDate ? deadlineDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
        
        // Status checks
        let isPast = deadlineDate && deadlineDate.getTime() < today;
        let colorClass = isPast ? 'text-muted' : 'text-danger';

        html += `
            <div class="col-lg-4 col-md-6 fade-in mb-4">
                <div class="card h-100 border-0 shadow-sm scholarship-mini-card">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="scholarship-icon-wrapper">
                                <i class="bi bi-mortarboard text-white"></i>
                            </div>
                            <span class="badge bg-light text-primary border rounded-pill px-3">${s.tipe_beasiswa || 'Umum'}</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-2 line-clamp-2" style="min-height: 2.8em;">${s.nama_beasiswa}</h5>
                        <div class="d-flex align-items-center gap-2 text-muted small mb-4">
                            <i class="bi bi-building"></i> <span>${s.sumber || 'Polban'}</span>
                        </div>
                        <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="${colorClass} small fw-bold">
                                <i class="bi bi-clock me-1"></i> s.d ${deadlineStr}
                            </div>
                            <a href="/beasiswa/${s.id}" class="btn btn-link link-primary p-0 text-decoration-none fw-bold">
                                Detail <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    $('#latest-scholarship-container').html(html);
}
