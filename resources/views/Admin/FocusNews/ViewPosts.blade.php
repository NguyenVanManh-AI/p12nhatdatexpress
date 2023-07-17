@extends('Admin.Layouts.Master')
@section('Title', 'Xem tiêu điểm| Tiêu điểm')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
    <style>
        /*Dropdown*/
        .dropdown-custom {
            background-color: #337ab7;
            border-color: #2e6da4;
            color: #fff;
        }

        .dropdown-custom:hover {
            color: white;
            background-color: #286090;
            border-color: #204d74;
        }

    </style>
@endsection
@section('Content')
    <section>
        <div class="row m-0 p-3">
            <ol class="breadcrumb mt-1">

                <li class="list-box px-2 pt-1 active check">
                    <a href="{{route('admin.focus.list')}}">
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>

                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 pt-1 ml-1">
                    <a href="{{route('admin.focus.listtrash')}}">
                        Thùng rác
                    </a>
                </li>

                <li class="ml-2 phay">
                    /
                </li>
                <li class="add px-2 pt-1 ml-1 check">
                    <a href="{{route('admin.focus.new')}}">
                        <i class="fa fa-edit mr-1"></i>Thêm
                    </a>
                </li>

            </ol>
        </div>
        <!-- ./Breadcrumb -->
        <div class="" style="height: 100vh;">
            <div class="row">
                <div  class="col-md-12 col-lg-5 ml-3">
                    <div  class="image-top">
                        <img
                            src="{{url('system/img/news/'.$new->image_url)}}"
                            width="450" height="400">
                    </div>

                </div>
                <div   class="col-md-12 col-lg-6 ml-3">
                    <h2>
                        <span class="name-text">{{$new->news_title}} </span>
                    </h2>
                    <h5  class="js-admin-tiny-textarea"  name="" value="" style="width: 100%;height: 300px">
                        {!!$new->news_content!!}
                    </h5>
                </div>
            </div>
        </div>
    </section>
@endsection
