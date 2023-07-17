@extends('user.layouts.master')
@section('title', 'Hướng dẫn')
@section('css')
    <style>
        .guide {
            width: 100%;

        }
    </style>
@endsection
@section('content')
    <div class="p-2">
        <div class="col-12 bg-white p-3 mb-2">
            <h5>Chi tiết khuyến mãi</h5>
        </div>
        <div class="col-12 bg-white p-3">
            <div class="mail-title ">
                <p class="text-bold">{{$guide->guide_title}}</p>
            </div>
            <div class="mail-content border-bottom">
                {!! $guide->guide_content !!}
            </div>
            <div class="text-right pt-2">
                <a href="{{URL::previous()}}" class="btn btn-express-search">Quay về</a>
            </div>
        </div>
    </div>
@endsection
