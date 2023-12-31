<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Classified\ClassifiedController;
use App\Http\Controllers\Admin\Classified\ClassifiedGroupController;
use App\Http\Controllers\Admin\Classified\PropertiesController;
use App\Http\Controllers\Admin\Classified\ClassifiedReportController;
use App\Http\Controllers\Admin\Classified\SetupController;

Route::prefix('classified')
    ->as('admin.classified.')
    ->group(function (){
        Route::get('/',[ClassifiedController::class,'list'])->name('list')->middleware('admin.check:32,4');
        Route::get('/list-trash',[ClassifiedController::class,'list_trash'])->name('trash')->middleware('admin.check:32,8');
    //    Route::get('/refresh/{id}/{created_by}',[ClassifiedController::class,'refresh'])->name('refresh')->middleware('admin.check:32,2');
    //    Route::get('/upgrade-vip/{id}/{created_by}',[ClassifiedController::class,'upgrade_vip'])->name('vip')->middleware('admin.check:32,2');
        Route::get('/trash-item/{id}/{created_by}',[ClassifiedController::class,'trash_item'])->name('trash_item')->middleware('admin.check:32,5');
        Route::get('/untrash-item/{id}/{created_by}',[ClassifiedController::class,'untrash_item'])->name('untrash_item')->middleware('admin.check:32,6');
        Route::post('/delete-multiple', [ClassifiedController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:32,5');
        Route::post('/restore-multiple', [ClassifiedController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:32,6');
        Route::post('/force-delete-multiple', [ClassifiedController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:32,7');
        //    Route::get('/upgrade-highlight/{id}/{created_by}',[ClassifiedController::class,'upgrade_highlight'])->name('highlight')->middleware('admin.check:32,2');
        Route::get('/change-status/{status}/{id}/{created_by}',[ClassifiedController::class,'change_status'])->name('change_status')->middleware('admin.check:32,2');
        Route::get('/edit/{id}',[ClassifiedController::class,'edit_classified'])->name('edit')->middleware('admin.check:32,2');
        Route::post('/edit/{id}',[ClassifiedController::class,'post_edit_classified'])->middleware('admin.check:32,2');
        Route::post('preview-nhadatban',[ClassifiedController::class,'preview_nhadatban'])->name('previewnhadatban');
        Route::get('/toggle-classified/{id}/{created_by}',[ClassifiedController::class,'toggle_classified'])->name('toggle_classified')->middleware('admin.check:32,2');
    });

// group tin rao
Route::prefix('classified-group')
    ->as('admin.groupclassified.')
    ->group(function(){
        Route::get('/',[ClassifiedGroupController::class,'list'])->name('list')->middleware('admin.check:16,4');
        Route::get('/list-trash',[ClassifiedGroupController::class,'list_trash'])->name('listtrash')->middleware('admin.check:16,8');

        //thêm
        Route::get('/new',[ClassifiedGroupController::class,'add'])->name('new')->middleware('admin.check:16,1');
        Route::post('/new',[ClassifiedGroupController::class,'post_add'])->middleware('admin.check:16,1');
        // Cập nhật
        Route::get('/edit/{id}/{created_by}',[ClassifiedGroupController::class,'edit'])->name('edit')->middleware('admin.check:16,2');
        Route::post('/edit/{id}/{created_by}',[ClassifiedGroupController::class,'post_edit'])->middleware('admin.check:16,2');

        // Trash item
        Route::post('/delete-multiple', [ClassifiedGroupController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:16,5');
        Route::post('/restore-multiple', [ClassifiedGroupController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:16,6');
        Route::post('/force-delete-multiple', [ClassifiedGroupController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:16,7');
    });

// thuộc tính tin rao
Route::prefix('classified-properties')->group(function (){
    Route::get('/',[PropertiesController::class,'list'])->name('admin.propertiesclassified.list')->middleware('admin.check:51,4');
    Route::post('/',[PropertiesController::class,'post_list'])->middleware('admin.check:51,4');
});
// Thiết lập tin rao
Route::prefix('classified-setup')->group(function (){
    Route::get('/',[SetupController::class,'get_setup'])->name('admin.classified.setup')->middleware('admin.check:52,4');
    Route::post('/',[SetupController::class,'update'])->middleware('admin.check:52,4');
});
//Báo cáo tin rao
Route::prefix('classified-report')->group(function (){
    // danh sách
    Route::get('/',[ClassifiedReportController::class,'list'])->name('admin.reportclassified.list')->middleware('admin.check:53,4');
    Route::get('/report-false/{id}',[ClassifiedReportController::class,'report_false'])->name('admin.reportclassified.report-false');
    Route::get('/block/{id}',[ClassifiedReportController::class,'block_display'])->name('admin.reportclassified.block');
    Route::get('/unblock/{id}',[ClassifiedReportController::class,'unblock_display'])->name('admin.reportclassified.unblock');
    Route::get('/block-account/{id}',[ClassifiedReportController::class,'block_account'])->name('admin.reportclassified.block-account');
    Route::get('/unblock-account/{id}',[ClassifiedReportController::class,'unblock_account'])->name('admin.reportclassified.unblock-account');
    Route::get('/forbidden/{id}',[ClassifiedReportController::class,'forbidden'])->name('admin.reportclassified.forbidden');
    Route::get('/unforbidden/{id}',[ClassifiedReportController::class,'unforbidden'])->name('admin.reportclassified.unforbidden');
    Route::get('/delete/{id}',[ClassifiedReportController::class,'delete_account'])->name('admin.reportclassified.delete');
    Route::get('/restore/{id}',[ClassifiedReportController::class,'undelete_account'])->name('admin.reportclassified.restore');
    Route::post('/list-action',[ClassifiedReportController::class,'list_action'])->name('admin.reportclassified.list_action');
    Route::post('/create-noti',[ClassifiedReportController::class,'create_notification'])->name('admin.reportclassified.create_noti');
});
