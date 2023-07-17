@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách yêu cầu | Quản lý dự án')
@section('Style')
@endsection
@section('Content')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">

<h4 class="text-center font-weight-bold mt-2 mb-4">THÙNG RÁC DANH SÁCH YÊU CẦU</h4>
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
                  <input type="checkbox" class="select-all checkbox" name="select-all" />
                </th>
                <th scope="col" style="width: 4%">STT</th>
                <th scope="col" style="width: 30%">Tên dự án</th>
                <th scope="col"  style="width: 22%">Ngày yêu cầu</th>
                <th scope="col" style="width: 22%">Tình trạng
{{--                  <div class="dropdown">--}}
{{--                    <button class="dropdow dropdown-toggle font-weight-bold" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                      Tình trạng--}}
{{--                    </button>--}}
                    {{-- <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div> --}}
{{--                  </div>--}}
                </th>
                <th scope="col w22">Cài đặt</th>
              </tr>
            </thead>
            <tbody>
              <form action="{{route('admin.request.untrashlist')}}" id="formtrash" method="post">
                @csrf
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
                <div>
                 {{-- <div class="float-left ml-2">
                  <i class="fas fa-cog mr-2"></i>
                  <a href="" class="text-primary ">Viết dự án</a>
                </div> --}}

                @if($check_role == 1  ||key_exists(6, $check_role))
                <div>
                  <i class="fas fa-undo-alt"></i>
                  <a href="javascript:{}" data-id="{{$item->id}}" data-confirmed_by="{{\Crypt::encryptString($item->confirmed_by)}}" class="text-primary mb-5  action_delete delete"> Khôi phục</a>
                </div>
                @endif
                <x-admin.force-delete-button
                  :check-role="$check_role"
                  id="{{ $item->id }}"
                  url="{{ route('admin.request.force-delete-multiple') }}"
                />
              </div>
            </td>
          </tr>
              {{-- <tr>
               <td class="active">
                <input type="checkbox" class="select-item checkbox" name="select-item" />
              </td>
              <td>1</td>
              <td class="name-color">Shantira Beach resort & spa</td>
              <td>19/10/2020 </td>
              <td>
                <span class="text-warning font-weight-bold">Đang viết</span>
              </td>
              <td>
                <div>
                 <div class="float-left ml-2">
                  <i class="fas fa-cog mr-2"></i>
                  <a href="" class="text-primary ">Viết dự án</a>
                </div>
                <br>
                <div class="float-left ml10">
                  <i class="fas fa-times mr12"></i>
                  <a href="#" class="text-danger action_delete">Xóa</a>
                </div>
                <div class="clear-both"></div>
              </div>
            </td>
            <tr>
              <tr>
               <td class="active">
                <input type="checkbox" class="select-item checkbox" name="select-item" />
              </td>
              <td>1</td>
              <td class="name-color">Shantira Beach resort & spa</td>
              <td>19/10/2020 </td>
              <td>
                <span class="text-secondary font-weight-bold">Đang viết</span>
              </td>
              <td>
                <div>
                 <div class="float-left ml-2">
                  <i class="fas fa-cog mr-2"></i>
                  <a href="" class="text-primary ">Viết dự án</a>
                </div>
                <br>
                <div class="float-left ml10">
                  <i class="fas fa-times mr12"></i>
                  <a href="#" class="text-danger action_delete">Xóa</a>
                </div>
                <div class="clear-both"></div>
              </div>
            </td> --}}
            {{-- <tr> --}}
              @empty
                  <td colspan="6">Chưa có dữ liệu</td>
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
                        @if($check_role == 1  ||key_exists(6, $check_role))
                        <a class="dropdown-item unToTrash" type="button" href="javascript:{}">
                          <i class="fas fa-undo-alt bg-primary p-1 mr-2 rounded"
                             style="color: white !important;font-size: 15px"></i>Khôi phục
                          <input type="hidden" name="action" value="restore">
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
                          <option @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">10</option>
                          <option @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">20</option>
                          <option @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">30</option>
                      </select>
                  </label>
              </div>
              {{-- <div class="view-trash">
                <a href="{{route('admin.request.trashrequest')}}"><i class="far fa-trash-alt"></i> Xem thùng rác</a>
                <span class="count-trash">

        {{$count_trash}}
                 {{-- @if(isset($count_trash))
                 {{$count_trash}}
                 @endif --}}
             {{-- </span>
             </div>} --}}
              <div>
                <a href="{{route('admin.request.list')}}" class="btn btn-primary">
                  Quay lại
              </a>
              </div>
          </div>
            <div class="d-flex align-items-center" >
                <div class="count-item" >Tổng cộng: @empty($trash_request) {{0}} @else {{$trash_request->total()}} @endempty items</div>
                <div class="count-item count-item-reponsive" style="display: none">@empty($trash_request) {{0}} @else {{$trash_request->total()}} @endempty items</div>
                @if($trash_request)
                    {{ $trash_request->render('Admin.Layouts.Pagination') }}
                @endif
            </div>
      </div>
  </div>
</div>
</div>
</section>
@endsection

@section('Script')
<script src="js/table.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css" />
<script type="text/javascript">
    $(document).ready(function () {
        $('#chuyenmuc').chosen();
        $('#mohinh').chosen();
        $('#taikhoandang').chosen();
    });
</script>
<script type="text/javascript">
    $('#quanlyduan').addClass('active');
    $('#danhsachyeucau').addClass('active');
    $('#nav-quanlyduan').addClass('menu-is-opening menu-open');
</script>
<!-- /.content -->
<script>
    $('.delete').click(function () {

        var id = $(this).data('id');
        var confirmed_by = $(this).data('confirmed_by');
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
                window.location.href = "/admin/project/request/undelete/" + id+"/"+confirmed_by;

            }
        });
    });
    $('.unToTrash').click(function () {
        const selectedArray = getSelected();
        if (!selectedArray) return;
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
@endsection
