<?php

namespace App\Http\Controllers\Settings\SystemSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\SystemSettings\SettingsRequest;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_settings', ['only' => ['index', 'store']]);
    }

    public function index()
    {
        return view('settings.system_settings.general_settings.index');
    }

    public function store(SettingsRequest $request)
    {
        $lock = Cache::lock('SettingsLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {
                $request = request();
                $data = $request->except(['_token', '_method']);
                foreach ($data as $key => $val) {
                    $setting = Settings::firstOrNew(['key' => $key]);
                    $setting ->value = $val;
                    $setting ->save();
                }

                return redirect() ->back() ->with('success', __('Settings saved successfully'));
            } catch (\Exception $e) { return redirect() ->back() ->with('error', $e->getMessage()); } finally { $lock ->release(); }
        } else { return redirect() ->back() ->with('error', __('There is another process currently performing this operation, please wait')); }
    }
}
