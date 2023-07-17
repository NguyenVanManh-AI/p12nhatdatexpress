@extends('user.layouts.master')
@section('title','Quản lý tài chính')
@section('css')
    <style>
        .logo_modal img{
            max-width: 200px;
            max-height: 60px;
        }
    </style>
@endsection
@section('content')
    <div class="account-information-content position-relative">
        <div class="row account-history">
            <div class="col-md-6 total-coin">
                <div class="row total-account">
                    <div class="col-md-6 coin-available">
                        <p class="title-total">Tổng số Coin hiện có</p>
                        <div class="total-number-coin">
                            <p class="number-coin-total">{{number_format(auth()->guard('user')->user()->coint_amount)}}</p>
                            <p class="coin-total">Coin</p>
                        </div>
                    </div>
                    <div class="col-md-6 coin-available">
                        <p class="title-total">Coin nhận từ giới thiệu</p>
                        <div class="total-intro-coin">
                            <p class="number-coin-total">{{number_format($total_coin_ref)}}</p>
                            <p class="coin-total">Coin</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 top-up-coins">
                <p class="title-to-up">Nạp coin</p>
                <div class="deposit-money">
                    <p class="option-to-up">Chọn số tiền để nạp </p>
                    <select name="categories" id="deposit-amount" class="deposit-amount w-50">
                        <option value="1">100.000 VND</option>
                        <option value="2">200.000 VND</option>
                        <option value="3">500.000 VND</option>
                        <option value="4">1.000.000 VND</option>
                        <option value="5">3.000.000 VND</option>
                        <option value="6">5.000.000 VND</option>
                        <option value="7">10.000.000 VND</option>
                        <option value="8">20.000.000 VND</option>
                    </select>
                    <div class="promo-code d-">
                        <input class="form-insert-codes select2-blue " id="deposit-voucher" placeholder="Nhập mã ưu đãi" value="{{$voucher_code}}">
                        <button class="btn-load btn-deposit-coin" data-toggle="modal" data-target="#exampleModalCenter">Nạp</button>
                    </div>
                </div>
            </div>
        </div>
        <p class="recharge-history-title">Lịch sử nạp tiền</p>
        <div class="recharge-history">
            <table class="table-introduce">
                <tbody>
                <tr class="title">
                    <th>STT</th>
                    <th>Thời gian</th>
                    <th>Mã thanh toán</th>
                    <th>Hình thức nạp</th>
                    <th>Số tiền</th>
                    <th>Mã ưu đãi</th>
                    <th>Tình trạng</th>
                </tr>
                @foreach($deposit_list as $deposit)
                    <tr>
                        <td class="ID">{{$deposit->num}}</td>
                        <td>{{vn_date($deposit->deposit_time)}}</td>
                        <td>{{$deposit->deposit_code}}</td>
                        <td>{{$deposit->payment_name == 'NH1'?'Chuyển khoản':$deposit->payment_name}}</td>
                        <td>{{number_format($deposit->deposit_amount)}}</td>
                        <td>+{{$deposit->voucher_discount_percent}}%</td>
                        @if($deposit->deposit_status == 0)
                            <td class="text-secondary">Chưa xử lý</td>
                        @elseif($deposit->deposit_status == 1)
                            <td class="text-success">Đã xác nhận</td>
                        @else
                            <td class="text-danger">Không xác nhận</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-pagination alpha">
            <div class="left"></div>
            <div class="right">
                {{ $deposit_list->render('user.page.pagination') }}
            </div>
        </div>
        <div class="note-accounts-history">
            <div class="step-up-mechanism p-2">
                {!! $guide !!}
            </div>
        </div>

        <div class="modal fade " id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="{{route('user.post-deposit')}}" class="form-payment" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
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
                                    <h3>Bạn đã chọn gói &nbsp; <p class="choose-package-number"></p></h3>
                                    <p>Vui lòng chọn hình thức thanh toán</p>
                                </div>
                                <div class="pay_content">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <a class="nav-item nav-link active" id="transfer-tab" data-internalid="1" data-toggle="tab" href="#transfer" role="tab" aria-controls="transfer" aria-selected="true" >
                                                <img src="{{asset('user/images/payment/logo_chuyenkhoan.png')}}" alt="">
                                                <span>Chuyển Khoản</span>
                                            </a>
                                            {{-- <a class="nav-item nav-link" id="express-coin" data-internalid="4" data-toggle="tab" href="#express_coin" role="tab" aria-controls="momo" aria-selected="false">
                                                <img src="{{asset('user/images/payment/image_default_nhadat.jpg')}}" alt="">
                                                <span>Express Coin</span>
                                            </a> --}}
                                            <a class="nav-item nav-link" id="momo-tab" data-internalid="2" data-toggle="tab" href="#momo" role="tab" aria-controls="momo" aria-selected="false">
                                                <img src="{{asset('user/images/payment/logo_momo1.png')}}" alt="">
                                                <span>Ví MoMo</span>
                                            </a>
                                            <a class="nav-item nav-link" id="zalo-pay-tab" data-internalid="3" data-toggle="tab" href="#zalo-pay" role="tab" aria-controls="zalo-pay" aria-selected="false">
                                                <img src="{{asset('user/images/payment/logo_paypal.png')}}" alt="">
                                                <span>Zalo Pay</span>
                                            </a>
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="transfer" role="tabpanel" aria-labelledby="transfer-tab">
                                            <div class="pm-desc">
                                                Quý khách vui lòng chuyển khoản số tiền: <span class="pm-price" id="show-payment-amount"> </span>
                                            </div>
                                            <div class="pm-content">
                                                Vào tài khoản bên dưới với nội dung: <span class="copy-content">{{$transaction_code}}</span>
                                            </div>
                                            <ul class="pm-info">
                                                <li>- Ngân hàng: {{$payment_method['NH1']['nh']}}</li>
                                                <li>- Chi nhánh: {{$payment_method['NH1']['cn']}}</li>
                                                <li>- Chủ tài khoản: {{$payment_method['NH1']['ctk']}}</li>
                                                <li>- STK: <span class="copy-stk">{{$payment_method['NH1']['stk']}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="tab-pane fade" id="express_coin" role="tabpanel" aria-labelledby="express-coin">
                                            <div class="row">
                                                <div class="coin_content"><label class="copy-content">ND256657</label></div>
                                            </div>
                                            <div class="row coin-info-main">
                                                <div class="coin-info-pm">
                                                    <ul>
                                                        <li><a href="#">Thông tin thanh toán</a></li>
                                                        <li>Thanh toán: <span class="coin-pm"> 2.500 Coin</span></li>
                                                        <li>Mã ưu đãi: <span class="coin-endow"> 10% - 250 Coin</span></li>
                                                        <li>Tổng thanh toán: <span class="coin-total">2.250 Coin</span></li>
                                                    </ul>
                                                </div>
                                                <div class="coin-info-account">
                                                    <ul>
                                                        <li><a href="#">Thông tin tài khoản</a></li>
                                                        <li>Bạn hiện có: <span class="coin-have">500 Coin</span></li>
                                                        <li class="d-flex text-center justify-content-center">Vui lòng <a href="#" class="load_coin" > Nạp Coin </a> hoặc thay đổi phương thức thanh toán</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="momo" role="tabpanel" aria-labelledby="momo-tab">
                                            <h4 class="title">MoMo - Mã QR Code</h4>
                                            <div class="pm-content">
                                                <div class="qr-code">
                                                    <img src="{{asset($payment_method['MOMO']['qr'])}}" alt="">
                                                </div>
                                                <div class="tutorial">
                                                    <h5>Thực hiện theo hướng dẫn sau để thanh toán</h5>
                                                    <ul>
                                                        <li>Bước 1: Mở ứng dụng MoMo để thanh toán</li>
                                                        <li>Bước 2: Chọn "Thanh toán" và quét mã QR Code tại hướng dẫn này</li>
                                                        <li>Bước 3: Nhập lời nhắn khi chuyển tiền là dãy số sau:
                                                            <span class="text-red">{{$transaction_code}}</span>
                                                        </li>
                                                        <li>Bước 4: Xác nhận chuyển tiền</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                Nếu bạn chưa có tài khoản Ví MOMO, bạn có thể đăng ký miễn phí
                                                <a href="https://momo.vn/"> tại đây</a>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="zalo-pay" role="tabpanel" aria-labelledby="zalo-pay-tab">
                                            <h4 class="title">ZaloPay - Mã QR Code</h4>
                                            <div class="pm-content">
                                                <div class="qr-code">
                                                    <img src="{{asset($payment_method['ZALOPAY']['qr'])}}" alt="">
                                                    <div class="text-justify mt-2 pl-3">
                                                        Thời gian quét mã QR thanh toán còn
                                                        <span  class="text-blue js-time-countdown">60</span> giây
                                                    </div>

                                                </div>
                                                <div class="tutorial">
                                                    <h5>Thực hiện theo hướng dẫn sau để thanh toán</h5>
                                                    <ul>
                                                        <li>Bước 1: Mở ứng dụng ZaloPay để thanh toán</li>
                                                        <li>Bước 2: Chọn "Thanh toán" và quét mã QR Code tại hướng dẫn này</li>
                                                        <li>Bước 3: Nhập lời nhắn khi chuyển tiền là dãy số sau:
                                                            <span class="text-red">{{$transaction_code}}</span>
                                                        </li>
                                                        <li>Bước 4: Xác nhận chuyển tiền</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input name="deposit_amount" id="input-deposit-amount" hidden>
                            <input name="deposit_voucher" id="input-depoosit-voucher" hidden>
                            <input name="deposit_code" value="{{$transaction_code}}" hidden>
                            <input name="payment_method" value="1" hidden>
                        </div>
                        <div class="modal-footer">
                            <label for="confirm-payment" class="confirm-pm">
                                <input type="checkbox" id="confirm-payment" name="confirm_payment">
                                Xác nhận đã thanh toán
                            </label>
                            <button type="submit" class="btn btn-info btn-confirm" disabled>Xác nhận</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('user/js/deposit.js')}}"></script>
@endsection
