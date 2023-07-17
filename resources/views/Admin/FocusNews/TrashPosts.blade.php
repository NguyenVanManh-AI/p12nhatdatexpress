@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác | Tiêu điểm')
@section('Style')
@endsection
@section('Content')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
    <style type="text/css">

    </style>
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1">
            @if($check_role == 1  ||key_exists(4, $check_role))

            <li class="add px-2 pt-1  ">
                <a href="{{route('admin.focus.list')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
            @endif
        </ol>
    </div>
    <form action="{{route('admin.focus.list')}}" method="get">
        <div class="row m-0 px-3 pb-3">
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 p-0 pl-lg-2">
                <div  class="search-reponsive pr-4">
                    <input class="form-control required" type="text" value="{{(isset($_GET['key']))?$_GET['key']:""}}" name="key" placeholder="Từ khóa">
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-2 col-lg-2 p-0" >
                <div class="search-reponsive">
                    <select class="form-control select2" name="author" style="width: 100%;height: 34px !important;">
                        <option selected="selected" value="">Tác giả</option>
                        @foreach($author as $item)
                            <option {{(isset($_GET['author'])&& $_GET['author']== $item->id)?"selected":""}} value="{{$item->id}}">{{$item->admin_fullname}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-3 col-lg-3 p-0" >
                <div class="search-reponsive px-4">
                    {{--        {{date('Y-m-d',$_GET['start_day'])}}--}}
                    <input class="form-control float-right" value="{{(isset($_GET['start_day'])&& $_GET['start_day']!="")?$_GET['start_day']:''}}"  name="start_day" type="date" placeholder="Ngày bắt đầu" >
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-3 col-lg-3 p-0 " >
                <div class="search-reponsive px-4 mt-3 mt-lg-0 mt-md-0">
                    <input class="form-control float-right" value="{{(isset($_GET['end_day'])&& $_GET['end_day']!="")?$_GET['end_day']:''}}" name="end_day" type="date"  placeholder="Ngày kết thúc" >
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 p-0" >
                <div class="row pt-3 pl-lg-2 pr-lg-1">
                    <div class="pr-lg-3 col-12 col-sm-12 col-md-6 col-lg-6">
                        <select class="form-control select2" name="group" style="width: 100%;height: 34px !important;">
                            <option selected="selected" value="">Chuyên mục</option>
                            @foreach($group as $item)
                                <option {{(isset($_GET['group'])&& $_GET['group']== $item->id)?"selected":""}} value="{{$item->id}}">{{$item->group_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 pr-lg-4 mt-3 pr-md-4 mt-lg-0 mt-md-0">
                        <select class="form-control select2" name="news_type" style="width: 100%;height: 34px !important;">
                            <option  {{((isset($_GET['news_type'])) && ($_GET['news_type'] !=0))?"selected":""}} value="">Loại bài viết</option>
                            <option  {{((isset($_GET['news_type'])) && ($_GET['news_type'] == 0))?"selected":""}} value="0">Thường</option>
                            <option  {{((isset($_GET['news_type']))&& ($_GET['news_type'] == 1))?"selected":""}} value="1">Nổi bật</option>
                            <option  {{((isset($_GET['news_type']))&& ($_GET['news_type'] == 2))?"selected":""}} value="2">Quảng cáo</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="search-reponsive col-12 col-sm-12 col-md-2 col-lg-2 pr-1 pl-0 mt-3">
                <button type="submit" class="search-button btn btn-primary w-100" style="height: 38px;line-height: 16px"><i class="fa fa-search mr-2" aria-hidden="true"></i>Tìm kiếm</button>
            </div>
        </div>
    </form>


    <h4 class="text-center font-weight-bold mt-2 mb-4">THÙNG RÁC</h4>
    <!-- Main content -->
    <section class="content hiden-scroll">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-custom" id="table" >
                            <thead>
                            {{--            fix giao diện thead--}}
                            <tr>
                                <th scope="row" class=" active" style="width: 3%">
                                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                                </th>
                                <th scope="col" style="width: 4%">STT</th>
                                <th scope="col" style="width: 14%">Hình ảnh</th>
                                <th scope="col"  style="width: 25%">Tiêu đề</th>
                                <th scope="col" style="width: 14%">Tác giả</th>
                                <th scope="col" style="width: 12%">
                                    Chuyên mục
                                </th>
                                <th scope="col" style="width: 11%">Ngày đăng</th>
                                <th scope="col" style="width: 25%">Cài đặt</th>
                            </tr>
                            {{--            -- end fix --}}
                            </thead>
                            <tbody>
                            <form action="{{route('admin.focus.trashlist')}}" id="trash_list" method="post">
                                <input type="hidden" name="action" id="action_list" value="">
                                @csrf
                                @foreach($news as $item)
                                    <tr>
                                        <td class="active">
                                            <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}" />
                                            <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}" />

                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td >
                                            <div class="image-box-main">
                                                <div class="image-top">
                                                    <img style="max-width: 100px;object-fit: cover" src="{{url($item->image_url)}}" class="h-100">
                                                </div>
                                            </div>
                                        </td>
                                        <td >
                                            <span class="name-text">{{$item->news_title}}</span>
                                            <div class="review-box-main pt-2">
                                                <div class="box-review d-flex">
                                                    <i class="fas fa-edit w-25"></i>
                                                    <span>Số chữ: <span >{{strlen(strip_tags($item->news_content))}}</span> </span>
                                                </div>
                                                <div class="view-box d-flex">
                                                    <i class="fas fa-signal w-25 mt-1"></i>
                                                    <span style="margin-top: 2px">Lượt xem: <span>{{$item->num_view}}</span></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="">
                                            <span style="{{($item->admin_deleted ==1)?"color: grey":""}}">{{$item->admin_fullname}}</span>
                                        </td>
                                        <td class="">
                                            <span style="{{($item->group_deleted ==1)?"color: grey":""}}">{{$item->group_name}}</span>
                                        </td>
                                        <td class="">
                                            <span>{{date('d/m/Y',$item->created_at)}}</span>
                                        </td>
                                        <td class="p-0">
{{--                                            <div class="float-left ml-2 pr-5">--}}
{{--                                                <i class="icon-setup fas fa-file-alt mr-2 mb-1"></i>--}}
{{--                                                <a href="" class="text-primary ">Xem bài</a>--}}
{{--                                            </div>--}}
                                            @if($check_role == 1  || key_exists(6, $check_role))

                                            <div class="float-left ml-2 pr-5">
                                                <i class="icon-setup fas fa-cog mr-2 mb-1" ></i>
                                                <a data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}" class="text-primary untrash_item" style="cursor: pointer">Khôi phục</a>
                                            </div>
                                            @endif
                                            <br>
                                            @if($check_role == 1  ||key_exists(7, $check_role))

                                            <div class="float-left ml-2 pr-5">
                                                <i class="icon-setup fas fa-times mr-2 mb-1" ></i>
                                                <a data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}" class="delete_item"  style="color:#ff0000;cursor: pointer">Xóa</a>
                                            </div>
                                            @endif
                                            <br>
                                            <div style="clear: left"></div>
                                        </td>
                                    </tr>
                            @endforeach
                            </form>
                            </tbody>
                        </table>

                    </div>
                    <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
                        <div class="text-left d-flex align-items-center">
                            <div class="manipulation d-flex mr-4">
                                <img src="image/manipulation.png" alt="" id="btnTop">
                                <div class="btn-group ml-1">
                                    <button type="button" class="btn dropdown-toggle dropdown-custom"
                                            data-toggle="dropdown"
                                            aria-expanded="false" data-flip="false" aria-haspopup="true">
                                        Thao tác
                                    </button>
                                    <div class="dropdown-menu">
                                        @if($check_role == 1  ||key_exists(6, $check_role))
                                            <a class="dropdown-item unToTrash" type="button" href="javascript:{}">
                                                <i class="fas fa-undo-alt bg-primary p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Khôi phục
                                            </a>
                                        @else
                                            <p class="dropdown-item m-0 disabled">
                                                Bạn không có quyền
                                            </p>
                                        @endif

                                    </div>
                                </div>

                            </div>
                            <div class="display d-flex align-items-center mr-4">
                                <span>Hiển thị:</span>
                                <form method="get" action="{{route('admin.focuscategory.listtrash')}}">
                                    <select name="items" class="custom-select" onchange="this.form.submit()">
                                        <option {{(isset($_GET['items'])&& $_GET['items']==10)?"selected":""}}  class=""
                                                value="10">10
                                        </option>
                                        <option
                                            {{(isset($_GET['items'])&& $_GET['items']==20)?"selected":""}} value="20">20
                                        </option>
                                        <option
                                            {{(isset($_GET['items'])&& $_GET['items']==30)?"selected":""}} value="30">30
                                        </option>
                                    </select>
                                </form>
                            </div>
                            <div>
                                @if($check_role == 1  ||key_exists(4, $check_role))
                                    <a href="{{route('admin.focus.list')}}" class="btn btn-primary">
                                        Quay lại
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng: {{$news->total()}}
                                items
                            </div>
                            @if($news)
                                {{ $news->render('Admin.Layouts.Pagination') }}
                            @endif
                        </div>

                    </div>
{{--                    <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5" >--}}
{{--                        <div class="text-left d-flex align-items-center" >--}}
{{--                            <div class="manipulation d-flex mr-4">--}}
{{--                                <img src="{{ asset("system/images/icons/manipulation.png")}}">--}}
{{--                                <select id="giatien" name="action"  class="select-button action_list">--}}
{{--                                    @if($check_role == 1  ||key_exists(6, $check_role))--}}
{{--                                    <option selected class="test" value="thaotac">Thao tác</option>--}}
{{--                                    <option value="restore" class="trash_list">Khôi phục</option>--}}
{{--                                    @endif--}}
{{--                                </select>--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="float-left mb-2 w150">--}}
{{--                            <div class=" d-flex mr-4">--}}
{{--                                <span class="mr-2 ml-3 mt-1">Hiển thị:</span>--}}
{{--                                <form action="{{route('admin.focus.listtrash')}}" method="get">--}}
{{--                                    <select name="items" class="display-box" onchange="this.form.submit()">--}}
{{--                                        <option  {{(isset($_GET['items'])&& $_GET['items']==10)?"selected":""}} value="10">10</option>--}}
{{--                                        <option  {{(isset($_GET['items'])&& $_GET['items']==20)?"selected":""}} value="20">20</option>--}}
{{--                                        <option  {{(isset($_GET['items'])&& $_GET['items']==30)?"selected":""}} value="30">30</option>--}}
{{--                                    </select>--}}
{{--                                </form>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                        <div class="float-left mb-2 w200">--}}
{{--                            <div class="manipulation d-flex mr-4 pt-1">--}}
{{--                                @if($check_role == 1  ||key_exists(4, $check_role))--}}

{{--                                <span><a href="{{route('admin.focus.list')}}">Quay lại</a></span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="float-left mb-2" style="width: calc(100% - 820px);"></div>--}}
{{--                        <div class="float-left mb-2 w370">--}}
{{--                            <div class="d-flex align-items-center">--}}
{{--                                <div class="count-item" style="margin-top: 0px">Tổng cộng: {{$news->total()}} items</div>--}}
{{--                                <div class="count-item-reponsive" style="display: none">{{$news->count()}} items</div>--}}
{{--                                <div>--}}
{{--                                    @if($news)--}}
{{--                                        {{ $news->render('Admin.Layouts.Pagination') }}--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="clear-both"></div>--}}
                </div>

            </div>
        </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('Script')
    <script type="text/javascript">
        $('#tieudiem').addClass('active');
        $('#danhsachbaiviet').addClass('active');
        $('#nav-tieudiem').addClass('menu-is-opening menu-open');
    </script>
    <script>
        $('.untrash_item').click(function (){
            id = $(this).data('id');
            created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Xác nhận khôi phục tiêu điểm',
                text: "Nhấn có sẽ tiến hành khôi phục",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href= "/admin/focus-news/untrash-item/"+id+"/"+created_by;
                }
            })
        });
            $('.delete_item').click(function (){
            id = $(this).data('id');
                created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Bạn có chắc chắn xóa tiêu điểm',
                text: "Sau khi xóa thì không thể khôi phục",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href= "/admin/focus-news/delete-item/"+id+"/"+created_by;
                }
            })
        });

        $('.unToTrash').click(function (){
            // id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: "Sau khi đồng ý sẽ tiến hành khôi phục !!!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("restore") ;
                    $('#trash_list').submit();
                }
                else{
                    $('.action_list').val("thaotac");
                }
            })
        });
    </script>

    <script src="js/table.js"></script>

@endsection
