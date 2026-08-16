<x-layout.app :title="$download->name . ' - ' . config('app.name')">
    <!-- Page Header -->
    <section class="page-header bg-navy text-white py-5">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-white mb-3">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">{{ __('menu.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('download.index') }}" class="text-white">{{ __('menu.documents') }}</a></li>
                    @if($download->category)
                    <li class="breadcrumb-item"><a href="{{ route('download.index', ['category' => $download->category->slug]) }}" class="text-white">{{ $download->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ $download->name }}</li>
                </ol>
            </nav>
            <h1 class="display-5 fw-bold">{{ $download->name }}</h1>
        </div>
    </section>

    <section class="py-5 bg-light min-vh-100">
        <div class="container">
            <div class="row g-4">
                <!-- Left: Preview -->
                <div class="col-lg-8">
                    @php
                    $isLink = $download->type === 'link';
                    $rawType = strtolower($download->file_type ?? '');
                    $pathExt = strtolower(pathinfo($download->file_path ?? '', PATHINFO_EXTENSION));

                    $isPdf = !$isLink && $download->file_path && ($pathExt === 'pdf' || str_contains($rawType, 'pdf'));
                    $isImage = !$isLink && $download->file_path && (in_array($pathExt, ['jpg', 'jpeg', 'png', 'svg', 'webp']) || str_contains($rawType, 'image/'));

                    $displayType = $pathExt ? strtoupper($pathExt) : ($rawType ? strtoupper(str_replace(['application/', 'image/'], '', $rawType)) : ($isLink ? 'LINK' : 'FILE'));
                    @endphp

                    @if($isLink)
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                        <div class="mb-3">
                            <div class="d-inline-flex p-4 rounded-circle bg-warning-subtle text-warning mb-3">
                                <i class="bi bi-link-45deg display-3"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-navy mb-2">Tautan Eksternal</h3>
                        <p class="text-muted mx-auto" style="max-width: 500px;">
                            Dokumen ini berada pada server/halaman luar. Klik tombol di sebelah kanan atau tombol di bawah untuk menuju ke dokumen target.
                        </p>
                        <div class="mt-3">
                            <a href="{{ $download->url }}" class="btn btn-primary rounded-pill px-4 py-2" target="_blank">
                                <i class="bi bi-box-arrow-up-right me-2"></i> Buka Tautan Dokumen
                            </a>
                        </div>
                    </div>
                    @elseif($isImage || $isPdf)
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-eye me-2"></i>Pratinjau Dokumen</h5>
                        </div>
                        <div class="card-body p-2 bg-light text-center border-top">
                            @if($isImage)
                            <img src="{{ asset('storage/' . $download->file_path) }}" alt="{{ $download->name }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 800px;">
                            @elseif($isPdf)
                            <div class="ratio ratio-4x3 rounded-3 shadow-sm overflow-hidden" style="min-height: 600px;">
                                <embed src="{{ asset('storage/' . $download->file_path) }}#toolbar=0" type="application/pdf">
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                        <i class="bi bi-file-earmark-binary display-1 text-muted mb-3"></i>
                        <h4 class="text-muted">Pratinjau tidak tersedia untuk tipe file ini</h4>
                        <p class="text-secondary">Silakan unduh dokumen untuk melihat isinya.</p>
                    </div>
                    @endif
                </div>

                <!-- Right: Metadata & Action -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 7rem; z-index: 10;">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">Informasi Dokumen</h4>

                            <div class="vstack gap-3 mb-4">
                                <div class="p-3 bg-light rounded-4 border-start border-primary border-4">
                                    <small class="text-muted d-block mb-1">Nama Dokumen</small>
                                    <span class="fw-bold text-navy">{{ $download->name }}</span>
                                </div>
                                <div class="p-3 bg-light rounded-4">
                                    <small class="text-muted d-block mb-1">Tanggal Diperbarui</small>
                                    <span class="fw-bold text-navy">{{ $download->updated_at->format('d F Y') }}</span>
                                </div>
                                <div class="p-3 bg-light rounded-4">
                                    <small class="text-muted d-block mb-1">Ukuran File</small>
                                    <span class="fw-bold text-navy">{{ $download->file_size ? number_format($download->file_size / 1024, 1) . ' KB' : ($isLink ? 'Link Eksternal' : '-') }}</span>
                                </div>
                                <div class="p-3 bg-light rounded-4">
                                    <small class="text-muted d-block mb-1">Tipe File</small>
                                    <span class="fw-bold text-navy">{{ $displayType }}</span>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ $download->url }}"
                                    class="btn btn-primary btn-lg rounded-pill shadow-sm"
                                    target="_blank">
                                    @if($isLink)
                                    <i class="bi bi-box-arrow-up-right me-2"></i> Buka Link
                                    @else
                                    <i class="bi bi-download me-2"></i> Unduh Sekarang
                                    @endif
                                </a>
                                @if($isPdf)
                                <a href="{{ asset('storage/' . $download->file_path) }}"
                                    class="btn btn-outline-navy btn-lg rounded-pill"
                                    target="_blank">
                                    <i class="bi bi-fullscreen me-2"></i> Mode Layar Penuh
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .bg-navy {
            background-color: #001f3f;
        }

        .text-navy {
            color: #001f3f;
        }

        .btn-outline-navy {
            color: #001f3f;
            border-color: #001f3f;
        }

        .btn-outline-navy:hover {
            background-color: #001f3f;
            color: #fff;
        }
    </style>
</x-layout.app>