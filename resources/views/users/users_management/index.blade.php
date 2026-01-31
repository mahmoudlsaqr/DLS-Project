@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Users"),
    "Title" => __("Users management")
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
                                <div class="mb-10">
                                    <label class="form-label fs-7 fw-semibold">Role</label>
                                    <select class="form-select form-select-sm fw-bold f_roles input_filter" data-kt-select2="true"
                                            data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="false">
                                        <option></option>
                                        @foreach($roles as $item)
                                            <option value="{{ $item["name"] }}">{{ $item["name"] }}</option>
                                        @endforeach
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
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                @include("layouts.partials.table_filter_buttons_actions")
                                <!--end::Actions-->
                            </div>
                            <!--end::Content-->
                        </div>
                    </div>

                    @include('layouts.partials.filter_columns_button')
                    @php
                        $columns = ['check' , '#', __('User'), __('Mobile'), __('Email'), __('Status') ];
                        $col_start = 1;
                    @endphp
                    @include('layouts.partials.filter_columns')

                    @if(auth()->user()->can('create_users'))
                        @include('layouts.partials.add_button_toggle')
                    @endif
                    @if(auth()->user()->can('remove_users'))
                        @include('layouts.partials.delete_selected')
                    @endif
                </div>

            </div>
        </div>

        <div class="card-body">
            @include('users.users_management.partials.table')
        </div>
    </div>
@endsection

@section('modal_add_content')
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Full name") }}</label>
        <input type="text" name="name" class="form-control  form-control-sm" placeholder="{{ __("Full name") }}" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("User name") }}</label>
        <input type="text" name="username" class="form-control  form-control-sm" placeholder="{{ __("User name") }}" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Password") }}</label>
        <input type="password" name="password" class="form-control form-control-sm" placeholder="{{ __("Password") }}" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Email") }}</label>
        <input type="email" name="email" class="form-control form-control-sm" placeholder="example@domain.com" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Mobile") }}</label>
        <input oninput="input_number(this)" type="text" name="mobile" class="form-control form-control-sm" placeholder="{{ __("Mobile") }}" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Country code") }}</label>
        <select name="country_code" data-control="select2" data-hide-search="false" id="country_code_input" class="form-select form-select-sm" dir="ltr"
                data-dropdown-parent="#modal_add" data-placeholder="{{ __("Select option") }}">
            <option></option>
            @include('layouts.partials.country_codes')
        </select>
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
        <label class="required fw-semibold fs-7 mb-2">{{ __("Avatar") }}</label>
        <input type="file" name="avatar" accept="*/image" class="form-control form-control-sm"/>
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Roles") }}</label>
        <select class="form-select role form-select-sm roles" data-kt-select2="true" data-placeholder="{{ __("Select option") }}"
                data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="false" name="role" data-dropdown-parent="#modal_add">
            <option></option>
        </select>
    </div>
    <div class="col-lg-6 mb-4 supervising">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Supervising") }}</label>
        <select class="form-select form-select-sm supervising_user" data-kt-select2="true" data-placeholder="{{ __("Select option") }}"
                data-allow-clear="false" data-kt-user-table-filter="supervising" data-hide-search="false" name="supervising" data-dropdown-parent="#modal_add">
            <option></option>
        </select>
    </div>
    <div class="separator separator-dashed separator-content border-gray-400 my-8"><h4 class="w-200px text-gray-700 fs-7">{{ __("Options") }} </h4></div>
    <div class="col-lg-6 mb-4">
        <div class="form-check form-switch">
            <input type="hidden" name="receiving_orders" value="0">
            <input class="form-check-input h-20px w-20px p-0" type="checkbox" name="receiving_orders" id="receiving_orders"
                   value="1"  {{ old("receiving_orders", 0) === 1 ? 'checked' : '' }} />
            <label class="fw-semibold fs-7 mb-2" for="receiving_orders">
                {{ __('Receiving orders') }}
            </label>
        </div>
    </div>
    <div class="col-lg-6 mb-4 skipping">
        <div class="form-check form-switch">
            <input type="hidden" name="skipping" value="0">
            <input class="form-check-input h-20px w-20px p-0" type="checkbox" name="skipping" id="skipping"
                   value="1"  {{ old("receiving_orders", 0) === 1 ? 'checked' : '' }} />
            <label class="fw-semibold fs-7 mb-2" for="skipping">
                {{ __('Skipping cases') }}
            </label>
        </div>
    </div>

