@extends('user.layouts.master')
@section('title', 'Quản lý cấp bậc')
@section('content')
  <x-user.breadcrumb
    active-label="Quản lý cấp bậc"
  />

  <div class="user-level-page">
    <div class="container bg-unset">
      <div class="user-level__box">
        <div class="owl-carousel owl-custom-theme owl-hover-nav owl-nav-rounded owl-drag user-level__carousel">
          @foreach ($userLevels as $userLevel)
            <div class="user-level__item px-3">
              <div class="user-level__frame-box image-ratio-box-square relative">
                <div class="user-level__frame absolute-full">
                  <div class="user-level__avatar absolute-full" style="background-image: url({{ asset(data_get($user->detail, 'avatar')) }})">
                  </div>
                  <img class="object-contain relative" src="{{ asset($userLevel->image_url) }}" alt="">
                </div>
              </div>

              <div class="user-level__meta-box text-center">
                <h3 class="user-level__meta-name mb-0 {{ $userLevel->id === $user->user_level_id ? 'text-blue' : '' }}">
                  Tài khoản {{ $userLevel->level_name }}
                </h3>
                <p class="user-level__meta-condition mb-0">
                  Điều kiện đạt: {{ "$userLevel->classified_min_quantity - $userLevel->classified_max_quantity" }} tin đăng
                </p>
                <div class="user-level__meta-give mb-3">
                  Tặng
                  <span class="bold text-success">
                    {{ $userLevel->percent_special }}%
                  </span>
                  giá trị nạp Coin
                </div>
              </div>

              @if($userLevel->id === $user->user_level_id)
                <div class="user-level__current-box text-center" data-current-index="{{ $loop->index }}">
                  <span class="user-level__current-badge badge badge-pill badge-light fw-normal text-dark-blue px-4 py-2 mb-3">Cấp bậc hiện tại</span>
                  <div class="user-level__current-progress-box">
                    <div class="user-level__current-progress progress bg-white mb-2">
                      <div class="progress-bar bg-success" role="progressbar"
                        style="width: {{ round($totalClassifieds * 100 / $userLevel->classified_max_quantity, 0, PHP_ROUND_HALF_DOWN) }}%"
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="user-level__current-posted flex-between flex-wrap">
                      <p class="text-success fs-18 fw-500 mb-2 mr-2">Số tin đã đăng:</p>
                      <p class="text-success fs-18 fw-500 mb-2">{{ "$totalClassifieds / $userLevel->classified_max_quantity" }}</p>
                    </div>
                    <div class="user-level__current-posted flex-between flex-wrap">
                      <p class="text-success fs-18 fw-500 mb-2 mr-2">Số coin đã nạp:</p>
                      <p class="text-success fs-18 fw-500 mb-2">
                        {{-- 100 = 1 coin --}}
                        {{ number_format($user->deposits()->sum('deposit_amount') / 100) }}
                      </p>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    </div>
    <div class="step-up-mechanism relative p-3">
      <h4 class="step-up-main-title text-uppercase text-dark-blue px-4">
        Cơ chế tăng bậc
      </h4>
      {!! $guide->config_value ?? '' !!}
    </div>
  </div>
@endsection
