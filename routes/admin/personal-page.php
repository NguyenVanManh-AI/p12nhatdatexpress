<?php

use App\Http\Controllers\Admin\PersonalPage\BannerController;
use App\Http\Controllers\Admin\PersonalPage\PersonalPageReportController;
use App\Http\Controllers\Admin\PersonalPage\PersonalSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('personal-page')->group(function () {
    //Danh sách banner
    Route::get('/banner', [BannerController::class, 'list_banner'])->name('admin.personal-page.list-banner')->middleware('admin.check:76,4');
//    Route::get('/approve-banner/{id}/{created_by?}', [BannerController::class, 'approve_banner'])->name('admin.personal-page.approve-banner')->middleware('admin.check:76,2');
//    Route::post('/approve-banner-list', [BannerController::class, 'approve_banner_list'])->name('admin.personal-page.approve-banner-list')->middleware('admin.check:76,2');
    Route::get('/close-banner/{id}', [BannerController::class, 'close_banner'])->name('admin.personal-page.close-banner')->middleware('admin.check:76,2');
    Route::post('/close-banner-list', [BannerController::class, 'close_banner_list'])->name('admin.personal-page.close-banner-list')->middleware('admin.check:76,2');

    Route::get('/un-approve-banner/{id}', [BannerController::class, 'un_approve_banner'])->name('admin.personal-page.un-approve-banner')->middleware('admin.check:76,2');
    Route::post('/un-approve-banner-list', [BannerController::class, 'un_approve_banner_list'])->name('admin.personal-page.un-approve-banner-list')->middleware('admin.check:76,2');

    Route::get('/show-banner/{id}', [BannerController::class, 'show_banner'])->name('admin.personal-page.show-banner')->middleware('admin.check:76,2');
    Route::post('/show-banner-list', [BannerController::class, 'show_banner_list'])->name('admin.personal-page.show-banner-list')->middleware('admin.check:76,2');

    Route::prefix('report')->group(function () {
        //báo cáo tài khoản
        Route::prefix('account')->name('admin.personal-page.account.')->group(function () {
            Route::get('/list-report-account', [PersonalPageReportController::class, 'list_report_account'])->name('list-report-account')->middleware('admin.check:77,4');
            //báo cáo sai
            Route::get('/wrong-report-account/{id}', [PersonalPageReportController::class, 'wrong_report_account'])->name('wrong-report')->middleware('admin.check:77,2');
            Route::post('/wrong-report-account-list', [PersonalPageReportController::class, 'wrong_report_account_list'])->name('wrong-report-list')->middleware('admin.check:77,2');
            //cấm tài khoản
            Route::get('/forbidden-account/{id}', [PersonalPageReportController::class, 'forbidden_account'])->name('forbidden-account')->middleware('admin.check:77,2');
            Route::post('/forbidden-account-list', [PersonalPageReportController::class, 'forbidden_account_list'])->name('forbidden-account-list')->middleware('admin.check:77,2');
            //mở cấm tài khoản
            Route::get('/un-forbidden-account/{id}', [PersonalPageReportController::class, 'forbidden_account'])->name('un-forbidden-account')->middleware('admin.check:77,2');
            Route::post('/un-forbidden-account-list', [PersonalPageReportController::class, 'un_forbidden_account_list'])->name('un-forbidden-account-list')->middleware('admin.check:77,2');
            //chặn tài khoản
            Route::get('/locked-account/{id}/{created_by?}', [PersonalPageReportController::class, 'locked_account'])->name('locked-account')->middleware('admin.check:77,2');
            Route::post('/locked-account-list', [PersonalPageReportController::class, 'locked_account_list'])->name('locked-account-list')->middleware('admin.check:77,2');
            Route::post('/unlocked-account-list', [PersonalPageReportController::class, 'un_locked_account_list'])->name('unlocked-account-list')->middleware('admin.check:77,2');
        });
        Route::prefix('comment')->name('admin.personal-page.comment.')->group(function () {
            // ẩn / hiện bình luận
            Route::get('/hide-show-comment/{id}', [PersonalPageReportController::class, 'hide_show_comment'])->name('hide-show')->middleware('admin.check:79,2');
            //báo cáo bình luận
            Route::get('/report-comment-post', [PersonalPageReportController::class, 'report_comment_post'])->name('report-comment-post')->middleware('admin.check:79,4');
            //báo cáo sai
            Route::get('/wrong-report-comment-post/{id}/', [PersonalPageReportController::class, 'wrong_report_comment_post'])->name('wrong-report')->middleware('admin.check:79,2');
            Route::post('/wrong-report-comment-post-list', [PersonalPageReportController::class, 'wrong_report_comment_post_list'])->name('wrong-report-list')->middleware('admin.check:79,2');
            //xóa 1 bình luận
            Route::get('/delete/{id}', [PersonalPageReportController::class, 'trash_item_user_post'])->name('delete-comment')->middleware('admin.check:79,5');
            //xóa nhiều bình luận
            Route::post('/trash-list', [PersonalPageReportController::class, 'trash_list_user_post'])->name('trashlist-comment')->middleware('admin.check:79,5');
            //chặn tài khoản bình luận
            Route::get('/locked-account/{id}/', [PersonalPageReportController::class, 'locked_account'])->name('locked-account')->middleware('admin.check:79,2');
            Route::post('/locked-account-list', [PersonalPageReportController::class, 'locked_account_post_list'])->name('locked-account-list')->middleware('admin.check:79,2');
            Route::post('/unlocked-account-list', [PersonalPageReportController::class, 'unlocked_account_comment_list'])->name('unlocked-account-list')->middleware('admin.check:78,2');
            //cấm tài khoản bình luận
            Route::get('/forbidden-account/{id}', [PersonalPageReportController::class, 'forbidden_account'])->name('forbidden-account')->middleware('admin.check:79,2');
            Route::post('/forbidden-account-list', [PersonalPageReportController::class, 'forbidden_account_post_list'])->name('forbidden-account-list')->middleware('admin.check:79,2');
            //mở cấm tài khoản
            Route::post('/un-forbidden-account-list', [PersonalPageReportController::class, 'un_forbidden_account_post_list'])->name('un-forbidden-account-list')->middleware('admin.check:79,2');
        });

        Route::prefix('user-post')->name('admin.personal-page.post.')->group(function () {
            //Báo cáo bài viết
            Route::get('/list-report-post', [PersonalPageReportController::class, 'list_report_post'])->name('list-report')->middleware('admin.check:78,4');
            //xóa 1 bài viết
            Route::get('/delete-post/{id}/{created_by?}', [PersonalPageReportController::class, 'delete_post'])->name('delete')->middleware('admin.check:78,7');
            //xóa nhiều bài viết
            Route::post('/delete-post-list', [PersonalPageReportController::class, 'trash_list_post'])->name('delete-project-list')->middleware('admin.check:78,7');
            //Chặn hiển thị
            Route::get('/hide-show-post/{id}/{created_by}', [PersonalPageReportController::class, 'hide_show_post'])->name('hide-show')->middleware('admin.check:78,2');
            Route::post('/block-display-post-list', [PersonalPageReportController::class, 'block_display_project_list'])->name('block-display-project-list')->middleware('admin.check:78,2');
            Route::post('/show-display-post-list', [PersonalPageReportController::class, 'show_display_project_list'])->name('show-display-project-list')->middleware('admin.check:78,2');
            //cấm tài khoản
            Route::get('/forbidden-account/{id}/{created_by?}', [PersonalPageReportController::class, 'forbidden_account'])->name('forbidden-account')->middleware('admin.check:78,2');
            Route::post('/forbidden-account-list', [PersonalPageReportController::class, 'forbidden_user_post_list'])->name('forbidden-account-list')->middleware('admin.check:78,2');
            //mở cấm tài khoản
            Route::post('/un-forbidden-account-list', [PersonalPageReportController::class, 'un_forbidden_user_post_list'])->name('un-forbidden-account-list')->middleware('admin.check:78,2');
            //chặn tài khoản
            Route::get('/locked-account/{id}/{created_by?}', [PersonalPageReportController::class, 'locked_account'])->name('locked-account')->middleware('admin.check:78,2');
            Route::post('/locked-account-list', [PersonalPageReportController::class, 'locked_user_post_list'])->name('locked-account-list')->middleware('admin.check:78,2');
            Route::post('/unlocked-account-list', [PersonalPageReportController::class, 'unlocked_user_post_list'])->name('unlocked-account-list')->middleware('admin.check:78,2');
            //báo cáo sai
            Route::get('/wrong-report-post/{id}/{created_by?}', [PersonalPageReportController::class, 'wrong_report_post'])->name('wrong-report')->middleware('admin.check:78,2');
            Route::post('/wrong-report-post-list', [PersonalPageReportController::class, 'wrong_report_post_list'])->name('wrong-report-list')->middleware('admin.check:78,2');
        });
    });
    Route::prefix('setting')->group(function () {
        Route::get('/', [PersonalSettingController::class, 'personal_setting'])->name('admin.personal-page.personal-setting')->middleware('admin.check:80,4');
        Route::post('/', [PersonalSettingController::class, 'post_personal_setting'])->name('admin.personal-page.post-personal-setting')->middleware('admin.check:80,2'); // cập nhật
    });
});
