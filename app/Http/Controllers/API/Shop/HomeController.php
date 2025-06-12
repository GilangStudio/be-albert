<?php

namespace App\Http\Controllers\API\Shop;

use App\Models\Product;
use App\Models\ShopBanner;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Models\FashionWeekBanner;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function getHome() {
        $shop_banners = ShopBanner::select('image')->orderBy('display_order')->get()->map(function ($banner) {
            return asset('storage/shop/banners/' . $banner->image); // Mengambil hanya URL gambar
        });

        $fashion_week_banner = FashionWeekBanner::select('image')->orderBy('display_order')->get()->map(function ($banner) {
            return asset('storage/shop/fashion-week-banners/'. $banner->image); // Mengambil hanya URL gambar
        });
        // foreach ($fashion_week_banner as $key => $value) {
        //     $fashion_week_banner[$key]['image'] = asset('storage/shop/fashion-week-banners/' . $value->image);
        // }

        //get best selling products
        // $best_selling_products = OrderProduct::with('product')->get()->groupBy('product_id')->sortByDesc(function($group) {
        //     return $group->count();
        // })->take(4)->pluck('product');

        $best_selling_products = OrderProduct::select('product_id', DB::raw('COUNT(*) as total_sales'))
        ->groupBy('product_id')
        ->orderByDesc('total_sales')
        ->take(4)
        ->with(['product:id,name,slug,product_category_id', 'product.first_image:id,product_id,image', 'product.product_category:id,main_category_id,name,slug', 'product.product_category.main_category:id,name,slug'])
        ->get()
        ->makeHidden(['product_id']);

        $best_selling_products->each(function ($best_selling_product) {
            $best_selling_product->product->image = asset('storage/shop/products/' . $best_selling_product->product->first_image->image);
            $best_selling_product->product->makeHidden(['first_image', 'product_category_id']);
        });

        $recommended_products = Product::select('id', 'name', 'slug', 'color', 'is_preorder', 'price', 'product_code', 'slug', 'product_category_id')->with(['first_image'])
        ->inRandomOrder()
        ->take(4)
        ->get()
        ->map(function($product) {
            $product->image = asset('storage/shop/products/' . $product->first_image->image);
            unset($product->first_image);
            unset($product->product_category_id);
            return $product;
        });
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'shop_banners' => $shop_banners,
                'fashion_week_banners' => $fashion_week_banner,
                'best_selling_products' => $best_selling_products,
                'recommended_products' => $recommended_products,
            ]
        ]);
    }
}
