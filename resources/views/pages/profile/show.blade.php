<x-layout.app :title="$page->title">
    <section class="py-5">
        <div class="container">
            <h1 class="mb-4 fw-bold">{{ $page->title }}</h1>

            @if($page->document_path)
                @php
                    $url = asset('storage/' . $page->document_path);
                    $ext = strtolower(pathinfo($page->document_path, PATHINFO_EXTENSION));
                @endphp

                @if($ext === 'pdf')
                    <div class="ratio ratio-16x9 mb-4">
                        <iframe src="{{ $url }}" title="{{ $page->title }}" style="border:0;" allowfullscreen></iframe>
                    </div>
                @else
                    <img src="{{ $url }}" class="img-fluid rounded mb-4" alt="{{ $page->title }}">
                @endif
            @endif

            @if($page->content)
                <div class="page-content">
                    {!! $page->content !!}
                </div>
            @else
                @if(!$page->document_path)
                    <p class="text-muted">Konten belum tersedia.</p>
                @endif
            @endif
        </div>
    </section>
</x-layout.app>
