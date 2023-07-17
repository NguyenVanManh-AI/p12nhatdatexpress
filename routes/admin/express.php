<?php

use App\Http\Controllers\Admin\Express\ExpressController;
use App\Http\Controllers\Admin\Express\SettingExpressController;
use Illuminate\Support\Facades\Route;

Route::name('admin.express.')->prefix('express')->group(function(){
    Route::get('/', [ExpressController::class, 'list'])->name('list')->middleware('admin.check:83,4');
    Route::get('/add', [ExpressController::class, 'add'])->name('add')->middleware('admin.check:83,1');
    Route::post('/add', [ExpressController::class, 'store'])->name('store')->middleware('admin.check:83,1');
    Route::get('/change-status/{id}/{status}', [ExpressController::class, 'change_status'])->name('change-status')->middleware('admin.check:83,2');
    Route::get('/renewal/{id}', [ExpressController::class, 'renewal'])->name('renewal')->middleware('admin.check:83,2');
    Route::post('/renewal_ajax/{id}', [ExpressController::class, 'renewal_ajax'])->name('renewal_ajax')->middleware('admin.check:83,2');
    Route::get('/edit/{id}', [ExpressController::class, 'edit'])->name('edit')->middleware('admin.check:83,2');
    Route::post('/update/{id}', [ExpressController::class, 'update'])->name('update')->middleware('admin.check:83,2');

    Route::name('setting.')->prefix('setting')->group(function(){
        Route::get('/', [SettingExpressController::class, 'edit'])->name('edit')->middleware('admin.check:84,2');
        Route::post('/', [SettingExpressController::class, 'update'])->name('update')->middleware('admin.check:84,2');
    });
});
