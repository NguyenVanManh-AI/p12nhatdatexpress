<?php


use App\Http\Controllers\Admin\Event\EventController;
use App\Http\Controllers\Admin\Event\EventReportController;
use App\Http\Controllers\Admin\Event\EventSettingController;
use Illuminate\Support\Facades\Route;

Route::name('admin.event.')->prefix('event')->group(function(){
    Route::get('/', [EventController::class, 'list'])->name('list')->middleware('admin.check:18,4');
    Route::get('/trash', [EventController::class, 'trash'])->name('trash')->middleware('admin.check:18,4');
    Route::get('/edit/{id}/{created_at}', [EventController::class, 'edit'])->name('edit')->middleware('admin.check:18,2');
    Route::post('/update/{id}/{created_at}', [EventController::class, 'update'])->name('update')->middleware('admin.check:18,2');
    Route::get('/delete/{id}/{created_at}', [EventController::class, 'delete'])->name('delete')->middleware('admin.check:18,5');
    Route::get('/restore/{id}/{created_at}', [EventController::class, 'restore'])->name('restore')->middleware('admin.check:18,6');
    Route::post('/force-delete-multiple', [EventController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:18,7');
    Route::get('/change-status/{id}/{status}', [EventController::class, 'change_status'])->name('change-status')->middleware('admin.check:18,2');
//    Route::get('/toggle-highlight/{id}', [EventController::class, 'toggle_highlight'])->name('toogle-highlight')->middleware('admin.check:18,2');
    Route::post('/action', [EventController::class, 'action'])->name('action')->middleware('admin.check:18,2');
    Route::get('/add', [EventController::class, 'add'])->name('add')->middleware('admin.check:18,1');
    Route::post('/add', [EventController::class, 'post'])->middleware('admin.check:18,1');
//    Route::get('/hight-light/{id}/{value}',[EventController::class,'high_light'])->name('high_light')->middleware('admin.check:18,2');
//    Route::get('/hight-light/{id}',[EventController::class,'unhightlight'])->name('unhigh_light')->middleware('admin.check:18,2');



    Route::name('report.')->prefix('report')->group(function(){
        Route::get('/', [EventReportController::class, 'list'])->name('list')->middleware('admin.check:19,4');
        // chặn hiển thị - khôi phục hiển thị
        Route::get('/block-display/{id}',[EventReportController::class,'block_display'])->name('block_display')->middleware('admin.check:19,2');
        Route::get('/unblock-display/{id}',[EventReportController::class,'unblock_display'])->name('unblock_display')->middleware('admin.check:19,2');
        // cấm tài khoản
        Route::get('/forbidden/{id}',[EventReportController::class,'forbidden'])->name('forbidden')->middleware('admin.check:19,2');
        // chặn tài khoản
        Route::get('/locked/{id}',[EventReportController::class,'locked'])->name('locked')->middleware('admin.check:19,2');
        // mở chặn
        Route::get('/un-locked/{id}',[EventReportController::class,'un_locked'])->name('unlocked')->middleware('admin.check:19,2');
        // xóa tài khoản
        Route::get('/delete-user/{id}',[EventReportController::class,'delete_user'])->name('delete_user')->middleware('admin.check:19,2');
        // thao tác hàng loạt
        Route::post('/list-action',[EventReportController::class,'list_action'])->name('list_action')->middleware('admin.check:19,2');
    });

    Route::name('setting.')->prefix('setting')->group(function(){
        Route::get('/', [EventSettingController::class, 'edit'])->name('edit')->middleware('admin.check:20,4');
    });


});
