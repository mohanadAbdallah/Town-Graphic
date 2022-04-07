<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showCategories()
    {
        $account_type = auth('sanctum')->user()->account_type;
        $categories = Category::where('status',1)->where('type',$account_type)->get();
        return response()->json(['data'=>$categories],200);
    }
    public function showSubCategories(Category $category)
    {
        $subCategories =$category->subCategory;
        return response()->json(['data'=>$subCategories],200);
    }
    public function showAgentCategories($agent_id)
    {
        $account_type = auth('sanctum')->user()->account_type;
        $categories = Category::where('status',1)
            ->where('user_id',$agent_id)
            ->where('type',$account_type)
            ->get();
        return response()->json(['data'=>$categories],200);
    }
}
