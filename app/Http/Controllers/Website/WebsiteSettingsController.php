<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Models\WebsiteSettings;
use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Support\Facades\Validator;

class WebsiteSettingsController extends Controller
{
    public function index() {
        $settings = WebsiteSettings::first();
        return view('pages.website.settings.index', compact('settings'));
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
            'instagram_url' => 'nullable|string|url',
            'facebook_url' => 'nullable|string|url',
            'whatsapp_url' => 'nullable|string|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $settings = WebsiteSettings::first();

        if (!$request->hasFile('logo') && !isset($settings->logo)) {
            return redirect()->back()->with('error', 'Logo is required.')->withInput();
        }

        if ($request->hasFile('logo')) {
            $logo = ImageService::upload($request->file('logo'), 'storage/website/', isset($settings->logo) ? $settings->logo : null);
        }

        $settings = WebsiteSettings::updateOrCreate([
            'id' => 1
        ], [
            'logo' => $logo ?? $settings->logo,
            'instagram_url' => $request->instagram_url,
            'facebook_url' => $request->facebook_url,
            'whatsapp_url' => $request->whatsapp_url,
        ]);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
