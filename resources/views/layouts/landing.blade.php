@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    use Illuminate\Support\Facades\Route;

    $locale = app()->getLocale();
    $User = auth()->user();
    $CurrentLocaleDirection = LaravelLocalization::getCurrentLocaleDirection();
    $route_name = Route::currentRouteName();
@endphp

<!doctype html>
<html lang="{{ $locale }}" direction="{{ $CurrentLocaleDirection }}" dir="{{ $CurrentLocaleDirection }}" style="direction: {{ $CurrentLocaleDirection }}">
    @include("layouts.main_partials.head")
    <body id="kt_body" data-bs-spy="scroll" data-bs-target="#kt_landing_menu" class="bg-body position-relative app-blank">
        <div class="d-flex flex-column flex-root" id="kt_app_root">
            <div class="mb-0" id="home">
                <div class="bgi-no-repeat bgi-size-contain bgi-position-x-center bgi-position-y-bottom landing-dark-bg">
                    <div class="landing-header" data-kt-sticky="true" data-kt-sticky-name="landing-header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                        <div class="container">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-equal">
                                    <button class="btn btn-icon btn-active-color-primary me-3 d-flex d-lg-none" id="kt_landing_menu_toggle">
                                        <i class="ki-outline ki-abstract-14 fs-2hx"></i>
                                    </button>
                                    <a href="#">
                                        <img alt="Logo" src="{{ asset("uploads/logo/logo-dark-{$locale}.png") }}" class="logo-default h-30px h-lg-60px" />
                                        <img alt="Logo" src="{{ asset("uploads/logo/logo-sm.png") }}" class="logo-sticky h-20px h-lg-25px" />
                                    </a>
                                </div>

                                <div class="flex-equal d-flex justify-content-end gap-2 text-end ms-1">
                                    @yield("toolbar_buttons")
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                @yield("content")
            </div>

            <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
                <i class="ki-outline ki-arrow-up"></i>
            </div>
        </div>

        @include("layouts.main_partials.footer")
        @include("layouts.main_partials.foot_script")

        <script src="{{ asset("assets/js/custom/landing.js") }}"></script>
{{--        <script src="{{ asset("assets/js/custom/pages/pricing/general.js") }}"></script>--}}
    </body>
</html>
