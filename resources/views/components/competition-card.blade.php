@props(['competition'])

<div class="card h-100 shadow-sm hover-card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h5 class="card-title mb-0">{{ $competition['name'] }}</h5>
            @if($competition['is_external'])
                <x-external-link />
            @endif
        </div>
        
        @if($competition['category'])
            <span class="badge bg-secondary mb-2">{{ ucfirst($competition['category']) }}</span>
        @endif
        
        @if($competition['description'])
            <p class="card-text text-muted">{{ Str::limit($competition['description'], 150) }}</p>
        @endif
    </div>
    
    <div class="card-footer bg-transparent border-0">
        @if($competition['link'])
            <a href="{{ $competition['link'] }}" 
               target="{{ $competition['is_external'] ? '_blank' : '_self' }}" 
               class="btn btn-primary btn-sm w-100">
                {{ __('app.read_more') }}
                @if($competition['is_external'])
                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                @endif
            </a>
        @else
            <button class="btn btn-primary btn-sm w-100">
                {{ __('app.read_more') }}
            </button>
        @endif
    </div>
</div>
