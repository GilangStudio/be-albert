<?php

namespace App\Http\Controllers\Website;

use App\Models\AboutCompany;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AboutCompanyController extends Controller
{
    public function index() 
    {
        $aboutCompany = AboutCompany::first();
        return view('pages.website.about-company.index', compact('aboutCompany'));
    }

    public function update(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'banner_image' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
            'main_description' => 'nullable|string',
            'section_image' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
            'section_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $aboutCompany = AboutCompany::first();

        // Handle banner image upload
        $banner_image = null;
        if ($request->hasFile('banner_image')) {
            $banner_image = ImageService::upload(
                $request->file('banner_image'), 
                'storage/website/about-company/', 
                isset($aboutCompany->banner_image) ? $aboutCompany->banner_image : null
            );
        }

        // Handle section image upload
        $section_image = null;
        if ($request->hasFile('section_image')) {
            $section_image = ImageService::upload(
                $request->file('section_image'), 
                'storage/website/about-company/', 
                isset($aboutCompany->section_image) ? $aboutCompany->section_image : null
            );
        }

        // Update or create about company
        $aboutCompany = AboutCompany::updateOrCreate([
            'id' => 1
        ], [
            'banner_image' => $banner_image ?? ($aboutCompany->banner_image ?? null),
            'main_description' => $request->main_description,
            'section_image' => $section_image ?? ($aboutCompany->section_image ?? null),
            'section_description' => $request->section_description,
        ]);
        
        return redirect()->route('about-company.index')->with('success', 'About Company updated successfully.');
    }
}