<x-layout.app :title="$service->name">
    <!-- Page Header -->
    <section class="page-header bg-navy text-white py-5">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-white mb-3">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">{{ __('menu.home') }}</a></li>
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
            <p class="lead">{{ $service->excerpt }}</p>
            @endif
        </div>
    </section>

    <!-- Service Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            @if($service->content)
                            <div class="content">
                                {!! $service->content !!}
                            </div>
                            @else
                            <p class="text-muted">Detail layanan untuk {{ $service->name }} sedang dalam pembaruan.</p>
                            @endif

                            @if($service->cta_url && $service->cta_label)
                            <div class="mt-4 p-4 bg-light rounded d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">Akses Layanan Langsung</h5>
                                    <p class="text-muted mb-0">Klik tombol di samping untuk langsung mengakses platform layanan.</p>
                                </div>
                                <a href="{{ $service->cta_url }}" class="btn btn-orange btn-lg" target="_blank">
                                    {{ $service->cta_label }}
                                    <i class="bi bi-box-arrow-up-right ms-2"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Sub Services / Related Services -->
                    @if($service->children->count() > 0)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Sub Layanan</h5>
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach($service->children as $child)
                            <a href="{{ $child->cta_url ?: route('service.show', $child->slug) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3"
                                @if($child->cta_url) target="_blank" @endif>
                                <div>
                                    <div class="fw-bold">{{ $child->name }}</div>
                                    @if($child->excerpt)
                                    <small class="text-muted">{{ Str::limit($child->excerpt, 60) }}</small>
                                    @endif
                                </div>
                                @if($child->cta_url)
                                <i class="bi bi-box-arrow-up-right text-primary"></i>
                                @else
                                <i class="bi bi-chevron-right text-muted"></i>
                                @endif
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <!-- Documents & Downloads -->
                    @if($service->downloads->count() > 0)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Unduhan</h5>
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach($service->downloads as $download)
                            <a href="{{ asset('storage/' . $download->file_path) }}"
                                class="list-group-item list-group-item-action d-flex align-items-center py-3"
                                target="_blank">
                                <i class="bi bi-file-earmark-arrow-down text-primary fs-4 me-3"></i>
                                <div>
                                    <div class="fw-bold text-wrap">{{ $download->name }}</div>
                                    <small class="text-muted">Klik untuk mengunduh</small>
                                </div>
                                <i class="bi bi-download ms-auto text-muted"></i>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Quick Support Card -->
                    <div class="card bg-navy text-white border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-headset mb-3" style="font-size: 3rem;"></i>
                            <h5 class="card-title fw-bold">Butuh Bantuan?</h5>
                            <p class="card-text opacity-75">Hubungi unit layanan kemahasiswaan untuk informasi lebih lanjut.</p>
                            <a href="#" class="btn btn-outline-light w-100 mt-2">Hubungi Kami</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout.app>