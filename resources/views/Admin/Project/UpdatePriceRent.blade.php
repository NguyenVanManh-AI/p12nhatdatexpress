@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý cập nhật | Quản lý dự án')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
<style type="text/css">
    .button-search1{
        height: 35px;line-height: 13px;border-radius: 0;
    }
    .button-search1>i{
        font-size: 80%
    }
    .count {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: #ff0000;
        color: #fff;
        font-weight: 600;

        line-height: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 10px;
        right: 5px;
    }
    .icon-box-main{
        width: 60%;margin: auto;
    }
    .icon-box-main .row .col-12 >div{
        width: 130px;height: 130px;margin: auto;position: relative
    }
    .cusor-point{
        cursor: pointer
    }
    .icon-box-main .row .col-12 >div>img{
        border: 5px solid #448ccb;border-radius: 50%;margin: auto
    }


    .button-comfirm{
        height: 30px;line-height: 15px
    }
    .button-reset{
        height: 30px;line-height: 15px;background: #00bff3
    }
    .count-trash {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #ff0000;
        color: #fff;
        font-size: 11px;
        text-align: center;
        line-height: 20px;
        margin-left: 5px;
    }

    @media only screen and (max-width: 600px) {
        .count-item-reponsive{
            display: block !important;
            margin-right: 8px;
        }
        .count-item{
            display: none;
        }
    }
    @media only screen and (max-width: 779px) {
        .search-reponsive{
            padding: 0 !important;
            margin-bottom: 20px !important;
        }
        .comment-new-reponsive{
            margin-top: -20px !important;
            padding: 0 !important;
        }
        .list-title-text{
            font-size: 120% !important;
        }
    }
    /* checkbox */
</style>
@endsection
@section('Content')
<form method="get" action="{{route('admin.project.update-price')}}">
  <div class="row m-0 p-3">
    <div class="col-12 col-sm-12 col-md-5 col-lg-5 p-0">
      <div  class="search-reponsive pr-5">
        <input class="form-control required" type="" name="name_project" placeholder="Nhập tên dự án" {{ app('request')->input('name_project') }}>
      </div>
    </div>
    <div class="search-reponsive col-12 col-sm-12 col-md-5 col-lg-5 pr-5 pl-0">
      <div class="row m-0">
        <div id="from_date_box" class=" search-reponsive input-reponsive-search col-12 col-sm-12 col-md-6 col-lg-6 pr-2 pl-0 ">
          <div  style="position: relative">
            @if(app('request')->input('from_date') == "")
            <div id="from_date_text"  style="position: absolute;width: 60%;height: 38px;padding: 1px;">
              <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Từ ngày</span></div>

            </div>
            @endif
            <input id="handleDateFrom" class="form-control float-left" name="from_date" type="date" placeholder="Từ ngày" value="{{ app('request')->input('from_date') }}" >
          </div>
        </div>
        <div id="to_date_box" class=" search-reponsive input-reponsive-search col-12 col-sm-12 col-md-6 col-lg-6 pl-3 pr-0">
          <div  style="position: relative">
           @if(app('request')->input('to_date') == "")
           <div id="to_date_text" style="position: absolute;width: 60%;height: 38px;padding: 1px;">
            <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Đến ngày</span></div>
          </div>
          @endif
          <input id="handleDateTo" class="form-control float-right" name="to_date" type="date" placeholder="Đến ngày" value="{{ app('request')->input('to_date') }}" >
          <div id="appendDateError"></div>
        </div>
      </div>

    </div>
  </div>
  <div class="comment-new-reponsive col-12 col-sm-12 col-md-2 col-lg-2 pl-0 pr-0">
   <button type="submit" class="button-search1 btn btn-primary w-100"><i class="fa fa-search mr-2" aria-hidden="true"></i>Tìm kiếm</button>
 </div>
