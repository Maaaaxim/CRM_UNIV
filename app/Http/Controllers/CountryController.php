<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function showCountries()
    {
        $countries = Country::all();
        return view('super-admin.country', compact('countries'));
    }

    public function createCountry(Request $request)
    {
        $request->validate([
            'country' => 'required|unique:countries',
            'code' => 'required|unique:countries',
        ]);

        Country::create([
            'country' => $request->country,
            'code' => $request->code,
        ]);

        return redirect()->back()->with('success', 'Страна успешно создана!');
    }

    public function deleteCountry($countryId)
    {
        $country = Country::find($countryId);

        if ($country) {
            $country->delete();
            return redirect()->back()->with('success', 'Країна видалена!');
        } else {
            return redirect()->back()->with('error', 'Страна не найдена!');
        }
    }
}
