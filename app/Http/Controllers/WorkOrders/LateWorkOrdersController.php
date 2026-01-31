<?php

namespace App\Http\Controllers\WorkOrders;

use App\Http\Controllers\Controller;
use App\Http\Traits\TablesBuilderTraits\WorkOrders\LateWorkOrdersTable;
use App\Models\User;
use App\Notifications\LateWorkOrdersNotification;
use App\Services\LateWorkOrderService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class LateWorkOrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_notifications', ['only' => ['index', 'data', 'latest']]);
    }

    use LateWorkOrdersTable;

    public function index(LateWorkOrderService $service)
    {
        $items = $service->getLateTable();

        return view('work_orders.late_work_orders.index', [
            'items' => $items,
        ]);
    }

    public function latest(LateWorkOrderService $service)
    {
        $query = $service->getLateNotifications();

        $notificationsCount = $query->count();
        $notifications = $query->take(10);

        return response()->json([
            'count' => $notificationsCount,
            'notifications' => $notifications,
        ]);
    }

}
