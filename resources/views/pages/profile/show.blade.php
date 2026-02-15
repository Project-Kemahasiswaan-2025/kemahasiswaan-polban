<x-layout.app :title="$page->title">
    <!-- Page Header -->
    <section class="page-header bg-navy text-white py-5">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-white mb-3">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">{{ __('menu.home') }}</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ $page->title }}</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold mb-0">{{ $page->title }}</h1>
        </div>
    </section>

    <!-- Page Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-md-5">
                            @if($page->document_path)
                            @php
                            $url = asset('storage/' . $page->document_path);
                            $ext = strtolower(pathinfo($page->document_path, PATHINFO_EXTENSION));
                            @endphp

                            @if($ext === 'pdf')
                            <div class="ratio ratio-16x9 mb-5 shadow-sm rounded overflow-hidden">
                                <iframe src="{{ $url }}" title="{{ $page->title }}" style="border:0;" allowfullscreen></iframe>
                            </div>
                            @else
                            <img src="{{ $url }}" class="img-fluid rounded mb-5 shadow-sm" alt="{{ $page->title }}">
                            @endif
                            @endif

                            @if($page->content)
                            <div class="page-content">
                                {!! $page->content !!}
                            </div>
                            @else
                            @if(!$page->document_path)
                            <div class="text-center py-5">
                                <i class="bi bi-file-earmark-text text-muted mb-3" style="font-size: 3rem; display: block;"></i>
                                <p class="text-muted lead">Konten untuk halaman ini belum tersedia.</p>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout.app>