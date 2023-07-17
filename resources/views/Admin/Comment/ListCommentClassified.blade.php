@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách bình luận tin đăng | Quản lý bình luận')
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
        <a href="{{route('admin.comment.trash-comment-classified')}}">
          Thùng rác
        </a>
      </li>
      @endif

    </ol>
  </div>
</div><!-- /.col -->

<!-- Filter -->
<div class="container-fluid px-3 mt-2">
  <form method="get" action="{{route("admin.comment.list-classified")}}">
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
          <input id="handleDateTo" class="end_day form-control float-right" name="to_date" type="date" placeholder="Đến ngày" value="{{ app('request')->input('to_date') }}" >
          <div id="appendDateError"></div>
        </div>

      </div>
      <div class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2 pb-3">
        <div class="input-reponsive-search ">
         <input class="form-control required" type="number" name="phone" placeholder="Nhập số điện thoại" value="{{ app('request')->input('phone') }}">
       </div>

     </div>

     <script type="text/javascript">
      $('#from_date_box').click(function(){
        $('#from_date_text').hide();
      })
      $('#to_date_box').click(function(){
        $('#to_date_text').hide();
      })

    </script>
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
    <div id="from_date_box" class=" search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-2 pl-4 ">
      <select class="form-control select2" name="category" style="width: 100%;height: 34px !important;">
        <option selected="selected" value="">Chuyên mục (Tất cả)</option>
        @foreach($category as $item)
        <option {{(isset($_GET['category'])&& $_GET['category']== $item->id)?"selected":""}} value="{{$item->id}}">{{$item->group_name}}</option>
        @endforeach
      </select>
    </div>

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
        <h1 class="m-0 font-weight-bold">QUẢN LÝ BÌNH LUẬN TIN ĐĂNG</h1>
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
          <form action="{{route('admin.comment.trashlist-comment-classified')}}" id="formtrash" method="post">
            @csrf
            <table class="table table-bordered text-center table-hover table-custom " id="table" style="min-width: 1050px">
              <thead>
                <tr class="contact-table">
                  <th scope="row" class="active" width="3%">
                      <label>
                          <input type="checkbox" class="select-all checkbox" name="select-all" />
                      </label>
                  </th>
                  <th scope="col" width="3%">STT</th>
                  <th scope="col" width="12%">Tên tài khoản</th>
                  <th scope="col" width="18%">Nội dung</th>
                  <th scope="col" width="12%">Chuyên mục</th>
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
                   {{$item->group_parent_parent_name??$item->group_parent_name}}
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
                  @if($item->classified && $item->classified->group)
                    <a href="{{route('home.classified.detail',[$item->classified->group->getLastParentGroup(), $item->classified_url])}}" target="_blank" rel="noopener noreferrer">{{$item->classified_url}}</a>
                  @endif
                </td>
                <td class="text-left">
                  <div class="d-flex flex-column">
                    {{-- <div class="ml-2 mb-2">
                      <i class="fa fa-eye mr-2"></i>
                      <a href="{{$item->classified_url}}" class="text-primary">Xem</a>
                    </div> --}}
                    @if($check_role == 1  ||key_exists(7, $check_role))
                    <div class="ml10 mb-2" >
                      <i class="fas fa-times mr12" ></i>
                      <a  class="setting-item delete text-danger action_delete cusor-point" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Xóa</a>
                    </div>
                    @endif
                    @if($check_role == 1  ||key_exists(2, $check_role))
                    @if($item->is_forbidden ==0 && $item->is_locked==0)
                   
                    <div class="ml10 mb-2 cusor-point">
                      <i class="fas fa-times mr12 "></i>
                      <a  class="text-danger forbidden" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Cấm </a>
                    </div>
                    
                    <div class="ml10 mb-2 cusor-point">
                      <i class="fas fa-times mr12 "></i>
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
                      <i class="fas fa-times mr12 "></i>
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
                    <i class="fas fa-times mr12 "></i>
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
                      <i class="fas fa-times mr12 "></i>
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
        </form>
      </div>
      <div class="table-bottom d-flex align-items-center justify-content-between pb-5" style="margin-bottom: 100px !important">
        <div class=" d-flex box-panage align-items-center">
          <div class=" d-flex mb-2">
            <img src="image/manipulation.png" alt="" id="btnTop">
            <div class="btn-group ml-1">
              <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown" aria-expanded="false" data-flip="false" aria-haspopup="true">
                Thao tác
              </button>
              <div class="dropdown-menu ">
                @if($check_role == 1  ||key_exists(2, $check_role))
                <a class="dropdown-item un-forbidden-list" type="button" href="javascript:{}">
                 <i class="fa fa-undo bg-primary p-1 mr-2 rounded"
                 style="color: white !important;font-size: 15px;width: 23px"></i>Mở cấm tài khoản
                 <input type="hidden" name="action" value="trash">
               </a>
               <a class="dropdown-item forbidden-list" type="button" href="javascript:{}">
                 <i class="fa fa-times bg-danger p-1 mr-2 rounded"
                 style="color: white !important;font-size: 16px;width: 23px"></i>Cấm tài khoản
                 <input type="hidden" name="action" value="trash">
               </a>
               <a class="dropdown-item locked-list" type="button" href="javascript:{}">
                 <i class="fa fa-times bg-danger p-1 mr-2 rounded"
                 style="color: white !important;font-size: 16px;width: 23px"></i>Chặn tài khoản
                 <input type="hidden" name="action" value="trash">
               </a>
               <a class="dropdown-item un-locked-list" type="button" href="javascript:{}">
                <i class="fa fa-undo bg-primary p-1 mr-2 rounded"
                style="color: white !important;font-size: 16px;width: 23px"></i>Mở Chặn tài khoản
                <input type="hidden" name="action" value="trash">
              </a>
               @endif
               @if($check_role == 1 || key_exists(5, $check_role))
               <a class="dropdown-item moveToTrash " type="button" href="javascript:{}" >
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
      <div class="d-flex align-items-center justify-content-between mx-4 mb-2">
        <div class="d-flex mr-2 align-items-center">Hiển thị</div>
        <label class="select-custom2">
          <select id="paginateNumber" name="items" onchange="submitPaginate(event)">
            <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
            <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
            <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
          </select>
        </label>
      </div>
      @if($check_role == 1 || key_exists(8, $check_role))
      <div class="d-flex flex-row align-items-center view-trash" style="margin-top: -5px">
        <i class="far fa-trash-alt mr-2" style="margin-top: -5px"></i>
        <div class="link-custom" >
          <a href="{{route('admin.comment.trash-comment-classified')}}"><span  style="color: #347ab6">Xem thùng rác</span>
            <span class="badge badge-pill badge-danger trashnum" style="font-weight: 500;">{{$trash_num}}</span>
          </a>
        </div>
      </div>
      @endif
    </div>
    <div class="d-flex align-items-center">
      <div class="count-item">Tổng cộng: @empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
      @if($list)
      {{ $list->render('Admin.Layouts.Pagination') }}
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
  //----------------------------------------------thao tác đơn
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
        window.location.href = "/admin/comment/classified/locked-account/" + id + "/" + created_by;
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
                    window.location.href = "{{route('admin.comment.locked-account-comment-classified', '')}}/" + id;
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
        window.location.href = "/admin/comment/classified/forbidden-account/" + id + "/" + created_by;
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
        window.location.href = "/admin/comment/classified/forbidden-account/" + id + "/" + created_by;
      }
    });
  });

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
        window.location.href = "/admin/comment/classified/delete/" + id + "/" + created_by;
      }
    });
  });

  //----------------------------------------------thao tác list
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

        $('#formtrash').submit();

      }
    });
  });
  $('.forbidden-list').click(function () {
  $('#formtrash').attr('action', "/admin/comment/classified/forbidden-comment")
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
  $('#formtrash').attr('action', "/admin/comment/classified/un-forbidden-comment")
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
  $('#formtrash').attr('action', "/admin/comment/classified/locked-comment")
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
  $('#formtrash').attr('action', "/admin/comment/classified/un-locked-account-list")
  var id = $(this).data('id');
  Swal.fire({
    title: 'Chặn nhiều tài khoản',
    text: "Các tài khoản đã được chọn sẽ bị chuyển sang trạng thái bỏ chặn",
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
  function submitPaginate(event){
    const uri = window.location.toString();
    const exist = uri.indexOf('?')
    const existItems = uri.indexOf('?items')
    const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
    exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() : window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
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
