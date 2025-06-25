<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Models\CollectionBanner;
use App\Models\CollectionBannerImage;
use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Support\Facades\Validator;

class CollectionBannerController extends Controller
{
    // Collection Banner
    public function collectionIndex()
    {
        $banner = CollectionBanner::collection()->with('images')->first();
        return view('pages.website.collection-banner.collection.index', compact('banner'));
    }

    public function collectionUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
            'remove_image_ids' => 'nullable|array',
            'remove_image_ids.*' => 'nullable|numeric|exists:collection_banner_images,id',
            'image_orders' => 'nullable|array',
            'image_orders.*' => 'nullable|numeric|exists:collection_banner_images,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update atau create banner
        $banner = CollectionBanner::updateOrCreate([
            'type' => 'collection'
        ], [
            'description' => $request->description,
        ]);

        // Hapus gambar yang dipilih untuk dihapus
        if ($request->remove_image_ids) {
            foreach ($request->remove_image_ids as $id) {
                $image = CollectionBannerImage::find($id);
                if ($image) {
                    ImageService::delete($image->image, 'storage/website/collection-banners/');
                    $image->delete();
                }
            }
        }

        // Update urutan gambar
        if ($request->image_orders) {
            foreach ($request->image_orders as $order => $imageId) {
                CollectionBannerImage::where('id', $imageId)->update([
                    'display_order' => $order + 1
                ]);
            }
        }

        // Upload gambar baru
        if ($request->hasFile('images')) {
            $lastOrder = CollectionBannerImage::where('collection_banner_id', $banner->id)->max('display_order') ?? 0;
            
            foreach ($request->file('images') as $index => $file) {
                $imageName = ImageService::upload($file, 'storage/website/collection-banners/');

                CollectionBannerImage::create([
                    'collection_banner_id' => $banner->id,
                    'image' => $imageName,
                    'display_order' => $lastOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('collection-banner.collection.index')->with('success', 'Banner Collection berhasil diperbarui.');
    }

    public function collectionImageDestroy($id)
    {
        $image = CollectionBannerImage::findOrFail($id);
        ImageService::delete($image->image, 'storage/website/collection-banners/');
        $image->delete();

        return redirect()->route('collection-banner.collection.index')->with('success', 'Gambar Banner Collection berhasil dihapus.');
    }

    public function collectionSort(Request $request)
    {
        $request->validate([
            'image_ids' => 'required|array',
            'image_ids.*' => 'required|numeric|exists:collection_banner_images,id',
        ]);

        foreach ($request->image_ids as $key => $value) {
            CollectionBannerImage::where('id', $value)->update([
                'display_order' => $key + 1
            ]);
        }

        return redirect()->route('collection-banner.collection.index')->with('success', 'Urutan gambar Banner Collection berhasil diperbarui.');
    }

    // Bridal Banner
    public function bridalIndex()
    {
        $banner = CollectionBanner::bridal()->with('images')->first();
        return view('pages.website.collection-banner.bridal.index', compact('banner'));
    }

    public function bridalUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
            'remove_image_ids' => 'nullable|array',
            'remove_image_ids.*' => 'nullable|numeric|exists:collection_banner_images,id',
            'image_orders' => 'nullable|array',
            'image_orders.*' => 'nullable|numeric|exists:collection_banner_images,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update atau create banner
        $banner = CollectionBanner::updateOrCreate([
            'type' => 'bridal'
        ], [
            'description' => $request->description,
        ]);

        // Hapus gambar yang dipilih untuk dihapus
        if ($request->remove_image_ids) {
            foreach ($request->remove_image_ids as $id) {
                $image = CollectionBannerImage::find($id);
                if ($image) {
                    ImageService::delete($image->image, 'storage/website/collection-banners/');
                    $image->delete();
                }
            }
        }

        // Update urutan gambar
        if ($request->image_orders) {
            foreach ($request->image_orders as $order => $imageId) {
                CollectionBannerImage::where('id', $imageId)->update([
                    'display_order' => $order + 1
                ]);
            }
        }

        // Upload gambar baru
        if ($request->hasFile('images')) {
            $lastOrder = CollectionBannerImage::where('collection_banner_id', $banner->id)->max('display_order') ?? 0;
            
            foreach ($request->file('images') as $index => $file) {
                $imageName = ImageService::upload($file, 'storage/website/collection-banners/');

                CollectionBannerImage::create([
                    'collection_banner_id' => $banner->id,
                    'image' => $imageName,
                    'display_order' => $lastOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('collection-banner.bridal.index')->with('success', 'Banner Bridal berhasil diperbarui.');
    }

    public function bridalImageDestroy($id)
    {
        $image = CollectionBannerImage::findOrFail($id);
        ImageService::delete($image->image, 'storage/website/collection-banners/');
        $image->delete();

        return redirect()->route('collection-banner.bridal.index')->with('success', 'Gambar Banner Bridal berhasil dihapus.');
    }

    public function bridalSort(Request $request)
    {
        $request->validate([
            'image_ids' => 'required|array',
            'image_ids.*' => 'required|numeric|exists:collection_banner_images,id',
        ]);

        foreach ($request->image_ids as $key => $value) {
            CollectionBannerImage::where('id', $value)->update([
                'display_order' => $key + 1
            ]);
        }

        return redirect()->route('collection-banner.bridal.index')->with('success', 'Urutan gambar Banner Bridal berhasil diperbarui.');
    }
}