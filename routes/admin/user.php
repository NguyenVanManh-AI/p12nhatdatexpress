<?php


use App\Http\Controllers\Admin\User\BusinessController;
use App\Http\Controllers\Admin\User\SetupController;
use App\Http\Controllers\Admin\User\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('business')->name('admin.business.')->group(function (){
    Route::get('/',[BusinessController::class,'list'])->name('list')->middleware('admin.check:9,4');
    Route::get('/block/{id}',[BusinessController::class,'block'])->name('block')->middleware('admin.check:9,2');
    Route::get('/unblock/{id}',[BusinessController::class,'unblock'])->name('unblock')->middleware('admin.check:9,2');
    Route::get('/forbidden/{id}',[BusinessController::class,'forbidden'])->name('forbidden')->middleware('admin.check:9,2');
    Route::get('/unforbidden/{id}',[BusinessController::class,'unforbidden'])->name('unforbidden')->middleware('admin.check:9,2');
    Route::get('/delete/{id}',[BusinessController::class,'delete'])->name('delete')->middleware('admin.check:9,5');
    Route::get('/restore/{id}',[BusinessController::class,'restore'])->name('restore')->middleware('admin.check:9,6');
    Route::get('/browse/{id}',[BusinessController::class,'browse'])->name('browse')->middleware('admin.check:9,2');
    Route::get('/edit/{id}',[BusinessController::class,'edit'])->name('edit')->middleware('admin.check:9,2');
    Route::post('/edit/{id}',[BusinessController::class,'post_edit'])->middleware('admin.check:9,2');
    Route::get('/truy-cap/{id}',[BusinessController::class,'truy_cap'])->name('truy_cap')->middleware('admin.check:9,2');
    Route::post('/list-action',[BusinessController::class,'list_action'])->name('list_action')->middleware('admin.check:9,2');
});
Route::prefix('account')->name('admin.account.')->group(function (){
    Route::get('/',[UserController::class,'user_list'])->name('list')->middleware('admin.check:9,4');
    Route::get('/block/{id}',[UserController::class,'block'])->name('block')->middleware('admin.check:9,2');
    Route::get('/unblock/{id}',[UserController::class,'unblock'])->name('unblock')->middleware('admin.check:9,2');
    Route::get('/forbidden/{id}',[UserController::class,'forbidden'])->name('forbidden')->middleware('admin.check:9,2');
    Route::get('/unforbidden/{id}',[UserController::class,'unforbidden'])->name('unforbidden')->middleware('admin.check:9,2');
    Route::get('/delete/{id}',[UserController::class,'delete'])->name('delete')->middleware('admin.check:9,5');
    Route::get('/restore/{id}',[UserController::class,'restore'])->name('restore')->middleware('admin.check:9,6');
    Route::get('/edit/{id}',[UserController::class,'edit'])->name('edit')->middleware('admin.check:9,2');
    Route::post('/edit/{id}',[UserController::class,'post_edit'])->middleware('admin.check:9,2');
    Route::post('/list-action',[UserController::class,'list_action'])->name('list_action')->middleware('admin.check:9,2');
    Route::get('/truy-cap/{id}',[UserController::class,'truy_cap'])->name('truy_cap')->middleware('admin.check:9,2');
});
Route::prefix('setup')->name('admin.setup')->middleware('admin.check:99,2')->group(function(){
    Route::get('/user',[SetupController::class,'get_setup'])->name('setup');
    Route::post('/user',[SetupController::class,'update']);
});
