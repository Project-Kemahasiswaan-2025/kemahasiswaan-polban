<nav class="navbar navbar-expand-lg navbar-dark bg-navy sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Polban Logo" height="50" class="me-3">
            <div class="d-flex flex-column">
                <span class="fw-bold text-white" style="font-size: 1.2rem; line-height: 1.2;">Kemahasiswaan</span>
                <span class="text-orange" style="font-size: 0.8rem; line-height: 1.2;">Politeknik Negeri Bandung</span>
            </div>
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
                        @isset($profilePages)
                        @forelse($profilePages as $p)
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show', $p->slug) }}">
                                {{ $p->title }}
                            </a>
                        </li>
                        @empty
                        <li><span class="dropdown-item text-muted">Belum ada halaman</span></li>
                        @endforelse
                        @else
                        <li><span class="dropdown-item text-muted">Belum ada halaman</span></li>
                        @endisset
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        {{ __('menu.services') }}
                    </a>
                    <ul class="dropdown-menu">
                        @isset($services)
                        @forelse($services as $s)
                        <li>
                            <a class="dropdown-item" href="{{ route('service.show', $s->slug) }}">
                                {{ $s->name }}
                            </a>
                        </li>
                        @empty
                        <li><span class="dropdown-item text-muted">Belum ada layanan</span></li>
                        @endforelse
                        @else
                        <li><span class="dropdown-item text-muted">Belum ada layanan</span></li>
                        @endisset
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('beasiswa.index') ? 'active' : '' }}" href="{{ route('beasiswa.index') }}">{{ __('menu.scholarship') }}</a>
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
                    <a class="nav-link {{ request()->routeIs('download.index') ? 'active' : '' }}" href="{{ route('download.index') }}">{{ __('menu.documents') }}</a>
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