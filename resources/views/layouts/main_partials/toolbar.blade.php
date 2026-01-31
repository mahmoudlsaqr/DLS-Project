<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
        <!--begin::Toolbar wrapper-->
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-primary fw-bold fs-3 m-0">
                    @if(isset($Title)) {{ $Title }} @else {{ __("Unknown") }} @endif
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        @if(isset($breadcrumb_main)) {{ $breadcrumb_main }} @else {{ __("Dashboard") }} @endif
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        @if(isset($breadcrumb_item)) {{ $breadcrumb_item }} @elseif(isset($Title)) {{ $Title }} @else {{ __("Unknown") }} @endif
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-2">
{{--                <a href="#" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">Button 1</a>--}}
                @yield("toolbar_buttons")
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar wrapper-->
    </div>
    <!--end::Toolbar container-->
</div>
<!--end::Toolbar-->
