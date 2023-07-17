<?php

use App\Http\Controllers\Admin\AddCoin\AddCoinController;
use App\Http\Controllers\Admin\AddCoin\ExportBillController;
use App\Http\Controllers\Admin\AddCoin\SetupController;
use Illuminate\Support\Facades\Route;

Route::prefix('coin')->group(function () {
    Route::get('/', [AddCoinController::class, 'list'])->name('admin.coin.list')->middleware('admin.check:22,4');
    Route::get('/trash', [AddCoinController::class, 'trash'])->name('admin.coin.trash')->middleware('admin.check:22,8');
    Route::get('/delete/{id}/{created_by?}', [AddCoinController::class,'delete_coin'])->name('admin.coin.delete')->middleware('admin.check:22,5');
    Route::get('/untrash-coin/{id}/{created_by?}', [AddCoinController::class,'untrash_coin'])->name('admin.coin.untrashcoin')->middleware('admin.check:22,6');
    Route::post('/trash-list', [AddCoinController::class, 'trash_list'])->name('admin.coin.trashlist')->middleware('admin.check:22,5');
    Route::post('/untrash-list', [AddCoinController::class, 'untrash_list'])->name('admin.coin.untrashlist')->middleware('admin.check:22,6');
    Route::post('/force-delete-multiple', [AddCoinController::class, 'forceDeleteMultiple'])->name('admin.coin.force-delete-multiple')->middleware('admin.check:22,7');
    Route::get('/change-status/{status}/{id}/{created_by?}', [AddCoinController::class, 'change_status'])->name('admin.coin.changestatus')->middleware('admin.check:22,2');
    Route::get('/setting', [SetupController::class, 'get_attr'])->name('admin.coin.setup')->middleware('admin.check:24,2');
    Route::post('/setting', [SetupController::class, 'post_attr'])->middleware('admin.check:24,2');
//    Route::get('/add',[AddCoinController::class,'add_coin'])->name('admin.coin.add');
//    Route::post('/add',[AddCoinController::class,'post'])->name('admin.coin.add');
    Route::post('/update-payment', [SetupController::class, 'update_payment'])->name('admin.coin.update_payment')->middleware('admin.check:24,2');
});

Route::prefix('bill')->group(function () {
   Route::get('/', [ExportBillController::class, 'bill'])->name('admin.bill.list')->middleware('admin.check:23,4');
   Route::post('/upload/{id}/{created_by?}',[ExportBillController::class,'upload_bill'])->name('admin.bill.upload_bill')->middleware('admin.check:23,2');
});
