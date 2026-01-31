@extends('layouts.master', [
    "breadcrumb_main" => __("Orders"),
    "Title" => __("Create order")
])
@section("toolbar_buttons")
    @include("layouts.partials.back_button", ["link" => route("cases.orders.index"), "label" => __("Orders list")])
@endsection
@section('content')
    <div class="card px-2">
        <form id="form_create" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card-body">
                <div class="row">
                    <!--begin::Input group-->
                    <div class="col-lg-4 mb-4">
                        <label class="form-label required fw-semibold fs-7 mb-2">{{ __("Patient Name") }}</label>
                        <input type="text" name="patient_name" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="{{ __("Patient Name") }}"/>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-4 mb-4">
                        <label class="form-label fw-semibold fs-7 mb-2">{{ __("Gender") }}</label>
                        <select class="form-select form-select-sm" data-kt-select2="true" data-hide-search="true" data-placeholder="{{ __("Select option") }}" name="gender">
                            <option></option>
                            <option value="0">{{ __("Female") }}</option>
                            <option value="1">{{ __("Male") }}</option>
                        </select>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-4 mb-4">
                        <label class="form-label fw-semibold fs-7 mb-2">{{ __("Age") }}</label>
                        <input type="text" oninput="input_number(this)" name="age" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="{{ __("Age") }}"/>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-4 mb-4">
                        <label class="form-label required fw-semibold fs-7 mb-2">{{ __("Required in case") }}</label>
                        <textarea class="form-control" rows="4" name="required_in_case"
                                  placeholder="{{ __("Write something ...") }}"></textarea>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-4 mb-4">
                        <label class="form-label required fw-semibold fs-7 mb-2">{{ __("Treatment plan") }}</label>
                        <textarea class="form-control" rows="4" name="treatment_plan"
                                  placeholder="{{ __("Write something ...") }}"></textarea>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="col-lg-4 mb-4">
                        <label class="form-label required fw-semibold fs-7 mb-2">{{ __("Patient expectations") }}</label>
                        <textarea class="form-control" rows="4" name="patient_expectations"
                                  placeholder="{{ __("Write something ...") }}"></textarea>
                    </div>
                    <!--end::Input group-->

                    <div class="col-lg-12 mb-4" id="attachments_repeater">
                        <div class="table-responsive">
                            <table class="table gs-2 mb-0 fw-semibold">
                                <thead>
                                <tr class="fs-7 fw-bold pb-2 text-start">
                                    <th class="form-label required fw-semibold fs-7">{{ __('Attachments') }}</th>
                                    <th class="form-label required fw-semibold fs-7">{{ __('Description') }}</th>
                                    <th class="form-label required fw-semibold fs-7">{{ __('Type') }}</th>
                                </tr>
                                </thead>

                                <tbody data-repeater-list="attachments_repeater">
                                @for($i = 0; $i < 3; $i++)
                                    <tr data-repeater-item="" data-kt-element="item">
                                        <td class="py-2 px-2">
                                            <input class="form-control form-control-sm" type="file" name="attachments_repeater[0][file]"
                                                   accept=".pdf, .stl, image/*, video/*">
                                        </td>
                                        <td class="py-2 px-2">
                                            <input class="form-control form-control-sm" type="text" name="attachments_repeater[0][description]"
                                                   accept=".pdf, image/*, video/*">
                                        </td>
                                        <td class="py-2 px-2">
                                            <select class="form-select form-select-sm" data-kt-repeater="select2"
                                                    name="attachments_repeater[0][type]">
                                                <option></option>
                                                <option value="1">{{ __("Image") }}</option>
                                                <option value="2">{{ __("Video") }}</option>
                                                <option value="3">{{ __("Pdf") }}</option>
                                                <option value="4">{{ __("Stl") }}</option>
                                            </select>
                                        </td>
                                        <td class="py-2 px-2">
                                            <a href="javascript:;" data-repeater-delete="" class="btn btn-icon btn-flex btn-light-danger">
                                                <i class="ki-solid ki-trash fs-5"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                        <div class="py-2 px-2">
                            <button type="button" href="javascript:;" data-repeater-create class="btn h-35px btn-sm btn-primary btn-light-success" id="AddButton">
                                <i class="ki-duotone ki-plus fs-3"></i>
                                {{ __("Add row") }}
                            </button>
                        </div>
                    </div>
                    <!--begin::Input group-->
                    <div class="col-lg-12 mt-6">
                        <p class="text-danger fw-bold fs-7">- {{ __("Please have the treating doctor attach the patient's images.") }}</p>
                        <p class="text-danger fw-bold fs-7">- {{ __("Please have the treating doctor attach the panorama image.") }}</p>
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
        </form>
    </div>
@endsection

@section("script")
    @include("layouts.partials.script_form_create", [
        "Form_route" => route('cases.orders.store'),
        "Redirect_route" => route('cases.orders.index')
    ])

    <script src="{{asset("assets/plugins/custom/formrepeater/formrepeater.bundle.js")}}"></script>

    <script>
        $(function () {

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
