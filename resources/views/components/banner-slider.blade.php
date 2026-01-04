@props(['banners' => []])

<div id="bannerCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach($banners as $index => $banner)
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $index }}" 
                    class="{{ $index === 0 ? 'active' : '' }}"></button>
        @endforeach
    </div>
    
    <div class="carousel-inner">
        @forelse($banners as $index => $banner)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                @if($banner['link'])
                    <a href="{{ $banner['link'] }}" class="d-block">
                        <img src="{{ $banner['image_url'] }}" class="d-block w-100" alt="{{ $banner['title'] }}">
                    </a>
                @else
                    <img src="{{ $banner['image_url'] }}" class="d-block w-100" alt="{{ $banner['title'] }}">
                @endif
                <div class="carousel-caption d-none d-md-block">
                    <h5>{{ $banner['title'] }}</h5>
                </div>
            </div>
        @empty
            <div class="carousel-item active">
                <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 500px;">
                    <p class="text-white">{{ __('app.loading') }}</p>
                </div>
            </div>
        @endforelse
    </div>
    
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
