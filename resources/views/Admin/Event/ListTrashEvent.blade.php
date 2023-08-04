@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách sự kiện | Quản lý sự kiện')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/main.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/plusb.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/frontend/responsivehao.css")}}">
@endsection

@section('Content')
    <div class="row m-0 px-3 pt-3">
        <ol class="breadcrumb mt-1">
        <li class="recye px-2 pt-1  check">
            <a href="{{ route('admin.event.list') }}">
            <i class="fa fa-th-list mr-1"></i>Danh sách
            </a>
        </li>
        </ol>
    </div>
    <h4 class="text-center font-weight-bold mb-3 mt-2">QUẢN LÝ THÙNG RÁC SỰ KIỆN</h4>
    <section class="content mt-3">
        <div class="container-fluid">
            <div class="table-contents">

                <table class="table">
                    <thead>
                    <tr>
                        <th><input type="checkbox" class="select-all checkbox"></th>
                        <th>STT</th>
                        <th class="dropdown" style="min-width: 150px">
                            Tình trạng
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
                                <td>
                                    <div class="flex-column">
                                        <x-admin.restore-button
                                          :check-role="$check_role"
                                          url="{{ route('admin.event.restore-multiple', ['ids' => $item->id]) }}"
                                        />
                      
                                        <x-admin.force-delete-button
                                          :check-role="$check_role"
                                          url="{{ route('admin.event.force-delete-multiple', ['ids' => $item->id]) }}"
                                        />
                                    </div>
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
            <x-admin.table-footer
                :check-role="$check_role"
                :lists="$list"
                force-delete-url="{{ route('admin.event.force-delete-multiple') }}"
                restore-url="{{ route('admin.event.restore-multiple') }}"
            />
        </div>
    </section>
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
    </script>
@endsection
