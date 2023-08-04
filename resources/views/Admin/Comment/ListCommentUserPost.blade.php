@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách bình luận bài viết | Quản lý bình luận')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
<style type="text/css">
  @media only screen and (max-width: 779px) {
    .box-panage{
      display:inline !important;
    }
  }
  .box_input{
    height: 50px !important;
  }
</style>
<!-- Content Header (Page header) -->
<div class="col-sm-12 mbup30">
  <div class="row m-0 px-2 pt-3">
    <ol class="breadcrumb mt-1">
      <li class="list-box px-2 pt-1 active check">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </li>
      @if($check_role == 1  ||key_exists(5, $check_role))
      <li class="phay ml-2 " style="margin-top: 2px !important">
        /
      </li>
      <li class="recye px-2 pt-1 ml-1">
        <a href="{{route('admin.comment.trash-comment-user-post')}}">
          Thùng rác
        </a>
      </li>
      @endif

    </ol>
  </div>
</div><!-- /.col -->

<!-- Filter -->
<div class="container-fluid px-3 mt-2">
  <form method="get" action="{{route("admin.comment.list-user-post")}}">
    <div class="row m-0">
      <div class="col-12 col-sm-12 col-md-3 col-lg-3 box_input px-0 mb-2">
       <div class="input-reponsive-search ">
           <input class="form-control required" type="text" name="keyword" placeholder="Nhập từ khóa" value="{{ app('request')->input('keyword') }}">
       </div>
     </div>
     <div class="search-reponsive  col-12 col-sm-12 col-md-9 col-lg-9 pl-0">
      <div class="row m-0">
        <div id="from_date_box" class=" search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-2 pl-4 ">
          <div  style="position: relative">
            @if(app('request')->input('from_date') == "")
            <div id="from_date_text"  style="position: absolute;width: 60%;height: 38px;padding: 1px;">
              <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Từ ngày</span></div>
            </div>
            @endif
              <label for="handleDateFrom"></label><input id="handleDateFrom" class="start_day form-control float-left" name="from_date" type="date" placeholder="Từ ngày" value="{{ app('request')->input('from_date') }}" >
          </div>
        </div>
        <div id="to_date_box" class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2 pb-3">
          <div  style="position: relative">
           @if(app('request')->input('to_date') == "")
           <div id="to_date_text" style="position: absolute;width: 60%;height: 38px;padding: 1px;">
            <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Đến ngày</span></div>
          </div>
          @endif
              <label for="handleDateTo"></label><input id="handleDateTo" class="end_day form-control float-right" name="to_date" type="date" placeholder="Đến ngày" value="{{ app('request')->input('to_date') }}" >
          <div id="appendDateError"></div>
        </div>

      </div>
      <div class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2 pb-3">
        <div class="input-reponsive-search ">
         <input class="form-control required" type="number" name="phone" placeholder="Nhập số điện thoại" value="{{ app('request')->input('phone') }}">
       </div>
     </div>
  </div>
</div>
</div>
<div class="row m-0">
  <div class="col-12 col-sm-12 col-md-3 col-lg-3 box_input px-0 mb-2">
   <div class="input-reponsive-search mtdow10 ">
    <select class="form-control select2" name="status" style="width: 100%;height: 34px !important;">
     <option  value="3" {{(isset($_GET['status'])&& $_GET['status']== 3)?"selected":""}} >Hiện trạng (Tất cả)</option>
     <option value="0" {{(isset($_GET['status'])&& $_GET['status']== 0)?"selected":""}}>Hiển thị</option>
     <option value="1" {{(isset($_GET['status'])&& $_GET['status']== 1)?"selected":""}}>Bị chặn</option>
     <option value="2" {{(isset($_GET['status'])&& $_GET['status']== 2)?"selected":""}}>Bị cấm</option>
   </select>
 </div>
