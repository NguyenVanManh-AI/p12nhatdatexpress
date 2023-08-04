<?php

use App\Http\Controllers\Classifieds\SearchController as ClassifiedSearchController;
use App\Http\Controllers\Home\Classified\ClassifiedController;
use App\Http\Controllers\Home\Classified\CommentController;
use App\Http\Controllers\Home\ClassifiedController as HomeClassifiedController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\Event\EventController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Param\ParamController;
use App\Http\Controllers\Home\ProjectController;
use App\Http\Controllers\Home\Focus\FocusController;
use App\Http\Controllers\Home\FocusController as HomeFocusController;
use App\Http\Controllers\Home\PhoneBook\ConsultantsController;
use App\Http\Controllers\Home\PhoneBook\EnterpriseController;
use App\Http\Controllers\Home\Project\CommentController as ProjectCommentController;
use App\Http\Controllers\Home\Project\CommentProjectController;
use App\Http\Controllers\Home\StaticPageController;
use App\Http\Controllers\User\Mail\CampaignController;
use App\Http\Controllers\User\UserPostController;
use App\Models\StaticPage;

require "persolnal_page.php";
require 'guest.php';

$page = StaticPage::showed()->get();
    foreach ($page as $item){
        Route::get($item->page_url,[StaticPageController::class,'detail_page']);
    }
Route::group(['prefix' => 'params'], function (){
    Route::get('ajax-get-province-name', [ParamController::class, 'get_province_by_name'])->name('param.get_province_name');
    Route::get('ajax-get-district-name', [ParamController::class, 'get_district_by_name'])->name('param.get_district_name');
    Route::get('ajax-get-ward-name', [ParamController::class, 'get_ward_by_name'])->name('param.get_ward_name');
    Route::get('ajax-get-district', [ParamController::class, 'get_district'])->name('param.get_district');
    Route::get('ajax-get-ward', [ParamController::class, 'get_ward'])->name('param.get_ward');
    Route::get('ajax-get-progress', [ParamController::class, 'get_progress'])->name('param.get_progress');
    Route::get('ajax-get-progress-by-url', [ParamController::class, 'get_progress_by_url'])->name('param.get_progress_by_url');
    Route::get('ajax-get-furniture', [ParamController::class, 'get_furniture'])->name('param.get_furniture');
    Route::get('ajax-get-furniture-by-url', [ParamController::class, 'get_furniture_by_url'])->name('param.get_furniture_by_url');
    Route::get('ajax-get-group-child',[ParamController::class,'get_group_child'])->name('param.get_child');
    Route::get('ajax-get-image-project',[ParamController::class,'get_image_project'])->name('param.get_image_project');
    Route::get('ajax-get-children-group',[ParamController::class,'get_children_group'])->name('param.get_children_group');
    Route::get('ajax-check-valid-date',[ParamController::class,'checkIsvalidDateBanner'])->name('param.check_valid_date');
    Route::get('ajax-get-deposit', [ParamController::class, 'get_deposit'])->name('param.get_deposit_name');
    Route::get('ajax-get-child-group', [ParamController::class, 'get_child_group'])->name('param.get_child_group');
    Route::get('ajax-get_banner-price', [ParamController::class, 'get_banner_price'])->name('param.get_banner_provice');
    Route::get('banner-group-price-data', [ParamController::class, 'getBannerGroupPriceData']);
    Route::post('set-location',[ParamController::class,'set_location'])->name('param.set-location');
    Route::post('set-geolocation',[ParamController::class, 'setGeolocation']);
    Route::get('remove-location',[ParamController::class,'remove_location'])->name('param.remove-location');
    Route::get('ajax-get-project-images',[ParamController::class, 'get_project_images']);
    Route::get('ajax-get-project-active',[ParamController::class, 'get_project_active']);
    Route::get('put-intended-url', [ParamController::class, 'putIntendedUrl']);
});

Route::group(['prefix' => '/'], function () {
    Route::group(['prefix' => 'project', 'as' => 'project.'], function () {
        Route::group(['prefix' => 'comment', 'as' => 'comment.'], function () {
            Route::get('new/{id}', [CommentProjectController::class, 'new_comments'])->name('new');
            Route::post('store', [CommentProjectController::class, 'store'])->name('store');
            Route::post('update', [CommentProjectController::class, 'update'])->name('update');
            Route::get('delete/{id}', [CommentProjectController::class, 'delete'])->name('delete');
        });
    });
});

