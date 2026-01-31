<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cases\CaseAttachmentsController;
use App\Http\Controllers\Cases\CasesController;
use App\Http\Controllers\Cases\Orders\OrderAttachmentsController;
use App\Http\Controllers\Cases\Orders\OrdersController;
use App\Http\Controllers\Cases\ReceiptVouchersController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GetDataController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Invoices\InvoicesController;
use App\Http\Controllers\Invoices\IssueInvoicesController;
use App\Http\Controllers\Reports\ReportCasesController;
use App\Http\Controllers\Reports\ReportPerformanceEvaluationController;
use App\Http\Controllers\Reports\ReportReceiptsController;
use App\Http\Controllers\Reports\ReportRevenueController;
use App\Http\Controllers\Reports\ReportServicesSalesController;
use App\Http\Controllers\Reports\ReportWorkOrdersController;
use App\Http\Controllers\Reports\ReportWorkOrdersStatisticsController;
use App\Http\Controllers\Reports\ReportWorkPhasesStatisticsController;
use App\Http\Controllers\Settings\DataSettings\ClientsController;
use App\Http\Controllers\Settings\DataSettings\ColorCodesController;
use App\Http\Controllers\Settings\DataSettings\DoctorsController;
use App\Http\Controllers\Settings\DataSettings\DoctorsServicesDetailsController;
use App\Http\Controllers\Settings\DataSettings\SectionsController;
use App\Http\Controllers\Settings\DataSettings\ServicesCategoriesController;
use App\Http\Controllers\Settings\DataSettings\ServicesController;
use App\Http\Controllers\Settings\DataSettings\SpecialOffersController;
use App\Http\Controllers\Settings\DataSettings\TechniciansController;
use App\Http\Controllers\Settings\DataSettings\WorkingPhasesController;
use App\Http\Controllers\Settings\RegionalSettings\CitiesController;
use App\Http\Controllers\Settings\RegionalSettings\DistrictsController;
use App\Http\Controllers\Settings\RegionalSettings\NationalitiesController;
use App\Http\Controllers\Settings\RegionalSettings\RegionsController;
use App\Http\Controllers\Settings\SystemSettings\BranchesController;
use App\Http\Controllers\Settings\SystemSettings\SettingsController;
use App\Http\Controllers\Users\RoleController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\WorkOrders\DoctorsRequestsController;
use App\Http\Controllers\WorkOrders\LateWorkOrdersController;
use App\Http\Controllers\WorkOrders\WorkOrdersManagementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/* || ================== BEGIN - ROUTES ===================== || */
Route::group(
    ['prefix' => LaravelLocalization::setLocale(), 'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'session.timeout' ]], function(){

    Route::controller(Controller::class)->group(function () {
        Route::get('/','landing')->name('landing');
    });

    Auth::routes();
    Auth::routes(['register' => false]);

    define("LOOK_COUNT", 10);
    define("LIMIT_SELECT2", 50);

    Route::post('/logout',[LoginController::class, 'logout'])->name('logout');

    /* || ================== BEGIN CONTROLLER ROUTE'S ===================== || */
    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard','index')->name('dashboard');
    });
    /* || ================== END - HOME CONTROLLER ROUTE'S ===================== || */

    /* || ================== MIDDLEWARE GUEST ===================== || */
    Route::middleware(['auth', 'check_status'])->group( function () {

        /* || ================== BEGIN -ROLES ===================== || */
        Route::controller(GetDataController::class)->group(function () {
            // || --------------- GET NATIONALITIES #
            Route::get('/get_regions','getRegions')->name('get_regions');
            // || --------------- GET REGIONS #
            Route::get('/get_nationalities','getNationalities')->name('get_nationalities');
            // || --------------- GET CITIES #
            Route::get('/get_cities','getCities')->name('get_cities');
            // || --------------- GET DISTRICTS #
            Route::get('/get_districts','getDistricts')->name("get_districts");
            // || --------------- GET CITIES WHEN CHOICE REGIONS #
            Route::get('/get_cities_region/{id}','getCitiesRegion');
            // || --------------- GET DISTRICTS WHEN CHOICE CITIES #
            Route::get('/get_districts_city/{id}','getDistrictsCity');
            // || --------------- GET ROLES #
            Route::get('/get_roles','getRoles')->name('get_roles');
            // || --------------- GET SUPERVISING USERS #
            Route::get('/get_supervising','getSupervising')->name('get_supervising');
            // || --------------- GET BRANCHES #
            Route::get('/get_branches','getBranches')->name('get_branches');
            // || --------------- GET BRANCHES USER #
            Route::get('/get_branches_user','getBranchesUser')->name('get_branches_user');
            // || --------------- GET WORKING PHASES #
            Route::get('/get_phases','getPhases')->name('get_phases');
            // || --------------- GET WORKING PHASES WITHOUT ENTRY #
            Route::get('/get_phases_without_entry','getPhasesWithoutEntry')->name('get_phases_without_entry');
            // || --------------- GET SERVICES CATEGORIES #
            Route::get('/get_categories','getCategories')->name('get_categories');
            // || --------------- GET SERVICES CATEGORIES FROM SERVICES #
            Route::get('/get_categories_from_services','getCategoriesFromServices')->name('get_categories_from_services');
            // || --------------- CREATE SERVICE CODE #
            Route::get('/create_service_code/{id}','createServiceCode')->name('create_service_code');
            // || --------------- GET SECTIONS #
            Route::get('/get_sections','getSections')->name('get_sections');
            // || --------------- GET SECTIONS FROM TECHNICIANS #
            Route::get('/get_sections_from_technicians','getSectionsFromTechnicians')->name('get_sections_from_technicians');
            // || --------------- GET NATIONALITIES FROM TECHNICIANS #
            Route::get('/get_nationalities_from_technicians','getNationalitiesFromTechnicians')->name('get_nationalities_from_technicians');
            // || --------------- GET DOCTORS #
            Route::get('/get_doctors','getDoctors')->name('get_doctors');
            // || --------------- GET DOCTORS WHEN CHOICE BRANCH #
            Route::get('/get_branch_doctors/{id}','getBranchDoctors');
            // || --------------- GET CLIENTS WHEN CHOICE BRANCH #
            Route::get('/get_branch_clients/{id}','getBranchClients');
            // || --------------- GET CLIENT WHEN CHOICE DOCTOR #
            Route::get('/get_client_doctor/{id}','getClientDoctor');
            // || --------------- GET DOCTORS WHEN CHOICE CLIENT #
            Route::get('/get_client_doctors/{id}','getClientDoctors');
            // || --------------- GET CLIENTS #
            Route::get('/get_clients','getClients')->name('get_clients');
            // || --------------- GET CLIENTS FROM DOCTORS #
            Route::get('/get_clients_from_doctors','getClientsFromDoctors')->name('get_clients_from_doctors');
            // || --------------- GET NATIONALITIES FROM DOCTORS #
            Route::get('/get_nationalities_from_doctors','getNationalitiesFromDoctors')->name('get_nationalities_from_doctors');
            // || --------------- GET SERVICES CATEGORY FROM DOCTORS DETAILS #
            Route::get('/get_services_categories_from_doctors_details','getServicesCategoriesFromDoctorsDetails')->name('get_services_categories_from_doctors_details');
            // || --------------- GET SERVICES FROM DOCTORS DETAILS #
            Route::get('/get_services_from_doctors_details','getServicesFromDoctorsDetails')->name('get_services_from_doctors_details');
            // || --------------- GET SERVICES #
            Route::get('/get_services','getServices')->name('get_services');
            // || --------------- GET SERVICE DETAILS #
            Route::get('/get_doctor_service_details','getDoctorServiceDetails')->name('get_doctor_service_details');
            // || --------------- GET DOCTOR SERVICE DETAILS #
            Route::get('/get_doctor_services/{id}','getDoctorServices')->name('get_doctor_services');
            // || --------------- GET COLORS #
            Route::get('/get_colors','getColors')->name('get_colors');
            // || --------------- GET BRANCHES OF THE TECHNICIAN #
            Route::get('/get_technician_branches/{id}','getTechnicianBranches');
            // || --------------- GET PHASES OF THE TECHNICIAN #
            Route::get('/get_technician_phases/{id}','getTechnicianPhases');

            Route::get('/get_notifications','getNotifications')->name('get_notifications');
            Route::get('/get_users','getUsers')->name('get_users');

        });
        /* || ================== END -ROLES ===================== || */

        /* || ================== BEGIN -ROLES ===================== || */
        Route::resource('roles', RoleController::class);
        Route::prefix('roles')->controller(RoleController::class)->name('roles.')->group(function() {
            Route::delete('/{id}/destroy','destroy')->name('destroy');
            Route::get('/{id}/give_permissions','destroy')->name('addPermissionToRole');
            Route::put('/{id}/give_permissions','destroy')->name('givePermissionToRole');
        });
        /* || ================== END -ROLES ===================== || */

        /* || ================== BEGIN - USERS ===================== || */
        Route::prefix('users')->controller(UserController::class)->name('users.')->group(function() {
            Route::get('/','index')->name('index');
            Route::post('/store','store')->name('store');
            Route::get('/edit/{id}','edit')->name('edit');
            Route::post('/update/{id}','update')->name('update');
            Route::post('destroy/{id}','destroy')->name('destroy');
            Route::get('/destroy_all','destroy_all')->name('destroy_all');
            Route::get('/update_status/{id}','update_status')->name('update_status');
            Route::get('/update_status_all','update_status_all')->name('update_status_all');
        });
        /* || ================== END - USERS ===================== || */

        /* || ================== BEGIN - CASES ===================== || */
        Route::prefix('cases')->name('cases.')->group(function () {
            Route::prefix('cases_list')->controller(CasesController::class)->name('cases_list.')->group(function () {
                Route::get('/','index')->name('index');
                Route::get('/create','create')->name('create');
                Route::post('/store','store')->name('store');
                Route::get('/edit/{id}','edit')->name('edit');
                Route::post('/update/{id}','update')->name('update');
                Route::post('/destroy/{id}','destroy')->name('destroy');
                Route::get('/destroy_all','destroy_all')->name('destroy_all');
                Route::get('/accept_status/{id}','accept_status')->name('accept_status');
                Route::post('/reject_status','reject_status')->name('reject_status');
                Route::get('/receipt_voucher/{id}','receipt_voucher')->name('receipt_voucher');
                Route::post('/store_receipt_voucher','store_receipt_voucher')->name('store_receipt_voucher');
                Route::get('view/{id}','view')->name('view');
            });

            /* || ================== BEGIN - ATTACHMENTS ===================== || */
            Route::prefix('attachments')->controller(CaseAttachmentsController::class)->name('attachments.')->group(function () {
                Route::get('/{status}','index')->name('index');
                Route::post('/store','store')->name('store');
                Route::post('/destroy/{id}','destroy')->name('destroy');
                Route::get('/download/{id}','download')->name('download');
                Route::get('/view/{id}','view')->name('view');
            });
            /* || ================== END - ATTACHMENTS ===================== || */

            /* || ================== BEGIN - RECEIPT VOUCHERS ===================== || */
            Route::prefix('receipt_vouchers')->controller(ReceiptVouchersController::class)->name('receipt_vouchers.')->group(function () {
                Route::get('/','index')->name('index');
                Route::get('/edit/{id}','edit')->name('edit');
                Route::post('/update/{id}','update')->name('update');
                Route::get('/download/{id}','download')->name('download');
            });
            /* || ================== END - RECEIPT VOUCHERS ===================== || */

            /* || ================== BEGIN - ORDERS ===================== || */
            Route::prefix('orders')->controller(OrdersController::class)->name('orders.')->group(function () {
                Route::get('/','index')->name('index');
                Route::get('/view/{id}','view')->name('view');
                Route::get('/create','create')->name('create');
                Route::post('/store','store')->name('store');
                Route::get('/edit/{id}','edit')->name('edit');
                Route::get('/create_case/{id}','create_case')->name('create_case');
                Route::post('/update/{id}','update')->name('update');
                Route::post('/destroy/{id}','destroy')->name('destroy');
                Route::get('/destroy_all','destroy_all')->name('destroy_all');
                Route::get('/approve_status/{id}','approve_status')->name('approve_status');
                Route::post('/accept_status','accept_status')->name('accept_status');
                Route::post('/reject_status','reject_status')->name('reject_status');

                /* || ================== BEGIN - ATTACHMENTS ===================== || */
                Route::prefix('attachments')->controller(OrderAttachmentsController::class)->name('attachments.')->group(function () {
                    Route::get('/{status}','index')->name('index');
                    Route::post('/store','store')->name('store');
                    Route::post('/destroy/{id}','destroy')->name('destroy');
                    Route::get('/download/{id}','download')->name('download');
                    Route::get('/view/{id}','view')->name('view');
                });
                /* || ================== END - ATTACHMENTS ===================== || */
            });
            /* || ================== END - ORDERS ===================== || */

        });
        /* || ================== END - CASES ===================== || */

        /* || ================== BEGIN - WORK ORDERS ===================== || */
        Route::prefix('work_orders')->name('work_orders.')->group(function () {
            /* || --------------- BEGIN - WORK ORDERS MANAGEMENT --------------- || */
            Route::prefix('work_orders_management')->controller(WorkOrdersManagementController::class)->name('work_orders_management.')->group(function () {
                Route::get('/','index')->name('index');
                Route::get('/data','data')->name('data');
                Route::get('/show/{id}','show')->name('show');
                Route::get('/create','create')->name('create');
                Route::post('/store','store')->name('store');
                Route::get('/edit/{id}','edit')->name('edit');
                Route::post('/update/{id}','update')->name('update');
                Route::post('/destroy/{id}','destroy')->name('destroy');
                Route::get('/destroy_all','destroy_all')->name('destroy_all');
                Route::get('/update_status/{id}','update_status')->name('update_status');
                Route::get('/update_status_all','update_status_all')->name('update_status_all');
                Route::post('/update_phase','updatePhase')->name('update_phase');
                Route::get('/get_log_phases/{id}','getLogPhases')->name('get_log_phases');
                Route::get('/print/{id}','print')->name('print');
                Route::get('/print_statement/{id}','printStatement')->name('print_statement');
                Route::get('/reader','reader')->name('reader');

                Route::prefix('update_work_orders_phases')->name('update_work_orders_phases.')->group(function () {
                    Route::get('/','indexPhases')->name('index');
                    Route::get('/search','searchWorkOrder')->name('search');
                    Route::post('/update_phase_after_search','updatePhaseAfterSearch')->name('update_phase_after_search');
                });
                Route::prefix('delivering_work_orders')->name('delivering_work_orders.')->group(function () {
                    Route::get('/','reader')->name('index');
                    Route::post('/reader/update_phase','readerUpdatePhase')->name('reader.update_phase');
                });
                Route::prefix('delivering_work_orders')->name('delivering_work_orders.')->group(function () {
                    Route::get('/','reader')->name('index');
                    Route::post('/reader/update_phase','readerUpdatePhase')->name('reader.update_phase');
                });
            });
            /* || --------------- END - WORK ORDERS MANAGEMENT --------------- || */

            Route::prefix('late_work_orders')->controller(LateWorkOrdersController::class)->name('late_work_orders.')->group(function () {
                Route::get('/','index')->name('index');
                Route::get('/data','data')->name('data');
                Route::get('/latest','latest')->name('latest');
                Route::get('/send-late-notifications','sendLateNotifications')->name('send-late-notifications');
            });

            Route::prefix('doctors-requests')->controller(DoctorsRequestsController::class)->name('doctors-requests.')->group(function () {
                Route::get('/','index')->name('index');
                Route::get('/data','data')->name('data');
                Route::get('/confirm/{id}','confirm')->name('confirm');
                Route::get('/get_details/{id}','getDetails')->name('get_details');
                Route::get('/download','download')->name('attachments.download');
            });
        });

        /* || ================== END - WORK ORDERS ===================== || */


        /* || ================== BEGIN - INVOICES ===================== || */
        Route::prefix('invoices')->name('invoices.')->group(function () {
            /* || --------------- BEGIN - INVOICES LIST --------------- || */
            Route::prefix('invoices_list')->controller(InvoicesController::class)->name('invoices_list.')->group(function () {
                Route::get('/','index')->name('index');
                Route::get('/data','data')->name('data');
                Route::get('/show/{id}','show')->name('show');
                Route::post('/pay','pay')->name('pay');

            });
            /* || --------------- END - INVOICES LIST --------------- || */

            /* || --------------- BEGIN - ISSUE INVOICES --------------- || */
            Route::prefix('issue_invoice')->controller(IssueInvoicesController::class)->name('issue_invoice.')->group(function () {
                Route::get('/','index')->name('index');
                Route::post('/search','search')->name('search');
                Route::post('/issue','issue')->name('issue');
                Route::get('/print/{id}','print')->name('print');

            });
            /* || --------------- END - ISSUE INVOICES --------------- || */
        });
        /* || ================== END - INVOICES ===================== || */


        /* || ================== BEGIN - SETTINGS MODULE ===================== || */
        Route::prefix('settings')->name('settings.')->group(function () {

            /* || ================== REGIONAL ===================== || */
            Route::prefix('regional_settings')->name('regional_settings.')->group(function () {
                /* || --------------- NATIONALITIES --------------- || */
                Route::prefix('nationalities')->controller(NationalitiesController::class)->name('nationalities.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::get('/update-status/{id}','update_status')->name('update-status');
                    Route::get('/update_status_all','update_status_all')->name('update_status_all');
                });
                /* || --------------- END - NATIONALITIES --------------- || */

                /* || --------------- REGIONS --------------- || */
                Route::prefix('regions')->controller(RegionsController::class)->name('regions.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::get('/update-status/{id}','update_status')->name('update-status');
                    Route::get('/update_status_all','update_status_all')->name('update_status_all');
                });
                /* || --------------- END - REGIONS --------------- || */

                /* || --------------- CITIES --------------- || */
                Route::prefix('cities')->controller(CitiesController::class)->name('cities.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::get('/update-status/{id}','update_status')->name('update-status');
                    Route::get('/update_status_all','update_status_all')->name('update_status_all');
                });
                /* || --------------- END - CITIES --------------- || */

                /* || --------------- DISTRICTS --------------- || */
                Route::prefix('districts')->controller(DistrictsController::class)->name('districts.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::get('/update-status/{id}','update_status')->name('update-status');
                    Route::get('/update_status_all','update_status_all')->name('update_status_all');
                });
                /* || --------------- END - DISTRICTS --------------- || */
            });
            /* || ================== END - REGIONAL ===================== || */


            /* || ================== BEGIN - SYSTEM SETTINGS ===================== || */
            Route::prefix('system_settings')->name('system_settings.')->group(function () {
                /* || --------------- BEGIN - GENERAL --------------- || */
                Route::prefix('general_settings')->controller(SettingsController::class)->name('general_settings.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::post('/store','store')->name('store');

                    /* || --------------- BEGIN - TABS --------------- || */
                    Route::get('/tab_1', function() { return view('settings.system_settings.general_settings.index'); })->name('tab_1');
                    Route::get('/tab_2', function() { return view('settings.system_settings.general_settings.index'); })->name('tab_2');
                    Route::get('/tab_3', function() { return view('settings.system_settings.general_settings.index'); })->name('tab_3');
                    /* || --------------- END - TABS --------------- || */

                });
                /* || --------------- END - GENERAL --------------- || */

                /* || --------------- BEGIN - BRANCHES --------------- || */
                Route::prefix('branches')->controller(BranchesController::class)->name('branches.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::get('/create','create')->name('create');
                    Route::post('/store','store')->name('store');
                    Route::get('/edit/{id}','edit')->name('edit');
                    Route::post('/update/{id}','update')->name('update');
                    Route::post('/destroy/{id}','destroy')->name('destroy');
                    Route::get('/destroy_all','destroy_all')->name('destroy_all');
                    Route::get('/update-status/{id}','update_status')->name('update-status');
                    Route::get('/update_status_all','update_status_all')->name('update_status_all');
                });
                /* || --------------- END - BRANCHES --------------- || */
            });
            /* || ================== END - SYSTEM SETTINGS ===================== || */

            /* || ================== BEGIN - DATA SETTINGS ===================== || */
            Route::prefix('data_settings')->name('data_settings.')->group(function () {
                /* || --------------- BEGIN - SERVICES CATEGORIES --------------- || */
                Route::prefix('services_categories')->controller(ServicesCategoriesController::class)->name('services_categories.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::post('/store','store')->name('store');
                    Route::get('/edit/{id}','edit')->name('edit');
                    Route::post('/update/{id}','update')->name('update');
                    Route::post('/destroy/{id}','destroy')->name('destroy');
                    Route::get('/destroy_all','destroyAll')->name('destroy_all');
                    Route::get('/update_status/{id}','updateStatus')->name('update_status');
                    Route::get('/update_status_all','updateStatusAll')->name('update_status_all');
                });
                /* || --------------- END - SERVICES CATEGORIES --------------- || */

                /* || --------------- BEGIN - SERVICES --------------- || */
                Route::prefix('services')->controller(ServicesController::class)->name('services.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::get('/create','create')->name('create');
                    Route::post('/store','store')->name('store');
                    Route::get('/edit/{id}','edit')->name('edit');
                    Route::post('/update/{id}','update')->name('update');
                    Route::post('/destroy/{id}','destroy')->name('destroy');
                    Route::get('/destroy_all','destroyAll')->name('destroy_all');
                    Route::get('/update_status/{id}','updateStatus')->name('update_status');
                    Route::get('/update_status_all','updateStatusAll')->name('update_status_all');
                });
                /* || --------------- END - SERVICES --------------- || */

                /* || --------------- BEGIN - COLOR CODES --------------- || */
                Route::prefix('color_codes')->controller(ColorCodesController::class)->name('color_codes.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::post('/store','store')->name('store');
                    Route::get('/edit/{id}','edit')->name('edit');
                    Route::post('/update/{id}','update')->name('update');
                    Route::post('/destroy/{id}','destroy')->name('destroy');
                    Route::get('/destroy_all','destroyAll')->name('destroy_all');
                    Route::get('/update_status/{id}','updateStatus')->name('update_status');
                    Route::get('/update_status_all','updateStatusAll')->name('update_status_all');
                });
                /* || --------------- END - COLOR CODES --------------- || */

                /* || --------------- BEGIN - WORKING STAGES --------------- || */
                Route::prefix('working_phases')->controller(WorkingPhasesController::class)->name('working_phases.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::post('/store','store')->name('store');
                    Route::get('/edit/{id}','edit')->name('edit');
                    Route::post('/update/{id}','update')->name('update');
                    Route::post('/destroy/{id}','destroy')->name('destroy');
                    Route::get('/destroy_all','destroyAll')->name('destroy_all');
                    Route::get('/update_status/{id}','updateStatus')->name('update_status');
                    Route::get('/update_status_all','updateStatusAll')->name('update_status_all');
                });
                /* || --------------- END - WORKING STAGES --------------- || */

                /* || --------------- BEGIN - SECTIONS --------------- || */
                Route::prefix('sections')->controller(SectionsController::class)->name('sections.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::post('/store','store')->name('store');
                    Route::get('/edit/{id}','edit')->name('edit');
                    Route::post('/update/{id}','update')->name('update');
                    Route::post('/destroy/{id}','destroy')->name('destroy');
                    Route::get('/destroy_all','destroyAll')->name('destroy_all');
                    Route::get('/update_status/{id}','updateStatus')->name('update_status');
                    Route::get('/update_status_all','updateStatusAll')->name('update_status_all');
                });
                /* || --------------- END - SECTIONS --------------- || */

                /* || --------------- BEGIN - CLIENTS --------------- || */
                Route::prefix('clients')->controller(ClientsController::class)->name('clients.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::get('/create','create')->name('create');
                    Route::post('/store','store')->name('store');
                    Route::get('/edit/{id}','edit')->name('edit');
                    Route::post('/update/{id}','update')->name('update');
                    Route::post('/destroy/{id}','destroy')->name('destroy');
                    Route::get('/destroy_all','destroy_all')->name('destroy_all');
                    Route::get('/update-status/{id}','update_status')->name('update-status');
                    Route::get('/update_status_all','update_status_all')->name('update_status_all');
                });
                /* || --------------- END - CLIENTS --------------- || */

                /* || --------------- BEGIN - CLIENTS --------------- || */
                Route::prefix('technicians')->controller(TechniciansController::class)->name('technicians.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::get('/create','create')->name('create');
                    Route::post('/store','store')->name('store');
                    Route::get('/edit/{id}','edit')->name('edit');
                    Route::post('/update/{id}','update')->name('update');
                    Route::post('/destroy/{id}','destroy')->name('destroy');
                    Route::get('/destroy_all','destroy_all')->name('destroy_all');
                    Route::get('/update_status/{id}','update_status')->name('update_status');
                    Route::get('/update_status_all','update_status_all')->name('update_status_all');
                });
                /* || --------------- END - CLIENTS --------------- || */

                /* || --------------- BEGIN - DOCTORS --------------- || */
                Route::prefix('doctors')->controller(DoctorsController::class)->name('doctors.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::get('/create','create')->name('create');
                    Route::post('/store','store')->name('store');
                    Route::get('/edit/{id}','edit')->name('edit');
                    Route::post('/update/{id}','update')->name('update');
                    Route::post('/destroy/{id}','destroy')->name('destroy');
                    Route::get('/destroy_all','destroy_all')->name('destroy_all');
                    Route::get('/update_status/{id}','update_status')->name('update_status');
                    Route::get('/update_status_all','update_status_all')->name('update_status_all');

                    /* || --------------- BEGIN - DOCTOR DETAILS --------------- || */
                    Route::prefix("details")->controller(DoctorsServicesDetailsController::class)->name("details.")->group(function () {
                        Route::get('/{id}','index')->name('index');
                        Route::get('/create','create')->name('create');
                        Route::post('/update/{id}','update')->name('update');
                        Route::get('/sync_services/{id}','syncServices')->name('sync_services');
                        Route::get('/reset_services/{id}','resetServices')->name('reset_services');
                        Route::prefix("update_status")->name("update_status.")->group(function () {
                            Route::get('/{id}','update_status')->name('update_status');
                            Route::get('/update_status_all','update_status_all')->name('update_status_all');
                        });
                        Route::prefix("destroy")->name("destroy.")->group(function () {
                            Route::post('/{id}','destroy')->name('destroy');
                            Route::get('/destroy_all','destroy_all')->name('destroy_all');
                        });
                    });
                    /* || --------------- END - DOCTOR DETAILS --------------- || */
                });
                /* || --------------- END - DOCTORS --------------- || */

                /* || --------------- BEGIN - SPECIAL OFFERS --------------- || */
                Route::prefix('special_offers')->controller(SpecialOffersController::class)->name('special_offers.')->group(function () {
                    Route::get('/','index')->name('index');
                    Route::post('/store','store')->name('store');
                    Route::get('/edit/{id}','edit')->name('edit');
                    Route::post('/update/{id}','update')->name('update');
                    Route::post('/destroy/{id}','destroy')->name('destroy');
                    Route::get('/destroy_all','destroyAll')->name('destroy_all');
                });
                /* || --------------- END - SPECIAL OFFERS --------------- || */
            });
            /* || ================== END - DATA SETTINGS ===================== || */

        });
        /* || ================== END - SETTINGS MODULE ===================== || */

        /* || ================== BEGIN - REPORTS ===================== || */
        Route::prefix('reports')->controller(ReportCasesController::class)->name('reports.')->group(function () {
            Route::prefix('cases_report')->name('cases_report.')->group(function () {
                Route::get('/','index')->name('index');
                Route::post('/report','report')->name('report');
                Route::post('/print','print')->name('print');
            });
            Route::prefix('receipts_report')->controller(ReportReceiptsController::class)->name('receipts_report.')->group(function () {
                Route::get('/','index')->name('index');
                Route::post('/report','report')->name('report');
                Route::post('/print','print')->name('print');
            });
            Route::prefix('work_orders_report')->controller(ReportWorkOrdersController::class)->name('work_orders_report.')->group(function () {
                Route::get('/','index')->name('index');
                Route::post('/report','report')->name('report');
                Route::post('/print','print')->name('print');
            });
            Route::prefix('work_phases_statistics_report')->controller(ReportWorkPhasesStatisticsController::class)->name('work_phases_statistics_report.')->group(function () {
                Route::get('/','index')->name('index');
                Route::post('/report','report')->name('report');
                Route::post('/return_to_report_work_order','returnToReportWorkOrder')->name('return_to_report_work_order');
            });
            Route::prefix('work_orders_statistics_report')->controller(ReportWorkOrdersStatisticsController::class)->name('work_orders_statistics_report.')->group(function () {
                Route::get('/','index')->name('index');
                Route::post('/report','report')->name('report');
                Route::post('/return_to_report_work_order','returnToReportWorkOrder')->name('return_to_report_work_order');
            });
            Route::prefix('revenue_report')->controller(ReportRevenueController::class)->name('revenue_report.')->group(function () {
                Route::get('/','index')->name('index');
                Route::post('/report','report')->name('report');
                Route::post('/print','print')->name('print');
            });
            Route::prefix('services_sales_report')->controller(ReportServicesSalesController::class)->name('services_sales_report.')->group(function () {
                Route::get('/','index')->name('index');
                Route::post('/report','report')->name('report');
                Route::post('/print','print')->name('print');
            });
            Route::prefix('performance_evaluation_report')->controller(ReportPerformanceEvaluationController::class)->name('performance_evaluation_report.')->group(function () {
                Route::get('/','index')->name('index');
                Route::post('/report','report')->name('report');
                Route::post('/print','print')->name('print');
            });
        });

        /* || ================== END - REPORTS ===================== || */

    });
    /* || ================== END - MIDDLEWARE GUEST ===================== || */

    /* || ================== BEGIN - LANDING PAGE ===================== || */
    Route::prefix('landing')->name('landing.')->group(function () {
        /* || ================== BEGIN - WORK ORDERS ===================== || */
        Route::prefix('work_orders')->controller(WorkOrdersManagementController::class)->name('work_orders.')->group(function () {
            Route::get('/search_page','searchPage')->name('search_page');
            Route::get('/search/{number}','search')->name('search');
            Route::get('/search_log_phases/{number}','searchLogPhases')->name('search_log_phases');
        });
        /* || ================== END - WORK ORDERS ===================== || */


        Route::prefix('doctors-requests')->controller(DoctorsRequestsController::class)->name('doctors-requests.')->group(function () {
            Route::get('/create','create')->name('create');
            Route::post('/store','store')->name('store');
        });
    });
    /* || ================== END - LANDING PAGE ===================== || */

    Route::controller(GetDataController::class)->group(function () {
        Route::get('ajax/get_colors','getColorsAjax')->name('ajax.get_colors');
    });

});

Route::get('error', function () {
    abort(500);
});
/* || ================== END - ROUTES ===================== || */
