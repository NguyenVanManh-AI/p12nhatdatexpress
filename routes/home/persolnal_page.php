<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\PersolnalPageController;

 Route::prefix('trang-ca-nhan')->name('trang-ca-nhan.')->group(function (){
    Route::get('danh-sach-tin/{user_code}.html',[PersolnalPageController::class,'list_classified'])->name('danh-sach-tin');
    Route::get('{user_code}.html',[PersolnalPageController::class,'get_list'])->name('dong-thoi-gian');
    Route::post('dang-bai-viet',[PersolnalPageController::class,'post_posts'])->name('dang-bai-viet');
    # like - unlike bài viết dòng thời gian
    Route::get('like/{id}',[PersolnalPageController::class,'like_post'])->name('like');
    # follow - unfollow
    Route::get('follows/{id}',[PersolnalPageController::class,'follow_user'])->name('follows');
    # update banner
    Route::post('uploads',[PersolnalPageController::class,'upload_banner'])->name('uploads');
    #update banner left -right
    Route::post('uploads-avatar',[PersolnalPageController::class,'upload_avatar'])->name('uploads-avatar');
    # add comment post
    Route::post('add-comment/{id}',[PersolnalPageController::class,'add_comment'])->name('add-comment')->middleware('user.check.auth');
    Route::get('danh-gia/{user_code}.html',[PersolnalPageController::class,'evaluate'])->name('danh-gia');
    # add rating
    Route::post('danh-gia/{user_code}.html',[PersolnalPageController::class,'evaluate_post']);
    # like - unlike comment post
    Route::get('like-comment/{id}',[PersolnalPageController::class,'like_comment'])->name('like-comment');
    # add comment in rating
    Route::post('comment-rating/{user_code}',[PersolnalPageController::class,'post_comment_rating'])->name('comment-rating');
    # like - unlike in rating
    Route::get('like-rating/{id}',[PersolnalPageController::class,'like_rating'])->name('like-rating');
     Route::post('bao-cao-trang-ca-nhan/{id}',[PersolnalPageController::class,'report_persolnal'])->name('report-persolnal');
     Route::post('bao-cao-bai-viet/{post_id}',[PersolnalPageController::class,'report_content'])->name('report-content');
     Route::post('bao-cao-binh-luan/{comment_id}',[PersolnalPageController::class,'report_comment'])->name('report-comment');

 });
