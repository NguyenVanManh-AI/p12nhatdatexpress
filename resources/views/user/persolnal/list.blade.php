@extends('user.persolnal.master')

@if($params['user'])
    @section('title', $params['user']->getSeoTitle())
    @section('description', $params['user']->getSeoDescription() ?: data_get($home_seo_config, 'meta_desc', 'Nhà đất express'))
    @section('image', $params['user']->getSeoBanner())
@endif

@section('content')
    <x-user.persolnal.ads-page :item="$params['user']"></x-user.persolnal.ads-page>
    <div class="frame-back-1">
        <a href="/" class="item back-home">
            <i class="fas fa-home"></i>
        </a>
        <span class="scrollTop item">
            <i class="fas fa-arrow-up"></i>
        </span>
    </div>
    <main class="main">
        <x-user.persolnal.header :item="$params['user']"></x-user.persolnal.header>

        <div class="profile-content">
            <div class="container bg-unset">
                <div class="row profile-main mb-4">
                    <div class="col-md-5">
                        <div class="content-left">
                            <x-user.persolnal.info :item="$params['user']"></x-user.persolnal.info>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="flex-center h-100">
                            <div class="content-rights w-100">
                                <x-user.persolnal.contact :item="$params['user']"></x-user.persolnal.contact>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-project">

                    <div class="search-form-newslist">

                        <div class="search">

                            <div class="search-title">

                                <h2>TÌM KIẾM</h2>

                            </div>

                            <div class="search-box">

                                <form action="" id="search-tool-forms" method="get">

                                    <div class="drop-search">

                                        <div class="box">

                                            <div class="container-3">

                                                <span class="icon"><i class="fa fa-search"></i></span>

                                                <input name="keyword" type="text" id="search" value="{{request()->keyword}}" placeholder="Nhập Tìm Kiếm">

                                            </div>

                                        </div>

                                        <select name="group_parent" id="chuyenmuc">

                                            <option selected value="">Chuyên mục</option>
                                            <option {{isset($_GET['group_parent'])&& $_GET['group_parent']==2?"selected":""}} value="2">Nhà đất bán</option>
                                            <option {{isset($_GET['group_parent'])&& $_GET['group_parent']==10?"selected":""}} value="10">Nhà đất cho thuê</option>
                                            <option {{isset($_GET['group_parent'])&& $_GET['group_parent']==18?"selected":""}} value="18">Cần mua - cần thuê</option>

                                        </select>

                                        <select name="group_id" id="mohinh" class="custom-select"  >
                                            <option value="" selected >Mô hình</option>
                                        </select>

                                        <select name="priceBillion" class="">
                                            <option {{($_GET['priceBillion']??-1)== ""?"selected":""}} value="">Mức giá</option>
                                            <option {{($_GET['priceBillion']??-1)== "0-1"?"selected":""}} value="0-1">Dưới 1 tỷ</option>
                                            <option {{($_GET['priceBillion']??-1)== "1-2"?"selected":""}} value="1-2">1 đến 2 tỷ</option>
                                            <option {{($_GET['priceBillion']??-1)== "2-3"?"selected":""}} value="2-3">2 đến 3 tỷ</option>
                                            <option {{($_GET['priceBillion']??-1)== "3-4"?"selected":""}} value="3-4">3 đến 4 tỷ</option>
                                            <option {{($_GET['priceBillion']??-1)== "4-5"?"selected":""}} value="4-5">4 đến 5 tỷ</option>
                                            <option {{($_GET['priceBillion']??-1)== "5-6"?"selected":""}} value="5-6">5 đến 6 tỷ</option>
                                            <option {{($_GET['priceBillion']??-1)== "6-8"?"selected":""}} value="6-8">6 đến 8 tỷ</option>
                                            <option {{($_GET['priceBillion']??-1)== "8-10"?"selected":""}} value="8-10">8 đến 10 tỷ</option>
                                            <option {{($_GET['priceBillion']??-1)== "10-15"?"selected":""}} value="10-15">10 đến 15 tỷ</option>
                                            <option {{($_GET['priceBillion']??-1)== "15-30"?"selected":""}} value="15-30">15 đến 30 tỷ</option>
                                            <option {{($_GET['priceBillion']??-1)== "30-50"?"selected":""}} value="30-50">30 đến 50 tỷ</option>
                                            <option {{($_GET['priceBillion']??-1)== "50-0"?"selected":""}} value="50-0">Trên 50 tỷ</option>
                                        </select>

                                    </div>

                                    <div class="drop-search">
                                        <select name="province_id" id="tinhthanhpho">
                                            <option selected  value="">Tỉnh / Thành phố</option>
                                            @foreach ($provinces as $item)
                                            <option {{isset($_GET['province_id'])&&$_GET['province_id']==$item->id?"selected":""}}  value="{{$item->id}}">{{$item->province_name}}</option>
                                            @endforeach
                                        </select>

                                        <select name="district_id" id="quanhuyen">
                                            <option value="">Quận/Huyện</option>
                                        </select>

                                        <select name="num_bed">

                                            <option value="">Phòng Ngủ </option>

                                            @foreach ($rooms as $item)
                                            <option {{isset($_GET['classified_param'])&&$_GET['classified_param']==$item->id?"selected":""}}  value="{{$item->id}}">{{$item->param_name}}</option>
                                            @endforeach

                                        </select>

                                        <div class="button-search">

                                            <button type="submit" class="btn btn-success">Tìm</button>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                        <div class="sorted-by">

                            <div class="dropdown">

                                <select name="sort" onchange="filter($(this).val())">
                                    <option value="">Sắp Xếp Theo </option>
                                    <option onclick="filter('luot-xem-nhieu-nhat')" value="luot-xem-nhieu-nhat">Lượt xem nhiều nhất</option>
                                    <option value="gia-thap-nhat">Giá thấp nhất</option>
                                    <option value="gia-cao-nhat">Giá cao nhất</option>
                                    <option value="dien-tich-lon-nhat">Diện tích lớn nhất</option>
                                    <option value="dien-tich-nho-nhat">Diện tích nhỏ nhất</option>
                                </select>

                            </div>

                        </div>
                    </div>

                    <div class="buy-rent-list section">
                        <div class="row">
                            <div class="col-md-12 project-main">
                                {{-- map popup for item list --}}
                                <x-common.classified.map-popup />

                                <div class="buy-rent-list-wrapper">
                                    <div class="row">
                                        @forelse($classified as $item)
                                            <x-news.classified.item :item="$item" />
                                        @empty
                                            <x-home.classified.add-classified-button />
                                        @endforelse
                                    </div>
                                </div>
                                <div class="pagination section">
                                    {{$classified->render('Home.Layouts.Pagination')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="location_classified">
        </div>
    </main>
@endsection
@section('Style')
    <style>
        .content-project {
            margin-left: 50px;
            margin-right: 50px;
        }
        input{
            outline: none;
            border: none;
        }
        select{
            outline: none;
        }
    </style>
@endsection
@section('script')
    <script>
        $(".auto_load").hide();
        $('.location_classified').click(function (){
            viewMap($(this).data('id'));
        });
    </script>
    <script>
        $('#chuyenmuc').change(function (){
            if($('#chuyenmuc').val()!=""){
              //  alert($('#chuyenmuc').val());
                var url = "{{route('param.get_child')}}";
                var group_id = $('#chuyenmuc').val();
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    data: {
                        group_id: group_id
                    },
                    success: function (data) {
                       // alert(data);
                        $('#mohinh').html('');
                        console.log(data['group_child']);
                        $('#mohinh').append(data['group_child']);
                    }
                });
            }
            else{
                $('#mohinh').append('<option selected disabled>Mô hình</option>');
            }
        });
           $(document).ready(function (){
               var url = "{{route('param.get_child')}}";
               var group_id = "{{$_GET['group_parent']??null}}";
               $.ajax({
                   url: url,
                   type: "GET",
                   dataType: "json",
                   data: {
                       group_id: group_id
                   },
                   success: function (data) {
                       $('#mohinh').html('');
                       $('#mohinh').append(data['group_child']);
                       @if(isset($_GET['group_id'])&& $_GET['group_id']!="")
                       $('#mohinh').val("{{$_GET['group_id']}}").change();
                       @endif
                   }
               });
           });
     </script>
