@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý chuyên mục | Quản lý dự án')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
<div class="row m-0 p-3">
    <ol class="breadcrumb mt-1 align-items-center">
        <li class="recye px-2 p-1 check active">
            <a href="javascript:{}">
                <i class="fa fa-th-list mr-1"></i>Danh sách
            </a>
        </li>
        @if($check_role == 1  ||key_exists(8, $check_role))
            <li class="phay ml-2">
                /
            </li>
            <li class="recye px-2 ml-1">
                <a href="{{route('admin.projects.categories.trash')}}">
                    <i class="far fa-trash-alt mr-1"></i>Thùng rác
                </a>
            </li>
        @endif
        @if($check_role == 1  ||key_exists(1, $check_role))
            <li class="ml-2 phay">
                /
            </li>
            <li class="add px-2 ml-1 p-1">
                <a href="{{route('admin.projectcategory.new')}}">
                    <i class="fa fa-edit mr-1"></i>Thêm
                </a>
            </li>
        @endif
    </ol>

</div>
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
                                    <input type="checkbox" class="select-item" value="{{$item->id}}"
                                           name="select_item[]">
                                    <input type="hidden" class="select-item"
                                           value="{{Crypt::encryptString($item->created_by)}}"
                                           name="select_item_created[{{$item->id}}]">
                                    @if(!isset($item->child))
                                        <input type="hidden" class="select-item" value="{{($item->id)}}"
                                               name=" parent[{{$item->id}}]">
                                    @else
                                        <input type="hidden" class="select-item" value="{{($item->id)}}"
                                               name=" child[{{$item->id}}]">
                                    @endif
                                </td>

                                <td>{{$item->id}}</td>

                               @if (isset($item->child))
                               <td class="text-left pl-5">---- {{$item->group_name}}</td>
                               @else
                               <td class="text-left pl-5">{{$item->group_name}}</td>
                               @endif
                                <td class="">
                                    <span>{{$item->group_url}}</span>
                                </td>

                                <td>
                                    <div class="text-left">
                                        @if($check_role == 1  ||key_exists(2, $check_role))
                                            <div class="mb-2 ml-2">
                                                <span class="icon-small-size mr-1 text-dark">
                                                    <i class="fas fa-cog"></i>
                                                </span>
                                                <a href="{{route('admin.projectcategory.edit',[$item->id,Crypt::encryptString($item->created_by)])}}" class="text-primary ">Chỉnh sửa</a>
                                            </div>
                                        @endif

                                        <x-admin.delete-button
                                            :check-role="$check_role"
                                            url="{{ route('admin.projects.categories.delete-multiple', ['ids' => $item->id]) }}"
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
                    :count-trash="$count_trash"
                    view-trash-url="{{ route('admin.projects.categories.trash') }}"
                    delete-url="{{ route('admin.projects.categories.delete-multiple') }}"
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
