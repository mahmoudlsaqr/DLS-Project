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

    <!--begin::Body-->
    <body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center">
        @yield("content")
        @include("layouts.main_partials.foot_script")
    </body>
    <!--end::Body-->
</html>
