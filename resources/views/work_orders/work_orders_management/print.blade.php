@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    $locale = app()->getLocale();
    $user = auth()->user();
@endphp
<!doctype html>
<html lang="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __("Print work order") }}</title>
    <style>
        @page {
            size: 4cm 3cm;
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
        }
        * {
            font-family: arial, Tahoma, sans-serif !important;
            margin: 0;
            padding: 0;
        }
        .data {
            font-size: 8px;
            font-weight: bold;
        }
        .label-md {
            font-size: 9px;
            font-weight: bold;
            margin-top: 2px;
        }
        .branch {
            font-size: 9px;
            text-decoration: underline;
            text-align: center;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .container {
            margin: 0 auto;
            padding: 2px;
            width: fit-content;
        }
        .barcode {
            text-align: center;
            margin-top: 2px;
        }
        .barcode img {
            width: 80px;
            height: 30px;
        }
        .d-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1em;
        }
        .flex-center {
            display: flex;
            justify-content: start;
            align-items: center;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="branch mb-2">{{ $locale == 'ar' ? $workOrder->branches->name_ar : $workOrder->branches->name_en }}</div>
        <div class="d-flex" style="width: 125px; margin: 2px auto">
            <span class="data">{{ $locale == 'ar' ? $workOrder->doctors->name_ar : $workOrder->doctors->name_en }}</span>
            <span class="label-md">{{ $workOrder->work_order_number }}</span>
        </div>
        <div class="d-flex" style="width: 125px; margin: 2px auto">
            <span class="data">{{ $locale == 'ar' ? $workOrder->services->name_ar : $workOrder->services->name_en }}</span>
            <span class="data">{{ $workOrder->colors->color_code }} ( {{ $workOrder->service_count }} ) </span>
        </div>
        <div class="d-flex" style="width: 125px; margin: 2px auto">
            <span class="data">{{ $workOrder->patient_name }}</span>
            <span class="data">{{ $workOrder->file_number }}</span>
        </div>
        <div class="barcode flex-center" >
            <img src="data:image/png;base64,{{ $barcodeData }}" alt="Barcode" />
        </div>
    </div>
</body>
</html>
