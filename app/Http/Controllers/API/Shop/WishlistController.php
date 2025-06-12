<?php

namespace App\Http\Controllers\API\Shop;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function getWishlist() {
        $wishlists = Wishlist::select('id', 'user_id', 'product_id')->with(['product' => function ($query) {
            $query->select('id', 'name', 'slug', 'color', 'is_preorder', 'price', 'product_code', 'slug')->with(['first_image']);
        }])->where('user_id', auth()->user()->id)->get();

        $product_wishlists = $wishlists->map(function ($wishlist) {
            $wishlist->product->image = asset('storage/shop/products/' . $wishlist->product->first_image->image);
            unset($wishlist->product->first_image);  
            return $wishlist->product;      
        });

        return response()->json([
            'status' => 'success',
            'data' => $product_wishlists
        ]);
    }

    public function getWishlistUserProduct($slug) {
        // $wishlist = Wishlist::where('user_id', auth()->user()->id)->with('product')->get();
        // return response()->json([
        //     'status' => 'success',
        //     'data' => $wishlist
        // ]);

        // get one product from slug
        $product = Product::where('slug', $slug)->first();

        $wishlist = Wishlist::where('user_id', auth()->user()->id)
            ->where('product_id', $product->id)
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'is_wishlist' => $wishlist ? true : false
            ]
        ]);
    }

    public function wishlist(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        $wishlist = Wishlist::where('user_id', auth()->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        //delete if exist
        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Product removed from wishlist successfully.'
            ]);
        }

        $wishlist = Wishlist::create([
            'user_id' => auth()->user()->id,
            'product_id' => $request->product_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to wishlist successfully.',
            'data' => [
                'total_wishlist' => Wishlist::where('user_id', auth()->user()->id)->count()
            ]
        ]);
    }

    public function removeWishlist(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        $wishlist = Wishlist::where('user_id', auth()->user()->id)->where('product_id', $request->id)->first();
        $wishlist->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product removed from wishlist successfully.'
        ]);
    }

    public function getTotalWishlist() {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_wishlist' => Wishlist::where('user_id', auth()->user()->id)->count()
            ]
        ]);
    }
}
