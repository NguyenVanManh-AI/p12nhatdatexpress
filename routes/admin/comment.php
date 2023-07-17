<?php

use App\Http\Controllers\Admin\Comment\CommentController;
use App\Http\Controllers\Admin\Comment\ReportCommentController;
use App\Http\Controllers\Admin\Comment\SettingController;
use App\Http\Controllers\Admin\Report\Project\ReportCommentProjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('comment')->group(function () {
    //dự án
    Route::prefix('project')->group(function () {
        //Danh sách bình luận dự án
        Route::get('/project-comment', [CommentController::class, 'list_project'])->name('admin.comment.list-project')->middleware('admin.check:55,4');
        //danh sách bình luận đã xóa
        Route::get('/trash', [CommentController::class, 'trash_project'])->name('admin.comment.trash-comment-project')->middleware('admin.check:55,8');
        //xóa 1 bình luận
        Route::get('/delete/{id}/{created_by?}', [CommentController::class, 'trash_item_project'])->name('admin.comment.delete-comment-project')->middleware('admin.check:55,5');
        //khôi phục 1 bình luận
        Route::get('/undelete/{id}/{created_by?}', [CommentController::class, 'untrash_item_project'])->name('admin.comment.undelete-comment-project')->middleware('admin.check:55,6');
        //xóa nhiều bình luận
        Route::post('/trash-list', [CommentController::class, 'trash_list_project'])->name('admin.comment.trashlist-comment-project')->middleware('admin.check:55,5');
        //khôi phục nhiều bình luận
        Route::post('/untrash-list', [CommentController::class, 'untrash_list_project'])->name('admin.comment.untrashlist-comment-project')->middleware('admin.check:55,6');
        Route::post('/force-delete-multiple', [CommentController::class, 'listProjectForceDeleteMultiple'])->name('admin.comment.list-project-force-delete-multiple')->middleware('admin.check:55,7');
        //chặn tài khoản bình luận
        Route::get('/locked-account/{id}/{created_by?}', [CommentController::class, 'locked_account_project'])->name('admin.comment.locked-account-comment-project')->middleware('admin.check:55,2');
        Route::post('/locked-comment', [ReportCommentController::class, 'locked_account_project_list'])->name('admin.project.locked-account-list')->middleware('admin.check:55,2');
        // Route::post('/un-locked-comment', [ReportCommentController::class, 'un_locked_account_project_list'])->name('admin.project.un-locked-account-list')->middleware('admin.check:64,2');
        //cấm tài khoản bình luận
        Route::get('/forbidden-account/{id}/{created_by?}', [CommentController::class, 'forbidden_account_project'])->name('admin.comment.forbidden-account-comment-project')->middleware('admin.check:55,2');
        Route::post('/forbidden-comment', [ReportCommentController::class, 'forbidden_account_project_list'])->name('admin.project.forbidden-account-list')->middleware('admin.check:55,2');
        //mở cấm tài khoản
        Route::post('/un-forbidden-comment', [ReportCommentController::class, 'un_forbidden_account_project_list'])->middleware('admin.check:55,2');


        //Danh sách báo cáo bình luận dự án
        Route::middleware('admin.check:58,4')->group(function (){
            Route::get('/report-comment-project', [ReportCommentProjectController::class, 'list'])->name('admin.comment.project.comment.list');
            Route::name('admin.comment.project.')->prefix('/report-comment-project')->group(function () {
                Route::get('/report-false/{id}', [ReportCommentProjectController::class, 'report_false'])->name('report-false');
                Route::get('/block/{id}', [ReportCommentProjectController::class, 'block_display'])->name('block');
                Route::get('/unblock/{id}', [ReportCommentProjectController::class, 'unblock_display'])->name('unblock');
                Route::get('/block-account/{id}', [ReportCommentProjectController::class, 'block_account'])->name('block-account');
                Route::get('/unblock-account/{id}', [ReportCommentProjectController::class, 'unblock_account'])->name('unblock-account');
                Route::get('/forbidden/{id}', [ReportCommentProjectController::class, 'forbidden'])->name('forbidden');
                Route::get('/unforbidden/{id}', [ReportCommentProjectController::class, 'unforbidden'])->name('unforbidden');
                Route::get('/delete/{id}', [ReportCommentProjectController::class, 'delete_account'])->name('delete');
                Route::get('/restore/{id}', [ReportCommentProjectController::class, 'undelete_account'])->name('restore');
                Route::post('/list-action', [ReportCommentProjectController::class, 'list_action'])->name('list_action');
                Route::post('/create-noti', [ReportCommentProjectController::class, 'create_notification'])->name('create_noti');
            });
        });

    });


    Route::prefix('classified')->group(function () {
        //Danh sách bình luận tin đăng
        Route::get('/classified-comment', [CommentController::class, 'list_classified'])->name('admin.comment.list-classified')->middleware('admin.check:56,4');
        //danh sách bình luận đã xóa
        Route::get('/trash', [CommentController::class, 'trash_classified'])->name('admin.comment.trash-comment-classified')->middleware('admin.check:56,8');
        //xóa 1 bình luận
        Route::get('/delete/{id}/{created_by?}', [CommentController::class, 'trash_item_classified'])->name('admin.comment.delete-comment-classified')->middleware('admin.check:56,5');
        //khôi phục 1 bình luận
        Route::get('/undelete/{id}/{created_by?}', [CommentController::class, 'untrash_item_classified'])->name('admin.comment.undelete-comment-classified')->middleware('admin.check:56,6');
        //xóa nhiều bình luận
        Route::post('/trash-list', [CommentController::class, 'trash_list_classified'])->name('admin.comment.trashlist-comment-classified')->middleware('admin.check:56,5');
        //khôi phục nhiều bình luận
        Route::post('/untrash-list', [CommentController::class, 'untrash_list_classified'])->name('admin.comment.untrashlist-comment-classified')->middleware('admin.check:56,6');
        Route::post('/force-delete-multiple', [CommentController::class, 'listClassifiedForceDeleteMultiple'])->name('admin.comment.list-classified-force-delete-multiple')->middleware('admin.check:56,7');
        //chặn tài khoản bình luận
        Route::get('/locked-account/{id}/{created_by?}', [CommentController::class, 'locked_account_classified'])->name('admin.comment.locked-account-comment-classified')->middleware('admin.check:56,2');
        //cấm tài khoản bình luận
        Route::get('/forbidden-account/{id}/{created_by?}', [CommentController::class, 'forbidden_account_classified'])->name('admin.comment.forbidden-account-comment-classified')->middleware('admin.check:56,2');
        Route::post('/forbidden-comment', [ReportCommentController::class, 'forbidden_account_classified_list'])->middleware('admin.check:56,2');
        //mở cấm tài khoản
        Route::post('/un-forbidden-comment', [ReportCommentController::class, 'un_forbidden_account_classified_list'])->middleware('admin.check:56,2');

        //Danh sách báo cáo bình luận tin đăng
        Route::get('/report-comment-classified', [ReportCommentController::class, 'report_comment_classified'])->name('admin.comment.report-comment-classified')->middleware('admin.check:59,4');
        //Khóa tài khoản
        Route::get('/lock-unlock-account-classified/{id}/{created_by}', [ReportCommentController::class, 'lock_unlock_account_classified'])->name('admin.classified.lock-unlock-account-classified')->middleware('admin.check:59,2');
        Route::post('/locked-comment', [ReportCommentController::class, 'locked_account_classified_list'])->middleware('admin.check:59,2');
        //xóa 1 bình luận
        Route::get('/delete-comment-classified/{id}/{created_by?}', [ReportCommentController::class, 'delete_comment_classified'])->name('admin.classified.delete-comment-classified')->middleware('admin.check:59,5');
        //xóa nhiều bình luận
        Route::post('/delete-comment-list-classified', [ReportCommentController::class, 'delete_comment_list_classified'])->name('admin.classified.delete-comment-list-classified')->middleware('admin.check:59,5');
        // khôi phục nhiều bình luận
        // Route::post('/delete-comment-list-classified', [ReportCommentController::class, 'delete_comment_list_classified'])->name('admin.classified.delete-comment-list-classified')->middleware('admin.check:59,5');
        //cấm tài khoản
        Route::get('/forbidden-account-classified/{id}/{created_by?}', [ReportCommentController::class, 'forbidden_account_classified'])->name('admin.classified.forbidden-account-classified')->middleware('admin.check:59,2');
        Route::post('/forbidden-account-list', [ReportCommentController::class, 'forbidden_account_classified_list'])->middleware('admin.check:59,2');
        //chặn tài khoản
        Route::get('/locked-account-classified/{id}/{created_by?}', [ReportCommentController::class, 'locked_account_classified'])->name('admin.classified.locked-account-classified')->middleware('admin.check:59,2');
        Route::post('/locked-account-list', [ReportCommentController::class, 'locked_account_classified_list'])->middleware('admin.check:59,2');
        Route::post('/un-locked-account-list', [ReportCommentController::class, 'un_locked_account_classified_list'])->name('admin.classified.un-locked-account-list')->middleware('admin.check:59,2');

        //mở cấm tài khoản
        Route::post('/un-forbidden-account-list', [ReportCommentController::class, 'un_forbidden_account_classified_list'])->middleware('admin.check:59,2');
        //báo cáo sai
        Route::get('/wrong-report-comment-classified/{id}/{created_by?}', [ReportCommentController::class, 'wrong_report_comment_classified'])->name('admin.classified.wrong-report-comment-classified')->middleware('admin.check:59,2');
        Route::post('/wrong-report-comment-classified-list', [ReportCommentController::class, 'wrong_report_comment_classified_list'])->name('admin.project.wrong-report-comment-classified-list')->middleware('admin.check:59,2');
    });

    // user
    Route::prefix('user-post')->group(function () {
        //Danh sách bình luận
        Route::get('/user-post-comment', [CommentController::class, 'list_user_post'])->name('admin.comment.list-user-post')->middleware('admin.check:57,4');
        //danh sách bình luận đã xóa
        Route::get('/trash', [CommentController::class, 'trash_user_post'])->name('admin.comment.trash-comment-user-post')->middleware('admin.check:57,8');
        //xóa 1 bình luận
        Route::get('/delete/{id}/{created_by?}', [CommentController::class, 'trash_item_user_post'])->name('admin.comment.delete-comment-user-post')->middleware('admin.check:57,5');
        //khôi phục 1 bình luận
        Route::get('/undelete/{id}/{created_by?}', [CommentController::class, 'untrash_item_user_post'])->name('admin.comment.undelete-comment-user-post')->middleware('admin.check:57,6');
        //xóa nhiều bình luận
        Route::post('/trash-list', [CommentController::class, 'trash_list_user_post'])->name('admin.comment.trashlist-comment-user-post')->middleware('admin.check:57,5');
        //khôi phục nhiều bình luận
        Route::post('/untrash-list', [CommentController::class, 'untrash_list_user_post'])->name('admin.comment.untrashlist-comment-user-post')->middleware('admin.check:57,6');
        Route::post('/force-delete-multiple', [CommentController::class, 'userPostForceDeleteMultiple'])->name('admin.comment.user-post-force-delete-multiple')->middleware('admin.check:57,7');

        //chặn tài khoản bình luận
        Route::get('/locked-account/{id}/{created_by?}', [CommentController::class, 'locked_account_user_post'])->name('admin.comment.locked-account-comment-user-post')->middleware('admin.check:57,2');
        Route::post('/locked-comment', [ReportCommentController::class, 'locked_account_post_list'])->middleware('admin.check:57,2');
        Route::post('/un-locked-comment', [ReportCommentController::class, 'un_locked_account_post_list'])->middleware('admin.check:57,2');

        //cấm tài khoản bình luận
        Route::get('/forbidden-account/{id}/{created_by?}', [CommentController::class, 'forbidden_account_user_post'])->name('admin.comment.forbidden-account-comment-user-post')->middleware('admin.check:57,2');
        Route::post('/forbidden-comment', [ReportCommentController::class, 'forbidden_account_post_list'])->middleware('admin.check:57,2');
        //mở cấm tài khoản
        Route::post('/un-forbidden-comment', [ReportCommentController::class, 'un_forbidden_account_post_list'])->middleware('admin.check:57,2');

        //Danh sách báo cáo bình luận bài viết
        Route::get('/report-comment-post', [ReportCommentController::class, 'report_comment_post'])->name('admin.comment.report-comment-post')->middleware('admin.check:60,4');
        //Khóa tài khoản
        Route::get('/lock-unlock-account-post/{id}/{created_by}', [ReportCommentController::class, 'lock_unlock_account_post'])->name('admin.post.lock-unlock-account-post')->middleware('admin.check:60,2');
        //xóa 1 bình luận
        Route::get('/delete-comment-post/{id}/{created_by?}', [ReportCommentController::class, 'delete_comment_post'])->name('admin.post.delete-comment-post')->middleware('admin.check:60,5');
        //xóa nhiều bình luận
        Route::post('/delete-comment-list-post', [ReportCommentController::class, 'delete_comment_list_post'])->name('admin.post.delete-comment-list-post')->middleware('admin.check:60,5');
        //cấm tài khoản
        Route::get('/forbidden-account-post/{id}/{created_by?}', [ReportCommentController::class, 'forbidden_account_post'])->name('admin.post.forbidden-account-post')->middleware('admin.check:60,2');
        Route::post('/forbidden-account-list', [ReportCommentController::class, 'forbidden_account_post_list'])->middleware('admin.check:60,2');
        //mở cấm tài khoản
        Route::post('/un-forbidden-account-list', [ReportCommentController::class, 'un_forbidden_account_post_list'])->middleware('admin.check:60,2');
        //chặn tài khoản
        Route::get('/locked-account-post/{id}/{created_by?}', [ReportCommentController::class, 'locked_account_post'])->name('admin.post.locked-account-post')->middleware('admin.check:60,2');
        Route::post('/locked-account-list', [ReportCommentController::class, 'locked_account_post_list'])->middleware('admin.check:60,2');
        Route::post('/un-locked-account-list', [ReportCommentController::class, 'un_locked_account_post_list'])->middleware('admin.check:60,2');

        //báo cáo sai
        Route::get('/wrong-report-comment-post/{id}/{created_by?}', [ReportCommentController::class, 'wrong_report_comment_post'])->name('admin.post.wrong-report-comment-post')->middleware('admin.check:60,2');
        Route::post('/wrong-report-comment-post-list', [ReportCommentController::class, 'wrong_report_comment_post_list'])->name('admin.post.wrong-report-comment-post-list')->middleware('admin.check:60,2');
    });

    Route::prefix('setting')->group(function () {
        Route::get('/', [SettingController::class, 'comment_setting'])->name('admin.comment.comment-setting')->middleware('admin.check:61,4');
        Route::post('/', [SettingController::class, 'post_comment_setting'])->name('admin.comment.post-comment-setting')->middleware('admin.check:61,2'); // cập nhật
    });
});
