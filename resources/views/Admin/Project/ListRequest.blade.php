@extends('Admin.Layouts.Master')

@section('Title', 'Danh sách yêu cầu | Quản lý dự án')

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
                                        <td class="text-left">
                                            <div>
                                                @if($item->confirmed_status== 0||$item->confirmed_status== 1)
                                                    <div class="mb-2 ml-2">
                                                        <span class="icon-small-size mr-1 text-dark">
                                                            <i class="fas fa-cog"></i>
                                                        </span>
                                                        <a href="{{route('admin.request.write',[$item->id,Crypt::encryptString($item->confirmed_by)])}}"
                                                           class="text-primary ">Viết dự án</a>
                                                    </div>
                                                @endif
                                                <x-admin.delete-button
                                                    :check-role="$check_role"
                                                    url="{{ route('admin.request.delete-multiple', ['ids' => $item->id]) }}"
                                                />
                                                @if($item->confirmed_status== 0||$item->confirmed_status== 1)
                                                    <div class="mb-2 ml-2">
                                                        <span class="icon-small-size mr-1 text-dark">
                                                            <i class="fas fa-times"></i>
                                                        </span>
                                                        <a href="javascript:{}" data-id="{{$item->id}}"
                                                           data-confirmed_by="{{Crypt::encryptString($item->confirmed_by)}}"
                                                           class="text-primary dont_write">Không viết</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <td colspan="7">Chưa có dữ liệu</td>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <x-admin.table-footer
                        :check-role="$check_role"
                        :lists="$list_request"
                        :count-trash="$count_trash"
                        view-trash-url="{{ route('admin.request.trash') }}"
                        delete-url="{{ route('admin.request.delete-multiple') }}"
                    />
                </div>
            </div>
        </div>
    </section>
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
    </script>

@endsection
