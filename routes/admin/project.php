<?php

use App\Http\Controllers\Admin\Project\CategoryController;
use App\Http\Controllers\Admin\Project\ListRequestController;
use App\Http\Controllers\Admin\Project\ProjectController;
use App\Http\Controllers\Admin\Project\ProjectReportController;
use App\Http\Controllers\Admin\Project\UpdateManageController;
use App\Http\Controllers\Admin\Report\Project\ReportCommentProjectController;
use App\Http\Controllers\Home\Project\CommentProjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('project')->group(function () {

    Route::get('/', [ProjectController::class, 'list'])->name('admin.project.list')->middleware('admin.check:13,4');
    Route::get('/add', [ProjectController::class, 'add'])->name('admin.project.add')->middleware('admin.check:12,1');
    Route::post('/store', [ProjectController::class, 'store'])->name('admin.project.store')->middleware('admin.check:12,1');
    Route::get('/edit/{id}/{created_by}', [ProjectController::class, 'edit'])->name('admin.project.edit')->middleware('admin.check:12,2');
    Route::post('/update/{id}/{created_by}', [ProjectController::class, 'update'])->name('admin.project.update')->middleware('admin.check:12,2');
    Route::post('/preview', [ProjectController::class, 'preview'])->name('admin.project.preview');
    Route::get('/view', [ProjectController::class, 'view'])->name('admin.project.view');

    Route::get('/trash-project', [ProjectController::class, 'trash'])->name('admin.project.trash')->middleware('admin.check:13,8'); // trash
    Route::get('/delete/{id}/{created_by}', [ProjectController::class, 'trash_item'])->name('admin.project.trash_item')->middleware('admin.check:13,7'); // delete
    Route::get('/undelete/{id}', [ProjectController::class, 'untrash_item'])->name('admin.project.restore')->middleware('admin.check:13,6'); // restore
    Route::post('/trash-list', [ProjectController::class, 'trash_list'])->name('admin.project.trashlist')->middleware('admin.check:13,2'); // action list
    Route::post('/untrash-list', [ProjectController::class, 'untrash_list'])->name('admin.project.untrashlist')->middleware('admin.check:13,6');
    Route::post('/force-delete-multiple', [ProjectController::class, 'forceDeleteMultiple'])->name('admin.project.force-delete-multiple')->middleware('admin.check:13,7');

    Route::get('/like/{comment_id}',[CommentProjectController::class,'like_comment'])->name('admin.project.like-comment');

    Route::prefix('request')->group(function () {
        // danh sách
        Route::get('/', [ListRequestController::class, 'list_request'])->name('admin.request.list')->middleware('admin.check:14,4');
        // danh sách đã xóa
        Route::get('/trash-request', [ListRequestController::class, 'trash_request'])->name('admin.request.trashrequest')->middleware('admin.check:14,8');
        // xóa 1 dự án yêu cầu
        Route::get('/delete/{id}/{created_by}', [ListRequestController::class, 'trash_item'])->name('admin.request.delete')->middleware('admin.check:14,5');
        Route::get('/dont-write/{id}/{created_by}', [ListRequestController::class, 'dont_write_item'])->name('admin.request.dont_write')->middleware('admin.check:14,2');
        // khôi phục 1 dự án yêu cầu
        Route::get('/undelete/{id}/{created_by}', [ListRequestController::class, 'untrash_item'])->name('admin.request.untrashrequest')->middleware('admin.check:14,6');
        // xóa nhiều dự án yêu cầu
        Route::post('/trash-list', [ListRequestController::class, 'trash_list'])->name('admin.request.trashlist')->middleware('admin.check:14,5');
        // khôi phục nhiều dự án yêu cầu
        Route::post('/untrash-list', [ListRequestController::class, 'untrash_list'])->name('admin.request.untrashlist')->middleware('admin.check:14,6');
        Route::post('/force-delete-multiple', [ListRequestController::class, 'forceDeleteMultiple'])->name('admin.request.force-delete-multiple')->middleware('admin.check:14,7');
        // viết dự án
        Route::get('write/{id}/{created_by}', [ListRequestController::class, 'add'])->name('admin.request.write')->middleware('admin.check:12,1');
        Route::post('write/{id}/{created_by}', [ListRequestController::class, 'store'])->middleware('admin.check:12,1');
    });

    Route::prefix('category')->group(function () {
        // danh sách
        Route::get('/', [CategoryController::class, 'list'])->name('admin.projectcategory.list')->middleware('admin.check:16,4');
        // thùng rác
        Route::get('/trash', [CategoryController::class, 'trash'])->name('admin.projectcategory.trash')->middleware('admin.check:16,8');
        // xóa 1 mục
        Route::get('/delete/{id}/{created_by}', [CategoryController::class, 'trash_item'])->name('admin.projectcategory.trash_item')->middleware('admin.check:16,5');
        // khôi phục 1 mục
        Route::get('/undelete/{id}/{created_by}', [CategoryController::class, 'untrash_item'])->name('admin.projectcategory.untrash_item')->middleware('admin.check:16,6');
        // xóa nhiều
        Route::post('/trash-list', [CategoryController::class, 'trash_list'])->name('admin.projectcategory.trashlist')->middleware('admin.check:16,5');
        // khôi phục nhiều
        Route::post('/untrash-list', [CategoryController::class, 'untrash_list'])->name('admin.projectcategory.untrashlist')->middleware('admin.check:16,6');
        Route::post('/force-delete-multiple', [CategoryController::class, 'forceDeleteMultiple'])->name('admin.projectcategory.force-delete-multiple')->middleware('admin.check:16,7');
        // thêm
        Route::get('/new', [CategoryController::class, 'new'])->name('admin.projectcategory.new')->middleware('admin.check:16,1');
        Route::post('/new', [CategoryController::class, 'post_new'])->middleware('admin.check:16,1');
        Route::get('/edit/{id}/{created_by}', [CategoryController::class, 'edit'])->name('admin.projectcategory.edit')->middleware('admin.check:16,2');
        Route::post('/edit/{id}/{created_by}', [CategoryController::class, 'post_edit'])->middleware('admin.check:16,2');
    });

    Route::prefix('update-manage')->group(function () {
        //Cập nhật tình trạng
        Route::get('/', [UpdateManageController::class, 'update_manage'])->name('admin.project.update-manage')->middleware('admin.check:15,4');
        //Xác nhận tình trạng
        Route::get('/confirm-update-manage/{id}/{index}', [UpdateManageController::class, 'confirm_update_manage'])->name('admin.project.confirm-update-manage')->middleware('admin.check:15,4');
        //Hoàn tác tình trạng
        Route::get('/recover-update-manage/{id}/{index}', [UpdateManageController::class, 'recover_update_manage'])->name('admin.project.recover-update-manage')->middleware('admin.check:15,4');
        //Cập nhật giá bán
        Route::get('/update-price', [UpdateManageController::class, 'update_price'])->name('admin.project.update-price')->middleware('admin.check:15,4');
        //Xác nhận giá bán
        Route::get('/confirm-update-price/{id}/{index}', [UpdateManageController::class, 'confirm_update_price'])->name('admin.project.confirm-update-price')->middleware('admin.check:15,4');
        //Hoàn tác giá bán
        Route::get('/recover-update-price/{id}/{index}', [UpdateManageController::class, 'recover_update_price'])->name('admin.project.recover-update-price')->middleware('admin.check:15,4');
        //Cập nhật giá thuê
        Route::get('/update-price-rent', [UpdateManageController::class, 'update_price_rent'])->name('admin.project.update-price-rent')->middleware('admin.check:15,4');
        //Xác nhận giá thuê
        Route::get('/confirm-update-price-rent/{id}/{index}', [UpdateManageController::class, 'confirm_update_price_rent'])->name('admin.project.confirm-update-price-rent')->middleware('admin.check:15,4');
        //Hoàn tác giá thuê
        Route::get('/recover-update-price-rent/{id}/{index}', [UpdateManageController::class, 'recover_update_price_rent'])->name('admin.project.recover-update-price-rent')->middleware('admin.check:15,4');
    });

    Route::prefix('report')->middleware('admin.check:48,4')->group(function () {
        //Danh sách báo cáo
        Route::get('/list-report', [ProjectReportController::class, 'list_report'])->name('admin.project.list-report');
        //xóa 1 dự án
        Route::get('/delete-project/{id}/{created_by?}', [ProjectReportController::class, 'delete_project'])->name('admin.project.delete-project');
        //xóa nhiều dự án
        Route::post('/delete-project-list', [ProjectReportController::class, 'delete_project_list'])->name('admin.project.delete-project-list');
        //khôi phục 1 dự án
        Route::get('/restore-project/{id}', [ProjectReportController::class, 'restore_project'])->name('admin.project.restore-project');
        //khôi phục nhiều dự án
        Route::post('/restore-list-project', [ProjectReportController::class, 'restore_list_project'])->name('admin.project.restore-list-project');
        //Chặn hiển thị
        Route::get('/hide-show-project/{id}', [ProjectReportController::class, 'hide_show_project'])->name('admin.project.hide-show-project');
        Route::post('/block-display-project-list', [ProjectReportController::class, 'block_display_project_list'])->name('admin.project.block-display-project-list');
        Route::post('/show-display-project-list', [ProjectReportController::class, 'show_display_project_list'])->name('admin.project.show-display-project-list');
        //báo cáo sai dự án
        Route::get('/wrong-project/{id}/{created_by?}', [ProjectReportController::class, 'wrong_project'])->name('admin.project.wrong-report');
        Route::post('/wrong-project-list', [ProjectReportController::class, 'wrong_project_list'])->name('admin.project.wrong-project-list');

        //Danh sách báo cáo bình luận
        Route::get('/list-report/list-report-comment/{id}', [ProjectReportController::class, 'list_report_comment'])->name('admin.project.list-report-comment');
        Route::name('admin.project.comment.')->prefix('/comment')->group(function (){
            Route::get('/report-false/{id}',[ReportCommentProjectController::class,'report_false'])->name('report-false');
            Route::get('/block/{id}',[ReportCommentProjectController::class,'block_display'])->name('block');
            Route::get('/unblock/{id}',[ReportCommentProjectController::class,'unblock_display'])->name('unblock');
            Route::get('/block-account/{id}',[ReportCommentProjectController::class,'block_account'])->name('block-account');
            Route::get('/unblock-account/{id}',[ReportCommentProjectController::class,'unblock_account'])->name('unblock-account');
            Route::get('/forbidden/{id}',[ReportCommentProjectController::class,'forbidden'])->name('forbidden');
            Route::get('/unforbidden/{id}',[ReportCommentProjectController::class,'unforbidden'])->name('unforbidden');
            Route::get('/delete/{id}',[ReportCommentProjectController::class,'delete_account'])->name('delete');
            Route::get('/restore/{id}',[ReportCommentProjectController::class,'undelete_account'])->name('restore');
            Route::post('/list-action',[ReportCommentProjectController::class,'list_action'])->name('list_action');
            Route::post('/create-noti',[ReportCommentProjectController::class,'create_notification'])->name('create_noti');
        });
    });


    //thuộc tính dự án
    Route::get('/project-properties', [ProjectReportController::class, 'project_properties'])->name('admin.project.project-properties')->middleware('admin.check:14,4');
    Route::post('/project-properties', [ProjectReportController::class, 'post_project_properties'])->name('admin.project.post-project-properties')->middleware('admin.check:14,2'); // cập nhật
    //Thiết lập
    Route::get('/project-setting', [ProjectReportController::class, 'project_setting'])->name('admin.project.project-setting')->middleware('admin.check:13,4');
    Route::post('/project-setting', [ProjectReportController::class, 'post_project_setting'])->name('admin.project.post-project-setting')->middleware('admin.check:13,2'); // cập nhật
});
