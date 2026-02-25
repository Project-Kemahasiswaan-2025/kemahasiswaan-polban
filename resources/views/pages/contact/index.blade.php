<x-layout.app :title="__('contact.title')">
    <style>
        @media (max-width: 991.98px) {
            .sticky-top {
                margin-bottom: 0 !important;
            }
        }
    </style>
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">{{ __('contact.title') }}</h1>
            <p class="lead">{{ __('contact.subtitle') }}</p>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-5">
                <!-- Contact Info -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <h3 class="fw-bold mb-4">{{ __('contact.info_title') }}</h3>

                        <div class="d-flex mb-4">
                            <div class="bg-primary-subtle text-primary rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-geo-alt-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">{{ __('contact.address') }}</h6>
                                <p class="text-muted small mb-0">{{ $contactSettings->address }}</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="bg-success-subtle text-success rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-envelope-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">{{ __('contact.email') }}</h6>
                                <p class="text-muted small mb-0">{{ $contactSettings->email }}</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="bg-info-subtle text-info rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-telephone-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">{{ __('contact.phone') }}</h6>
                                <p class="text-muted small mb-0">{{ $contactSettings->phone }}</p>
                            </div>
                        </div>

                        @php
                        $hasSocials = ($contactSettings->instagram_url && $contactSettings->instagram_url !== '#') ||
                        ($contactSettings->facebook_url && $contactSettings->facebook_url !== '#') ||
                        ($contactSettings->twitter_url && $contactSettings->twitter_url !== '#') ||
                        ($contactSettings->youtube_url && $contactSettings->youtube_url !== '#');
                        @endphp
                        @if($hasSocials)
                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">{{ __('contact.social_media') }}</h6>
                        <div class="d-flex gap-2">
                            @if($contactSettings->instagram_url && $contactSettings->instagram_url !== '#')
                            <a href="{{ $contactSettings->instagram_url }}" target="_blank" class="btn btn-outline-primary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-instagram"></i></a>
                            @endif
                            @if($contactSettings->facebook_url && $contactSettings->facebook_url !== '#')
                            <a href="{{ $contactSettings->facebook_url }}" target="_blank" class="btn btn-outline-primary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-facebook"></i></a>
                            @endif
                            @if($contactSettings->twitter_url && $contactSettings->twitter_url !== '#')
                            <a href="{{ $contactSettings->twitter_url }}" target="_blank" class="btn btn-outline-primary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-twitter-x"></i></a>
                            @endif
                            @if($contactSettings->youtube_url && $contactSettings->youtube_url !== '#')
                            <a href="{{ $contactSettings->youtube_url }}" target="_blank" class="btn btn-outline-primary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-youtube"></i></a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Form -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-4 p-md-5">
                        <h3 class="fw-bold mb-2">{{ __('contact.form_title') }}</h3>
                        <p class="text-muted mb-4">{{ __('contact.form_subtitle') }}</p>

                        <form id="contact-form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">{{ __('contact.name_label') }}</label>
                                    <input type="text" name="name" class="form-control" placeholder="{{ __('contact.name_placeholder') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">{{ __('contact.email_label') }}</label>
                                    <input type="email" name="email" class="form-control" placeholder="{{ __('contact.email_placeholder') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">{{ __('contact.phone_label') }}</label>
                                    <input type="text" name="phone" class="form-control" placeholder="{{ __('contact.phone_placeholder') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">{{ __('contact.subject_label') }}</label>
                                    <input type="text" name="subject" class="form-control" placeholder="{{ __('contact.subject_placeholder') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small">{{ __('contact.message_label') }}</label>
                                    <textarea name="message" class="form-control" rows="5" placeholder="{{ __('contact.message_placeholder') }}" required></textarea>
                                </div>

                                @if(config('contact.recaptcha.site_key'))
                                <div class="col-12">
                                    <div class="g-recaptcha" data-sitekey="{{ config('contact.recaptcha.site_key') }}"></div>
                                </div>
                                @endif

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary px-5 py-3 fw-bold rounded-pill w-100 w-md-auto" id="submit-btn"
                                        data-sending="{{ __('contact.sending') }}"
                                        data-default="{{ __('contact.submit_btn') }}">
                                        <i class="bi bi-send me-2"></i>{{ __('contact.submit_btn') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div id="alert-container" class="mt-4 d-none">
                            <div class="alert d-flex align-items-center" role="alert">
                                <i class="bi flex-shrink-0 me-2 fs-5" id="alert-icon"></i>
                                <div id="alert-message"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($contactSettings->maps_url)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm overflow-hidden rounded-4">
                        <iframe
                            src="{{ $contactSettings->maps_url }}"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    @push('scripts')
    @if(config('contact.recaptcha.site_key'))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script src="{{ asset('js/pages/contact.js') }}"></script>
    @endpush
</x-layout.app>