@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác | Hướng dẫn')
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
        <li class="list-box px-2 pt-1 active check">
            <a href="{{route('admin.guide.list')}}">
                <i class="fa fa-th-list mr-1"></i>Danh sách
            </a>
        </li>
        @endif
        {{-- <li class="phay ml-2">
            /
        </li> --}}
        {{-- <li class="recye px-2 pt-1 ml-1">
            <a href="{{route('admin.guide.trash')}}">
                Thùng rác
            </a>
        </li> --}}
        {{-- <li class="ml-2 phay">
            /
        </li>
        <li class="add px-2 pt-1 ml-1 check">
            <a href="{{route('admin.guide.add')}}">
                <i class="fa fa-edit mr-1"></i>Thêm
            </a>
        </li> --}}
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
                        @foreach ( $trash_Guide as $item )
                            <tr>
                                <td class="active">
                                    <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                                    <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}" />

                                </td>
                                <td>{{$item->id}}</td>
                                <td>
                                    <div class="image-box-main">
                                        <div class="image-top">
                                            <img
                                                src="{{asset($item->image_url ?? '/frontend/images/home/image_default_nhadat.jpg')}}"
                                                class=" h-100">
                                        </div>
                                       
                                           
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center flex-column flex-fill">
                                        <span class="name-text">{{$item->guide_title}} </span>
                                        <div class="review-box-main mt-3">
                                            {{-- <div class="box-review d-flex flex-row">
                                                <i class="fas fa-edit mr-2"></i>
                                                <span class="d-flex">Số chữ: <span class="ml-1">1600</span> </span>
                                            </div>
                                            <div class="view-box d-flex flex-row mt-3">
                                                <i class="fas fa-signal mt-1 mr-2"></i>
                                                <span class="d-flex flex-row align-items-center">Lượt xem: <span
                                                        class="ml-1">300</span></span>
                                            </div> --}}
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
                                    <span>{{\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')}}</span>
                                </td>
                                <td>
                                    <div class="row flex-column justify-content-center pl-3">
                                        {{-- <div class="text-left mb-2">
                                            <i class="icon-setup fas fa-file-alt mr-2"></i>
                                            <a href="" class="text-primary ">Xem bài</a>
                                        </div>
                                        <div class="text-left mb-2">
                                            <i class="icon-setup fas fa-cog mr-2"></i>
                                            <a href="" class="text-primary ">Chỉnh sửa</a>
                                        </div> --}}
                                        {{-- <div class="text-left mb-2">
                                            <i class="icon-setup fas fa-times mr-2"></i>
                                            <a href="" style="color:#ff0000">Xóa</a>
                                        </div> --}}
                                        @if($check_role == 1  ||key_exists(6, $check_role))    
                                        <div class="text-left mb-2">
                                            <i class="fas fa-undo-alt text-black"></i>
                                            <a class="setting-item delete text-primary mb-2" style="cursor: pointer" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">
                                                Khôi phục</a>
                                        </div>
                                        @endif
                                        <x-admin.force-delete-button
                                            :check-role="$check_role"
                                            id="{{ $item->id }}"
                                            url="{{ route('admin.guide.force-delete-multiple') }}"
                                        />
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <form action="" class="force-delete-item-form d-none" method="POST">
                    @csrf
                    <input type="hidden" name="ids">
                </form>

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
                                    {{-- <a class="dropdown-item updateShowOrder "  type="button" href="javascript:{}">
                                        <i class="fas fa-pencil-alt bg-orange p-1 mr-2 rounded"
                                           style="color: white !important;font-size: 15px"></i> Cập nhật
                                    </a> --}}
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
                                </form>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mx-4">
                            <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                            <form action="{{route('admin.guide.trash')}}" method="GET">
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
                        <div>
                            @if($check_role == 1  ||key_exists(4, $check_role))
                            <a href="{{route('admin.guide.list')}}" class="btn btn-primary">
                                Quay lại
                            </a>
                            @endif
                        </div>
                        {{-- <div class="d-flex flex-row align-items-center view-trash">
                            <i class="far fa-trash-alt mr-2"></i>
                            <div class="link-custom">
                                <a href="#"><span style="color: #347ab6">Xem thùng rác</span>
                                    <span class="badge badge-pill badge-danger trashnum"
                                          style="font-weight: 500">{{2}}</span>
                                </a>
                            </div>
                        </div> --}}
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="count-item">Tổng cộng: {{$trash_Guide->total()}} items</div>
                        <div>
                            @if($trash_Guide)
                     {{ $trash_Guide->render('Admin.Layouts.Pagination') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
                    window.location.href = "/admin/guide/untrash-guide/" + id+"/"+created_by;
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

                    $('#formtrash').submit();

                }
            });
        });
     </script>
@endsection