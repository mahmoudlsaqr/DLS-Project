<?php

namespace App\Http\Traits\TablesBuilderTraits\WorkOrders;

use App\Services\LateWorkOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

trait LateWorkOrdersTable
{
    public function data(Request $request, LateWorkOrderService $service)
    {
        if ($request->ajax()) {

            try {
                $items = $service->getLateTable();

                $search = trim($request->input('search'));
                if ($search) {
                    $items = $items->filter(fn($item) =>
                        Str::contains($item['workOrderNumber'] ?? '', $search) ||
                        Str::contains($item['work_order_id'] ?? '', $search) ||
                        Str::contains($item['work_order_number'] ?? '', $search) ||
                        Str::contains($item['phase_name'] ?? '', $search) ||
                        Str::contains($item['title'] ?? '', $search)
                    );
                }

                $phase = $request->input('phase') ?? null;
                if (!empty($phase)) {
                    $items = $items->filter(fn($item) => (int)$item['phase_id'] === (int)$phase);
                }

                $user = $request->input('user') ?? null;
                if (!empty($user)) {
                    $items = $items->filter(fn($item) => (int)$item['user_id'] === (int)$user);
                }

                if ($items->isEmpty()) {
                    return response()->json(['data' => []]);
                }

                return $items->isNotEmpty()
                    ? DataTables::of($items)
                        ->addIndexColumn()
                        ->setRowId(fn($row) => $row['work_order_id'])
                        ->rawColumns(["last_update", "actions"])
                        ->make(true)
                    : response()->json(['error' => 'No records found'], 404);

            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }
}
