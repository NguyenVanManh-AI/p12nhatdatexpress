@extends('user.layouts.master')
@section('title', 'Quản lý gói tin')
@section('content')
    <div class="p-5 user-package-page">
        <div class="status-content mb-5">
            <div class="containers">
                <div class="card mb-5">
                    <div class="row status-parcel text-white">
                        <div class="col showing">
                            <p class="my-4">Đang hiển thị</p>
                            <h1 class="bold">{{ $total_show_classified }}</h1>
                        </div>
                        <div class="col pending-approval">
                            <p class="my-4">Đang chờ duyệt</p>
                            <h1 class="bold">{{ $total_confirm_classified }}</h1>
                        </div>
                        <div class="col not-approved">
                            <p class="my-4">Không được duyệt</p>
                            <h1 class="bold">{{ $total_not_confirm_classified }}</h1>
                        </div>
                        <div class="col expired">
                            <p class="my-4">Đã hết hạn</p>
                            <h1 class="bold">{{ $total_expired_date }}</h1>
                        </div>
                    </div>
                </div>
                @if($current_package)
                <div class="package-item">
                    <p class="title-post">Gói đăng tin hiện tại</p>
                    <div class="row  package-item">
                        <div class="col-4 upgrade-content">
                            <div class="item_cont_packet">
                                <div class="basic">
                                    <input type="text" name="package_temp_id" value="{{$current_package->package_id}}" hidden>
                                    <span class="text-basic red
                                        @if($current_package->package_type == 'A')
                                            wanring
                                        @elseif($current_package->package_type == 'V')
                                            success
                                        @else
                                            red
                                        @endif
                                        package_name ">{{ data_get($current_package->classifiedPackage, 'package_name') }}
                                    </span>
                                </div>
                                <div class="buys_basic
                                    @if($current_package->package_type == 'A')
                                        bg-warning
                                    @elseif($current_package->package_type == 'V')
                                        bg-success
                                    @else
                                    bg-danger
                                    @endif
                                ">
                                    <div class="vnd ">
                                        <p class="line-through text-lowercase">{{ number_format(data_get($current_package->classifiedPackage, 'price', 0)) }} vnđ({{number_format($current_package->coin_price)}} coins)/tháng</p>
                                    </div>
                                    <p class="number">
                                        <span class="package-price">{{ number_format(data_get($current_package->classifiedPackage, 'discount_price', 0)) }}</span> vnđ
                                    </p>
                                    <span class="text-white package-coin">{{ number_format(data_get($current_package->classifiedPackage, 'discount_coin_price', 0)) }}</span> <span class="text-white">coins/tháng</span>
                                </div>
                                <div class="buys_information">
                                    <p>Số tin đăng: <strong>{{ data_get($current_package->classifiedPackage, 'classified_nomal_amount') ? data_get($current_package->classifiedPackage, 'classified_nomal_amount') : 'Không giới hạn' }}</strong></p>
                                    <p>Số tin vip: <b>{{ data_get($current_package->classifiedPackage, 'vip_amount', 0) }} tin ({{ data_get($current_package->classifiedPackage, 'vip_amount', 0) }} ngày vip)</b></p>
                                    <p>Tin nổi bật: <b><b>{{ data_get($current_package->classifiedPackage, 'highlight_amount') }}</b></b></p>
                                    <p class="duration">Thời hạn tin Vip, nổi bật: {{ data_get($current_package->classifiedPackage, 'vip_duration', 0) / 3600 }} giờ</p>
                                    <p>Quản lý khách hàng: <b>{{ data_get($current_package->classifiedPackage, 'cus_mana') ? 'Có' : 'Không' }}</b></p>
                                    <p>Thống kê dữ liệu: <b>{{ data_get($current_package->classifiedPackage, 'data_static') ? 'Có' : 'Không' }}</b></p>
                                    <p class="duration">Hỗ trợ 24/7</p>
                                </div>
                                <button type="button" class="btn btn-danger {{$current_package->package_id != 1?'btn-deposit-package':null}}" data-toggle="modal" data-target="#buyPackageModal" {{$current_package->package_id == 1?'disabled':null}}>Gia Hạn</button>
                            </div>
                        </div>
                        <div class="col-8 upgrade-content">
                            <div class="item_cont_packet">
                                <div class="basic">
                                    <span>Tình Trạng Sử Dụng</span>
                                </div>
                                <div class="buys_basic">
                                    <div class="vnd">
                                        <p class="line-through">{{ number_format(data_get($current_package->classifiedPackage, 'price')) }}</p>
                                        <p>vnđ/tháng</p>
                                    </div>
                                    <p class="number package-price">{{ number_format(data_get($current_package->classifiedPackage, 'discount_price')) }}</p>
                                    <span>coins/Tháng</span>
                                </div>
                                <div class="buys_information">
                                    <p>
                                        Số tin còn lại:
                                        <strong>
                                            {{ $current_package->normal }} tin/tháng
                                        </strong>
                                    </p>
                                    <p>Số tin vip: {{ $current_package->vip }}</p>
                                    <p>Tin nổi bật: {{ $current_package->highlight }}</p>
                                    <p class="duration">Thời hạn tin Vip, nổi bật: {{ data_get($current_package->classifiedPackage, 'vip_duration', 0) / 3600 }} giờ</p>
                                    <p>Quản lý khách hàng: <b>{{ data_get($current_package->classifiedPackage, 'cus_mana') ? 'Có' : 'Không' }}</b></p>
                                    <p>Thống kê dữ liệu: <b>{{ data_get($current_package->classifiedPackage, 'data_static') ? 'Có' : 'Không' }}</b></p>
                                    <p class="duration">Hỗ trợ 24/7</p>
                                </div>
                                <button type="button" class="btn btn-secondary">Đang dùng</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <section class="current-packet">
            <p class="title-post">Nâng cấp gói tin đăng</p>
            <div class="owl-carousel owl-hover-nav owl-drag user-package__carousel w-100">
                @foreach($packages as $item)
                    <div class="post-upgrade package-item package__item">
                        <div class="package__best-seller-tag">
                            @if($item->best_seller)
                                <img src="{{asset('user/images/icon/buy.png')}}" alt="">
                            @endif
                        </div>
                        <div class="item_cont_packet">
                            <div class="basic">
                                <input type="text" name="package_temp_id" value="{{$item->id}}" hidden>
                                <span class="text-basic
                                @if($item->package_type == 'A')
                                    wanring
                                @elseif($item->package_type == 'V')
                                    success
                                @else
                                    red
                                @endif
                                package_name">{{$item->package_name}}</span>
                            </div>
                            <div class="buys_basic
                                @if($item->package_type == 'A')
                                    bg-warning
                                @elseif($item->package_type == 'V')
                                    bg-success
                                @else
                                bg-danger
                                @endif
                            ">
                                <div class="vnd ">
                                    <p class="line-through text-lowercase">{{number_format($item->price)}} vnđ({{number_format($item->coin_price)}} coins)/tháng</p>
                                </div>
                                <p class="number">
                                    <span class="package-price">{{number_format($item->discount_price)}}</span> vnđ
                                </p>
                                <span class="text-white package-coin">{{$item->discount_coin_price}}</span><span class="text-white">coins/tháng</span>
                            </div>
                            <div class="buys_information">
                                <p>Thời hạn sử dụng: {{$item->duration_time/86400}} ngày</p>
                                <p>Số tin đăng: <b>{{$item->classified_nomal_amount?$item->classified_nomal_amount:'Không giới hạn'}}</b></p>
                                <p>Số tin vip: {{$item->vip_amount}} tin ({{$item->vip_amount}} ngày vip)</p>
                                <p>Tin nổi bật: {{$item->highlight_amount}}</p>
                                <p class="duration">Thời hạn tin Vip, nổi bật: {{$item->vip_duration/3600}} giờ</p>
                                <p>Quản lý khách hàng: <b>{{$item->cus_mana == 1 ? 'Có': 'Không'}}</b></p>
                                <p>Thống kê dữ liệu: <b>{{$item->cus_mana == 1 ? 'Có': 'Không'}}</b></p>
                                <p class="duration">Hỗ trợ 24/7</p>
                            </div>
                            @if($item->package_type == 'D')
                                <button type="button" class="btn btn-danger dangky1" disabled>Đăng ký</button>
                            @elseif($item->package_type == 'A')
                                <button type="button" class="btn btn-warning dangky1 btn-deposit-package" data-toggle="modal" data-target="#buyPackageModal" >Đăng ký</button>
                            @else
                                <button type="button" class="btn btn-success dangky1 btn-deposit-package" data-toggle="modal" data-target="#buyPackageModal" >Đăng ký</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        <!-- Modal -->
        <div class="modal" id="buyPackageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered payment-express" role="document">
                <form action="{{route('user.post-package')}}" class="form-payment" method="post">
                    @csrf
                    <div class="modal-content" style="max-width: 600px;">
                        <div class="modal-header" >
                            <div class="logo_modal">
                                <img src="{{asset(SystemConfig::logo())}}" alt="">
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="pay">
                                <div class="pay_title">
                                    <h3>Bạn đã chọn &nbsp;<p class="choose-package-number select_package"></p></h3>
                                    <p>Vui lòng chọn hình thức thanh toán</p>
                                </div>
                                <div class="pay_content">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <a class="nav-item nav-link active payment-tab" data-internalid="1" id="transfer-tab" data-toggle="tab" href="#transfer" role="tab" aria-controls="transfer" aria-selected="true"><img src="{{asset('frontend/user/images/payment/logo_chuyenkhoan.png')}}" alt="">
                                                <span>Chuyển Khoản</span>
                                            </a>
                                            <a class="nav-item nav-link payment-tab" data-internalid="0" id="express-coin-tab" data-toggle="tab" href="#express-coin" role="tab" aria-controls="express-coin" aria-selected="false"> <img src="{{asset('frontend/user/images/payment/logo_Excoin.png')}}" alt="">
                                                <span>Express Coin</span>
                                            </a>
                                            <a class="nav-item nav-link payment-tab" data-internalid="2" id="momo-tab" data-toggle="tab" href="#momo" role="tab" aria-controls="momo" aria-selected="false"><img src="{{asset('frontend/user/images/payment/logo_momo1.png')}}" alt="">
                                                <span>Ví MoMo</span>
                                            </a>
                                            <a class="nav-item nav-link payment-tab " data-internalid="3" id="zalo-pay-tab" data-toggle="tab" href="#zalo-pay" role="tab" aria-controls="zalo-pay" aria-selected="false"><img src="{{asset('frontend/user/images/payment/logo_paypal.png')}}" alt="">
                                                <span>Zalo Pay</span>
                                            </a>
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="transfer" role="tabpanel" aria-labelledby="transfer-tab">
                                            <div class="pm-desc">
                                                Quý khách vui lòng chuyển khoản số tiền: <span class="pm-price"></span>
                                            </div>
                                            <div class="pm-content">
                                                Vào tài khoản bên dưới với nội dung:
                                                <span class="copy-content">{{$deposit_code}}</span>
                                                <span class="copy-text"><i class="far fa-copy"></i></span>
                                            </div>
                                            <ul class="pm-info">
                                                <li>- Ngân hàng: {{$payment_method['NH1']['nh']}}</li>
                                                <li>- Chi nhánh: {{$payment_method['NH1']['cn']}}</li>
                                                <li>- Chủ tài khoản: {{$payment_method['NH1']['ctk']}}</li>
                                                <li>- STK: <span class="copy-stk">{{$payment_method['NH1']['stk']}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        {{-- <div class="tab-pane fade" id="express-coin" role="tabpanel" aria-labelledby="express-coin-tab">
                                            <div class="coupon-code text-center">
                                                <input type="text" name="deposit_voucher" value="" id="deposit-voucher" placeholder="Mã khuyến mại">
                                            </div>
                                            <div class="list-info">
                                                <div class="info-payment item">
                                                    <h4 class="title">Thông tin thanh toán</h4>
                                                    <div class="price">Thanh toán: <span class="pm-coin-price text-red text-bold"></span> coins</div>
                                                    <div class="discount">Mã ưu đãi: <span class="copy-content discount-coin-percent">0% - 0</span> coins</div>
                                                    <div class="total-pm">Tổng thanh toán: <span class="copy-stk text-red total-payment-coin"></span> coins</div>
                                                </div>
                                                <div class="info-account-1 item">
                                                    <h4 class="title">Thông tin tài khoản</h4>
                                                    <div class="my-coin">Bạn hiện có: <span class="pm-user-coin text-red text-bold">{{auth()->guard('user')->user()->coint_amount}}</span> coins</div>
                                                    <div class="notification d-none">Vui lòng <a href="#" class="add-coin"> Nạp Coin </a> hoặc <br> thay đổi phương thức thanh toán</div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="tab-pane fade" id="momo" role="tabpanel" aria-labelledby="momo-tab">
                                            <h4 class="title">MoMo - Mã QR Code</h4>
                                            <div class="pm-content">
                                                <div class="qr-code">
                                                    <img src="{{asset($payment_method['MOMO']['qr'])}}" alt="">
                                                </div>
                                                <div class="tutorial">
                                                    <h5>Thực hiện theo hướng dẫn sau để thanh toán</h5>
                                                    <ul>
                                                        form                         <li>Bước 1: Mở ứng dụng MoMo để thanh toán</li>
                                                        <li>Bước 2: Chọn "Thanh toán" và quét mã QR Code tại hướng dẫn này</li>
                                                        <li>Bước 3: Nhập số tiền thanh toán: <span class="pm-price"></span></li>
                                                        <li>Bước 4: Nhập lời nhắn khi chuyển tiền là dãy số sau: <span class="text-red">{{$deposit_code}}</span></li>
                                                        <li>Bước 5: Xác nhận chuyển tiền</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="text-center">Nếu bạn chưa có tài khoản Ví MOMO, bạn có thể đăng ký miễn phí <a href="https://momo.vn/"> tại đây</a></div>
                                        </div>
                                        <div class="tab-pane fade" id="zalo-pay" role="tabpanel" aria-labelledby="zalo-pay-tab">
                                            <h4 class="title">ZaloPay - Mã QR Code</h4>
                                            <div class="pm-content">
                                                <div class="qr-code">
                                                    <img src="{{asset($payment_method['ZALOPAY']['qr'])}}" alt="">
                                                    <div class="text-justify mt-2 pl-3">Thời gian quét mã QR thanh toán còn <span class="text-blue js-time-countdown">287</span> giây</div>
                                                </div>
                                                <div class="tutorial">
                                                    <h5>Thực hiện theo hướng dẫn sau để thanh toán</h5>
                                                    <ul>
                                                        <li>Bước 1: Mở ứng dụng ZaloPay để thanh toán</li>
                                                        <li>Bước 2: Chọn "Thanh toán" và quét mã QR Code tại hướng dẫn này</li>
                                                        <li>Bước 3: Nhập số tiền thanh toán: <span class="pm-price"></span></li>
                                                        <li>Bước 4: Nhập lời nhắn khi chuyển tiền là dãy số sau: <span class="text-red">{{$deposit_code}}</span></li>
                                                        <li>Bước 5: Xác nhận chuyển tiền</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <input type="text" name="package_id" value="{{$item->id}}" hidden>
                            <input  type="text" name="payment_method" value="0" hidden>
                            <input  type="text" name="deposit_code" value="{{$deposit_code}}" hidden>
                        </div>
                        <div class="modal-footer">
                            <label for="confirm-payment" class="confirm-pm">
                                <input type="checkbox" id="confirm-payment" name="confirm_payment">
                                Xác nhận đã thanh toán
                            </label>
                            <button type="submit" class="btn btn-info btn-confirm " disabled>Xác nhận</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('user/js/package.js')}}"></script>

@endsection
