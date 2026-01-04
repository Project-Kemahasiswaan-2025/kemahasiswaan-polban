<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">{{ __('app.site_name') }}</h5>
                <p class="text-white-50">
                    Direktorat Kemahasiswaan dan Alumni<br>
                    Politeknik Negeri Bandung
                </p>
            </div>
            
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">{{ __('menu.contact') }}</h5>
                <ul class="list-unstyled text-white-50">
                    <li class="mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        kemahasiswaan@polban.ac.id
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        (022) 1234567
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-geo-alt me-2"></i>
                        Jl. Gegerkalong Hilir, Bandung
                    </li>
                </ul>
            </div>
            
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">{{ __('menu.home') }}</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-white-50 text-decoration-none">{{ __('menu.home') }}</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('ormawa.index') }}" class="text-white-50 text-decoration-none">{{ __('menu.student_organizations') }}</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('competition.index') }}" class="text-white-50 text-decoration-none">{{ __('menu.competitions') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <hr class="border-secondary my-4">
        
        <div class="row">
            <div class="col-12 text-center text-white-50">
                <p class="mb-0">
                    &copy; {{ date('Y') }} {{ __('app.site_name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>
