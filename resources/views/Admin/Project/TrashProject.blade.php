@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách dự án | Quản lý dự án')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
    <h4 class="text-center font-weight-bold mt-2 mb-4">QUẢN LÝ THÙNG RÁC DỰ ÁN</h4>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive table-custom">
                        <table class="table table-bordered text-center table-custom" id="table">
                            <thead>
                            <tr>
                                <th scope="row" class=" active" style="width: 3%">
                                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                                </th>
                                <th scope="col" style="width: 4%">STT</th>
                                <th scope="col" style="width: 14%">Hình ảnh</th>
                                <th scope="col"  style="width: 17%">Tên dự án</th>
                                <th scope="col" style="width: 11%">
{{--                                    <div class="dropdown">--}}
                                        Mô hình
{{--                                        <button class="dropdow dropdown-toggle font-weight-bold" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                                            Mô hình--}}
{{--                                        </button>--}}
{{--                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
{{--                                            <a class="dropdown-item" href="#">Action</a>--}}
{{--                                            <a class="dropdown-item" href="#">Another action</a>--}}
{{--                                            <a class="dropdown-item" href="#">Something else here</a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </th>
                                <th scope="col" style="width: 11%">Ngày</th>
                                <th scope="col" style="width: 11%">
                                    Người đăng
{{--                                    <div class="dropdown"> --}}
{{--                                        <button class="dropdow  dropdown-toggle font-weight-bold" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                                            Người đăng--}}
{{--                                        </button>--}}
{{--                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
{{--                                            <a class="dropdown-item" href="#">Action</a>--}}
{{--                                            <a class="dropdown-item" href="#">Another action</a>--}}
{{--                                            <a class="dropdown-item" href="#">Something else here</a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </th>
                                <th scope="col" style="width: 10%">Cài đặt</th>
                            </tr>

                            </thead>
                            <tbody>
                            <form action="{{route('admin.project.untrashlist')}}" id="formtrash" method="post">
                            @csrf
                            @foreach ($trash  as $item )
                            <tr>
                                <td class="active">
                                    <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                                    <input type="hidden" class="select-item"  name="select_item_created[{{$item->id}}]">
                                </td>
                                <td>{{$item->id}}</td>
                                <td>
                                    @php
                                        $list_image = json_decode($item->image_url);
                                    @endphp
                                    @if($list_image != null)
                                        <div class="list-image">
                                            <div class="image-large">
                                                <img src="{{$list_image[0]}}" alt="">
                                            </div>
                                            @if(count($list_image) > 2)
                                                <div class="image-small">
                                                    @if(count($list_image) > 1)
                                                        <div class="item">
                                                            <img src="{{$list_image[1]}}" alt="">
                                                        </div>
                                                    @endif
                                                    @if(count($list_image) > 2)
                                                        <div class="item">
                                                            <img src="{{$list_image[2]}}" alt="">
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="position-relative" style="padding-block: 3%">
                                    <a href="javascript:{}" class="title">{{$item->project_name}}</a>
                                    <div class="project-statistic">
                                        <div class="item">
                                            <i class="fas fa-edit"></i>
                                            Số chữ: <span class="text-blue-light">{{$item->num_word ?? 0}}</span>
                                        </div>
                                        <div class="item">
                                            <i class="fas fa-signal"></i>
                                            Lượt xem: <span class="text-blue-light">{{$item->num_view}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="">
                                    {{ data_get($item->group, 'group_name') }}
                                </td>
                                <td class="">
                                    <span>{{date('d/m/Y',$item->created_at)}}</span>
                                </td>
                                <td class="" style="overflow-x: hidden;max-width: 100px">
                                    <span>{{ data_get($item->admin, 'admin_fullname') }}</span>
                                </td>
                                <td>
                                    <div class="setting-item delete" data-id="{{$item->id}}">
                                        <i class="fas fa-undo-alt mr-2"></i>
                                        <a href="javascript:{}" class="text-primary"> Khôi phục</a>
                                    </div>

                                    <x-admin.force-delete-button
                                        :check-role="$check_role"
                                        id="{{ $item->id }}"
                                        url="{{ route('admin.project.force-delete-multiple') }}"
                                    />
                                </td>
                            </tr>
                                @endforeach
                                </form>
                            </tbody>
                        </table>
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
                                            <i class="fas fa-undo-alt bg-blue p-1 mr-2 rounded text-center" style="color: white !important;font-size: 13px; width: 23px"></i>Khôi phục
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
                            <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                                <label class="select-custom2">
                                    <select id="paginateNumber" name="items" onchange="submitPaginate(event, this)">
                                        <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
                                        <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
                                        <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
                                    </select>
                                </label>
                            </div>
                        <div>
                         @if($check_role == 1  ||key_exists(4, $check_role))
                        <a href="{{route('admin.project.list')}}" class="btn btn-primary">
                            Quay lại
                        </a>
                         @endif
                        </div>
                        </div>
                        <div class="d-flex align-items-center" >
                            <div class="count-item" >Tổng cộng: @empty($trash) {{0}} @else {{$trash->total()}} @endempty items</div>
                            <div class="count-item count-item-reponsive" style="display: none">@empty($trash) {{0}} @else {{$trash->total()}} @endempty items</div>
                            @if($trash)
                                {{ $trash->render('Admin.Layouts.Pagination') }}
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
    <!-- Select2 -->
    <script src="js/table.js"></script>
    <script type="text/javascript">
        $('#quanlyduan').addClass('active');
        $('#danhsachduan').addClass('active');
        $('#nav-quanlyduan').addClass('menu-is-opening menu-open');
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
                    window.location.href = "/admin/project/undelete/" + id;

                }
            });
        });
        $('.unToTrash').click(function () {
            const selectedArray = getSelected();
            if (!selectedArray) return;
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

                    $('#formtrash').submit();

                }
            });
        });
    </script>

@endsection
