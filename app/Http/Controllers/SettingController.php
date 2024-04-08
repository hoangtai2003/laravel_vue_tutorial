<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function list (){
        $settings =  Setting::pluck('value','key')->toArray();
        if (!$settings){
            $settings = config('settings.default');
        }
        return $settings;
    }
    public function update(Request $request) {
        $settings = $request->validate([
           'app_name' => ['required', 'string'],
            'date_format' => ['required', 'string'],
            'pagination_limit' => ['required', 'int','min:1','max:100']
        ]);

        foreach ($settings as $key => $value){
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        return response()->json(['message' => 'success']);
    }
}
