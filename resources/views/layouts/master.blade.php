@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    use Illuminate\Support\Facades\Route;

    $locale = app()->getLocale();
    $User = auth()->user();
    $CurrentLocaleDirection = LaravelLocalization::getCurrentLocaleDirection();
    $route_name = Route::currentRouteName();
@endphp

<!doctype html>
<html lang="{{ $locale }}" dir="{{ $CurrentLocaleDirection }}" style="direction: {{ $CurrentLocaleDirection }}">
    @include("layouts.main_partials.head")

    <!--begin::Body-->
    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
          data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
          data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true"
          class="app-default">

{{--        <div class="page-loader">--}}
{{--            <div>--}}
{{--                <div class="spinner-border-medical" role="status"></div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <!--begin::App-->
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <!--begin::Page-->
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                @include("layouts.main_partials.header")
                <!--begin::Wrapper-->
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    @include("layouts.main_partials.sidebar")
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">
                            @include("layouts.main_partials.toolbar")
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid ">
                                    @yield('content')
                                </div>
                                <!--end::Content container-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Content wrapper-->
                        @include("layouts.main_partials.footer")
                    </div>
                    <!--end:::Main-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>

        <!--begin::Modals actions-->
        @include('layouts.partials.modal_edit')
        @include('layouts.partials.modal_add')
        @include('layouts.partials.modal_submit')
        <!--end::Modals actions-->
        @include('layouts.partials.modal_export')

        @include("layouts.main_partials.foot_script")


    </body>
    <!--end::Body-->
</html>
