@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác | Danh mục')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')

    <div class="row m-0 px-3 pt-3">

        <ol class="breadcrumb mt-1">
            @if($check_role == 1  ||key_exists(4, $check_role))
            <li class="add px-2 pt-1  check">
                <a href="{{route('admin.focuscategory.list')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
            @endif


        </ol>
    </div>
    <h4 class="text-center text-bold mt-2">THÙNG RÁC</h4>
    <!-- Main content -->
    <section class="content hiden-scroll">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <form action="{{route('admin.listcategory.untrashlist')}}" id="formtrash" method="post">
                            <input type="hidden" name="action" id="action_list" value="">

                            @csrf
                            <table class="table table-bordered text-center table-custom" id="table"
                                   style="min-width: 1000px">
                                <thead>
                                <tr class="contact-table">
                                    <th scope="col" style="width: 3%"><input type="checkbox" class="select-all"></th>
                                    <th scope="col" style="width: 4%">ID</th>
                                    <th scope="col" style="width: 28%">Tiêu đề</th>
                                    <th scope="col" style="width: 28%">Đường dẫn tĩnh</th>
                                    <th scope="col" style="width: 13%">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($group as $item )
                                    <tr>
                                        <td class="active">
                                            <input type="checkbox" class="select-item" value="{{$item->id}}"
                                                   name="select_item[]">
                                            <input type="hidden" class="select-item"
                                                   value="{{Crypt::encryptString($item->created_by)}}"
                                                   name="select_item_created[{{$item->id}}]">
                                            @if(!isset($item->child))
                                                <input type="hidden" class="select-item" value="{{($item->id)}}"
                                                       name=" parent[{{$item->id}}]">
                                            @else
                                                <input type="hidden" class="select-item" value="{{($item->id)}}"
                                                       name=" child[{{$item->id}}]">
                                            @endif
                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td class="text-left pl-5">
                                            @if($item->parent_id ==47)
                                                {{$item->group_name}}
                                            @else
                                                {{"---- ".$item->group_name}}
                                            @endif
                                        </td>
                                        <td class="">
                                            <span>{{$item->group_url}}</span>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                @if($check_role == 1  ||key_exists(6, $check_role))
                                                <div>
                                                    <i class="fas fa-undo-alt"></i>
                                                    <a class="setting-item delete text-danger action_delete cusor-point"
                                                       data-id="{{$item->id}}"
                                                       data-created_by="{{Crypt::encryptString($item->created_by)}}">Khôi
                                                        phục</a>
                                                </div>
                                                @endif
                                                <x-admin.force-delete-button
                                                    :check-role="$check_role"
                                                    id="{{ $item->id }}"
                                                    url="{{ route('admin.listcategory.force-delete-multiple') }}"
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <form action="" class="force-delete-item-form d-none" method="POST">
                        @csrf
                        <input type="hidden" name="ids">
                    </form>

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
                                            <input type="hidden" name="action" value="restore">
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
                                <a href="{{route('admin.focuscategory.list')}}" class="btn btn-primary">
                                    Quay lại
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng: @empty($group) {{0}} @else {{$group->total()}} @endempty
                                items
                            </div>
                            @if($group)
                                {{ $group->render('Admin.Layouts.Pagination') }}
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
        $('#makhuyenmai').addClass('active');
        $('#nav-makhuyenmai').addClass('menu-is-opening menu-open');
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>

    <script>
        $('.delete').click(function () {

            var id = $(this).data('id');
            var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: "Nhấn đồng ý thì sẽ tiến hành khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/focus-news-category/undelete/" + id + "/" + created_by;

                }
            });
        });
        $('.unToTrash').click(function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: "Nhấn đồng ý thì sẽ tiến hành khôi phục !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("restore") ;
                    $('#formtrash').submit();

                }
            });
        });
    </script>

@endsection
