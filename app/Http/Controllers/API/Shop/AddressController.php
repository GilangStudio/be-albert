<?php

namespace App\Http\Controllers\API\Shop;

use App\Models\City;
use App\Models\Address;
use App\Models\District;
use App\Models\Province;
use App\Models\SubDistrict;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PostalCode;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function getAddress()
    {
        $addresses = Address::where('user_id', auth()->user()->id)->orderBy('is_primary', 'desc')->get();

        foreach ($addresses as $address) {
            $province = Province::where('prov_id', $address->province_id)->first();
            $city = City::where('city_id', $address->city_id)->first();
            $district = District::where('dis_id', $address->district_id)->first();
            $subdistrict = SubDistrict::where('subdis_id', $address->subdistrict_id)->first();

            $address->province = [
                'id' => $address->province_id,
                'name' => $province->prov_name
            ];
            $address->city = [
                'id' => $address->city_id,
                'name' => $city->city_name
            ];
            $address->district = [
                'id' => $address->district_id,
                'name' => $district->dis_name
            ];
            $address->subdistrict = [
                'id' => $address->subdistrict_id,
                'name' => $subdistrict->subdis_name
            ];

            unset($address->province_id);
            unset($address->city_id);
            unset($address->district_id);
            unset($address->subdistrict_id);
        }

        return response()->json([
            'status' => 'success',
            'data' => $addresses
        ]);
    }

    public function getAddressDetail($id) {
        $address = Address::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if (!$address) {
            return response()->json([
              'status' => 'error',
              'message' => 'Address not found'
            ]);
        }

        $provinces = Province::all();
        $cities = City::where('prov_id', $address->province_id)->get();
        $districts = District::where('city_id', $address->city_id)->get();
        $subdistricts = SubDistrict::where('dis_id', $address->district_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'address' => $address,
                'provinces' => $provinces,
                'cities' => $cities,
                'districts' => $districts,
                'subdistricts' => $subdistricts
            ]
        ]);
    }

    public function addAddress(Request $request) {
        $validator = Validator::make($request->all(), [
            'recipient_name' => 'required|string',
            'phone_number' => 'required|string',
            'country' => 'required|string|in:indonesia',
            'province' => 'required|integer',
            'city' => 'required|integer',
            'district' => 'required|integer',
            'subdistrict' => 'required|integer',
            // 'postal_code' => 'required|string',
            'detail_address' => 'required|string',
            'is_primary' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        // Validate if province exists
        $province = Province::where('prov_id', $request->province)->first();
        if (!$province) {
            return response()->json([
                'status' => 'error',
                'message' => 'Province not found'
            ]);
        }

        // Validate if city exists and belongs to the province
        $city = City::where('city_id', $request->city)->where('prov_id', $request->province)->first();
        if (!$city) {
            return response()->json([
                'status' => 'error',
                'message' => 'City not found or does not belong to the selected province'
            ]);
        }

        // Validate if district exists and belongs to the city
        $district = District::where('dis_id', $request->district)->where('city_id', $request->city)->first();
        if (!$district) {
            return response()->json([
                'status' => 'error',
                'message' => 'District not found or does not belong to the selected city'
            ]);
        }

        // Validate if subdistrict exists and belongs to the district
        $subdistrict = SubDistrict::where('subdis_id', $request->subdistrict)->where('dis_id', $request->district)->first();
        if (!$subdistrict) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subdistrict not found or does not belong to the selected district'
            ]);
        }

        $postal_code = PostalCode::where('subdis_id', $request->subdistrict)->first();
        if (!$postal_code) {
            return response()->json([
                'status' => 'error',
                'message' => 'Postal code not found'
            ]);
        }

        if (Address::where('user_id', auth()->user()->id)->count() >= 5) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have reached the maximum address limit'
            ]);
        }

        if ($request->is_primary) {
            Address::where('user_id', auth()->user()->id)->update([
                'is_primary' => false
            ]);
        }

        //cek apakah user sudah memiliki alamat utama, jika belum maka jadikan alamat utama
        $address = Address::where('user_id', auth()->user()->id)->where('is_primary', true)->first();
        if (!$address) {
            $request->is_primary = true;
        }

        Address::create([
            'user_id' => auth()->user()->id,
            'recipient_name' => $request->recipient_name,
            'phone_number' => $request->phone_number,
            'country' => $request->country,
            'province_id' => $request->province,
            'city_id' => $request->city,
            'district_id' => $request->district,
            'subdistrict_id' => $request->subdistrict,
            'postal_code' => $postal_code->postal_code,
            'detail_address' => $request->detail_address,
            'is_primary' => $request->is_primary,
        ]);

        $addresses = Address::where('user_id', auth()->user()->id)->orderBy('is_primary', 'desc')->get();
        foreach ($addresses as $address) {
            $province = Province::where('prov_id', $address->province_id)->first();
            $city = City::where('city_id', $address->city_id)->first();
            $district = District::where('dis_id', $address->district_id)->first();
            $subdistrict = SubDistrict::where('subdis_id', $address->subdistrict_id)->first();

            $address->province = [
                'id' => $address->province_id,
                'name' => $province->prov_name
            ];
            $address->city = [
                'id' => $address->city_id,
                'name' => $city->city_name
            ];
            $address->district = [
                'id' => $address->district_id,
                'name' => $district->dis_name
            ];
            $address->subdistrict = [
                'id' => $address->subdistrict_id,
                'name' => $subdistrict->subdis_name
            ];

            unset($address->province_id);
            unset($address->city_id);
            unset($address->district_id);
            unset($address->subdistrict_id);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Address added successfully',
            'data' => $addresses
        ]);
    }

    public function updateAddress(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'country' => 'required|in:indonesia',
            'province' => 'required|integer',
            'city' => 'required|integer',
            'district' => 'required|integer',
            'subdistrict' => 'required|integer',
            'detail_address' => 'required',
            'recipient_name' => 'required',
            'phone_number' => 'required',
            'is_primary' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        $address = Address::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found'
            ]);
        }

        // Validate if province exists
        $province = Province::where('prov_id', $request->province)->first();
        if (!$province) {
            return response()->json([
                'status' => 'error',
                'message' => 'Province not found'
            ]);
        }

        // Validate if city exists
        $city = City::where('city_id', $request->city)->first();
        if (!$city) {
            return response()->json([
                'status' => 'error',
                'message' => 'City not found'
            ]);
        }

        // Validate if district exists
        $district = District::where('dis_id', $request->district)->first();
        if (!$district) {
            return response()->json([
               'status' => 'error',
               'message' => 'District not found'
            ]);
        }

        // Validate if subdistrict exists
        $subdistrict = SubDistrict::where('subdis_id', $request->subdistrict)->first();
        if (!$subdistrict) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subdistrict not found'
            ]);
        }

        $postal_code = PostalCode::where('subdis_id', $request->subdistrict)->first();
        if (!$postal_code) {
            return response()->json([
                'status' => 'error',
                'message' => 'Postal code not found'
            ]);
        }

        //check if address is primary
        if ($request->is_primary == 1) {
            Address::where('user_id', auth()->user()->id)->update([
                'is_primary' => false
            ]);
        }

        $address->update([
            'recipient_name' => $request->recipient_name,
            'phone_number' => $request->phone_number,
            'country' => $request->country,
            'province_id' => $request->province,
            'city_id' => $request->city,
            'district_id' => $request->district,
            'subdistrict_id' => $request->subdistrict,
            'postal_code' => $postal_code->postal_code,
            'detail_address' => $request->detail_address,
            'is_primary' => $request->is_primary,
        ]);

        $addresses = Address::where('user_id', auth()->user()->id)->orderBy('is_primary', 'desc')->get();
        foreach ($addresses as $address) {
            $province = Province::where('prov_id', $address->province_id)->first();
            $city = City::where('city_id', $address->city_id)->first();
            $district = District::where('dis_id', $address->district_id)->first();
            $subdistrict = SubDistrict::where('subdis_id', $address->subdistrict_id)->first();

            $address->province = [
                'id' => $address->province_id,
                'name' => $province->prov_name
            ];
            $address->city = [
                'id' => $address->city_id,
                'name' => $city->city_name
            ];
            $address->district = [
                'id' => $address->district_id,
                'name' => $district->dis_name
            ];
            $address->subdistrict = [
                'id' => $address->subdistrict_id,
                'name' => $subdistrict->subdis_name
            ];

            unset($address->province_id);
            unset($address->city_id);
            unset($address->district_id);
            unset($address->subdistrict_id);
        }

        return response()->json([
            'status' =>'success',
            'message' => 'Address updated successfully',
            'data' => $addresses
        ]);
    }

    public function removeAddress(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        $address = Address::where('id', $request->id)->where('user_id', auth()->user()->id)->first();
        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found'
            ]);
        }

        $addresses = Address::where('user_id', auth()->user()->id)->get();
        if (count($addresses) < 2) {
            return response()->json([
                'status' => 'error',
                'message' => 'You must have at least 1 address'
            ]);
        }

        $address->delete();

        $addresses = Address::where('user_id', auth()->user()->id)->get();

        foreach ($addresses as $address) {
            $province = Province::where('prov_id', $address->province_id)->first();
            $city = City::where('city_id', $address->city_id)->first();
            $district = District::where('dis_id', $address->district_id)->first();
            $subdistrict = SubDistrict::where('subdis_id', $address->subdistrict_id)->first();

            $address->province = [
                'id' => $address->province_id,
                'name' => $province->prov_name
            ];
            $address->city = [
                'id' => $address->city_id,
                'name' => $city->city_name
            ];
            $address->district = [
                'id' => $address->district_id,
                'name' => $district->dis_name
            ];
            $address->subdistrict = [
                'id' => $address->subdistrict_id,
                'name' => $subdistrict->subdis_name
            ];

            unset($address->province_id);
            unset($address->city_id);
            unset($address->district_id);
            unset($address->subdistrict_id);
        }

        //jika addresses tidak null dan tidak ada yang primary maka set address pertama sebagai primary
        if ($addresses) {
            if (count($addresses) > 0 && !$addresses->where('is_primary', true)->first()) {
                $addresses->first()->update([
                    'is_primary' => true
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Address removed successfully',
            'data' => $addresses
        ]);
    }

    public function setPrimaryAddress(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        $address = Address::where('id', $request->id)->where('user_id', auth()->user()->id)->first();
        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found'
            ]);
        }

        Address::where('user_id', auth()->user()->id)->update([
            'is_primary' => false
        ]);
        $address->update([
            'is_primary' => true
        ]);

        $addresses = Address::where('user_id', auth()->user()->id)->orderBy('is_primary', 'desc')->get();

        foreach ($addresses as $address) {
            $province = Province::where('prov_id', $address->province_id)->first();
            $city = City::where('city_id', $address->city_id)->first();
            $district = District::where('dis_id', $address->district_id)->first();
            $subdistrict = SubDistrict::where('subdis_id', $address->subdistrict_id)->first();

            $address->province = [
                'id' => $address->province_id,
                'name' => $province->prov_name
            ];
            $address->city = [
                'id' => $address->city_id,
                'name' => $city->city_name
            ];
            $address->district = [
                'id' => $address->district_id,
                'name' => $district->dis_name
            ];
            $address->subdistrict = [
                'id' => $address->subdistrict_id,
                'name' => $subdistrict->subdis_name
            ];

            unset($address->province_id);
            unset($address->city_id);
            unset($address->district_id);
            unset($address->subdistrict_id);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Address set as primary successfully',
            'data' => $addresses
        ]);
    }


    public function getProvince() {
        $provinces = Province::all();
        return response()->json([
           'status' =>'success',
            'data' => $provinces
        ]);
    }

    public function getCity(Request $request) {
        $validator = Validator::make($request->all(), [
            'province_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        $cities = City::where('prov_id', $request->province_id)->get();

        return response()->json([
          'status' =>'success',
            'data' => $cities
        ]);
    }

    public function getDistrict(Request $request) {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        $districts = District::where('city_id', $request->city_id)->get();
        return response()->json([
           'status' =>'success',
            'data' => $districts
        ]);
    }
    
    public function getSubdistrict(Request $request) {
        $validator = Validator::make($request->all(), [
            'district_id' =>'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json([
               'status' => 'error',
               'message' => $validator->errors()->first()
            ]);
        }

        $subdistricts = SubDistrict::where('dis_id', $request->district_id)->get();
        return response()->json([
          'status' =>'success',
            'data' => $subdistricts
        ]);
    }
}
