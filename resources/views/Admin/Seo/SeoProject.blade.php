@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý seo | Dự án')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
    <section class="content hiden-scroll">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <h4 class="text-center text-bold mt-4" >SEO CHUYÊN MỤC DỰ ÁN</h4>
                    <div class="table-responsive mt-2">
                        <form action="" id="formtrash" method="post">
                            @csrf
                            <input type="hidden" name="action" id="action_list" value="">
                            <table class="table table-bordered text-center table-custom" id="table">
                                <thead>
                                <tr>

                                    <th scope="col" style="width:15%">Tên chuyên mục</th>
                                    <th scope="col" style="width:15%"> Tiêu đề</th>
                                    <th scope="col" style="width: 10%">Từ khóa</th>
                                    {{--                                <th scope="col" style="width: 14%">Thứ tự</th>--}}
                                    <th scope="col" style="width: 20%">Mô tả</th>
                                    <th scope="col" style="width: 20%">Đường dẫn</th>
                                    <th scope="col" style="width: 13%">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($group as $item)
                                    <tr>
                                        <td> {{(!isset($item->child))?$item->group_name:"---- ".$item->group_name}}</td>
                                        <td>{{$item->meta_title}}</td>
                                        <td>{{$item->meta_key}}</td>
                                        <td class="text-center">
                                            {{$item->meta_desc}}
                                        </td>
                                        <td class="">
                                            <span>{{$item->group_url}}</span>
                                        </td>
                                        <td>
                                            <div>
                                                {{-- @if($check_role == 1  ||key_exists(2, $check_role)) --}}
                                                    <div class="float-left ml-2">
                                                        <i class="fas fa-cog mr-2"></i>
                                                        <a href="{{route('admin.seo.editproject',$item->id)}}"
                                                           class="text-primary ">Chỉnh sửa</a>
                                                    </div>
                                                {{-- @endif --}}
                                                <div class="clear-both"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($group->count()==0)
                                    <tr>
                                        <td colspan="5"> <p class="text-center mt-2">Không có dữ liệu</p></td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
                        <div class="text-left d-flex align-items-center">


                            <div class="display d-flex align-items-center mr-4">
                                <span>Hiển thị:</span>
                                <form method="get" id="paginateform" action="{{route('admin.seo.listproject')}}">
                                    <select class="custom-select" id="paginateNumber" name="items">
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
                                </form>
                            </div>


                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng: {{$group->total()}} items</div>
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
    <script type="text/javascript">
        $('#tieudiem').addClass('active');
        $('#quanlydanhmuc').addClass('active');
        $('#nav-tieudiem').addClass('menu-is-opening menu-open');
    </script>
    <script>
        $('#paginateNumber').change(function (e) {
            $('#paginateform').submit();
        });
        $('.trash').click(function () {
            created_by = $(this).data('created_by');
            id = $(this).data('id');
            Swal.fire({
                title: 'Chuyển danh mục vào thùng rác',
                text: "Nhấn đồng ý để chuyển danh mục vào thùng rác !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/classified-group/trash-item/" + id + "/" + created_by;
                }
            })
        });
        $('.moveToTrash').click(function () {
            var id = $(this).data('id');
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
                    $('#action_list').val("trash");
                    $('#formtrash').submit();

                }
            });
        });

    </script>

    <script src="js/table.js"></script>

@endsection
