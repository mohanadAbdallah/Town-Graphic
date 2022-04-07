<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $products = [];
        if (auth()->user()->hasRole('admin')) {
            $products = Product::paginate(15);
        } else {
            $products = Product::where('user_id', auth()->user()->id)->paginate(15);
        }
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('user_id', auth()->user()->id)->get();
        return view('admin.products.create', compact('categories'));
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
            'sub_category_id' => 'required',
            'size' => 'required',
            'price' => 'required',
            'currency' => 'required'
        ]);

        $product = new Product();
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->user_id = auth()->user()->id;
        $product->title_ar = $request->title_ar;
        $product->title_en = $request->title_en;
        $product->description_ar = $request->description_ar;
        $product->description_en = $request->description_en;
        $product->size = $request->size;
        $product->price = $request->price;
        $product->currency = $request->currency;
        $product->material_type_ar = $request->material_type_ar;
        $product->material_type_en = $request->material_type_en;
        $product->images_count_ar = $request->images_count_ar;
        $product->images_count_en = $request->images_count_en;
        $product->amount = $request->amount;

        if ($request->has('images') and $request->images != null) {
            $imageNames = [];
            foreach ($request->images as $image) {
                $imageName = $image->store('public/products');
                array_push($imageNames, $imageName);
            }
            $product->images = $imageNames;
        }
        $product->save();

        return redirect()->route('products.index')->with('success', 'success')->with('id', $product->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $categories = Category::where('user_id', auth()->user()->id)->get();
        return view('admin.products.edit', compact('product', 'categories'));
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
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'size' => 'required',
            'price' => 'required',
            'currency' => 'required'
        ]);

        $product = Product::find($id);
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->title_ar = $request->title_ar;
        $product->title_en = $request->title_en;
        $product->description_ar = $request->description_ar;
        $product->description_en = $request->description_en;
        $product->size = $request->size;
        $product->price = $request->price;
        $product->currency = $request->currency;
        $product->material_type_ar = $request->material_type_ar;
        $product->material_type_en = $request->material_type_en;
        $product->images_count_ar = $request->images_count_ar;
        $product->images_count_en = $request->images_count_en;
        $product->amount = $request->amount;

        if ($request->has('images') and $request->images != null) {
            $imageNames = $product->images ?? [];
            foreach ($request->images as $image) {
                $imageName = $image->store('public/products');
                array_push($imageNames, $imageName);
            }
            $product->images = $imageNames;
        }
        $product->save();

        return redirect()->route('products.index')->with('success', 'success')->with('id', $product->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return back()->with('success', 'success');
    }

    public function destroyProductImage(Request $request, $product_id)
    {
        $product = Product::find($product_id);
        if ($product->images and count($product->images) > 0) {
            $currentImages = $product->images;
            foreach ($product->images as $key => $image) {
                if ($image == $request->key) {
                    unset($currentImages[$key]);
                    Storage::delete($image);
                }
            }
            $product->images = $currentImages;
            $product->save();

        }
        return true;
    }
}
