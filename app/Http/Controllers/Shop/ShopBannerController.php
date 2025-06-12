<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\Models\ShopBanner;

class ShopBannerController extends Controller
{
    public function index()
    {
        $banners = ShopBanner::all()->sortBy('display_order');
        return view('pages.shop.banner.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:png,jpeg,jpg|max:10000',
        ]);

        $image = $request->file('image');
        $image_name  = ImageService::upload($image, 'storage/shop/banners/');

        $last_order = ShopBanner::max('display_order');
        $display_order = $last_order + 1;

        ShopBanner::create([
            'image' => $image_name,
            'display_order' => $display_order
        ]);

        return redirect()->back()->with('success', 'Banner created successfully.');
    }

    public function update(Request $request, ShopBanner $banner)
    {
        $request->validate([
            'image' => 'mimes:png,jpeg,jpg|max:10000',
        ]);

        $image = $request->file('image');

        if ($image) {
            $image_name  = ImageService::upload($image, 'storage/shop/banners/', $banner->image);
            $banner->image = $image_name;
        }

        $banner->save();

        return redirect()->back()->with('success', 'Banner updated successfully.');
    }

    public function destroy(ShopBanner $banner)
    {
        ImageService::delete($banner->image, 'storage/shop/banners/');
        $banner->delete();

        //re order display order
        $banners = ShopBanner::all();
        foreach ($banners as $key => $value) {
            $value->display_order = $key + 1;
            $value->save();
        }

        return redirect()->back()->with('success', 'Banner deleted successfully.');
    }

    public function sort(Request $request) {
        $request->validate([
            'id' => 'required|array',
            'id.*' => 'required|numeric|exists:shop_banners,id',
        ]);

        foreach ($request->id as $key => $value) {
            $banner = ShopBanner::find($value);
            $banner->display_order = $key + 1;
            $banner->save();
        }

        return redirect()->back()->with('success', 'Banner order updated successfully.');
    }
}
