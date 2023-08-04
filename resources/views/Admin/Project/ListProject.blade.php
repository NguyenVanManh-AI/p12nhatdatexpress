@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách dự án | Quản lý dự án')
@section('Style')
    <link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
    <style type="text/css">
        @media only screen and (max-width: 767px) {
            .mohinhthut{
                padding: 0 !important;
            }
        }
    </style>
@endsection
@section('Content')
    <form action="{{route('admin.project.list')}}" method="GET" id="formFilter">
        <div class="row m-0 p-3">
        <div class="col-12 col-sm-12 col-md-5 col-lg-5 p-0">
            <div class="search-reponsive pr-5">
                <input class="form-control required" type="text" name="keyword"
                       placeholder="Khu đô thị mới Thuận Phước" value="{{request()->query('keyword') ?? ""}}">
            </div>
        </div>
        <div class="mohinhthut col-12 col-sm-12 col-md-5 col-lg-5 pr-5">
            <div class="search-reponsive">
                <select class="form-control select2" style="width: 100%;height: 34px !important;" name="group_id">
                    <option selected="selected" value="">Mô hình</option>
                    @foreach($category as $item)
                        <option value="{{$item->id}}" @if(isset($_GET['group_id']) && $_GET['group_id'] == $item->id) {{ 'selected' }} @endif>{{$item->group_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-2 col-lg-2 p-0">
            <select class="form-control select2" style="width: 100%;height: 34px !important;" name="created_by">
                <option selected="selected" value="">Tài khoản đăng</option>
                @foreach($creator as $item)
                    <option value="{{$item->id}}" @if(isset($_GET['created_by']) && $_GET['created_by'] == $item->id) {{ 'selected' }} @endif>{{$item->admin_fullname}}</option>
                @endforeach
            </select>
        </div>
        @php
            $created_at = request()->get('created_at');
            if ($created_at){
                $date_start = $created_at['date_start'] ?? "";
                $date_end = $created_at['date_end'] ?? "";
            }
        @endphp
        <div class="search-reponsive col-12 col-sm-12 col-md-5 col-lg-5 pr-5 pl-0 mt-3">
            <div class="row m-0">
                <div class="search-reponsive col-12 col-sm-12 col-md-6 col-lg-6 pr-3 pl-0" onclick="hideTextDateStart()">
                    <div style="position: relative">
                        <div id="txtDateStart" style="width:100px; height: 22px;background: #fff;position: absolute;top: 9px;left: 9px">Từ ngày</div>
                        <input name="created_at[date_start]" class="form-control float-right date_start" type="date" value="{{$date_start ?? ""}}" />
                        <small class="text-danger error-message-custom" style="display: none" id="errorDateStart"></small>
                    </div>
                </div>
                <div class="search-reponsive col-12 col-sm-12 col-md-6 col-lg-6 pl-3 pr-0" onclick="hideTextDateEnd()">
                    <div style="position: relative">
                        <div id="txtDateEnd" style="width: 100px;height: 22px;background: #fff;position: absolute;top: 9px;left: 9px;z-index: 1">Đến ngày</div>
                        <input name="created_at[date_end]" class="form-control float-right date_end" type="date" value="{{$date_end ?? ""}}" />
                        <small class="text-danger error-message-custom" style="display: none" id="errorDateEnd"></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt20 comment-new-reponsive col-12 col-sm-12 col-md-5 col-lg-5 pr-5">
            <div class="search-reponsive">
                <div class="row">
                    <div class="col-6">
                        <label class="search-reponsive form-control-409 mr-2">
                            <input type="checkbox" value="1" class="checkbox-forced-colors-checked" id="new_comment" name="new_comment"
                            @if(isset($_GET['new_comment']) && $_GET['new_comment'] == 1) {{ 'checked' }} @endif />
                        </label>
                        <label for="new_comment" class="ml-1 mt3 font-weight-normal">Có bình luận mới</label>
                    </div>
                    <div class="col-6">
                        <label class="search-reponsive form-control-409 mr-2">
                            <input type="checkbox" value="1" class="checkbox-forced-colors-checked" id="new_notify" name="new_notify"
                            @if(isset($_GET['new_notify']) && $_GET['new_notify'] == 1) {{ 'checked' }} @endif />
                        </label>
                        <label for="new_notify" class="ml-1 mt3 font-weight-normal">Có thông báo mới</label>
                    </div>
                </div>
            </div>
        </div>
        {{--        <div class="pt20 comment-new-reponsive col-12 col-sm-12 col-md-2 col-lg-2 pl-0">--}}
        {{--            <div class="d-flex">--}}
        {{--                <label class="search-reponsive form-control-409 mr-2">--}}
        {{--                    <input type="checkbox" name="checkbox-forced-colors-checked" checked="">--}}
        {{--                </label>--}}
        {{--                <span class="ml-1 mt3" >Có bình luận mới</span>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div class="search-reponsive col-12 col-sm-12 col-md-3 col-lg-3 px-5 pt20" >--}}
        {{--            <div class="d-flex">--}}
        {{--                <label class="search-reponsive form-control-409 mr-2">--}}
        {{--                    <input type="checkbox" name="checkbox-forced-colors-checked" checked="">--}}
        {{--                </label>--}}
        {{--                <span class="ml-1 mt3">Có thông báo mới</span>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        <div class="comment-new-reponsive col-12 col-sm-12 col-md-2 col-lg-2 pl-0 pr-0 pt20">
            <button class="search-button btn btn-primary w-100"><i class="fa fa-search mr-2" aria-hidden="true"></i>Tìm
                kiếm
            </button>
        </div>
    </div>
    </form>
    <h4 class="text-center font-weight-bold mt-2 mb-4">DANH SÁCH DỰ ÁN</h4>
    <!-- Main content -->
    <section class="content hiden-scroll">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive table-custom">
                        <table class="table table-bordered text-center table-custom" id="table">
                            <thead>
                            <tr>
                                <th scope="row" class=" active" style="width: 3%">
                                    <input type="checkbox" class="select-all checkbox" name="select-all"/>
                                </th>
                                <th scope="col" style="width: 4%">STT</th>
                                <th scope="col" style="width: 14%">Hình ảnh</th>
                                <th scope="col" style="width: 17%">Tên dự án</th>
                                <th scope="col" style="width: 11%">
                                    Mô hình
{{--                                    <div class="dropdown">--}}
{{--                                        <button class="dropdow dropdown-toggle font-weight-bold" type="button"--}}
{{--                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"--}}
{{--                                                aria-expanded="false">--}}
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
{{--                                    <div class="dropdown">--}}
{{--                                        <button class="dropdow  dropdown-toggle font-weight-bold" type="button"--}}
{{--                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"--}}
{{--                                                aria-expanded="false">--}}
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
                                @foreach ($list_project as $item )
                                    <tr>
                                        <td class="active">
                                            <input type="checkbox" class="select-item checkbox" name="select_item[]"
                                                   value="{{$item->id}}"/>
                                            <input type="hidden" class="select-item checkbox"
                                                   name="select_item_created[{{$item->id}}]"/>
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
                                        <td class="position-relative">
                                            <a target="__blank" href="{{route('home.project.project-detail', $item->project_url)}}" class="title">{{$item->project_name}}</a>
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
                                            <span>
                                                {{ data_get($item->group, 'group_name') }}
                                            </span>
                                        </td>
                                        <td class="">
                                            <span>{{date('d/m/Y',$item->created_at)}}</span>
                                        </td>
                                        <td class="" style="overflow-x: hidden;max-width: 100px">
                                            <span>{{ data_get($item->admin, 'admin_fullname') }}</span>
                                        </td>
                                        <td class="text-left">
                                            @if($check_role == 1  ||key_exists(2, $check_role))
                                                <div class="mb-2 ml-2">
                                                    <span class="icon-small-size mr-1 text-dark">
                                                        <i class="fas fa-cog"></i>
                                                    </span>
                                                    <a href="{{route('home.project.project-detail', $item->project_url)}}" class="text-primary">Chỉnh sửa</a>
                                                </div>
                                            @endif

                                            <x-admin.delete-button
                                                :check-role="$check_role"
                                                url="{{ route('admin.project.delete-multiple', ['ids' => $item->id]) }}"
                                            />

                                            @if($check_role == 1  ||key_exists(4, $check_role))
                                            <div class="position-relative mb-2 ml-2">
                                                <a href="#" class="setting-item comment mb-2" data-id="{{$item->id}}" onclick="getCommentProject(event,'{{route('project.comment.new', $item->id)}}'); window.selectedComment = this;">
                                                    <span class="icon-small-size mr-1 text-dark">
                                                        <i class="fas fa-comment-dots"></i>
                                                    </span>
                                                    Bình luận
                                                    @if($item->total_new_comments_count > 0)
                                                        <span class="count">{{$item->total_new_comments_count}}</span>
                                                    @endif
                                                </a>
                                            </div>
                                            @endif

                                            @if($check_role == 1  ||key_exists(4, $check_role))
                                            <div class="position-relative mb-2 ml-2">
                                                <a href="{{route('admin.project.list-report')}}?project_id={{$item->id}}" class="setting-item notify" >
                                                    <span class="icon-small-size mr-1 text-dark">
                                                        <i class="fas fa-info-circle"></i>
                                                    </span>
                                                    Xem báo cáo
                                                    @if($item->report_pending_count > 0)
                                                        <span class="count">{{$item->report_pending_count}}</span>
                                                    @endif
                                                </a>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <x-admin.table-footer
                        :check-role="$check_role"
                        :lists="$list_project"
                        :count-trash="$count_trash"
                        view-trash-url="{{ route('admin.project.trash') }}"
                        delete-url="{{ route('admin.project.delete-multiple') }}"
                    />
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    {{-- popup view --}}
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal_file" role="document" style="max-width:90%;">
            <div class="modal-content" style="height: 100% !important;">
                <div class="modal-header">
                    <h5 class="modal-title">Xem dự án</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="width: 100%;height: calc(100vh - 150px);overflow-y: scroll">
                        <div id="content-project"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- popup view --}}
    {{-- popup comment --}}
    <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-file" role="document" style="max-width:90%;">
            <div class="modal-content" style="height: 100% !important;">
                <div class="modal-header">
                    <h5 class="modal-title">Bình luận mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="width: 100%;height: calc(100vh - 150px);overflow-y: scroll">
                        <div id="comment-project"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- popup comment --}}
@endsection

@section('Script')
    <script src="{{asset('system/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{asset('frontend/js/plusb.js')}}"></script>
    <script src="js/table.js"></script>
    <script type="text/javascript">
        $('#quanlyduan').addClass('active');
        $('#danhsachduan').addClass('active');
        $('#nav-quanlyduan').addClass('menu-is-opening menu-open');
    </script>
    <script>
        appendPaginateOnSubmitForm('#formFilter', '#paginateNumber');
        // date filter
        hiddenInputTextDate('#txtDateStart')
        hiddenInputTextDate('#txtDateEnd')
        setMinMaxDate('.date_start', '.date_end')
        function hideTextDateStart(){
            $('#txtDateStart').hide();
        }
        function hideTextDateEnd(){
            $('#txtDateEnd').hide();
        }

        // view modal
        $("#viewModal").on('hide.bs.modal', function(){
            $('#content-project').empty()
        });
        async function getPreviewProject(url, id){
            jQuery.ajax({
                url: url,
                type: "GET",
                // dataType: "json",
                data: { id },
                success: function (result) {
                    $('#content-project').html(result)
                    jQuery('#viewModal').modal('show');
                },
            })
        }
        $('.view-project').click( async function () {
            await getPreviewProject('{{route('admin.project.view')}}', $(this).data('id'))
        })
        // comment modal
        $("#commentModal").on('hide.bs.modal', function(e){
            $('#comment-project').empty()
        });
        async function getCommentProject(event, url, id){
            event.preventDefault();
            let that = this
            jQuery.ajax({
                url: url,
                type: "GET",
                // dataType: "json",
                data: { id },
                success: function (result) {
                    $('#comment-project').html(result)
                    jQuery('#commentModal').modal('show');
                    let count = $('.comment-section .block-comment').length;
                    $(window.selectedComment).find('span').html($(window.selectedComment).find('span').html() - count)
                    if($(window.selectedComment).find('span').html() <= 0) $(window.selectedComment).find('span').remove()
                    $('.pagination li').find('a').click(function (event) {
                        event.preventDefault()
                        if ($(this).parent().hasClass('active'))
                            return;
                        var url_string = $(this).attr('href')
                        var url = new URL(url_string);
                        var page = url.searchParams.get("page");
                        var project_id = parseInt( url_string.match(/new\/(\d+)/)[1] );
                        getCommentProject( event,'{{route('project.comment.new', '')}}' + `/${project_id}?page=${page}`)
                    })
                },
            })
        }
    </script>
@endsection
