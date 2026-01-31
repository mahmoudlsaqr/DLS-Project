@php
    use Illuminate\Support\Facades\Auth;
    $locale = app() ->getLocale();
    $User = Auth::user();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Orders"),
    "Title" => __("Edit order")
])
@section("toolbar_buttons")
    @include("layouts.partials.back_button", ["link" => route("cases.orders.index"), "label" => __("Orders list")])
@endsection
@section('content')
    <div class="card">
        <form id="form_update" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card-body">
                <div class="row ">
                    <!--begin::Input group-->
                    <div class="col-lg-4 mb-7">
                        <label class="form-label required fw-semibold fs-7 mb-2">{{ __("Patient Name") }}</label>
                        <input type="text" name="patient_name" value="{{ old('patient_name')? : $Order["patient_name"] }}" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="{{ __("Patient Name") }}"/>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-4 mb-7">
                        <label class="form-label fw-semibold fs-7 mb-2">{{ __("Gender") }}</label>
                        <select class="form-select form-select-sm" data-kt-select2="true" data-hide-search="true" data-placeholder="{{ __("Select option") }}" name="gender">
                            <option></option>
                            <option value="0" @if(0 == $Order["gender"]) selected @endif>{{ __("Female") }}</option>
                            <option value="1" @if(1 == $Order["gender"]) selected @endif>{{ __("Male") }}</option>
                        </select>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-4 mb-7">
                        <label class="form-label fw-semibold fs-7 mb-2">{{ __("Age") }}</label>
                        <input type="text" oninput="input_number(this)" value="{{ old('age')? : $Order["age"] }}" name="age" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="{{ __("Age") }}"/>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-4 mb-7">
                        <label class="form-label required fw-semibold fs-7 mb-2">{{ __("Required in case") }}</label>
                        <textarea class="form-control" rows="4" name="required_in_case"
                                  placeholder="{{ __("Write something ...") }}">{{ old('required_in_case')? : $Order["required_in_case"] }}</textarea>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-4 mb-7">
                        <label class="form-label required fw-semibold fs-7 mb-2">{{ __("Treatment plan") }}</label>
                        <textarea class="form-control" rows="4" name="treatment_plan"
                                  placeholder="{{ __("Write something ...") }}">{{ old('treatment_plan')? : $Order["treatment_plan"] }}</textarea>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-4 mb-7">
                        <label class="form-label required fw-semibold fs-7 mb-2">{{ __("Patient expectations") }}</label>
                        <textarea class="form-control" rows="4" name="patient_expectations"
                                  placeholder="{{ __("Write something ...") }}">{{ old('patient_expectations')? : $Order["patient_expectations"] }}</textarea>
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <div class="card-footer">
                <div class="mb-2">
                    <button type="submit" id="submit_btn" class="btn h-35px btn-sm btn-primary btn-primary" data-kt-users-modal-action="submit">
                        <span class="indicator-label">{{ __("Submit") }}</span>
                        <span class="indicator-progress">{{ __("Please wait...") }}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>

            @if(auth()->user()->can('view_attachments'))
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        @include('layouts.partials.table_search')
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end gap-2" data-kt-user-table-toolbar="base">

                            {{--    ##===> FILTER COLUMNS <===##--}}
                            @include('layouts.partials.filter_columns_button')
                            @php
                                $columns = ['check' , '#', __('File'), __('Type') ];
                                $col_start = 1;
                            @endphp
                            @include('layouts.partials.filter_columns')

                            @if(auth()->user()->can('add_attachments'))
                                @include('layouts.partials.add_button_toggle')
                            @endif
                        </div>
                        <!--end::Toolbar-->

                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body">
                    <div class="row">
                        @include('cases.order_attachments.partials.table')
                    </div>
                </div>
                <!--end::Card body-->
            @endif
        </form>
    </div>

    @if(auth()->user()->can('view_attachments'))
        @if(count($OrderAttachment) > 0)
            @foreach($OrderAttachment as $item)
                <div class="modal bg-body fade" tabindex="-1" id="modal_video_{{ $item["id"] }}_view">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content shadow-none">
                            <!--begin::Modal header-->
                            <div class="modal-header d-flex justify-content-between align-items-center">
                                <h2 class="modal-title fs-4" id="exampleModalLabel">{{ __("View video") }}</h2>
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-icon-danger ms-2 modal_{{ $item["id"] }}_cancel" data-bs-dismiss="modal" aria-label="cancel">
                                    <i class="ki-outline ki-cross fs-1"></i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <!--end::Modal header-->
                            <div class="modal-body d-flex justify-content-center align-items-center">
                                <video class="vide_{{ $item["id"] }}" height="100%" width="100%" controls>
                                    <source class="video_source" src="{{ "uploads/order_attachments/{$item["order_id"]}/{$item['file']}" }}" type="video/mp4">
                                </video>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light modal_{{ $item["id"] }}_cancel" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="modal bg-body fade" tabindex="-1" id="modal_image_view">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content shadow-none">
                    <!--begin::Modal header-->
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h2 class="modal-title" id="exampleModalLabel">{{ __("View image") }}</h2>
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-danger ms-2" data-bs-dismiss="modal" aria-label="cancel">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->

                    <div class="modal-body d-flex justify-content-center align-items-center w-100">
                        <div class="mw-50">
                            <img class="image_source w-100 p-4" src="" alt="image_view"/>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@if(auth()->user()->can('add_attachments'))
    <!--begin::Modals actions-->
    @section('modal_add_content')
        <input type="hidden" name="order_id" value="{{ $Order["id"] }}" />
        <div id="attachments_repeater">
            <div class="table-responsive">
                <table class="table gs-2 mb-0 fw-semibold">
                    <thead>
                    <tr class="fs-7 fw-bold text-start">
                        <th class="form-label required fw-semibold">{{ __('Attachments') }}</th>
                        <th class="form-label required fw-semibold">{{ __('Description') }}</th>
                        <th class="form-label required fw-semibold">{{ __('Type') }}</th>
                    </tr>
                    </thead>

                    <tbody data-repeater-list="attachments_repeater">
                    <tr data-repeater-item="" data-kt-element="item">
                        <td class="px-1">
                            <input class="form-control form-control-sm fs-7" type="file" name="file"
                                   accept=".pdf, .stl, image/*, video/*">
                        </td>
                        <td class="px-1">
                            <input class="form-control form-control-sm fs-7" type="text" name="description">
                        </td>
                        <td class="px-1">
                            <select class="form-select form-select-sm fs-7" data-kt-repeater="select2"
                                    name="type" data-dropdown-parent="#modal_add">
                                <option></option>
                                <option value="1">{{ __("Image") }}</option>
                                <option value="2">{{ __("Video") }}</option>
                                <option value="3">{{ __("Pdf") }}</option>
                                <option value="4">{{ __("Stl") }}</option>
                            </select>
                        </td>
                        <td class="px-1">
                            <a data-repeater-delete="" class="btn h-35px btn-sm btn-icon btn-flex btn-light-danger">
                                <i class="ki-solid ki-trash fs-5"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-3">
                <button type="button" href="javascript:;" data-repeater-create class="btn btn-sm btn-light-success fs-7" id="AddButton">
                    <i class="ki-duotone ki-plus fw-bold fs-5"></i>
                    {{ __("Add row") }}
                </button>
            </div>
        </div>
    @endsection
    <!--end::Modals actions-->
@endif

@section("script")
    @include('cases.order_attachments.partials.scripts')
    @include("layouts.partials.script_form_update", [
       "Form_route" => route('cases.orders.update', [$Order["id"]]),
       "Redirect_route" => route('cases.orders.index')
   ])
    <script src="{{asset("assets/plugins/custom/formrepeater/formrepeater.bundle.js")}}"></script>
    <script>
        $(function () {

            @if(auth()->user()->can('view_attachments'))
                @if(count($OrderAttachment) > 0)
                    @php $i = 0; @endphp
                    @foreach($OrderAttachment as $item)
                        $(document).on('click', '.modal_{{ $item["id"] }}_cancel', function() {
                            var video = $('video');
                            video.get({{ $i }}).pause();
                            video.get({{ $i }}).stop();
                        });
                        @php ++$i @endphp
                    @endforeach
                @endif

                $(document).on('click', '.image_view_btn', function () {
                    let Src = $(this).data('src');
                    $('.image_source').attr('src', Src);
                    $('#modal_image_view').modal('show');
                });
            @endif

            $('#attachments_repeater').repeater({
                initEmpty: false,

                defaultValues: {
                    'text-input': 'foo'
                },

                show: function () {
                    $(this).slideDown();

                    // Re-init select2
                    $('[data-kt-repeater="select2"]').select2({
                        placeholder: '{{ __("Select option") }}'
                    });
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                },
                ready: function(){
                    // Init select2
                    $('[data-kt-repeater="select2"]').select2({
                        placeholder: '{{ __("Select option") }}'
                    });
                }
            })
        })
    </script>

@endsection
