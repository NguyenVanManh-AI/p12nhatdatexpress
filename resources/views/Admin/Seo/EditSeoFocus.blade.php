@extends('Admin.Layouts.Master')
@section('Title', 'Seo chuyên mục tiêu điểm')
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

<h4 class="text-center font-weight-bold mt-5 mb-4">SEO CHUYÊN MỤC TIÊU ĐIỂM</h4>

<form action="{{route('admin.seo.editfocus',[$category->id])}}" method="post" enctype="multipart/form-data">
    @csrf
<div class="row" style="margin-left:5px">
    <div class="col-md-12 col-lg-6">
        <label for=""> Tiêu đề</label>
        <input class="form-group form-control" type="text" value="{{$category->meta_title}}"  name="meta_title" placeholder="Tiêu đề">
    </div>
    <div class="col-md-12 col-lg-6">
        <label for=""> Từ khóa</label>
        <input class="form-group form-control" type="text" value="{{$category->meta_key}}"  name="meta_key" placeholder="Từ khóa">
    </div>
</div>    
<div class="row" style="margin-left:5px">
    <div class="col-md-12 col-lg-6">
        <label for=""> Mô tả</label>
        <input class="form-group form-control" type="text" value="{{$category->meta_desc}}"  name="meta_desc" placeholder="Mô tả">
    </div>
    <div class="col-md-12 col-lg-6">
        <label  for="">Đường dẫn</label>
        <input class="form-group form-control" type="text" value= "{{$category->group_url}}"  name="group_url" placeholder="Đường dẫn">
    </div>
</div>   
<div class="text-center">
<button type="submit" class="btn bg-success"><i class="fas fa-plus-circle"></i> Cập nhật</button>
</div> 
</form>
@endsection