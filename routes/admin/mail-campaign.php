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
        //danh sách mẫu mail đã xóa
        Route::get('/trash-list-template', [MailTemplateCampaignController::class, 'trash_list_mail_template'])->name('admin.email-campaign.trash-list-template')->middleware('admin.check:35,8');
        //thay đổi thứ tự mẫu mail
        Route::post('/change-show-order', [MailTemplateCampaignController::class, 'change_show_order'])->name('admin.email-campaign.change-show-order')->middleware('admin.check:35,2');
        //xóa 1 mẫu mail
        Route::get('/delete-mail-template/{id}/{created_by?}', [MailTemplateCampaignController::class, 'delete_mail_template'])->name('admin.email-campaign.delete-mail-template')->middleware('admin.check:35,5');
        //khôi phục 1 mẫu mail
        Route::get('/un-delete-mail-template/{id}/{created_by?}', [MailTemplateCampaignController::class, 'un_delete_mail_template'])->name('admin.email-campaign.un-delete-mail-template')->middleware('admin.check:35,6');
        //xóa nhiều mẫu mail
        Route::post('/delete-mail-template-list', [MailTemplateCampaignController::class, 'delete_mail_template_list'])->name('admin.email-campaign.delete-mail-template-list')->middleware('admin.check:35,5');
        //khôi phục nhiều mẫu mail
        Route::post('/un-delete-mail-template-list', [MailTemplateCampaignController::class, 'un_delete_mail_template_list'])->name('admin.email-campaign.un-delete-mail-template-list')->middleware('admin.check:35,6');
        Route::post('/force-delete-multiple', [MailTemplateCampaignController::class, 'forceDeleteMultiple'])->name('admin.email-campaign.mail-template-force-delete-multiple')->middleware('admin.check:35,7');
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
        //danh sách mail cấu hình đã xóa
        Route::get('/trash-list-mail-config', [MailConfigCampaignController::class, 'trash_list_mail_config'])->name('admin.email-campaign.trash-list-mail-config')->middleware('admin.check:37,8');
        //xóa 1 mail cấu hình
        Route::get('/delete-mail-config/{id}/{created_by?}', [MailConfigCampaignController::class, 'delete_mail_config'])->name('admin.email-campaign.delete-mail-config')->middleware('admin.check:37,5');
        //khôi phục 1 mail cấu hình
        Route::get('/un-delete-mail-config/{id}/{created_by?}', [MailConfigCampaignController::class, 'un_delete_mail_config'])->name('admin.email-campaign.un-delete-mail-config')->middleware('admin.check:37,6');
        //xóa nhiều mail cấu hình
        Route::post('/delete-mail-config-list', [MailConfigCampaignController::class, 'delete_mail_config_list'])->name('admin.email-campaign.delete-mail-config-list')->middleware('admin.check:37,5');
        //khôi phục nhiều mail cấu hình
        Route::post('/un-delete-mail-config-list', [MailConfigCampaignController::class, 'un_delete_mail_config_list'])->name('admin.email-campaign.un-delete-mail-config-list')->middleware('admin.check:37,6');
        Route::post('/force-delete-multiple', [MailConfigCampaignController::class, 'forceDeleteMultiple'])->name('admin.email-campaign.mail-config-force-delete-multiple')->middleware('admin.check:37,7');
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
        //danh sách chiến dịch email đã xóa
        Route::get('/trash', [MailCampaignController::class, 'trash_list_campaign'])->name('admin.email-campaign.trash-list-campaign')->middleware('admin.check:38,8');
        //xóa 1 chiến dịch email
        Route::get('/delete-mail-campaign/{id}/{created_by?}', [MailCampaignController::class, 'delete_mail_campaign'])->name('admin.email-campaign.delete-mail-campaign')->middleware('admin.check:38,5');
        //khôi phục 1 chiến dịch email
        Route::get('/un-delete-mail-campaign/{id}/{created_by?}', [MailCampaignController::class, 'un_delete_mail_campaign'])->name('admin.email-campaign.un-delete-mail-campaign')->middleware('admin.check:38,6');
        //xóa nhiều chiến dịch email
        Route::post('/delete-mail-campaign-list', [MailCampaignController::class, 'delete_mail_campaign_list'])->name('admin.email-campaign.delete-mail-campaign-list')->middleware('admin.check:38,5');
        //khôi phục nhiều chiến dịch email
        Route::post('/un-delete-mail-campaign-list', [MailCampaignController::class, 'un_delete_mail_campaign_list'])->name('admin.email-campaign.un-delete-mail-campaign-list')->middleware('admin.check:38,6');
        Route::post('/force-delete-multiple', [MailCampaignController::class, 'forceDeleteMultiple'])->name('admin.email-campaign.mail-campaign-force-delete-multiple')->middleware('admin.check:38,7');
        //thêm chiến dịch email
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
