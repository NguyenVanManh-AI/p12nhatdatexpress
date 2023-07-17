@extends('Admin.Layouts.Master')
@section('Title', 'Seo trang chủ')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
    <style>
        .form{
            display: block;
            width: 100%;
            height: 100px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: rgba(196, 196, 196, 0.329);
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 0;
        }

    </style>
@endsection
@section('Content')

<h4 class="text-center font-weight-bold mt-5 mb-4">SEO TRANG CHỦ</h4>

<form action="{{route('admin.seo.edit',[$home->id])}}" method="post" enctype="multipart/form-data">
    @csrf
<div class="row" style="margin-left:5px">
    <div class="col-md-12 col-lg-6">
        <label for=""> Tiêu đề</label>
        <input class="form-group form-control" type="text" value="{{$home->meta_title}}"  name="meta_title" placeholder="Tiêu đề">
    </div>
    <div class="col-md-12 col-lg-6">
        <label for=""> Từ khóa</label>
        <input class="form-group form-control" type="text" value="{{$home->meta_key}}"  name="meta_key" placeholder="Từ khóa">
    </div>
    <div  class="col-md-12 col-lg-6"  >
        <label for="" style=""> Mô tả</label>
        <textarea  class="form " name="meta_desc" cols="30" rows="10" value="{{$home->meta_desc}}" placeholder="Mô tả" ></textarea>
    </div>
    <div class="col-md-12 col-lg-6">
        <label  for="">Đường dẫn</label>
        <input class="form-group form-control" type="text" value= "{{$home->home_url}}"  name="home_url" placeholder="Đường dẫn">
        <div class="">
            <button type="submit" style="margin-top: 2px" class="btn bg-success"><i class="fas fa-plus-circle"></i> Cập nhật</button>
        </div>
    </div>
</div> 
</form>
@endsection