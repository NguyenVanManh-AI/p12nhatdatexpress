<?php

use App\Http\Controllers\Admin\Contact\ContactCampaignController;
use App\Http\Controllers\Admin\Contact\ContactController;
use App\Http\Controllers\Admin\Contact\JobController;
use Illuminate\Support\Facades\Route;


Route::prefix('contact')->group(function () {
    Route::get('/', [ContactController::class, 'list_contact'])->name('admin.contact.list')->middleware('admin.check:100,4');

    Route::prefix('campaign')->group(function () {
        Route::post('/campaign-mail', [ContactCampaignController::class, 'campaign_mail'])->name('admin.contact.campaign')->middleware('admin.check:100,4');
        Route::get('/', [ContactCampaignController::class, 'list'])->name('admin.contact.campaign.list')->middleware('admin.check:101,4');
        Route::get('/trash', [ContactCampaignController::class, 'trash_list_campaign'])->name('admin.contact.campaign.trash')->middleware('admin.check:101,8');
        Route::get('/edit/{id}/{created_at?}', [ContactCampaignController::class, 'edit_mail_campaign'])->name('admin.contact.campaign.edit')->middleware('admin.check:101,2');
        Route::post('/update/{id}/{created_at?}', [ContactCampaignController::class, 'update_mail_campaign'])->name('admin.contact.campaign.update')->middleware('admin.check:101,2');
    });

    Route::prefix('job')->name('admin.contact.job.')->group(function () {
        Route::get('/', [JobController::class, 'list'])->name('list')->middleware('admin.check:101,4');
        Route::get('/trash', [JobController::class, 'trash'])->name('trash')->middleware('admin.check:101,8');

        Route::post('/add', [JobController::class, 'store'])->name('add')->middleware('admin.check:101,1');
        Route::post('/edit/{id}/{created_by}', [JobController::class, 'update'])->name('update')->middleware('admin.check:101,2');

        Route::get('/delete/{id}/{created_by}', [JobController::class, 'soft_delete'])->name('delete')->middleware('admin.check:101,5');
        Route::get('/untrash/{id}/{created_by}', [JobController::class, 'untrash'])->name('restore')->middleware('admin.check:101,6');

        Route::post('/untrash-list', [JobController::class, 'untrash_list'])->name('untrashlist')->middleware('admin.check:101,6');
        Route::post('/trash-list', [JobController::class, 'trash_list'])->name('trashlist')->middleware('admin.check:101,5');
        Route::post('/force-delete-multiple', [JobController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:101,7');

//        Route::get('/force-delete/{id}/{created_by}', [JobController::class, 'delete'])->name('force_delete')->middleware('admin.check:101,7');
    });
});
