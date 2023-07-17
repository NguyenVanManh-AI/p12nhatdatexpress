@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác nhóm quản trị | Tài khoản quản trị')
@section('Content')
    <!-- Content Header (Page header) -->
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1">
            <li class="recye px-2 p-1">
                <a href="{{route('admin.manage.group')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
        </ol>
    </div><!-- /.col -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12 text-center">
                    <h4 class="m-0 font-weight-bold">THÙNG RÁC NHÓM QUẢN TRỊ</h4>
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
                                <th scope="col">ID</th>
                                <th scope="col">Thứ tự</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form action="{{route('admin.manage.group.action')}}" id="formAction" method="post">
                                @csrf
                                @forelse($roles_trash as $role)
                                    <tr>
                                        <td class=" active">
                                            <input type="checkbox" value="{{$role->id}}" data-name="{{$role->role_name}}" class="select-item checkbox" name="select_item[]" />
                                        </td>
                                        <td class="id_role">{{$role->id}}</td>
                                        <td class="input_rounded">
                                            <input type="text" value="{{$role->show_order}}" name="show_order[{{$role->id}}]">
                                        </td>
                                        <td class="title_role">{{$role->role_name}}</td>
                                        <td class="text-left">
                                            <div class="table_action">
                                                @if( $check_role == 1 || key_exists(6, $check_role))
                                                    <div class="ml-3 mb-2"><i class="fas fa-undo-alt mr-2"></i><a href="javascript:{}" class="text-primary action_restore" data-c="{{$role->created_by}}" data-id="{{$role->id}}" data-created="{{\Crypt::encryptString($role->created_by)}}">Khôi phục</a></div>
                                                @endif
                                                @if($check_role == 1 || key_exists(7, $check_role))
                                                    <div class="ml-3"><i class="fas fa-times mr-2" style="padding-right: 4px"></i><a href="javascript:{}" class="text-danger action_delete" data-c="{{$role->created_by}}" data-id="{{$role->id}}" data-created="{{\Crypt::encryptString($role->created_by)}}">Xóa</a></div>
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
                                        @if( $check_role == 1 || key_exists(1, $check_role))
                                            <a class="dropdown-item updateShowOrder" type="button" href="javascript:{}">
                                                <i class="fas fa-pencil-alt bg-orange p-1 mr-2 rounded" style="color: white !important;font-size: 15px; width: 25px"></i> Cập nhật
                                            </a>
                                        @endif
                                        @if($check_role == 1 || key_exists(6, $check_role))
                                            <a class="dropdown-item restoreRole" type="button" href="javascript:{}">
                                                <i class="fas fa-undo-alt bg-primary text-center p-1 mr-2 rounded" style="color: white !important;font-size: 15px; width: 25px"></i> Khôi phục
                                            </a>
                                        @endif
                                        @if($check_role == 1 || key_exists(7, $check_role))
                                            <a class="dropdown-item moveToTrash" type="button" href="javascript:{}" >
                                                <i class="fas fa-times bg-red p-1 rounded text-center " style="color: white !important;font-size: 15px; width: 25px; margin-right: 12px;"></i>Xóa
                                            </a>
                                        @endif
                                            @if(is_array($check_role) && !key_exists(1, $check_role) && !key_exists(6, $check_role) && !key_exists(7, $check_role))
                                                <p class="dropdown-item m-0 disabled">
                                                    Bạn không có quyền
                                                </p>
                                            @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mx-4">
                                <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                                <form action="{{route('admin.manage.group.trash')}}" method="GET">
                                    <label class="select-custom2">
                                        <select id="paginateNumber" name="items" onchange="this.form.submit()">
                                            <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
                                            <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
                                            <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
                                        </select>
                                    </label>
                                </form>
                            </div>

                            <div>
                                @if($check_role == 1  ||key_exists(4, $check_role))
                                <a href="{{route('admin.manage.group')}}" class="btn btn-primary">
                                    Quay lại
                                </a>
                                @endif
                            </div>

                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng: @empty($roles_trash) {{0}} @else {{$roles_trash->total()}} @endempty items</div>
                            @if($roles_trash)
                                {{ $roles_trash->render('Admin.Layouts.Pagination') }}
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
        function forceDeleteItem(id, created)
        {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Sau khi xóa sẽ không thể khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href= `{{route('admin.manage.group.force-delete', ['',''])}}/${id}/${created}`;
                }
            })
        }
        function restoreItem(id, created)
        {
            Swal.fire({
                title: 'Xác nhận khôi phục!',
                text: `Sau khi khôi phục sẽ được chuyển sang danh sách`,
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-success btn-lg mr-2',
                    cancelButton: 'btn btn-outline-secondary btn-lg'
                },
                confirmButtonText: 'Khôi phục',
                cancelButtonText: 'Hủy',
            }).then((e) => {
                if (e.value) {
                    window.location.href= `{{route('admin.manage.group.restore', ['',''])}}/${id}/${created}`;
                } else {
                    e.dismiss;
                }
            })
        }
        jQuery(document).ready(function ($) {
            $(function () {
                // remove click
                $('.action_delete').click(function () {
                    forceDeleteItem($(this).data('id'), $(this).data('created'))
                })
                // restore click
                $('.action_restore').click(function () {
                    restoreItem($(this).data('id'),$(this).data('created'))
                })
                // restote
                $('.dropdown-item.restoreRole').click(function () {
                    const selectedArray = getSelected();
                    if (selectedArray) {
                        Swal.fire({
                            title: 'Xác nhận Khôi phục!',
                            text: `Sau khi khôi phục sẽ được chuyển sang danh sách`,
                            icon: 'warning',
                            showCancelButton: true,
                            buttonsStyling: false,
                            customClass: {
                                confirmButton: 'btn btn-success btn-lg mr-2',
                                cancelButton: 'btn btn-outline-secondary btn-lg'
                            },
                            confirmButtonText: 'Khôi phục',
                            cancelButtonText: 'Hủy',
                        }).then((e) => {
                            if (e.value) {
                                if (selectedArray)
                                    $('#formAction').attr('action', $('#formAction').attr('action') + '?action=restore').submit();
                            } else {
                                e.dismiss;
                            }
                        })
                    }
                })
                // force delete
                $('.dropdown-item.moveToTrash').click(function () {
                    const selectedArray = getSelected();
                    if (selectedArray) {
                        Swal.fire({
                            title: 'Xác nhận xóa',
                            text: "Sau khi xóa sẽ không thể khôi phục!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            cancelButtonText: 'Quay lại',
                            confirmButtonText: 'Đồng ý'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (selectedArray)
                                    $('#formAction').attr('action', $('#formAction').attr('action') + '?action=forceDelete').submit();
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
@endsection
