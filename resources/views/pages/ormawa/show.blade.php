<x-layout.app :title="$organization->name">
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-white mb-3">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">{{ __('menu.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ormawa.index') }}" class="text-white">{{ __('menu.ormawa') }}</a></li>
                    @if($organization->parent)
                    <li class="breadcrumb-item"><a href="{{ route('ormawa.index', ['category' => $organization->parent->slug]) }}" class="text-white">{{ $organization->parent->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ $organization->name }}</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold">{{ $organization->name }}</h1>
            @if($organization->excerpt)
            <p class="lead">{{ $organization->excerpt }}</p>
            @endif
        </div>
    </section>

    <!-- Organization Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                @if($organization->logo || $organization->cover_image)
                <div class="col-lg-4 mb-4">
                    @if($organization->logo)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body text-center p-4">
                            <img src="{{ Storage::url($organization->logo) }}" alt="{{ $organization->name }}" class="img-fluid" style="max-height: 300px;">
                        </div>
                    </div>
                    @endif

                    @if($organization->cover_image)
                    <div class="card shadow-sm">
                        <img src="{{ Storage::url($organization->cover_image) }}" alt="{{ $organization->name }}" class="card-img-top">
                    </div>
                    @endif
                </div>
                @endif

                <div class="{{ ($organization->logo || $organization->cover_image) ? 'col-lg-8' : 'col-12' }}">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @if($organization->content)
                            <div class="content">
                                {!! $organization->content !!}
                            </div>
                            @else
                            <p class="text-muted">{{ __('landing.ormawa.no_content', ['name' => $organization->name]) }}</p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Child Organizations (if any) -->
    @if($organization->is_group && $organization->children->count() > 0)
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="mb-4">{{ __('landing.ormawa.org_list_heading', ['name' => $organization->name]) }}</h2>
            <div class="row g-4">
                @foreach($organization->children()->where('is_active', true)->orderBy('sort_order')->get() as $child)
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm hover-card">
                        @if($child->logo)
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <img src="{{ Storage::url($child->logo) }}" alt="{{ $child->name }}" class="img-fluid" style="max-height: 180px;">
                        </div>
                        @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-building text-white" style="font-size: 4rem;"></i>
                        </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $child->name }}</h5>
                            @if($child->excerpt)
                            <p class="card-text text-muted">{{ Str::limit($child->excerpt, 100) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</x-layout.app>