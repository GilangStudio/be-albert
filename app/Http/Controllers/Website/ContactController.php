<?php

namespace App\Http\Controllers\Website;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index() {
        $contact = Contact::first();
        return view('pages.website.contact.index', compact('contact'));
    }

    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'banner_image' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
            'section_image' => 'nullable|image|mimes:png,jpg,jpeg|max:10000',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $contact = Contact::first();

        if (!$request->hasFile('banner_image') && !isset($contact->banner_image)) {
            return redirect()->back()->with('error', 'Banner Image is required.')->withInput();
        }

        // Handle banner image upload
        $banner_image = null;
        if ($request->hasFile('banner_image')) {
            $banner_image = ImageService::upload(
                $request->file('banner_image'), 
                'storage/website/contact/', 
                isset($contact->banner_image) ? $contact->banner_image : null
            );
        }

        // Handle section image upload
        $section_image = null;
        if ($request->hasFile('section_image')) {
            $section_image = ImageService::upload(
                $request->file('section_image'), 
                'storage/website/contact/', 
                isset($contact->section_image) ? $contact->section_image : null
            );
        }

        //update or create contact
        $contact = Contact::updateOrCreate([
            'id' => 1
        ], [
            'banner_image' => $banner_image ?? ($contact->banner_image ?? null),
            'section_image' => $section_image ?? ($contact->section_image ?? null),
            'description' => $request->description,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);
        
        return redirect()->route('contact.index')->with('success', 'Contact updated successfully.');
    }
}