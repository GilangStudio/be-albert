<?php

namespace App\Http\Controllers\Website;

use Carbon\Carbon;
use App\Models\Press;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $presses = Press::latest()->get();

        return view('pages.website.press.index', compact('presses'));
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
            'title' => 'required|string|max:255',
            'published_on' => 'required|string|max:255',
            'published_date' => 'required|date_format:d/m/Y',
            'link' => 'required|string|max:255',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:10000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $press = Press::create([
            'title' => $request->title,
            'published_on' => $request->published_on,
            'published_date' => Carbon::createFromFormat('d/m/Y', $request->published_date)->format('Y-m-d'),
            'link' => $request->link,
            'image' => ImageService::upload($request->file('image'), 'storage/website/presses/'),
        ]);

        return redirect()->route('press.index')->with('success', 'Press created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Press $press)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Press $press)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Press $press)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'published_on' => 'required|string|max:255',
            'published_date' => 'required|date_format:d/m/Y',
            'link' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        if ($request->hasFile('image')) {
            $press->image = ImageService::upload($request->file('image'), 'storage/website/presses/', $press->image);
        }

        $press->title = $request->title;
        $press->published_on = $request->published_on;
        $press->published_date = Carbon::createFromFormat('d/m/Y', $request->published_date)->format('Y-m-d');
        $press->link = $request->link;
        $press->save();

        return redirect()->back()->with('success', 'Press updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Press $press)
    {
        ImageService::delete($press->image, 'storage/website/presses/');
        $press->delete();

        return redirect()->route('press.index')->with('success', 'Press deleted successfully.');
    }
}
