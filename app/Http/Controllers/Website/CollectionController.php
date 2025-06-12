<?php

namespace App\Http\Controllers\Website;

use App\Models\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\SlugService;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collections = Collection::latest()->get();

        return view('pages.website.collection.index', compact('collections'));
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
            'name' => 'required|string|max:255',
            'type' => 'required|in:regular,bridal',
            'year' => 'required|numeric|digits:4',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:10000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $slug = SlugService::create(
            $request->name, 
            Collection::where('slug', Str::slug($request->name, '-'))
            ->first()
        );

        $collection = Collection::create([
            'name' => $request->name,
            'type' => $request->type,
            'collection_year' => $request->year,
            'description' => $request->description,
            'main_image' => ImageService::upload($request->file('image'), 'storage/website/collections/'),
            'slug' => $slug
        ]);

        return redirect()->route('collection.index')->with('success', 'Collection created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        $collection = $collection->load(['collection_genders' => function ($query) {
            $query->orderBy('display_order', 'asc');
        }]);
        return view('pages.website.collection.edit', compact('collection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            // 'type' => 'required|in:regular,bridal',
            'year' => 'required|numeric|digits:4',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        if ($request->hasFile('image')) {
            $collection->main_image = ImageService::upload($request->file('image'), 'storage/website/collections/', $collection->main_image);
        }

        $slug = SlugService::create(
            $request->name, 
            Collection::where('slug', Str::slug($request->name, '-'))
            ->where('id', '!=', $collection->id)
            ->first()
        );

        $collection->name = $request->name;
        // $collection->type = $request->type;
        $collection->collection_year = $request->year;
        $collection->description = $request->description;
        $collection->slug = $slug;
        $collection->save();

        return redirect()->back()->with('success', 'Collection updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        foreach ($collection->collection_genders as $collection_gender) {
            ImageService::delete($collection_gender->image, 'storage/website/collections/regular/');
        }

        ImageService::delete($collection->main_image, 'storage/website/collections/');
        $collection->delete();

        return redirect()->route('collection.index')->with('success', 'Collection deleted successfully.');
    }
}
