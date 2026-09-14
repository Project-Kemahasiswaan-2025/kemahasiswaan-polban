<x-layout.app :title="$selectedCategory ? $selectedCategory->name : __('menu.student_organizations')">
    <!-- Page Header -->
    <section class="page-header bg-navy text-white py-5">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-white mb-3">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-white text-decoration-none opacity-75 hover-opacity-100">
                            <i class="bi bi-house-door me-1"></i>{{ __('menu.home') }}
                        </a>
                    </li>
                    @if($selectedCategory)
                    <li class="breadcrumb-item">
                        <a href="{{ route('ormawa.index') }}" class="text-white text-decoration-none opacity-75 hover-opacity-100">
                            {{ __('menu.student_organizations') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">{{ $selectedCategory->name }}</li>
                    @else
                    <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">{{ __('menu.student_organizations') }}</li>
                    @endif
                </ol>
            </nav>
            <h1 class="display-5 fw-bold mb-2">
                {{ $selectedCategory ? $selectedCategory->name : __('menu.student_organizations') }}
            </h1>
            <p class="lead opacity-90 mb-0">
                {{ $selectedCategory ? ($selectedCategory->excerpt ?: __('landing.ormawa.subtitle')) : __('landing.ormawa.subtitle') }}
            </p>
        </div>
    </section>

    <!-- Filter and Search Section -->
    <section class="py-4 bg-light border-bottom">
        <div class="container">
            <form method="GET" action="{{ route('ormawa.index') }}" id="filterForm">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group search-box shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   id="search-input"
                                   class="form-control border-start-0 ps-0 shadow-none"
                                   placeholder="Cari nama organisasi..."
                                   value="{{ $search }}">
                            @if($search)
                            <a href="{{ route('ormawa.index', array_filter(['category' => request('category')])) }}" class="btn btn-outline-secondary border-start-0" title="Hapus pencarian">
                                <i class="bi bi-x-lg"></i>
                            </a>
                            @endif
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <select class="form-select shadow-sm" name="category" id="category-filter" onchange="document.getElementById('filterForm').submit()">
                                <option value="">{{ __('app.all') }} {{ __('menu.student_organizations') }} (Kategori Induk)</option>
                                @foreach($parentGroups as $group)
                                <option value="{{ $group->slug }}" {{ $selectedCategory && $selectedCategory->slug === $group->slug ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                                @endforeach
                            </select>
                            @if($selectedCategory || $search)
                            <a href="{{ route('ormawa.index') }}" class="btn btn-outline-secondary text-nowrap d-flex align-items-center shadow-sm" title="Reset Semua Filter">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Organizations List Section -->
    <section class="py-5 bg-white min-vh-50">
        <div class="container">
            <!-- Mode Info Banner -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom gap-2">
                <div>
                    <h4 class="fw-bold mb-1">
                        @if($selectedCategory)
                            Daftar {{ $selectedCategory->name }}
                        @else
                            Organisasi & Kategori Induk
                        @endif
                    </h4>
                    <span class="text-muted small">
                        Menampilkan <strong>{{ $organizations->count() }}</strong> organisasi
                        @if($search) dengan kata kunci "<em>{{ $search }}</em>"@endif
                    </span>
                </div>

                @if($selectedCategory)
                <a href="{{ route('ormawa.index') }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Semua Kategori Induk
                </a>
                @endif
            </div>

            <!-- Organizations Grid -->
            <div class="row g-4">
                @forelse($organizations as $org)
                    @if(!$selectedCategory && $org->is_group)
                        {{-- Mode 1: Parent Group Organization Card (HMJ / UKM) --}}
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm hover-card border-primary-subtle rounded-3 overflow-hidden">
                                <div class="card-header bg-primary-subtle border-0 py-2 px-3 d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 small">
                                        <i class="bi bi-collection-fill me-1"></i> Kategori Induk
                                    </span>
                                    <span class="text-primary small fw-semibold">
                                        {{ $org->children()->where('is_active', true)->count() }} Organisasi
                                    </span>
                                </div>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center p-4" style="height: 180px;">
                                    @if($org->logo)
                                        <img src="{{ Storage::url($org->logo) }}" alt="{{ $org->name }}" class="img-fluid" style="max-height: 140px; object-fit: contain;">
                                    @else
                                        <div class="text-center text-primary">
                                            <i class="bi bi-people-fill" style="font-size: 3.8rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold text-navy mb-2">{{ $org->name }}</h5>
                                    @if($org->excerpt)
                                        <p class="card-text text-muted small flex-grow-1">
                                            {{ Str::limit($org->excerpt, 120) }}
                                        </p>
                                    @else
                                        <p class="card-text text-muted small flex-grow-1">
                                            Kumpulan organisasi dan himpunan mahasiswa di bawah naungan {{ $org->name }}.
                                        </p>
                                    @endif
                                    <div class="mt-3 pt-3 border-top">
                                        <a href="{{ route('ormawa.index', ['category' => $org->slug]) }}" class="btn btn-primary w-100 fw-semibold d-flex align-items-center justify-content-center">
                                            <i class="bi bi-grid-fill me-2"></i> Lihat Daftar {{ $org->name }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Mode 2 (Children Organization) OR Mode 1 Standalone (MPM, BEM) --}}
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 shadow-sm hover-card border-0 rounded-3 overflow-hidden">
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center p-3" style="height: 170px;">
                                    @if($org->logo)
                                        <img src="{{ Storage::url($org->logo) }}" alt="{{ $org->name }}" class="img-fluid" style="max-height: 130px; object-fit: contain;">
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="bi bi-building" style="font-size: 3.2rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column p-3">
                                    <div class="mb-1">
                                        @if($org->parent)
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill small" style="font-size: 0.72rem;">
                                                {{ $org->parent->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small" style="font-size: 0.72rem;">
                                                Organisasi Kampus
                                            </span>
                                        @endif
                                    </div>
                                    <h6 class="card-title fw-bold text-navy mt-1 mb-2">{{ $org->name }}</h6>
                                    @if($org->excerpt)
                                        <p class="card-text text-muted small flex-grow-1" style="font-size: 0.83rem;">
                                            {{ Str::limit($org->excerpt, 90) }}
                                        </p>
                                    @else
                                        <p class="card-text text-muted small flex-grow-1" style="font-size: 0.83rem;">
                                            Informasi profil dan kegiatan {{ $org->name }} POLBAN.
                                        </p>
                                    @endif
                                    <div class="mt-3 pt-2 border-top">
                                        <a href="{{ route('ormawa.show', $org->slug) }}" class="btn btn-outline-primary btn-sm w-100 fw-semibold d-flex align-items-center justify-content-center">
                                            <i class="bi bi-arrow-right-circle me-1.5"></i> Lihat Profil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3 text-muted">
                            <i class="bi bi-search" style="font-size: 3.5rem;"></i>
                        </div>
                        <h5 class="fw-bold text-secondary">{{ __('app.no_results') }}</h5>
                        <p class="text-muted">Tidak ada data organisasi mahasiswa yang sesuai dengan kriteria.</p>
                        <a href="{{ route('ormawa.index') }}" class="btn btn-primary btn-sm px-3 mt-1">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Kembali ke Semua Organisasi
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layout.app>