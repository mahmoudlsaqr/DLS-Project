<?php

namespace App\Http\Traits\TablesBuilderTraits\WorkOrders;

use App\Models\DoctorsRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Exception;

trait DoctorsRequestsTable
{
    public  function data(Request $request)
    {
        $query = DoctorsRequest::query();
        $search = $request->input('search');
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('doctor_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('patient_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('file_number', 'LIKE', $search . '%');
            });
        }

        $data = $query->orderBy('id', 'desc');
        if ($request ->ajax()) {
            try {
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowId('{{$id}}')
                    ->setRowClass('')
                    ->addColumn('checkbox', function($row) {
                        return '<div class="form-check form-switch d-flex justify-content-center align-items-center">
                                <input class="form-check-input h-20px w-20px p-0 child_check" data-id="'.$row["id"].'" type="checkbox" data-kt-check="true" />
                            </div>';
                    })
                    ->addColumn('color_code', function($row) {
                        return $row->colorCode->color_code ?? '';
                    })
                    ->addColumn('request_status', function($row) {
                        return $row->request_status == 1 ? __("New") : __("Remark");
                    })
                    ->addColumn('confirmed_by', function($row) {
                        if (empty($row->confirmed_by)) { return __("Not yet confirmed"); }
                        return WhoDidItAndWhen($row->confirmed_at, $row->confirmedBy);
                    })
                    ->addColumn('actions', function ($row) {
                        $actions = [];
                        $attachments = $row->attachments ?? null;

                        $confirmText = __("Confirm");
                        $confirmUrl = route("work_orders.doctors-requests.confirm", ["id" => encrypt($row["id"])]);

                        if (auth()->user()->can('confirm_doctors_requests') && empty($row->confirmed_by)) {
                            $actions[] = <<<HTML
                                <div class="menu-item px-2">
                                    <a href="$confirmUrl" class="menu-link">$confirmText</a>
                                </div>
                            HTML;
                        }

                        $attachmentsText = __("View attachments");
                        if (!empty($attachments) && count($attachments) > 0) {
                            $actions[] = <<<HTML
                                <div class="menu-item px-2">
                                    <a class="menu-link view_details" data-id="{$row["id"]}">$attachmentsText</a>
                                </div>
                            HTML;
                        }

                        if (empty($actions)) {
                            return '';
                        }

                        $actionsHtml = implode('', $actions);
                        return <<<HTML
                        <div class="btn-group dropstart">
                            <a class="btn btn-icon btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical fs-3"></i>
                            </a>
                            <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-2" data-kt-menu="true">
                                {$actionsHtml}
                            </ul>
                        </div>
                    HTML;
                    })
                    ->rawColumns(['checkbox', 'color_code', 'request_status', 'confirmed_by', 'actions'])
                    ->make(true);
            } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
        }
    }
}
