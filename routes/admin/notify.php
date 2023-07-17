<?php

use App\Http\Controllers\Admin\Banner\BannerController;
use App\Http\Controllers\Admin\Banner\BannerGroupController;
use App\Http\Controllers\Admin\Notify\NotifyController;
use Illuminate\Support\Facades\Route;

Route::name('admin.notify.')->prefix('notify')->group(function(){
    Route::post('/make-notify', [NotifyController::class, 'make_notify'])->name('make_notify');
});
