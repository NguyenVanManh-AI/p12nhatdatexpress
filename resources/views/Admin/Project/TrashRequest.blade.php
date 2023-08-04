@extends('Admin.Layouts.Master')

@section('Title', 'Thùng rác danh sách yêu cầu | Quản lý dự án')

@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection

@section('Content')
<div class="row m-0 px-3 pt-3">
  <ol class="breadcrumb mt-1">
    <li class="recye px-2 pt-1  check">
      <a href="{{ route('admin.request.list') }}">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </a>
    </li>
  </ol>
</div>
<h4 class="text-center font-weight-bold mt-2 mb-4">THÙNG RÁC DANH SÁCH YÊU CẦU</h4>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <table class="table table-bordered text-center table-custom" id="table">
            <thead>
              <tr>
                <th scope="row" class=" active" style="width: 3%">
                  <input type="checkbox" class="select-all checkbox" name="select-all" />
                </th>
                <th scope="col" style="width: 4%">STT</th>
                <th scope="col" style="width: 30%">Tên dự án</th>
                <th scope="col"  style="width: 22%">Ngày yêu cầu</th>
                <th scope="col" style="width: 22%">Tình trạng
                </th>
                <th scope="col w22">Cài đặt</th>
              </tr>
            </thead>
            <tbody>
              @forelse ( $trash_request as $item )
              <tr>
                <td class="active">
                  <input  value="{{$item->id}}" type="checkbox" class="select-item checkbox" name="select_item[]">
                  <input type="hidden" class="select-item"  name="select_item_created[{{$item->id}}]">
                </td>
              <td>{{($trash_request->currentPage() -1) * $trash_request->perPage() + $loop->index + 1}}</td>
                  <td class="name-color">
                      <p>{{$item->project_name}}</p>
                      <p>{{$item->investor}}</p>
                      <p>{{$item->address}} {{is_numeric($item->ward_name) ? "Phường " . $item->ward_name : $item->ward_name }}, {{$item->district_name}}, {{$item->province_name}}</p>
                  </td>

                  <td >{{date('d/m/Y',$item->created_at)}}</td>
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
                <div class="flex-column">
                  <x-admin.restore-button
                    :check-role="$check_role"
                    url="{{ route('admin.request.restore-multiple', ['ids' => $item->id]) }}"
                  />

                  <x-admin.force-delete-button
                    :check-role="$check_role"
                    url="{{ route('admin.request.force-delete-multiple', ['ids' => $item->id]) }}"
                  />
                </div>
              </td>
            </tr>
              @empty
                  <td colspan="6">Chưa có dữ liệu</td>
              @endforelse
            </tbody>
          </table>
        </div>

        <x-admin.table-footer
          :check-role="$check_role"
          :lists="$trash_request"
          force-delete-url="{{ route('admin.request.force-delete-multiple') }}"
          restore-url="{{ route('admin.request.restore-multiple') }}"
        />
      </div>
    </div>
  </div>
</section>
@endsection

@section('Script')
<script src="js/table.js"></script>
@endsection
