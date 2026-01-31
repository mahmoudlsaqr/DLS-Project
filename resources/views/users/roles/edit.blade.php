@extends('layouts.master', [
    "Title" => __("Edit role"),
    "breadcrumb_main" => __("Permissions & roles")
])
@section("toolbar_buttons")
    @include("layouts.partials.back_button", ["link" => route("roles.index"), "label" => __("Role list")])
@endsection
@section('content')
    <div class="card">
        <form action="{{ route('roles.update', $role["id"]) }}" method="POST">
            @csrf @method('PUT')
            <div class="card-body">
                @if ($role->default != 'true')
                <div class="mb-3">
                    <label class="form-label required fw-semibold fs-7" for="name">{{ __("Role Name") }}</label>
                    <input type="text" name="name" id="name" value="{{ $role->name }}" class="form-control form-control-sm" />
                </div>
                <!--begin::Example-->
                <div class="separator separator-dashed separator-content border-gray-400 my-10"><h4 class="mb-4 text-gray-700 fs-6">{{ __("Permissions") }} </h4></div>
                <!--end::Example-->
                @else
                    <input type="hidden" name="name" value="{{ $role->name }}" />
                @endif
                    <h3 class="title mb-4">{{ __("Users management") }}</h3>
                    @php $module = 'users'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'roles'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    <h3 class="title mb-4">{{ __("Cases") }}</h3>
                    @php $module = 'cases'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'orders'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'attachments'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'receipt_vouchers'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    <h3 class="title mb-4">{{ __("Work orders") }}</h3>
                    @php $module = 'work_orders_management'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'delivering_work_orders'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'doctors_requests'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    <h3 class="title mb-4">{{ __("Invoices") }}</h3>
                    @php $module = 'invoices'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    <h3 class="title mb-4">{{ __("Settings") }}</h3>
                    <h4 class="title text-gray-600 mb-4">{{ __("System settings") }}</h4>
                    @php $module = 'branches'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'settings'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'notifications'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    <h4 class="title text-gray-600 mb-4">{{ __("Regional settings") }}</h4>
                    @php $module = 'regions'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'cities'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'districts'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'nationalities'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    <h4 class="title text-gray-600 mb-4">{{ __("Data settings") }}</h4>
                    @php $module = 'services_categories'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'services'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'color_codes'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'working_phases'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'sections'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'clients'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'technicians'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'doctors'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'special_offers'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    <h3 class="title mb-4">{{ __("Reports") }}</h3>
                    @php $module = 'cases_report'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'receipts_report'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'work_orders_report'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'work_phases_statistics_report'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

