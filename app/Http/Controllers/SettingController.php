<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function list (){
        return Setting::pluck('value','key')->toArray();
    }
    public function update() {
        $settings = \request()->all();

        foreach ($settings as $key => $value){
            Setting::where('key', $key)->update(['value' => $value]);
        }
        return response()->json(['message' => 'success']);
    }
}
