@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Data settings"),
    "Title" => __("Services")
])
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                @include('layouts.partials.table_search')
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end gap-2" data-kt-user-table-toolbar="base">
                    <div>
                        <button type="button" class="btn btn-sm btn-info" data-kt-menu-trigger="click" data-kt-menu-static="true" data-kt-menu-placement='bottom-{{ ($locale == "ar")? "start" : "end" }}'>
                            {{ __("Filter") }}</button>
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                            <div class="px-7 py-5">
                                <div class="fs-7 text-dark fw-bold">{{ __("Filter Options") }}</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                <div class="col-lg-12 mb-4">
                                    <label class="required fw-semibold fs-7 mb-2">{{ __("Category") }}</label>
                                    <select class="form-select form-select-sm fw-bold f_category input_filter" data-kt-select2="true"
                                            data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false">
                                        <option></option>
                                    </select>
                                </div>
                                <div class="mb-10">
                                    <label class="form-label fs-7 fw-semibold">{{ __("Status") }}</label>
                                    <select class="form-select form-select-sm fw-bold f_status input_filter" data-kt-select2="true"
                                            data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="false">
                                        <option></option>
                                        <option value="true">{{ __("Enabled") }}</option>
                                        <option value="false">{{ __("Disabled") }}</option>
                                    </select>
                                </div>
                                @include("layouts.partials.table_filter_buttons_actions")
                            </div>
                        </div>
                    </div>

                    @include('layouts.partials.filter_columns_button')
                    @php
                        $columns = ['check' , '#', __('Service'), __('Category'), __('Service code'), __('Service cost'), __('Service price'), __('Tax'), __('Price + Tax'), __('Note'), __('Status') ];
                        $col_start = 1;
                    @endphp
                    @include('layouts.partials.filter_columns')

                    @if(auth()->user()->can('create_services'))
                        @include('layouts.partials.add_button_toggle')
                    @endif
                    @if(auth()->user()->can('remove_services'))
                        @include('layouts.partials.delete_selected')
                    @endif
                </div>

            </div>
        </div>
        <div class="card-body">
            @include('settings.data_settings.services.partials.table')
        </div>
    </div>
@endsection

@section('modal_add_content')
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Arabic name") }}</label>
        <input type="text" name="name_ar" class="form-control form-control-sm" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("English name") }}</label>
        <input type="text" name="name_en" class="form-control form-control-sm" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Category") }}</label>
        <select class="form-select form-select-sm fw-bold category_id" data-kt-select2="true" name="category_id" id="category_id"
                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false" data-dropdown-parent="#modal_add">
            <option></option>
        </select>
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Service code") }}</label>
        <input type="text" name="service_code" class="form-control form-control-sm service_code" />
    </div>
    <div class="col-lg-12 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Service cost") }}</label>
        <input type="text" name="service_cost" oninput="input_float(this);" class="form-control form-control-sm" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Price") }}</label>
        <input type="text" name="service_price" oninput="input_float(this);" class="form-control form-control-sm" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Tax") }}</label>
        <input type="text" name="tax" oninput="input_float(this); max_min_val(this, 100, 0);" class="form-control form-control-sm" />
    </div>
    <div class="col-lg-12 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Branches") }}</label>
        <select class="form-select form-select-sm branches" data-control="select2"
                name="branches[]" data-close-on-select="true" data-dropdown-parent="#modal_add"
                data-placeholder="{{ __('Select option') }}" data-allow-clear="true"
                multiple="multiple">
        </select>
    </div>
    <div class="col-lg-12 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Notes") }}</label>
        <textarea type="text" name="note" class="form-control form-control-sm"></textarea>
    </div>
@endsection

@section('modal_edit_content')
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Arabic name") }}</label>
        <input type="text" name="name_ar" id="name_ar_edit" class="form-control form-control-sm" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("English name") }}</label>
        <input type="text" name="name_en" id="name_en_edit" class="form-control form-control-sm" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Category") }}</label>
        <select class="form-select form-select-sm fw-bold category_id" data-kt-select2="true" name="category_id" id="category_id_edit"
                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false" data-dropdown-parent="#modal_edit">
            <option></option>
            @foreach($categories as $item)
                <option value="{{ $item["id"] }}">{{ $locale == "ar"? $item["name_ar"] : $item["name_en"] }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Service code") }}</label>
        <input type="text" name="service_code" id="service_code_edit" class="form-control form-control-sm" />
    </div>
    <div class="col-lg-12 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Service cost") }}</label>
        <input type="text" name="service_cost" id="service_cost_edit" oninput="input_float(this);" class="form-control form-control-sm" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Price") }}</label>
        <input type="text" name="service_price" id="service_price_edit" oninput="input_float(this);" class="form-control form-control-sm" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Tax") }}</label>
        <input type="text" name="tax" id="tax_percent_edit" oninput="input_float(this); max_min_val(this, 100, 0);" class="form-control form-control-sm" />
    </div>
    <div class="col-lg-12 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Branches") }}</label>
        <select class="form-select form-select-sm branches" data-control="select2"
                name="branches[]" id="branches_edit" data-close-on-select="true" data-dropdown-parent="#modal_edit"
                data-placeholder="{{ __('Select option') }}" data-allow-clear="true"
                multiple="multiple">
            @foreach($branches as $item)
                <option value="{{ $item["id"] }}">{{ $locale == "ar"? $item["name_ar"] : $item["name_en"] }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-12 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Notes") }}</label>
        <textarea type="text" name="note" id="note_edit" class="form-control form-control-sm"></textarea>
    </div>
@endsection

@section("script")
    @include('settings.data_settings.services.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')

    <script>
        $(function () {
            SelectGetData($('.branches'), "{{ route( 'get_branches' ) }}", true);
            SelectGetData($('.category_id'), "{{ route( 'get_categories' ) }}", false);
            SelectGetData($('.f_category'), "{{ route( 'get_categories_from_services' ) }}", false);

            $('#category_id').on('change', function () {
                const id = $(this).val();
                if (id) {
                    $.ajax({
                        url: `/create_service_code/${id}`,
                        type: "GET",
                        success: function(data) {
                            $('.service_code').val(data);
                        },
                        error: function(error) {
                            console.log(error["responseJSON"])
                            alert(`Error: ${error}`);
                        }
                    });
                }
            });
        });
    </script>
@endsection

