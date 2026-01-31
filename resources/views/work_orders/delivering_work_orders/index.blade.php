@php
    use Illuminate\Support\Facades\Auth;

    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Work orders management"),
    "Title" => __("Delivering work orders")
])
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h4 class="">{{ __("Search for work order") }}</h4>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center align-content-center">
                <div class="col-12">
                    <video id="barcode-scanner" class="w-100" width="100%"></video>
                </div>
                <div class="col-12">
                    <form id="form">
                        @csrf
                        <input class="form-control form-control-sm" type="text" id="barcode-input" name="barcode_code" placeholder="{{ __("Scan barcode") }}" readonly>
                        <button type="submit" id="submit_button" class="btn btn-primary btn-sm mt-4" data-kt-users-modal-action="submit">
                            <span class="indicator-label">{{ __("Delivery") }}</span>
                            <span class="indicator-progress">{{ __("Please wait...") }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </form>
                </div>
                <div class="col-12">
                    <div id="error-message" class="error-message"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script src="{{ url("https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js") }}"></script>
    <script src="{{ url("https://unpkg.com/@zxing/library@latest") }}"></script>
    <script>
        const codeReader = new ZXing.BrowserMultiFormatReader();
        const videoElement = document.getElementById('barcode-scanner');
        const barcodeInput = document.getElementById('barcode-input');
        const errorMessage = document.getElementById('error-message');

        let barcodeRead = false;

        function clearError() {
            errorMessage.textContent = '';
        }

        function showError(message) {
            errorMessage.textContent = message;
        }

        function stopCamera() {
            const stream = videoElement.srcObject;
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
                videoElement.srcObject = null;
            }
        }

        function startBarcodeScanning() {
            clearError();

            codeReader
                .listVideoInputDevices()
                .then((videoInputDevices) => {
                    if (videoInputDevices.length > 0) {
                        const deviceId = videoInputDevices[0].deviceId;

                        codeReader.decodeFromVideoDevice(deviceId, videoElement, (result, error) => {
                            if (result && !barcodeRead) {
                                barcodeInput.value = result.text;
                                alert("{{ __("Barcode read") }}: " + result.text);
                                barcodeRead = true;
                                stopCamera();
                                clearError();
                            } else if (error) {
                                console.error(error);
                            }
                        });
                    } else {
                        showError("{{ __("No cameras available.") }}");
                    }
                })
                .catch((err) => {
                    console.error(err);
                    showError("{{ __("Error occurred while accessing the camera.") }}");
                });
        }

        window.onload = () => {
            startBarcodeScanning();
        };

        $("#submit_button").click(function(e) {
            $(this).attr('data-kt-indicator', 'on');
            $("#submit_button").prop('disabled', true);
            e.preventDefault();
            let form = new FormData($("#form")[0]);
            $.ajax({
                url: "{{ route("work_orders.work_orders_management.delivering_work_orders.reader.update_phase") }}",
                type: "POST",
                data: form,
                processData: false,
                contentType: false,
                success: function(data) {
                    $("#submit_button").removeAttr('data-kt-indicator').prop('disabled', false);
                    if (data.success) {
                        Swal.fire("{{ __('success') }}", data.success, 'success');
                    } else { Swal.fire("{{ __('error') }}", data.error, 'error'); }
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON)
                }
            });
        });


    </script>
@endsection

