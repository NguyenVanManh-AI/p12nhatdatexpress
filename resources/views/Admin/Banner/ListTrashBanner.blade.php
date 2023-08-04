@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý trang tĩnh | Trang tĩnh')
@section('Content')
    <!-- Content Header (Page header) -->
    <div class="row m-0 px-3 pt-3">
        <ol class="breadcrumb mt-1">
            <li class="recye px-2 p-1">
                <a href="{{route('admin.banner.list')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
        </ol>
    </div>
    <!-- ./Breakcum -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12 text-center">
                    <h4 class="m-0 font-weight-bold">QUẢN LÝ THÙNG RÁC BANNER</h4>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="" method="GET" id="filterForm">
                        <div class="form-group select2-custom">
                            <label for="page_content">Vị trí <span class="font-weight-bold text-danger">*</span></label>
                            <select class="form-control select2" style="width: 100%;height: 34px !important;" name="banner_group_id" data-placeholder="Vị trí"
                                    onchange="$(this).parents('#filterForm').submit()">
                                @foreach($group_banner as $item)
                                    <option @if(request()->query('banner_group_id') == $item->id) {{ 'selected' }} @endif value="{{$item->id}}">
                                        {{$item->banner_name}} ({{$item->banner_width ?? 'auto'}} x {{$item->banner_height ?? 'auto'}})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-hover table-custom " id="table">
                            <thead>
                            <tr>
                                <th scope="row" class="active">
                                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                                </th>
                                <th scope="col">ID</th>
                                <th scope="col" width="15%">Hình ảnh</th>
                                <th scope="col" width="30%">Tiêu đề</th>
                                <th scope="col">Chuyên mục</th>
                                <th scope="col">Thông tin</th>
                                <th scope="col">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($list as $item)
                                    <tr>
                                        <td class=" active">
                                            <input type="checkbox"  value="{{$item->id}}" data-name="{{$item->banner_title}}" class="select-item checkbox" name="select_item[]" />
                                            <input type="text" hidden name="select_item_created[{{$item->id}}]" value="{{\Crypt::encryptString($item->created_by)}}">
                                        </td>
                                        <td class="id_role">{{$item->id}}</td>
                                        <td>
                                            @if($item->image_url)
                                                <a data-fancybox="image_{{$item->id}}" data-src="{{strpos($item->image_url, 'http') === 0 ? $item->image_url : asset($item->image_url)}}">
                                                    <img src="{{strpos($item->image_url, 'http') === 0 ? $item->image_url : asset($item->image_url)}}" width="200px" height="200px" style="object-fit: contain" />
                                                </a>
                                            @endif
                                        </td>
                                        <td class="title_role text-wrap" style="word-break: break-word">{{$item->banner_title}}</td>
                                        <td>{{$item->group_name}}</td>
                                        <td>
                                            <p><span class="text-gray mr-2">Ngày tạo:</span>{{date('d/m/Y H:i:s',$item->created_at)}}</p>
                                            <p><span class="text-gray mr-2">Cập nhật:</span>{{date('d/m/Y H:i:s',$item->updated_at ?? $item->created_at)}}</p>
                                            @if($item->date_from)
                                                <hr>
                                                <p><span class="text-gray mr-2">Ngày bắt đầu:</span>{{date('d/m/Y H:i:s',$item->date_from)}}</p>
                                                <p><span class="text-gray mr-2">Ngày kết thúc:</span>{{date('d/m/Y H:i:s',$item->date_to)}}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex-column">
                                                <x-admin.restore-button
                                                    :check-role="$check_role"
                                                    url="{{ route('admin.banner.restore-multiple', ['ids' => $item->id]) }}"
                                                />
                                
                                                <x-admin.force-delete-button
                                                    :check-role="$check_role"
                                                    url="{{ route('admin.banner.force-delete-multiple', ['ids' => $item->id]) }}"
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">Chưa có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <x-admin.table-footer
                        :check-role="$check_role"
                        :lists="$list"
                        force-delete-url="{{ route('admin.banner.force-delete-multiple') }}"
                        restore-url="{{ route('admin.banner.restore-multiple') }}"
                    />
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('Script')
    <script src="js/table.js" type="text/javascript"></script>
@endsection
