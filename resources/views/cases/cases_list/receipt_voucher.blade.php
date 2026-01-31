@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    use Alkoumi\LaravelArabicTafqeet\Tafqeet;
    use Milon\Barcode\DNS1D;
    use App\Models\User;

    $locale = app()->getLocale();
    $User = auth()->user();
    $D = new DNS1D();
@endphp

<!doctype html>
<html lang="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<!--begin::Head-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <link rel="shortcut icon" href="{{ asset("assets/media/logos/favicon.ico") }}" />

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="{{ url("https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700") }}" />
    <!--end::Fonts(mandatory for all pages)-->

    <title>{{ __("Receipt voucher") }}</title>

    <link href="{{ asset("assets/plugins/custom/datatables/datatables.bundle.css") }}" rel="stylesheet" type="text/css" />

    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset("assets/plugins/global/plugins.bundle.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("assets/css/style.bundle.css") }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
      data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
      data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true"
      class="app-default">

    <div class="card border-gray-900 p-6">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="arabic_name">
                    {{ settings("center_name_ar") ?? "arabic_name" }}
                </div>
                <div class="image_logo w-150px">
                    <img class="bi-card-image w-150px" src="{{ asset("uploads\logo\logo.png") }}"  alt="logo"/>
                </div>
                <div class="english_name">
                    {{ settings("center_name_en") ?? "english_name"}}
                </div>
            </div>
        </div>
        <div class="card-body rounded border border-gray-900 p-6">
            <div class="d-flex justify-content-start align-items-center gap-6">
                <div class="div_r mb-3">
                    <div class="fw-semibold text-gray-600 fs-6">{{ __("Doctor name") }}:
                        <span class="text-gray-800 fw-normal">{{ User::where("id", $Case["physician"])->value("name") }}</span>
                    </div>
                </div>
                <div class="div_l mb-3">
                    <div class="fw-semibold text-gray-600 fs-6">{{ __("Clinic name") }}:
                        <span class="text-gray-800 fw-normal">{{ $Voucher["clinic_name"] }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-start align-items-center gap-6 mb-6">
                <div class="div_r mb-3">
                    <div class="fw-semibold text-gray-600 fs-6">{{ __("You have been handed over the patient's case") }}:
                        <span class="text-gray-800 fw-normal">{{ $Case["patient_name"] }}</span>

                    </div>
                </div>

                <div class="div_l mb-3">
                    <div class="fw-semibold text-gray-600 fs-6">{{ __("With a number") }}:
                        <span class="text-gray-800 fw-normal">{{ $Case["case_number"] }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-start align-items-center gap-6">
                <div class="mb-3">
                    <div class="fw-normal text-gray-600 fs-6">{{ $Voucher["description"] }}</div>
                    <div class="fw-semibold text-gray-600 fs-6">{{ __("Amount") }}:
                        <span class="text-gray-800 fw-normal">{{ $Voucher["amount"] }}</span>
                    </div>
                    <div class="fw-semibold text-gray-600 fs-6">{{ __("Text amount") }}:
                        <span class="text-gray-800 fw-normal">{!! Tafqeet::inArabic($Voucher["amount"]) !!}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start gap-6">
                <div class="div_r mb-3">
                    <div class="fw-semibold text-gray-600 fs-6">{!! $D->getBarcodeSVG("$Voucher->id", 'C39+') !!}</div>
                </div>
                <div class="div_l mb-3">
                    <div class="fw-semibold text-gray-600 fs-6">{{ __("Recipient's signature") }}</div>
                    <div class="fw-semibold text-gray-600 fs-6">.............</div>
                </div>
            </div>
        </div>
    </div>

</body>
<!--end::Body-->
</html>
