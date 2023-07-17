@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách chặn cấm | Chặn Cấm')
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
    <!-- Breadcrumb -->
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1">
            @if($check_role == 1  ||key_exists(4, $check_role))
            <li class="list-box px-2 pt-1 active check">
                <a href="{{route('admin.block.list')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
             @endif
            @if($check_role == 1  ||key_exists(8, $check_role))
            <li class="phay ml-2">
                /
            </li>
            <li class="recye px-2 pt-1 ml-1">
                <a href="{{route('admin.block.trash')}}">
                    Thùng rác
                </a>
            </li>
           @endif

        </ol>
    </div>
    <!-- ./Breadcrumb -->

    <!-- Filter -->

    <!-- ./Filter -->
    @if($check_role == 1  ||key_exists(1, $check_role))
    <div  id="add_forbidden_word" class="col-md-12 col-lg-6">
          <form action="{{route('admin.block.add')}}" method="POST">
            @csrf
        <label for=""> Thêm từ cấm</label>
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <input class="form-control" type="text"  name="forbidden_word" value="{{old('forbidden_word')}}" placeholder="Từ cấm">
                @if($errors->has('forbidden_word'))
                <small style="font-size: 100%;" class="text-danger">
                {{$errors->first('forbidden_word')}}
                </small>
                  @endif
            </div>
            <div class="col-md-6 col-lg-6">
            <button type="submit" class="btn btn-primary"> Thêm </button>

            </div>
        </div>
         </form>
    </div>
    @endif
    @if($check_role == 1  ||key_exists(2, $check_role))
    <div id="edit_forbidden_word" class="col-md-12 col-lg-6"  style="display: none">
        <form action="{{ old('old_edit_url') ?? url('admin/block/edit')}}" id="post_edit" method="POST">
          @csrf
      <label for="">Sửa từ cấm</label>
      <div class="row">
          <div class="col-md-6 col-lg-6">
              <input  class="form-control id_forbidden_word" type="hidden"  name="id_forbidden_word" value="{{old('id_forbidden_word')}}">
              <input class="form-control forbidden_word" type="text"  name="forbidden_word" value="{{old('forbidden_word')}}" placeholder="Từ cấm">
              <input  class="form-control" type="hidden" id="old_edit_url"  name="old_edit_url" value="{{old('old_edit_url')}}">
              @if($errors->has('forbidden_word'))
                  <small style="font-size: 100%;" class="text-danger">
                        {{$errors->first('forbidden_word')}}
                  </small>
                @endif
          </div>
          <div class="col-md-6 col-lg-6">
          <button type="submit" class="btn btn-primary"> Cập nhật </button>
          <button type="button" class="btn btn-secondary remove_edit"> Hủy </button>
          </div>
      </div>
       </form>
  </div>
  @endif
    <h4 class="text-center font-weight-bold mt-5 mb-4">DANH SÁCH CHẶN CẤM</h4>
    <!-- Main content -->
    <section class="content mb-2">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <!-- Filter -->
                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <form action="" method="GET">
                                <div class="form-group d-flex">
                                    <input type="text" class="form-control mr-3" name="keyword" placeholder="Từ cấm" value="{{request('keyword')}}">
                                    <button class="btn btn-primary">Tìm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- //Filter -->

                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-custom" id="table">
                            <thead>
                            <tr>
                                <th scope="row" class=" active" style="width: 3%">
                                    <input type="checkbox" class="select-all checkbox" name="select-all"/>
                                </th>
                                <th scope="col" style="width: 4%">STT</th>
                                <th scope="col" style="width: 14%">Từ cấm</th>
                                <th scope="col" style="width: 28%">Người tạo</th>
                                <th scope="col" style="width: 14%">Ngày tạo</th>
                                {{-- <th scope="col" style="width: 11%">Trạng thái hiển thị</th> --}}
                                <th scope="col" style="width: 22%;">Cài đặt</th>
                            </tr>
                            </thead>
                            <tbody>
                                <form id="formtrash" action="{{route('admin.block.trashlist')}}" method="POST">
                                @csrf
                                @foreach ( $list_block as $item )
                                <tr>
                                    <td class="active">
                                        <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                                        <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}" />
                                    </td>
                                    <td>{{$item->id}}</td>

                                    <td>
                                        <div class="d-flex align-items-center flex-column flex-fill">
                                            <span class="name-text">{{$item->forbidden_word}} </span>
                                            <div class="review-box-main mt-3">

                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span>{{$item->admin_fullname}}</span>
                                    </td>

                                    <td>
                                        <span>{{date('d/m/Y',$item->created_at)}}</span>
                                    </td>
                                    {{-- <td> @if ($item->is_show==0)
                                        <a href="javascript:{}" data-id="{{$item->id}}" class="btn btn-success changestatus ">Hiển thị</a>
                                        @else
                                        <a href="javascript:{}" data-id="{{$item->id}}" class="btn btn-danger changestatus">Ẩn</a>
                                    @endif

                                    </td> --}}
                                    <td>
                                        <div class="row flex-column justify-content-center pl-3">

                                            @if($check_role == 1  ||key_exists(2, $check_role))
                                            <div class="text-left mb-2">
                                                <i class="icon-setup fas fa-cog mr-2"></i>
                                                <a href="javascript:{}" data-forbidden_word ="{{$item->forbidden_word}}"  data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}" class="text-primary edit_block">Chỉnh sửa</a>
                                            </div>
                                            @endif
                                            @if($check_role == 1  ||key_exists(5, $check_role))
                                            <div class="text-left mb-2">
                                                <i class="icon-setup fas fa-times mr-2"></i>
                                                <a href="javascript:{}" class="delete " data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}" style="color:#ff0000;cursor: pointer;">Xóa</a>
                                            </div>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

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
                                @if($check_role == 1  ||key_exists(5, $check_role))
                                        <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
                                            <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"
                                               style="color: white !important;font-size: 15px"></i>Thùng rác
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
                        </form>
                            <div class="d-flex align-items-center justify-content-between mx-4">
                                <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                                <form action="{{route('admin.block.list')}}" method="GET">
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
                            @if($check_role == 1  ||key_exists(8, $check_role))
                            <div class="d-flex flex-row align-items-center view-trash">
                                <i class="far fa-trash-alt mr-2"></i>
                                <div class="link-custom">

                                    <a href="{{route('admin.block.trash')}}"><span style="color: #347ab6">Xem thùng rác</span>
                                        <span class="badge badge-pill badge-danger trashnum"
                                              style="font-weight: 500">{{$count_trash}}</span>
                                    </a>

                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng: {{$list_block->total()}} items</div>
                            @if($list_block)
                     {{ $list_block->render('Admin.Layouts.Pagination') }}
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
<script>
        //  $('#edit_forbidden_word').hide();
    $(document).ready(function(){

        if("{{old('edit_forbidden_word')}}"){
            $('#edit_forbidden_word').attr('action', '').show();
            $('#add_forbidden_word').hide();
        }

    });
    $('.edit_block').click(function(){
        key = $(this).data('forbidden_word');
        id = $(this).data('id');
        created_by = $(this).data('created_by')
        $('#post_edit .forbidden_word').val(key);
        url = "{{route('admin.block.edit',['',''])}}/" + id + '/' + created_by;
        // url = $('#post_edit').attr('action');
        // url +="/"+id+"/"+created_by;
        $('#post_edit').attr('action',url);
        $('#old_edit_url').val(url)

        $('#add_forbidden_word').hide();
        $('#edit_forbidden_word').show();
    });
    $('.remove_edit').click(function(){
            $('#add_forbidden_word').show();
            $('#edit_forbidden_word').hide();
    });

</script>
<script type="text/javascript">
    $('#huongdan').addClass('active');
    $('#nav-huongdan').addClass('menu-is-opening menu-open');
</script>

<script>
    $('.delete').click(function () {
        var id = $(this).data('id');
        var created_by = $(this).data('created_by');
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
                window.location.href = "/admin/block/delete/" + id+"/"+created_by;
            }
        });
    });
    $('.moveToTrash').click(function () {
        // var id = $(this).data('id');
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

                $('#formtrash').submit();

            }
        });
    });
    $('.changestatus').click(function () {
        var id = $(this).data('id');
        var created_by = $(this).data('created_by');
        Swal.fire({
            title: 'Xác nhận chuyển đổi trạng thái',
            text: "Sau khi nhấn đồng ý sẽ thay đổi trạng thái!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/admin/block/change/" + id +"/"+created_by;
            }
        });
    });
</script>
@endsection
