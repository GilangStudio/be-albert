<?php

namespace App\Http\Controllers\Shop;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ImageService;

class ProductImageController extends Controller
{

    public function destroy(Product $product, $id)
    {
        if ($product->images()->count() <= 1) {
            return redirect()->back()->with('error', 'Product image must have at least one image.');
        }

        ImageService::delete($product->images()->findOrFail($id)->image, 'storage/shop/products/');

        $product->images()->find($id)->delete();

        return redirect()->back()->with('success', 'Product image deleted successfully.');
    }
}
