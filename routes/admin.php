<?php

use App\Http\Controllers\Admin\Block\ListBlockController;
use App\Http\Controllers\Admin\CategoryPageConfigController\CategoryPageConfigController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\File\FileController;
use App\Http\Controllers\Admin\FocusNews\FocusNewsCategoryController;
use App\Http\Controllers\Admin\FocusNews\SetupController;
use App\Http\Controllers\Admin\Guide\GuideController;
use App\Http\Controllers\Admin\Home\HomeController;
use App\Http\Controllers\Admin\KeywordUseController;
use App\Http\Controllers\Admin\MailBox\MailBoxController;
use App\Http\Controllers\Admin\ManageAdmin\GroupAdminController;
use App\Http\Controllers\Admin\ManageAdmin\ListAccountController;
use App\Http\Controllers\Admin\NewController;
use App\Http\Controllers\Admin\Package\ListBuyPackageController;
use App\Http\Controllers\Admin\Package\SetUpPackageController;
use App\Http\Controllers\Admin\Promotion\PromotionController;
use App\Http\Controllers\Admin\PromotionNewsController;
use App\Http\Controllers\Admin\Rank\RankController;
use App\Http\Controllers\Admin\Seo\ProvinceController;
use App\Http\Controllers\Admin\Seo\SeoController;
use App\Http\Controllers\Admin\StaticPage\StaticPageController;
use App\Http\Controllers\Admin\SystemConfig\ConfigController;
use App\Http\Controllers\Admin\SystemConfig\MailTemplateController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'admin.auth', 'prefix' => '/admin/nde'], function () {
    Route::get('/login', [App\Http\Controllers\Admin\Auth\AuthController::class, 'showLoginForm'])->name('admin_login');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\AuthController::class, 'login']);

    Route::prefix('forgot-password')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\Auth\AuthController::class, 'forgot_password'])->name('admin.forgot_password');
        Route::post('/', [App\Http\Controllers\Admin\Auth\AuthController::class, 'send_mail_reset']);
    });

    Route::prefix('reset-password')->group(function () {
        Route::get('/{admin}', [App\Http\Controllers\Admin\Auth\AuthController::class, 'reset_password'])->name('admin.reset_password');
        Route::post('/{admin}', [App\Http\Controllers\Admin\Auth\AuthController::class, 'update_password']);
    });
});

