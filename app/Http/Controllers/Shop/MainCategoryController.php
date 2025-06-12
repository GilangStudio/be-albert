<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Support\Str;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Services\SlugService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MainCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $main_categories = MainCategory::latest()->get();

        return view('pages.shop.main-category.index', compact('main_categories'));
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $slug = SlugService::create(
            $request->name, 
            MainCategory::where('slug', Str::slug($request->name, '-'))
            ->first()
        );

        MainCategory::create([
            'name' => $request->name,
            'slug' => $slug
        ]);

        return redirect()->route('main-category.index')->with('success', 'Main category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MainCategory $mainCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MainCategory $mainCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MainCategory $mainCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $slug = SlugService::create(
            $request->name, 
            MainCategory::where('slug', Str::slug($request->name, '-'))
            ->where('id', '!=', $mainCategory->id)
            ->first()
        );

        $mainCategory->update([
            'name' => $request->name,
            'slug' => $slug
        ]);

        return redirect()->route('main-category.index')->with('success', 'Main category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MainCategory $mainCategory)
    {
        $mainCategory->delete();

        return redirect()->route('main-category.index')->with('success', 'Main category deleted successfully.');
    }
}
