@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý nhóm quản trị | Tài khoản quản trị')
@section('Content')
    <!-- Content Header (Page header) -->
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1 align-items-center">
            <li class="recye px-2 p-1 check active">
                <a href="javascript:{}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
            @if($check_role == 1  ||key_exists(5, $check_role))
                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 ml-1">
                    <a href="{{route('admin.manage.group.trash')}}">
                        <i class="far fa-trash-alt mr-1"></i>Thùng rác
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(1, $check_role))
                <li class="ml-2 phay">
                    /
                </li>
                <li class="add px-2 ml-1 p-1">
                    <a href="{{route('admin.manage.group.add')}}">
                        <i class="fa fa-edit mr-1"></i>Thêm
                    </a>
                </li>
            @endif
        </ol>
    </div>
    <!-- ./Breakcum -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12 text-center">
                    <h4 class="m-0 font-weight-bold">QUẢN LÝ NHÓM QUẢN TRỊ</h4>
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
                        <table class="table table-bordered text-center table-hover table-custom " id="table">
                            <thead>
                            <tr>
                                <th scope="row" class="active">
                                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                                </th>
                                <th scope="col">ID</th>
                                <th scope="col">Thứ tự</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Cài đặt</th>
                            </tr>
                            </thead>
                                <tbody>
                                    <form action="{{route('admin.manage.group.action')}}" id="formAction" method="post">
                                        @csrf
                                        @forelse($roles as $role)
                                                <tr>
                                                <td class=" active">
                                                    <input type="checkbox"  value="{{$role->id}}" data-name="{{$role->role_name}}" class="select-item checkbox" name="select_item[]" />
                                                    <input type="text" hidden name="select_item_created[{{$role->id}}]" value="{{\Crypt::encryptString($role->created_by)}}">
                                                </td>
                                                <td class="id_role">{{$role->id}}</td>
                                                <td class="input_rounded">
                                                    <input type="text" value="{{$role->show_order}}" name="show_order[{{$role->id}}]">
                                                </td>
                                                <td class="title_role">{{$role->role_name}}</td>
                                                <td class="text-left">
                                                    <div class="table_action">
                                                        @if($check_role == 1 || key_exists(2, $check_role))
                                                            <div class="ml-3 mb-2"><i class="fas fa-cog mr-2"></i>
                                                                <a href="{{route('admin.manage.group.edit',[$role->id, \Crypt::encryptString($role->created_by)])}}" class="text-primary">Chỉnh sửa</a>
                                                            </div>
                                                        @endif
                                                        @if($check_role == 1 || key_exists(5, $check_role))
                                                            <div class="ml-3"><i class="fas fa-times mr-2" style="padding-right: 5px"></i>
                                                                <a href="javascript:{}" class="text-danger action_delete" data-id="{{$role->id}}" data-created="{{\Crypt::encryptString($role->created_by)}}">
                                                                    Xóa
                                                                </a>

                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <td colspan="5">Chưa có dữ liệu</td>
                                        @endforelse
                                    </form>
                                </tbody>
                        </table>
                    </div>
                    <div class="d-flex align-items-center justify-content-between my-4">
                        <div class="d-flex align-items-center">
                            <div class="d-flex">
                                <img src="image/manipulation.png" alt="" id="btnTop">
                                    <div class="btn-group ml-1">
                                        <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown" aria-expanded="false" data-flip="false" aria-haspopup="true">
                                            Thao tác
                                        </button>
                                        <div class="dropdown-menu">
                                            @if( $check_role == 1 || key_exists(2, $check_role))
                                                <a class="dropdown-item updateShowOrder" type="button" href="javascript:{}">
                                                    <i class="fas fa-pencil-alt bg-orange p-1 mr-2 rounded" style="color: white !important;font-size: 15px"></i> Cập nhật
                                                </a>
                                            @endif
                                            @if($check_role == 1 || key_exists(5, $check_role))
                                                <a class="dropdown-item moveToTrash" type="button" href="javascript:{}" >
                                                    <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded" style="color: white !important;font-size: 15px"></i>Thùng rác
                                                </a>
                                            @endif
                                            @if(is_array($check_role) && !key_exists(2, $check_role) && !key_exists(5, $check_role))
                                                    <p class="dropdown-item m-0 disabled">
                                                        Bạn không có quyền
                                                    </p>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mx-4">
                                <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                                <form action="{{route('admin.manage.group')}}" method="GET">
                                    <label class="select-custom2">
                                        <select id="paginateNumber" name="items" onchange="this.form.submit()">
                                            <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
                                            <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
                                            <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
                                        </select>
                                    </label>
                                </form>
                            </div>
                            @if($check_role || key_exists(8, $check_role))
                                <div class="d-flex flex-row align-items-center view-trash">
                                    <i class="far fa-trash-alt mr-2"></i>
                                    <div class="link-custom">
                                        <a href="{{route('admin.manage.group.trash')}}"><span style="color: #347ab6">Xem thùng rác</span>
                                            <span class="badge badge-pill badge-danger trashnum" style="font-weight: 500">{{$trash_num}}</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng: @empty($roles) {{0}} @else {{$roles->total()}} @endempty items</div>
                            @if($roles)
                                {{ $roles->render('Admin.Layouts.Pagination') }}
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
    <script src="js/table.js" type="text/javascript"></script>
    <script>
        function deleteItem(id, created)
        {
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
                    window.location.href= `{{route('admin.manage.group.delete', ['',''])}}/${id}/${created}`;
                }
            })
        }
        jQuery(document).ready(function ($) {
            $(function () {
                // remove click
                $('.action_delete').click(function () {
                    deleteItem($(this).data('id'), $(this).data('created'))
                })
                // move to trash
                $('.dropdown-item.moveToTrash').click(function () {
                    const selectedArray = getSelected();
                    if (selectedArray){
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
                                if (selectedArray)
                                    $('#formAction').attr('action', $('#formAction').attr('action') + '?action=trash').submit();
                            }
                        });
                    }
                })
                // update show order
                $('.dropdown-item.updateShowOrder').click(function () {
                    const selectedArray = getSelected();
                    if (selectedArray)
                        $('#formAction').attr('action', $('#formAction').attr('action') + '?action=update').submit();
                })

            })
        })
    </script>
    @if(count($errors) > 0)
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
@endsection