</div>
</form>
<div class="mb-4 icon-box-main">
  <div class="row m-0">
    <div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 p-3 mb-4 cusor-point" >
      <div>
        <a href="{{route('admin.project.update-manage')}}">
          <img src="{{ asset("system/images/icons/update1.png")}}" width="100%" height="100%" style="border: 5px solid #d7d7d7;border-radius: 50%">
        </a>
        <span class="count">{{$countUpdateProgress}}</span>
        <p class="text-center mt-2">Cập nhật tình trạng</p>
      </div>
    </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 p-3 mb-4 cusor-point" >
    <div>
      <a href="{{route('admin.project.update-price-rent')}}">
        <img src="{{ asset("system/images/icons/update2.png")}}" width="100%" height="100%" style="border: 5px solid #007bff;border-radius: 50%">
      </a>
      <span class="count">{{$countUpdatePriceRent}}</span>
      <p class="text-center mt-2">Cập nhật giá thuê</p>
    </div>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 p-3 mb-4 cusor-point">
    <div>
      <a href="{{route('admin.project.update-price')}}">
        <img src="{{ asset("system/images/icons/update3.png")}}" width="100%" height="100%" style="border: 5px solid #d7d7d7;border-radius: 50%">
      </a>
      <span class="count">{{$countUpdatePrice}}</span>
      <p class="text-center mt-2">Cập nhật giá bán</p>
    </div>
  </div>
</div>
</div>

