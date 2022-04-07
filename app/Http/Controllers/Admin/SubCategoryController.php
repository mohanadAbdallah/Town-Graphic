<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Category\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        return view('admin.categories.subCategories.create',compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_ar' => 'required',
            'title_en' => 'required',
            'category_id' => 'required',
        ]);
        $category = Category::find($request->category_id);

        $subCategory = new SubCategory();
        $subCategory->category_id = $category->id;
        $subCategory->user_id = $category->user_id;
        $subCategory->title_ar = $request->title_ar;
        $subCategory->title_en = $request->title_en;
        $subCategory->description_ar = $request->description_ar;
        $subCategory->description_en = $request->description_en;
        if ($request->has('image') and $request->image != null) {
            $imageName = $request->image->store('public/category');
            $subCategory->image = $imageName;
        }
        $subCategory->save();

        return redirect()->route('categories.index')->with('success', 'success')->with('id', $subCategory->id)->with('type', 'subcategory');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = SubCategory::find($id);
        return view('admin.categories.subCategories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = SubCategory::find($id);
        return view('admin.categories.subCategories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title_ar' => 'required',
            'title_en' => 'required',
        ]);

        $subCategory = SubCategory::find($id);
        $subCategory->title_ar = $request->title_ar;
        $subCategory->title_en = $request->title_en;
        $subCategory->description_ar = $request->description_ar;
        $subCategory->description_en = $request->description_en;
        if ($request->has('image') and $request->image != null) {
            $imageName = $request->image->store('public/category');
            $subCategory->image = $imageName;
        }
        $subCategory->save();

        return redirect()->route('categories.index')->with('success', 'success')->with('id', $subCategory->id)->with('type', 'subcategory');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SubCategory::destroy($id);
        return back()->with('success', 'success');
    }
    public function activate($id)
    {
        $subCategory = SubCategory::find($id);
        if ($subCategory) {
            $subCategory->status = !$subCategory->status;
            $subCategory->save();
        }
        $state = $subCategory->status == 1 ? 'Activated' : 'De-Activated' ;
        return back()->with('success', 'Sub Category '.$state);
    }
}
