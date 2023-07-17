@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách mã khuyến mãi | Mã khuyến mãi')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')

<div class="row m-0 px-3 pt-3">

  <ol class="breadcrumb mt-1">
    <li class="recye px-2 pt-1  check">
      <a href="{{route('admin.promotion.list-news-promotion')}}">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </a>
    </li>
  </ol>
</div>
<h4 class="text-center font-weight-bold mb-3 mt-2">QUẢN LÝ THÙNG RÁC TIN KHUYẾN MÃI</h4>
<!-- Main content -->
<section class="content hiden-scroll">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <form action="{{route('admin.promotion.news-untrashlist')}}" id="formtrash" method="post">
            @csrf
            <table class="table table-bordered text-center table-custom" id="table"  style="min-width: 1050px">
              <thead>
                <tr class="contact-table">
                  <th scope="col" style="width: 3%"><input type="checkbox" class="select-all"></th>
                  <th scope="col" style="width: 4%">ID</th>
                  <th scope="col" style="width: 12%">Hình đại diện</th>
                  <th scope="col" style="width: 18%">Tiêu đề</th>

                  <th scope="col" style="width: 11%">Mã đính kèm</th>
                  <th scope="col" style="width: 10%">Loại</th>
                  
                  <th scope="col" style="width: 25%">Hạn dùng</th>
                  <th scope="col" style="width: 13%">Cài đặt</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($list_promotion_news as $item )

                <tr>
                  <td class="active">
                   <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                   <input type="hidden" class="select-item" value="{{\Crypt::encryptString($item->created_by)}}" name="select_item_created[{{$item->id}}]">
                 </td>
                 
                 <td class="font-weight-bold">{{$item->id}}</td>
                 <td class="font-weight-bold">
                  <div style="width: 100%;max-height: 300px">
                    @if($item->image == null)
                    <img src="{{ asset("system/images/post_promotion/default.png")}}" width="100%" height="100%">
                    @else
                    <img src="{{ asset("system/images/post_promotion")."/".$item->image}}" width="100%" height="100%">
                    @endif
                  </div>
                </td>
                <td >
                  <span class="name-text">{{$item->news_title}}</span>
                  <div class="review-box-main pt-2">
                   <div class="box-review d-flex">
                     <i class="fas fa-edit w-25"></i>
                     <span>Ngày viết: <span >{{\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')}}</span> </span>
                   </div>
                   <div class="view-box d-flex">
                     <i class="fa fa-user w-25 mt-1"></i>
                     <span style="margin-top: 2px">Tác giả: <span>{{$item->admin_fullname}}</span></span>
                   </div>      
                 </div>
               </td>

               <td class="font-weight-bold">
                @if($item->promotion_id != null)
                {{$item->promotion_code}} <br>
                <span class="font-weight-bold">Đã nhận {{$item->used}}/<span class="text-danger">{{$item->num_use}}</span></span>
                @else
                Không đính kèm
                @endif
              </td>
              <td>
                @if($item->promotion_id != null)
                @if($item->promotion_type == 0)
                Mua gói giảm {{$item->value}}%
                @else
                Nạp tiền tặng {{$item->value}}%
                @endif
                @else
                Không đính kèm
                @endif
              </td>


              
              <td class="text-left">

                @if($item->promotion_id != null)
                <span class="font-weight-bold">Ngày bắt đầu: </span><span >{{\Carbon\Carbon::parse($item->date_from)->format('d/m/Y h:i')}}</span><br>
                <span class="font-weight-bold">Ngày kết thúc: </span><span >{{\Carbon\Carbon::parse($item->date_to)->format('d/m/Y h:i')}}</span>
                @else
                Không đính kèm mã
                @endif
              </td>
              
              <td>
                <div>
                  @if($check_role == 1  ||key_exists(6, $check_role))
                  <div>
                    <i class="fas fa-undo-alt ml-0" ></i>
                    <a  class="setting-item delete text-primary action_delete cusor-point" data-id="{{$item->id}}" data-created_by="{{\Crypt::encryptString($item->created_by)}}">Khôi phục</a>
                  </div>
                  @endif
                  <x-admin.force-delete-button
                    :check-role="$check_role"
                    id="{{ $item->id }}"
                    url="{{ route('admin.promotion.news-force-delete-multiple') }}"
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
    
<div class="m-0 mt-3 mb-5" style="width: 100%">
  <div class="w140 float-left mb-2" >
    <div class="manipulation d-flex mr-4">
      <img src="{{ asset("system/images/icons/manipulation.png")}}" id="btnTop">
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
 <div class="float-left mb-2 w150" style="width: 190px">
    <div class="d-flex align-items-center justify-content-between mx-4">
      <div class="d-flex mr-0 align-items-center">Hiển thị</div>
      <label class="select-custom2">
        <select id="paginateNumber" name="items" onchange="submitPaginate(event)">
          <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
          <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
          <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
        </select>
      </label>
    </div>

  </div>
<div class="float-left mb-2 w160">
  <div class="manipulation d-flex mr-4">
   @if($check_role == 1  ||key_exists(8, $check_role))
   <div class="view-trash">
     @if($check_role == 1  ||key_exists(4, $check_role))
     <a href="{{route('admin.promotion.list-news-promotion')}}" class="btn btn-primary">
      Quay lại
    </a>
    @endif
  </div>
  @endif
</div>
</div>
<div class="float-left mb-2" style="width: calc(100% - 800px);"></div>
<div class="float-right mb-2 w370">
 <div class="d-flex align-items-center">
  <div class="count-item" >Tổng cộng: @empty($list_promotion_news) {{0}} @else {{$list_promotion_news->total()}} @endempty items</div>
  <div class="count-item-reponsive" style="display: none">@empty($list_promotion_news) {{0}} @else {{$list_promotion_news->total()}} @endempty items</div>
  <div class="float-right">
   @if($list_promotion_news)
   {{ $list_promotion_news->render('Admin.Layouts.Pagination') }}
   @endif
 </div>
</div>
</div>
<div class="clear-both"></div>
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
        window.location.href = "/admin/promotion/news-undelete/" + id+"/"+created_by;

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
</script>
<script type="text/javascript">
  function submitPaginate(event){
    const uri = window.location.toString();
    const exist = uri.indexOf('?')
    const existItems = uri.indexOf('?items')
    const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
    exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() : window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
  }
</script>
@endsection
