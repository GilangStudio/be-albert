<?php

namespace App\Http\Controllers\API\Shop;

use App\Models\Product;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FilterController extends Controller
{
    public function get($slug) {
        $mainCategory = MainCategory::with(['products.sizes'])->where('slug', $slug)->first();

        // Mengumpulkan semua warna unik
        // $colors = $mainCategory->products->flatMap->colors->unique('color')->values();
        $colors = Product::whereHas('product_category', function ($query) use ($mainCategory) {
            $query->where('main_category_id', $mainCategory->id);
        })
        ->pluck('color')
        ->unique()
        ->values();
        
        //get product categories name
        $product_categories = $mainCategory->product_categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug
            ];
        });

        // Mengumpulkan semua ukuran unik
        $sizes = $mainCategory->products->flatMap->sizes->unique('size')->pluck('size')->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'product_categories' => $product_categories,
                'colors' => $colors,
                'sizes' => $sizes
            ]
        ]);
    }

    public function filter(Request $request, $slug) {
        // {"categories":[1,2],"sizes":["L","S"],"colors":["Orange"]}

        $validator = Validator::make($request->all(), [
            'categories' => 'nullable|array|exists:product_categories,id',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        $categories = $request->categories;
        $sizes = $request->sizes;
        $colors = $request->colors;

        $query = Product::query();

        // Filter berdasarkan kategori
        if ($request->has('categories') && !empty($request->categories)) {
            $query->whereIn('product_category_id', $request->categories);
        }

        // Filter berdasarkan ukuran
        if ($request->has('sizes') && !empty($request->sizes)) {
            $query->whereHas('sizes', function ($sizeQuery) use ($request) {
                $sizeQuery->whereIn('size', $request->sizes);
            });
        }

        // Filter berdasarkan warna
        if ($request->has('colors') && !empty($request->colors)) {
            $query->whereIn('color', $request->colors);
        }

        // $products = $query->with(['product_category'])->get();
        $products = $query->with(['product_category:id,main_category_id,name,slug', 'first_image'])->whereHas('product_category', function ($query) use ($slug) {
            $query->whereHas('main_category', function ($mainQuery) use ($slug) {
                $mainQuery->where('slug', $slug);
            });
        })
        // ->latest()->get();

        //sort by, latest to oldest, oldest to latest, price low to high, price high to low
        ->when($request->sort, function ($query) use ($request) {
            switch ($request->sort) {
                case 'latest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'lowest':
                    $query->orderBy('price', 'asc');
                    break;
                case 'highest':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        })->get();

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
