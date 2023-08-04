<?php

use App\Http\Controllers\Admin\Banner\BannerController;
use App\Http\Controllers\Admin\Banner\BannerGroupController;
use Illuminate\Support\Facades\Route;

Route::name('admin.banner.')->prefix('banner')->group(function(){
    Route::get('/', [BannerController::class, 'list'])->name('list')->middleware('admin.check:81,4');
    Route::get('/trash', [BannerController::class, 'list_trash'])->name('trash')->middleware('admin.check:81,4');
    Route::get('/add', [BannerController::class, 'add'])->name('add')->middleware('admin.check:81,1');
    Route::post('/store', [BannerController::class, 'store'])->name('store')->middleware('admin.check:81,1');
    Route::get('/edit/{banner}/{created_by}', [BannerController::class, 'edit'])->name('edit')->middleware('admin.check:81,2');
    Route::post('/update/{id}/{created_by}', [BannerController::class, 'update'])->name('update')->middleware('admin.check:81,2');

    Route::post('/delete-multiple', [BannerController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:81,5');
    Route::post('/restore-multiple', [BannerController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:81,6');
    Route::post('/force-delete-multiple', [BannerController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:81,7');

    Route::get('/ajax-get-group/{id_banner_group}', [BannerController::class, 'get_group'])->name('get_group')->middleware('admin.check:81,2');

    Route::name('locate.')->prefix('locate')->group(function(){
        Route::get('/', [BannerGroupController::class, 'list'])->name('list')->middleware('admin.check:82,4');
        Route::get('/trash', [BannerGroupController::class, 'list_trash'])->name('trash')->middleware('admin.check:82,4');
        Route::get('/edit/{id}/{created_by}', [BannerGroupController::class, 'edit'])->name('edit')->middleware('admin.check:82,2');
        Route::post('/update/{id}/{created_by}', [BannerGroupController::class, 'update'])->name('update')->middleware('admin.check:82,2');
        Route::post('/action', [BannerGroupController::class, 'action'])->name('action')->middleware('admin.check:82,2');
//        Route::get('/add', [LocationController::class, 'add'])->name('add')->middleware('admin.check:82,1');
//        Route::post('/store', [LocationController::class, 'store'])->name('store')->middleware('admin.check:82,1');
//        Route::get('/delete/{id}/{created_at}', [LocationController::class, 'delete'])->name('delete')->middleware('admin.check:82,5');
//        Route::get('/restore/{id}/{created_at}', [LocationController::class, 'restore'])->name('restore')->middleware('admin.check:82,6');
    });

});
