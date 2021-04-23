<?php

use App\Models\Location;
use App\Models\Ship;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    // Get User
    $user = User::where('username', getenv('ST_USERNAME'))->first();
    Auth::login($user);

    // Get Planets
    $planets = Location::where('type' , 'PLANET')->get();


    // Get Ships
    $ships = Ship::all();

    // return view

    return view('welcome')->with(['user' => $user, 'planets' => $planets, 'ships' => $ships]);


});