{{--                    @php $module = 'work_orders_statistics_report'; @endphp--}}
{{--                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>--}}
{{--                    <div class="row input-group form-check form-switch form-check">--}}
{{--                        @include("users.roles.partials.edit_checkboxes")--}}
{{--                    </div>--}}
{{--                    <div class="separator separator-dotted border-gray-300 my-5"></div>--}}

                    @php $module = 'revenue_report'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'services_sales_report'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>
                    <div class="separator separator-dotted border-gray-300 my-5"></div>

                    @php $module = 'performance_evaluation_report'; @endphp
                    <h5 class="title text-primary">{{ __(ucfirst(str_replace('_', ' ', $module))) }}</h5>
                    <div class="row input-group form-check form-switch form-check">
                        @include("users.roles.partials.edit_checkboxes")
                    </div>

            </div>
            <div class="card-footer">
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary"> {{ __("Update") }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section("script")
    <script>
        $(function() {

            function checkBoxs(Parent, Child) {
                Parent.click(function() {
                    Child.prop('checked', $(this).prop('checked'));
                });

                Child.change(function() {
                    let allChecked = Child.not(':checked').length === 0;
                    Parent.prop('checked', allChecked);
                }); // End users managements checkboxes all
            }

            let usersParent = $('#users');
            let usersChildren = $(".users");

            let rolesParent = $('#roles');
            let rolesChildren = $(".roles");

            let attachmentsParent = $('#attachments');
            let attachmentsChildren = $(".attachments");

            let casesParent = $('#cases');
            let casesChildren = $(".cases");

            let ordersParent = $('#orders');
            let ordersChildren = $(".orders");

            let branchesParent = $('#branches');
            let branchesChildren = $(".branches");

            let receiptVouchersParent = $('#receipt_vouchers');
            let receiptVouchersChildren = $(".receipt_vouchers");

            let regionsParent = $('#regions');
            let regionsChildren = $(".regions");

            let citiesParent = $('#cities');
            let citiesChildren = $(".cities");

            let districtsParent = $('#districts');
            let districtsChildren = $(".districts");

            let nationalitiesParent = $('#nationalities');
            let nationalitiesChildren = $(".nationalities");

            let casesReportParent = $('#cases_report');
            let casesReportChildren = $(".cases_report");

            let receiptsReportParent = $('#receipts_report');
            let receiptsReportChildren = $(".receipts_report");

            let settingsParent = $('#settings');
            let settingsChildren = $(".settings");

            let servicesCategoriesParent = $('#services_categories');
            let servicesCategoriesChildren = $(".services_categories");

            let servicesParent = $('#services');
            let servicesChildren = $(".services");

            let colorCodesParent = $('#color_codes');
            let colorCodesChildren = $(".color_codes");

            let clientsParent = $('#clients');
            let clientsChildren = $(".clients");

            let workingPhasesParent = $('#working_phases');
            let workingPhasesChildren = $(".working_phases");

            let sectionsParent = $('#sections');
            let sectionsChildren = $(".sections");

            let doctorsParent = $('#doctors');
            let doctorsChildren = $(".doctors");

            let techniciansParent = $('#technicians');
            let techniciansChildren = $(".technicians");

            let specialOffersParent = $('#special_offers');
            let specialOffersChildren = $(".special_offers");

            let workOrdersManagementParent = $('#work_orders_management');
            let workOrdersManagementChildren = $(".work_orders_management");

            let deliveringWorkOrdersParent = $('#delivering_work_orders');
            let deliveringWorkOrdersChildren = $(".delivering_work_orders");

            let workPhasesStatisticsReportParent = $('#work_phases_statistics_report');
            let workPhasesStatisticsReportChildren = $(".work_phases_statistics_report");

            // let workOrdersStatisticsReportParent = $('#work_orders_statistics_report');
            // let workOrdersStatisticsReportChildren = $(".work_orders_statistics_report");

            let workOrdersReportParent = $('#work_orders_report');
            let workOrdersReportChildren = $(".work_orders_report");

            let doctorsRequestsParent = $('#doctors_requests');
            let doctorsRequestsChildren = $(".doctors_requests");

            let invoicesParent = $('#invoices');
            let invoicesChildren = $(".invoices");

            let revenueReportParent = $('#revenue_report');
            let revenueReportChildren = $(".revenue_report");

            let serviceSalesReportParent = $('#services_sales_report');
            let serviceSalesReportChildren = $(".services_sales_report");

            let performanceEvaluationReportParent = $('#performance_evaluation_report');
            let performanceEvaluationReportChildren = $(".performance_evaluation_report");

            let notificationsParent = $('#notifications');
            let notificationsChildren = $(".notifications");

            checkBoxs(usersParent, usersChildren)
            checkBoxs(rolesParent, rolesChildren)
            checkBoxs(casesParent, casesChildren)
            checkBoxs(ordersParent, ordersChildren)
            checkBoxs(branchesParent, branchesChildren)
            checkBoxs(receiptVouchersParent, receiptVouchersChildren)
            checkBoxs(regionsParent, regionsChildren)
            checkBoxs(citiesParent, citiesChildren)
            checkBoxs(districtsParent, districtsChildren)
            checkBoxs(nationalitiesParent, nationalitiesChildren)
            checkBoxs(casesReportParent, casesReportChildren)
            checkBoxs(receiptsReportParent, receiptsReportChildren)
            checkBoxs(settingsParent, settingsChildren)
            checkBoxs(servicesCategoriesParent, servicesCategoriesChildren)
            checkBoxs(servicesParent, servicesChildren)
            checkBoxs(colorCodesParent, colorCodesChildren)
            checkBoxs(attachmentsParent, attachmentsChildren)
            checkBoxs(clientsParent, clientsChildren)
            checkBoxs(workingPhasesParent, workingPhasesChildren)
            checkBoxs(sectionsParent, sectionsChildren)
            checkBoxs(doctorsParent, doctorsChildren)
            checkBoxs(techniciansParent, techniciansChildren)
            checkBoxs(workOrdersManagementParent, workOrdersManagementChildren)
            checkBoxs(doctorsRequestsParent, doctorsRequestsChildren)
            checkBoxs(workOrdersReportParent, workOrdersReportChildren)
            checkBoxs(invoicesParent, invoicesChildren)
            checkBoxs(specialOffersParent, specialOffersChildren)
            checkBoxs(revenueReportParent, revenueReportChildren)
            // checkBoxs(workOrdersStatisticsReportParent, workOrdersStatisticsReportChildren)
            checkBoxs(serviceSalesReportParent, serviceSalesReportChildren)
            checkBoxs(workPhasesStatisticsReportParent, workPhasesStatisticsReportChildren)
            checkBoxs(deliveringWorkOrdersParent, deliveringWorkOrdersChildren)
            checkBoxs(performanceEvaluationReportParent, performanceEvaluationReportChildren)
            checkBoxs(notificationsParent, notificationsChildren)

        });
    </script>
@endsection
