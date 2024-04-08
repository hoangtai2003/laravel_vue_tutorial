<?php
function setting($key) {
    $settings = \App\Models\Setting::pluck('value', 'key')->all();
    if (!$settings){
        $settings = config('settings.default');
    }
    return $settings[$key] ?? false;
}
