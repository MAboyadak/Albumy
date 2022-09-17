<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PictureController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware('auth')->group(function(){
    Route::get('/', [AlbumController::class,'index'])->name('albums');
    Route::get('/albums', function(){
        return redirect('/');
    });
    Route::POST('/album/store', [AlbumController::class,'store'])->name('album.store');
    Route::POST('/album/update/', [AlbumController::class,'update'])->name('album.update');
    Route::POST('/album/delete/{id}', [AlbumController::class,'delete'])->name('album.delete');
    Route::POST('/album/move}', [AlbumController::class,'move'])->name('album.move');
    Route::get('/album/check/{id}', [AlbumController::class,'checkEmpty'])->name('album.check');
    Route::get('/album/{id}', [AlbumController::class,'show'])->name('album.show');


    Route::post('/picture', [PictureController::class,'store'])->name('picture.store');

});


require __DIR__.'/auth.php';
