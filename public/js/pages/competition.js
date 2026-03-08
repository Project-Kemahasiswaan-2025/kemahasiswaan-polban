// Pagination State
let competitionCurrentPage = 1;

$(document).ready(function() {
    loadCompetitions();
    
    $('#category-filter').on('change', function() {
        loadCompetitions();
    });
});

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
                                
                                <div class="card-text text-muted mb-4 text-justify line-clamp-3">
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
