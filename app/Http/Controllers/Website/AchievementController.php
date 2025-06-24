<?php

namespace App\Http\Controllers\Website;

use App\Models\Achievement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ImageService;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::all()->sortBy('display_order');
        return view('pages.website.achievement.index', compact('achievements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|mimes:png,jpeg,jpg|max:10000',
        ]);

        $image = $request->file('image');
        $image_name = ImageService::upload($image, 'storage/website/achievements/');

        $last_order = Achievement::max('display_order');
        $display_order = $last_order + 1;

        Achievement::create([
            'name' => $request->name,
            'image' => $image_name,
            'display_order' => $display_order
        ]);

        return redirect()->route('achievement.index')->with('success', 'Achievement created successfully.');
    }

    public function update(Request $request, Achievement $achievement)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'mimes:png,jpeg,jpg|max:10000',
        ]);

        $achievement->name = $request->name;

        $image = $request->file('image');
        if ($image) {
            $image_name = ImageService::upload($image, 'storage/website/achievements/', $achievement->image);
            $achievement->image = $image_name;
        }

        $achievement->save();

        return redirect()->route('achievement.index')->with('success', 'Achievement updated successfully.');
    }

    public function destroy(Achievement $achievement)
    {
        ImageService::delete($achievement->image, 'storage/website/achievements/');
        $achievement->delete();

        // Re-order display order
        $achievements = Achievement::all();
        foreach ($achievements as $key => $value) {
            $value->display_order = $key + 1;
            $value->save();
        }

        return redirect()->route('achievement.index')->with('success', 'Achievement deleted successfully.');
    }

    public function sort(Request $request) 
    {
        $request->validate([
            'id' => 'required|array',
            'id.*' => 'required|numeric|exists:achievements,id',
        ]);

        foreach ($request->id as $key => $value) {
            $achievement = Achievement::find($value);
            $achievement->display_order = $key + 1;
            $achievement->save();
        }

        return redirect()->route('achievement.index')->with('success', 'Achievement order updated successfully.');
    }
}