</div>
<div class="search-reponsive  mtdow10  col-12 col-sm-12 col-md-9 col-lg-9 pl-0">
  <div class="row m-0">
    <div id="to_date_box" class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2">
      <button class=" search-button btn btn-primary w-100" style="height: 37px;"><i class="fa fa-search mr-2 ml-0" aria-hidden="true"></i>Tìm kiếm</button>
    </div>
  </div>
</div>


</div>
</form>
</div>
<!-- ./Filter -->

<div class="content-header" style="margin-top: -10px">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12 text-center">
        <h1 class="m-0 font-weight-bold">QUẢN LÝ BÌNH LUẬN BÀI VIẾT</h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content" style="overflow-x: hidden">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered text-center table-hover table-custom " id="table" style="min-width: 1050px">
              <thead>
                <tr>
                  <th scope="row" class="active" width="3%">
                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                  </th>
                  <th scope="col" width="3%">STT</th>
                  <th scope="col" width="12%">Tên tài khoản</th>
                  <th scope="col" width="18%">Nội dung</th>

                  <th scope="col" width="10%">Thời gian</th>
                  <th scope="col" width="10%">Tình trạng</th>
                  <th scope="col" width="16%">Liên kết</th>
                  <th scope="col" width="12%">Thao tác</th>
                </tr>
              </thead>
              <tbody>

                @php
                $countStt = $list->total()+1;
                @endphp
                @forelse($list as $item)
                @php
                $countStt--;
                @endphp
                <tr>
                  <td class="active">
                      <label>
                          <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                      </label>
                      <input type="hidden" class="select-item" value="{{\Crypt::encryptString($item->created_by)}}" name="select_item_created[{{$item->id}}]">
                  </td>
                  <td >{{$countStt}}</td>
                  <td>
                    {{$item->username}}
                    <br>
                    <span>
                      @if($item->is_forbidden ==1)
                      (Đã cấm)
                      @endif
                      @if($item->is_locked ==1)
                      (Đã chặn 7 ngày)
                      @endif
                    </span>

                  </td>
                  <td class="title_role text-wrap text-left" style="word-break: break-word;">
                    <p class="mb-0" style="e-height: 1.5em;height: 3em;overflow: hidden;
                    ">{{$item->comment_content}}</p>

                  </td>

                  <td class="title_role text-wrap" style="word-break: break-word">
                    {{date('d/m/Y H:i',$item->created_at)}}

                  </td>
                  <td >
                    @if($item->is_forbidden ==1)
                    <span class="text-danger">Bị cấm</span>
                    @endif
                    @if($item->is_locked ==1)
                    <span class="text-danger">Bị chặn</span>
                    @endif
                    @if($item->is_forbidden == 0 && $item->is_locked == 0)
                    <span class="text-success">Hiển thị</span>
                    @endif
                  </td>
                  <td >
                    <a href="https://nhadatexpress.vn/user-post/{{$item->post_code}}" target="_blank" rel="noopener noreferrer">https://nhadatexpress.vn/user-post/{{$item->post_code}}</a>

                  </td>
                  <td class="text-left">
                    <div class="d-flex flex-column">
                      <div class="ml-2 mb-2">
                        <i class="fa fa-eye mr-2"></i>
                        <a href="https://nhadatexpress.vn/user-post/{{$item->post_code}}" class="text-primary ">Xem</a>
                      </div>
                     
                      
                      <x-admin.delete-button
                        :check-role="$check_role"
                        url="{{ route('admin.comment.user-posts.delete-multiple', ['ids' => $item->id]) }}"
                      />

                      @if($check_role == 1  ||key_exists(2, $check_role))
                      @if($item->is_forbidden ==0 && $item->is_locked==0)
                     
                      <div class="ml10 mb-2 cusor-point">
                        <span class="icon-small-size mr-1 text-dark">
                          <i class="fas fa-times"></i>
                        </span>
                        <a  class="text-danger forbidden" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Cấm </a>
                      </div>
                      
                      <div class="ml10 mb-2 cusor-point">
                        <span class="icon-small-size mr-1 text-dark">
                          <i class="fas fa-times"></i>
                        </span>
                        <a  class="text-danger locked" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Chặn </a>
                      </div>
                      
                      @endif
                      @endif
                       
                      {{-- cấm tk --}}
                      @if($check_role == 1  ||key_exists(2, $check_role))
                      @if($item->is_forbidden ==1 && $item->is_locked==0)
                      @if($item->is_forbidden == 1)
                      @if($check_role == 1  ||key_exists(2, $check_role))
                      <div class="ml10 mb-2 cusor-point">
                        <i class="fas fa-times mr12  "></i>
                        <a  class=" un-forbidden text-primary" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Mở cấm</a>
                      </div>
                      
                      @endif
                      @endif
                      @if($item->is_locked == 0)
                      @if($check_role == 1  ||key_exists(2, $check_role))
                      <div class="ml10 mb-2 cusor-point">
                        <span class="icon-small-size mr-1 text-dark">
                          <i class="fas fa-times"></i>
                        </span>
                        <a  class="text-danger locked" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Chặn </a>
                      </div>
                      
                      @endif
                      @endif
                      @endif
                      @endif
  
  
                      {{-- chặn --}}
  
                    @if($check_role == 1  ||key_exists(2, $check_role))
                    @if($item->is_forbidden ==0 && $item->is_locked==1)
                    @if($item->is_locked == 1)
                    @if($check_role == 1  ||key_exists(2, $check_role))
                    <div class="ml10 mb-2" >
                      <i class="fas fa-times mr12" style="margin-left: 2px"></i>
                      <a  class="setting-item unblock_account text-primary action_delete cusor-point" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Mở Chặn</a>
                    </div>
                
                    @endif
                    @endif
                    @if($item->is_forbidden == 0)
                    @if($check_role == 1  ||key_exists(2, $check_role))
                    <div class="ml10 mb-2 cusor-point">
                      <span class="icon-small-size mr-1 text-dark">
                        <i class="fas fa-times"></i>
                      </span>
                      <a  class="text-danger forbidden" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Cấm </a>
                    </div>
                    
                    @endif
                    @endif
                    @endif
                    @endif
  
                    {{-- chặn , cấm --}}
                    @if($check_role == 1  ||key_exists(2, $check_role))
                    @if($item->is_forbidden ==1 && $item->is_locked==1)
                    @if($item->is_locked == 1)
                    @if($check_role == 1  ||key_exists(2, $check_role))
                    <div class="ml10 mb-2" >
                      <i class="fas fa-times mr12" style="margin-left: 2px"></i>
                      <a  class="setting-item unblock_account text-primary action_delete cusor-point" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Mở Chặn</a>
                    </div>
                    
                    @endif
                    @endif
                    @if($item->is_forbidden == 1)
                      @if($check_role == 1  ||key_exists(2, $check_role))
                      <div class="ml10 mb-2 cusor-point">
                        <span class="icon-small-size mr-1 text-dark">
                          <i class="fas fa-times"></i>
                        </span>
                        <a  class=" un-forbidden text-primary" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Mở cấm</a>
                      </div>
                      
                      @endif
                      @endif
                    @endif
                    @endif
                      <div class="clear-both"></div>
                    </div>
                  </td>

                </tr>
                @empty
                <td colspan="9">Chưa có dữ liệu</td>
                @endforelse

              </tbody>
            </table>
        </div>
        
        <x-admin.table-footer
          :check-role="$check_role"
          :lists="$list"
          :count-trash="$trash_num"
          view-trash-url="{{ route('admin.comment.trash-comment-user-post') }}"
          delete-url="{{ route('admin.comment.user-posts.delete-multiple') }}"
        />
    </div>
  </div>
