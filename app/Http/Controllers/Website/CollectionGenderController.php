<?php

namespace App\Http\Controllers\Website;

use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Support\Facades\Validator;

class CollectionGenderController extends Controller
{
    public function update(Request $request, Collection $collection) {
        $validator = Validator::make($request->all(), [
            // 'collection_gender' => 'required|in:men,women',
            //collection gender required if collection type is regular
            'collection_gender' => $collection->type == 'regular' ? 'required|in:men,women' : 'nullable',
            'ids' => 'nullable|array|min:1',
            'ids.*' => 'nullable|numeric|exists:collection_genders,id',
            'images' => 'nullable|array|min:1',
            'images.*' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        if ($request->ids) {
            foreach ($request->ids as $key => $value) {
                $collection->collection_genders()->where('id', $value)->update([
                    'display_order' => $key + 1
                ]);
            }
        }

        // ImageService::upload()
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                if ($collection->type == 'regular') {
                    $gender = $request->collection_gender;
                    $storage_path = 'storage/website/collections/regular/';
                }
                else {
                    $gender = '';
                    $storage_path = 'storage/website/collections/bridal/';
                }

                $collection->collection_genders()->create([
                    'gender' => $gender,
                    'image' => ImageService::upload($image, $storage_path),
                    'display_order' => $collection->collection_genders()->count() + 1
                ]);
            }
        }

        return redirect()->back()->with('success', 'Collection gender updated successfully.');
    }

    public function destroy(Collection $collection, $id) {
        ImageService::delete($collection->collection_genders()->find($id)->image, 'storage/website/collections/regular/');
        
        $collection->collection_genders()->find($id)->delete();

        return redirect()->back()->with('success', 'Collection gender deleted successfully.');
    }
}
