<?php

use App\Http\Controllers\Admin\MailCampaign\ApiMailCampaignController;
use App\Http\Controllers\Admin\MailCampaign\MailTemplateCampaignController;
use App\Http\Controllers\Admin\MailCampaign\MailConfigCampaignController;
use App\Http\Controllers\Admin\MailCampaign\MailCampaignController;
use App\Http\Controllers\Admin\MailCampaign\MailCampaginSetup;
use Illuminate\Support\Facades\Route;

Route::prefix('mail-campaign')->group(function () {
    Route::prefix('template')->group(function () {
        //danh sách mẫu mail
        Route::get('/', [MailTemplateCampaignController::class, 'list_mail_template'])->name('admin.email-campaign.list-template')->middleware('admin.check:35,4');
        Route::post('/change-show-order', [MailTemplateCampaignController::class, 'change_show_order'])->name('admin.email-campaign.change-show-order')->middleware('admin.check:35,2');

        Route::as('admin.mail-campaign.templates.')
            ->group(function () {
                Route::get('/trash', [MailTemplateCampaignController::class, 'trash'])->name('trash')->middleware('admin.check:35,8');
                Route::post('/delete-multiple', [MailTemplateCampaignController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:35,5');
                Route::post('/restore-multiple', [MailTemplateCampaignController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:35,6');
                Route::post('/force-delete-multiple', [MailTemplateCampaignController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:35,7');
            });

        //thêm mẫu mail
        Route::get('/add-mail-template', [MailTemplateCampaignController::class, 'add_mail_template'])->name('admin.email-campaign.add-mail-template')->middleware('admin.check:35,1'); // thêm cấp bậc
        Route::post('/add-mail-template', [MailTemplateCampaignController::class, 'post_add_mail_template'])->name('admin.email-campaign.post-add-mail-template')->middleware('admin.check:35,1'); // thêm
        //chỉnh sửa mẫu mail
        Route::get('/edit-mail-template/{id}/{created_by?}', [MailTemplateCampaignController::class, 'edit_mail_template'])->name('admin.email-campaign.edit-mail-template')->middleware('admin.check:35,2');
        Route::post('/edit-mail-template/{id}/{created_by?}', [MailTemplateCampaignController::class, 'post_edit_mail_template'])->name('admin.email-campaign.post-edit-mail-template')->middleware('admin.check:35,2');
    });
    Route::prefix('config')->group(function () {
        //thử mail
        Route::post('/test-mail-config', [MailConfigCampaignController::class, 'test_mail_config'])->name('admin.email-campaign.test-mail-config')->middleware('admin.check:37,1');
        //danh sách mail cấu hình
        Route::get('/list-mail-config', [MailConfigCampaignController::class, 'list_mail_config'])->name('admin.email-campaign.list-mail-config')->middleware('admin.check:37,4');
        Route::as('admin.mail-campaign.configs.')
            ->group(function () {
                Route::get('/trash', [MailConfigCampaignController::class, 'trash'])->name('trash')->middleware('admin.check:37,8');
                Route::post('/delete-multiple', [MailConfigCampaignController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:37,5');
                Route::post('/restore-multiple', [MailConfigCampaignController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:37,6');
                Route::post('/force-delete-multiple', [MailConfigCampaignController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:37,7');
            });

        //thêm mail cấu hình
        Route::get('/add-mail-config', [MailConfigCampaignController::class, 'add_mail_config'])->name('admin.email-campaign.add-mail-config')->middleware('admin.check:37,1');
        Route::post('/add-mail-config', [MailConfigCampaignController::class, 'post_add_mail_config'])->name('admin.email-campaign.post-add-mail-config')->middleware('admin.check:37,1'); // thêm
        //chỉnh sửa mail cấu hình
        Route::get('/edit-mail-config/{id}/{created_by?}', [MailConfigCampaignController::class, 'edit_mail_config'])->name('admin.email-campaign.edit-mail-config')->middleware('admin.check:37,2');
        Route::post('/edit-mail-config/{id}/{created_by?}', [MailConfigCampaignController::class, 'post_edit_mail_config'])->name('admin.email-campaign.post-edit-mail-config')->middleware('admin.check:37,2');
    });
    Route::prefix('campaign')->group(function () {
        //danh sách chiến dịch email
        Route::get('/', [MailCampaignController::class, 'list_campaign'])->name('admin.email-campaign.list-campaign')->middleware('admin.check:38,4');

        Route::as('admin.mail-campaign.campaigns.')
            ->group(function () {
                Route::get('/trash', [MailCampaignController::class, 'trash'])->name('trash')->middleware('admin.check:38,8');
                Route::post('/delete-multiple', [MailCampaignController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:38,5');
                Route::post('/restore-multiple', [MailCampaignController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:38,6');
                Route::post('/force-delete-multiple', [MailCampaignController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:38,7');
            });

        Route::get('/add-mail-campaign', [MailCampaignController::class, 'add_mail_campaign'])->name('admin.email-campaign.add-mail-campaign')->middleware('admin.check:37,1');
        Route::post('/add-mail-campaign', [MailCampaignController::class, 'post_add_mail_campaign'])->name('admin.email-campaign.post-add-mail-campaign')->middleware('admin.check:37,1');
        //gửi cho danh sách nhập vào
        Route::post('/send-list-user', [MailCampaignController::class, 'send_list_user'])->name('admin.email-campaign.send-list-user')->middleware('admin.check:38,6');
        //gửi cho danh sách lọc
        Route::post('/send-filter-user', [MailCampaignController::class, 'send_filter_user'])->name('admin.email-campaign.send-filter-user')->middleware('admin.check:38,6');
        //xem chi tiết chiến dịch
        Route::get('/list-send-mail/{id}', [MailCampaignController::class, 'list_send_mail'])->name('admin.mail-campaign.list-send-mail')->middleware('admin.check:38,4');
        //chỉnh sửa chiến dịch email
        Route::get('/edit-mail-campaign/{id}/{created_by?}', [MailCampaignController::class, 'edit_mail_campaign'])->name('admin.email-campaign.edit-mail-campaign')->middleware('admin.check:37,1');
        //sửa chiến dịch danh sách tự nhập
        Route::post('/edit-send-list-user/{id}/{created_by?}', [MailCampaignController::class, 'edit_send_list_user'])->name('admin.email-campaign.edit-send-list-user')->middleware('admin.check:38,6');
        //sửa chiến dịch danh sách lọc
        Route::post('/edit-send-filter-user/{id}/{created_by?}', [MailCampaignController::class, 'edit_send_filter_user'])->name('admin.email-campaign.edit-send-filter-user')->middleware('admin.check:38,6');
    });
    Route::prefix('setting')->group(function (){
        //thiết lập
        Route::get('/', [MailCampaginSetup::class, 'get_attr'])->name('admin.email-campaign.set-up')->middleware('admin.check:34,2');
        Route::post('/', [MailCampaginSetup::class, 'post_attr'])->middleware('admin.check:34,2');
    });

    Route::prefix('api')->name('admin.email.api')->middleware('admin.check:35,2')->group(function (){
        Route::get('/', [ApiMailCampaignController::class, 'get_users'])->name('get_users');
    });
});
