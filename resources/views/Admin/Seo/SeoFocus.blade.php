@extends('Admin.Layouts.Master')
@section('Title', 'Quản lý seo | Tiêu điểm')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection
@section('Content')
    <section class="content hiden-scroll">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <h4 class="text-center text-bold mt-4" >SEO CHUYÊN MỤC TIÊU ĐIỂM</h4>
                    <div class="table-responsive mt-2">
                        <form action="" id="formtrash" method="post">
                            @csrf
                            <input type="hidden" name="action" id="action_list" value="">
                            <table class="table table-bordered text-center table-custom" id="table">
                                <thead>
                                <tr>
                                    {{-- <th scope="row" class=" active" style="width: 3%">
                                        <input type="checkbox" class="select-all checkbox" name="select-all"/>
                                    </th> --}}
                                    <th scope="col" style="width:15%">Tên chuyên mục</th>
                                    <th scope="col" style="width:15%"> Tiêu đề</th>
                                    <th scope="col" style="width: 10%">Từ khóa</th>
                                    {{--                                <th scope="col" style="width: 14%">Thứ tự</th>--}}
                                    <th scope="col" style="width: 20%">Mô tả</th>
                                    <th scope="col" style="width: 20%">Đường dẫn</th>
                                    <th scope="col" style="width: 13%">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($group as $item)
                                    <tr>
                                        <td> {{(!isset($item->child))?$item->group_name:"---- ".$item->group_name}}</td>
                                        <td>{{$item->meta_title}}</td>
                                        <td>{{$item->meta_key}}</td>
                                        <td class="text-center">
                                            {{$item->meta_desc}}
                                        </td>
                                        <td class="">
                                            <span>{{$item->group_url}}</span>
                                        </td>
                                        <td>
                                            <div class="flex-column">
                                                <x-admin.action-button
                                                    action="update"
                                                    :check-role="$check_role"
                                                    :item="$item"
                                                    url="{{ route('admin.seo.editfocus', $item) }}"
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($group->count()==0)
                                    <tr>
                                        <td colspan="5"> <p class="text-center mt-2">Không có dữ liệu</p></td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <x-admin.table-footer
                        :check-role="$check_role"
                        :lists="$group"
                        hide-action
                    />
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
