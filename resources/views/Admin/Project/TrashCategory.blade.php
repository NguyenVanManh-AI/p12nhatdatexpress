@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý chuyên mục | Quản lý dự án')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
<div class="row m-0 p-3">
    <ol class="breadcrumb">
        @if($check_role == 1  ||key_exists(4, $check_role))
        <li class="add px-2 pt-1">
            <a href="{{route('admin.projectcategory.list')}}">
                <i class="fa fa-th-list mr-1"></i>Danh sách
            </a>
        </li>
        @endif
    </ol>
</div>
<!-- Main content -->
<section class="content hiden-scroll">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <form action="{{route('admin.projectcategory.untrashlist')}}" id="formtrash" method="post">
                        @csrf

                    <table class="table table-bordered text-center table-custom" id="table">
                        <thead>
                            <tr>
                                <th scope="row" class=" active" style="width: 3%">
                                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                                </th>
                                <th scope="col" style="width: 4%">STT</th>
                                {{-- <th scope="col" style="width: 14%">Thứ tự</th> --}}
                                <th scope="col"  style="width: 28%">Tên mô hình</th>
                                <th scope="col" style="width: 28%">Đường dẫn tĩnh</th>
                                <th scope="col" style="width: 13%">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($group as $item)
                            <tr>
                                <td class="active">
                                    <input type="checkbox" class="select-item" value="{{$item->id}}"name="select_item[]">
                                    <input type="hidden" class="select-item"   name="select_item_created[{{$item->id}}]">
                                    @if(!isset($item->child))
                                        <input type="hidden" class="select-item" value="{{($item->id)}}"
                                               name="parent[{{$item->id}}]">
                                    @else
                                        <input type="hidden" class="select-item" value="{{($item->id)}}"
                                               name="child[{{$item->id}}]">
                                    @endif
                                </td>

                                <td>{{$item->id}}</td>
                                {{-- <td class="input_rounded">
                                    <input type="text" value="0">
                                </td> --}}
                               @if (isset($item->child))
                               <td class="text-left pl-5">---- {{$item->group_name}}</td>
                               @else
                               <td class="text-left pl-5">{{$item->group_name}}</td>
                               @endif
                                <td class="">
                                    <span>{{$item->group_url}}</span>
                                </td>

                                <td>
                                    <div class="text-left">
                                        @if($check_role == 1  ||key_exists(6, $check_role))
                                        <div>
                                            <i class="fas fa-undo-alt" ></i>
                                            <a href="javascript:{}" data-id="{{$item->id}}" data-created_by="{{Crypt::encryptString($item->created_by)}}" class="setting-item untrash mb-2 text-primary">Khôi phục</a>
                                        </div>
                                        @endif
                                        <x-admin.force-delete-button
                                            :check-role="$check_role"
                                            id="{{ $item->id }}"
                                            url="{{ route('admin.projectcategory.force-delete-multiple') }}"
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
                                    <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
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

                        <div class="d-flex align-items-center justify-content-between mr-4">
                            <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                            <label class="select-custom2">
                                <select id="paginateNumber" name="items" onchange="submitPaginate(event, this)">
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
                                </select>
                            </label>
                        </div>
                        {{-- <div class="view-trash">
                            <a href=""><i class="far fa-trash-alt"></i> Xem thùng rác</a>
                            <span class="count-trash">1</span>
                        </div> --}}

                        <div>
                            @if($check_role == 1  ||key_exists(4, $check_role))
                            <a href="{{route('admin.projectcategory.list')}}" class="btn btn-primary">
                                Quay lại
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex align-items-center" >
                        <div class="count-item" >Tổng cộng: @empty($group) {{0}} @else {{$group->total()}} @endempty items</div>
                        <div class="count-item count-item-reponsive" style="display: none">@empty($group) {{0}} @else {{$group->total()}} @endempty items</div>
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
    $('#quanlyduan').addClass('active');
    $('#quanlychuyenmuc').addClass('active');
    $('#nav-quanlyduan').addClass('menu-is-opening menu-open');
</script>
<script>
    $('.untrash').click(function (){
    created_by = $(this).data('created_by');
    id = $(this).data('id');
    Swal.fire({
        title: 'Xác nhận khôi phục danh mục',
        text: "Nhấn đồng ý sẽ khôi phục danh mục",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Quay lại',
        confirmButtonText: 'Đồng ý'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href= "{{route('admin.projectcategory.untrash_item',['',''])}}" + '/' + id + "/" +created_by;
        }
    })
});
$('.moveToTrash').click(function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: "Sau khi đồng ý sẽ tiến hành khôi phục !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
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
