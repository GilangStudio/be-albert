<?php

namespace App\Http\Controllers\Shop;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\MainCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Services\SlugService;
use App\Services\ImageService;
use App\Models\ProductCategory;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['product_category' => fn ($q) => $q->with('main_category'), 'first_image'])
                            ->withSum('sizes', 'stock')
                            ->latest()->get()
                            ->map(function ($product) {
                                // Jika `sizes_sum_stock` ada, ubah namanya menjadi `total_stock`, jika tidak ada, set nilai default 0
                                $product->total_stock = $product->sizes_sum_stock ?? 0;  // Gunakan null coalescing operator untuk set default 0
                                unset($product->sizes_sum_stock);  // Hapus kolom default 'sizes_sum_stock' jika tidak diperlukan
                                return $product;
                            });

        return view('pages.shop.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $main_categories = MainCategory::all();

        return view('pages.shop.product.create', compact('main_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'product_code' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'main_category' => 'required|exists:main_categories,id',
            'product_category' => 'required|exists:product_categories,id',
            'description' => 'nullable|string',
            'size_guide' => 'required|image|mimes:png,jpg,jpeg|max:10000',
            'preorder_days' => 'nullable|numeric',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:png,jpg,jpeg|max:10000',

            'size' => 'required|array|min:1',
            'size.*' => 'required|string',
            'stock' => ['required', 'array', 'min:1', 'size:'.count($request->size)],
            'stock.*' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $slug = SlugService::create(
            $request->product_name, 
            Product::where('slug', Str::slug($request->product_name, '-'))
            ->first()
        );

        if ($request->hasFile('size_guide')) {
            $size_guide = ImageService::upload($request->file('size_guide'), 'storage/shop/products/size_guides/');
        }

        try {
            DB::beginTransaction();
            
            $product = Product::create([
                'product_code' => $request->product_code,
                'name' => $request->product_name,
                'color' => $request->color,
                'price' => $request->price,
                'description' => $request->description,
                'size_guide' => $size_guide,
                'is_preorder' => $request->preorder_days ? true : false,
                'preorder_days' => $request->preorder_days,
                'product_category_id' => $request->product_category,
                'slug' => $slug,
            ]);
    
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => ImageService::upload($image, 'storage/shop/products/'),
                        'display_order' => $product->images()->count() + 1
                    ]);
                }
            }

            foreach ($request->size as $key => $value) {
                $product->sizes()->create([
                    'product_id' => $product->id,
                    'size' => $value,
                    'stock' => $request->stock[$key],
                ]);
            }

            DB::commit();

            return redirect()->route('product.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Something went wrong')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $main_categories = MainCategory::all();
        $product_categories = ProductCategory::where('main_category_id', $product->product_category->main_category_id)->get();

        // $product_images = ProductImage::where('product_id', $product->id)->orderBy('display_order')->get();
        $product = $product->load(['images' => fn ($q) => $q->orderBy('display_order'), 'sizes']);

        return view('pages.shop.product.edit', compact('product', 'main_categories', 'product_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'product_code' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'main_category' => 'required|exists:main_categories,id',
            'product_category' => 'required|exists:product_categories,id',
            'description' => 'nullable|string',
            'size_guide' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
            'preorder_days' => 'nullable|numeric',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
            'ids' => 'nullable|array|min:1',
            'ids.*' => 'nullable|numeric|exists:product_images,id',

            'size_ids' => 'nullable|array|min:1',
            'size_ids.*' => 'nullable|numeric|exists:product_sizes,id',
            'size' => 'required|array|min:1',
            'size.*' => 'required|string',
            'stock' => 'required|array|min:1',
            'stock.*' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $slug = SlugService::create(
            $request->product_name, 
            Product::where('slug', Str::slug($request->product_name, '-'))
            ->where('id', '!=', $product->id)
            ->first()
        );

        try {
            DB::beginTransaction();

            if ($request->ids) {
                foreach ($request->ids as $key => $value) {
                    $product->images()->where('id', $value)->update([
                        'display_order' => $key + 1
                    ]);
                }
            }

            if ($request->hasFile('size_guide')) {
                $size_guide = ImageService::upload($request->file('size_guide'), 'storage/shop/products/size_guides/', $product->size_guide);
                $product->size_guide = $size_guide;
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $product->images()->create([
                        'image' => ImageService::upload($image, 'storage/shop/products/'),
                        'display_order' => $product->images()->count() + 1
                    ]);
                }
            }

            foreach ($request->size as $key => $value) {
                $size_id = isset($request->size_ids[$key]) ? $request->size_ids[$key] : null;

                if ($size_id) {
                    $product->sizes()->where('id', $size_id)->update([
                        'size' => $value,
                        'stock' => $request->stock[$key],
                    ]);
                    continue;
                }
                else {
                    $product->sizes()->create([
                        'product_id' => $product->id,
                        'size' => $value,
                        'stock' => $request->stock[$key],
                    ]);
                }
                // $product->sizes()->updateOrCreate(
                //     [
                //         'id' => $request->size_ids[$key]
                //     ],
                //     [
                //         'size' => $value,
                //         'stock' => $request->stock[$key],
                //     ]
                // );
            }
    
            $product->product_code = $request->product_code;
            $product->name = $request->product_name;
            $product->color = $request->color;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->is_preorder = $request->preorder_days ? true : false;
            $product->preorder_days = $request->preorder_days;
            $product->product_category_id = $request->product_category;
            $product->slug = $slug;
            $product->save();

            DB::commit();
    
            return redirect()->back()->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Something went wrong')->withInput();
        }

        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //No Delete Product Image because soft delete,
        //ProductImage::where('product_id', $product->id)->delete();

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product deleted successfully.');
    }
}
