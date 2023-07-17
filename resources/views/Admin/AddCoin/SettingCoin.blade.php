@extends('Admin.Layouts.Master')
@section('Style')
    <style>
        .input-line{
            border: none;
            outline: none;
            background: transparent;
            font-weight: bold;
            min-width: 76%;
            max-width: 100%;
        }
        .color-gray{
            color: #6c757d !important;
        }
        .payment{
            height: 240px;
        }
        .qr-code{
            width: 120px;
            height: 120px;
            position: relative;
        }
    </style>
@endsection

@section('Content')
    <section class="content">
        <div class="container-fluid p-2 mt-2">
                <label for="">Phương thức thanh toán</label>
                <div class="row">
                    @foreach($bank_payments as $bank)
                        <div class="col-12 col-md-12 col-lg-4">
                            <form action="{{route('admin.coin.update_payment')}}" method="post">
                            @csrf
                            <input type="hidden" name="payment_name" value="{{$bank->payment_name}}">
                            <div class="card bg-light d-flex flex-fill payment">
                                <div class="card-header text-muted border-bottom-0">Thanh toán qua chuyển khoản ngân hàng</div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center flex-wrap">
                                                <input type="number" class="input-line lead text-uppercase text-green" name="stk" value="{{intval($bank->payment_param['stk'])}}" required />
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap">
                                                <p class="text-muted text-sm m-0 mt-2">Ngân hàng: </p>
                                                <input class="input-line mt-2 color-gray" name="nh" type="text" value="{{$bank->payment_param['nh']}}" required />
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap">
                                                <p class="text-muted text-sm m-0 mt-2">Chi nhánh: </p>
                                                <input class="input-line mt-2 color-gray" name="cn" type="text" value="{{$bank->payment_param['cn']}}" required />
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap ">
                                                <p class="text-muted text-sm m-0 mt-2">Chủ tài khoản: </p>
                                               <input class="input-line text-uppercase mt-2 color-gray" name="ctk" type="text" value="{{$bank->payment_param['ctk']}}" oninput="this.size = this.value.length" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-right">
                                        <button class="btn btn-sm btn-success" type="submit">
                                            <i class="fas fa-save mr-1"></i> Lưu
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                    @endforeach

                    @foreach($momo_payments as $payment)
                        <div class="col-12 col-md-12 col-lg-4">
                            <form action="{{route('admin.coin.update_payment')}}" method="post">
                                @csrf
                                <input type="hidden" name="payment_name" value="{{$payment->payment_name}}">
                                <div class="card bg-light d-flex flex-fill payment">
                                    <div class="card-header text-muted border-bottom-0">Thanh toán qua momo</div>
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-center">
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <input type="hidden" class="input-line lead text-uppercase text-green" name="stk" value="{{intval($payment->payment_param['stk'])}}" required />
                                                </div>
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <input class="input-line mt-2 color-gray" name="nh" type="hidden" value="{{$payment->payment_param['nh']}}" required />
                                                </div>
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <input class="input-line mt-2 color-gray" name="cn" type="hidden" value="{{$payment->payment_param['cn']}}" required />
                                                </div>
                                                <div class="d-flex align-items-center flex-wrap ">
                                                    <input class="input-line text-uppercase mt-2 color-gray" name="ctk" type="hidden" value="{{$payment->payment_param['ctk']}}" oninput="this.size = this.value.length" required />
                                                </div>

                                                <img src="{{asset($payment->payment_param['qr'])}}" alt="" class="qr-code" data-toggle="modal" data-target="#modalMomo_{{$loop->index}}">
                                                <input type="hidden" name="qr" class="qr_input" id="image_url_qr_momo_{{$loop->index}}" onchange="changeImage(this)">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-right">
                                            <button class="btn btn-sm btn-success" type="submit">
                                                <i class="fas fa-save mr-1"></i> Lưu
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                            <!-- Modal IMAGE ZALO -->
                            <div class="modal fade" id="modalMomo_{{$loop->index}}" tabindex="-1" role="dialog" aria-labelledby="modalMomo_{{$loop->index}}" aria-hidden="true">
                                <div class="modal-dialog modal-file" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Chọn ảnh QR code Momo</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <iframe src="{{url('responsive_filemanager/filemanager/dialog.php')}}?multiple=0&type=1&field_id=image_url_qr_momo_{{$loop->index}}" frameborder="0"
                                                    style="width: 100%; height: 70vh"></iframe>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- //Modal IMAGE ZALO -->
                    @endforeach

                    @foreach($zalo_pay_payment as $payment)
                        <div class="col-12 col-md-12 col-lg-4">
                            <form action="{{route('admin.coin.update_payment')}}" method="post">
                                @csrf
                                <input type="hidden" name="payment_name" value="{{$payment->payment_name}}">
                                <div class="card bg-light d-flex flex-fill payment">
                                    <div class="card-header text-muted border-bottom-0">Thanh toán qua zalo pay</div>
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-center">
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <input type="hidden" class="input-line lead text-uppercase text-green" name="stk" value="{{intval($payment->payment_param['stk'])}}" required />
                                                </div>
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <input class="input-line mt-2 color-gray" name="nh" type="hidden" value="{{$payment->payment_param['nh']}}" required />
                                                </div>
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <input class="input-line mt-2 color-gray" name="cn" type="hidden" value="{{$payment->payment_param['cn']}}" required />
                                                </div>
                                                <div class="d-flex align-items-center flex-wrap ">
                                                    <input class="input-line text-uppercase mt-2 color-gray" name="ctk" type="hidden" value="{{$payment->payment_param['ctk']}}" oninput="this.size = this.value.length" required />
                                                </div>

                                                <img src="{{asset($payment->payment_param['qr'])}}" alt="" class="qr-code" data-toggle="modal" data-target="#modalZaloPay_{{$loop->index}}">
                                                <input type="hidden" name="qr" class="qr_input" id="image_url_qr_zalopay_{{$loop->index}}" onchange="changeImage(this)">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-right">
                                            <button class="btn btn-sm btn-success" type="submit">
                                                <i class="fas fa-save mr-1"></i> Lưu
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                            <!-- Modal IMAGE ZALO -->
                            <div class="modal fade" id="modalZaloPay_{{$loop->index}}" tabindex="-1" role="dialog" aria-labelledby="modalZaloPay_{{$loop->index}}" aria-hidden="true">
                                <div class="modal-dialog modal-file" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Chọn ảnh QR code Zalo Pay</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <iframe src="{{url('responsive_filemanager/filemanager/dialog.php')}}?multiple=0&type=1&field_id=image_url_qr_zalopay_{{$loop->index}}" frameborder="0"
                                                    style="width: 100%; height: 70vh"></iframe>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- //Modal IMAGE ZALO -->
                    @endforeach

                </div>

            <div class="row">
                <div class="col-12">
                    <form action="{{route('admin.coin.setup')}}" method="post">
                        @csrf
                        @foreach($notes as $item)
                        <div class="form-group w-100 p-2 my-2">
                            <label for="">{{$item->config_name}}</label>
                            <textarea name="{{$item->config_code}}" id="{{$item->config_code}}" class="js-admin-tiny-textarea">{{$item->config_value}}</textarea>
                        </div>
                        @endforeach

                        <div class="form-group d-flex justify-content-center mb-4">
                            <button class="btn btn-primary no-border no-radius">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
