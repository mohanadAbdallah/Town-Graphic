<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Category\SubCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showProducts(SubCategory $subcategory)
    {
         $products = $subcategory->products;
        return response()->json(['data' => $products], 200);
    }
    public function showAllProducts(Category $category)
    {
         $products = $category->product;
        return response()->json(['data' => $products], 200);
    }
}
