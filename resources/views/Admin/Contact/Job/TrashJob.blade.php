@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác | Danh sách nghành nghề')
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
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1">
            @if($check_role == 1  ||key_exists(4, $check_role))
                <li class="recye px-2 pt-1 check">
                    <a href="{{route('admin.contact.job.list')}}">
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>
            @endif
        </ol>
    </div>
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
                                <th scope="col" style="width: 4%">ID</th>
                                <th scope="col" style="width: 22%">Nghề nghiệp</th>
                                <th scope="col" style="width: 14%">Người tạo</th>
                                {{-- <th scope="col" style="width: 11%">Trạng thái hiển thị</th> --}}
                                <th scope="col" style="width: 14%;">Cài đặt</th>
                            </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" name="action" id="formaction" value="">
                                @forelse( $list as $item )
                                    <tr>
                                        <td class="active">
                                            <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                                            <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}" />

                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td>
                                            <div class="d-flex align-items-center flex-column flex-fill">
                                                <span class="name-text">{{$item->param_name}} </span>
                                                <div class="review-box-main mt-3">

                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <span>{{$item->admin_fullname}}</span>
                                        </td>

                                        <td>
                                            <div class="flex-column">
                                                <x-admin.restore-button
                                                    :check-role="$check_role"
                                                    url="{{ route('admin.contact.job.restore-multiple', ['ids' => $item->id]) }}"
                                                />
                              
                                                <x-admin.force-delete-button
                                                    :check-role="$check_role"
                                                    url="{{ route('admin.contact.job.force-delete-multiple', ['ids' => $item->id]) }}"
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <x-admin.table-footer
                        :check-role="$check_role"
                        :lists="$list"
                        force-delete-url="{{ route('admin.contact.job.force-delete-multiple') }}"
                        restore-url="{{ route('admin.contact.job.restore-multiple') }}"
                    />
                </div>
            </div>
        </div>
    </section>
@endsection
@section('Script')
    <script src="js/table.js"></script>

    
    <script>
        $('.delete').click(function (e) {
            e.preventDefault()
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
                    window.location.href = $(this).attr('href');
                }
            });
        });

        $('.unToTrash').click(function () {
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
                    $('#formaction').val('restore');
                    // alert($('#formaction').val('restore'));
                    $('#formtrash').submit();

                }
            });
        });
        $('.delete_list').click(function () {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Sau khi xóa thì không thể khôi phục",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formaction').val('delete');
                    $('#formtrash').submit();

                }
            });
        });

        // $('.deletealways').click(function () {
        //     Swal.fire({
        //         title: 'Xác nhận xóa',
        //         text: "Sau khi xóa sẽ không thể khôi phục",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#d33',
        //         cancelButtonColor: '#3085d6',
        //         cancelButtonText: 'Quay lại',
        //         confirmButtonText: 'Đồng ý'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             window.location.href = $(this).attr('href');
        //         }
        //     });
        // });
    </script>
@endsection
