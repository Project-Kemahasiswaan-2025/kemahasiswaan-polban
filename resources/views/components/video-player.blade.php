@props(['video'])

<div class="ratio ratio-16x9">
    @if(str_contains($video['video_url'], 'youtube.com') || str_contains($video['video_url'], 'youtu.be'))
        @php
            // Extract YouTube video ID
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $video['video_url'], $matches);
            $videoId = $matches[1] ?? '';
        @endphp
        @if($videoId)
            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
                    title="{{ $video['title'] }}" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
            </iframe>
        @else
            <div class="bg-secondary d-flex align-items-center justify-content-center">
                <p class="text-white">Invalid video URL</p>
            </div>
        @endif
    @else
        <div class="bg-secondary d-flex align-items-center justify-content-center">
            <p class="text-white">{{ $video['title'] }}</p>
        </div>
    @endif
</div>
<h5 class="mt-2">{{ $video['title'] }}</h5>
