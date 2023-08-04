@extends('Admin.Layouts.Master')
@section('Title', 'Thùng rác quản lý chuyên mục | Quản lý dự án')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
<div class="row m-0 p-3">
    <ol class="breadcrumb">
        @if($check_role == 1  ||key_exists(4, $check_role))
        <li class="add px-2 pt-1">
            <a href="{{route('admin.projectcategory.list')}}">
                <i class="fa fa-th-list mr-1"></i>Danh sách
            </a>
        </li>
        @endif
    </ol>
</div>
<h4 class="text-center font-weight-bold mb-3 mt-2">THÙNG RÁC QUẢN LÝ CHUYÊN MỤC</h4>

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
                                {{-- <th scope="col" style="width: 14%">Thứ tự</th> --}}
                                <th scope="col"  style="width: 28%">Tên mô hình</th>
                                <th scope="col" style="width: 28%">Đường dẫn tĩnh</th>
                                <th scope="col" style="width: 13%">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($group as $item)
                            <tr>
                                <td class="active">
                                    <input type="checkbox" class="select-item" value="{{$item->id}}"name="select_item[]">
                                    <input type="hidden" class="select-item"   name="select_item_created[{{$item->id}}]">
                                    @if(!isset($item->child))
                                        <input type="hidden" class="select-item" value="{{($item->id)}}"
                                               name="parent[{{$item->id}}]">
                                    @else
                                        <input type="hidden" class="select-item" value="{{($item->id)}}"
                                               name="child[{{$item->id}}]">
                                    @endif
                                </td>

                                <td>{{$item->id}}</td>
                                {{-- <td class="input_rounded">
                                    <input type="text" value="0">
                                </td> --}}
                               @if (isset($item->child))
                               <td class="text-left pl-5">---- {{$item->group_name}}</td>
                               @else
                               <td class="text-left pl-5">{{$item->group_name}}</td>
                               @endif
                                <td class="">
                                    <span>{{$item->group_url}}</span>
                                </td>

                                <td>
                                    <div class="flex-column">
                                        <x-admin.restore-button
                                          :check-role="$check_role"
                                          url="{{ route('admin.projects.categories.restore-multiple', ['ids' => $item->id]) }}"
                                        />
                      
                                        <x-admin.force-delete-button
                                          :check-role="$check_role"
                                          url="{{ route('admin.projects.categories.force-delete-multiple', ['ids' => $item->id]) }}"
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
                :lists="$group"
                force-delete-url="{{ route('admin.projects.categories.force-delete-multiple') }}"
                restore-url="{{ route('admin.projects.categories.restore-multiple') }}"
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
