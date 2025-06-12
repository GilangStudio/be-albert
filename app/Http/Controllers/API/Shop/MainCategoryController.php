<?php

namespace App\Http\Controllers\API\Shop;

use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainCategoryController extends Controller
{
    public function get() {
        return response()->json([
            'status' => 'success',
            'data' => MainCategory::all()->map(function ($mainCategory) {
                return [
                    'id' => $mainCategory->id,
                    'name' => $mainCategory->name,
                    'slug' => $mainCategory->slug
                ];
            })
        ]);
    }

    public function getProduct($slug) {
        $mainCategory = MainCategory::where('slug', $slug)
            ->with('product_categories.products')
            ->firstOrFail();

        // Ambil semua produk dari kategori terkait
        $products = $mainCategory->product_categories->flatMap->products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'color' => $product->color,
                'is_preorder' => $product->is_preorder,
                'price' => $product->price,
                'product_code' => $product->product_code,
                'category_name' => $product->product_category->name,
                'image' => asset('storage/shop/products/' . $product->first_image->image),
                // 'product_category_id' => $product->product_category_id,
                'size_guide' => asset('storage/shop/products/size_guides/' . $product->size_guide),
                'slug' => $product->slug,
            ];
        });

        // $products = $mainCategory->product_categories->map(function ($category) {
        //     return [
        //         'collection' => $category->name,
        //         'products' => $category->products->map(function ($product) {
        //             return [
        //                 'id' => $product->id,
        //                 'name' => $product->name,
        //                 'slug' => $product->slug,
        //                 'color' => $product->color,
        //                 'is_preorder' => $product->is_preorder,
        //                 'price' => $product->price,
        //                 'product_code' => $product->product_code,
        //                 'size_guide' => asset('storage/shop/products/size_guides/' . $product->size_guide),
        //                 'slug' => $product->slug,
        //             ];
        //         })
        //     ];
        // });


        return response()->json([
            'status' => 'success',
            'data' => [
                'collection' => $mainCategory->name,
                'products' => $products,
            ]
        ]);
    }
}
