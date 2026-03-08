{{-- resources/views/services/show.blade.php (contoh path) --}}
<x-layout.app :title="$service->name">
    <!-- Page Header -->
    <section class="page-header bg-navy text-white py-5">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-white mb-3">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-white">{{ __('menu.home') }}</a>
                    </li>
                    <li class="breadcrumb-item active text-white" aria-current="page">
                        {{ __('menu.services') }}
                    </li>
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ $service->name }}</li>
                </ol>
            </nav>

            <div class="d-flex align-items-center mb-3">
                @if($service->icon)
                <i class="bi {{ $service->icon }} me-3" style="font-size: 3rem;"></i>
                @endif
                <h1 class="display-4 fw-bold mb-0">{{ $service->name }}</h1>
            </div>

            @if($service->excerpt)
            <p class="lead mb-0">{{ $service->excerpt }}</p>
            @endif
        </div>
    </section>

    <!-- Service Content -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            @if($service->content)
                            <div class="content">
                                {!! $service->content !!}
                            </div>
                            @else
                            <p class="text-muted mb-0">Detail untuk layanan ini sedang kami lengkapi.</p>
                            @endif

                            @if($service->cta_url && $service->cta_label)
                            <div class="mt-4 p-4 rounded border bg-light">
                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                    <div>
                                        <div class="fw-bold fs-5 mb-1">Lanjut ke Layanan</div>
                                        <div class="text-muted">
                                            Layanan ini diakses melalui halaman eksternal. Klik tombol untuk membuka di tab baru.
                                        </div>
                                    </div>

                                    <a
                                        href="{{ $service->cta_url }}"
                                        class="btn btn-outline-primary btn-lg d-inline-flex align-items-center justify-content-center text-nowrap flex-shrink-0"
                                        target="_blank"
                                        rel="noopener">
                                        {{ $service->cta_label }}
                                        <i class="bi bi-box-arrow-up-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Tautan Lanjutan --}}
                    @if($service->links->count() > 0)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Tautan Lanjutan</h5>
                        </div>

                        <div class="list-group list-group-flush">
                            @foreach($service->links as $link)
                            @php
                            $href = $link->url;
                            @endphp

                            <a
                                href="{{ $href }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3"
                                target="_blank" rel="noopener">
                                <div class="pe-3">
                                    <div class="fw-bold">{{ $link->name }}</div>
                                    @if($link->description)
                                    <small class="text-muted">{{ Str::limit($link->description, 60) }}</small>
                                    @endif
                                </div>

                                <i class="bi bi-box-arrow-up-right text-primary"></i>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Dokumen & Unduhan --}}
                    @if($service->downloads->count() > 0)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Dokumen & Unduhan</h5>
                        </div>

                        <div class="list-group list-group-flush">
                            @foreach($service->downloads as $download)
                            <a
                                href="{{ asset('storage/' . $download->file_path) }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                target="_blank"
                                rel="noopener">
                                <i class="bi bi-file-earmark-arrow-down text-primary fs-4 me-3"></i>

                                <div class="flex-grow-1">
                                    <div class="fw-bold text-wrap">{{ $download->name }}</div>
                                    <small class="text-muted">Buka / unduh dokumen</small>
                                </div>

                                <i class="bi bi-download ms-3 text-muted"></i>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Bantuan --}}
                    <div class="card bg-navy text-white border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-headset mb-3" style="font-size: 3rem;"></i>
                            <h5 class="card-title fw-bold">Butuh Bantuan?</h5>
                            <p class="card-text opacity-75 mb-3">
                                Hubungi unit terkait untuk informasi lebih lanjut tentang layanan ini.
                            </p>
                            <a href="#" class="btn btn-outline-light w-100">Hubungi Kami</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout.app>