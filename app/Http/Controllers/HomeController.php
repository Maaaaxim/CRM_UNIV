<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Desk;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        $teams = Team::all();
        $desks = Desk::all();
        return view('super-admin.create-user', compact('countries', 'teams', 'desks'));
    }

}
