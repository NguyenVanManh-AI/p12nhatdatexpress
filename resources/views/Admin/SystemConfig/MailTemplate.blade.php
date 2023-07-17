@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý email mẫu')
@section('Style')
@endsection
@section('Content')
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1">
            @if($check_role == 1  ||key_exists(4, $check_role))

                <li class="list-box px-2 pt-1 active check">
                    <a href="{{route('admin.templates.index')}}">
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(8, $check_role))

                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 pt-1 ml-1">
                    <a href="{{route('admin.system.mail.mail_trash')}}">
                        Thùng rác
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(1, $check_role))

                <li class="ml-2 phay">
                    /
                </li>
                <li class="add px-2 pt-1 ml-1 check">
                    <a href="{{route('admin.system.mail.new')}}">
                        <i class="fa fa-edit mr-1"></i>Thêm
                    </a>
                </li>
            @endif
        </ol>
    </div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4 class="m-0 text-bold text-center">QUẢN LÝ MAIL MẪU</h4>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-custom" id="table">
                            <thead>
                            <tr>
                                <th scope="row" class=" active">
                                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                                </th>
                                <th scope="col" >Thứ tự</th>
                                <th scope="col">ID</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col" >Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form action="{{route('admin.system.mail.listtrash')}}" id="trash_list" method="post">
                            <input type="hidden" name="action" id="action_list" value="">
                            @csrf
                            @foreach($mailtemplate as $item )
                                <tr>
                                    <td class=" active">
                                        <input type="checkbox" class="select-item checkbox order{{$item->id}}" name="select_item[]" value="{{$item->id}}" />

                                    </td>
                                    <td class="input_rounded">

                                        <input type="number" class="show_order" name="order{{$item->id}}"  value="{{$item->show_order}}" >
                                        <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}" />
                                    </td>
                                    <td class="">
                                        @if($item->template_action)
                                        <span class="mail_code">{{$item->template_action}}</span>
                                        @endif
                                    </td>
                                    <td>{{$item->template_title}}</td>
                                    <td>
                                        <div class="d-flex flex-row justify-content-around align-content-center table_action">
                                            @if($check_role ==1 || key_exists(2, $check_role))
{{--                                            <div><i class="fas fa-cog mr-2"></i><a href="{{route('admin.system.mail.update',[$item->id,\Crypt::encryptString($item->created_by)])}}" class="text-primary stretched-link">Chỉnh sửa</a></div>--}}
                                            <div><i class="fas fa-cog mr-2"></i><a href="{{route('admin.system.mail.update',[$item->id,\Crypt::encryptString($item->created_by)])}}" class="text-primary stretched-link">Chỉnh sửa</a></div>
                                            @endif
                                                @if($check_role ==1 || key_exists(5, $check_role))
                                                <div><i class="fas fa-times mr-2"></i>
                                                    <a onclick="deleteitem({{$item->id}},'{{\Crypt::encryptString($item->created_by)}}')" class="text-danger stretched-link action_delete">Xóa</a>
                                                </div>
                                                @endif
                                        </div>
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
                                            <a class="dropdown-item trash_list"  href="javascript:{}">
                                                <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Thùng rác
                                                <input type="hidden" name="action" value="trash">
                                            </a>
                                            <a class="dropdown-item update_list"  href="javascript:{}">
                                                <i class="fas fa-pencil-alt bg-orange p-1 mr-2 rounded"
                                                   style="color: white !important;font-size: 15px"></i>Cập nhật
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
                            <div class="display d-flex align-items-center mr-4">
                                <span>Hiển thị:</span>
                                <form method="get" action="{{route('admin.templates.index')}}">
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
                            <div class="view-trash">
                                <a href="{{route('admin.system.mail.mail_trash')}}"><i class="far fa-trash-alt"></i> Xem thùng rác</a>
                                <span class="count-trash">
                                    @if(isset($count_trash))
                                        {{$count_trash}}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng:  {{$mailtemplate->total()}}
                                items
                            </div>
                            @if($mailtemplate)
                                {{ $mailtemplate->render('Admin.Layouts.Pagination') }}
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
@section('Style')

@endsection
@section('Script')
    <script src="js/table.js"></script>
    <script>
        function deleteitem(id,created_by)
        {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Sau khi xóa sẽ chuyển vào thùng rác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href= "/admin/system-config/trash-mail/"+id+"/"+created_by;
                }
            })
        }
        $('.show_order').click(function () {
           $('.'+$(this).attr('name')).prop('checked',true); });
        $('.action').change(function () {
                $('#action_list').submit();
        });
    </script>
    <script>
        $('.trash_list').click(function (){
            // id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Sau khi đồng ý sẽ chuyển vào thùng rác !!!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("trash") ;
                    $('#trash_list').submit();
                }
            })
        });
        $('.update_list').click(function (){
            // id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận cập nhật',
                text: "Sau khi đồng ý tiến hành cập nhật",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update") ;
                    $('#trash_list').submit();
                }
            })
        });
    </script>
@endsection
