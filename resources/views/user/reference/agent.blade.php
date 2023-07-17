@extends('user.layouts.master')
@section('content')
    <div class="account-link">
        @if(auth()->guard('user')->user()->user_link_id)
            <p class="title-link">Tài khoản đang liên kết với</p>
            <span class="title-account">{{$reference->fullname}}</span>

            <div class="button-link">
                @if(auth()->guard('user')->user()->is_link_confirm == 0)
                    <a href="{{route('user.remove-reference')}}" class="btn btn-danger delete-alert">Xóa Liên kết</a>
                    <a href="{{route('user.accept-reference')}}" class="btn btn-success accept-alert">Đồng ý liên kết</a>
                @else
                    <a href="{{route('user.remove-reference')}}" class="btn btn-danger delete-alert">Xóa Liên kết</a>
                @endif
            </div>
        @else
            <p class="title-link">Tài khoản không liên kết với doanh nghiệp</p>

        @endif
    </div>
@endsection
