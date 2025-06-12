<?php

namespace App\Http\Controllers\Website;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ImageService;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all()->sortBy('display_order');
        return view('pages.website.banner.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:png,jpeg,jpg|max:10000',
        ]);

        $image = $request->file('image');
        $image_name  = ImageService::upload($image, 'storage/website/banners/');

        $last_order = Banner::max('display_order');
        $display_order = $last_order + 1;

        Banner::create([
            'image' => $image_name,
            'display_order' => $display_order
        ]);

        return redirect()->route('banner.index')->with('success', 'Banner created successfully.');
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image' => 'mimes:png,jpeg,jpg|max:10000',
        ]);

        $image = $request->file('image');

        if ($image) {
            $image_name  = ImageService::upload($image, 'storage/website/banners/', $banner->image);
            $banner->image = $image_name;
        }

        $banner->save();

        return redirect()->route('banner.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        ImageService::delete($banner->image, 'storage/website/banners/');
        $banner->delete();

        //re order display order
        $banners = Banner::all();
        foreach ($banners as $key => $value) {
            $value->display_order = $key + 1;
            $value->save();
        }

        return redirect()->route('banner.index')->with('success', 'Banner deleted successfully.');
    }

    public function sort(Request $request) {
        $request->validate([
            'id' => 'required|array',
            'id.*' => 'required|numeric|exists:banners,id',
        ]);

        foreach ($request->id as $key => $value) {
            $banner = Banner::find($value);
            $banner->display_order = $key + 1;
            $banner->save();
        }

        return redirect()->route('banner.index')->with('success', 'Banner order updated successfully.');
    }
}
