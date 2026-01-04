<nav class="navbar navbar-expand-lg navbar-dark bg-navy sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            {{ __('app.site_name') }}
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        {{ __('menu.home') }}
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        {{ __('menu.profile') }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">{{ __('menu.about') }}</a></li>
                        <li><a class="dropdown-item" href="#">{{ __('menu.organizational_structure') }}</a></li>
                        <li><a class="dropdown-item" href="#">{{ __('menu.regulations') }}</a></li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        {{ __('menu.services') }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">{{ __('menu.counseling') }}</a></li>
                        <li><a class="dropdown-item" href="#">{{ __('menu.insurance') }}</a></li>
                        <li><a class="dropdown-item" href="#">{{ __('menu.career') }}</a></li>
                        <li><a class="dropdown-item" href="#">{{ __('menu.facilities') }}</a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ __('menu.scholarship') }}</a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('ormawa.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                        {{ __('menu.ormawa') }}
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($ormawaGroups as $group)
                            @if($group->is_group)
                                <li><a class="dropdown-item" href="{{ route('ormawa.index', ['category' => $group->slug]) }}">{{ $group->name }}</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('ormawa.show', $group->slug) }}">{{ $group->name }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('competition.index') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                        {{ __('menu.achievements') }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('competition.index') }}">{{ __('menu.competitions') }}</a></li>
                        <li><a class="dropdown-item" href="#">{{ __('menu.achievements_form') }}</a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ __('menu.documents') }}</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ __('menu.contact') }}</a>
                </li>
                
                <li class="nav-item">
                    <x-language-switcher />
                </li>
            </ul>
        </div>
    </div>
</nav>