Route::name('home.')->prefix('/')->middleware('visitor')->group(function() {
    Route::get('/', function () {
        return redirect('trang-chu');
    });
    Route::get('/trang-chu', [HomeController::class, 'index'])->name('index');

    #chi tiet bai viet
    Route::get('{user_code}/bai-viet/{post_code}.html',[UserPostController::class,'get_detail_post'])->name('get_detail_post');

    Route::name('event.')->prefix('/su-kien')->group(function () {
        Route::get('/', [EventController::class, 'list'])->name('list');
        Route::post('/', [EventController::class, 'add'])->name('add');
        Route::post('bao-cao-su-kien/{id}',[EventController::class, 'reportContent'])->name('report-content');

        Route::post('/danh-gia-su-kien/{event_id}',[EventController::class,'rating_event'])->name('rating');

        Route::get('/{event_url}', [EventController::class, 'detail'])->name('detail');
        Route::post('/ajax-list', [EventController::class, 'ajax_list'])->name('ajax_list');
    });

    Route::name('focus.')->prefix('/tieu-diem')->group(function () {
        Route::get('/', [FocusController::class, 'list'])->name('list');
        Route::get('/{group_url}', [FocusController::class, 'list_children'])->name('list-children');
        Route::get('/{group_url}/{focus_url}.html', [FocusController::class, 'detail'])->name('detail');
        Route::post('/ajax-knowledge/{group_id}', [FocusController::class, 'ajax_knowledge'])->name('ajax_knowledge');
    });

    Route::group([
        'prefix' => 'focus'
    ], function () {
        Route::get('more-news', [HomeFocusController::class, 'moreNews']);
        Route::post('/toggle-reaction', [HomeFocusController::class, 'toggleReaction']);
        Route::get('/get-description', [HomeFocusController::class, 'getDescription']);
    });

    Route::get('danh-ba/chuyen-vien-tu-van', [ConsultantsController::class, 'index'])->name('danh-ba.chuyen-vien-tu-van');
    Route::post('danh-ba/chuyen-vien-tu-van', [ConsultantsController::class, 'item_list'])->name('ajax.chuyen-vien-tu-van');
    Route::get('danh-ba/doanh-nghiep', [EnterpriseController::class, 'index'])->name('danh-ba.doanh-nghiep');
    Route::post('danh-ba/doanh-nghiep', [EnterpriseController::class, 'item_list'])->name('ajax.doanh-nghiep');

    Route::name('project.')->prefix('/du-an')->group(function () {
        Route::get('/{slug}.html', [ProjectController::class, 'projectDetail'])->name('project-detail');
        Route::get('/{group_url?}', [ProjectController::class, 'index'])->name('index');
        Route::get('/view-map/{id}', [ProjectController::class, 'viewMap'])->name('view-map');
        Route::post('/vote/{id}', [ProjectController::class, 'Vote'])->middleware('user.check.auth.back')->name('vote');
        Route::post('bao-cao-bai-viet/{project_id}',[ProjectController::class,'report_content'])->name('report-content');
        Route::post('bao-cao-binh-luan/{comment_id}',[ProjectController::class,'report_comment'])->name('report-comment');
        Route::post('comment/delete/{comment_id}',[ProjectController::class,'delete_comment_project'])->name('comment.delete');
        Route::post('comment/update/{comment_id}',[ProjectController::class,'update_comment_project'])->name('comment.update');
        Route::get('like-binh-luan/{comment_id}',[ProjectController::class,'like_comment'])->name('like-comment');

        Route::post('/cap-nhat-du-an/{project_id}', [ProjectController::class, 'update_project'])->name('update-project');
        Route::post('/yeu-cau-du-an', [ProjectController::class, 'request_project'])->name('request-project');

        Route::get('du-an-{province_url}/{group_url?}',[ProjectController::class,'list_project_classified'])->name('location');
    });
    // search classifieds category
    Route::group(['prefix' => 'search-classifieds'], function () {
        Route::get('/form-data', [ClassifiedSearchController::class, 'getFormData']);
        Route::get('/paradigm-data', [ClassifiedSearchController::class, 'getParadigmData']);
    });

    // classifieds
    Route::group(['prefix' => 'classifieds', 'as' => 'classifieds.'], function () {
        Route::get('/preview', [HomeClassifiedController::class, 'preview']);
        Route::get('/more-estate-news', [HomeClassifiedController::class, 'getMoreEstateNews']);
        Route::get('/relates', [HomeClassifiedController::class, 'getRelates']);
        Route::get('/project-form-data', [HomeClassifiedController::class, 'getProjectFormData']);
        Route::get('/parent-group-form-data', [HomeClassifiedController::class, 'getParentGroupFormData']);
        Route::post('/rating/{classified_id}',[ClassifiedController::class, 'rating']);
        Route::group(['prefix' => 'comments', 'as' => 'comments.', 'middleware' => 'user.check.auth'], function () {
            Route::post('/{comment}', [CommentController::class, 'update'])->name('update');
            Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('delete');
            Route::post('/{comment}/like', [CommentController::class, 'like'])->name('like');
        });
        Route::post('/{id}/send-advisory', [ClassifiedController::class, 'sendAdvisory'])->name('send-advisory');;
        Route::post('/{classified}/comments', [CommentController::class, 'store'])
            ->middleware('user.check.auth');
    });

    // projects
    Route::group(['prefix' => 'projects', 'as' => 'projects.'], function () {
        // rating
        Route::post('/rating/{project_id}',[ProjectController::class, 'rating']);
        Route::group(['prefix' => 'comments', 'as' => 'comments.', 'middleware' => 'user.check.auth'], function () {
            Route::post('/{comment}', [ProjectCommentController::class, 'update'])->name('update');
            Route::delete('/{comment}', [ProjectCommentController::class, 'destroy'])->name('delete');
            Route::post('/{comment}/like', [ProjectCommentController::class, 'like'])->name('like');
        });
        Route::post('/{project}/comments', [ProjectCommentController::class, 'store'])
            ->middleware('user.check.auth');
    });

    // events
    Route::group(['prefix' => 'events', 'as' => 'events.'], function () {
        // rating
        Route::post('/rating/{event_id}',[EventController::class, 'rating']);
        Route::post('/{id}/send-advisory', [EventController::class, 'sendAdvisory'])->name('send-advisory');;
    });

    Route::group([
        'prefix' => '/user/campaigns',
    ], function () {
        Route::get('list-customers',[CampaignController::class, 'listCustomers']);
    });

    Route::get('tin-rao/view-map/{id}', [ClassifiedController::class, 'viewMap'])->name('view-map');
    Route::post('/list-classified',[ClassifiedController::class,'get_list'])->name('classified.getlist');
    // Route::post('/danh-gia-tin-rao/{classified_id}',[ClassifiedController::class,'rating_classified'])->name('classified.rating');
    // Route::post('/binh-luan-tin-rao/{classified_id}',[ClassifiedController::class,'comment_classified'])->name('classified.comment');
    Route::post('chi-tiet-tin-rao/delete/{comment_id}',[ClassifiedController::class,'delete_comment_classified'])->name('classified.comment.delete');
    Route::post('chi-tiet-tin-rao/update/{comment_id}',[ClassifiedController::class,'update_comment_classified'])->name('classified.comment.update');
    Route::post('chi-tiet-tin-rao/share/{classified_id}',[ClassifiedController::class,'share_classified'])->name('classified.share');
    Route::post('chi-tiet-tin-rao/customer/{user_id}',[ClassifiedController::class,'customer_classified'])->name('classified.custommer');
    Route::get('like-binh-luan/{comment_id}',[ClassifiedController::class,'like_comment'])->name('classified.like-comment');
    Route::get('bat-dong-san-{province_url}/{group_url?}',[ClassifiedController::class,'list_location_classified'])->name('classified.location');

    Route::get('/vi-tri/nha-dat-{province_url}',[ClassifiedController::class, 'provinceClassified'])->name('location.province-classified');
    // nha dat
    Route::post('bao-cao-binh-luan/{comment_id}',[ClassifiedController::class,'report_comment'])->name('classified.report-comment');
    Route::post('bao-cao-bai-viet/{classified_id}',[ClassifiedController::class,'report_content'])->name('classified.report-content');
    Route::get('/more-item/{group_url}/{group_child?}', [ClassifiedController::class, 'getMoreItems']);
    Route::get('/search-classified/{group_url}/{group_child?}',[ClassifiedController::class, 'searchClassified']);
    Route::get('/category-search-keyword/{group_url}/{group_child?}',[ClassifiedController::class, 'categorySearchKeyword']);
    Route::get('/search-project/{group_url?}',[ProjectController::class, 'searchProject']);
    Route::get('/more-project-item/{group_url}/{group_child?}', [ProjectController::class, 'getMoreItems']); // should change
    Route::get('/{group_url}/{classified_url}.html',[ClassifiedController::class,'detail'])->name('classified.detail');
    Route::get('/{group_url?}/{group_child?}',[ClassifiedController::class,'classified_list'])->name('classified.list');
});