<script>
    $('#tinhthanhpho').change(function (){
          if($('#tinhthanhpho').val()!=""){
               var url = "{{route('param.get_district')}}";
               var group_id = $('#tinhthanhpho').val();
               $.ajax({
                   url: url,
                   type: "GET",
                   dataType: "json",
                   data: {
                      province_id: group_id
                   },
                   success: function (data) {
                       $('#quanhuyen').html('');
                       console.log(data['districts']);
                       $('#quanhuyen').append(data['districts']);
                       district_value ="<option value=''>Quận / Huyện</option>";
                       $.each(data['districts'], function (index, value) {
                         let selected = "";
                         district_value+="<option value='" + value.id + "' " + selected + " >" + value.district_name + "</option>";
                      });
                      $('#quanhuyen').append(district_value);
                   }
               });
          }
          else{
               $('#quanhuyen').html('<option selected disabled value>Quận / Huyện</option>');
             }
       });
       $(document).ready(function(){
        @if(isset($_GET['province_id']) && $_GET['province_id'] !="")
                   var province_id = "{{$_GET['province_id']}}";
                   var url = "{{route('param.get_district')}}";
                   $.ajax({
                   url: url,
                   type: "GET",
                   dataType: "json",
                   data: {
                      province_id: province_id
                   },
                   success: function (data) {
                       $('#quanhuyen').html('');
                       console.log(data['districts']);
                       $('#quanhuyen').append(data['districts']);
                       district_value ="<option value=''>Quận / Huyện</option>";
                       $.each(data['districts'], function (index, value) {
                         let selected = "";
                         let choose  = "{{$_GET['district_id']}}";
                         if(choose == value.id) selected ="selected";
                         district_value+="<option "+selected+" value='" + value.id + "'>" + value.district_name + "</option>";
                      });
                      $('#quanhuyen').append(district_value);
                   }
               });
          @endif
       });
    function filter(type){
        window.location.href = changeParamUrl('sort', type);
    }
   </script>
@endsection

