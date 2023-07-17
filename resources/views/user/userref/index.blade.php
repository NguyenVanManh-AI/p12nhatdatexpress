@extends('user.layouts.master')
@section('content')
    <div class="user-introduction">
        <div class="top-intro">
            <div class="link-intro">
                <div class="title-manager text-center">
                    <h4 class="color-white">Link giới thiệu</h4>
                </div>
                <div class="content">
                    <div class="link">
                        <a href="{{$user_ref_link}}" class="link-need-copy">{{$user_ref_link}}</a>
                    </div>
                    <p>Tặng <span class="color-red">15%</span> giá trị Coin khi người được giới thiệu nạp Coin</p>
                    <div class="group-introduction">
                        <a href="#" class="btn-post btn copy-link-account"><i class="far fa-copy"></i><span>Sao chép</span></a>
                        <a href="#" class="btn-login"><i class="fas fa-share"></i><span>Chia sẻ</span></a>
                    </div>
                </div>
            </div>
            <div class="count-people">
                <h4 class="color-white">Giới thiệu người dùng</h4>
                <h1 class="color-white center">{{$total_user_ref}}</h1>
            </div>
            <div class="count-coin">
                <h4 class="color-white">Số coin đã nhận</h4>
                <h1 class="color-white center">{{$total_coin_ref}}</h1>
            </div>
        </div>
        <div class="main-intro">
            <table class="table-introduce">
                <tr class="title">
                    <th>STT</th>
                    <th>Tên hiển thị</th>
                    <th>Ngày đăng ký</th>
                </tr>
                @foreach($user_refs as $user_ref)
                    <tr>
                        <td>{{$user_ref->id}}</td>
                        <td>{{$user_ref->fullname}}</td>
                        <td>{{vn_date($user_ref->created_at)}}</td>
                    </tr>
                @endforeach

            </table>

        </div>
        <div class="table-pagination">
            <div class="left"></div>
            <div class="right">
                {{ $user_refs->render('user.page.pagination', ['user_refs' => $user_refs]) }}
            </div>

        </div>

    </div>
@endsection
