<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Models\FashionWeekBanner;
use App\Http\Controllers\Controller;

class FashionWeekBannerController extends Controller
{
    public function index()
    {
        $banners = FashionWeekBanner::all()->sortBy('display_order');
        return view('pages.shop.fashion-week-banner.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:png,jpeg,jpg|max:10000',
        ]);

        $image = $request->file('image');
        $image_name  = ImageService::upload($image, 'storage/shop/fashion-week-banners/');

        $last_order = FashionWeekBanner::max('display_order');
        $display_order = $last_order + 1;

        FashionWeekBanner::create([
            'image' => $image_name,
            'display_order' => $display_order
        ]);

        return redirect()->back()->with('success', 'Banner created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'mimes:png,jpeg,jpg|max:10000',
        ]);

        $image = $request->file('image');

        $banner = FashionWeekBanner::findOrFail($id);

        if ($image) {
            $image_name  = ImageService::upload($image, 'storage/shop/fashion-week-banners/', $banner->image);
            $banner->image = $image_name;
        }

        $banner->save();

        return redirect()->back()->with('success', 'Banner updated successfully.');
    }

    public function destroy($id)
    {
        $banner = FashionWeekBanner::findOrFail($id);
        ImageService::delete($banner->image, 'storage/shop/fashion-week-banners/');
        $banner->delete();

        //re order display order
        $banners = FashionWeekBanner::all();
        foreach ($banners as $key => $value) {
            $value->display_order = $key + 1;
            $value->save();
        }

        return redirect()->back()->with('success', 'Banner deleted successfully.');
    }

    public function sort(Request $request) {
        $request->validate([
            'id' => 'required|array',
            'id.*' => 'required|numeric|exists:fashion_week_banners,id',
        ]);

        foreach ($request->id as $key => $value) {
            $banner = FashionWeekBanner::find($value);
            $banner->display_order = $key + 1;
            $banner->save();
        }

        return redirect()->back()->with('success', 'Banner order updated successfully.');
    }
}
