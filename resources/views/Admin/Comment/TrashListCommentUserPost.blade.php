@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác bình luận bài viết | Quản lý bình luận')
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
      <a href="{{route('admin.comment.list-user-post')}}">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </a>
    </li>


  </ol>
</div>
<h4 class="text-center font-weight-bold">Thùng rác bình luận bài viết</h4>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered text-center table-custom" id="table"  style="min-width: 1050px">
              <thead>
               <tr>
                <th scope="row" class="active" width="3%">
                    <label>
                        <input type="checkbox" class="select-all checkbox" name="select-all" />
                    </label>
                </th>
                <th scope="col" width="3%">STT</th>
                <th scope="col" width="12%">Tên tài khoản</th>
                <th scope="col" width="18%">Nội dung</th>

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
                {{date('d/m/Y H:i',$item->created_at)}}

              </td>

              <td >
               <a href="https://nhadatexpress.vn/user-post/{{$item->post_code}}" target="_blank" rel="noopener noreferrer">https://nhadatexpress.vn/user-post/{{$item->post_code}}</a>

             </td>
              <td >
                <div class="flex-column">
                  <x-admin.restore-button
                    :check-role="$check_role"
                    url="{{ route('admin.comment.user-posts.restore-multiple', ['ids' => $item->id]) }}"
                  />

                  <x-admin.force-delete-button
                    :check-role="$check_role"
                    url="{{ route('admin.comment.user-posts.force-delete-multiple', ['ids' => $item->id]) }}"
                  />
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
        force-delete-url="{{ route('admin.comment.user-posts.force-delete-multiple') }}"
        restore-url="{{ route('admin.comment.user-posts.restore-multiple') }}"
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
        window.location.href = "/admin/comment/user-post/undelete/" + id+"/"+created_by;

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
