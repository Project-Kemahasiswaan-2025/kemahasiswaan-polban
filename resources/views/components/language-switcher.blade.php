<div class="dropdown">
    <button class="btn btn-link nav-link dropdown-toggle p-0" type="button" data-bs-toggle="dropdown">
        <i class="bi bi-globe"></i>
        {{ strtoupper(app()->getLocale()) }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item {{ app()->getLocale() === 'id' ? 'active' : '' }}" href="{{ route('lang.switch', 'id') }}">
                🇮🇩 Bahasa Indonesia
            </a>
        </li>
        <li>
            <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">
                🇬🇧 English
            </a>
        </li>
    </ul>
</div>
