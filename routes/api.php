<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\UserController;

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

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/admin/login',[AuthController::class,'loginAdmin']);

Route::post('/logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function(){
    

    Route::middleware('role:1')->group(function(){

        // Route::resource('mahasiswa', MahasiswaController::class);

        Route::post('/user', [AuthController::class,'register']);

        Route::delete('/user/{id?}', [UserController::class,'destroy']);

        Route::prefix('user')->group(function(){

            Route::patch('/reset/password/{id}', [UserController::class,'resetpassword']);

            Route::patch('/blokir/{id}', [UserController::class,'blokir']);

            Route::patch('/aktif/{id}',[UserController::class,'aktif']);
        });
    });

    /**Admin */
    Route::middleware('role:2')->group(function(){

        Route::resource('mahasiswa', MahasiswaController::class);

        /**Penelitian */
        Route::get('/penelitian/admin', [PenelitiaController::class, 'indexAdmin']);

        Route::get('/penelitian/admin/search/{keyword}',[PenelitianController::class,'searchAdmin']);

        Route::post('/penelitian', [PenelitianController::class, 'store']);

        Route::patch('/penelitian/{id', [PenelitianController::class, 'update']);
        
        /** progres penelitian */
        /** tambah id penelitian */
        Route::post('/progres/{id}', [ProgresController::class, 'store']);

        Route::patch('/progres/fisik/{id}', [ProgresController::class,'updateProgresFisik']);

        Route::patch('/progres/pengeluaran/{id}',[ProgresController::class,'updateProgresPengeluaran']);

    });


});




// Route::group(['middleware' => ['auth:sanctum'],['role:1']],function(){
//     Route::get('/posts', [PostController::class,'index']);
//     Route::post('/posts', [PostController::class,'store']);
//     Route::get('/posts/{id}', [PostController::class,'show']);
//     Route::put('/posts/{id}', [PostController::class,'update']);
//     Route::delete('/posts/{id}', [PostController::class,'destroy']);

//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::resource('mahasiswa', MahasiswaController::class);


// });