@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    use Illuminate\Support\Facades\Route;

    $locale = app()->getLocale();
    $User = auth()->user();
    $CurrentLocaleDirection = LaravelLocalization::getCurrentLocaleDirection();
    $route_name = Route::currentRouteName();
@endphp
@extends('layouts.auth', [
    "Title" => __("Sign in")
])
@section('style')
    <!--begin::Page bg image-->
    <style>
        body { background-image: url('{{ asset("assets/media/auth/bg10.jpeg") }}'); } [data-bs-theme="dark"] body { background-image: url('{{ asset("assets/media/auth/bg10-dark.jpeg") }}'); }
    </style>
    <!--end::Page bg image-->
@endsection

@section('content')
<!--begin::Root-->
<div class="d-flex flex-column flex-root" id="kt_app_root">
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Aside-->
        <div class="d-flex flex-lg-row-fluid">
            <!--begin::Content-->
            <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                <!--begin::Image-->
                <img class="theme-light-show mx-auto mw-200 w-300px w-lg-400px mb-10 mb-lg-20" src="{{ asset("uploads/logo/logo.png") }}" alt="agency" />
                <img class="theme-dark-show mx-auto mw-200 w-300px w-lg-400px mb-10 mb-lg-20" src="{{ asset("uploads/logo/logo.png") }}" alt="agency-dark" />
                <!--end::Image-->
                <!--begin::Title-->
{{--                <h1 class="text-primary fs-2qx fw-bold text-center mb-7">{{ settings("center_name_".app()->getLocale()) }}</h1>--}}
                <!--end::Title-->
                <!--begin::Text-->
{{--                <div class="text-gray-600 fs-base text-center fw-semibold">In this kind of post,--}}
{{--                    <a href="#" class="opacity-75-hover text-primary me-1">the blogger</a>introduces a person they’ve interviewed--}}
{{--                    <br />and provides some background information about--}}
{{--                    <a href="#" class="opacity-75-hover text-primary me-1">the interviewee</a>and their--}}
{{--                    <br />work following this is a transcript of the interview.--}}
{{--                </div>--}}
                <!--end::Text-->
            </div>
            <!--end::Content-->
        </div>
        <!--begin::Aside-->
        <!--begin::Body-->
        <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
            <!--begin::Wrapper-->
            <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
                        <!--begin::Form-->
                        <form class="form w-100" method="POST" action="{{ route('login') }}">
                            @csrf
                            <!--begin::Heading-->
                            <div class="text-center mb-11">
                                <h1 class="fw-bolder mb-3">{{ __('Login') }}</h1>
                                <p class="text-gray-500 fw-semibold fs-6">{{ settings("center_name_".app()->getLocale()) }}</p>
                            </div>
                            <!--begin::Heading-->
                            <!--begin::Input group=-->
                            {{-- username --}}
                            <div class="fv-row mb-8">
                                <input type="text" class="form-control @error('username') is-invalid @enderror @error('email') is-invalid @enderror"
                                       name="username" value="{{ old('username') ? : '' }}"
                                       placeholder="{{ __('username or email') }}" autofocus >
                            </div>
                            <!--end::Input group=-->
                            {{-- password --}}
                            <div class="fv-row mb-8">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                                       value="{{ old('password') ? : '' }}" placeholder="{{ __('password') }}" autocomplete="current-password">
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Submit button-->
                            <div class="d-grid mb-10">
                                <button id="login_btn_submit" type="submit" class="btn btn-primary">
                                    <span class="indicator-label">{{__("Sign in")}}</span>
                                    <span class="indicator-progress">{{__("Please wait")}}
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                            <!--end::Submit button-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Footer-->
                    <div class="d-flex flex-stack">
                        <!--begin::Languages-->
                        <div class="me-10">
                            <!--begin::Toggle-->
                            <button class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base" data-kt-menu-trigger="click" data-kt-menu-placement='bottom-{{ ($locale == "ar")? "start" : "end" }}' data-kt-menu-offset="0px, 0px">
                                <img data-kt-element="current-lang-flag" class="w-20px h-20px rounded me-3" src="{{ asset("assets/media/flags/".app()->getLocale().".svg") }}" alt="{{ app()->getLocale() }}" />
                                <span data-kt-element="current-lang-name" class="me-1">{{ LaravelLocalization::getCurrentLocaleNative() }}</span>
                                <i class="ki-outline ki-down fs-5 text-muted rotate-180 m-0"></i>
                            </button>
                            <!--end::Toggle-->
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4 fs-7" data-kt-menu="true" id="kt_auth_lang_menu">
                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="menu-link d-flex px-5 {{ ($localeCode == app()->getLocale())? "active" : "" }}">
                                        <span class="symbol symbol-20px me-4">
                                            <img class="rounded-1" src="{{asset("assets/media/flags/".$localeCode.".svg")}}" alt="{{ $properties['native'] }}" />
                                        </span> {{ $properties['native'] }}
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                @endforeach
                            </div>
                            <!--end::Menu-->
                        </div>
                        <!--end::Languages-->
                        <!--begin::Links-->
                        <div class="d-flex fw-semibold text-primary fs-base gap-5">
                            <a href="{{ route("landing") }}">{{ __("Landing") }}</a>
{{--                            <a href="#" target="_blank">Plans</a>--}}
{{--                            <a href="#" target="_blank">Contact Us</a>--}}
                        </div>
                        <!--end::Links-->
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication - Sign-in-->
</div>
<!--end::Root-->

@endsection
@section('script')
    <script>
        $(function () {
            let LoginSubmit =  $("#login_btn_submit");
            LoginSubmit.click(function() {
                LoginSubmit.attr('data-kt-indicator', 'on');
                $("#login_form").submit();
                LoginSubmit.prop('disabled', true);
                LoginSubmit.removeAttr('data-kt-indicator').prop('disabled', false);
            });
        });
    </script>
@endsection
