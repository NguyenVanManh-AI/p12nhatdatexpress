@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách yêu cầu | Quản lý dự án')
@section('Style')
@endsection
@section('Content')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
    <div class="box-fiter-reponsive">
        <form action="{{route('admin.request.list')}}" method="GET" id="search_button">
            <div class="row m-0 p-3">

                <div class="col-12 col-sm-12 col-md-3 col-lg-3 p-0">
                    <div class="search-reponsive ">
                        <select class="custom-select" id="chuyenmuc" name="status">
                            <option selected value="">Tình Trạng</option>
                            <option {{(isset($_GET['status']) && $_GET['status'] != ''  && $_GET['status'] == 0 ) ? "selected":""}} value="0">Đang chờ</option>
                            <option {{(isset($_GET['status']) && $_GET['status'] == 1 ) ? "selected":""}} value="1">Đang viết</option>
                            <option {{(isset($_GET['status']) && $_GET['status'] == 2 ) ? "selected":""}} value="2">Đã Hoàn tất</option>
                            <option {{(isset($_GET['status']) && $_GET['status'] == 3 ) ? "selected":""}} value="3">Không viết</option>
                        </select>
                    </div>
                </div>
                <div class="search-reponsive col-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="row m-0">
                        <div onclick="hideTextDateStart()" class="search-reponsive col-12 col-sm-12 col-md-6 col-lg-6">
                            <div style="position: relative">
                                <div id="txtDateStart" style="width:90px; height: 22px;background: #fff;position: absolute;top: 9px;left: 9px">Từ ngày</div>
                                <input class="form-group form-control start_day" id="start_day" name="date_start"
                                       value="{{(isset($_GET['date_start']) && $_GET['date_start']!="")?$_GET['date_start']:""}}"
                                       type="date">
{{--                                <small class="text-danger error-message-custom" style="display: none" id="errorDateStart">--}}
{{--                                </small>--}}
                            </div>
                        </div>
                        <div onclick="hideTextDateEnd()" class="search-reponsive col-12 col-sm-12 col-md-6 col-lg-6">
                            <div style="position: relative">
                                <div id="txtDateEnd" style="width:90px; height: 22px;background: #fff;position: absolute;top: 9px;left: 9px">Đến ngày</div>
                            <input class="form-group form-control mb-0 end_day" id="end_day" name="date_end"
                                   value="{{(isset($_GET['date_end']) && $_GET['date_end']!="")?$_GET['date_end']:""}}"
                                   type="date" placeholder="Từ ngày">
                            {{-- <small class="text-danger error-message-custom " style="display: none" id="messageerror">
                             Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu</small> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="comment-new-reponsive col-12 col-sm-12 col-md-3 col-lg-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-search mr-2" aria-hidden="true"></i>Tìm kiếm
                    </button>
                </div>
            </div>
        </form>
    </div>
    <h4 class="text-center font-weight-bold mt-2 mb-4">DANH SÁCH YÊU CẦU</h4>
    <!-- Main content -->
    <section class="content hiden-scroll">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-custom" id="table">
                            <thead>
                            <tr>
                                <th scope="row" class=" active" style="width: 3%">
                                    <input type="checkbox" class="select-all checkbox" name="select-all"/>
                                </th>
                                <th scope="col" style="width: 4%">STT</th>
                                <th scope="col" style="width: 30%">Tên dự án</th>
                                <th scope="col" style="width: 15%">Chủ đầu tư</th>
                                <th scope="col" style="width: 15%">Ngày yêu cầu</th>
                                <th scope="col" style="width: 15%">
                                    Tình trạng
                                </th>
                                <th scope="col w22" style="width: 30%">Cài đặt</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form action="{{route('admin.request.trashlist')}}" id="formtrash" method="post">
                                @csrf
                                @forelse ( $list_request as $item )
                                    <tr>
                                        <td class="active">
                                            <input value="{{$item->id}}" type="checkbox" class="select-item checkbox"
                                                   name="select_item[]">
                                            <input type="hidden" class="select-item"
                                                   name="select_item_created[{{$item->id}}]">
                                        </td>
                                        <td>{{ ($list_request->currentPage() -1) * $list_request->perPage() + $loop->index + 1 }}</td>
                                        <td class="name-color">
                                            <p>{{$item->project_name}}</p>
                                            <p>{{$item->investor}}</p>
                                            <p>{{$item->address}} {{is_numeric($item->ward_name) ? "Phường " . $item->ward_name : $item->ward_name }}, {{$item->district_name}}, {{$item->province_name}}</p>
                                        </td>

                                        <td class="">{{$item->investor}}</td>
                                        <td>{{date('d/m/Y',$item->created_at)}}</td>
                                        <td>
                                            @if ($item->confirmed_status==0)
                                                <span class="text-gray font-weight-bold">Đang chờ</span>
                                            @endif

                                            @if ($item->confirmed_status==1)
                                                <span class="text-warning font-weight-bold">Đang viết</span>
                                            @endif
                                            @if ($item->confirmed_status==2)
                                                <span class="text-success font-weight-bold">Đã hoàn tất</span>
                                            @endif
                                            @if ($item->confirmed_status==3)
                                                <span class="text-danger font-weight-bold">Không viết</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                @if($item->confirmed_status== 0||$item->confirmed_status== 1)
                                                    <div class="float-left ml-2">
                                                        <i class="fas fa-cog mr-2"></i>
                                                        <a href="{{route('admin.request.write',[$item->id,Crypt::encryptString($item->confirmed_by)])}}"
                                                           class="text-primary ">Viết dự án</a>
                                                    </div>

                                                    <br>
                                                @endif
                                                @if($check_role == 1  ||key_exists(5, $check_role))
                                                    <div class="float-left ml10">
                                                        <i class="fas fa-times mr12"></i>
                                                        <a href="javascript:{}" data-id="{{$item->id}}"
                                                           data-confirmed_by="{{Crypt::encryptString($item->confirmed_by)}}"
                                                           class="text-danger action_delete delete">Xóa</a>
                                                    </div>
                                                    <br>
                                                @endif
                                                @if($item->confirmed_status== 0||$item->confirmed_status== 1)
                                                    <div class="float-left ml-2">
                                                        <i class="fas fa-times mr12"></i>
                                                        <a href="javascript:{}" data-id="{{$item->id}}"
                                                           data-confirmed_by="{{Crypt::encryptString($item->confirmed_by)}}"
                                                           class="text-primary dont_write">Không viết</a>
                                                    </div>

                                                @endif
                                                <div class="clear-both"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <td colspan="7">Chưa có dữ liệu</td>
                                @endforelse
                            </form>
                            </tbody>
                        </table>
                    </div>
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
                            <div class="display d-flex align-items-center mr-4">
                                <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                                <label class="select-custom2">
                                    <select id="paginateNumber" name="items" onchange="submitPaginate(event, this)">
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
                            </div>
                            @if($check_role == 1  ||key_exists(8, $check_role))
                                <div class="view-trash">
                                    <a href="{{route('admin.request.trashrequest')}}"><i class="far fa-trash-alt mr-1"></i>Xem thùng rác</a>
                                    <span class="count-trash">{{$count_trash}}</span>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="count-item">Tổng cộng: @empty($list_request) {{0}} @else {{$list_request->total()}} @endempty
                                items
                            </div>
                            <div class="count-item count-item-reponsive"
                                 style="display: none">@empty($list_request) {{0}} @else {{$list_request->total()}} @endempty items
                            </div>
                            @if($list_request)
                                {{ $list_request->render('Admin.Layouts.Pagination') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css"/>
    <script type="text/javascript">
        // $(document).ready(function () {
        //   $('#chuyenmuc').chosen();
        //   $('#mohinh').chosen();
        //   $('#taikhoandang').chosen();
        // });
    </script>
    <script type="text/javascript">
        $('#quanlyduan').addClass('active');
        $('#danhsachyeucau').addClass('active');
        $('#nav-quanlyduan').addClass('menu-is-opening menu-open');
    </script>
    <!-- /.content -->

@endsection

@section('Script')
    <script src="js/table.js"></script>
    <script>
        jQuery(document).ready(function ($) {
            // date filter
            hiddenInputTextDate('#txtDateStart')
            hiddenInputTextDate('#txtDateEnd')
            setMinMaxDate('.start_day', '.end_day')

        });
        function hideTextDateStart(){
            $('#txtDateStart').hide();
        }
        function hideTextDateEnd(){
            $('#txtDateEnd').hide();
        }

        $('.delete').click(function () {
            var id = $(this).data('id');
            var confirmed_by = $(this).data('confirmed_by');
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
                    window.location.href = "/admin/project/request/delete/" + id + "/" + confirmed_by;
                }
            });
        });
        $('.dont_write').click(function () {
            var id = $(this).data('id');
            var confirmed_by = $(this).data('confirmed_by');
            Swal.fire({
                title: 'Xác nhận trạng thái',
                text: "Sau khi xác nhận sẽ không thể khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/project/request/dont-write/" + id + "/" + confirmed_by;
                }
            });
        });
        $('.moveToTrash').click(function () {
            const selectedArray = getSelected();
            if (!selectedArray) return;
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
    </script>

@endsection
