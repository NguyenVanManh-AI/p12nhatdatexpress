@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác | Chiến dịch email')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
<style type="text/css">
  {{-- css reponsive panagetion --}}
  @media only screen and (max-width: 779px) {
    .box-panage{
      display:inline !important;
    }
  }
</style>
<div class="row m-0 px-3 pt-3">

  <ol class="breadcrumb mt-1">
    <li class="recye px-2 pt-1 check">
          {{-- đường dẫn đến danh sách --}}
      <a href="{{route('admin.email-campaign.list-template')}}">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </a>
    </li>
  </ol>
</div>
<h4 class="text-center font-weight-bold">THÙNG RÁC MẪU EMAIL</h4>
<!-- Main content -->
<section class="content hiden-scroll">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <form action="{{route('admin.email-campaign.un-delete-mail-template-list')}}" id="formtrash" method="post">
            @csrf
            <table class="table table-bordered text-center table-custom" id="table"  style="min-width: 1050px">
              <thead>
               <tr>
                <th scope="row" class="active" width="3%">
                  {{-- chọn tất cả --}}
                  <input type="checkbox" class="select-all checkbox" name="select-all" />
                </th>
                <th scope="col" width="3%">ID</th>
                <th scope="col" width="30%">Tiêu đề</th>
                <th scope="col" width="12%">Ngày tạo</th>
                <th scope="col" width="10%">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              {{-- lặp lấy ra danh sách --}}
              @forelse($list as $item)
                <tr>
                  <td class="active">
                    {{-- chọn nhiều dòng --}}
                    <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                    <input type="hidden" class="select-item" value="{{\Crypt::encryptString($item->created_by)}}" name="select_item_created[{{$item->id}}]">
                  </td>
                  {{-- id mẫu mail --}}
                  <td >{{$item->id}}</td>

                 <td>
                  {{-- tiêu đề mail --}}
                  {{$item->template_title}}
                </td>
                <td>
                  {{-- ngày tạo --}}
                  {{date('d/m/Y H:i',$item->created_at)}}
                </td>
                <td class="text-left">
                  <div>
                    {{-- kiểm tra quyền khôi phục --}}
                    @if($check_role == 1  ||key_exists(6, $check_role))
                    <div>
                      <i class="fas fa-undo-alt mr-2" ></i>
                      <a  class="setting-item delete text-primary action_delete cusor-point" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Khôi phục</a>
                    </div>
                    @endif
                    <x-admin.force-delete-button
                      :check-role="$check_role"
                      id="{{ $item->id }}"
                      url="{{ route('admin.email-campaign.mail-template-force-delete-multiple') }}"
                    />
                  </div>
                </td>
            </tr>
            @empty
            <tr>
              <td colspan="9">Chưa có dữ liệu</td>
            </tr>
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
          {{-- scroll lên dầu --}}
          <img src="image/manipulation.png" alt="" id="btnTop">
          <div class="btn-group ml-1">
            <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
            aria-expanded="false" data-flip="false" aria-haspopup="true">
            Thao tác
          </button>
          <div class="dropdown-menu">
            {{-- kiểm tra quyền khôi phục --}}
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
           {{-- số dòng hiển thị danh sách --}}
          <select id="paginateNumber" name="items" onchange="submitPaginate(event)">
            <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
            <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
            <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
          </select>
        </label>
      </div>
      <div>
         {{-- kiểm tra phân quyền quản lý danh sách --}}
        @if($check_role == 1  ||key_exists(4, $check_role))
        <a href="{{route('admin.email-campaign.list-template')}}" class="btn btn-primary">
          Quay lại
        </a>
        @endif
      </div>
    </div>
    <div class="d-flex align-items-center">
       {{-- tổng cộng mẫu mail --}}
      <div class="count-item">Tổng cộng: @empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
      @if($list)
      {{-- phân trang --}}
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

<script>
  {{-- nếu lỗi trả về lớn hơn 0 --}}
  @if(count($errors) > 0)
  {{-- lặp và hiển thị ra giao diện các lỗi đó --}}
  toastr.error("Vui lòng kiểm tra các trường");
  @endif
</script>

<script>
  //khôi phục 1 mẫu mail
  {{-- click nút có class là delete --}}
  $('.delete').click(function () {
    {{-- lấy ra id --}}
    var id = $(this).data('id');
    {{-- lấy ra người tạo --}}
    var created_by = $(this).data('created_by');
    {{-- Hiển thị thông báo xác nhận xóa --}}
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
      {{-- nếu nhấn đồng ý --}}
      if (result.isConfirmed) {
        {{-- truy cập link để xóa item --}}
        window.location.href = "/admin/mail-campaign/template/un-delete-mail-template/" + id+"/"+created_by;

      }
    });
  });
  //khôi phục nhiều item
  //click button có class unToTrash
  $('.unToTrash').click(function () {
    //lấy ra id 
    var id = $(this).data('id');
    //hiển thị thông báo
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
      //nếu nhấn xác nhận
      if (result.isConfirmed) {
      //submit form
        $('#formtrash').submit();

      }
    });
  });

  //Phân trang
  function submitPaginate(event){
    //lấy ra uri
    const uri = window.location.toString();
    const exist = uri.indexOf('?')
    const existItems = uri.indexOf('?items')
    const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
    exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() : window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
  }
</script>

@endsection
