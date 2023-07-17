@extends('user.layouts.master')
@section('title', 'Danh sách hóa đơn')

@section('content')
    <div class="invoice-personal-content">
        <div class=" invoice-personal">
            <div class="row">
                <div class="col-12 col-md-6 information-acc">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link d-flex flex-column align-items-center {{old('invoice_type') != 2?'active':null}}" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                <img src="{{asset('user/images/payment/canhan_img.png')}}" alt="">
                                <p class="mt-2 text-center text-bold">Xuất hóa đơn cá nhân</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex flex-column align-items-center {{old('invoice_type') == 2?'active':null}}" id="profile-tab" data-toggle="tab"
                               href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                <img src="{{asset('user/images/payment/congty_img.png')}}" alt="">
                                <p class="mt-2 text-center text-bold">Xuất hóa đơn công ty</p>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="myTabContent">
                        <div class="tab-pane  {{old('invoice_type') != 2?'show active':null}}" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form action="{{route('user.request-invoice')}}" method="post">
                                @csrf
                                <input type="text" name="invoice_type" value="1" hidden>
                                <div class="form-group">
                                    <label>Chọn mục cần nhận <span class="text-danger">*</span></label>
                                    <select name="deposit_type" class="form-control cs-select" data-placeholder="Chọn mục">
                                        <option value=""></option>
                                        <option value="I">Mua gói tin</option>
                                        <option value="S">Dịch vụ</option>
                                        <option value="C">Nạp tiền</option>
                                        <option value="B">Quảng cáo</option>
                                    </select>
                                    @if(old('invoice_type') == 1)
                                        {{show_validate_error($errors,'deposit_type')}}
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>Chọn giao dịch<span class="text-danger">*</span></label>
                                    <select name="deposit_code" class="form-control cs-select" data-placeholder="Chọn giao dịch">
                                        <option value=""></option>
                                        @foreach($deposit_list as $deposit)
                                            <option
                                                value="{{$deposit->deposit_code}}">{{$deposit->deposit_code." - ".vn_date($deposit->deposit_time)}}</option>
                                        @endforeach
                                    </select>
                                    @if(old('invoice_type') == 1)
                                        {{show_validate_error($errors,'deposit_code')}}
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Ghi chú</label>
                                    <textarea  name="invoice_content" class="form-control" rows="3" placeholder="Nhập yêu cầu khác nếu có"></textarea>
                                    @if(old('invoice_type') == 1)
                                        {{show_validate_error($errors,'invoice_content')}}
                                    @endif
                                </div>
                                <div class="send-require text-right mb-3">
                                    <button type="submit" class="btn-request">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                        Gửi yêu cầu
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade {{old('invoice_type') == 2?'show active':null}}" id="profile" role="tabpanel"
                             aria-labelledby="profile-tab">
                            <form action="{{route('user.request-invoice')}}" method="post">
                                @csrf
                                <input type="text" name="invoice_type" value="2" hidden>
                                <div class="form-group">
                                    <label>Tên công ty<span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control form-control-lg" >
                                    {{show_validate_error($errors,'company_name')}}
                                </div>
                                <div class="form-group">
                                    <label>Người đại diện<span class="text-danger">*</span></label>
                                    <input type="text" name="fullname" class="form-control form-control-lg" >
                                    {{show_validate_error($errors,'fullname')}}
                                </div>
                                <div class="form-group">
                                    <label>Mã số thuế<span class="text-danger">*</span></label>
                                    <input type="text" name="tax_number" class="form-control form-control-lg" >
                                    {{show_validate_error($errors,'tax_number')}}
                                </div>
                                <div class="form-group">
                                    <label>Địa chỉ<span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control form-control-lg" >
                                    {{show_validate_error($errors,'address')}}
                                </div>
                                <div class="form-group">
                                    <label>Chọn mục cần nhận hóa đơn<span class="text-danger">*</span></label>
                                    <select name="deposit_type" class="form-control cs-select" data-placeholder="Chọn giao dịch">
                                        <option value=""></option>
                                        <option value="I">Mua gói tin</option>
                                        <option value="S">Dịch vụ</option>
                                        <option value="C">Nạp tiền</option>
                                        <option value="B">Quảng cáo</option>
                                    </select><br>
                                    @if(old('invoice_type') == 2)
                                        {{show_validate_error($errors,'deposit_type')}}
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Chọn giao dịch<span class="text-danger">*</span></label>
                                    <select name="deposit_code" class="form-control cs-select" data-placeholder="Chọn giao dịch">
                                        <option value=""></option>
                                        @foreach($deposit_list as $deposit)                                            <option
                                                value="{{$deposit->deposit_code}}">{{$deposit->deposit_code." - ".vn_date($deposit->deposit_time)}}</option>
                                        @endforeach
                                    </select>
                                    @if(old('invoice_type') == 2)
                                        {{show_validate_error($errors,'deposit_code')}}
                                    @endif

                                </div>
                                <div class="form-group">
                                    <label for="">Ghi chú</label>
                                    <textarea name="invoice_content" class="form-control" rows="3"  placeholder="Nhập yêu cầu khác nếu có"></textarea>
                                    @if(old('invoice_type') == 2)
                                        {{show_validate_error($errors,'invoice_content')}}
                                    @endif
                                </div>
                                <div class="send-require text-right mb-3">
                                    <button type="submit" class="btn-request">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                        Gửi yêu cầu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 information-note">
                    <div class="note-bill m-0 ">
                        <p class="title-note">Ghi chú</p>
                        <div class="note-content p-1">
                            {!! $guide !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="table-individual m-0 mt-3">
            <table class="table table-introduce">
                <tbody>
                <tr class="title">
                    <th>STT</th>
                    <th>Thời gian</th>
                    <th>Mã giao dịch</th>
                    <th>Hóa đơn</th>
                    <th>Giao dịch</th>
                    <th>Trạng thái</th>
                    <th>Tải xuống</th>
                </tr>
                @php $i = 1; @endphp
                @foreach($invoice_request as $item)
                    <tr>
                        <td class="STT">{{$i++}}</td>
                        <td>{{vn_date($item->created_at)}}</td>
                        <td>{{$item->deposit_code}}</td>
                        @if($item->deposit_type == 'I')
                            <td>Đăng tin</td>
                        @elseif($item->deposit_type == 'B')
                            <td>Mua quản cáo</td>
                        @elseif($item->deposit_type == 'S')
                            <td>Mua dịch vụ</td>
                        @elseif($item->deposit_type == 'C')
                            <td>Nạp tiền</td>
                        @endif
                        <td>{{number_format($item->deposit_amount)}} VND</td>
                        @if($item->confirm_status == 0)
                            <td class="processing">Đang xử lý</td>
                        @else
                            <td class="processed">Đã xử lý</td>
                        @endif
                        @if($item->confirm_status)
                            <td><a href="{{route('user.invoice-download', $item->id)}}"><i class="fas fa-download"></i></a>
                            </td>
                        @else
                            <td></td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-pagination alpha">
            <div class="right">
                {{ $invoice_request->render('user.page.pagination') }}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('user/js/deposit.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
@endsection

