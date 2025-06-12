<?php

namespace App\Http\Controllers\Website;

use App\Models\About;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $abouts = About::latest()->get();

        return view('pages.website.about.index', compact('abouts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.website.about.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:png,jpg,jpeg|max:10000',
            'content' => 'required|string',
            'layout' => 'required|in:true,false',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $image = ImageService::upload($request->file('image'), 'storage/website/about/');

        About::create([
            'image' => $image,
            'content' => $request->content,
            'layout' => $request->layout == 'true' ? 1 : 0,
        ]);

        return redirect()->route('about.index')->with('success', 'About Section created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(About $about)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(About $about)
    {
        return view('pages.website.about.edit', compact('about'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, About $about)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
            'content' => 'required|string',
            'layout' => 'required|in:true,false',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        if ($request->hasFile('image')) {
            $about->image = ImageService::upload($request->file('image'), 'storage/website/about/', $about->image);
        }

        $about->content = $request->content;
        $about->layout = $request->layout == 'true' ? 1 : 0;
        $about->save();

        return redirect()->route('about.index')->with('success', 'About Section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about)
    {
        ImageService::delete($about->image, 'storage/website/about/');
        $about->delete();

        return redirect()->route('about.index')->with('success', 'About Section deleted successfully.');
    }
}
