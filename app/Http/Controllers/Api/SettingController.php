<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Validate;
use App\Http\Controllers\Controller;
use App\Models\Advertisement\Advertisement;
use App\Models\Data\AboutUs;
use App\Models\Data\Category;
use App\Models\Data\City;
use App\Models\Data\ContactUs;
use App\Models\Data\District;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function showAboutUs()
    {
        $about_us = AboutUs::get()->first();
        return response()->json(['data' => $about_us], 200);
    }
    public function storeContactUs(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'title' => 'required',
        ];
        $validator = Validate::validateRequest($request, $rules);
        if ($validator !== 'valid') return $validator;
        $contact_us = new ContactUs();
        $contact_us->name = $request->name;
        $contact_us->email = $request->email;
        $contact_us->title = $request->title;
        $contact_us->description = $request->description;
        $contact_us->save();
        return response()->json(['data' => $contact_us], 200);
    }

    public function getCities()
    {
        $cities = City::all();
        return response()->json(['data' => $cities], 200);
    }

    public function getDistrict($city_id)
    {
        $districts = District::where('city_id', $city_id)->get();
        return response()->json(['data' => $districts], 200);
    }

    public function getCategories()
    {
        $categories = Category::all();
        return response()->json(['data' => $categories], 200);
    }
    public function getAdvertisement()
    {
        $advertisement = Advertisement::all();
        return response()->json(['data' => $advertisement], 200);
    }
}
