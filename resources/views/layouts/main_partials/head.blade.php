<!--begin::Head-->
<head>
    <base href="/"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <link rel="shortcut icon" href="{{ asset("uploads/logo/logo-sm.png") }}" />

    <!--begin::Fonts(mandatory for all pages)-->
    <link href="{{ asset("assets/css/fonts_googleapis.css") }}" rel="stylesheet" type="text/css" />
    <!--end::Fonts(mandatory for all pages)-->

    <title>@if(isset($Title)) {{ $Title }} @else {{ __("Unknown") }} @endif</title>

    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <link href="{{ asset("assets/plugins/custom/prismjs/prismjs.bundle.css") }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendor Stylesheets-->

    @if($locale === "ar")
        <link href="{{ asset("assets/plugins/custom/datatables/datatables.bundle.rtl.css") }}" rel="stylesheet" type="text/css" />
        <!--begin::Global Stylesheets Bundle(used by all pages)-->
        <link href="{{ asset("assets/plugins/global/plugins.bundle.rtl.css") }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset("assets/css/style.bundle.rtl.css") }}" rel="stylesheet" type="text/css" />
        <!--end::Global Stylesheets Bundle-->
        <style>
            table.dataTable tfoot td, table.dataTable tfoot th, table.dataTable thead td, table.dataTable thead th {
                text-align: right;
            }
        </style>
    @else
        <link href="{{ asset("assets/plugins/custom/datatables/datatables.bundle.css") }}" rel="stylesheet" type="text/css" />
        <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        <link href="{{ asset("assets/plugins/global/plugins.bundle.css") }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset("assets/css/style.bundle.css") }}" rel="stylesheet" type="text/css" />
        <!--end::Global Stylesheets Bundle-->

        <style>
            table.dataTable tfoot td, table.dataTable tfoot th, table.dataTable thead td, table.dataTable thead th {
                text-align: left;
            }
        </style>
    @endif

    <!--begin::Custom Stylesheet-->
    <link href="{{ asset("assets/css/custom.css") }}" rel="stylesheet" type="text/css" />
    <!--end::Custom Stylesheet-->

    @yield("style")
</head>
<!--end::Head-->
