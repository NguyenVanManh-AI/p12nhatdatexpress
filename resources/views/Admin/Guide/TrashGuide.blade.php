@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác | Hướng dẫn')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
    <style>
        /*Dropdown*/
        .dropdown-custom {
            background-color: #337ab7;
            border-color: #2e6da4;
            color: #fff;
        }

        .dropdown-custom:hover {
            color: white;
            background-color: #286090;
            border-color: #204d74;
        }

    </style>
@endsection
@section('Content')
<div class="row m-0 p-3">
    <ol class="breadcrumb mt-1">
        @if($check_role == 1  ||key_exists(4, $check_role))    
        <li class="list-box px-2 pt-1 active check">
            <a href="{{route('admin.guide.list')}}">
                <i class="fa fa-th-list mr-1"></i>Danh sách
            </a>
        </li>
        @endif
    </ol>
</div>
<section class="content mb-2">
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
                            <th scope="col" style="width: 14%">Hình ảnh</th>
                            <th scope="col" style="width: 28%">Tiêu đề</th>
                            <th scope="col" style="width: 14%">Tác giả</th>
                            <th scope="col" style="width: 11%">Ngày đăng</th>
                            <th scope="col" style="width: 22%;">Cài đặt</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ( $trash_Guide as $item )
                            <tr>
                                <td class="active">
                                    <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                                    <input type="hidden" class="select-item checkbox" name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}" />

                                </td>
                                <td>{{$item->id}}</td>
                                <td>
                                    <div class="image-box-main">
                                        <div class="image-top">
                                            <img
                                                src="{{asset($item->image_url ?? '/frontend/images/home/image_default_nhadat.jpg')}}"
                                                class=" h-100">
                                        </div>
                                       
                                           
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center flex-column flex-fill">
                                        <span class="name-text">{{$item->guide_title}} </span>
                                        <div class="review-box-main mt-3">
                                            {{-- <div class="box-review d-flex flex-row">
                                                <i class="fas fa-edit mr-2"></i>
                                                <span class="d-flex">Số chữ: <span class="ml-1">1600</span> </span>
                                            </div>
                                            <div class="view-box d-flex flex-row mt-3">
                                                <i class="fas fa-signal mt-1 mr-2"></i>
                                                <span class="d-flex flex-row align-items-center">Lượt xem: <span
                                                        class="ml-1">300</span></span>
                                            </div> --}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span>{{$item->admin_fullname}}</span>
                                </td>
                                <td>
                                    <span>{{\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')}}</span>
                                </td>
                                <td>
                                    <div class="flex-column">
                                      <x-admin.restore-button
                                        :check-role="$check_role"
                                        url="{{ route('admin.guide.restore-multiple', ['ids' => $item->id]) }}"
                                      />
                    
                                      <x-admin.force-delete-button
                                        :check-role="$check_role"
                                        url="{{ route('admin.guide.force-delete-multiple', ['ids' => $item->id]) }}"
                                      />
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <x-admin.table-footer
                    :check-role="$check_role"
                    :lists="$trash_Guide"
                    force-delete-url="{{ route('admin.guide.force-delete-multiple') }}"
                    restore-url="{{ route('admin.guide.restore-multiple') }}"
                />
            </div>
        </div>
    </div>
</section>
@endsection
@section('Script')
    <script src="js/table.js"></script>

    <script type="text/javascript">
        $('#huongdan').addClass('active');
        $('#nav-huongdan').addClass('menu-is-opening menu-open');
    </script>
     
     <script>
        $('.delete').click(function () {
            var id = $(this).data('id');
            var created_by = $(this).data('created_by');
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
                    window.location.href = "/admin/guide/untrash-guide/" + id+"/"+created_by;
                }
            });
        });
        $('.unToTrash').click(function () {
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