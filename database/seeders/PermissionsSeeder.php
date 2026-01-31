<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{

    public function run()
    {
        /* || ================== PERMISSIONS ===================== || */

        $permissions = [
            'menu_users_management' => 'users',
            'menu_roles' => 'roles',
            'menu_attachments' => 'attachments',
            'menu_cases' => 'cases',
            'menu_orders' => 'orders',
            'menu_branches' => 'branches',
            'menu_receipt_vouchers' => 'receipt_vouchers',
            'menu_regions' => 'regions',
            'menu_cities' => 'cities',
            'menu_districts' => 'districts',
            'menu_nationalities' => 'nationalities',
            'menu_cases_report' => 'cases_report',
            'menu_receipts_report' => 'receipts_report',
            'menu_settings' => 'settings',
            'menu_services_categories' => 'services_categories',
            'menu_services' => 'services',
            'menu_color_codes' => 'color_codes',
            'menu_working_phases' => 'working_phases',
            'menu_sections' => 'sections',
            'menu_clients' => 'clients',
            'menu_technicians' => 'technicians',
            'menu_doctors' => 'doctors',
            'menu_work_orders_report' => 'work_orders_report',
            'menu_work_phases_statistics_report' => 'work_phases_statistics_report',
            'menu_work_orders_management' => 'work_orders_management',
            'menu_invoices' => 'invoices',
            'menu_special_offers' => 'special_offers',
            'menu_revenue_report' => 'revenue_report',
            'menu_services_sales_report' => 'services_sales_report',
            'menu_performance_evaluation_report' => 'performance_evaluation_report',
            'menu_delivering_work_orders' => 'delivering_work_orders',

            'view_attachments' => 'attachments',
            'add_attachments' => 'attachments',
            'download_attachments' => 'attachments',
            'remove_attachments' => 'attachments',

            'view_settings' => 'settings',

            'view_orders' => 'orders',
            'create_orders' => 'orders',
            'update_orders' => 'orders',
            'remove_orders' => 'orders',
            'approve_orders' => 'orders',
            'accept_orders' => 'orders',
            'reject_orders' => 'orders',

            'view_users' => 'users',
            'create_users' => 'users',
            'update_users' => 'users',
            'remove_users' => 'users',
            'update status_users' => 'users',

            'view_roles' => 'roles',
            'create_roles' => 'roles',
            'update_roles' => 'roles',
            'remove_roles' => 'roles',

            'view_cases' => 'cases',
            'create_cases' => 'cases',
            'update_cases' => 'cases',
            'remove_cases' => 'cases',
            'accept_cases' => 'cases',
            'reject_cases' => 'cases',

            'view_branches' => 'branches',
            'create_branches' => 'branches',
            'update_branches' => 'branches',
            'remove_branches' => 'branches',
            'update status_branches' => 'branches',

            'view_services_categories' => 'services_categories',
            'create_services_categories' => 'services_categories',
            'update_services_categories' => 'services_categories',
            'remove_services_categories' => 'services_categories',
            'update status_services_categories' => 'services_categories',

            'view_services' => 'services',
            'create_services' => 'services',
            'update_services' => 'services',
            'remove_services' => 'services',
            'update status_services' => 'services',

            'view_sections' => 'sections',
            'create_sections' => 'sections',
            'update_sections' => 'sections',
            'remove_sections' => 'sections',
            'update status_sections' => 'sections',

            'view_technicians' => 'technicians',
            'create_technicians' => 'technicians',
            'update_technicians' => 'technicians',
            'remove_technicians' => 'technicians',
            'update status_technicians' => 'technicians',

            'view_doctors' => 'doctors',
            'create_doctors' => 'doctors',
            'update_doctors' => 'doctors',
            'remove_doctors' => 'doctors',
            'update status_doctors' => 'doctors',

            'view details_doctors' => 'doctors',
            'update details_doctors' => 'doctors',
            'remove details_doctors' => 'doctors',
            'update status details_doctors' => 'doctors',
            'sync services_details_doctors' => 'doctors',

            'view_clients' => 'clients',
            'create_clients' => 'clients',
            'update_clients' => 'clients',
            'remove_clients' => 'clients',
            'update status_clients' => 'clients',

            'view_color_codes' => 'color_codes',
            'create_color_codes' => 'color_codes',
            'update_color_codes' => 'color_codes',
            'remove_color_codes' => 'color_codes',
            'update status_color_codes' => 'color_codes',

            'view_working_phases' => 'working_phases',
            'create_working_phases' => 'working_phases',
            'update_working_phases' => 'working_phases',
            'remove_working_phases' => 'working_phases',
            'update status_working_phases' => 'working_phases',

            'view_special_offers' => 'special_offers',
            'create_special_offers' => 'special_offers',
            'update_special_offers' => 'special_offers',
            'remove_special_offers' => 'special_offers',

            'view_work_orders_management' => 'work_orders_management',
            'view details_work_orders_management' => 'work_orders_management',
            'create_work_orders_management' => 'work_orders_management',
            'update_work_orders_management' => 'work_orders_management',
            'remove_work_orders_management' => 'work_orders_management',
            'update status_work_orders_management' => 'work_orders_management',
            'print_work_orders_management' => 'work_orders_management',
            'print statement_work_orders_management' => 'work_orders_management',
            'update phase_work_orders_management' => 'work_orders_management',
            'log phases details_work_orders_management' => 'work_orders_management',
            'service details_work_orders_management' => 'work_orders_management',
            'show prices_work_orders_management' => 'work_orders_management',
            'change date_work_orders_management' => 'work_orders_management',

            'view_doctors_requests' => 'doctors_requests',
            'confirm_doctors_requests' => 'doctors_requests',

            'view_delivering_work_orders' => 'delivering_work_orders',

            'view_invoices' => 'invoices',
            'view details_invoices' => 'invoices',
            'issue_invoices' => 'invoices',
            'print_invoices' => 'invoices',
            'pay_invoices' => 'invoices',

            'view_receipt_vouchers' => 'receipt_vouchers',
            'create_receipt_vouchers' => 'receipt_vouchers',
            'update_receipt_vouchers' => 'receipt_vouchers',
            'print_receipt_vouchers' => 'receipt_vouchers',
            'download attachment_receipt_vouchers' => 'receipt_vouchers',

            'view_regions' => 'regions',
            'update status_regions' => 'regions',

            'view_cities' => 'cities',
            'update status_cities' => 'cities',

            'view_districts' => 'districts',
            'update status_districts' => 'districts',

            'view_nationalities' => 'nationalities',
            'update status_nationalities' => 'nationalities',

            'view_cases_report' => 'cases_report',
            'print_cases_report' => 'cases_report',

            'view_receipts_report' => 'receipts_report',
            'print_receipts_report' => 'receipts_report',

            'view_work_orders_report' => 'work_orders_report',
            'print_work_orders_report' => 'work_orders_report',

            'view_work_phases_statistics_report' => 'work_phases_statistics_report',

            'view_revenue_report' => 'revenue_report',
            'print_revenue_report' => 'revenue_report',

            'view_services_sales_report' => 'services_sales_report',
            'print_services_sales_report' => 'services_sales_report',

            'view_performance_evaluation_report' => 'performance_evaluation_report',
            'print_performance_evaluation_report' => 'performance_evaluation_report',

            'view_notifications' => 'notifications',
        ];

        foreach ($permissions as $key => $module) {
            Permission::create([
                'name' => $key,
                'module' => $module,
                'display_name' => ucfirst(str_replace('_', ' ', $key)),
            ]);
        }

        /* || ================== END - PERMISSIONS ===================== || */

    }
}
