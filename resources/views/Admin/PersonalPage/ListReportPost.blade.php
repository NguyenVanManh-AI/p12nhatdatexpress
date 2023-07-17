@extends('Admin.Layouts.Master')
@section('Title', 'Báo cáo bài viết | Quản lý trang cá nhân')
@section('Style')
  <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-event.css")}}" />
<style type="text/css">
  .dropdown-toggle::after{
    margin-top: 8px !important;
    float: right !important;
  }
</style>
@endsection
@section('Content')
<div class="row m-0 p-3">
  <div class="col-12 p-0">
    <div class="box-dash mt-4 pt-4">
      <h3 class="title-info-reponsive font-weight-bold">BỘ LỌC</h3>
      <form method="get" action="" >
        <div class="row m-0 pt-2 ">
          <div class="col-12 col-sm-12 col-md-5 col-lg-5 box_input px-0">
           <div class="input-reponsive-search pr-3">
             <input class="form-control required" type="text" name="user_post_name" placeholder="Nhập từ khóa (Tên bài viết)" value="{{ app('request')->input('project_name') }}">
           </div>
         </div>
         <div class="search-reponsive col-12 col-sm-12 col-md-7 col-lg-7 pl-0">
          <div class="row m-0">
            <div class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-0">
              <div class="search-reponsive ">
                <select name="report_content" class="form-control select2" style="width: 100%;height: 34px !important;">
                  <option selected="selected" value="Tất cả">Nội dung báo cáo</option>
                  @if(app('request')->input('report_content'))
                  <option selected="selected" value="{{ app('request')->input('report_content') }}">{{ app('request')->input('report_content') }}</option>
                  @endif
                  @foreach ($getReportReason as $item)
                  <option value="{{$item->content}}">{{$item->content}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div id="from_date_box" class="mtdow10 search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-2 pl-4 ">
              <div  style="position: relative">
                @if(app('request')->input('from_date') == "")
                <div id="from_date_text"  style="position: absolute;width: 60%;height: 38px;padding: 1px;">
                  <div class="bg-white"><span class="ml-2" style="line-height: 36px;">Từ ngày</span></div>
                </div>
                @endif
                <input id="handleDateFrom" class="start_day form-control float-left" name="from_date" type="date" placeholder="Từ ngày" value="{{ app('request')->input('from_date') }}" >
              </div>
            </div>
            <div id="to_date_box" class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2">
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

        </div>
      </div>

      <div class="container pb-3">
        <div class="row">
          <div class="mtdow10 col text-center">
           <button class=" mtdow10 search-button btn btn-primary w-100 mt-1" style="width: 130px !important"><i class="fa fa-search mr-2 ml-0" aria-hidden="true"></i>Tìm kiếm</button>
         </div>
       </div>
     </div>
   </div>
 </form>
</div>
</div>
</div>



<!-- Main content -->
<section class="content mt-3">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <form action="{{-- {{route('admin.project.delete-project-list')}} --}}" id="formtrash" method="post">
            @csrf
            <table class="table table-bordered text-center table-custom" id="table" style="min-width: 1050px !important">
              <thead>
                <tr class="contact-table">
                  <th scope="row" class="active" width="3%">
                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                  </th>
                  <th scope="col" style="width: 3%">STT</th>
                  <th scope="col"  style="width: 18%">Người đăng</th>
                  <th scope="col"  style="width: 18%">Bài viết</th>
                  <th scope="col" style="width: 25%">Nội dung báo cáo</th>

                  <th scope="col" style="width: 9%">Thời gian</th>
                  <th scope="col" style="width: 14%">Tình trạng</th>
                  <th scope="col" style="width: 22%;min-width: 170px">Cài đặt</th>
                </tr>
              </thead>
              <tbody>
                @php
                $countStt = $getListPost->total()+1;
                @endphp
                @foreach ($getListPost as $ListPost)
                @php
                $countStt--;
                @endphp
                <tr>
                  <td class="active">
                    <input type="checkbox" class="select-item" value="{{$ListPost->id}}" name="select_item[]">
                    <input type="hidden" class="select-item" value="{{\Crypt::encryptString($ListPost->created_by)}}" name="select_item_created[{{$ListPost->id}}]">
                  </td>
                  <td>
                    <span>{{$countStt}}</span>
                  </td>
                  <td>
                    <span>{{$ListPost->username}}</span>
                    <br>
                    <span>
                      @if($ListPost->is_forbidden ==1)
                      (Đã cấm)
                      @endif
                      @if($ListPost->is_locked == 1 && time() < $ListPost->lock_time)
                      (Đã chặn 7 ngày)
                      @endif
                    </span>
                  </td>
                  <td>
                    <span class="font-weight-bold ">{{$ListPost->post_content}}</span>

                  </td>
                  <td>
                    <div class="report-reasion toggle-show-more{{$ListPost->id}}" style="font-size:14px">
                        @php
                            $count = 0;
                        @endphp
                        @foreach($getListReport as $ListReport)
                            @if($ListReport->user_post_id == $ListPost->id && $ListReport->report_position == 0)
                                <div class="report-reasion-box mb-2">
                                    <span>{{$ListReport->report_content}} <span>({{$ListReport->count}})</span></span>
                                </div>
                                @php
                                    $count++;
                                @endphp
                            @endif
                        @endforeach
                    </div>
                    @if($count >3)
                    <div id="show-more{{$ListPost->id}}">
                      <p class="text-primary mb-0 cusor-point">Hiện thêm <i class="fa fa-angle-double-down ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>
                    </div>
                    @endif
                  </td>

                  <td class="">
                    <span>{{\Carbon\Carbon::parse($ListPost->report)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y')}}</span>
                  </td>
                  <td class="event-button-dropdow" >

                   @if($ListPost->is_show == 1)
                   <span class="text-success">Hiển thị</span>
                   @else
                   <span class="text-danger">Chặn hiển thị</span>
                   @endif

                 </td>
                 <td class="py-2 px-0">
                  <div>
                    <div class="float-left ml-2 mb-1">
                      <i class="icon-setup fa fa-eye mr-2 " ></i>
                      <a  class="text-primary" style="cursor:pointer">Xem</a>
                    </div>
                    <br>
                    @if($check_role == 1  ||key_exists(2, $check_role))
                        @if($ListPost->confirm_status == 0)
                        <div class="float-left ml-2 mb-1">
                          <i class="icon-setup fa fa-history mr-2 " ></i>
                          <a class="text-primary wrong" href="{{route('admin.personal-page.post.wrong-report', [$ListPost->id, \Crypt::encryptString($ListPost->created_by)])}}" style="cursor:pointer">Báo cáo sai</a>
                        </div>
                        <br>
                        @endif
                        @if($ListPost->is_show == 1)
                        <div class="float-left ml-2 cusor-point mb-1">
                          <i class="icon-setup fa fa-times mr-2 "></i>
                          <a  class="text-danger block-display" href="{{route('admin.personal-page.post.hide-show', [$ListPost->id, \Crypt::encryptString($ListPost->created_by)])}}">Chặn hiển thị</a>
                        </div>
                        <br>
                        @else
                        <div class="float-left ml-2 cusor-point mb-1">
                          <i class="icon-setup fa fa-undo mr-2 "></i>
                          <a  class="text-primary show-display" href="{{route('admin.personal-page.post.hide-show', [$ListPost->id, \Crypt::encryptString($ListPost->created_by)])}}">Mở hiển thị</a>
                        </div>
                        <br>
                        @endif
                    @endif

                    @if($check_role == 1  ||key_exists(2, $check_role))
                        @if($ListPost->is_locked == 1 && time() < $ListPost->lock_time)
                        <div class="float-left ml-2 cusor-point mb-1">
                            <i class="icon-setup fa fa-undo mr-2 "></i>
                            <a  class="text-primary un-locked" href="{{route('admin.personal-page.post.locked-account', [$ListPost->user_id, \Crypt::encryptString($ListPost->created_by)])}}">Mở chặn tài khoản</a>
                        </div>
                        <br>
                        @else
                            <div class="float-left ml-2 cusor-point mb-1">
                                <i class="icon-setup fa fa-times mr-2 "></i>
                                <a class="text-danger locked" href="{{route('admin.personal-page.post.locked-account', [$ListPost->user_id, \Crypt::encryptString($ListPost->created_by)])}}">Chặn tài khoản</a>
                            </div>
                            <br>
                        @endif
                            @if($ListPost->is_forbidden == 1)
                                <div class="float-left ml-2 cusor-point mb-1">
                                    <i class="icon-setup fa fa-undo mr-2 "></i>
                                    <a  class="un-forbidden text-primary" href="{{route('admin.personal-page.post.forbidden-account', [$ListPost->user_id, \Crypt::encryptString($ListPost->created_by)])}}">Mở cấm tài khoản</a>
                                </div>
                                <br>
                            @else
                                <div class="float-left ml-2 cusor-point mb-1">
                                    <i class="icon-setup fa fa-times mr-2 "></i>
                                    <a  class="text-danger forbidden" href="{{route('admin.personal-page.post.forbidden-account', [$ListPost->user_id, \Crypt::encryptString($ListPost->created_by)])}}">Cấm tài khoản</a>
                                </div>
                                <br>
                            @endif
                    @endif
                    @if($check_role == 1  ||key_exists(5, $check_role))
                    <div class="float-left ml-2 cusor-point mb-1">
                      <i class="icon-setup fa fa-times mr-2 "></i>
                      <a  class="text-danger delete" href="{{route('admin.personal-page.post.delete', [$ListPost->id, \Crypt::encryptString($ListPost->created_by)])}}">Xóa bài viết</a>
                    </div>
                    <br>
                    @else
                    @if(key_exists(7, $check_role) == false && key_exists(2, $check_role) == false)
                    <span>Không đủ quyền</span>
                    @endif
                    @endif
                    <div class="clear-both"></div>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>


      <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5" style="width: 100%;margin-bottom: 125px !important">
       <div class="text-left d-flex align-items-center">
         <div class="manipulation d-flex mr-4 ">
           <img src="image/manipulation.png" alt="" id="btnTop">
           <div class="btn-group ml-1">
             <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
             aria-expanded="false" data-flip="false" aria-haspopup="true">
             Thao tác
           </button>
           <div class="dropdown-menu">
            @if($check_role == 1  ||key_exists(2, $check_role))
            <a class="dropdown-item wrong-list" type="button" href="{{route('admin.personal-page.post.wrong-report-list')}}">
             <i class="fa fa-undo bg-primary p-1 mr-2 rounded" style="color: white !important;font-size: 15px;width: 23px"></i>Báo cáo sai
             <input type="hidden" name="action" value="trash">
           </a>
           <a class="dropdown-item un-forbidden-list" type="button" href="{{route('admin.personal-page.post.un-forbidden-account-list')}}">
             <i class="fa fa-undo bg-primary p-1 mr-2 rounded" style="color: white !important;font-size: 15px;width: 23px"></i>Mở cấm tài khoản
             <input type="hidden" name="action" value="trash">
           </a>
           <a class="dropdown-item forbidden-list" type="button" href="{{route('admin.personal-page.post.forbidden-account-list')}}">
             <i class="fa fa-times bg-danger p-1 mr-2 rounded" style="color: white !important;font-size: 16px;width: 23px"></i>Cấm tài khoản
             <input type="hidden" name="action" value="trash">
           </a>
           <a class="dropdown-item unlocked-list" type="button" href="{{route('admin.personal-page.post.unlocked-account-list')}}">
             <i class="fa fa-undo bg-primary p-1 mr-2 rounded" style="color: white !important;font-size: 16px;width: 23px"></i>Mở chặn tài khoản
             <input type="hidden" name="action" value="trash">
           </a>
           <a class="dropdown-item locked-list" type="button" href="{{route('admin.personal-page.post.locked-account-list')}}">
             <i class="fa fa-times bg-danger p-1 mr-2 rounded" style="color: white !important;font-size: 16px;width: 23px"></i>Chặn tài khoản
             <input type="hidden" name="action" value="trash">
           </a>
           @endif
           @if($check_role == 1  ||key_exists(5, $check_role))
           <a class="dropdown-item moveToTrash" type="button" href="{{route('admin.personal-page.post.delete-project-list')}}">
             <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded" style="color: white !important;font-size: 15px"></i>Xóa bài viết
             <input type="hidden" name="action" value="trash">
           </a>
           @endif
         </div>
       </div>
     </div>

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

      {{-- @if($check_role == 1  ||key_exists(8, $check_role))
      <div class="view-trash">
       <a href="{{route('admin.promotion.trash')}}"><i class="far fa-trash-alt"></i> Xem thùng rác</a>
       <span class="count-trash">
        @if(isset($count_trash))
        {{$count_trash}}
        @endif
      </span>
    </div>
    @endif --}}
  </div>
  <div class="d-flex align-items-center">
    <div class="count-item">Tổng cộng: @empty($ListPost) {{0}} @else {{$getListPost->total()}} @endempty items</div>
    @if($getListPost)
    {{ $getListPost->render('Admin.Layouts.Pagination') }}
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
        @foreach($getListPost as $ListPost)
        $('#show-more{{$ListPost->id}}').click(function(){
            $('.toggle-show-more{{$ListPost->id}}').toggleClass("show-more");
            $('#show-more{{$ListPost->id}}').html($('#show-more{{$ListPost->id}}').html() == '<p class="text-primary mb-0 cusor-point text-dark">Thu gọn <i class="fa fa-angle-double-up ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>' ? '<p class="text-primary mb-0 cusor-point">Hiện thêm <i class="fa fa-angle-double-down ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>' : '<p class="text-primary mb-0 cusor-point text-dark">Thu gọn <i class="fa fa-angle-double-up ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>');
        })

        $('#show-more2{{$ListPost->id}}').click(function(){
            $('.toggle-show-more{{$ListPost->id}}').toggleClass("show-more");
            $('#show-more2{{$ListPost->id}}').html($('#show-more2{{$ListPost->id}}').html() == '<p class="text-primary mb-0 cusor-point text-dark">Thu gọn <i class="fa fa-angle-double-up ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>' ? '<p class="text-primary mb-0 cusor-point">Hiện thêm <i class="fa fa-angle-double-down ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>' : '<p class="text-primary mb-0 cusor-point text-dark">Thu gọn <i class="fa fa-angle-double-up ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>');
        })
        @endforeach
    </script>
    <script type="text/javascript">
        $('#show-more').click(function(){
            $('.report-reasion').toggleClass("show-more");
            $('#show-more').html($('#show-more').html() == '<p class="text-primary mb-0 cusor-point text-dark">Thu gọn <i class="fa fa-angle-double-up ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>' ? '<p class="text-primary mb-0 cusor-point">Hiện thêm <i class="fa fa-angle-double-down ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>' : '<p class="text-primary mb-0 cusor-point text-dark">Thu gọn <i class="fa fa-angle-double-up ml-1" aria-hidden="true" style="font-size: 60%;"></i></p>');
        })
    </script>
<script type="text/javascript">
    $('#from_date_box').click(function(){
        $('#from_date_text').hide();
    })
    $('#to_date_box').click(function(){
        $('#to_date_text').hide();
    })
    setMinMaxDate('#handleDateFrom', '#handleDateTo')
</script>

<script type="text/javascript">
    $('.block-display').click(function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Chặn hiển thị',
            text: "Bài viết sẽ được đưa về trạng thái chặn hiển thị",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = $(this).attr('href');
            }
        });
    });
    $('.show-display').click(function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hiển thị',
            text: "Bài viết sẽ được đưa về trạng thái hiển thị",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = $(this).attr('href');
            }
        });
    });
    $('.wrong').click(function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Xóa báo cáo',
            text: "Báo cáo sai, xóa yêu cầu này",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = $(this).attr('href');
            }
        });
    });

    $('.forbidden').click(function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Cấm tài khoản',
            text: "Tài khoản sẽ bị cấm lâu dài cho tới khi được admin gỡ cấm!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = $(this).attr('href');
            }
        });
    });
    $('.un-forbidden').click(function (e) {
        e.preventDefault();
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
                window.location.href = $(this).attr('href');
            }
        });
    });
    $('.locked').click(function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Chặn tài khoản',
            text: "Tài khoản sẽ bị chặn 7 ngày!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = $(this).attr('href');
            }
        });
    });

    $('.un-locked').click(function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Mở chặn tài khoản',
            text: "Tài khoản sẽ được mở chặn!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = $(this).attr('href');
            }
        });
    });

    $('.delete').click(function () {
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
                window.location.href = $(this).attr('href');
            }
        });
    });
    $('.wrong-list').click(function (e) {
        e.preventDefault();
        $('#formtrash').attr('action', $(this).attr('href'))
        var id = $(this).data('id');
        Swal.fire({
            title: 'Xác nhận chọn nhiều báo cáo sai',
            text: "Sau khi xác nhận các báo cáo sẽ được chuyển sang trạng thái đã xử lý",
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

    $('.moveToTrash').click(function (e) {
        e.preventDefault();
        $('#formtrash').attr('action', $(this).attr('href'))
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

    $('.forbidden-list').click(function (e) {
        e.preventDefault();
        $('#formtrash').attr('action', $(this).attr('href'))
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
    $('.un-forbidden-list').click(function (e) {
        e.preventDefault();
        $('#formtrash').attr('action', $(this).attr('href'))
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
    $('.locked-list').click(function (e) {
        e.preventDefault();
        $('#formtrash').attr('action', $(this).attr('href'))
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

    $('.unlocked-list').click(function (e) {
        e.preventDefault();
        $('#formtrash').attr('action', $(this).attr('href'))
        Swal.fire({
            title: 'Mở chặn nhiều tài khoản',
            text: "Các tài khoản đã được chọn sẽ bị chuyển sang trạng thái hoạt động",
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
