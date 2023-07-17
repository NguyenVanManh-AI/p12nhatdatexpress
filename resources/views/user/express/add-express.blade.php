@extends('user.layouts.master')
@section('title', 'Tạo chiến dịch')
@section('content')
    <form action="{{route('user.post-express')}}" method="post" enctype="multipart/form-data" class="user-express-form">
        @csrf
        <div class="campaign-homepage step-section phase-1">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Quảng cáo Express</a></li>
                <li class="breadcrumb-item"><a href="#">Vị trí</a></li>
            </ol>
            <div class="homepage-content">
                <nav>
                    <p class="advertisement-title">Chọn vị trí quảng cáo</p>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="select-position nav-item nav-link {{ old('banner') != 'H' ? 'active' : '' }}" data-banner="C" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
                            Trang chuyên mục
                        </a>
                        <a class="select-position nav-item nav-link {{ old('banner') == 'H' ? 'active' : '' }}" data-banner="H" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                            Trang chủ
                        </a>
                    </div>
                </nav>
                <div class="tab-content mt-3" id="nav-tabContent">
                    <div class="tab-device tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="row banner-group-type">
                            <div class="col-md-5 page-qc">
                                <div class="option-page d-flex justify-content-between">
                                    <select name="category" id="cate" class="form-control cs-select" data-placeholder="Chọn chuyên mục">
                                        {{show_select_option($group, 'id', 'group_name', 'category', null, 0, old('category'))}}
                                    </select>
                                    <select name="paradigm" id="para" class="paradigm form-control cs-select" data-placeholder="Chọn mô hình"></select>
                                </div>
                                <div class="homepage-content-one">
                                    <div class="device-group-btn d-flex">
                                        <button type="button" data-device="D" class="device-btn btn-desktop {{ old('banner') != 'M' ? 'active' : '' }}">Desktop</button>
                                        <button type="button" data-device="M" class="device-btn {{ old('banner') == 'M' ? 'active' : '' }}">Mobile</button>
                                    </div>
                                    <div class="homepage-number">
                                        @foreach($banner->where('banner_group', 'C') as $item)
                                            <div class="custom-control custom-radio {{$item->banner_position}} {{$item->banner_type}} {{$item->banner_type == 'D'?'':'d-none'}}" >
                                                <input type="radio" id="expressPosition{{$item->id}}" name="position" class="custom-control-input {{$item->banner_group}}" value="{{$item->id}}"
                                                       data-coin-price="{{$item->banner_coin_price}}" data-position="c-{{$item->banner_type}}-{{$item->banner_position}}"
                                                       data-banner-width="{{$item->banner_width}}" data-banner-height="{{$item->banner_height}}"
                                                       data-banner-name="{{ $item->banner_name }}" data-banner-pos="{{ $item->banner_position }}"
                                                       data-banner-group-id="{{ $item->id }}" {{ old('position') == $item->id ? 'selected' : '' }}>
                                                <label class="custom-control-label" for="expressPosition{{$item->id}}">{{$item->banner_name}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="note-pages"></div>
                            </div>
                            <div class="col-md-7 image-home-page mt-3">
                                <div class="image-page d-flex justify-content-center">
                                    <img src="{{asset('user/images/banner/banner.png')}}" alt="" class="express-img img-c-d-l col-2 m-0 p-0">
                                    <img src="{{asset('user/images/banner/group.PNG')}}" class="col-8 m-0 p-0">
                                    <img src="{{asset('user/images/banner/banner.png')}}" alt="" class="express-img img-c-d-r col-2 m-0 p-0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="row home-page-one">
                            <div class="col-md-5 page-qc banner-group-type">
                                <div class="homepage-content-one">
                                    <div class="device-group-btn d-flex">
                                        <button type="button" data-device="D" class="device-btn btn-desktop active">Desktop</button>
                                        <button type="button" data-device="M" class="device-btn">Mobile</button>
                                    </div>
                                    <div class="homepage-number">
                                        @foreach($banner->where('banner_group', 'H') as $item)
                                            <div class="custom-control custom-radio {{$item->banner_position}} {{$item->banner_type}} {{$item->banner_type == 'D'?'':'d-none'}}" >
                                                <input type="radio" id="expressPosition{{$item->id}}" name="position" class="custom-control-input {{$item->banner_group}}" value="{{$item->id}}"
                                                       data-coin-price="{{$item->banner_coin_price}}" data-position="h-{{$item->banner_type}}-{{$item->banner_position}}"
                                                       data-banner-width="{{$item->banner_width}}" data-banner-height="{{$item->banner_height}}"
                                                       data-banner-name="{{ $item->banner_name }}" data-banner-pos="{{ $item->banner_position }}"
                                                       data-banner-group-id="{{ $item->id }}" {{ old('position') == $item->id ? 'selected' : '' }}>
                                                <label class="custom-control-label" for="expressPosition{{$item->id}}">{{$item->banner_name}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="note-pages"></div>
                            </div>
                            <div class="col-md-7 image-home-page">
                                <div class="image-page d-flex justify-content-center">
                                    <img src="{{asset('user/images/banner/banner.png')}}" alt="" class="express-img img-h-d-l col-2 m-0 p-0">
                                    <img src="{{asset('user/images/banner/home.png')}}" class="col-8 m-0 p-0">
                                    <img src="{{asset('user/images/banner/banner.png')}}" alt="" class="express-img img-h-d-r col-2 m-0 p-0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="budget">
                {!! $guide->where('config_code', 'N002')->first()->config_value!!}
            </div>
            <div class="next-page-home">
                <button type="button" class="btn-next" onclick="nextToStep2()">Tiếp >></button>
            </div>
        </div>

        {{--  phase 2--}}
        <div class="campaign-homepage step-section phase-2 d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Quảng cáo Express</a></li>
                <li class="breadcrumb-item"><a href="#">Vị trí</a></li>
                <li class="breadcrumb-item"><a href="#">Hình ảnh</a></li>
            </ol>
            <div class="row campaign-two">
                <div class="col-md-7 campaign-download-image">
                    <div class="upload-item">
                        <div class="wrap-out">
                            <div class="wrap-in">
                                <img src="{{asset('user/images/icon/upload-logo.png')}}">
                                <div class="logo-note">Chọn ảnh để tải lên</div>
                                <p class="banner-img-size-desc"></p>
                                <div class="buttons upload">
                                    <div class="button button-upload">Upload ảnh</div>
                                    <input type="file" name="banner_image" value="{{ old('banner_image') }}" accept="image/*" hidden>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="address">
                        <input type="text" name="" placeholder="Chèn đường dẫn">
                    </div>
                    <div class="display-time">
                        <div class="time-title">Chọn thời gian hiển thị</div>
                        <div class="calendar">
                            <input type="text" name="banner_date" value="{{ old('banner_date') }}" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="col-md-5 images-campaign-2">
                    <div class="upload-images">
                        <div class="image-size">
                            <div class="upload-image" >
                                <img class="upload-cover upload-img show-upload-banner-img" src="" style="max-width: 100%;max-height: 100%">
                            </div>
                        </div>
                        <div class="campaign-note">
                            <div class="title-note">Ghi chú</div>
                            <div class="note bg-white p-3 " style="padding-top: 10px!important;">
                                {!! $guide->where('config_code', 'N003')->first()->config_value !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 button-campaign2">
                    <button type="button" class="btn-next" onclick="nextToStep3()">Tiếp >></button>
                    <button type="button" class="btn-back" onclick="goToStep(1)"><< Quay lại</button>
                </div>
            </div>
        </div>
        <div class="campaign-homepage step-section phase-3 d-none">
            <div class="create-campaign">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Quảng cáo Express</a></li>
                        <li class="breadcrumb-item"><a href="#">Vị trí</a></li>
                        <li class="breadcrumb-item"><a href="#">Hình ảnh</a></li>
                        <li class="breadcrumb-item"><a href="#">Thanh toán</a></li>
                    </ol>
                </nav>
                <div class="campaign-title">
                    <a class="title-payment" href="#"><img src="{{asset('user/images/icon/icon-thanhtoan.png')}}" alt="">Thanh Toán</a>
                    <p class="title_coin">Chỉ chấp nhận thanh toán bằng <a class="coin" href="{{route('user.deposit')}}">Express Coin</a> </p>
                </div>
                <div class="row campaign-payment-3 ">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 campaign-cont ">
                        <div class="campaign-one">
                            <p class="title-advertisement">Xem lại quảng cáo </p>
                            <div class="advertisement-contents">
                                <div class="information">
                                    <div class="alpha display-detail">
                                        <ul>
                                            <li>Chuyên mục: <span id="display-paradigm"></span></li>
                                            <li>Vị trí hiển thị: <span id="display-position"></span></li>
                                            <li>Thời gian hiển thị: <span id="display-time"></span></li>
                                            <li>Số ngày hiển thị: <span id="display-days"></span></li>
                                            <li>Thiết bị hiển thị: <span id="display-device"></span></li>
                                        </ul>
                                    </div>
                                    <div class="beta">
                                        <p class="account-coin">Tài khoản Express Coin</p>
                                        <p>Hiên đang có <span class="coin-number">{{auth()->guard('user')->user()->coint_amount}} Coin.</span> Nếu không đủ Coin thanh toán vui lòng tiến
                                            hành <a class="load-coin" href="{{route('user.deposit')}}">Nạp Coin</a> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 campaign-cont ">
                        <div class="campaign-two p-0">
                            <p class="payments">Thanh toán</p>
                            <div class="payment-content">
                                <input type="text" class="form-insert-code" placeholder="NHẬP MÃ ƯU ĐÃI TẠI ĐÂY">
                                <ul>
                                    <li>Cần thanh toán: <span id="display-coin"></span> Coin</li>
                                    <li>Mã ưu đãi: </li>
                                    {{-- <li>Mã ưu đãi: -10% (500 Coin)</li> --}}
                                </ul>
                                <p class="coin-payments">Tổng số coin cần thanh toán</p>
                                <p class="coin-number"><span id="display-total-amount"></span> Coin</p>
                                <button class="btn btn-primary btn-small btn-payout" type="button">Thanh toán</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="come-back">
                    <button type="button" class="btn btn-secondary" onclick="goToStep(2)"><< Quay lại</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{asset('user/js/express.js')}}"></script>
@endsection
