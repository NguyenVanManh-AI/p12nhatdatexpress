@extends('Home.Layouts.Master')
@section('Title',$group->meta_title??'Tiêu điểm | Nhà đất express')
@section('Keywords',$group->meta_key??'Tiêu điểm | Nhà đất express')
@section('Description',$group->meta_desc??'Tiêu điểm | Nhà đất express')
@section('Image',asset($group->image_banner??'frontend/images/home/image_default_nhadat.jpg'))

@section('Content')
    <div class="page-focus focus-page">
        <x-home.focus.high-light :group="$group"/>

        <section class="focus-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-7-3">
                        <div class="content-main">
                            {{-- <div class="section-title">
                                <h2 class="title">{{$group->group_name}}</h2>
                            </div> --}}
                            <div>
                                <x-home.focus.property />
                                <x-home.focus.furniture />
                                <x-home.focus.fengshui />
                                <x-home.focus.knowledge />
                            </div>
                            <br>
                        </div>
                    </div>
                    <div class="col-md-3-7 pr-0">
                        <div class="sidebar-right">

                            <!-- search -->
                            <div class="widget widget-height widget-search c-mb-10">
                                <div class="widget-title">
                                    <h3 class="text-center">Tìm kiếm</h3>
                                </div>
                                <form action="" class="search-form" method="get">
                                    <input type="text" name="keyword" placeholder="Nhập từ khóa" value="{{request('keyword')}}">
                                    <button type="submit" class="btn-search"><img src="{{asset('frontend/images/sidebar/search.png')}}" alt=""></button>
                                </form>
                            </div>
                            <!-- //search -->
                            <x-home.event />
                            <x-home.focus.most-viewed />
                            <x-home.fanpage />
                            <x-home.exchange/>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('Style')

@endsection

@section('Script')

@endsection
