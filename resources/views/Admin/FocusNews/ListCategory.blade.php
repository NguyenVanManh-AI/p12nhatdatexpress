@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý danh mục | Tiêu điểm')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')

    <div class="row m-0 p-3">
        <ol class="breadcrumb">
            @if($check_role == 1  ||key_exists(4, $check_role))
                <li class="list-box px-2 pt-1">
                    <a href="{{route('admin.focuscategory.list')}}">
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(8, $check_role))
                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 pt-1 ml-1">
                    <a href="{{route('admin.focuscategory.trash')}}">
                        Thùng rác
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(1, $check_role))
                <li class="ml-2 phay">
                    /
                </li>
                <li class="add px-2 pt-1 ml-1">
                    <a href="{{route('admin.focuscategory.new')}}">
                        <i class="fa fa-edit mr-1"></i>Thêm
                    </a>
                </li>
            @endif
        </ol>
    </div>
    <h4 class="text-center text-bold ">DANH SÁCH DANH MỤC</h4>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                            <input type="hidden" name="action" id="action_list" value="">
                            <table class="table table-bordered text-center table-custom" id="table">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 3%"><input type="checkbox" class="select-all"></th>
                                    <th scope="col" style="width: 4%">ID</th>
                                    <th scope="col" style="width: 28%">Tiêu đề</th>
                                    <th scope="col" style="width: 28%">Đường dẫn tĩnh</th>
                                    <th scope="col" style="width: 13%">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($group as $item )
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
                                        <td class="text-left pl-5">
                                            @if($item->parent_id ==47)
                                                {{$item->group_name}}
                                            @else
                                                {{"---- ".$item->group_name}}
                                            @endif
                                        </td>
                                        <td class="">
                                            <span>{{$item->group_url}}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="float-left ml-2">
                                                    <i class="fas fa-cog mr-2"></i>
                                                    <a href="{{route('admin.focuscategory.edit',[$item->id,\Crypt::encryptString($item->created_by)])}}"
                                                       class="text-primary ">Chỉnh sửa</a>
                                                </div>
                                                <br>

                                                <x-admin.delete-button
                                                    :check-role="$check_role"
                                                    url="{{ route('admin.focuscategory.delete-multiple', ['ids' => $item->id]) }}"
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
                        view-trash-url="{{ route('admin.focuscategory.trash') }}"
                        delete-url="{{ route('admin.focuscategory.delete-multiple') }}"
                    />
                </div>
            </div>
        </div>
    </section>
@endsection

@section('Script')
    <script src="js/table.js"></script>
@endsection
