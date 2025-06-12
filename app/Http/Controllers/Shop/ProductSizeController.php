<?php

namespace App\Http\Controllers\Shop;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductSizeController extends Controller
{
    public function destroy(Product $product, $id)
    {
        if ($product->sizes()->count() <= 1) {
            return redirect()->back()->with('error', 'Product size must have at least one size.');
        }

        $product->sizes()->find($id)->delete();

        return redirect()->back()->with('success', 'Product size deleted successfully.');
    }
}
