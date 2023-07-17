@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách sự kiện | Quản lý sự kiện')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/main.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/plusb.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/responsivehao.css")}}">
@endsection

@section('Content')

    <!-- Main content -->
    <section class="content hiden-scroll mt-3">
        <div class="container-fluid">
            <!-- Main row -->

            <div class="table-contents">

                <table class="table">
                    <thead>
                    <tr>
                        <th><input type="checkbox" class="select-all checkbox"></th>
                        <th>STT</th>
                        <th class="dropdown" style="min-width: 150px">
                            Tình trạng
                            {{--                        <a href="#" class="nav-link" data-toggle="dropdown">Tình trạng</a>--}}
                            {{--                        <div class="dropdown-menu dropdown-menu-right">--}}
                            {{--                            <a href="#" class="dropdown-item">--}}
                            {{--                                Đã duyệt--}}
                            {{--                            </a>--}}
                            {{--                            <a href="#" class="dropdown-item">--}}
                            {{--                                Chờ duyệt--}}
                            {{--                            </a>--}}
                            {{--                            <a href="#" class="dropdown-item">--}}
                            {{--                                Không duyệt--}}
                            {{--                            </a>--}}
                            {{--                            <a href="#" class="dropdown-item">--}}
                            {{--                                Hết hạn--}}
                            {{--                            </a>--}}
                            {{--                        </div>--}}
                        </th>
                        <th>Sự kiện</th>
                        <th>Địa điểm</th>
                        <th style="min-width: 150px">Thời gian tổ chức</th>
                        <th>Cài đặt</th>
                    </tr>
                    </thead>

                    <tbody>
                    <form action="{{route('admin.event.action')}}" id="formAction" method="post">
                        @csrf
                        @forelse($list as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" value="{{$item->id}}" data-name="nnnn" class="select-item checkbox" name="select_item[]" />
                                    <input type="hidden" hidden name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}">
                                </td>
                                <td>{{$item->id}}</td>
                                <td class="
                                    @if($item->is_confirmed == 0)
                                    {{"bg-orange-light"}}
                                    @elseif($item->is_confirmed == 1)
                                    {{"bg-green-light"}}
                                    @elseif($item->is_confirmed == 2)
                                    {{"bg-red-light"}}
                                    @elseif($item->end_date < time() || $item->is_confirmed == 3)
                                    {{"bg-gray-medium"}}
                                    @endif">
                                    <select name="is_confirmed" id="is_confirmed" class="custom-select" style="pointer-events: none;">
                                        <option value="0" {{$item->is_confirmed == 0 ? 'selected' : ''}}>Chờ duyệt</option>
                                        <option value="1" {{$item->is_confirmed == 1 ? 'selected' : ''}}>Đã duyệt</option>
                                        <option value="3" {{$item->is_confirmed == 3 ? 'selected' : ''}}>Hết hạn</option>
                                        <option value="2" {{$item->is_confirmed == 2 ? 'selected' : ''}}>Không duyệt</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="text-left title"><a href="#">{{$item->event_title}}</a></div>
                                    <div class="text-left mt-2">Đơn vị tổ chức: {{ data_get($item->bussiness, 'user_detail.fullname') }}</div>
                                </td>
                                <td>
                                    {{ $item->getLocationAddress() }}
                                    {{-- {{$item->location->district->district_name}}, {{$item->location->province->province_name}} --}}
                                </td>
                                <td>
                                    <p class="time mb-1">{{date('G', $item->start_date)}}h{{date('i', $item->start_date)}}</p>
                                    <p class="date">{{date('d/m/Y', $item->start_date)}}</p>
                                </td>
                                <td class="text-left">
                                    @if( $check_role == 1 || key_exists(6, $check_role))
                                        <div class="mb-2">
                                            <i class="fas fa-undo-alt mr-2"></i>
                                            <a href="javascript:{}" class="text-primary action_restore" data-c="{{$item->created_by}}" data-id="{{$item->id}}" data-created="{{\Crypt::encryptString($item->created_by)}}">Khôi phục</a>
                                        </div>
                                    @endif
                                    <x-admin.force-delete-button
                                        :check-role="$check_role"
                                        id="{{ $item->id }}"
                                        url="{{ route('admin.event.force-delete-multiple') }}"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">Chưa có dữ liệu</td>
                            </tr>
                        @endforelse
                    </form>
                    </tbody>
                </table>
            </div>

            <form action="" class="force-delete-item-form d-none" method="POST">
                @csrf
                <input type="hidden" name="ids">
            </form>

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
                                @if($check_role == 1 || key_exists(6, $check_role))
                                    <a class="dropdown-item restoreItem" type="button" href="javascript:{}">
                                        <i class="fas fa-undo-alt bg-blue p-1 mr-2 rounded text-center" style="color: white !important;font-size: 13px; width: 23px"></i>Khôi phục
                                    </a>
                                @else
                                    <p class="dropdown-item m-0 disabled">
                                        Bạn không có quyền
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mx-4">
                        <div class="d-flex align-items-center mr-2">Hiển thị</div>
                        <form action="{{route('admin.project.list')}}" method="GET">
                            <label class="select-custom2">
                                <select id="paginateNumber" name="items" onchange="submitPaginate(event, this)">
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
                                    <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
                                </select>
                            </label>
                        </form>
                    </div>
                    <a href="{{route('admin.event.list')}}" class="btn btn-primary">Quay lại</a>
                </div>
                <div class="d-flex align-items-center" >
                    <div class="count-item" >Tổng cộng: @empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
                    <div class="count-item count-item-reponsive" style="display: none">@empty($list) {{0}} @else {{$list->total()}} @endempty items</div>
                    @if($list)
                        {{ $list->render('Admin.Layouts.Pagination') }}
                    @endif
                </div>
            </div>

            <!-- /Main row -->

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('Script')
    <script src="js/table.js" type="text/javascript"></script>
    <script type="text/javascript">
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
        function restoreItem(id, created)
        {
            Swal.fire({
                title: 'Xác nhận khôi phục!',
                text: `Sau khi khôi phục sẽ được chuyển sang danh sách`,
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-success btn-lg mr-2',
                    cancelButton: 'btn btn-outline-secondary btn-lg'
                },
                confirmButtonText: 'Khôi phục',
                cancelButtonText: 'Hủy',
            }).then((e) => {
                if (e.value) {
                    window.location.href= `{{route('admin.event.restore', ['',''])}}/${id}/${created}`;
                } else {
                    e.dismiss;
                }
            })
        }

        $(document).ready(function () {
            // restore click
            $('.action_restore').click(function () {
                restoreItem($(this).data('id'), $(this).data('created'))
            })

            // restote
            $('.dropdown-item.restoreItem').click(function () {
                const selectedArray = getSelected();
                if (!selectedArray) return;
                Swal.fire({
                    title: 'Xác nhận khôi phục!',
                    text: `Sau khi khôi phục sẽ được chuyển sang danh sách`,
                    icon: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-success btn-lg mr-2',
                        cancelButton: 'btn btn-outline-secondary btn-lg'
                    },
                    confirmButtonText: 'Khôi phục',
                    cancelButtonText: 'Hủy',
                }).then((e) => {
                    if (e.value) {
                        if (selectedArray)
                            $('#formAction').attr('action', $('#formAction').attr('action') + '?action=restore').submit();
                    } else {
                        e.dismiss;
                    }
                })
            })
        });
    </script>
@endsection
