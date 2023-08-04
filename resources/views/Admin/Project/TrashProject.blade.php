@extends('Admin.Layouts.Master')

@section('Title', 'Danh sách dự án | Quản lý dự án')

@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
@endsection

@section('Content')
    <h4 class="text-center font-weight-bold mt-2 mb-4">QUẢN LÝ THÙNG RÁC DỰ ÁN</h4>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive table-custom">
                        <table class="table table-bordered text-center table-custom" id="table">
                            <thead>
                            <tr>
                                <th scope="row" class=" active" style="width: 3%">
                                    <input type="checkbox" class="select-all checkbox" name="select-all" />
                                </th>
                                <th scope="col" style="width: 4%">STT</th>
                                <th scope="col" style="width: 14%">Hình ảnh</th>
                                <th scope="col"  style="width: 17%">Tên dự án</th>
                                <th scope="col" style="width: 11%">
{{--                                    <div class="dropdown">--}}
                                        Mô hình
{{--                                        <button class="dropdow dropdown-toggle font-weight-bold" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                                            Mô hình--}}
{{--                                        </button>--}}
{{--                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
{{--                                            <a class="dropdown-item" href="#">Action</a>--}}
{{--                                            <a class="dropdown-item" href="#">Another action</a>--}}
{{--                                            <a class="dropdown-item" href="#">Something else here</a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </th>
                                <th scope="col" style="width: 11%">Ngày</th>
                                <th scope="col" style="width: 11%">
                                    Người đăng
{{--                                    <div class="dropdown"> --}}
{{--                                        <button class="dropdow  dropdown-toggle font-weight-bold" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                                            Người đăng--}}
{{--                                        </button>--}}
{{--                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
{{--                                            <a class="dropdown-item" href="#">Action</a>--}}
{{--                                            <a class="dropdown-item" href="#">Another action</a>--}}
{{--                                            <a class="dropdown-item" href="#">Something else here</a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </th>
                                <th scope="col" style="width: 10%">Cài đặt</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach ($trash  as $item )
                            <tr>
                                <td class="active">
                                    <input type="checkbox" class="select-item" value="{{$item->id}}" name="select_item[]">
                                    <input type="hidden" class="select-item"  name="select_item_created[{{$item->id}}]">
                                </td>
                                <td>{{$item->id}}</td>
                                <td>
                                    @php
                                        $list_image = json_decode($item->image_url);
                                    @endphp
                                    @if($list_image != null)
                                        <div class="list-image">
                                            <div class="image-large">
                                                <img src="{{$list_image[0]}}" alt="">
                                            </div>
                                            @if(count($list_image) > 2)
                                                <div class="image-small">
                                                    @if(count($list_image) > 1)
                                                        <div class="item">
                                                            <img src="{{$list_image[1]}}" alt="">
                                                        </div>
                                                    @endif
                                                    @if(count($list_image) > 2)
                                                        <div class="item">
                                                            <img src="{{$list_image[2]}}" alt="">
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="position-relative" style="padding-block: 3%">
                                    <a href="javascript:{}" class="title">{{$item->project_name}}</a>
                                    <div class="project-statistic">
                                        <div class="item">
                                            <i class="fas fa-edit"></i>
                                            Số chữ: <span class="text-blue-light">{{$item->num_word ?? 0}}</span>
                                        </div>
                                        <div class="item">
                                            <i class="fas fa-signal"></i>
                                            Lượt xem: <span class="text-blue-light">{{$item->num_view}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="">
                                    {{ data_get($item->group, 'group_name') }}
                                </td>
                                <td class="">
                                    <span>{{date('d/m/Y',$item->created_at)}}</span>
                                </td>
                                <td class="" style="overflow-x: hidden;max-width: 100px">
                                    <span>{{ data_get($item->admin, 'admin_fullname') }}</span>
                                </td>
                                <td>
                                    <div class="flex-column">
                                        <x-admin.restore-button
                                            :check-role="$check_role"
                                            url="{{ route('admin.project.restore-multiple', ['ids' => $item->id]) }}"
                                        />
                                        <x-admin.force-delete-button
                                            :check-role="$check_role"
                                            url="{{ route('admin.project.force-delete-multiple', ['ids' => $item->id]) }}"
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
                        :lists="$trash"
                        force-delete-url="{{ route('admin.project.force-delete-multiple') }}"
                        restore-url="{{ route('admin.project.restore-multiple') }}"
                    />
                </div>
            </div>
        </div>
    </section>
@endsection

@section('Script')
    <script src="js/table.js"></script>
@endsection
