@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác bình luận dự án | Quản lý bình luận')
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
</style>
<div class="row m-0 px-3 pt-3">
  
  <ol class="breadcrumb mt-1">
    <li class="recye px-2 pt-1  check">
      <a href="{{route('admin.comment.list-project')}}">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </a>
    </li>


  </ol>
</div>
<h4 class="text-center font-weight-bold">Thùng rác bình luận dự án</h4>
<!-- Main content -->
<section class="content hiden-scroll">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <form action="{{route('admin.comment.untrashlist-comment-project')}}" id="formtrash" method="post">
            @csrf
            <table class="table table-bordered text-center table-custom" id="table"  style="min-width: 1050px">
              <thead>
               <tr>
                <th scope="row" class="active" width="3%">
                  <input type="checkbox" class="select-all checkbox" name="select-all" />
                </th>
                <th scope="col" width="3%">STT</th>
                <th scope="col" width="12%">Tên tài khoản</th>
                <th scope="col" width="18%">Nội dung</th>
                <th scope="col" width="12%">Chuyên mục</th>                          
                <th scope="col" width="10%">Thời gian</th>
               
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
                    <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
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
                      (Đã chặn 1 tuần)
                      @endif
                    </span>

                  </td>
                  <td class="title_role text-wrap text-left" style="word-break: break-word;">
                    <p class="mb-0" style="e-height: 1.5em;height: 3em;overflow: hidden;
                    ">{{$item->comment_content}}</p>
                    
                  </td>
                  <td class="title_role text-wrap" style="word-break: break-word">
                   {{$item->group_name}}
                 </td>
                 <td class="title_role text-wrap" style="word-break: break-word">
                  {{date('d/m/Y H:i',$item->created_at)}}

                </td>

                <td >
                  <a href="{{$item->project_url}}" target="_blank" rel="noopener noreferrer">{{$item->project_url}}</a>

                </td>
                <td class="text-left">
                  <div>

                    @if($check_role == 1  ||key_exists(6, $check_role))
                    <div>
                      <i class="fas fa-undo-alt mr-2" ></i>
                      <a  class="setting-item delete text-primary action_delete cusor-point" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Khôi phục</a>
                    </div>
                    @endif
                    <x-admin.force-delete-button
                      :check-role="$check_role"
                      id="{{ $item->id }}"
                      url="{{ route('admin.comment.list-project-force-delete-multiple') }}"
                    />
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

      <form action="" class="force-delete-item-form d-none" method="POST">
        @csrf
        <input type="hidden" name="ids">
      </form>

      <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
        <div class="text-left d-flex box-panage align-items-center mt-3">
          <div class="manipulation d-flex mr-4">
            <img src="image/manipulation.png" alt="" id="btnTop">
            <div class="btn-group ml-1">
              <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
              aria-expanded="false" data-flip="false" aria-haspopup="true">
              Thao tác
            </button>
            <div class="dropdown-menu">
              @if($check_role == 1 ||key_exists(6, $check_role))
              <a class="dropdown-item unToTrash" type="button" href="javascript:{}">
                <i class="fas fa-undo-alt bg-red p-1 mr-2 rounded"
                style="color: white !important;font-size: 15px"></i>Khôi phục
                <input type="hidden" name="action" value="restore">
              </a>
              @endif
            </div>
          </div>

        </div>
        <div class="d-flex align-items-center justify-content-between mr-4 mb-2 mt-1">
          <div class="d-flex mr-2 align-items-center">Hiển thị</div>
          <label class="select-custom2">
            <select id="paginateNumber" name="items" onchange="submitPaginate(event)">
              <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
              <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
              <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
            </select>
          </label>
        </div>
        <div>
          @if($check_role == 1  ||key_exists(4, $check_role))
          <a href="{{route('admin.comment.list-project')}}" class="btn btn-primary">
            Quay lại
          </a>
          @endif
        </div>
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
<script src="js/table.js"></script>
<script type="text/javascript">
  $('#makhuyenmai').addClass('active');
  $('#nav-makhuyenmai').addClass('menu-is-opening menu-open');
</script>

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
        window.location.href = "/admin/comment/project/undelete/" + id+"/"+created_by;

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

        $('#formtrash').submit();

      }
    });
  });

  function submitPaginate(event){
    const uri = window.location.toString();
    const exist = uri.indexOf('?')
    const existItems = uri.indexOf('?items')
    const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
    exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() : window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
  }
</script>

@endsection
