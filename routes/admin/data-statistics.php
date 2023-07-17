<?php

use App\Http\Controllers\Admin\DataStatistics\AccessStatisticController;
use App\Http\Controllers\Admin\DataStatistics\ClassifiedStatisticController;
use App\Http\Controllers\Admin\DataStatistics\CustomerStatisticController;
use App\Http\Controllers\Admin\DataStatistics\MemberRankStatisticController;
use App\Http\Controllers\Admin\DataStatistics\MemberStatisticController;
use App\Http\Controllers\Admin\DataStatistics\RevenueStatisticController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DataStatistics\DataStatisticsController;

Route::get('/', [DataStatisticsController::class, 'dashboard'])->name('admin.thongke')->middleware('admin.check:62,4');

Route::prefix('api')->name('admin.thongke.api.')->middleware('admin.check:62,4')->group(function () {
    // MEMBER CLASSIFIED RANK STATISTIC
    Route::get('/access-week-data', [AccessStatisticController::class, 'access_week'])->name('access_week');
    Route::get('/access-month-data', [AccessStatisticController::class, 'access_month'])->name('access_month');
    Route::get('/access-year-data', [AccessStatisticController::class, 'access_year'])->name('access_year');

    // MEMBER STATISTIC
    Route::get('/member-week-data', [MemberStatisticController::class, 'member_week'])->name('member_week');
    Route::get('/member-month-data', [MemberStatisticController::class, 'member_month'])->name('member_month');
    Route::get('/member-year-data', [MemberStatisticController::class, 'member_year'])->name('member_year');

    // REVENUE STATISTIC
    Route::get('/current-revenue-week-data/{is_coin?}', [RevenueStatisticController::class, 'revenue_week'])->name('current_revenue_week');
    Route::get('/current-revenue-month-data/{is_coin?}', [RevenueStatisticController::class, 'revenue_month'])->name('current_revenue_month');
    Route::get('/current-revenue-year-data/{is_coin?}', [RevenueStatisticController::class, 'revenue_year'])->name('current_revenue_year');
    Route::get('/total-revenue-week-data', [RevenueStatisticController::class, 'total_revenue_week'])->name('total_revenue_week');
    Route::get('/total-revenue-month-data', [RevenueStatisticController::class, 'total_revenue_month'])->name('total_revenue_month');
    Route::get('/total-revenue-year-data', [RevenueStatisticController::class, 'total_revenue_year'])->name('total_revenue_year');

    // CLASSIFIED STATISTIC
    Route::get('/current-classified-week-data/{type?}/{group_parent?}', [ClassifiedStatisticController::class, 'classified_week'])->name('current_classified_week');
    Route::get('/current-classified-month-data/{type?}/{group_parent?}', [ClassifiedStatisticController::class, 'classified_month'])->name('current_classified_month');
    Route::get('/current-classified-year-data/{type?}/{group_parent?}', [ClassifiedStatisticController::class, 'classified_year'])->name('current_classified_year');

    Route::get('/total-classified-week-data', [ClassifiedStatisticController::class, 'total_classified_week'])->name('classified_week');
    Route::get('/total-classified-month-data', [ClassifiedStatisticController::class, 'total_classified_month'])->name('classified_month');
    Route::get('/total-classified-year-data', [ClassifiedStatisticController::class, 'total_classified_year'])->name('classified_year');

    // CUSTOMER STATISTIC
    Route::get('/current-customer-week-data', [CustomerStatisticController::class, 'current_customer_week'])->name('current_customer_week');
    Route::get('/current-customer-month-data', [CustomerStatisticController::class, 'current_customer_month'])->name('current_customer_month');
    Route::get('/current-customer-year-data', [CustomerStatisticController::class, 'current_customer_year'])->name('current_customer_year');

    Route::get('/total-customer-week-data', [CustomerStatisticController::class, 'customer_week'])->name('customer_week');
    Route::get('/total-customer-month-data', [CustomerStatisticController::class, 'customer_month'])->name('customer_month');
    Route::get('/total-customer-year-data', [CustomerStatisticController::class, 'customer_year'])->name('customer_year');

    // MEMBER CHARGE RANK STATISTIC
    Route::get('/member-charge-total-charge', [MemberRankStatisticController::class, 'charge_total'])->name('charge_total');
    Route::get('/member-charge-week-charge', [MemberRankStatisticController::class, 'current_charge_week'])->name('current_charge_week');
    Route::get('/member-charge-month-charge', [MemberRankStatisticController::class, 'current_charge_month'])->name('current_charge_month');
    Route::get('/member-charge-year-charge', [MemberRankStatisticController::class, 'current_charge_year'])->name('current_charge_year');

    // MEMBER CLASSIFIED RANK STATISTIC
    Route::get('/member-classified-total-charge', [MemberRankStatisticController::class, 'classified_total'])->name('classified_total');
    Route::get('/member-classified-week-charge', [MemberRankStatisticController::class, 'current_classified_week'])->name('current_classified_charge_week');
    Route::get('/member-classified-month-charge', [MemberRankStatisticController::class, 'current_classified_month'])->name('current_classified_charge_month');
    Route::get('/member-classified-year-charge', [MemberRankStatisticController::class, 'current_classified_year'])->name('current_classified_charge_year');

});

