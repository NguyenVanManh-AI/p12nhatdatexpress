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
                            <input type="hidden" name="action" id="action_list" value="">
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
                                            <div class="flex-column">
                                                <x-admin.restore-button
                                                    :check-role="$check_role"
                                                    url="{{ route('admin.focuscategory.restore-multiple', ['ids' => $item->id]) }}"
                                                />
                                
                                                <x-admin.force-delete-button
                                                    :check-role="$check_role"
                                                    url="{{ route('admin.focuscategory.force-delete-multiple', ['ids' => $item->id]) }}"
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>

                    <x-admin.table-footer
                        :check-role="$check_role"
                        :lists="$group"
                        force-delete-url="{{ route('admin.focuscategory.force-delete-multiple') }}"
                        restore-url="{{ route('admin.focuscategory.restore-multiple') }}"
                    />
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
