<x-layout.app :title="$beasiswa['nama_beasiswa'] . ' - ' . __('menu.scholarships')">
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('beasiswa.index') }}" class="text-white">{{ __('menu.scholarships') }}</a></li>
                    <li class="breadcrumb-item active text-white opacity-75" aria-current="page">Detail</li>
                </ol>
            </nav>
            <h1 class="display-5 fw-bold">{{ $beasiswa['nama_beasiswa'] }}</h1>
            <p class="lead mb-0"><i class="bi bi-building me-2"></i>{{ $beasiswa['sumber'] ?? '-' }}</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Sidebar Info -->
                <div class="col-lg-4">
                    <div class="card shadow-sm sticky-top" style="top: 6rem; z-index: 10;">
                        <div class="card-body p-4">
                            @php
                            $poster = $beasiswa['poster_beasiswa'][0]['link_poster'] ?? null;
                            @endphp

                            @if($poster)
                            <div class="mb-4 rounded-3 overflow-hidden shadow-sm">
                                <img src="{{ Str::startsWith($poster, 'http') ? $poster : asset('storage/posters/' . $poster) }}"
                                    class="img-fluid w-100" alt="Poster Beasiswa"
                                    onerror="this.parentElement.innerHTML='<div class=\'bg-secondary py-5 text-center\'><i class=\'bi bi-mortarboard text-white display-1\'></i></div>'">
                            </div>
                            @endif

                            <div class="mb-4">
                                <label class="small text-muted d-block mb-1">Tipe</label>
                                <span class="badge bg-primary rounded-pill px-3">{{ strtoupper($beasiswa['tipe_beasiswa']) }}</span>
                            </div>

                            <div class="mb-4">
                                <label class="small text-muted d-block mb-1">Jenis</label>
                                <span class="badge bg-info text-dark rounded-pill px-3">{{ strtoupper($beasiswa['jenis_beasiswa']) }}</span>
                            </div>

                            <div class="mb-4">
                                <label class="small text-muted d-block mb-1">Kuota</label>
                                <p class="fw-bold text-dark mb-0"><i class="bi bi-people me-2"></i>{{ $beasiswa['kuota'] ?? '-' }} Mahasiswa</p>
                            </div>
                            <div class="mb-4">
                                <label class="small text-muted d-block mb-1">Sumber Beasiswa</label>
                                <p class="fw-bold text-dark mb-0"><i class="bi bi-building me-2"></i>{{ $beasiswa['sumber'] ?? '-' }}</p>
                            </div>

                            <div class="mb-4">
                                <label class="small text-muted d-block mb-1">Batas Akhir Pendaftaran</label>
                                <p class="fw-bold text-danger mb-0">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    {{ format_date($beasiswa['tanggal_berakhir']) }}
                                </p>
                            </div>

                            <div class="d-grid gap-2">
                                @if(isset($beasiswa['link_beasiswa']['link_beasiswa']) && $beasiswa['link_beasiswa']['link_beasiswa'] !== '#')
                                <a href="{{ $beasiswa['link_beasiswa']['link_beasiswa'] }}" target="_blank" class="btn btn-primary rounded-pill py-2 fw-bold">
                                    Daftar Sekarang <i class="bi bi-box-arrow-up-right ms-2"></i>
                                </a>
                                @endif
                                <a href="{{ route('beasiswa.index') }}" class="btn btn-outline-secondary rounded-pill py-2">
                                    <i class="bi bi-arrow-left me-2"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body px-4 px-md-5">
                            <h4 class="fw-bold mb-4 border-bottom pb-3">{{ $beasiswa['nama_beasiswa'] }}</h4>
                            <div class="text-muted lh-lg mb-3 text-justify">
                                {!! nl2br(e($beasiswa['deskripsi'])) !!}
                            </div>

                            <div class="row g-4 mb-5">
                                @if(!empty($beasiswa['benefit_beasiswa']))
                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-3"><i class="bi bi-gift text-primary me-2"></i>Benefit</h5>
                                    <ul class="list-group list-group-flush">
                                        @foreach($beasiswa['benefit_beasiswa'] as $benefit)
                                        <li class="list-group-item border-0 px-0 py-2">
                                            <i class="bi bi-check2-circle text-primary me-2"></i>{{ $benefit['benefit'] }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                @if(!empty($beasiswa['syarat_beasiswa']))
                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-3"><i class="bi bi-check-circle text-success me-2"></i>Syarat</h5>
                                    <ul class="list-group list-group-flush">
                                        @foreach($beasiswa['syarat_beasiswa'] as $syarat)
                                        <li class="list-group-item border-0 px-0 py-2">
                                            <i class="bi bi-dot text-success me-2"></i>{{ $syarat['syarat'] }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>

                            @if(!empty($beasiswa['syarat_dokumen']))
                            <div class="mb-5">
                                <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-text text-info me-2"></i>Dokumen Diperlukan</h5>
                                <div class="row g-3">
                                    @foreach($beasiswa['syarat_dokumen'] as $doc)
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 d-flex align-items-center bg-light">
                                            <i class="bi bi-file-earmark-arrow-down fs-4 text-info me-3"></i>
                                            <span class="small fw-medium">{{ $doc['dokumen'] }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Recipients Section -->
                            <div id="penerima-section" class="mt-5 pt-4 border-top d-none">
                                <h5 class="fw-bold mb-4"><i class="bi bi-people-fill text-primary me-2"></i>Penerima Beasiswa</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0">NIM</th>
                                                <th class="border-0">Nama Mahasiswa</th>
                                                <th class="border-0">Prodi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="penerima-list">
                                            <!-- Data loaded via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Fetch recipients dynamically
            const beasiswaId = '{{ $beasiswa["id"] }}';
            $.get(`/api/beasiswa/${beasiswaId}/penerima`, function(response) {
                if (response.success && response.data && response.data.penerima && response.data.penerima.length > 0) {
                    $('#penerima-section').removeClass('d-none');
                    let rows = '';
                    response.data.penerima.forEach(p => {
                        rows += `
                            <tr>
                                <td class="small fw-bold">${p.nim}</td>
                                <td class="small">${p.nama_mahasiswa || '-'}</td>
                                <td class="small text-muted">${p.nama_prodi}</td>
                            </tr>
                        `;
                    });
                    $('#penerima-list').html(rows);
                }
            }).fail(function() {
                console.log("Failed to fetch recipients data.");
            });
        });
    </script>
    @endpush
</x-layout.app>