<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-header d-flex flex-stack d-none d-lg-flex pt-8 pb-2" id="kt_app_sidebar_header">
        <!--begin::Logo-->
        <a href="{{ url("/dashboard") }}" class="app-sidebar-logo">
            <img alt="Logo" src="{{ asset("uploads/logo/logo-light-{$locale}.png") }}" class="h-30px h-lg-60px d-none d-sm-inline app-sidebar-logo-default theme-light-show" />
            <img alt="Logo" src="{{ asset("uploads/logo/logo-dark-{$locale}.png") }}" class="h-30px h-lg-60px theme-dark-show" />
        </a>
        <!--end::Logo-->
        <!--begin::Sidebar toggle-->
        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-sm btn-icon bg-light btn-color-gray-700 btn-active-color-primary d-none d-lg-flex rotate"
             data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-outline ki-text-align-right rotate-180 fs-1"></i>
        </div>
        <!--end::Sidebar toggle-->
    </div>

    @php
        use Illuminate\Support\Facades\Route;

        $route_name = Route::currentRouteName();

        $permissionsCases = [
            'view_cases', 'view_receipt_vouchers', 'view_orders'
        ];

        $permissionsWorkOrders = [
            'view_work_orders_management', 'create_work_orders_management', 'update phase_work_orders_management', 'view_delivering_work_orders', 'view_doctors_requests'
        ];

        $permissionsInvoices = [
            'view_invoices', 'issue_invoices',
        ];
        $permissionsUsers = ['view_users', 'view_roles' ];
        $permissionsReport = ['view_cases_report', 'view_receipts_report', 'view_work_phases_statistics_report', 'view_work_orders_statistics_report', 'view_work_orders_report', 'view_revenue_report', 'view_services_sales_report', 'view_performance_evaluation_report' ];

        $permissionsSettings = [
            'view_settings', 'view_branches', 'view_regions', 'view_cities',
            'view_districts', 'view_nationalities', 'view_services', 'view_services_categories',
            'view_color_codes', 'view_working_phases', 'view_sections', 'view_clients', 'view_technicians', 'view_doctors', 'view_special_offers'
        ];

        $permissionsSystemSettings = ['view_settings', 'view_branches' ];
        $permissionsDataSettings = [
            'view_services', 'view_services_categories', 'view_color_codes', 'view_working_phases',
            'view_sections', 'view_clients', 'view_technicians', 'view_doctors', 'view_work_orders_stages', 'view_special_offers' ];
        $permissionsRegionalSettings = [
            'view_regions', 'view_cities', 'view_districts', 'view_nationalities'
        ];

        $hasPermissionCases = function ($perm) use ($permissionsCases) {
            return auth()->user()->can($perm);
        };
        $hasPermissionWorkOrders = function ($perm) use ($permissionsWorkOrders) {
            return auth()->user()->can($perm);
        };
        $hasPermissionInvoices = function ($perm) use ($permissionsInvoices) {
            return auth()->user()->can($perm);
        };
        $hasPermissionUsers = function ($perm) use ($permissionsUsers) {
            return auth()->user()->can($perm);
        };
        $hasPermissionsReport = function ($perm) use ($permissionsReport) {
            return auth()->user()->can($perm);
        };
        $hasPermissionSettings = function ($perm) use ($permissionsSettings) {
            return auth()->user()->can($perm);
        };

        $activeMenu = function ($routePrefix) use ($route_name) {
            return str_contains($route_name, $routePrefix) ? 'show here' : '';
        };

        $activeLink = function ($routePrefix) use ($route_name) {
            return str_contains($route_name, $routePrefix) ? 'active' : '';
        };
    @endphp

    <!--begin::Navs-->
    <div class="app-sidebar-navs flex-column-fluid py-6" id="kt_app_sidebar_navs">
        <div id="kt_app_sidebar_navs_wrappers" class="app-sidebar-wrapper hover-scroll-y my-2" data-kt-scroll="true" data-kt-scroll-activate="true"
             data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_header" data-kt-scroll-wrappers="#kt_app_sidebar_navs" data-kt-scroll-offset="5px">

            <!--begin::Teams-->
            <div class="app-sidebar-menu-secondary menu menu-rounded menu-column mb-3">
                <!--begin::Menu Item-->
                <div class="menu-item">
                    <!--begin::Menu Home-->
                    <a class="menu-link {{ $activeLink('dashboard') }}" href="{{ url("/dashboard") }}">
                        <!--begin::Icon-->
                        <span class="menu-icon"> <i class="ki-outline ki-home-3 fs-2"></i> </span>
                        <!--end::Icon-->
                        <!--begin::Title-->
                        <span class="menu-title">{{ __("Home") }}</span>
                        <!--end::Title-->
                    </a>
                    <!--end::Menu Home-->
                </div>
                <!--end::Menu Item-->
            </div>
            <!--end::Teams-->

            <!--begin::Teams-->
            <div class="app-sidebar-menu-secondary menu menu-rounded menu-column mb-3">
                <!--begin::Menu Item-->
                <div class="menu-item">
                    <!--begin::Menu Home-->
                    <a class="menu-link {{ $activeLink('landing') }}" href="{{ route("landing") }}" target="_blank">
                        <!--begin::Icon-->
                        <span class="menu-icon"> <i class="ki-outline ki-abstract-20 fs-2"></i> </span>
                        <!--end::Icon-->
                        <!--begin::Title-->
                        <span class="menu-title">{{ __("Landing") }}</span>
                        <!--end::Title-->
                    </a>
                    <!--end::Menu Home-->
                </div>
                <!--end::Menu Item-->
            </div>
            <!--end::Teams-->

            <!--begin::Sidebar menu-->
            <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                 class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary">
                <!--begin::Heading-->
                <div class="menu-item mb-2">
                    <div class="menu-heading text-uppercase fs-7 fw-bold">{{ __("Menu") }}</div>
                    <!--begin::Separator-->
                    <div class="app-sidebar-separator separator"></div>
                    <!--end::Separator-->
                </div>
                <!--end::Heading-->

                @if(collect($permissionsCases)->contains($hasPermissionCases))
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $activeMenu('cases.') }}">
                        <!--begin:Menu Cases-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-status fs-2"></i>
                            </span>
                            <span class="menu-title">{{ __("Cases") }}</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu Cases-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            @foreach(['orders' => 'orders', 'cases' => 'cases_list', 'receipt_vouchers' => 'receipt_vouchers'] as $item => $route)
                                @if($hasPermissionSettings("view_$item"))
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <a class="menu-link {{ $activeLink("cases.$route.") }}"
                                           href="{{ route("cases.$route.index") }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-vertical"></span>
                                            </span>
                                            <span class="menu-title">{{ __(ucfirst(str_replace('_', ' ', $item))) }}</span>
                                        </a>
                                    </div>
                                    <!--end:Menu item-->
                                @endif
                            @endforeach
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end:Menu item-->
                @endif

                @if(collect($permissionsWorkOrders)->contains($hasPermissionWorkOrders))
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $activeMenu('work_orders.') }}">
                        <!--begin:Menu Work Orders-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-note fs-2"></i>
                            </span>
                            <span class="menu-title">{{ __("Work orders") }}</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu Work Orders-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            @if($hasPermissionWorkOrders("view_work_orders_management"))
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    @php
                                        $activeRoutes = [
                                            "work_orders.work_orders_management.create",
                                            "work_orders.work_orders_management.update_work_orders_phases.index",
                                            "work_orders.work_orders_management.update_work_orders_phases.search",
                                            "work_orders.work_orders_management.delivering_work_orders.index"
                                        ];
                                    @endphp
                                    <a class="menu-link {{ !in_array($route_name, $activeRoutes) ? $activeLink("work_orders.work_orders_management.") : '' }}"
                                       href="{{ route("work_orders.work_orders_management.index") }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-vertical"></span>
                                        </span>
                                        <span class="menu-title">{{ __("Work orders management") }}</span>
                                    </a>
                                </div>
                                <!--end:Menu item-->
                            @endif

                        @if($hasPermissionWorkOrders("create_work_orders_management"))
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <a class="menu-link {{ $activeLink("work_orders.work_orders_management.create") }}"
                                       href="{{ route("work_orders.work_orders_management.create") }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-vertical"></span>
                                        </span>
                                        <span class="menu-title">{{ __(ucfirst(str_replace('_', ' ', "create_work_order"))) }}</span>
                                    </a>
                                </div>
                                <!--end:Menu item-->
                            @endif
                            @if($hasPermissionWorkOrders("update phase_work_orders_management"))
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <a class="menu-link {{ $activeLink("work_orders.work_orders_management.update_work_orders_phases.index") }}"
                                       href="{{ route("work_orders.work_orders_management.update_work_orders_phases.index") }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-vertical"></span>
                                        </span>
                                        <span class="menu-title">{{ __(ucfirst(str_replace('_', ' ', "update_work_orders_phases"))) }}</span>
                                    </a>
                                </div>
                                <!--end:Menu item-->
                            @endif
                            @if($hasPermissionWorkOrders("view_delivering_work_orders"))
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <a class="menu-link {{ $activeLink("work_orders.work_orders_management.delivering_work_orders.index") }}"
                                       href="{{ route("work_orders.work_orders_management.delivering_work_orders.index") }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-vertical"></span>
                                        </span>
                                        <span class="menu-title">{{ __(ucfirst(str_replace('_', ' ', "delivering_work_orders"))) }}</span>
                                    </a>
                                </div>
                                <!--end:Menu item-->
                            @endif

                            @if($hasPermissionWorkOrders("view_doctors_requests"))
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <a class="menu-link {{ $activeLink("work_orders.doctors-requests.") }}"
                                   href="{{ route("work_orders.doctors-requests.index") }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-vertical"></span>
                                    </span>
                                    <span class="menu-title">{{ __(ucfirst(str_replace('_', ' ', "doctors_requests"))) }}</span>
                                </a>
                            </div>
                            <!--end:Menu item-->
                            @endif
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end:Menu item-->
                @endif

                @if(collect($permissionsInvoices)->contains($hasPermissionInvoices))
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $activeMenu('invoices.') }}">
                        <!--begin:Menu Invoices-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-receipt-square fs-2"></i>
                            </span>
                            <span class="menu-title">{{ __("Invoices") }}</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu Invoices-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion">
                            @if($hasPermissionInvoices("issue_invoices"))
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <a class="menu-link {{ $activeLink("invoices.issue_invoice.") }}"
                                       href="{{ route("invoices.issue_invoice.index") }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-vertical"></span>
                                        </span>
                                        <span class="menu-title">{{ __(ucfirst(str_replace('_', ' ', 'issue_invoice'))) }}</span>
                                    </a>
                                </div>
                                <!--end:Menu item-->
                            @endif
                            @if($hasPermissionInvoices("view_invoices"))
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <a class="menu-link {{ $activeLink("invoices.invoices_list.") }}"
                                       href="{{ route("invoices.invoices_list.index") }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-vertical"></span>
                                        </span>
                                        <span class="menu-title">{{ __(ucfirst(str_replace('_', ' ', 'follow_up_invoice'))) }}</span>
                                    </a>
                                </div>
                                <!--end:Menu item-->
                            @endif
                        </div>
                        <!--end:Menu sub-->
                    </div>
                    <!--end:Menu item-->
                @endif

                @if(collect($permissionsUsers)->contains($hasPermissionUsers))
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $activeMenu('users.') }} {{ $activeMenu('roles.') }}">
                    <!--begin:Menu Users-->
                    <span class="menu-link ">
                        <span class="menu-icon">
                            <i class="ki-outline ki-user fs-2"></i>
                        </span>
                        <span class="menu-title">{{ __("Users") }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu Users-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @foreach(['users', 'roles'] as $item)
                            @if($hasPermissionUsers("view_$item"))
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <a class="menu-link {{ $activeLink("$item.") }}"
                                       href="{{ route("$item.index") }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-vertical"></span>
                                        </span>
                                        <span class="menu-title">{{ __(ucfirst($item)) }}</span>
                                    </a>
                                </div>
                                <!--end:Menu item-->
                            @endif
                        @endforeach
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
                @endif

                @if(collect($permissionsSettings)->contains($hasPermissionSettings))
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $activeMenu('settings.') }}">
                        <!--begin:Menu Settings-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-setting fs-2"></i>
                            </span>
                            <span class="menu-title">{{ __("Settings") }}</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu Settings-->

                        @if(collect($permissionsRegionalSettings)->contains($hasPermissionSettings))
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">
                                <!--begin:Menu item-->
                                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $activeMenu('settings.regional_settings.') }}">
                                    <!--begin:Menu Regional-->
                                    <span class="menu-link">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-vertical"></span>
                                        </span>
                                        <span class="menu-title">{{ __("Regional settings") }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <!--end:Menu Regional-->

                                    <!--begin:Menu sub-->
                                    <div class="menu-sub menu-sub-accordion">
                                        @foreach(['regions', 'cities', 'districts', 'nationalities'] as $item)
                                            @if($hasPermissionSettings("view_$item") || $hasPermissionSettings("menu_$item"))
                                                <!--begin:Menu item-->
                                                <div class="menu-item">
                                                    <a class="menu-link {{ $activeLink("settings.regional_settings.$item.") }}"
                                                       href="{{ route("settings.regional_settings.$item.index") }}">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-vertical"></span>
                                                        </span>
                                                        <span class="menu-title">{{ __(ucfirst($item)) }}</span>
                                                    </a>
                                                </div>
                                                <!--end:Menu item-->
                                            @endif
                                        @endforeach
                                    </div>
                                    <!--end:Menu sub-->
                                </div>
                                <!--end:Menu item-->
                            </div>
                            <!--end:Menu sub-->
                        @endif

                        @if(collect($permissionsDataSettings)->contains($hasPermissionSettings))
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">
                                <!--begin:Menu item-->
                                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $activeMenu('settings.data_settings.') }}">
                                    <!--begin:Menu link-->
                                    <span class="menu-link">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-vertical"></span>
                                        </span>
                                        <span class="menu-title">{{ __("Data settings") }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <!--end:Menu link-->

                                    <!--begin:Menu sub-->
                                    <div class="menu-sub menu-sub-accordion">
                                        @foreach(['services_categories', 'services', 'color_codes', 'working_phases', 'sections', 'clients', 'technicians', 'doctors', 'special_offers'] as $item)
                                            @if($hasPermissionSettings("view_$item"))
                                                <!--begin:Menu item-->
                                                <div class="menu-item">
                                                    <a class="menu-link {{ $activeLink("settings.data_settings.$item.") }}"
                                                       href="{{ route("settings.data_settings.$item.index") }}">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-vertical"></span>
                                                        </span>
                                                        <span class="menu-title">{{ __(ucfirst(str_replace('_', ' ', $item))) }}</span>
                                                    </a>
                                                </div>
                                                <!--end:Menu item-->
                                            @endif
                                        @endforeach
                                    </div>
                                    <!--end:Menu sub-->
                                </div>
                                <!--end:Menu item-->
                            </div>
                            <!--end:Menu sub-->
                        @endif

                        @if(collect($permissionsSystemSettings)->contains($hasPermissionSettings))
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">
                                <!--begin:Menu item-->
                                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $activeMenu('settings.system_settings.') }}">
                                    <!--begin:Menu link-->
                                    <span class="menu-link">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-vertical"></span>
                                        </span>
                                        <span class="menu-title">{{ __("System settings") }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <!--end:Menu link-->

                                    <!--begin:Menu sub-->
                                    <div class="menu-sub menu-sub-accordion">
                                        @foreach(['branches' => 'branches', 'settings' => 'general_settings'] as $item => $route)
                                            @if($hasPermissionSettings("view_$item"))
                                                <!--begin:Menu item-->
                                                <div class="menu-item">
                                                    <a class="menu-link {{ $activeLink("settings.system_settings.$route.") }}"
                                                       href="{{ route("settings.system_settings.$route.index") }}">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-vertical"></span>
                                                        </span>
                                                        <span class="menu-title">{{ __(ucfirst(str_replace('_', ' ', $item))) }}</span>
                                                    </a>
                                                </div>
                                                <!--end:Menu item-->
                                            @endif
                                        @endforeach
                                    </div>
                                    <!--end:Menu sub-->
                                </div>
                                <!--end:Menu item-->
                            </div>
                            <!--end:Menu sub-->
                        @endif

                    </div>
                    <!--end:Menu item-->
                @endif

                @if(collect($permissionsReport)->contains($hasPermissionsReport))
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $activeMenu('reports.') }}">
                    <!--begin:Menu Reports-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-search-list fs-2"></i>
                        </span>
                        <span class="menu-title">{{ __("Reports") }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu Reports-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @foreach(['cases_report', 'receipts_report', 'work_orders_report', 'work_phases_statistics_report', 'work_orders_statistics_report', 'revenue_report', 'services_sales_report', 'performance_evaluation_report'] as $item)
                            @if($hasPermissionsReport("view_$item"))
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <a class="menu-link {{ $activeLink("reports.$item.") }}"
                                       href="{{ route("reports.$item.index") }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-vertical"></span>
                                        </span>
                                        <span class="menu-title">{{ __(ucfirst(str_replace('_', ' ', $item))) }}</span>
                                    </a>
                                </div>
                                <!--end:Menu item-->
                            @endif
                        @endforeach
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
                @endif
            </div>
            <!--end::Sidebar menu-->
        </div>
    </div>
    <!--end::Navs-->
</div>
<!--end::Sidebar-->
