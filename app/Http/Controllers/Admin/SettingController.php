<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings');
    }

    public function update(Request $request)
    {
        $rules = array_fill_keys(array_keys(Setting::DEFAULTS), ['nullable', 'string', 'max:255']);
        $rules['email'] = ['nullable', 'email'];
        Setting::put($request->validate($rules));

        return back()->with('ok', 'Settings saved.');
    }
}
