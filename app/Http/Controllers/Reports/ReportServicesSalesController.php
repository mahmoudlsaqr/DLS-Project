<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Clients;
use App\Models\Doctors;
use App\Models\Services;
use App\Models\ServicesCategories;
use App\Models\Technicians;
use App\Models\WorkingPhases;
use App\Models\WorkOrdersManagement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportServicesSalesController extends Controller
{

    public function index()
    {
        $data = $this->getSharedData();
        $data['workOrders'] = $this->getWorkOrdersByDate(Carbon::today());

        return view("reports.services_sales_report.index", $data);
    }

    public function report(Request $request)
    {
        // Retrieve request parameters
        $requestParams = $request->only(['branch_id', 'doctor_id', 'client_id', 'service_type', 'category_id', 'service_id', 'start_date', 'end_date']);

        // Prepare the base query with relations
        $query = WorkOrdersManagement::with(['branches', 'doctors', 'clients', 'colors', 'services', 'phases', 'createdBy', 'updatedBy']);

        $user = Auth::user();
        $userId = Auth::id();
        $branchesUserIds = $user->branches->pluck('id');

        if ($user->hasRole('technician')) {
            $technician = Technicians::where('user_id', $userId)->first();

            if (!empty($technician)) {
                $technicianBranches = $technician->branches->pluck('id');

                $query->whereIn('branch_id', $technicianBranches);
            }
        } else {
            if (!empty($branchesUserIds) && !$user->hasRole('owner')) {
                $query->whereIn("branch_id", $branchesUserIds);
            }
        }

        if (!empty($requestParams['branch_id'])) {
            $query->where('branch_id', $requestParams['branch_id']);
        }
        if (!empty($requestParams['doctor_id'])) {
            $query->where('doctor_id', $requestParams['doctor_id']);
        }
        if (!empty($requestParams['client_id'])) {
            $query->where('client_id', $requestParams['client_id']);
        }
        if (!empty($requestParams['start_date']) && !empty($requestParams['end_date'])) {
            $query->whereBetween('work_order_date', [$requestParams['start_date'], $requestParams['end_date']]);
        }

        // Fetch service IDs based on service type
        if (!empty($requestParams['service_type'])) {
            $serviceIds = (int)$requestParams['service_type'] === 1
                ? $query->whereHas('services', fn($q) => $q->where('category_id', $requestParams['category_id']))->pluck('service_id')->unique()
                : collect([$requestParams['service_id']]);
        } else {
            $serviceIds = $query->pluck('service_id')->unique();
        }

        // Retrieve service data and calculate statistics
        $serviceData = Services::whereIn('id', $serviceIds)->get(['id', 'name_ar', 'name_en', "service_cost"]);
        $result = $serviceData->map(function ($service) use ($query) {
            $serviceItems = (clone $query)->where('service_id', $service->id);

            return array(
                'service' => getLocaleName($service, 'name_ar', 'name_en'),
                'count' => $serviceItems->sum('service_count'),
                'total_price' => $serviceItems->sum('service_total_net'),
                'total_cost' => $serviceItems->sum('service_count') * $service->service_cost,
                'tax' => number_format($serviceItems->sum('service_tax_value'), 2, '.', ''),
                'total_including_tax' => $serviceItems->sum('service_total'),
                'total_net' => $serviceItems->sum('service_total_net') - $service->service_cost,
            );
        });

        // Calculate grand totals
        $grandTotal = [
            'total_count' => $result->sum('count'),
            'total_price' => number_format($result->sum('total_price'), 2, '.', ''),
            'total_cost' => number_format($result->sum('total_cost'), 2, '.', ''),
            'total_tax' => number_format($result->sum('tax'), 2, '.', ''),
            'total_including_tax' => number_format($result->sum('total_including_tax'), 2, '.', ''),
            'total_net' => number_format($result->sum('total_net'), 2, '.', ''),
        ];

        // Get shared data for the view
        $data = $this->getSharedData();
        $data['result'] = $result;
        $data['grand_total'] = $grandTotal;
        $data['request'] = $request;

        return view("reports.services_sales_report.index", $data);
    }

    public function print(Request $request)
    {
//        // Validate the incoming request data
//        $validator = Validator::make($request->all(), [
//            'service_type' => 'required|integer',
//            'category_id' => 'required_if:service_type,1|exists:services_categories,id',
//            'service_id' => 'required_if:service_type,2|exists:services,id',
//        ]);
//
//        if ($validator->fails()) {
//            return redirect()->back()->with('errors', $validator->errors());
//        }

        // Retrieve request parameters
        $requestParams = $request->only(['branch_id', 'doctor_id', 'client_id', 'service_type', 'category_id', 'service_id', 'start_date', 'end_date']);

        // Prepare the base query with relations
        $query = WorkOrdersManagement::with(['branches', 'doctors', 'clients', 'colors', 'services', 'phases', 'createdBy', 'updatedBy']);

        if (!empty($requestParams['branch_id'])) {
            $query->where('branch_id', $requestParams['branch_id']);
        }
        if (!empty($requestParams['doctor_id'])) {
            $query->where('doctor_id', $requestParams['doctor_id']);
        }
        if (!empty($requestParams['client_id'])) {
            $query->where('client_id', $requestParams['client_id']);
        }
        if (!empty($requestParams['start_date']) && !empty($requestParams['end_date'])) {
            $query->whereBetween('work_order_date', [$requestParams['start_date'], $requestParams['end_date']]);
        }

        // Fetch service IDs based on service type
        if (!empty($requestParams['service_type'])) {
            $serviceIds = (int)$requestParams['service_type'] === 1
                ? $query->whereHas('services', fn($q) => $q->where('category_id', $requestParams['category_id']))->pluck('service_id')->unique()
                : collect([$requestParams['service_id']]);
        } else {
            $serviceIds = $query->pluck('service_id')->unique();
        }

        // Retrieve service data and calculate statistics
        $serviceData = Services::whereIn('id', $serviceIds)->get(['id', 'name_ar', 'name_en']);
        $result = $serviceData->map(function ($service) use ($query) {
            $serviceItems = (clone $query)->where('service_id', $service->id);

            return [
                'service' => getLocaleName($service, 'name_ar', 'name_en'),
                'count' => $serviceItems->sum('service_count'),
                'total_price' => $serviceItems->sum('service_total_net'),
                'tax' => number_format($serviceItems->sum('service_tax_value'), 2, '.', ''),
                'total_including_tax' => $serviceItems->sum('service_total'),
            ];
        });

        // Calculate grand totals
        $grandTotal = [
            'total_count' => $result->sum('count'),
            'total_price' => number_format($result->sum('total_price'), 2, '.', ''),
            'total_tax' => number_format($result->sum('tax'), 2, '.', ''),
            'total_including_tax' => number_format($result->sum('total_including_tax'), 2, '.', ''),
        ];

        if (!empty($requestParams['category_id'])) {
            $category = ServicesCategories::findOrFail($requestParams['category_id']);
        }
        if (!empty($requestParams['service_id'])) {
            $service = Services::findOrFail($requestParams['service_id']);
        }

        // Get shared data for the view
        $data = $this->getSharedData();
        $data['result'] = $result;
        $data['grand_total'] = $grandTotal;
        $data['request'] = $request;
        $data['branch'] = isset($branch)? getLocaleName($branch, "name_ar", "name_en") : __("All Branches");
        $data['doctor'] = isset($doctor)? getLocaleName($doctor, "name_ar", "name_en") : __("All Doctors");
        $data['client'] = isset($client)? getLocaleName($client, "name_ar", "name_en") : __("All Clients");
        $data['date_from'] = $dateFrom ?? __("Undefined");
        $data['date_to'] = $dateTo ?? __("Undefined");
        $data['service_type_bool'] = (int)$requestParams['service_type'] === 1;
        $data['service_type'] = (int)$requestParams['service_type'] === 1? __("Category") : __("Service");
        $data['category'] = isset($category)? getLocaleName($category, "name_ar", "name_en") : __("Undefined");
        $data['service'] = isset($service)? getLocaleName($service, "name_ar", "name_en") : __("Undefined");

        return view("reports.services_sales_report.print", $data);

    }




    // Begin Helper Functions.
    private function getWorkOrdersByDate($date)
    {
        return WorkOrdersManagement::with(['branches', 'doctors', 'clients', 'colors', 'services', 'phases', 'createdBy', 'updatedBy'])
            ->whereDate('work_order_date', $date)
            ->get();
    }

    private function getSharedData()
    {
        return [
            'branches' => Branch::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get(),
            'clients' => Clients::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get(),
            'doctors' => Doctors::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get(),
            'phases' => WorkingPhases::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get(),
            'categories' => ServicesCategories::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get(),
            'services' => Services::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get(),
        ];
    }
    // End Helper Functions.


}
