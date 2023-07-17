@extends('Admin.Layouts.Master')
@section('Title', 'Bài viết hướng dẫn | Hướng dẫn')
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
    <!-- Breadcrumb -->
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1">
            @if($check_role == 1  ||key_exists(4, $check_role))
            <li class="list-box px-2 pt-1 active check">
                <a href="{{route('admin.guide.list')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
            @endif
            @if($check_role == 1  ||key_exists(8, $check_role))
            <li class="phay ml-2">
                /
            </li>
            <li class="recye px-2 pt-1 ml-1">
                <a href="{{route('admin.guide.trash')}}">
                    Thùng rác
                </a>
            </li>
            @endif
            @if($check_role == 1  ||key_exists(1, $check_role))
            <li class="ml-2 phay">
                /
            </li>
            <li class="add px-2 pt-1 ml-1 check">
                <a href="{{route('admin.guide.add')}}">
                    <i class="fa fa-edit mr-1"></i>Thêm
                </a>
            </li>
            @endif

        </ol>
    </div>
    <!-- ./Breadcrumb -->

    <!-- Filter -->
    <div class="container-fluid">
        <form action="{{route('admin.guide.list')}}" method="GET" >

        <div class="row">
            <div class="col-md-12 col-lg-6">
                <input class="form-group form-control" type="text"  name="keyword" value="{{(isset($_GET['keyword']) && $_GET['keyword']!="")?$_GET['keyword']:""}}" placeholder="Từ khóa">
            </div>
            <div class="search-reponsive col-md-12 col-lg-3">
                <input class="form-group form-control start_day" id="start_day" name="date_start" value="{{(isset($_GET['date_start']) && $_GET['date_start']!="")?$_GET['date_start']:""}}" type="date" placeholder="Từ ngày">
            </div>
            <div class="search-reponsive col-md-12 col-lg-3">
                <input class="form-group form-control end_day" id="end_day" name="date_end" value="{{(isset($_GET['date_end']) && $_GET['date_end']!="")?$_GET['date_end']:""}}" type="date" placeholder="Từ ngày">
                {{-- <small class="text-danger error-message-custom " style="display: none" id="messageerror">
                    Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu</small> --}}
            </div>
        </div>
        {{-- <div class="search-reponsive col-12 col-sm-12 col-md-6 col-lg-6">
            <div class="row m-0">
              <div class="search-reponsive col-md-12 col-lg-3">
                <input class="form-group form-control" id="start_day" name="date_start" value="{{(isset($_GET['date_start']) && $_GET['date_start']!="")?$_GET['date_start']:""}}" type="date" placeholder="Từ ngày">
            </div>
            <div class="search-reponsive col-md-12 col-lg-3">
              <input class="form-group form-control mb-0" id="end_day" name="date_end" value="{{(isset($_GET['date_end']) && $_GET['date_end']!="")?$_GET['date_end']:""}}" type="date" placeholder="Từ ngày">
              <small class="text-danger error-message-custom " style="display: none" id="messageerror">
               Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu</small>
            </div>
          </div>
        </div>
       --}}

        <div class="row">
            <div class="col-md-12 col-lg-3">
                <select name="author" class="form-group form-control select2" style="width: 100%;height: 34px !important;">
                    <option value="" selected="selected">Tác giả</option>
                     @foreach ($auth as $item )
                        <option {{(isset($_GET['author'])&& $_GET['author']== $item->id)?"selected":""}}  value="{{$item->id}}">{{$item->admin_fullname}}</option>
                     @endforeach
                </select>
            </div>
            <div class="col-md-12 col-lg-3">
                <button id="search_button" class="search-button btn btn-primary w-100" style="height: 38px;line-height: 16px"><i class="fa fa-search mr-2" aria-hidden="true"></i>Tìm kiếm
                </button>
            </div>
        </div>
    </form>