</div>
</section>
<!-- /.content -->
@endsection

@section('Script')
<script src="js/table.js" type="text/javascript"></script>
<script>
  $('.locked').click(function () {
    var id = $(this).data('id');
    var created_by = $(this).data('created_by');
    Swal.fire({
      title: 'Chặn tài khoản',
      text: "Tài khoản sẽ bị chặn 1 tuần!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      cancelButtonText: 'Quay lại',
      confirmButtonText: 'Đồng ý'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "/admin/comment/user-post/locked-account/" + id + "/" + created_by;
      }
    });
  });
   //Bỏ Chặn tài khoản
   $('.unblock_account').click(function () {
            var id = $(this).data('id');
            // var created_by = $(this).data('created_by');
            Swal.fire({
                title: 'Bỏ chặn tài khoản',
                text: "Xác nhận bỏ chặn tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.comment.locked-account-comment-user-post', '')}}/" + id;
                }
            });
        });   


  $('.forbidden').click(function () {
    var id = $(this).data('id');
    var created_by = $(this).data('created_by');
    Swal.fire({
      title: 'Cấm tài khoản',
      text: "Tài khoản sẽ bị cấm cho đến khi mở lại!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      cancelButtonText: 'Quay lại',
      confirmButtonText: 'Đồng ý'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "/admin/comment/user-post/forbidden-account/" + id + "/" + created_by;
      }
    });
  });

  $('.un-forbidden').click(function () {
    var id = $(this).data('id');
    var created_by = $(this).data('created_by');
    Swal.fire({
      title: 'Mở cấm tài khoản',
      text: "Tài khoản sẽ được mở cấm!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      cancelButtonText: 'Quay lại',
      confirmButtonText: 'Đồng ý'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "/admin/comment/user-post/forbidden-account/" + id + "/" + created_by;
      }
    });
  });

  $('.forbidden-list').click(function () {
    $('#formtrash').attr('action', "/admin/comment/user-post/forbidden-comment")
    var id = $(this).data('id');
    Swal.fire({
      title: 'Cấm nhiều tài khoản',
      text: "Sau khi cấm, danh sách tài khoản được chọn sẽ đưa về trạng thái cấm",
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
  $('.un-forbidden-list').click(function () {
    $('#formtrash').attr('action', "/admin/comment/user-post/un-forbidden-comment")
    var id = $(this).data('id');
    Swal.fire({
      title: 'Mở nhiều tài khoản',
      text: "Sau khi mở cấm, danh sách tài khoản được chọn sẽ đưa về trạng thái hoạt động",
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

  $('.locked-list').click(function () {
    $('#formtrash').attr('action', "/admin/comment/user-post/locked-comment")
    var id = $(this).data('id');
    Swal.fire({
      title: 'Chặn nhiều tài khoản',
      text: "Các tài khoản đã được chọn sẽ bị chuyển sang trạng thái chặn",
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

  $('.un-locked-list').click(function () {
    $('#formtrash').attr('action', "/admin/comment/user-post/un-locked-comment")
    var id = $(this).data('id');
    Swal.fire({
      title: 'Chặn nhiều tài khoản',
      text: "Các tài khoản đã được chọn sẽ bị chuyển sang trạng thái chặn",
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


  function hideTextDateStart(){
    $('#txtDateStart').hide();
  }
  function hideTextDateEnd(){
    $('#txtDateEnd').hide();
  }
  setMinMaxDate('#handleDateFrom', '#handleDateTo')
  function setMinMaxDate(inputElementStart, inputElementEnd){
    if ($(inputElementStart).val()) $(inputElementEnd).attr('min',$(inputElementStart).val());
    if ($(inputElementEnd).val()) $(inputElementStart).attr('max',$(inputElementEnd).val());

    $(inputElementStart).change(function (){
      $(inputElementEnd).attr('min',$(inputElementStart).val());
    });
    $(inputElementEnd).change(function (){
      $(inputElementStart).attr('max',$(inputElementStart).val());
    });
  }
</script>
@if(count($errors) > 0)
@foreach($errors->all() as $error)
toastr.error("{{ $error }}");
@endforeach
@endif
@endsection
