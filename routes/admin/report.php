<?php
// Bình quản lý báo cáo
use App\Http\Controllers\Admin\Report\Account\ReportAccountController;
use App\Http\Controllers\Admin\Report\Account\ReportCommentAccountController;
use App\Http\Controllers\Admin\Report\Account\ReportPostAccountController;
use App\Http\Controllers\Admin\Report\Classified\ReportClassifiedController;
use App\Http\Controllers\Admin\Report\Classified\ReportCommentClassifiedController;
use App\Http\Controllers\Admin\Report\Event\ReportEventController;
use App\Http\Controllers\Admin\Report\Project\ReportProjectController;
use App\Http\Controllers\Admin\Report\Project\ReportCommentProjectController;
use Illuminate\Support\Facades\Route;
Route::prefix('report')->group(function (){
    // báo cáo nội dung tin rao
    Route::prefix('classified')->group(function (){
        // danh sách
        Route::get('/',[ReportClassifiedController::class,'list'])->name('admin.report.classified.list')->middleware('admin.check:89,4');
        Route::get('/report-false/{id}',[ReportClassifiedController::class,'report_false'])->name('admin.report.classified.report-false');
        Route::get('/block/{id}',[ReportClassifiedController::class,'block_display'])->name('admin.report.classified.block');
        Route::get('/unblock/{id}',[ReportClassifiedController::class,'unblock_display'])->name('admin.report.classified.unblock');
        Route::get('/block-account/{id}',[ReportClassifiedController::class,'block_account'])->name('admin.report.classified.block-account');
        Route::get('/unblock-account/{id}',[ReportClassifiedController::class,'unblock_account'])->name('admin.report.classified.unblock-account');
        Route::get('/forbidden/{id}',[ReportClassifiedController::class,'forbidden'])->name('admin.report.classified.forbidden');
        Route::get('/unforbidden/{id}',[ReportClassifiedController::class,'unforbidden'])->name('admin.report.classified.unforbidden');
        Route::get('/delete/{id}',[ReportClassifiedController::class,'delete_account'])->name('admin.report.classified.delete');
        Route::get('/restore/{id}',[ReportClassifiedController::class,'undelete_account'])->name('admin.report.classified.restore');
        Route::post('/list-action',[ReportClassifiedController::class,'list_action'])->name('admin.report.classified.list_action');
        Route::post('/create-noti',[ReportClassifiedController::class,'create_notification'])->name('admin.report.classified.create_noti');
    });
    // báo cáo bình luận tin rao
    Route::prefix('comment-classified')->group(function (){
        Route::get('/',[ReportCommentClassifiedController::class,'list'])->name('admin.report.classified.comment.list')->middleware('admin.check:90,4');;
        Route::get('/report-false/{id}',[ReportCommentClassifiedController::class,'report_false'])->name('admin.report.classified.comment.report-false');
        Route::get('/block/{id}',[ReportCommentClassifiedController::class,'block_display'])->name('admin.report.classified.comment.block');
        Route::get('/unblock/{id}',[ReportCommentClassifiedController::class,'unblock_display'])->name('admin.report.classified.comment.unblock');
        Route::get('/block-account/{id}',[ReportCommentClassifiedController::class,'block_account'])->name('admin.report.classified.comment.block-account');
        Route::get('/unblock-account/{id}',[ReportCommentClassifiedController::class,'unblock_account'])->name('admin.report.classified.comment.unblock-account');
        Route::get('/forbidden/{id}',[ReportCommentClassifiedController::class,'forbidden'])->name('admin.report.classified.comment.forbidden');
        Route::get('/unforbidden/{id}',[ReportCommentClassifiedController::class,'unforbidden'])->name('admin.report.classified.comment.unforbidden');
        Route::get('/delete/{id}',[ReportCommentClassifiedController::class,'delete_account'])->name('admin.report.classified.comment.delete');
        Route::get('/restore/{id}',[ReportCommentClassifiedController::class,'undelete_account'])->name('admin.report.classified.comment.restore');
        Route::post('/list-action',[ReportCommentClassifiedController::class,'list_action'])->name('admin.report.classified.comment.list_action');
        Route::post('/create-noti',[ReportCommentClassifiedController::class,'create_notification'])->name('admin.report.classified.comment.create_noti');

    });
    // báo cáo nội dung dự án
    Route::prefix('project')->group(function (){
        // danh sách
        Route::get('/',[ReportProjectController::class,'list'])->name('admin.report.project.list')->middleware('admin.check:91,4');
        Route::get('/report-false/{id}',[ReportProjectController::class,'report_false'])->name('admin.report.project.report-false');
        Route::get('/block/{id}',[ReportProjectController::class,'block_display'])->name('admin.report.project.block');
        Route::get('/unblock/{id}',[ReportProjectController::class,'unblock_display'])->name('admin.report.project.unblock');
        Route::get('/delete/{id}',[ReportProjectController::class,'delete'])->name('admin.report.project.delete');
        Route::get('/restore/{id}',[ReportProjectController::class,'restore'])->name('admin.report.project.restore');
        Route::post('/list-action',[ReportProjectController::class,'list_action'])->name('admin.report.project.list_action');

    });

    //báo cáo bình luận dự án
    Route::prefix('comment-project')->group(function (){
        // danh sách
        Route::get('/',[ReportCommentProjectController::class,'list'])->name('admin.report.project.comment.list')->middleware('admin.check:92,4');
        Route::get('/report-false/{id}',[ReportCommentProjectController::class,'report_false'])->name('admin.report.project.comment.report-false');
        Route::get('/block/{id}',[ReportCommentProjectController::class,'block_display'])->name('admin.report.project.comment.block');
        Route::get('/unblock/{id}',[ReportCommentProjectController::class,'unblock_display'])->name('admin.report.project.comment.unblock');
        Route::get('/block-account/{id}',[ReportCommentProjectController::class,'block_account'])->name('admin.report.project.comment.block-account');
        Route::get('/unblock-account/{id}',[ReportCommentProjectController::class,'unblock_account'])->name('admin.report.project.comment.unblock-account');
        Route::get('/forbidden/{id}',[ReportCommentProjectController::class,'forbidden'])->name('admin.report.project.comment.forbidden');
        Route::get('/unforbidden/{id}',[ReportCommentProjectController::class,'unforbidden'])->name('admin.report.project.comment.unforbidden');
        Route::get('/delete/{id}',[ReportCommentProjectController::class,'delete_account'])->name('admin.report.project.comment.delete');
        Route::get('/restore/{id}',[ReportCommentProjectController::class,'undelete_account'])->name('admin.report.project.comment.restore');
        Route::post('/list-action',[ReportCommentProjectController::class,'list_action'])->name('admin.report.project.comment.list_action');
        Route::post('/create-noti',[ReportCommentProjectController::class,'create_notification'])->name('admin.report.project.comment.create_noti');

    });
    // báo cáo tài khoản
    Route::prefix('account')->group(function (){
        //báo cáo tài khoản
        Route::prefix('/')->group(function(){
            Route::get('/', [ReportAccountController::class, 'list_report_account'])->name('admin.report.account.list-report-account')->middleware('admin.check:93,4');
            Route::get('/report-false/{id}',[ReportAccountController::class,'report_account_false'])->name('admin.report.account.report-false')->middleware('admin.check:93,2');
            Route::get('/block/{id}',[ReportAccountController::class,'block_account'])->name('admin.report.account.block')->middleware('admin.check:93,2');
            Route::get('/unblock/{id}',[ReportAccountController::class,'unblock_account'])->name('admin.report.account.unblock')->middleware('admin.check:93,2');
            Route::get('/forbidden/{id}',[ReportAccountController::class,'forbidden_account'])->name('admin.report.account.forbidden')->middleware('admin.check:93,2');
            Route::get('/unforbidden/{id}',[ReportAccountController::class,'unforbidden_account'])->name('admin.report.account.unforbidden')->middleware('admin.check:93,2');
            Route::get('/trash/{id}',[ReportAccountController::class,'delete_account'])->name('admin.report.account.trash')->middleware('admin.check:93,2');
            Route::get('/untrash/{id}',[ReportAccountController::class,'undelete_account'])->name('admin.report.account.untrash')->middleware('admin.check:93,2');
            Route::post('/list-action',[ReportAccountController::class,'list_action'])->name('admin.report.account.action_list');
            Route::post('/create-noti',[ReportAccountController::class,'create_notification'])->name('admin.report.account.create_noti');

        });


    });
    Route::prefix('comment-account')->group(function(){
        //báo cáo bình luận
        Route::get('/', [ReportCommentAccountController::class, 'list'])->name('admin.report.account.comment.list')->middleware('admin.check:96,4');
        Route::get('/report-false/{id}',[ReportCommentAccountController::class,'report_false'])->name('admin.report.account.comment.report-false');
        Route::get('/block/{id}',[ReportCommentAccountController::class,'block_display'])->name('admin.report.account.comment.block');
        Route::get('/unblock/{id}',[ReportCommentAccountController::class,'unblock_display'])->name('admin.report.account.comment.unblock');
        Route::get('/block-account/{id}',[ReportCommentAccountController::class,'block_account'])->name('admin.report.account.comment.block-account');
        Route::get('/unblock-account/{id}',[ReportCommentAccountController::class,'unblock_account'])->name('admin.report.account.comment.unblock-account');
        Route::get('/forbidden/{id}',[ReportCommentAccountController::class,'forbidden'])->name('admin.report.account.comment.forbidden');
        Route::get('/unforbidden/{id}',[ReportCommentAccountController::class,'unforbidden'])->name('admin.report.account.comment.unforbidden');
        Route::get('/delete/{id}',[ReportCommentAccountController::class,'delete_account'])->name('admin.report.account.comment.delete');
        Route::get('/restore/{id}',[ReportCommentAccountController::class,'undelete_account'])->name('admin.report.account.comment.restore');
        Route::post('/list-action',[ReportCommentAccountController::class,'list_action'])->name('admin.report.account.comment.list_action');
        Route::post('/create-noti',[ReportCommentAccountController::class,'create_notification'])->name('admin.report.account.comment.create_noti');

    });
    Route::prefix('post-account')->group(function(){
        Route::get('/', [ReportPostAccountController::class, 'list'])->name('admin.report.account.post.list')->middleware('admin.check:94,4');
        Route::get('/report-false/{id}',[ReportPostAccountController::class,'report_false'])->name('admin.report.account.post.report-false');
        Route::get('/block/{id}',[ReportPostAccountController::class,'block_display'])->name('admin.report.account.post.block');
        Route::get('/unblock/{id}',[ReportPostAccountController::class,'unblock_display'])->name('admin.report.account.post.unblock');
        Route::get('/block-account/{id}',[ReportPostAccountController::class,'block_account'])->name('admin.report.account.post.block-account');
        Route::get('/unblock-account/{id}',[ReportPostAccountController::class,'unblock_account'])->name('admin.report.account.post.unblock-account');
        Route::get('/forbidden/{id}',[ReportPostAccountController::class,'forbidden'])->name('admin.report.account.post.forbidden');
        Route::get('/unforbidden/{id}',[ReportPostAccountController::class,'unforbidden'])->name('admin.report.account.post.unforbidden');
        Route::get('/delete/{id}',[ReportPostAccountController::class,'delete_account'])->name('admin.report.account.post.delete');
        Route::get('/restore/{id}',[ReportPostAccountController::class,'undelete_account'])->name('admin.report.account.post.restore');
        Route::post('/list-action',[ReportPostAccountController::class,'list_action'])->name('admin.report.account.post.list_action');
        Route::post('/create-noti',[ReportPostAccountController::class,'create_notification'])->name('admin.report.account.post.create_noti');

    });
    //Báo cáo nội dung sự kiện
    Route::prefix('event')->group(function (){
        Route::get('/', [ReportEventController::class, 'list'])->name('admin.report.event.list')->middleware('admin.check:96,4');
        Route::get('/report-false/{id}',[ReportEventController::class,'report_false'])->name('admin.report.event.report-false');
        Route::get('/block/{id}',[ReportEventController::class,'block_display'])->name('admin.report.event.block');
        Route::get('/unblock/{id}',[ReportEventController::class,'unblock_display'])->name('admin.report.event.unblock');
        Route::get('/block-account/{id}',[ReportEventController::class,'block_account'])->name('admin.report.event.block-account');
        Route::get('/unblock-account/{id}',[ReportEventController::class,'unblock_account'])->name('admin.report.event.unblock-account');
        Route::get('/forbidden/{id}',[ReportEventController::class,'forbidden'])->name('admin.report.event.forbidden');
        Route::get('/unforbidden/{id}',[ReportEventController::class,'unforbidden'])->name('admin.report.event.unforbidden');
        Route::get('/delete/{id}',[ReportEventController::class,'delete_account'])->name('admin.report.event.delete');
        Route::get('/restore/{id}',[ReportEventController::class,'undelete_account'])->name('admin.report.event.restore');
        Route::post('/list-action',[ReportEventController::class,'list_action'])->name('admin.report.event.list_action');
        Route::post('/create-noti',[ReportEventController::class,'create_notification'])->name('admin.report.event.create_noti');

    });
});
