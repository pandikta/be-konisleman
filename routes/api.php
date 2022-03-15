<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PengurusKoniController;
use App\Http\Controllers\TentangKamiController;
use App\Http\Controllers\UserController;
use App\Models\PengurusKoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// protected route
Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // user
    Route::get('user', [UserController::class, 'index']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::put('activate-user/{id}', [UserController::class, 'activateUser']);
    Route::put('change-password', [UserController::class, 'changePassword']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

    // tentang kami
    Route::post('tentang-kami', [TentangKamiController::class, 'store']);
    Route::put('tentang-kami/{id}', [TentangKamiController::class, 'update']);
    Route::delete('tentang-kami/{id}', [TentangKamiController::class, 'destroy']);

    // pengurus koni
    Route::resource('pengurus-koni', PengurusKoniController::class)->except('create', 'edit');
    Route::post('pengurus-koni/{id}', [PengurusKoniController::class, 'update']);

    //landing
    Route::resource('landing', LandingController::class)->except('create', 'edit');

    // pengumuman
    Route::resource('pengumuman', PengumumanController::class)->except('create', 'edit');

    // berita
    Route::resource('berita', BeritaController::class)->except('create', 'edit');
});

// open route
Route::get('tentang-kami', [TentangKamiController::class, 'index']);
Route::get('all-pengumuman', [PengumumanController::class, 'getAllPengumuman']);
Route::get('search-pengumuman', [PengumumanController::class, 'searchPengumuman']);
Route::get('all-berita', [BeritaController::class, 'getAllBerita']);
Route::get('search-berita', [BeritaController::class, 'searchBerita']);
