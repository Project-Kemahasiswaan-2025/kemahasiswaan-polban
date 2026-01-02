@props(['placeholder' => __('app.search_placeholder')])

<div class="search-box">
    <div class="input-group">
        <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-search"></i>
        </span>
        <input type="text" 
               class="form-control border-start-0" 
               placeholder="{{ $placeholder }}" 
               {{ $attributes }}>
    </div>
</div>