<h4 class="list-title-text text-center font-weight-bold mt-4 mb-4">QUẢN LÝ DANH SÁCH CẬP NHẬT</h4>
<!-- Main content -->
<section class="content hiden-scroll">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <table class="table table-bordered text-center table-custom" id="table">
            <thead>
              <tr>

                <th scope="col" style="width: 4%">ID</th>
                <th scope="col" style="width: 30%">Tên dự án</th>
                <th scope="col"  style="width: 13%">Giá hiện tại</th>
                <th scope="col" style="width: 13%">Giá thay đổi</th>
                <th scope="col" style="width: 13%">Ngày thay đổi</th>
                <th scope="col" style="width: 13%">Trạng thái</th>
                <th scope="col" style="width: 20%;min-width: 150px">Cài đặt</th>
              </tr>
            </thead>
            <tbody>

              @forelse ($listUpdate as $item)
              <tr>
                <td>{{($listUpdate->currentPage() - 1) * $listUpdate->perPage() + $loop->index + 1}}</td>
                <td>
                  <a class="name-text" href="">{{$item["project_name"]}}</a>
                </td>
                <td>{{number_format($item["price_old"], 0, '.', '.')}}</td>
                <td>{{number_format($item["project_price_new"], 0, '.', '.')}}</td>
                <td>
                    @if($item['change_date'])
                    {{\Carbon\Carbon::parse($item["change_date"])->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y')}}
                    @else
                        ---
                    @endif
                </td>

                  @if($item["confirm"] == 1 )
                      <td class="text-success">Đã xác thực</td>
                  @elseif($item["confirm"] ==2)
                      <td class="text-secondary">Đã hoàn tác</td>
                  @elseif($item['change_date'])
                      <td style="color: #fd7e14">Đã tự động thay đổi</td>
                  @elseif($item["confirm"] ==0)
                      <td style="color: #fd7e14">Chờ xác thực</td>
                  @endif

                <td>
                  @if($check_role == 1  ||key_exists(2, $check_role))
                  <div class="px-3 py-1">
                    @if($item["confirm"] ==0 || $item["confirm"] ==2)
                    <a class="confirm" data-id="{{$item["id"]}}" data-index="{{$item["index"]}}">
                      <button class="button-comfirm btn btn-success w-100">Xác thực</button>
                    </a>
                    @else
                    <button class="button-comfirm btn btn-secondary w-100" disabled="">Xác thực</button>
                    @endif

                    @if($item["confirm"] != 2)
                        <a class="recover" data-id="{{$item["id"]}}" data-index="{{$item["index"]}}">
                            <button class="button-reset btn w-100 mt-2 text-white">Hoàn tác</button>
                        </a>
                    @elseif($item["confirm"] == 2)
                        <button class="button-comfirm btn btn-secondary w-100 mt-2" disabled="">Hoàn tác</button>
                    @elseif($item['change_date'])
                        <a class="recover" data-id="{{$item["id"]}}" data-index="{{$item["index"]}}">
                            <button class="button-reset btn w-100 mt-2 text-white">Hoàn tác</button>
                        </a>
                    @else
                        <button class="button-comfirm btn btn-secondary w-100 mt-2" disabled="">Hoàn tác</button>
                    @endif

                 </div>
                 @else
                 <span>Không đủ quyền</span>
                 @endif
               </td>
             </tr>
              @empty
                  <tr><td colspan="7">Chưa có dữ liệu</td></tr>
             @endforelse
           </tbody>
         </table>
       </div>



       <div class=" m-0 mt-3 mb-5"  style="width: 100%">
        <div class="w140 float-left mb-2" >
          <div class="manipulation d-flex mr-4">
            <img src="{{ asset("system/images/icons/manipulation.png")}}">
            <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
            aria-expanded="false" data-flip="false" aria-haspopup="true">
            Thao tác
          </button>
          {{-- <div class="dropdown-menu">
            @if($check_role == 1  ||key_exists(5, $check_role))
            <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
             <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"
             style="color: white !important;font-size: 15px"></i>Thùng rác
             <input type="hidden" name="action" value="trash">
           </a>
           @endif
         </div> --}}
       </div>
     </div>
     <div class="float-left mb-2 w150" style="width: 210px">
      <div class=" d-flex mr-4 pl-3">
       <div class="d-flex align-items-center justify-content-between mr-2">
        <div class="d-flex mr-0 align-items-center mr-2">Hiển thị</div>
        <label class="select-custom2">
          <select id="paginateNumber" name="items" onchange="submitPaginate(event)">
            <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
            <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
            <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
          </select>
        </label>
      </div>
    </div>
  </div>

  <div class="float-left mb-2" style="width: calc(100% - 800px);"></div>
  <div class="float-right mb-2 ">
   <div class="d-flex align-items-center">
    <div class="count-item" >Tổng cộng: @empty($listUpdate) {{0}} @else {{$listUpdate->total()}} @endempty items</div>
    <div class="count-item-reponsive" style="display: none">@empty($listUpdate) {{0}} @else {{$listUpdate->total()}} @endempty items</div>
    <div class="float-right">
     @if($listUpdate)
     {{ $listUpdate->render('Admin.Layouts.Pagination') }}
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
<script type="text/javascript">
    $('#from_date_box').click(function(){
        $('#from_date_text').hide();
    })
    $('#to_date_box').click(function(){
        $('#to_date_text').hide();
    })
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
<script src="js/table.js"></script>
<script type="text/javascript">
  function submitPaginate(event){
    const uri = window.location.toString();
    const exist = uri.indexOf('?')
    const existItems = uri.indexOf('?items')
    const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
    exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() : window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
  }

  $('.confirm').click(function () {
    var id = $(this).data('id');
    var index = $(this).data('index');
    Swal.fire({
      title: 'Xác thực',
      text: "Bạn xác thực thay đổi này là đúng?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      cancelButtonText: 'Quay lại',
      confirmButtonText: 'Đồng ý'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "/admin/project/update-manage/confirm-update-price-rent/" + id + "/" + index;
      }
    });
  });
  $('.recover').click(function () {
    var id = $(this).data('id');
    var index = $(this).data('index');
    Swal.fire({
      title: 'Hoàn tác',
      text: "Khi hoàn tác, Giá thuê sẽ được đưa về lúc chưa cập nhật",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      cancelButtonText: 'Quay lại',
      confirmButtonText: 'Đồng ý'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "/admin/project/update-manage/recover-update-price-rent/" + id + "/" + index;
      }
    });
  });
</script>
@endsection
