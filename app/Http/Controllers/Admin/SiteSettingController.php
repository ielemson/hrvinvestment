<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteSettingController extends Controller
{
    public function edit()
    {
        $settings = SiteSetting::firstOrCreate([]);
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = SiteSetting::firstOrCreate([]);

        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],

            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'about_us' => ['nullable', 'string', 'max:2000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:2000'],
            'contact_address' => ['nullable', 'string', 'max:2000'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'currency_code' => ['nullable', 'string', 'max:10'],
            'currency_symbol' => ['nullable', 'string', 'max:10'],
            'currency_position' => ['nullable', 'string', 'max:10'],
            'decimal_places' => ['nullable', 'string', 'max:10'],
            'thousand_separator' => ['nullable', 'string', 'max:10'],

            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'logo_mini' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'file', 'mimes:png,ico', 'max:1024'],
            'og_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        // Text fields
        $settings->fill(collect($validated)->except(['logo', 'logo_mini', 'favicon', 'og_image'])->toArray());

        // Upload helper
        $uploadToPublic = function ($file, $folder) {
            $name = Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($folder), $name);
            return $folder . '/' . $name; // relative path for asset()
        };

        if ($request->hasFile('logo')) {
            $settings->logo_path = $uploadToPublic($request->file('logo'), 'uploads/settings');
        }

        if ($request->hasFile('logo_mini')) {
            $settings->logo_mini_path = $uploadToPublic($request->file('logo_mini'), 'uploads/settings');
        }

        if ($request->hasFile('favicon')) {
            $settings->favicon_path = $uploadToPublic($request->file('favicon'), 'uploads/settings');
        }

        if ($request->hasFile('og_image')) {
            $settings->og_image_path = $uploadToPublic($request->file('og_image'), 'uploads/settings');
        }

        $settings->save();

        return back()->with('success', 'Settings updated successfully.');
    }
}
