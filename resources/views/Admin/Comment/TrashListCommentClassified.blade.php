@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác bình luận tin đăng | Quản lý bình luận')
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
      <a href="{{route('admin.comment.list-classified')}}">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </a>
    </li>


  </ol>
</div>
<h4 class="text-center font-weight-bold">Thùng rác bình luận tin đăng</h4>
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
                <th scope="col" width="12%">Chuyên mục</th>
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
                 {{$item->group_name}}
               </td>
               <td class="title_role text-wrap" style="word-break: break-word">
                {{date('d/m/Y H:i',$item->created_at)}}

              </td>

              <td >
                <a href="{{$item->classified_url}}" target="_blank" rel="noopener noreferrer">{{$item->classified_url}}</a>

              </td>
              <td>
                <div class="flex-column">
                  <x-admin.restore-button
                    :check-role="$check_role"
                    url="{{ route('admin.comment.classified.restore-multiple', ['ids' => $item->id]) }}"
                  />

                  <x-admin.force-delete-button
                    :check-role="$check_role"
                    url="{{ route('admin.comment.classified.force-delete-multiple', ['ids' => $item->id]) }}"
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
      force-delete-url="{{ route('admin.comment.classified.force-delete-multiple') }}"
      restore-url="{{ route('admin.comment.classified.restore-multiple') }}"
    />
</div>
</div>
</div>
</section>
<!-- /.content -->
@endsection

@section('Script')
<script src="js/table.js"></script>
@endsection
