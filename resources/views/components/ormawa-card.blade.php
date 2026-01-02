@props(['organization'])

<div class="card h-100 shadow-sm hover-card">
    @if($organization['logo'])
        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
            <img src="{{ $organization['logo'] }}" alt="{{ $organization['name'] }}" class="img-fluid" style="max-height: 180px;">
        </div>
    @else
        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
            <i class="bi bi-building text-white" style="font-size: 4rem;"></i>
        </div>
    @endif
    
    <div class="card-body">
        <h5 class="card-title">{{ $organization['name'] }}</h5>
        @if($organization['description'])
            <p class="card-text text-muted">{{ Str::limit($organization['description'], 100) }}</p>
        @endif
    </div>
    
    <div class="card-footer bg-transparent border-0">
        <button class="btn btn-primary btn-sm w-100">
            {{ __('app.read_more') }}
        </button>
    </div>
</div>
