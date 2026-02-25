// scholarship State
let beasiswaCurrentPage = 1;

$(document).ready(function() {
    loadBeasiswa();

    $('#beasiswa-filter-btn').on('click', function() {
        loadBeasiswa();
    });

    // Trigger search on enter
    $('#beasiswa-search, #beasiswa-jurusan').on('keypress', function(e) {
        if (e.which == 13) loadBeasiswa();
    });

    // Auto reload on select change
    $('#beasiswa-tipe, #beasiswa-jenis').on('change', function() {
        loadBeasiswa();
    });
});

// Load beasiswa
function loadBeasiswa(page = 1) {
    const search = $('#beasiswa-search').val();
    const tipe = $('#beasiswa-tipe').val();
    const jenis = $('#beasiswa-jenis').val();
    const jurusan = $('#beasiswa-jurusan').val();
    
    beasiswaCurrentPage = page;
    
    $('#beasiswa-loading').removeClass('d-none');
    $('#beasiswa-container').html('');
    $('#beasiswa-no-results').addClass('d-none');
    $('#beasiswa-pagination-container').html('');
    
    $.ajax({
        url: '/api/beasiswa',
        method: 'GET',
        data: {
            search: search,
            tipe_beasiswa: tipe,
            jenis_beasiswa: jenis,
            jurusan: jurusan,
            page: page
        },
        success: function(response) {
            $('#beasiswa-loading').addClass('d-none');
            
            if (response.data && response.data.data && response.data.data.length > 0) {
                renderBeasiswa(response.data.data, response.base_url);
                renderBeasiswaPagination(response.data);
            } else {
                $('#beasiswa-no-results').removeClass('d-none');
            }
        },
        error: function() {
            $('#beasiswa-loading').addClass('d-none');
            $('#beasiswa-container').html('<div class="col-12 text-center"><p class="text-danger">Failed to load scholarships</p></div>');
        }
    });
}

// Render beasiswa
function renderBeasiswa(beasiswas, baseUrl = null) {
    let html = '';
    
    beasiswas.forEach(b => {
        let poster = b.poster_beasiswa && b.poster_beasiswa.length > 0 ? b.poster_beasiswa[0].link_poster : null;
        const link = b.link_beasiswa ? b.link_beasiswa.link_beasiswa : '#';

        if (poster && !poster.startsWith('http')) {
            const apiBase = baseUrl || window.location.origin;
            poster = `${apiBase.replace(/\/$/, '')}/storage/posters/${poster}`;
        }
        
        const desc = b.deskripsi ? b.deskripsi : '';
        
        const benefitsHtml = b.benefit_beasiswa && b.benefit_beasiswa.length > 0 
            ? `<div class="mt-3">
                <h6 class="fw-bold small mb-2"><i class="bi bi-gift text-primary me-2"></i>Benefit</h6>
                <ul class="list-unstyled mb-0">
                    ${b.benefit_beasiswa.map(item => `<li class="small text-muted mb-1">• ${item.benefit}</li>`).join('')}
                </ul>
               </div>`
            : '';

        const conditionsHtml = b.syarat_beasiswa && b.syarat_beasiswa.length > 0 
            ? `<div class="mt-3">
                <h6 class="fw-bold small mb-2"><i class="bi bi-check-circle text-success me-2"></i>Syarat</h6>
                <ul class="list-unstyled mb-0">
                    ${b.syarat_beasiswa.map(item => `<li class="small text-muted mb-1">• ${item.syarat}</li>`).join('')}
                </ul>
               </div>`
            : '';

        const dateFormatted = b.updated_at ? new Date(b.updated_at).toLocaleDateString('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric'
        }) : '-';
        
        html += `
            <div class="col-12 fade-in mb-4">
                <div class="card h-100 shadow-sm beasiswa-card border-0 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-lg-4">
                            <div class="h-100 bg-light d-flex align-items-center justify-content-center overflow-hidden" style="min-height: 350px;">
                                ${poster ? 
                                    `<img src="${poster}" alt="${b.nama_beasiswa}" class="img-fluid w-100 h-100" style="object-fit: cover;" 
                                     onerror="this.parentElement.innerHTML='<div class=\\'h-100 w-100 bg-secondary d-flex align-items-center justify-content-center\\'><i class=\\'bi bi-mortarboard text-white\\' style=\\'font-size: 6rem;\\'></i></div>'">` :
                                    `<div class="h-100 w-100 bg-secondary d-flex align-items-center justify-content-center">
                                        <i class="bi bi-mortarboard text-white" style="font-size: 6rem;"></i>
                                     </div>`
                                }
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card-body p-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
                                    <div>
                                        <span class="badge bg-primary rounded-pill px-3 me-1">${b.tipe_beasiswa.toUpperCase()}</span>
                                        <span class="badge bg-info text-dark rounded-pill px-3">${b.jenis_beasiswa.toUpperCase()}</span>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="bi bi-clock-history me-1"></i>Terakhir diupdate: ${dateFormatted}
                                    </div>
                                </div>
                                
                                <h3 class="card-title fw-bold text-dark mb-2">${b.nama_beasiswa}</h3>
                                <p class="text-primary fw-bold mb-3"><i class="bi bi-building me-2"></i>${b.sumber || '-'}</p>
                                
                                <div class="card-text text-muted mb-4 text-justify">
                                    ${desc}
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        ${benefitsHtml}
                                        <div class="mt-3">
                                            <h6 class="fw-bold small mb-2"><i class="bi bi-people text-info me-2"></i>Kuota</h6>
                                            <p class="small text-muted mb-0">${b.kuota || '-'} Mahasiswa</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        ${conditionsHtml}
                                        <div class="mt-3">
                                            <h6 class="fw-bold small mb-2"><i class="bi bi-calendar-event text-danger me-2"></i>Batas Akhir</h6>
                                            <p class="small text-muted mb-0">${b.tanggal_berakhir || '-'}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top">
                                    <a href="${link}" target="_blank" class="btn btn-primary px-5 rounded-pill fw-bold">Daftar Sekarang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#beasiswa-container').html(html);
}

// Render Pagination for beasiswa
function renderBeasiswaPagination(paginationData) {
    if (paginationData.last_page <= 1) return;

    let html = '<nav aria-label="beasiswa pagination"><ul class="pagination pagination-primary mb-0">';
    
    // Previous
    html += `
        <li class="page-item ${paginationData.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="loadBeasiswa(${paginationData.current_page - 1})" aria-label="Previous">
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>
    `;

    // Pages
    for (let i = 1; i <= paginationData.last_page; i++) {
        if (i === 1 || i === paginationData.last_page || (i >= paginationData.current_page - 1 && i <= paginationData.current_page + 1)) {
            html += `
                <li class="page-item ${paginationData.current_page === i ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="loadBeasiswa(${i})">${i}</a>
                </li>
            `;
        } else if (i === paginationData.current_page - 2 || i === paginationData.current_page + 2) {
            html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    // Next
    html += `
        <li class="page-item ${paginationData.current_page === paginationData.last_page ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="loadBeasiswa(${paginationData.current_page + 1})" aria-label="Next">
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    `;

    html += '</ul></nav>';
    $('#beasiswa-pagination-container').html(html);
}
