<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        $cleaned = InputSanitizer::cleanArray($validated['settings']);

        foreach ($cleaned as $key => $value) {
            \App\Helpers\NecHelper::setting_set($key, $value);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