</div>
    <!-- ./Filter -->

    <h4 class="text-center font-weight-bold mt-5 mb-4">DANH SÁCH TIN HƯỚNG DẪN</h4>
    <!-- Main content -->
    <section class="content mb-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-custom" id="table">
                            <thead>
                            <tr>
                                <th scope="row" class=" active" style="width: 3%">
                                    <input type="checkbox" class="select-all checkbox" name="select-all"/>
                                </th>
                                <th scope="col" style="width: 4%">STT</th>
                                <th scope="col" style="width: 14%">Hình ảnh</th>
                                <th scope="col" style="width: 28%">Tiêu đề</th>
                                <th scope="col" style="width: 14%">Tác giả</th>
                                {{-- <th scope="col" style="width: 12%"> --}}
                                    {{-- <div class="dropdown">
                                        <button class="dropdow dropdown-toggle font-weight-bold" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false" data-offset="-35,2">
                                            Chuyên mục
                                        </button>
                                        <div class="dropdown-menu shadow-lg text-center"
                                             aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div> --}}
                                {{-- </th> --}}
                                <th scope="col" style="width: 11%">Ngày đăng</th>
                                <th scope="col" style="width: 22%;">Cài đặt</th>
                            </tr>
                            </thead>
                            <tbody>
                                <form id="formtrash" action="{{route('admin.guide.trashlist')}}" method="POST">
                                @csrf
                                @foreach ( $list_Guide as $item )
                                <tr>
                                    <td class="active">
                                        <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                                        <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}" />
                                    </td>
                                    <td>{{$item->id}}</td>
                                    <td>
                                        <div class="image-box-main">
                                            <div  class="image-top">
                                                <img
                                                    src="{{asset($item->image_url ?? '/frontend/images/home/image_default_nhadat.jpg')}}"
                                                    class="h-100">
                                            </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center flex-column flex-fill">
                                            <span class="name-text">{{$item->guide_title}} </span>
                                            <div class="review-box-main mt-3">

                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span>{{$item->admin_fullname}}</span>
                                    </td>
                                    {{-- <td>
                                        <span>Nhà đất bán</span>
                                    </td> --}}
                                    <td>
                                        <span>{{date('d/m/Y',$item->created_at)}}</span>
                                    </td>
                                    <td>
                                        <div class="row flex-column justify-content-center pl-3">
                                            <div class="text-left mb-2">
                                                <i class="icon-setup fas fa-file-alt mr-2"></i>
                                                <a href="{{route('admin.guide.view',$item->id)}}" class="text-primary ">Xem bài</a>
                                            </div>
                                            @if($check_role == 1  ||key_exists(2, $check_role))
                                            <div class="text-left mb-2">
                                                <i class="icon-setup fas fa-cog mr-2"></i>
                                                <a href="{{route('admin.guide.edit',[$item->id,\Crypt::encryptString($item->created_by)])}}" class="text-primary ">Chỉnh sửa</a>
                                            </div>
                                            @endif
                                            @if($check_role == 1  ||key_exists(5, $check_role))
                                            <div class="text-left mb-2">
                                                <i class="icon-setup fas fa-times mr-2"></i>
                                                <a  class="delete " data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}" style="color:#ff0000;cursor: pointer;">Xóa</a>
                                            </div>
                                            @endif
                                            {{-- <div class="text-left mb-2">
                                                <i class="icon-setup fa fa-arrow-circle-up mr-2"></i>
                                                <a href="" class="text-primary ">Nâng cấp</a>
                                            </div> --}}
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex align-items-center justify-content-between my-4">
                        <div class="d-flex align-items-center">
                            <div class="d-flex">
                                <img src="image/manipulation.png" alt="" id="btnTop">
                                <div class="btn-group ml-1">
                                    <!-- data-flip="false" -->
                                    <button type="button" class="btn dropdown-toggle dropdown-custom"
                                            data-toggle="dropdown" aria-expanded="true" data-flip="false"
                                            aria-haspopup="true">
                                        Thao tác
                                    </button>
                                    <div class="dropdown-menu">
                                @if($check_role == 1  ||key_exists(5, $check_role))
                                        <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
                                            <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"
                                               style="color: white !important;font-size: 15px"></i>Thùng rác
                                               <input type="hidden" name="action" value="trash">
                                        </a>
                                        @else
                                        <p class="dropdown-item m-0 disabled">
                                            Bạn không có quyền
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                            <div class="d-flex align-items-center justify-content-between mx-4">
                                <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                                <form action="{{route('admin.guide.list')}}" method="GET">
                                    <label class="select-custom2">
                                        <select id="paginateNumber" name="items" onchange="this.form.submit()">
                                            <option
                                                @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">
                                                10
                                            </option>
                                            <option
                                                @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">
                                                20
                                            </option>
                                            <option
                                                @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">
                                                30
                                            </option>
                                        </select>
                                    </label>
                                </form>
                            </div>
                            @if($check_role == 1  ||key_exists(8, $check_role))
                            <div class="d-flex flex-row align-items-center view-trash">
                                <i class="far fa-trash-alt mr-2"></i>
                                <div class="link-custom">

                                    <a href="{{route('admin.guide.trash')}}"><span style="color: #347ab6">Xem thùng rác</span>
                                        <span class="badge badge-pill badge-danger trashnum"
                                              style="font-weight: 500">{{$count_trash}}</span>
                                    </a>

                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng: {{$list_Guide->total()}} items</div>
                            @if($list_Guide)
                     {{$list_Guide->render('Admin.Layouts.Pagination') }}
                            @endif
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>

    <!-- /.content -->
@endsection

@section('Script')
    <script src="js/table.js"></script>
    <script type="text/javascript">
        $('#huongdan').addClass('active');
        $('#nav-huongdan').addClass('menu-is-opening menu-open');
    </script>

<script>
    $('.delete').click(function () {
        var id = $(this).data('id');
        var created_by = $(this).data('created_by');
        Swal.fire({
            title: 'Xác nhận xóa',
            text: "Sau khi xóa sẽ chuyển vào thùng rác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/admin/guide/delete/" + id+"/"+created_by;
            }
        });
    });
    $('.moveToTrash').click(function () {
        // var id = $(this).data('id');
        Swal.fire({
            title: 'Xác nhận xóa',
            text: "Sau khi xóa sẽ chuyển vào thùng rác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {

                $('#formtrash').submit();

            }
        });
    });
</script>
{{-- <script>
    $("#search_button").on("submit",function(e){
      var start = $('#start_day').val();
      var end = $('#end_day').val();
      if(start!="" && end !=""){
        var daystart = new Date(start);
      var dayend = new Date(end);
        if( daystart.getTime() > dayend.getTime()){
            $("#messageerror").css('display','block');
            e.preventDefault();
        }
        else{
          $("#messageerror").css('display','none');

        }

      }


      // var today = new Date(start);
      // alert(today);
   });
   </script> --}}
   <script>
    $(document).ready(function(){
        $('.start_day').change(function (){
            $('.end_day').attr('min',$('.start_day').val());
        });
        $('.end_day').change(function (){
            $('.start_day').attr('max',$('.end_day').val());
        });
    });
</script>


@endsection
