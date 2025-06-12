<?php

namespace App\Http\Controllers\Shop;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Services\SlugService;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product_categories = ProductCategory::with('main_category')->latest()->get();
        $main_categories = MainCategory::latest()->get();

        return view('pages.shop.product-category.index', compact('product_categories', 'main_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'main_category' => 'required|exists:main_categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $slug = SlugService::create(
            $request->name, 
            ProductCategory::where('slug', Str::slug($request->name, '-'))
            ->first()
        );

        ProductCategory::create([
            'name' => $request->name,
            'main_category_id' => $request->main_category,
            'slug' => $slug
        ]);

        return redirect()->back()->with('success', 'Product category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'main_category' => 'required|exists:main_categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $slug = SlugService::create(
            $request->name, 
            ProductCategory::where('slug', Str::slug($request->name, '-'))
            ->where('id', '!=', $productCategory->id)
            ->first()
        );

        $productCategory->update([
            'name' => $request->name,
            'main_category_id' => $request->main_category,
            'slug' => $slug
        ]);

        return redirect()->back()->with('success', 'Product category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return redirect()->back()->with('success', 'Product category deleted successfully');
    }

    public function get() {
        if (!request()->has('id')) {
            return response()->json([
                'success' => false,
                'message' => 'Product category not found'
            ]);
        }

        $id = request()->get('id');

        $product_categories = ProductCategory::where('main_category_id', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $product_categories
        ]);
    }
}
