<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole(['admin', 'owner'])) {
            $casesData = $this->getCasesData();
            $ordersData = $this->getOrdersData();
        } elseif ($user->hasRole('physician')) {
            $casesData = $this->getCasesDataForPhysician($user->id);
            $ordersData = $this->getOrdersDataForPhysician($user->id);
        } elseif ($user->hasRole('supervising')) {
            $casesData = $this->getCasesDataForSupervising($user->id);
            $ordersData = $this->getOrdersDataForSupervising($user->id);
        } elseif ($user->hasRole('designer')) {
            $casesData = $this->getCasesDataForDesigner($user->id);
            $ordersData = $this->getOrdersDataForDesigner($user->id);
        } else {
            $casesData = $this->getEmptyCasesData();
            $ordersData = $this->getEmptyOrdersData();
        }

        return view('home', array_merge($casesData, $ordersData));
    }

    private function getCasesData()
    {
        $countCases = Cases::count();
        if ($countCases > 0) {
            $statusesCases = [1 => 'WS', 2 => 'WP', 3 => 'A', 4 => 'RS', 5 => 'RP'];
            return $this->getDataForStatuses(Cases::class, 'status', $statusesCases, $countCases, 'Cases');
        }
        return $this->getEmptyCasesData();
    }

    private function getOrdersData()
    {
        $countOrders = Order::count();
        if ($countOrders > 0) {
            $statusesOrders = [1 => 'PR', 2 => 'SD', 3 => 'A', 4 => 'R'];
            return $this->getDataForStatuses(Order::class, 'status', $statusesOrders, $countOrders, 'Orders');
        }
        return $this->getEmptyOrdersData();
    }

    private function getCasesDataForPhysician($physicianId)
    {
        $countCases = Cases::where('physician', $physicianId)->count();
        if ($countCases > 0) {
            $statusesCases = [2 => 'WP', 3 => 'A', 5 => 'RP'];
            return $this->getDataForStatuses(Cases::class, 'status', $statusesCases, $countCases, 'Cases', $physicianId);
        }
        return $this->getEmptyCasesData();
    }

    private function getOrdersDataForPhysician($physicianId)
    {
        $countOrders = Order::where('created_by', $physicianId)->count();
        if ($countOrders > 0) {
            $statusesOrders = [1 => 'PR', 2 => 'SD', 3 => 'A', 4 => 'R'];
            return $this->getDataForStatuses(Order::class, 'status', $statusesOrders, $countOrders, 'Orders', $physicianId);
        }
        return $this->getEmptyOrdersData();
    }

    private function getCasesDataForSupervising($supervisingId)
    {
        $physicianIds = User::where('supervising', $supervisingId)->pluck('id');
        $countCases = Cases::whereIn('physician', $physicianIds)->count();
        if ($countCases > 0) {
            $statusesCases = [1 => 'WS', 3 => 'A', 4 => 'RS'];
            return $this->getDataForStatuses(Cases::class, 'status', $statusesCases, $countCases, 'Cases', $physicianIds);
        }
        return $this->getEmptyCasesData();
    }

    private function getOrdersDataForSupervising($supervisingId)
    {
        $physicianIds = User::where('supervising', $supervisingId)->pluck('id');
        $countOrders = Order::whereIn('created_by', $physicianIds)->count();
        if ($countOrders > 0) {
            $statusesOrders = [1 => 'PR', 2 => 'SD', 3 => 'A', 4 => 'R'];
            return $this->getDataForStatuses(Order::class, 'status', $statusesOrders, $countOrders, 'Orders', $physicianIds);
        }
        return $this->getEmptyOrdersData();
    }

    private function getCasesDataForDesigner($designerId)
    {
        $countCases = Cases::where('created_by', $designerId)->count();
        if ($countCases > 0) {
            $statusesCases = [1 => 'WS', 2 => 'WP', 3 => 'A', 4 => 'RS', 5 => 'RP'];
            return $this->getDataForStatuses(Cases::class, 'status', $statusesCases, $countCases, 'Cases', $designerId);
        }
        return $this->getEmptyCasesData();
    }

    private function getOrdersDataForDesigner($designerId)
    {
        $countOrders = Order::where('designer', $designerId)->count();
        if ($countOrders > 0) {
            $statusesOrders = [1 => 'PR', 2 => 'SD', 3 => 'A', 4 => 'R'];
            return $this->getDataForStatuses(Order::class, 'status', $statusesOrders, $countOrders, 'Orders', $designerId);
        }
        return $this->getEmptyOrdersData();
    }

    private function getEmptyCasesData()
    {
        return [
            "StatusesCasesLabel" => [],
            "CountCases" => 0,
            "CountsCases" => [],
            "PercentsCases" => [],
        ];
    }

    private function getEmptyOrdersData()
    {
        return [
            "StatusesOrdersLabel" => [],
            "CountOrders" => 0,
            "CountsOrders" => [],
            "PercentsOrders" => [],
        ];
    }

    private function getDataForStatuses($model, $statusColumn, $statuses, $totalCount, $type, $userId = null)
    {
        $counts = [];
        $percents = [];
        foreach ($statuses as $status => $label) {
            $query = $model::where($statusColumn, $status);
            if ($userId !== null) {
                $query->where('created_by', $userId);
            }
            $count = $query->count();
            $counts[$label] = $count;
            $percents[$label] = ($count / $totalCount) * 100;
        }

        $labels = $this->getLabels($type);

        return [
            "Statuses" . $type . "Label" => $labels,
            "Count" . $type => $totalCount,
            "Counts" . $type => $counts,
            "Percents" . $type => $percents,
        ];
    }

    private function getLabels($type)
    {
        if ($type === 'Cases') {
            return [
                'WS' => 'Cases awaiting the treating supervising',
                'WP' => 'Cases awaiting the treating physician',
                'A' => 'Accepted cases',
                'RS' => 'Rejected for supervising',
                'RP' => 'Rejected for physician',
            ];
        } elseif ($type === 'Orders') {
            return [
                'PR' => 'Orders pending review',
                'SD' => 'Orders sent to the designer',
                'A' => 'Approved orders',
                'R' => 'Rejected orders',
            ];
        }

        return [];
    }

}
