@extends('user.layouts.master')
@section('content')
    <div class="content-account-business">
        <div class="search-event-top">
            <form action="{{route('user.reference')}}" method="get">
                <div class="row">
                    <div class="col-md-6 col-xl-6">
                        <span>Nhập email</span>
                        <input type="text" name="email" class="form-control" value="{{request()->email}}">
                    </div>
                    <div class="col-md-3 col-xl-4">
                        <span>Họ tên</span>
                        <input type="text" name="fullname" class="form-control" value="{{request()->fullname}}">
                    </div>
                    <div class="col-md-3 col-xl-2 search-col">
                        <button type="submit" class="btn btn-outline-success search-acount" type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="add-account-link">
            <div class="title-business-acount-top">
                <h3>Thêm tài khoản liên kết</h3>
            </div>
            <div class="content-account-link">
                <form action="{{route('user.add-reference')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <span>Email</span>
                            <input type="text" name="email" class="form-control">
                            {{show_validate_error($errors, 'email')}}
                        </div>
                        <div class="col-12 col-md-5">
                            <span>Mã người dùng</span>
                            <input type="text" name="user_code" class="form-control">
                            {{show_validate_error($errors, 'user_code')}}
                        </div>
                        <div class="col-12 col-md-2 button-link">
                            <button class="btn btn-outline-success" type="submit"><i class="fas fa-link"></i> Liên kết</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <div class="table-account-business">
            <table class="table" border="1">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên tài khoản</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Tài khoản</th>
                    <th>Tùy chỉnh</th>
                </tr>
                </thead>
                <tbody>
                @foreach($refs as $item)
                    <tr>
                        <td>
                            <div class="stt-account-customer">
                                <div class="info-account-create">{{$item->id}}</div>
                            </div>
                            <input type="text" class="user_ref_code" value="{{$item->user_code}}" hidden>
                        </td>

                        <td>
                            <div class="info-account-customer">
                                <div class="avatar-account-customer"><img class="w-100" src="{{asset($item->image_url)}}" alt="" ></div>
                                <div class="name-account-customer">
                                    <a href="#" class="user_ref_name">{{$item->fullname}}</a>
                                </div>
                            </div>
                        </td>
                        <td><div class="info-account-create">{{$item->email}}</div></td>
                        <td><div class="info-account-create">{{$item->phone_number}}</div></td>
                        <td><div class="info-account-detail">
                                <div class="detail-account">
                                    <p>{{$item->coint_amount}}</p>
                                    <p>{{$item->vip_amount}} tin vip</p>
                                </div>
                            </div></td>
                        <td>
                            <div class="list-item-setting">
                                <div class="item-setting-account-customer">
                                    <img src="/frontend/images/business-account/icon/icon24.png" alt="">
                                    <a href="{{route('user.remove-reference', $item->id)}}" style="color: red;"  class="delete-alert">Xóa</a>
                                </div>
                                <div class="item-setting-account-customer">
                                    <i class="fas fa-coins"></i>
                                    <a href="#" data-toggle="modal" class="modal_share_vip_amount" data-target="#share-coint-amount">Tặng coin</a>
                                </div>
                                <div class="item-setting-account-customer">
                                    <i class="fas fa-archive"></i>
                                    <a href="#" data-toggle="modal" class="modal_share_vip_amount" data-target="#share-vip-amount">Tặng Tin Vip</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="bottom-account-business">
            <div class="row">
                <div class="col-6">

                </div>
                <div class="col-6 pagenate-bottom">
                    {{ $refs->render('user.page.pagination') }}

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="share-vip-amount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content lent-coin">
                <div class="modal-header lent-coin">
                    <h5 class="modal-title" id="exampleModalLabel">Tặng tin Vip</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('user.share-vip-amount')}}" class="form form-chat form-popup" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Tin Vip hiện có</label>
                            <input type="text" class="form-control" value="{{$current_balance->vip_amount}}">
                        </div>
                        <div class="form-group">
                            <label for="">Sử dụng từ </label>
                            <input type="text" class="form-control" value="{{vn_date($current_balance->package_from_date)}}">
                        </div>
                        <div class="form-group">
                            <label for="">Sử dụng đến </label>
                            <input type="text" class="form-control" value="{{vn_date($current_balance->package_to_date)}}">
                        </div>
                        <div class="form-group">
                            <label for="">Chia sẻ cho tài khoản liên kết</label>
                            <input type="text" class="form-control user_ref_info" >
                        </div>
                        <div class="form-group">
                            <label for="">Số tin vip chia sẻ</label>
                            <input type="number" name="share_vip_amount" class="form-control" value="{{old('share_vip_amount')??0}}" min="1"required>
                            {{show_validate_error($errors, 'share_vip_amount')}}
                            <input type="text" name="user_ref_share_vip" hidden>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn  btn-success " value="Chia sẻ">
                        <button type="button" class="btn btn-cancel btn-danger" data-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="share-coint-amount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content lent-coin">
                <div class="modal-header lent-coin">
                    <h5 class="modal-title" id="exampleModalLabel">Tặng tin Vip</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('user.share-coin-amount')}}" class="form form-chat form-popup" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Coin hiện có</label>
                            <input type="text" class="form-control" value="{{auth()->guard('user')->user()->coin_amount}}">
                        </div>
                        <div class="form-group">
                            <label for="">Chia sẻ cho tài khoản liên kết</label>
                            <input type="text" class="form-control user_ref_info" >
                        </div>
                        <div class="form-group">
                            <label for="">Số coin chia sẽ</label>
                            <input type="number" name="share_coin_amount" class="form-control" value="{{old('share_coin_amount')??0}}" min="1"required>
                            {{show_validate_error($errors, 'share_coin_amount')}}
                            <input type="text" name="user_ref_share_vip" hidden>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn  btn-success " value="Chia sẻ">
                        <button type="button" class="btn btn-cancel btn-danger" data-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