Route::get('admin/logout', [App\Http\Controllers\Admin\Auth\AuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->middleware(['admin.session'])->group(function () {
    // dashboard
    Route::group(['prefix' => 'dashboard', 'as' => 'admin.dashboard.'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });

    require 'admin/data-statistics.php';

    Route::prefix('system-config')->group(function () {
        Route::get('/', [ConfigController::class, 'systemConfig'])->name('admin.system.general')->middleware('admin.check:2,4');
        Route::post('/', [ConfigController::class, 'updateSystemConfig'])->middleware('admin.check:2,2');
        Route::post('/test-mail', [ConfigController::class, 'test_mail'])->name('admin.system.mail.testmail');
        Route::post('/add-mail', [ConfigController::class, 'add_mail'])->name('admin.system.mail.add')->middleware('admin.check:2,1');
        Route::get('/delete-mail/{id}/{created_by?}', [ConfigController::class, 'delete_mail'])->name('admin.system.mail.delete')->middleware('admin.check:2,7');
        Route::get('/edit-mail/{id}/{created_by?}', [ConfigController::class, 'edit_mail'])->name('admin.system.mail.edit')->middleware('admin.check:2,2');
        Route::post('/edit-mail/{id}/{created_by?}', [ConfigController::class, 'postedit_mail'])->middleware('admin.check:2,2');
        Route::view('/other', 'Admin.SystemConfig.Other')->name('admin.system.other')->middleware('admin.check:4,4');
    });
    Route::prefix('manage-admin')->group(function () {
        Route::get('/', [GroupAdminController::class, 'list'])->name('admin.manage.group')->middleware('admin.check:6,4'); // list
        Route::get('/accounts', [ListAccountController::class, 'listAccount'])->name('admin.manage.accounts')->middleware('admin.check:7,4');
        Route::get('/editaccount/{id}/{created_by?}', [ListAccountController::class, 'editaccount'])->name('admin.manage.editaccount')->middleware('admin.check:7,2');
        Route::post('/editaccount/{id}/{created_by?}', [ListAccountController::class, 'updateaccount'])->middleware('admin.check:7,2');

        Route::as('admin.manage.accounts.')
            ->group(function () {
                Route::get('/accounts/trash', [ListAccountController::class, 'trash_account'])->name('trash')->middleware('admin.check:7,8');
                Route::post('/delete-multiple', [ListAccountController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:7,5');
                Route::post('/restore-multiple', [ListAccountController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:7,6');
                Route::post('/force-delete-multiple', [ListAccountController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:7,7');
            });

        Route::get('/addaccount', [ListAccountController::class, 'addaccount'])->name('admin.manage.addaccount')->middleware('admin.check:7,1');
        Route::post('/addaccount', [ListAccountController::class, 'postaccount'])->middleware('admin.check:7,1');
        Route::get('/updateavatar/{id}/{created_by?}', [ListAccountController::class, 'update_avatar'])->name('admin.manage.updateavatar')->middleware('admin.check:7,2');
        Route::post('/updateavatar/{id}/{created_by?}', [ListAccountController::class, 'postavatar'])->middleware('admin.check:7,2');

        Route::get('/accounts/info-log/{id}', [ListAccountController::class, 'info_log'])->name('admin.manage.info_log')->middleware('admin.check:7,4');

        Route::get('/accounts/customer-care/{id}', [ListAccountController::class, 'info_customer_care'])->name('admin.manage.accounts.info')->middleware('admin.check:7,4');


        Route::prefix('role')->group(function () {
            Route::get('/trash', [GroupAdminController::class, 'list_trash'])->name('admin.manage.group.trash')->middleware('admin.check:6,8'); // cycle bin
            Route::get('/add', [GroupAdminController::class, 'add'])->name('admin.manage.group.add')->middleware('admin.check:6,1'); // add
            Route::post('/store', [GroupAdminController::class, 'store'])->name('admin.manage.group.store')->middleware('admin.check:6,1'); // add
            Route::get('/edit/{id}/{created_by}', [GroupAdminController::class, 'edit'])->name('admin.manage.group.edit')->middleware('admin.check:6,2'); //update
            Route::post('/update/{id}/{created_by}', [GroupAdminController::class, 'update'])->name('admin.manage.group.update')->middleware('admin.check:6,2'); //update
//            Route::put('/update-order/{created_by?}', [GroupAdminController::class, 'update_show_order'])->name('admin.manage.group.update-show_order')->middleware('admin.check:6,2'); //update
            Route::get('/restore/{id}/{created_by}', [GroupAdminController::class, 'restore'])->name('admin.manage.group.restore')->middleware('admin.check:6,6'); //restore
            Route::get('/delete/{id}/{created_by}', [GroupAdminController::class, 'delete'])->name('admin.manage.group.delete')->middleware('admin.check:6,5'); // trash
            Route::get('/force-delete/{id}/{created_by}', [GroupAdminController::class, 'force_delete'])->name('admin.manage.group.force-delete')->middleware('admin.check:6,7'); // remove
            Route::post('/action', [GroupAdminController::class, 'action'])->name('admin.manage.group.action')->middleware('admin.check:6,2');
        });

    });

    Route::prefix('static')->group(function () {
//        Route::prefix('group')->group(function () {
//            Route::get('/', [StaticPageGroupController::class, 'list'])->name('admin.static.group')->middleware('admin.check:40,4'); // list
//            Route::get('/trash', [StaticPageGroupController::class, 'list_trash'])->name('admin.static.group.trash')->middleware('admin.check:40,8'); // trash
//            Route::get('/add', [StaticPageGroupController::class, 'add'])->name('admin.static.group.add')->middleware('admin.check:40,1'); // add
//            Route::post('/add', [StaticPageGroupController::class, 'store'])->name('admin.static.group.store')->middleware('admin.check:40,1'); // add
//            Route::get('/edit/{id}/{created_by}', [StaticPageGroupController::class, 'edit'])->name('admin.static.group.edit')->middleware('admin.check:40,2'); // update
//            Route::post('/update/{id}/{created_by}', [StaticPageGroupController::class, 'update'])->name('admin.static.group.update')->middleware('admin.check:40,2'); // update
//            Route::get('/restore/{id}/{created_by}', [StaticPageGroupController::class, 'restore'])->name('admin.static.group.restore')->middleware('admin.check:40,6'); // restore
//            Route::get('/duplicate/{id}/{created_by}', [StaticPageGroupController::class, 'duplicate'])->name('admin.static.group.duplicate')->middleware('admin.check:40,3'); // duplicate
//            Route::get('/delete/{id}/{created_by}', [StaticPageGroupController::class, 'delete'])->name('admin.static.group.delete')->middleware('admin.check:40,5'); // move to trash
//            Route::get('/force-delete/{id}/{created_by}', [StaticPageGroupController::class, 'force_delete'])->name('admin.static.group.force-delete')->middleware('admin.check:40,7'); // remove
//            Route::post('/action', [StaticPageGroupController::class, 'action'])->name('admin.static.group.action')->middleware('admin.check:40,2'); // update
//        });
        Route::as('admin.static.page.')
            ->prefix('page')
            ->group(function () {
                Route::get('/', [StaticPageController::class, 'index'])->name('index')->middleware('admin.check:41,4');
                Route::post('/', [StaticPageController::class, 'store'])->name('store')->middleware('admin.check:41,1');
                Route::get('/trash', [StaticPageController::class, 'trash'])->name('trash')->middleware('admin.check:41,8');
                Route::get('/create', [StaticPageController::class, 'create'])->name('add')->middleware('admin.check:41,1');
                Route::get('/{id}/edit', [StaticPageController::class, 'edit'])->name('edit')->middleware('admin.check:41,2');
                Route::post('/duplicate-multiple', [StaticPageController::class, 'duplicateMultiple'])->name('duplicate-multiple')->middleware('admin.check:41,5');
                Route::post('/delete-multiple', [StaticPageController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:41,5');
                Route::post('/restore-multiple', [StaticPageController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:41,6');
                Route::post('/force-delete-multiple', [StaticPageController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:41,7');
                Route::post('/update-order-multiple', [StaticPageController::class, 'updateOrderMultiple'])->name('update-order-multiple')->middleware('admin.check:41,2');
                Route::post('/{id}', [StaticPageController::class, 'update'])->name('update')->middleware('admin.check:41,2');
            });
    });

    Route::prefix('cskh')->group(function () {
        Route::view('/accounts-content', 'Admin.ManageAdmin.ManageContent')->name('admin.manage.accounts-content');
        Route::view('/customer-care', 'Admin.ManageAdmin.CustomerCare')->name('admin.manage.customer-care');
    });

    // focus news.
    Route::group([
        'prefix' => 'focus-news',
        'middleware' => 'admin.check:46,4',
        'as' => 'admin.news.'
    ], function() {
        Route::get('/', [NewController::class, 'index'])->name('index');
        Route::post('/', [NewController::class, 'store'])->name('store')->middleware('admin.check:104,1');
        Route::get('/create', [NewController::class, 'create'])->name('create')->middleware('admin.check:104,1');
        Route::post('/delete-multiple', [NewController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:3,5');
        Route::post('/restore-multiple', [NewController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:3,6');
        Route::post('/force-delete-multiple', [NewController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:3,7');
        Route::get('/{new}', [NewController::class, 'edit'])->name('edit')->middleware('admin.check:104,2');
        Route::post('/{new}', [NewController::class, 'update'])->name('update')->middleware('admin.check:104,2');
        Route::post('/{new}/highlight', [NewController::class, 'highlight'])->name('highlight');
        Route::post('/{new}/express', [NewController::class, 'express'])->name('express');
        Route::post('/{new}/un-express', [NewController::class, 'unExpress'])->name('unExpress');
        Route::post('/{new}/change-show', [NewController::class, 'changeShow'])->name('changeShow');
    });

    Route::prefix('setup')->group(function () {
        Route::get('focus', [SetupController::class, 'get_setup'])->name('admin.setup.focus')->middleware('admin.check:61,4');
        Route::post('focus', [SetupController::class, 'update'])->middleware('admin.check:61,2');

    });


    Route::prefix('focus-news-category')->group(function () {
        Route::get('/', [FocusNewsCategoryController::class, 'index'])->name('admin.focuscategory.list')->middleware('admin.check:47,4');
        Route::get('/new', [FocusNewsCategoryController::class, 'add_new'])->name('admin.focuscategory.new')->middleware('admin.check:47,1');
        Route::post('/new', [FocusNewsCategoryController::class, 'postadd_new'])->middleware('admin.check:47,1');
        Route::get('/edit/{id}/{created_by}', [FocusNewsCategoryController::class, 'edit'])->name('admin.focuscategory.edit')->middleware('admin.check:47,2');
        Route::post('/update/{id}/{created_by}', [FocusNewsCategoryController::class, 'update'])->name('admin.focuscategory.update')->middleware('admin.check:47,2');

        Route::as('admin.focuscategory.')
            ->group(function () {
                Route::get('/trash', [FocusNewsCategoryController::class, 'list_trash'])->name('trash')->middleware('admin.check:47,8');
                Route::post('/delete-multiple', [FocusNewsCategoryController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:47,5');
                Route::post('/restore-multiple', [FocusNewsCategoryController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:47,6');
                Route::post('/force-delete-multiple', [FocusNewsCategoryController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:47,7');
            });
    });

    Route::prefix('guide')->group(function () {
        Route::get('/', [GuideController::class, 'list_guide'])->name('admin.guide.list')->middleware('admin.check:73,4');
        Route::get('/add', [GuideController::class, 'add'])->name('admin.guide.add')->middleware('admin.check:73,1');
        Route::post('/add', [GuideController::class, 'postguide'])->middleware('admin.check:73,1');
        Route::get('/edit/{id}/{created_by}', [GuideController::class, 'edit_guide'])->name('admin.guide.edit')->middleware('admin.check:73,2');
        Route::post('/edit/{id}/{created_by}', [GuideController::class, 'post_guide'])->middleware('admin.check:73,2');

        Route::as('admin.guide.')
            ->group(function () {
                Route::get('/trash', [GuideController::class, 'trash_guide'])->name('trash')->middleware('admin.check:73,8');
                Route::post('/delete-multiple', [GuideController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:73,5');
                Route::post('/restore-multiple', [GuideController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:73,6');
                Route::post('/force-delete-multiple', [GuideController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:73,7');
            });

        Route::get('/View/{id}', [GuideController::class, 'View_guide'])->name('admin.guide.view')->middleware('admin.check:73,4');
    });

    Route::prefix('seo')->group(function () {
        Route::get('seo-home', [SeoController::class, 'seo_home'])->name('admin.seo.home')->middleware('admin.check:85,4');
        Route::post('/edit/{id}', [SeoController::class, 'post_home'])->name('admin.seo.edit')->middleware('admin.check:85,2');
        // chuyên mục tin rao
        Route::get('focus-category', [SeoController::class, 'list_focuscategory'])->name('admin.seo.listfocus')->middleware('admin.check:88,4');
        Route::get('focus-category/edit/{id}', [SeoController::class, 'seo_focus'])->name('admin.seo.editfocus')->middleware('admin.check:88,2');
        Route::post('focus-category/edit/{id}', [SeoController::class, 'post_focus'])->middleware('admin.check:88,2');
        // chuyên mục tiêu điểm
        Route::get('classified-category', [SeoController::class, 'list_classified'])->name('admin.seo.listclassified')->middleware('admin.check:86,4');
        Route::get('classified-category/edit/{id}', [SeoController::class, 'seo_classified'])->name('admin.seo.editclassified')->middleware('admin.check:86,2');
        Route::post('classified-category/edit/{id}', [SeoController::class, 'post_classified'])->middleware('admin.check:86,2');
        // chuyên mục dự án
        Route::get('project-category', [SeoController::class, 'list_project'])->name('admin.seo.listproject')->middleware('admin.check:87,4');
        Route::get('project-category/edit/{id}', [SeoController::class, 'seo_project'])->name('admin.seo.editproject')->middleware('admin.check:87,2');
        Route::post('project-category/edit/{id}', [SeoController::class, 'post_project'])->middleware('admin.check:87,2');

        Route::group(['prefix' => 'provinces', 'as' => 'admin.seo.provinces.', 'middleware' => 'admin.check:105,4'], function () {
            Route::get('/', [ProvinceController::class, 'index'])->name('index')->middleware('admin.check:105,4');
            Route::get('/{id}', [ProvinceController::class, 'edit'])->name('edit')->middleware('admin.check:105,2');
            Route::post('/{id}', [ProvinceController::class, 'update'])->name('update')->middleware('admin.check:105,2');
        });
    });

    Route::prefix('package')->group(function () {
        Route::get('/', [ListBuyPackageController::class, 'list_buy'])->name('admin.package.list')->middleware('admin.check:26,4');
        Route::get('/change-status/{status}/{id}', [ListBuyPackageController::class, 'change_status'])->name('admin.package.changestatus')->middleware('admin.check:26,2');
    });

    Route::prefix('setup')->group(function () {
        Route::get('/', [SetUpPackageController::class, 'set_up'])->name('admin.setup.list')->middleware('admin.check:27,4');
        Route::get('add', [SetUpPackageController::class, 'add'])->name('admin.setup.add')->middleware('admin.check:27,1');
        Route::post('add', [SetUpPackageController::class, 'post_package'])->middleware('admin.check:27,1');
        Route::get('/edit/{id}/{created_by}', [SetUpPackageController::class, 'edit_package'])->name('admin.setup.edit')->middleware('admin.check:27,2');
        Route::post('/edit/{id}/{created_by}', [SetUpPackageController::class, 'postpackage'])->middleware('admin.check:27,2');
        Route::as('admin.setup.')
            ->group(function () {
                Route::get('/trash', [SetUpPackageController::class, 'trash'])->name('trash')->middleware('admin.check:27,8');
                Route::post('/delete-multiple', [SetUpPackageController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:27,5');
                Route::post('/restore-multiple', [SetUpPackageController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:27,6');
                Route::post('/force-delete-multiple', [SetUpPackageController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:27,7');
            });
    });
    Route::prefix('block')->group(function () {
        Route::get('/', [ListBlockController::class, 'list_block'])->name('admin.block.list')->middleware('admin.check:62,4');
        Route::get('/trash-block', [ListBlockController::class, 'trash_block'])->name('admin.block.trash')->middleware('admin.check:62,8');
        Route::post('/add', [ListBlockController::class, 'post_block'])->name('admin.block.add')->middleware('admin.check:62,1');
        Route::post('/edit/{id}/{created_by}', [ListBlockController::class, 'edit_post_block'])->name('admin.block.edit')->middleware('admin.check:62,2');
        Route::get('/change/{id}/{created_by}', [ListBlockController::class, 'change_status'])->name('admin.block.change');
        Route::get('/delete/{id}/{created_by}', [ListBlockController::class, 'delete_block'])->name('admin.block.delete')->middleware('admin.check:62,5');
        Route::get('/untrash-block/{id}/{created_by}', [ListBlockController::class, 'untrash_block'])->name('admin.block.untrashblock')->middleware('admin.check:62,6');
        Route::post('/trash-list', [ListBlockController::class, 'trash_list'])->name('admin.block.trashlist')->middleware('admin.check:62,5');
        Route::post('/untrash-list', [ListBlockController::class, 'untrash_list'])->name('admin.block.untrashlist')->middleware('admin.check:62,6');
        Route::get('/deletealways/{id}/{created_by}', [ListBlockController::class, 'delete'])->name('admin.block.deletealways')->middleware('admin.check:62,7');
    });

    //vinh trang chuyên mục
    Route::prefix('category-page-config')->group(function () {
        Route::get('/', [CategoryPageConfigController::class, 'page_config'])->name('admin.category-page-config.page-config')->middleware('admin.check:64,4'); // hiển thị
        Route::post('/', [CategoryPageConfigController::class, 'edit_page_config'])->name('admin.category-page-config.post-page-config')->middleware('admin.check:64,2'); // cập nhật
    });
    //mã khuyến mãi
    Route::as('admin.')
        ->group(function () {
            Route::group([
                'as' => 'promotion.',
                'prefix' => 'promotion',
                'middleware' => 'admin.check:43,4'
            ], function () {
                Route::get('/', [PromotionController::class, 'index'])->name('index');
                Route::post('/', [PromotionController::class, 'store'])->name('store')->middleware('admin.check:43,1');
                Route::get('/trash', [PromotionController::class, 'trash'])->name('trash')->middleware('admin.check:43,8');
                Route::get('/create', [PromotionController::class, 'create'])->name('create')->middleware('admin.check:43,1');
                Route::post('/delete-multiple', [PromotionController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:43,5');
                Route::post('/restore-multiple', [PromotionController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:43,6');
                Route::post('/force-delete-multiple', [PromotionController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:43,7');
                Route::get('/{id}/edit', [PromotionController::class, 'edit'])->name('edit')->middleware('admin.check:43,2');
                Route::post('/{id}', [PromotionController::class, 'update'])->name('update')->middleware('admin.check:43,2');
            });

            Route::group([
                'as' => 'promotion-news.',
                'prefix' => 'promotion-news',
                'middleware' => 'admin.check:44,4'
            ], function () {
                Route::get('/', [PromotionNewsController::class, 'index'])->name('index');
                Route::post('/', [PromotionNewsController::class, 'store'])->name('store')->middleware('admin.check:44,1');
                Route::get('/trash', [PromotionNewsController::class, 'trash'])->name('trash')->middleware('admin.check:44,8');
                Route::get('/create', [PromotionNewsController::class, 'create'])->name('create')->middleware('admin.check:44,1');
                Route::post('/delete-multiple', [PromotionNewsController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:44,5');
                Route::post('/restore-multiple', [PromotionNewsController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:44,6');
                Route::post('/force-delete-multiple', [PromotionNewsController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:44,7');
                Route::post('/{id}', [PromotionNewsController::class, 'update'])->name('update')->middleware('admin.check:44,2');
                Route::get('/{id}/edit', [PromotionNewsController::class, 'edit'])->name('edit')->middleware('admin.check:44,2');
            });
        });

    //Vinh quản lý cấp bậc
    Route::prefix('rank')->group(function () {
        //Danh sách cấp bậc
        Route::get('/', [RankController::class, 'list'])->name('admin.rank.list')->middleware('admin.check:58,4');
        //danh sách cấp bậc đã xóa
        Route::get('/trash', [RankController::class, 'trash'])->name('admin.rank.trash')->middleware('admin.check:58,8');
        //xóa 1 cấp bậc
        Route::get('/delete/{id}/{created_by?}', [RankController::class, 'trash_item'])->name('admin.rank.delete')->middleware('admin.check:58,5');
        //khôi phục 1 cấp bậc
        Route::get('/undelete/{id}/{created_by?}', [RankController::class, 'untrash_item'])->name('admin.rank.undelete')->middleware('admin.check:58,6');
        //xóa nhiều cấp bậc
        Route::post('/trash-list', [RankController::class, 'trash_list'])->name('admin.rank.trashlist')->middleware('admin.check:58,5');
        //khôi phục nhiều cấp bậc
        Route::post('/untrash-list', [RankController::class, 'untrash_list'])->name('admin.rank.untrashlist')->middleware('admin.check:58,6');
        // Route::post('/force-delete-multiple', [RankController::class, 'forceDeleteMultiple'])->name('admin.rank.force-delete-multiple')->middleware('admin.check:58,7');
        //thêm cấp bậc
        Route::get('/add', [RankController::class, 'add'])->name('admin.rank.add')->middleware('admin.check:58,1'); // thêm cấp bậc
        Route::post('/add', [RankController::class, 'post_add'])->name('admin.rank.post-add')->middleware('admin.check:58,1'); // thêm
        //chỉnh sữa cấp bậc
        Route::get('/edit/{id}/{created_by?}', [RankController::class, 'edit'])->name('admin.rank.edit')->middleware('admin.check:58,2');
        Route::post('/edit/{id}/{created_by?}', [RankController::class, 'post_edit'])->name('admin.rank.post-edit')->middleware('admin.check:58,2');

        Route::post('/set_up_rank', [RankController::class, 'set_up_rank'])->name('admin.rank.set_up_rank');
    });


    // Quan ly binh luan
    require 'admin/comment.php';

    // Quan ly du an
    require 'admin/project.php';

    // Quan ly banner
    require 'admin/banner.php';

    // Quan ly su kien
    require 'admin/event.php';

    // Quan ly quảng cáo express
    require 'admin/express.php';

    // Route notify
    require 'admin/notify.php';

    // Chiến dịch email
    require 'admin/mail-campaign.php';

    //hòm thư
    Route::prefix('/mailbox')->group(function () {
        Route::get('/', [MailBoxController::class, 'list'])->name('admin.mailbox.list')->middleware('admin.check:59,4');
        Route::get('/trash', [MailBoxController::class, 'trash'])->name('admin.mailbox.trash')->middleware('admin.check:59,8'); //all mail
        Route::post('/trash-list', [MailBoxController::class, 'trash_list'])->name('admin.mailbox.trashlist')->middleware('admin.check:59,8');
        Route::post('/untrash-list', [MailBoxController::class, 'untrash_list'])->name('admin.mailbox.untrashlist')->middleware('admin.check:59,6');
        // Route::post('/force-delete-multiple', [MailBoxController::class, 'forceDeleteMultiple'])->name('admin.mailbox.force-delete-multiple')->middleware('admin.check:59,7');

        Route::get('/read-mail', [MailBoxController::class, 'read_mail'])->name('admin.mailbox.read')->middleware('admin.check:59,4'); //mail đã đọc
        Route::get('/unread-mail', [MailBoxController::class, 'unread_mail'])->name('admin.mailbox.unread')->middleware('admin.check:59,4'); // mail chưa đọc
        Route::get('pin-mail', [MailBoxController::class, 'pin_mail'])->name('admin.mailbox.pin')->middleware('admin.check:59,2'); // ghim mail
        Route::get('nofitication-post', [MailBoxController::class, 'nofitication_post'])->name('admin.mailbox.nofitication')->middleware('admin.check:59,4'); // thông báo tin đăng
        Route::get('nofitication-acc', [MailBoxController::class, 'nofitication_acc'])->name('admin.mailbox.nofitication-acc')->middleware('admin.check:59,4');// thông báo tài khoản;
        Route::get('nofitication-system', [MailBoxController::class, 'nofitication_system'])->name('admin.mailbox.nofitication-system')->middleware('admin.check:59,4'); // thông báo hệ thống
        Route::get('/pin/{id}', [MailBoxController::class, 'pin'])->name('admin.mail.pin')->middleware('admin.check:59,2');
        Route::get('/unpin', [MailBoxController::class, 'unpin'])->name('admin.mail.unpin')->middleware('admin.check:59,2');
        Route::get('/status/update', [MailBoxController::class, 'updateStatus'])->name('mail.update.status')->middleware('admin.check:59,2');
        Route::get('/status/unupdate', [MailBoxController::class, 'unupdateStatus'])->name('mail.unupdate.status')->middleware('admin.check:59,2');
        Route::get('/detail/{id}', [MailBoxController::class, 'detail_mail'])->name('admin.mail.detail')->middleware('admin.check:59,4');
        Route::post('/reply/{id}', [MailBoxController::class, 'reply'])->name('admin.mail.reply')->middleware('admin.check:59,2');
        //gửi mail
        Route::get('/add', [MailBoxController::class, 'add'])->name('admin.mail.add')->middleware('admin.check:59,1');
        Route::post('/add/{id?}', [MailBoxController::class, 'post_add'])->name('admin.mail.post-add')->middleware('admin.check:59,1'); // thêm
        Route::get('/add-mail', [MailBoxController::class, 'post_add_mail'])->name('admin.mail.post-add-mail')->middleware('admin.check:59,1');
    });

    Route::get('file', [FileController::class, 'index'])->name('admin.file')->middleware('admin.check:74,9');

    Route::prefix('home')->group(function () {
        Route::get('/', [HomeController::class, 'addhome'])->name('admin.home.saved')->middleware('admin.check:63,4');
        Route::post('/', [HomeController::class, 'posthome'])->middleware('admin.check:63,2');
        Route::get('/delete-image/{type}/{image}/{id}/{created_at}', [HomeController::class, 'delete_image'])->name('admin.home.delete')->middleware('admin.check:63,7');
    });

    // templates
    Route::group([
        'prefix' => '/system-config/mail-template',
        'middleware' => 'admin.check:3,4',
        'as' => 'admin.templates.'
    ], function() {
        Route::get('/', [MailTemplateController::class, 'index'])->name('index');
        Route::get('/trash', [MailTemplateController::class, 'trash'])->name('trash')->middleware('admin.check:3,8');
        Route::get('/create', [MailTemplateController::class, 'create'])->name('create')->middleware('admin.check:3,1');
        Route::post('/', [MailTemplateController::class, 'store'])->name('store')->middleware('admin.check:3,1');
        Route::post('/delete-multiple', [MailTemplateController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:3,5');
        Route::post('/restore-multiple', [MailTemplateController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:3,6');
        Route::post('/force-delete-multiple', [MailTemplateController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:3,7');
        Route::post('/update-order-multiple', [MailTemplateController::class, 'updateOrderMultiple'])->name('update-order-multiple')->middleware('admin.check:3,2');
        Route::get('/{id}', [MailTemplateController::class, 'edit'])->name('edit')->middleware('admin.check:3,2');
        Route::post('/{id}', [MailTemplateController::class, 'update'])->name('update')->middleware('admin.check:3,2');
    });

    // keywords use | từ khóa nổi bật.
    Route::group(['prefix' => 'keyword-use', 'middleware' => 'admin.check:104,4', 'as' => 'admin.keywords.'], function() {
        Route::get('/', [KeywordUseController::class, 'index'])->name('index');
        Route::post('/', [KeywordUseController::class, 'store'])->name('store')->middleware('admin.check:104,1');
        Route::get('/create', [KeywordUseController::class, 'create'])->name('create')->middleware('admin.check:104,1');
        Route::post('/delete-multiple', [KeywordUseController::class, 'deleteMultiple'])->name('delete-multiple')->middleware('admin.check:104,5');
        Route::post('/restore-multiple', [KeywordUseController::class, 'restoreMultiple'])->name('restore-multiple')->middleware('admin.check:104,6');
        Route::post('/force-delete-multiple', [KeywordUseController::class, 'forceDeleteMultiple'])->name('force-delete-multiple')->middleware('admin.check:104,7');
        Route::get('/{keyword}', [KeywordUseController::class, 'edit'])->name('edit')->middleware('admin.check:104,2');
        Route::post('/{keyword}', [KeywordUseController::class, 'update'])->name('update')->middleware('admin.check:104,2');
    });

    // Quản lý báo cáo
    require 'admin/report.php';

    // Quan ly tin rao
    require('admin/classified.php');

    // quản lý nạp express
    require('admin/coin.php');

    // chat realtime
    require('admin/chat.php');

    // thành viên
    require('admin/user.php');

    // liên hệ
    require('admin/contact.php');

    // quản lý trang cá nhân
    require('admin/personal-page.php');

});
