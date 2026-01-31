<!--begin::Header-->
<div id="kt_app_header" class="app-header">
    <!--begin::Header container-->
    <div class="app-container container-fluid d-flex align-items-stretch flex-stack" id="kt_app_header_container">
        <!--begin::Sidebar toggle-->
        <div class="d-flex align-items-center d-block d-lg-none ms-n3" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px me-2" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>
            <!--begin::Logo image-->
            <a href="#">
                <img alt="Logo" src="#" class="h-30px" />
            </a>
            <!--end::Logo image-->
        </div>
        <!--end::Sidebar toggle-->
        <!--begin::Navbar-->
        <div class="app-navbar flex-lg-grow-1" id="kt_app_header_navbar">
            <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1">
                <!--begin::Search-->
                <!--end::Search-->
            </div>

            @if(auth()->user()->can("view_notifications"))
            <div class="app-navbar-item me-4 ms-1 ms-md-3">
                <!--begin::Menu- wrapper-->
                <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative"
                     data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                     data-kt-menu-attach="parent"
                     data-kt-menu-placement="bottom-{{ $locale == 'ar' ? 'start' : 'end' }}"
                     id="kt_menu_item_wow">

                    <i class="ki-outline ki-notification-on fs-1"></i>

                </div>

                <!--begin::Menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-450px" data-kt-menu="true" id="kt_menu_notifications" style="">
                    <!--begin::Heading-->
                    <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('{{ asset("assets/media/misc/menu-header-dark.png") }}'); background-size: cover">
                        <!--begin::Title-->
                        <h3 class="text-white fw-semibold px-9 mt-10 mb-6">
                            {{ __("Notifications") }}
                        </h3>
                        <!--end::Title-->

                        <!--begin::Tabs-->
                        <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab" href="#notifications_1" aria-selected="true" role="tab" tabindex="-1">{{ __("Late work orders") }}</a>
                            </li>
                        </ul>
                        <!--end::Tabs-->
                    </div>
                    <!--end::Heading-->

                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab panel-->
                        <div class="tab-pane fade show active" id="notifications_1" role="tabpanel">
                            <!--begin::Items-->
                            <div class="scroll-y mh-325px my-5 px-4 py-2" id="logs-list"></div>
                            <!--end::Items-->
                            <!--begin::View more-->
                            <div class="py-2 text-center border-top">
                                <a href="{{ route("work_orders.late_work_orders.index") }}" href="#" class="btn btn-color-gray-600 btn-active-color-primary fs-7">
                                    {{ __("View All") }}
                                </a>
                            </div>
                            <!--end::View more-->
                        </div>
                        <!--end::Tab panel-->

                    </div>
                    <!--end::Tab content-->
                </div>
                <!--end::Menu-->
                <!--end::Menu wrapper-->
            </div>
            @endif

            <!--begin::User menu-->
            <div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
                <!--begin::Menu wrapper-->
                <div class="cursor-pointer symbol symbol-circle symbol-35px symbol-md-45px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                     data-kt-menu-placement='bottom-{{ ($locale == "ar")? "start" : "end" }}'>
                    @if($User ->avatar !== null)
                        <img src="{{ asset("/uploads/avatars/".$User ->avatar) }}" alt="{{ $User ->avatar }}/{{ $User ->id }}" />
                    @else
                        <img src="{{ asset("/uploads/avatars/blank.png") }}" alt="blank" />
                    @endif
                </div>
                <!--begin::User account menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-7 w-350px" data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5">
                                @if($User ->avatar !== null)
                                    <img src="{{ asset("/uploads/avatars/".$User ->avatar) }}" alt="{{ $User ->avatar }}/{{ $User ->id }}" />
                                @else
                                    <img src="{{ asset("/uploads/avatars/blank.png") }}" alt="blank" />
                                @endif
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">{{ $User["name"] }}
                                    <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">{{ $User->roles->value("name") }}</span></div>
                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ $User["email"] }}</a>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    <div class="separator my-2"></div>
                    <!--begin::Menu item-->
                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement='{{ ($locale == "ar")? "right" : "left" }}-start' data-kt-menu-offset="-15px, 0">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-title position-relative">{{ __("Mode") }}
                                <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                    <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                    <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                                </span>
                            </span>
                        </a>
                        <!--begin::Menu-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-night-day fs-2"></i>
                                    </span>
                                    <span class="menu-title">{{ __("Light") }}</span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-moon fs-2"></i>
                                    </span>
                                    <span class="menu-title">{{ __("Dark") }}</span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-screen fs-2"></i>
                                    </span>
                                    <span class="menu-title">{{ __("System") }}</span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement='{{ ($locale == "ar")? "right" : "left" }}-start' data-kt-menu-offset="-15px, 0">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-title position-relative">{{ __("Language") }}
                            <span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">{{ LaravelLocalization::getCurrentLocaleNative() }}
                            <img class="w-15px h-15px rounded-1 ms-2" src="{{ asset("assets/media/flags/".app()->getLocale().".svg") }}" alt="{{ app()->getLocale() }}" /></span></span>
                        </a>
                        <!--begin::Menu sub-->
                        <div class="menu-sub menu-sub-dropdown w-175px py-4">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="menu-link d-flex px-5 {{ ($localeCode == app()->getLocale())? "active" : "" }}">
                                        <span class="symbol symbol-20px me-4">
                                            <img class="rounded-1" src="{{asset("assets/media/flags/".$localeCode.".svg")}}" alt="{{ $properties['native'] }}" />
                                        </span> {{ $properties['native'] }}
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            @endforeach
                        </div>
                        <!--end::Menu sub-->
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    @include("layouts.partials.logout_button", ["type" => "link"])
                    <!--end::Menu item-->
                </div>
                <!--end::User account menu-->
                <!--end::Menu wrapper-->
            </div>
            <!--end::User menu-->

        </div>
        <!--end::Navbar-->
        <!--begin::Separator-->
        <div class="app-navbar-separator separator d-none d-lg-flex"></div>
        <!--end::Separator-->
    </div>
    <!--end::Header container-->
</div>
<!--end::Header-->
