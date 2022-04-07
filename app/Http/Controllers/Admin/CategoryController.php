<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Category\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = [];
        if (auth()->user()->hasRole('admin')){
            $categories = Category::get();
        }else{
            $categories = Category::where('user_id',auth()->user()->id)->get();
        }
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_ar' => 'required',
            'title_en' => 'required',
        ]);

        $category = new Category();
        $category->user_id = auth()->user()->id;
        $category->title_ar = $request->title_ar;
        $category->title_en = $request->title_en;
        $category->description_ar = $request->description_ar;
        $category->description_en = $request->description_en;
        $category->type = $request->type;
        if($request->has('image') and $request->image != null){
            $imageName = $request->image->store('public/category');
            $category->image = $imageName;
        }
        $category->save();

        return redirect()->route('categories.index')->with('success', 'success')->with('id', $category->id)->with('type', 'category');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title_ar' => 'required',
            'title_en' => 'required',
        ]);

        $category =  Category::find($id);
        $category->title_ar = $request->title_ar;
        $category->title_en = $request->title_en;
        $category->description_ar = $request->description_ar;
        $category->description_en = $request->description_en;
        $category->type = $request->type;
        if($request->has('image') and $request->image != null){
            $imageName = $request->image->store('public/category');
            $category->image = $imageName;
        }
        $category->save();

        return redirect()->route('categories.index')->with('success', 'success')->with('id', $category->id)->with('type', 'category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::destroy($id);
        return back()->with('success', 'success');
    }
    public function activate($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->status = !$category->status;
            $category->save();
        }
        $state = $category->status == 1 ? 'Activated' : 'De-Activated' ;
        return back()->with('success', 'Category '.$state);
    }

    public function subcategories($id)
    {
        $subCategories = SubCategory::where('category_id', $id)->get();
        $sub_category_id = request('sub_category_id') ?? '';
        $generatedOptions = '';
        foreach ($subCategories as $subCategory) {
            if ($sub_category_id and $sub_category_id == $subCategory->id)
                $generatedOptions .= '<option value="' . $subCategory->id . '" selected>' . $subCategory->title_ar . '</option>';
            else
                $generatedOptions .= '<option value="' . $subCategory->id . '">' . $subCategory->title_ar . '</option>';
        }
        return response()->json(['options' => $generatedOptions]);
}

}
