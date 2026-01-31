<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\City;
use App\Models\Clients;
use App\Models\ColorCodes;
use App\Models\District;
use App\Models\Doctors;
use App\Models\DoctorsServicesDetails;
use App\Models\Nationalities;
use App\Models\Region;
use App\Models\Sections;
use App\Models\Services;
use App\Models\ServicesCategories;
use App\Models\SpecialOffers;
use App\Models\Technicians;
use App\Models\User;
use App\Models\WorkingPhases;
use App\Models\WorkOrdersLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Exception;

class GetDataController extends Controller
{

    public function getNationalities(Request $request)
    {
        $search = $request->input('search', '');
        $query = Nationalities::where('status', '=', 'true')
            ->select(['id','Nat_Ar', 'Nat_En'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('Nat_Ar', 'like', "%$search%")->orwhere('Nat_En', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->Nat_Ar : $text = $item->Nat_En;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    }

    public function getUsers(Request $request)
    {
        $search = $request->input('search', '');
        $query = User::where('status', '=', 'true')
            ->select('id','name')
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('id', 'like', "%$search%")->orwhere('name', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });

        return response() ->json($response);
    }

    public function getRegions(Request $request)
    {
        $search = $request->input('search', '');
        $query = Region::where('status', '=', 'true')
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Regions Data

    public function getCities(Request $request)
    {
        $search = $request->input('search', '');
        $query = City::where('status', '=', 'true')
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Cities Data

    public function getDistricts(Request $request)
    {
        $search = $request->input('search', '');
        $query = District::where('status', '=', 'true')
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);

    } // End Get Districts Data

    public function getCitiesRegion(Request $request, $id)
    {
        $search = $request->input('search', '');
        $query = City::where('status', '=', 'true')
            ->where('region_id', $id)
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Cities Data When Select Region

    public function getDistrictsCity(Request $request, $id)
    {
        $search = $request->input('search', '');
        $query = District::where('status', '=', 'true')
            ->where('city_id', $id)
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Districts Data When Select City

    public function getRoles(Request $request)
    {
        $search = $request->input('search', '');
        $query = Role::orderby('id','asc')->select('id','name')
            ->where('name', 'like', '%' .$search . '%')
            ->distinct();

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            return [
                'id'   => $item->name,
                'text' => $item->name,
            ];
        });

        return response() ->json($response);
    } // End Get Roles Data

    public function getSupervising(Request $request)
    {
        $search = $request->input('search', '');
        $query = User::where('status', 'true')
            ->where("role", "supervising")
            ->orderby('id','asc')
            ->select('id','name')
            ->distinct();

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });

        return response() ->json($response);
    } // End Get Supervising Users

    public function getCategoriesFromServices(Request $request)
    {
        $search = $request->input('search', '');
        $query = ServicesCategories::where('status', '=', 'true')
            ->whereIn('id', function($query) {
                $query->select('category_id')
                    ->from('services');
            })
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Main Category Services Data From Services

    public function getCategories(Request $request)
    {
        $search = $request->input('search', '');
        $query = ServicesCategories::where('status', '=', 'true')
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Category Services Data

    public function getBranches(Request $request)
    {
        $search = $request->input('search', '');

        $query = Branch::where('status', '=', 'true')
            ->select(['id','name_ar', 'name_en']);

        $user = Auth::user();
        $userId = Auth::id();
        $branchesUserIds = $user->branches->pluck('id');

        if ($user->hasRole('technician')) {
            $technician = Technicians::where('user_id', $userId)->first();

            if (!empty($technician)) {
                $technicianBranches = $technician->branches->pluck('id');

                $query->whereIn('id', $technicianBranches);
            }
        } else {
            if (!empty($branchesUserIds) && !$user->hasRole('owner')) {
                $query->whereIn("id", $branchesUserIds);
            }
        }

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Branches Data

    public function getBranchesUser(Request $request)
    {
        $search = $request->input('search', '');
        $branchIds = DB::table("many_users_branches")->where('user_id', Auth::id())->pluck('branch_id')->toArray();

        $query = DB::table("branches")->where('status', '=', 'true')
            ->whereIn("id", $branchIds)
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Branches user Data

    public function getTechnicianBranches(Request $request)
    {
        $search = $request->input('search', '');
        $User = Auth::user();
        if ($User->hasRole('technician')) {
            $technicianId = Technicians::where('status', '=', 'true')->where("user_id", Auth::id())->value('id');
            $branchIds = DB::table("many_branches_technicians")->where('technician_id', '=', $technicianId)->pluck('branch_id');
            $query = DB::table("branches")->where('status', '=', 'true')
                ->whereIn("id", $branchIds)
                ->select(['id','name_ar', 'name_en'])
                ->orderby('id','asc')
                ->distinct();
        } elseif ($User->hasRole(['owner','admin'])) {
            $query = Branch::where('status', '=', 'true')
                ->select(['id','name_ar', 'name_en'])
                ->orderby('id','asc')
                ->distinct();
        } else {
            $query = Branch::where('status', '=', 'true')
                ->select(['id','name_ar', 'name_en'])
                ->orderby('id','asc')
                ->distinct();
        }

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Branches Of Technician

    public function getTechnicianPhases(Request $request)
    {
        $search = $request->input('search', '');
        $User = Auth::user();
        if ($User->hasRole('technician')) {
            $technicianId = Technicians::where('status', '=', 'true')->where("user_id", Auth::id())->value('id');
            $phaseIds = DB::table("many_phases_technicians")->where('technician_id', '=', $technicianId)->pluck('phase_id');
            $query = DB::table("working_phases")->where('status', '=', 'true')
                ->where("id", "!=", 1)
                ->whereIn("id", $phaseIds)
                ->select(['id','name_ar', 'name_en'])
                ->orderby('id','asc')
                ->distinct();
        } elseif ($User->hasRole(['owner','admin'])) {
            $query = WorkingPhases::where('status', '=', 'true')
                ->where("id", "!=", 1)
                ->select(['id','name_ar', 'name_en'])
                ->orderby('id','asc')
                ->distinct();
        } else {
            $query = [];
        }

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Phases Of Technician

    public function getPhases(Request $request)
    {
        $search = $request->input('search', '');
        $query = WorkingPhases::where('status', '=', 'true')
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Phases Data

    public function getPhasesWithoutEntry(Request $request)
    {
        $search = $request->input('search', '');
        $query = WorkingPhases::where('status', '=', 'true')
            ->where("id", "!=", 1)
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Phases Data Without Entry

    public function getSections(Request $request)
    {
        $search = $request->input('search', '');
        $query = Sections::where('status', '=', 'true')
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Sections Data

    public function getSectionsFromTechnicians(Request $request)
    {
        $search = $request->input('search', '');
        $query = Sections::where('status', '=', 'true')
            ->whereIn('id', function($query) {
                $query->select('section_id')
                    ->from('technicians');
            })
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Nationalities Data From Technicians

    public function getNationalitiesFromTechnicians(Request $request)
    {
        $search = $request->input('search', '');
        $query = Nationalities::where('status', '=', 'true')
            ->whereIn('id', function($query) {
                $query->select('nationality_id')
                    ->from('technicians');
            })
            ->select(['id','Nat_Ar', 'Nat_En'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('Nat_Ar', 'like', "%$search%");
            $query->where('Nat_En', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->Nat_Ar : $text = $item->Nat_En;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Nationalities Data From Technicians

    public function getClients(Request $request)
    {
        $search = $request->input('search', '');

        $user = Auth::user();
        $userId = Auth::id();
        $branchesUserIds = $user->branches->pluck('id');

        $query = Clients::with('region', 'city', 'district', 'branches', 'createdBy', 'updatedBy')
            ->where('status', '=', 'true')
            ->select(['id','name_ar', 'name_en']);

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

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    }

    public function getDoctors(Request $request)
    {
        $search = $request->input('search', '');

        $user = Auth::user();
        $userId = Auth::id();
        $branchesUserIds = $user->branches->pluck('id');

        $query = Doctors::with('clients', 'nationalities', 'branches')
            ->where('status', '=', 'true')
            ->select(['id','name_ar', 'name_en']);

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

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    }

    public function getServices(Request $request)
    {
        $search = $request->input('search', '');
        $query = Services::where('status', 'true')
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Services Data

    public function getColors(Request $request)
    {
        $search = $request->input('search', '');
        $query = ColorCodes::where('status', '=', 'true')
            ->select(['id','color_code'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('color_code', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            return [
                'id'   => $item->id,
                'text' => $item->color_code,
            ];
        });

        return response() ->json($response);
    } // End Get Color Codes Data

    public function getColorsAjax(Request $request)
    {
        try {
            $search = $request->input('search', '');
            $page = $request->input('page', 1);
            $perPage = LIMIT_SELECT2;
            $requestId = $request->input('id');

            $query = ColorCodes::where('status', '=', 'true')
                ->select(['id','color_code'])
                ->orderby('id','desc');

            if ($requestId) {
                $item = (clone $query)->where('id', $requestId)->first();
                return response()->json([
                    'items' => $item ? [[
                        'id' => $item->id,
                        'text' => $item->color_code,
                    ]] : []
                ]);
            }

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('color_code', 'like', "%{$search}%");
                });
            }

            $items = $query->orderBy('id')->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'items' => $items->map(fn($item) => [
                    'id' => $item->id,
                    'text' => $item->color_code,
                ]),
                'more' => $items->hasMorePages(),
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getBranchDoctors(Request $request, string $id)
    {
        $search = $request->input('search', '');
        $query = Doctors::where('status', '=', 'true')
            ->where('branch_id', $id)
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Doctor Data When Select Branch

    public function getBranchClients(Request $request, string $id)
    {
        $search = $request->input('search', '');
        $query = Clients::where('status', '=', 'true')
            ->where('branch_id', $id)
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Clients Data When Select Branch

    public function getClientDoctors(Request $request, string $id)
    {
        $search = $request->input('search', '');
        $query = Doctors::where('status', '=', 'true')
            ->where('client_id', $id)
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Doctors Data When Select Client

    public function getNationalitiesFromDoctors(Request $request)
    {
        $search = $request->input('search', '');
        $query = Nationalities::where('status', '=', 'true')
            ->whereIn('id', function($query) {
                $query->select('nationality_id')
                    ->from('doctors');
            })
            ->select(['id','Nat_Ar', 'Nat_En'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('Nat_Ar', 'like', "%$search%");
            $query->where('Nat_En', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->Nat_Ar : $text = $item->Nat_En;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Nationalities Data From Doctors

    public function getClientsFromDoctors(Request $request)
    {
        $search = $request->input('search', '');
        $query = Clients::where('status', '=', 'true')
            ->whereIn('id', function($query) {
                $query->select('client_id')
                    ->from('doctors');
            })
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Clients Data From Doctors

    public function getServicesCategoriesFromDoctorsDetails(Request $request)
    {
        $search = $request->input('search', '');
        $query = ServicesCategories::where('status', '=', 'true')
            ->whereIn('id', function($query) {
                $query->select('category_id')
                    ->from('doctors_services_details');
            })
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Services Categories Data From Doctors

    public function getServicesFromDoctorsDetails(Request $request)
    {
        $search = $request->input('search', '');
        $query = Services::where('status', '=', 'true')
            ->whereIn('id', function($query) {
                $query->select('service_id')
                    ->from('doctors_services_details');
            })
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Services Data From Doctors

    public function getDoctorServices(Request $request, string $id)
    {
        $search = $request->input('search', '');
        $serviceIds = DoctorsServicesDetails::where('status', '=', 'true')
            ->where('doctor_id', $id)
            ->get('service_id');

        $query = Services::where('status', '=', 'true')
            ->whereIn('id', $serviceIds)
            ->select(['id','name_ar', 'name_en'])
            ->orderby('id','asc')
            ->distinct();

        if ($search) {
            $query->where('name_ar', 'like', "%$search%")->orwhere('name_en', 'like', "%$search%");
        }

        $items = $query->get();
        $response = $items->map(function ($item) {
            (app()->getLocale() == "ar") ? $text = $item->name_ar : $text = $item->name_en;
            return [
                'id'   => $item->id,
                'text' => $text,
            ];
        });

        return response() ->json($response);
    } // End Get Doctor Services Data When Select Doctor

    public function createServiceCode(int $id)
    {
        $service = ServicesCategories::where('id', $id) ->first();
        $oldCode = Services::where('service_code', 'like', $service ->category_code.'%') ->first();
        $last_service_code = Services::where('service_code', 'like', $service ->category_code.'%')->latest('id')->first();

        if (empty($oldCode)) {
            $new_service_code = $service ->category_code . str_pad(1, 3, '0', STR_PAD_LEFT);
        } else {
            $match_code = $last_service_code ->service_code;
            preg_match_all('!\d+!', $match_code, $matches);
            $new_match_code = (int)$matches[0][0];
            $new_service_code = $service ->category_code . str_pad($new_match_code, 3, '0', STR_PAD_LEFT);
        }
        return response() ->json($new_service_code);
    }

    public function getClientDoctor(int $id)
    {
        $doctor = Doctors::with('clients') ->where('status', '=', 'true') ->where('id', $id) ->first();

        $client = app()->getLocale() == "ar"? $doctor->clients->name_ar : $doctor->clients->name_en;
        return response() ->json(['client' => $client]);
    }

    public function getDoctorServiceDetails(Request $request)
    {
        $today = Carbon::today();
        $date = $request['date'] ?? now();
        $date = date('Y-m-d', strtotime($date));

        $service = DoctorsServicesDetails::with('services')
            ->where('doctor_id', $request['doctorId'])
            ->where('service_id', $request['serviceId'])
            ->first();

        $doctor = Doctors::with('clients')
            ->where('status', 'true')
            ->where('id', $request['doctorId'])
            ->first();

        if (!auth()->user()->can('change date_work_orders_management')) {
            if (!empty($doctor->client_id)) {
                $offer = SpecialOffers::where('doctor_id', $doctor['id'])
                    ->where('end_date', '>=', $today)
                    ->where('start_date', '<=', $today)
                    ->first();
            }
        } else {
            if (!empty($doctor->client_id)) {
                $offer = SpecialOffers::where('doctor_id', $doctor['id'])
                    ->where('end_date', '>=', $date)
                    ->where('start_date', '<=', $date)
                    ->first();
            }
        }

        $clientDiscount = $doctor->clients->discount ?? 0;
        if (!empty($offer)) {
            $offerDiscount = $offer->discount;
            $discount = max((float)$clientDiscount, (float)$offerDiscount);
        } else { $discount = $clientDiscount; }

        $response = [
            'price' => $service->price,
            'service_price' => $service->service_price,
            'tax' => $service->services->tax_percent,
            'discount' => (float)$discount ?? $service->service_discount,
            'service_discount' => $service->service_discount ?? 0,
        ];
        return response() ->json($response);
    }

}
