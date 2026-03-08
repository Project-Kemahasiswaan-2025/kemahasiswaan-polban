// scholarship State
let beasiswaCurrentPage = 1;

$(document).ready(function() {
    loadBeasiswa();

    // Auto reload on filter change
    $('#beasiswa-tipe, #beasiswa-jenis, #beasiswa-status').on('change', function() {
        loadBeasiswa();
    });

    // Auto trigger on input with debounce
    $('#beasiswa-search, #beasiswa-jurusan').on('input', debounce(function() {
        loadBeasiswa();
    }, 500));
});

// Debounce helper
function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

// Helper to format date in JS
function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    } catch (e) {
        return dateString;
    }
}

// Load beasiswa
function loadBeasiswa(page = 1) {
    const search = $('#beasiswa-search').val();
    const tipe = $('#beasiswa-tipe').val();
    const jenis = $('#beasiswa-jenis').val();
    const status = $('#beasiswa-status').val();
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
            status: status,
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
        error: function(xhr) {
            $('#beasiswa-loading').addClass('d-none');
            const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal mengambil data beasiswa';
            $('#beasiswa-container').html(`<div class="col-12 text-center py-5"><i class="bi bi-exclamation-triangle display-1 text-warning mb-4 d-block"></i><p class="text-muted fw-bold">${message}</p></div>`);
        }
    });
}

// Render beasiswa
function renderBeasiswa(beasiswas, baseUrl = null) {
    const container = $('#beasiswa-container');
    container.empty();
    
    // Get template
    const template = document.getElementById('beasiswa-card-template');
    if (!template) {
        console.error('Template list beasiswa tidak ditemukan!');
        return;
    }

    const today = new Date().setHours(0, 0, 0, 0);

    beasiswas.forEach(b => {
        // Clone template content
        const clone = template.content.cloneNode(true);
        const $card = $(clone);

        // Poster handling
        let poster = b.poster_beasiswa && b.poster_beasiswa.length > 0 ? b.poster_beasiswa[0].link_poster : null;
        if (poster && !poster.startsWith('http')) {
            const apiBase = baseUrl || window.location.origin;
            poster = `${apiBase.replace(/\/$/, '')}/storage/posters/${poster}`;
        }

        const $posterImg = $card.find('.beasiswa-poster');
        const $posterFallback = $card.find('.beasiswa-poster-fallback');
        
        if (poster) {
            $posterImg.attr('src', poster).removeClass('d-none');
            $posterFallback.addClass('d-none');
            $posterImg.on('error', function() {
                $(this).addClass('d-none');
                $posterFallback.removeClass('d-none');
            });
        } else {
            $posterImg.addClass('d-none');
            $posterFallback.removeClass('d-none');
        }

        // Status Badge
        let statusClass = 'bg-primary';
        let statusLabel = b.status_beasiswa || 'Berjalan';
        if (statusLabel === 'berjalan') statusClass = 'bg-success';
        else if (statusLabel === 'akan-datang') statusClass = 'bg-warning text-dark';
        else if (statusLabel === 'selesai') statusClass = 'bg-gray-dark';
        
        $card.find('.beasiswa-status-badge')
            .addClass(statusClass)
            .text(statusLabel.charAt(0).toUpperCase() + statusLabel.slice(1).replace('-', ' '));

        // Tipe & Jenis Badges
        $card.find('.beasiswa-tipe-badge').text(b.tipe_beasiswa ? b.tipe_beasiswa.toUpperCase() : '-');
        $card.find('.beasiswa-jenis-badge').text(b.jenis_beasiswa ? b.jenis_beasiswa.toUpperCase() : '-');

        // Content
        $card.find('.beasiswa-title').text(b.nama_beasiswa);
        $card.find('.beasiswa-sumber').text(b.sumber || '-');
        $card.find('.beasiswa-deadline').text(formatDate(b.tanggal_berakhir));
        
        const desc = b.deskripsi ? (b.deskripsi.length > 150 ? b.deskripsi.substring(0, 150) + '...' : b.deskripsi) : 'Tidak ada deskripsi';
        $card.find('.beasiswa-description').text(desc);

        // Registration Button Logic
        const deadline = b.tanggal_berakhir ? new Date(b.tanggal_berakhir) : null;
        const isClosed = statusLabel === 'selesai' || (deadline && deadline.getTime() < today);
        const regUrl = b.link_beasiswa ? b.link_beasiswa.link_beasiswa : null;

        if (!isClosed && regUrl) {
            $card.find('.beasiswa-register-link').attr('href', regUrl).removeClass('d-none');
        }

        // Link Detail
        $card.find('.beasiswa-detail-link').attr('href', `/beasiswa/${b.id}`);

        // Append to container
        container.append($card);
    });
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
