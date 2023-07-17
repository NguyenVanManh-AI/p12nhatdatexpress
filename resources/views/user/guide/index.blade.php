@extends('user.layouts.master')
@section('title', 'Hướng dẫn')
@section('css')
    <style>
        .user-help-title {
            background-color: #2484ce;
        }
        .user-help-title a {
            color: white !important;
        }
    </style>
@endsection
@section('content')
    <div class="p-2 instruct">
        <form method="get" action="{{route('user.guide')}}">
            <div class="row search">
                <div class="col-12 col-md-4 help center">
                    <h4 class="title-tutorial">Trung tâm trợ giúp</h4>
                </div>
                <div class="col-12 col-md-8 tutorial search p-0 pr-1">
                    <div class="container-4">
                        <span class="text-center"><i class="fa fa-search"></i></span>
                        <input value="{{request()->input('title')}}" type="search" name="title" id="search-one" class="" placeholder="Tìm Kiếm" />
                    </div>
                </div>
                <div class="col-12 mt-3 col-md-1 d-none justify-content-center align-items-center">
                    <button type="submit" class="btn btn-express-search"> <i class="fa fa-search" aria-hidden="true"></i> Tìm</button>
                </div>
            </div>
        </form>
        <div class="tutorial-item-content scroll-tutorial-content">
            <div class="row">
                @foreach($guides as $item)
                    <div class="col-12 col-sm-6 col-md-4 tutorial-item">
                        <div class="image-advisor">
                            <a href="{{route('user.guide-detail', $item->guide_url)}}"><img src="{{asset($item->image_url??SystemConfig::USER_GUIDE_BANNER)}}" alt=""></a>
                            <div class="user-help-title">
                                <a href="{{route('user.guide-detail', $item->guide_url)}}" >{{$item->guide_title}}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="table-pagination alpha">
                <div class="left"></div>
                <div class="right">
                    {{ $guides->render('user.page.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection
