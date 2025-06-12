<?php

namespace App\Http\Controllers\API\Website;

use App\Models\Banner;
use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Contact;
use App\Models\Press;
use App\Models\WebsiteSettings;

class ApiController extends Controller
{
    // Route::get('/home/get', [ApiController::class, 'get_home']);
    // Route::get('/about/get', [ApiController::class, 'get_about']);
    // Route::get('/press/get', [ApiController::class, 'get_press']);
    // Route::get('/contact/get', [ApiController::class, 'get_contact']);
    // Route::get('/collection/get', [ApiController::class, 'get_collection']);
    // Route::get('/collection/{slug}/get', [ApiController::class, 'get_collection_detail']);
    
    // Route::get('/collection/bridal/get', [ApiController::class, 'get_collection_bridal']);
    // Route::get('/collection/bridal/{slug}/get', [ApiController::class, 'get_collection_bridal_detail']);

    public function get_home() {
        $banners = Banner::select('id', 'image', 'display_order')->orderBy('display_order', 'asc')->get();
        $collections = Collection::select('id', 'name', 'slug', 'type', 'collection_year', 'main_image')->where('is_active', 1)->orderBy('collection_year', 'desc')->limit(3)->get();
        $presses = Press::select('id', 'title', 'published_on', 'published_date', 'link', 'image')->orderBy('published_date', 'desc')->limit(3)->get();

        foreach ($banners as $banner) {
            $banner->image = asset('storage/website/banners/' . $banner->image);
        }

        foreach ($collections as $collection) {
            $collection->main_image = asset('storage/website/collections/' . $collection->main_image);
        }

        foreach ($presses as $press) {
            $press->image = asset('storage/website/presses/' . $press->image);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'banners' => $banners,
                'collections' => $collections,
                'presses' => $presses
            ]
        ]);
    }

    public function get_about() {
        $about = About::select('id', 'image', 'content', 'layout')->orderBy('id', 'asc')->get();

        foreach ($about as $a) {
            $a->layout_default = $a->layout == 1 ? true : false;
            $a->image = asset('storage/website/about/' . $a->image);

            unset($a->layout);
        }

        return response()->json([
            'status' => 'success',
            'data' => $about
        ]);
    }

    public function get_press() {
        $presses = Press::select('id', 'title', 'published_on', 'published_date', 'link', 'image')->orderBy('published_date', 'desc')->get();

        foreach ($presses as $press) {
            $press->image = asset('storage/website/presses/' . $press->image);

            $press->published_date = date('d-m-Y', strtotime($press->published_date));
        }

        return response()->json([
            'status' => 'success',
            'data' => $presses
        ]);
    }

    public function get_contact() {
        $contact = Contact::select('banner_image', 'description', 'address', 'phone_number', 'email')->firstOrFail();

        $contact->banner_image = asset('storage/website/contact/' . $contact->banner_image);        

        return response()->json([
            'status' => 'success',
            'data' => $contact
        ]);
    }

    public function get_settings() {
        $settings = WebsiteSettings::select('logo', 'instagram_url', 'facebook_url', 'whatsapp_url')->firstOrFail();

        $settings->logo = asset('storage/website/' . $settings->logo);

        return response()->json([
            'status' => 'success',
            'data' => $settings
        ]);
    }

    public function get_collection() {
        $collections = Collection::select('id', 'name', 'slug', 'type', 'collection_year', 'main_image')->where('is_active', 1)->where('type', 'regular')->orderBy('collection_year', 'desc')->get();

        foreach ($collections as $collection) {
            $collection->main_image = asset('storage/website/collections/' . $collection->main_image);
        }

        return response()->json([
            'status' => 'success',
            'data' => $collections
        ]);
    }

    public function get_collection_bridal() {
        $collections = Collection::select('id', 'name', 'slug', 'type', 'collection_year', 'main_image')->where('is_active', 1)->where('type', 'bridal')->orderBy('collection_year', 'desc')->get();

        foreach ($collections as $collection) {
            $collection->main_image = asset('storage/website/collections/' . $collection->main_image);
        }

        return response()->json([
            'status' => 'success',
            'data' => $collections
        ]);
    }

    public function get_collection_detail($slug) {
        $collection = Collection::with(['collection_images' => function ($query) {
            $query->select('id', 'collection_id', 'gender', 'image')->orderBy('display_order', 'asc');
        }])->select('id', 'name', 'slug', 'type', 'collection_year', 'description', 'main_image')->where('slug', $slug)->firstOrFail();

        $collection->main_image = asset('storage/website/collections/' . $collection->main_image);

        foreach ($collection->collection_images as $collection_gender) {
            unset($collection_gender->collection_id);
            $collection_gender->image = asset('storage/website/collections/' . $collection->type . '/' . $collection_gender->image);
        }

        return response()->json([
            'status' => 'success',
            'data' => $collection
        ]);
    }
}
