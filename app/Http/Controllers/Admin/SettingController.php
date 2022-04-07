<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Data\District;
use App\Models\Data\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order_fees = Setting::firstorcreate(['key'=>'order_fees'],['key'=>'order_fees','value'=>0]);
        $vat_id = Setting::firstorcreate(['key'=>'vat_id'],['key'=>'vat_id','value'=>1111111]);
        return view('admin.settings.order_fees.edit', compact('order_fees','vat_id'));
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
        $order_fees =  Setting::firstorcreate(['key'=>'order_fees'],['key'=>'order_fees','value'=>0]);
        $order_fees->value = $request->value;
        $order_fees->save();
        $vat_id =  Setting::firstorcreate(['key'=>'vat_id'],['key'=>'vat_id','value'=>1111111]);
        $vat_id->value = $request->vat_id;
        $vat_id->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cityDistricts($id)
    {
        $districts = District::where('city_id', $id)->get();
        $district_id = request('district_id') ?? '';
        $generatedOptions = '';
        foreach ($districts as $district) {
            if ($district_id and $district_id == $district->id)
                $generatedOptions .= '<option value="' . $district->id . '" selected>' . $district->name_en . '</option>';
            else
                $generatedOptions .= '<option value="' . $district->id . '">' . $district->name_en . '</option>';
        }
        return response()->json(['options' => $generatedOptions]);
    }
}