@endsection
@section('modal_edit_content')
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Full name") }}</label>
        <input type="text" name="name" id="name_edit" class="form-control form-control-sm" placeholder="{{ __("Full name") }}" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("User name") }}</label>
        <input type="text" name="username" id="username_edit" class="form-control form-control-sm" placeholder="{{ __("User name") }}" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Password") }}</label>
        <input type="password" name="password" class="form-control form-control-sm" placeholder="{{ __("Password") }}" />
        <span class="text-info d-block fw-bold mt-4 fs-7"> {{ __('leave it blank if you want to keep the old password') }} </span>
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Email") }}</label>
        <input type="email" name="email" id="email_edit" class="form-control form-control-sm" placeholder="example@domain.com" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Mobile") }}</label>
        <input oninput="input_number(this)" type="text" name="mobile" id="mobile_edit" class="form-control form-control-sm" placeholder="{{ __("Mobile") }}" />
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Country code") }}</label>
        <select name="country_code" data-control="select2" data-hide-search="false" id="country_code_input_edit" class="form-select form-select-sm" dir="ltr"
                data-dropdown-parent="#modal_edit" data-placeholder="{{ __("Select option") }}">
            <option></option>
            @include('layouts.partials.country_codes')
        </select>
    </div>
    <div class="col-lg-12 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Branches") }}</label>
        <select class="form-select form-select-sm branches branches_edit" data-control="select2"
                name="branches[]" id="branches_edit" data-close-on-select="true" data-dropdown-parent="#modal_edit"
                data-placeholder="{{ __('Select option') }}" data-allow-clear="true" multiple="multiple">
            @foreach($branches as $item)
                <option value="{{ $item["id"] }}">{{ getLocaleName($item, "name_ar", "name_en") }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-12 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Avatar") }}</label>
        <input type="file" name="avatar" accept="*/image" class="form-control form-control-sm"/>
        <span class="text-info d-block fw-bold mt-4 fs-7"> {{ __('leave it blank if you want to keep the old avatar') }} </span>
    </div>
    <div class="col-lg-6 mb-4">
        <label class="form-label fs-7 fw-semibold">{{ __("Roles") }}</label>
        <select class="form-select role form-select-sm roles" id="role_edit" data-kt-select2="true" data-placeholder="{{ __("Select option") }}"
                data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="false" name="role" data-dropdown-parent="#modal_edit">
            <option></option>
            @foreach($roles as $item)
                <option value="{{ $item["name"] }}">{{ $item["name"] }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-6 mb-4 supervising">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Supervising") }}</label>
        <select class="form-select form-select-sm supervising_user" id="supervising_edit" data-kt-select2="true" data-placeholder="{{ __("Select option") }}"
                data-allow-clear="false" data-kt-user-table-filter="supervising" data-hide-search="false" name="supervising" data-dropdown-parent="#modal_edit">
            <option></option>
            @foreach($users as $item)
                @if($item["role"] == "supervising")
                    <option value="{{ $item["id"] }}">{{ $item["name"] }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="separator separator-dashed separator-content border-gray-400 my-8"><h4 class="w-200px text-gray-700 fs-7">{{ __("Options") }} </h4></div>
    <div class="col-lg-6 mb-4">
        <div class="form-check form-switch">
            <input type="hidden" name="receiving_orders" value="0">
            <input class="form-check-input h-20px w-20px p-0" type="checkbox" name="receiving_orders" id="edit_receiving_orders"
                   value="1"  {{ old("receiving_orders", 0) === 1 ? 'checked' : '' }} />
            <label class="fw-semibold fs-7 mb-2" for="edit_receiving_orders">
                {{ __('Receiving orders') }}
            </label>
        </div>
    </div>
    <div class="col-lg-6 mb-4 skipping">
        <div class="form-check form-switch">
            <input type="hidden" name="skipping" value="0">
            <input class="form-check-input h-20px w-20px p-0" type="checkbox" name="skipping" id="edit_skipping"
                   value="1"  {{ old("receiving_orders", 0) === 1 ? 'checked' : '' }} />
            <label class="fw-semibold fs-7 mb-2" for="edit_skipping">
                {{ __('Skipping cases') }}
            </label>
        </div>
    </div>
@endsection
@section("script")
    @include('users.users_management.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')

    <script>
        $(function () {
            SelectGetData($('.roles'), "{{ route( 'get_roles' ) }}", false);
            SelectGetData($('.supervising_user'), "{{ route( 'get_supervising' ) }}", false);

            $(".skipping").hide()
            $(".supervising").hide()
            $(".role").change(function () {
                var RoleID = this.value;
                if (RoleID === "physician") {
                    $(".supervising").show(300)
                } else {
                    $(".supervising").hide(300)
                }
                if (RoleID === "supervising") {
                    $(".skipping").show(300)
                } else {
                    $(".skipping").hide(300)
                }
            })

        });

    </script>
@endsection

