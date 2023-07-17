@extends('user.layouts.master')
@section('title', 'Thông tin tài khoản')
@section('css')
    <link rel="stylesheet" href="{{asset('frontend/user/css/component-custom-switch.min.css')}}">
@endsection
@section('content')
    <div class="info-account-done position-relative">
        <div class="row">
            <div class="col-md-4 left">
                <div class="account-done-left">
                    <x-user.index.user-info-status />
                    <x-user.index.user-info />
                </div>
            </div>
            <div class="col-md-8 right position-static">
                <div class="info-account-action">
                    <x-user.index.personal-info />
                    <x-user.index.social-link />
                    <x-user.index.deploying-project />
                    <x-user.index.user-log />
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('user/js/account-infor.js')}}"></script>
@endsection
