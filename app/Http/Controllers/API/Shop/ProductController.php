<?php

namespace App\Http\Controllers\API\Shop;

use App\Models\Product;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function getAllProducts()
    {
        $products = Product::select('id', 'name', 'slug', 'color', 'is_preorder', 'price', 'product_code', 'product_category_id', 'size_guide', 'slug')
                            ->with(['product_category' => fn ($q) => $q->select('id', 'main_category_id', 'name', 'slug')->with('main_category:id,name,slug'), 'first_image'])
                            ->withSum('sizes', 'stock')
                            ->latest()->get()
                            ->map(function ($product) {
                                $product->total_stock = $product->sizes_sum_stock ?? 0;  // Gunakan null coalescing operator untuk set default 0
                                unset($product->sizes_sum_stock);  // Hapus kolom default 'sizes_sum_stock' jika tidak diperlukan
                                return $product;
                            });
        
        foreach ($products as $product) {
            if ($product->first_image) {
                $product->image = asset('storage/shop/products/' . $product->first_image->image);
                unset($product->first_image);
            }

            $product->size_guide = asset('storage/shop/products/size_guides/' . $product->size_guide);
        }

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    public function getProductBySlug($slug)
    {
        $product = Product::select('id', 'name', 'slug', 'description', 'discount_percentage', 'color', 'is_preorder', 'price', 'product_code', 'product_category_id', 'size_guide', 'slug')
                            ->with(['product_category' => 
                                fn ($q) => $q->select('id', 'main_category_id', 'name', 'slug')
                                ->with('main_category:id,name,slug'), 'images' => fn ($q) => $q->select('id', 'product_id', 'image')->orderBy('display_order'), 'sizes' => fn ($q) => $q->select('id', 'product_id', 'size', 'stock')])
                            ->withSum('sizes', 'stock')
                            ->where('slug', $slug)->firstOrFail();

        // if (!$product) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Product not found'
        //     ]);
        // }

        $product->total_stock = $product->sizes_sum_stock ?? 0;  // Gunakan null coalescing operator untuk set default 0
        unset($product->sizes_sum_stock);  // Hapus kolom default 'sizes_sum_stock' jika tidak diperlukan

        if ($product->images) {
            $product->images = $product->images->map(function ($image) {
                $image->image = asset('storage/shop/products/' . $image->image);
                return $image;
            });
            // foreach ($product->images as $image) {
            //     $image->image = asset('storage/shop/products/' . $image->image);
            // }
        }

        $product->size_guide = asset('storage/shop/products/size_guides/' . $product->size_guide);

        $product->related_products = Product::whereHas('product_category', function ($query) use ($product) {
            $query->where('main_category_id', $product->product_category->main_category_id);
        })
        ->where('id', '!=', $product->id)
        ->inRandomOrder()
        ->limit(3)
        ->get()
        ->map(function ($product) {
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
                'slug' => $product->slug,
            ];
        });
        

        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }

    public function searchProducts(Request $request) 
    {
        $search = $request->input('q');
        $products = Product::select('id', 'name', 'slug', 'color', 'is_preorder', 'price', 'product_code', 'product_category_id', 'size_guide', 'slug')
                            ->where('name', 'like', '%' . $search . '%')
                            ->with(['product_category' => fn ($q) => $q->select('id', 'main_category_id', 'name', 'slug')->with('main_category:id,name,slug'), 'first_image'])
                            ->withSum('sizes', 'stock')
                            ->latest()->get()
                            ->map(function ($product) {
                                $product->total_stock = $product->sizes_sum_stock ?? 0;  // Gunakan null coalescing operator untuk set default 0
                                unset($product->sizes_sum_stock);  // Hapus kolom default 'sizes_sum_stock' jika tidak diperlukan
                                return $product;
                            });
        
        foreach ($products as $product) {
            if ($product->first_image) {
                $product->image = asset('storage/shop/products/' . $product->first_image->image);
                unset($product->first_image);
            }

            $product->size_guide = asset('storage/shop/products/size_guides/' . $product->size_guide);
        }

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }
}
