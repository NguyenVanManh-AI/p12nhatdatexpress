@extends('Admin.Layouts.Master')
@section('Title', 'Tài khoản thường | Thành viên')
@section('Content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if($check_role == 1  || auth('admin')->user()->rol_id == 1)
            <x-admin.user.statistics></x-admin.user.statistics>
            @endif
            <div class="filter block-dashed">
                <h3 class="title">Bộ lọc</h3>
                <form action="" method="get" class="form-filter">
                    <div class="form-row form-group">
                        <div class="col-md-4">
                            <input type="text" class="form-control" value="{{request()->keyword}}" name="keyword" placeholder="Nhập từ khóa (Tên, Số điện thoại, Email )">
                        </div>
                        <div class="col-md-4">
                            <input type="date" class="form-control" value="{{request()->created_at}}" name="created_at" placeholder="Thời gian tham gia">
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="custom-select">
                                <option value="">Tình trạng</option>
                                <option {{request()->status == 1?"selected":""}}  value="1">Đã xác thực</option>
                                <option {{request()->status != "" && request()->status == 0?"selected":""}}  value="0">Chưa xác thực</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-md-4">
                            <select name="province" id="province_id" onchange="get_district(this,'{{route('param.get_district')}}','#district_id',)" class="custom-select">
                                <option value="">Tỉnh / Thành phố</option>
                                @foreach( $province as $item)
                                    <option {{request()->province == $item->id?"selected":""}} value="{{$item->id}}" >{{$item->province_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="district" id="district_id" class="custom-select">
                                <option value="">Quận / Huyện</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="level" class="custom-select">
                                <option value="">Cấp bậc</option>
                                @foreach($level as $item)
                                    <option {{request()->level == $item->id?"selected":""}} value="{{$item->id}}">{{$item->level_name}}</option>

                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-md-4">
                            <select name="user_type" class="custom-select">
                                <option value="">Loại tài khoản</option>
                                <option {{request()->user_type == 1?"selected":""}}  value="1">Cá nhân</option>
                                <option {{request()->user_type == 2?"selected":""}}  value="2">Chuyên viên tư vấn</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="state" class="custom-select">
                                <option value="">Trạng thái</option>
                                <option {{request()->state == 1?"selected":""}}  value="1">Hoạt động</option>
                                <option {{request()->state != "" && request()->state == 0?"selected":""}}  value="0">Chờ xác thực</option>
                                <option {{request()->state == 2 ? "selected" : ""}}  value="2">Đã chặn</option>
                                <option {{request()->state == 3 ? "selected": ""}}  value="3">Đã cấm</option>
                                <option {{request()->state == 4 ? "selected" : ""}}  value="4">Đã xóa</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn bg-blue-light"><i class="fas fa-search"></i> Tìm kiếm</button>
                            {{--                            <button type="submit" class="btn bg-blue-light"><i class="fas fa-chart-pie"></i> Lên chiến dịch Email marketing</button>--}}
                        </div>
                    </div>
                </form>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th><input type="checkbox" class="select-all checkbox" name="select-all"></th>
                    <th>STT</th>
                    <th class="w-400px">Tên thành viên</th>
                    <th>Ngày tham gia</th>
                    <th class="">Tài khoản
{{--                        <a href="#" class="nav-link" data-toggle="dropdown"></a>--}}
{{--                        <div class="dropdown-menu dropdown-menu-right">--}}
{{--                            <a href="#" class="dropdown-item">--}}

{{--                            </a>--}}
{{--                        </div>--}}
                    </th>
                    <th class="">Tình trạng
{{--                        <a href="#" class="nav-link" data-toggle="dropdown"></a>--}}
{{--                        <div class="dropdown-menu dropdown-menu-right">--}}
{{--                            <a href="#" class="dropdown-item">--}}
{{--                                Đã xác thực--}}
{{--                            </a>--}}
{{--                            <a href="#" class="dropdown-item">--}}
{{--                                Chưa xác thực--}}
{{--                            </a>--}}
{{--                            <a href="#" class="dropdown-item">--}}
{{--                                Đã chặn--}}
{{--                            </a>--}}
{{--                        </div>--}}
                    </th>
                    <th>Trạng thái</th>
                    <th>Cài đặt</th>
                </tr>
                </thead>
                <tbody>
                <form action="{{route('admin.account.list_action')}}" id="list_action" method="post">
                    @csrf
                    <input type="hidden" name="action" id="action_list" value="">
                    <input type="hidden" name="action_method" id="action_method" value="">
                    @forelse($user as $key => $item)
                    <tr>
                        <td>  <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                        <td>{{ ($user->currentpage()-1) * $user->perpage() + $key + 1 }}</td>
                        <td>
                            @include('Admin.User.partials._user-info', [
                                'user' => $item,
                            ])
                        </td>
                        <td>
                            {{date('d/m/Y',$item->created_at)}}
                        </td>
                        <td>
                            <div class="express-coin mb-3">
                                <small class="d-block">Express Coin</small>
                                {{$item->coint_amount}}
                            </div>
                            <div class="package">
                                <small class="d-block">Gói tin</small>
                                {{$item->package_name??"Mặc định"}}
                            </div>
                        </td>
                        <td>
                            @if($item->is_confirmed == 1 )
                                <span class="text-green">Đã xác thực</span>
                            @else
                                <span class="text-warning">Chờ xác thực</span>
                            @endif
                        </td>
                        <td>
                            @include('Admin.User.partials._user-status', [
                                'user' => $item
                            ])
                        </td>
                        <td>
                            @if($check_role == 1  || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                            <a href="{{route('admin.account.edit',$item->id)}}" class="setting-item edit mb-2"><i class="fas fa-cog"></i> Chỉnh sửa</a>
                            @endif

                            @if($item->is_deleted ==0)
                                @if($check_role == 1 || key_exists(5, $check_role)  || auth('admin')->user()->rol_id == 1)
                                    <a href="javascript:{}" class="setting-item delete text-red mb-2" data-id="{{$item->id}}"><i class="fas fa-times"></i> Xóa</a>
                                @endif
                            @else
                                @if($check_role == 1 || key_exists(6, $check_role)  || auth('admin')->user()->rol_id == 1)
                                <a href="javascript:{}" class="setting-item restore text-primary mb-2" data-id="{{$item->id}}"><i class="fas fa-redo-alt"></i> Khôi phục</a>
                                @endif
                            @endif

                            @if(!$item->isBlocked())
                                @if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                                <a href="javascript:{}" class="setting-item block_account text-red mb-2" data-id="{{$item->id}}"><i class="fas fa-times"></i> Chặn</a>
                                @endif
                            @else
                                @if($check_role == 1 || key_exists(2, $check_role)  || auth('admin')->user()->rol_id == 1)
                                <a href="javascript:{}" class="setting-item unblock_account text-primary mb-2" data-id="{{$item->id}}"><i class="fas fa-redo-alt"></i> Mở chặn</a>
                                @endif
                            @endif

                            @if(!$item->isForbidden())
                                @if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                                <a href="javascript:{}" class="setting-item forbidden text-red mb-2" data-id="{{$item->id}}"><i class="fas fa-times"></i> Cấm</a>
                                @endif
                            @else
                                @if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                                <a href="javascript:{}" class="setting-item unforbidden text-primary mb-2" data-id="{{$item->id}}"><i class="fas fa-redo-alt"></i> Mở cấm</a>
                                @endif
                            @endif

                            {{-- @if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                            <a href="#" class="setting-item send-mail mb-2" data-toggle="modal" data-target="#modalSendMail"><i class="fas fa-envelope"></i> Gửi mail</a>
                            @endif --}}

                            @if($item->is_confirmed == 1 )
                            <a href="{{route('trang-ca-nhan.dong-thoi-gian',$item->user_code)}}" target="_blank" class="setting-item profile mb-2">
                                <i class="fas fa-user"></i> Xem trang cá nhân
                            </a>
                            @endif

                            @if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                            <a href="{{route('admin.account.truy_cap',$item->id)}}" class="setting-item access mb-2"><i class="fas fa-sign-out-alt"></i> Truy cập tài khoản</a>
                            @endif

                            @if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                            <a href="#" class="setting-item notify" data-toggle="modal" data-target="#modalCreateNotify" data-user-id="{{$item->id}}" data-user-username="{{$item->username}}">
                                <i class="fas fa-comment-dots"></i> Thông báo
                            </a> <!-- <span class="count">2</span> -->
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Không có dữ liệu</td>
                    </tr>

                @endforelse
                </form>
                </tbody>
            </table>
            <div class="table-bottom d-flex align-items-center justify-content-between mb-4">
                <div class="text-left d-flex align-items-center">
                    <div class="manipulation d-flex mr-4 ">
                        <img src="image/manipulation.png" alt="" id="btnTop">
                        <div class="btn-group ml-1">
                            <button type="button" class="btn dropdown-toggle dropdown-custom"
                                    data-toggle="dropdown"
                                    aria-expanded="false" data-flip="false" aria-haspopup="true">
                                Thao tác
                            </button>
                            <div class="dropdown-menu">
                                @if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                                <a class="dropdown-item list_locked" type="button" href="javascript:{}">
                                    <i class="fas fa-lock bg-red p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Chặn tài khoản
                                </a>
                                @endif

                                @if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                                <a class="dropdown-item list_unlocked" type="button" href="javascript:{}">
                                    <i class="fas fa-undo-alt bg-primary p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Bỏ chặn tài khoản
                                    <input type="hidden" name="action" value="trash">
                                </a>
                                @endif

                                @if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                                <a class="dropdown-item list_forbidden" type="button" href="javascript:{}">
                                    <i class="fas fa-ban  bg-red p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Cấm tài khoản
                                    <input type="hidden" name="action" value="trash">
                                </a>
                                @endif

                                @if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                                <a class="dropdown-item list_unforbidden" type="button" href="javascript:{}">
                                    <i class="fas fa-undo-alt  bg-primary p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Bỏ cấm tài khoản
                                    <input type="hidden" name="action" value="trash">
                                </a>
                                @endif

                                @if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                                <a class="dropdown-item list_deleted" type="button" href="javascript:{}">
                                    <i class="fas fa-times  bg-red p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Xóa tài khoản
                                    <input type="hidden" name="action" value="trash">
                                </a>
                                @endif

                                @if($check_role == 1 || key_exists(2, $check_role) || auth('admin')->user()->rol_id == 1)
                                <a class="dropdown-item list_restore" type="button" href="javascript:{}">
                                    <i class="fas fa-undo-alt bg-primary p-1 mr-2 rounded"
                                       style="color: white !important;font-size: 15px"></i>Khôi phục tài khoản
                                    <input type="hidden" name="action" value="trash">
                                </a>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="display d-flex align-items-center mr-4">
                        <span>Hiển thị:</span>
                        <form method="get" id="paginateform" action="{{route('admin.account.list')}}">
                            <select class="custom-select" id="paginateNumber" onchange="this.form.submit()" name="items" >
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
                        </form>
                    </div>
                </div>
                <div class="text-right d-flex">
                    <div class="count-item">Tổng cộng: {{$user->total()}} items</div>
                    @if($user)
                        {{ $user->render('Admin.Layouts.Pagination') }}
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/main.css")}}">
@endsection
@section('Script')
    <script src="js/table.js"></script>
    <script>
        $('.block_account').click(function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận chặn',
                text: "Sau khi đồng ý sẽ tiến hành chặn tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.account.block','')}}/" + id;
                }
            });
        });
        $('.unblock_account').click(function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận mở chặn',
                text: "Sau khi đồng ý sẽ tiến hành mở chặn tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.account.unblock','')}}/" + id;
                }
            });
        });
        $('.forbidden').click(function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận cấm',
                text: "Sau khi đồng ý sẽ tiến hành cấm tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.account.forbidden','')}}/" + id;
                }
            });
        });
        $('.unforbidden').click(function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận mở cấm',
                text: "Sau khi đồng ý sẽ tiến hành mở cấm tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.account.unforbidden','')}}/" + id;
                }
            });
        });
        $('.delete').click(function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Sau khi đồng ý sẽ tiến hành xóa tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.account.delete','')}}/" + id;
                }
            });
        });
        $('.restore').click(function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: "Sau khi đồng ý sẽ tiến hành khôi phục tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('admin.account.restore','')}}/" + id;
                }
            });
        });
        $('.list_locked').click(function () {
            Swal.fire({
                title: 'Chặn tài khoản',
                text: "Sau khi đồng ý sẽ chặn tài khoản 7 ngày",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("block_account");
                    $('#list_action').submit();

                }
            });
        });
        // bỏ Chặn tài khoản
        $('.list_unlocked').click(function () {
            Swal.fire({
                title: 'Bỏ chặn tài khoản',
                text: "Sau khi đồng ý sẽ bỏ chặn tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("unblock_account");
                    $('#list_action').submit();

                }
            });
        });
        // Cấm tài khoản
        $('.list_forbidden').click(function () {
            Swal.fire({
                title: 'Cấm tài khoản',
                text: "Sau khi đồng ý sẽ cấm tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("forbidden");
                    $('#list_action').submit();

                }
            });
        });
        //bỏ Cấm tài khoản
        $('.list_unforbidden').click(function () {
            Swal.fire({
                title: 'Bỏ cấm tài khoản',
                text: "Sau khi đồng ý sẽ bỏ cấm tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("unforbidden");
                    $('#list_action').submit();

                }
            });
        });
        // Xóa tài khoản
        $('.list_deleted').click(function () {
            Swal.fire({
                title: 'Xóa tài khoản',
                text: "Sau khi đồng ý sẽ xóa tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("delete");
                    $('#list_action').submit();

                }
            });
        });
        // khôi phục tài khoản
        $('.list_restore').click(function () {
            Swal.fire({
                title: 'Khôi phục tài khoản',
                text: "Sau khi đồng ý sẽ khôi phục tài khoản",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#action_list').val("update");
                    $('#action_method').val("restore");
                    $('#list_action').submit();

                }
            });
        });
    </script>
@endsection
