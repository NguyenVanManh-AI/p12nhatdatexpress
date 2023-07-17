@extends('Admin.Layouts.Master')
@section('Title', 'Thêm nhóm quản trị | Tài khoản quản trị')
@section('Style')
@endsection
@section('Content')
    <!-- Content Header (Page header) -->
    <div class="row m-0 p-3">
        <ol class="breadcrumb mt-1 align-items-center">
            <li class="recye px-2">
                <a href="{{route('admin.manage.group')}}">
                    <i class="fa fa-th-list mr-1"></i>Danh sách
                </a>
            </li>
            @if($check_role == 1  ||key_exists(5, $check_role))
                <li class="phay ml-2">
                    /
                </li>
                <li class="recye px-2 ml-1">
                    <a href="{{route('admin.manage.group.trash')}}">
                        <i class="far fa-trash-alt mr-1"></i>Thùng rác
                    </a>
                </li>
            @endif
            @if($check_role == 1  ||key_exists(1, $check_role))
                <li class="ml-2 phay">
                    /
                </li>
                <li class="add px-2 ml-1 p-1 check active">
                    <a href="javascript:{}">
                        <i class="fa fa-edit mr-1"></i>Thêm
                    </a>
                </li>
            @endif
        </ol>
    </div>
    <!-- ./Breakcum -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12 text-left">
                    <h4 class="m-0 font-weight-bold text-uppercase">Thêm nhóm quản trị</h4>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form method="post" action="{{route('admin.manage.group.store')}}">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="title">Tiêu đề</label>
                                    <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}">
                                    @if($errors->has('title'))
                                        <small id="titleHelp" class="text-danger error-message-custom">
                                            {{$errors->first('title')}}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-hover table-bordered font-weight-normal table-role">
                                    @forelse($pages as $page)
                                    <tr>
                                        <td colspan="3" class="header title">
                                            <label>{{$page->page_name}}</label>
                                            <input type="checkbox" name="page[{{$page->id}}]" id="page_{{$page->id}}" value="-1" checked hidden>
                                        </td>
                                    </tr>

                                        @forelse($page->children as $page_child)
                                            <!-- Permission -->
                                            <tr class="">
                                                <td class="" align="center" width="4%">
                                                    |--
                                                </td>
                                                <td class="title" colspan="2">
                                                    <label>{{$page_child->page_name}}</label>
                                                    <input type="checkbox" name="page[{{$page_child->id}}]" id="page_{{$page_child->id}}" value="-1" checked hidden>
                                                </td>
                                            </tr>

                                                <tr class="">
                                                    <td class="" align="center" width="4%">
                                                        |--
                                                    </td>
                                                    <td align="center" width="4%">
                                                        |--
                                                    </td>
                                                    <td class="">

                                                        @foreach($page_permission as $page_per)
                                                            @if($page_per->is_file == $page_child->is_page_file)
                                                                @if($page_per->is_duplicate == 1 && $page_child->is_duplicate == 0)
                                                                @else
                                                                    <div style="border-bottom:1px dotted #ccc; padding:2px 0;">
                                                                    <label class="checkbox-inline title" for="page[{{$page_child->id}}][{{$page_per->id}}][check]">
                                                                        <input class="checkbox firstCheckbox" type="checkbox" value="1" name="page[{{$page_child->id}}][{{$page_per->id}}][check]" id="page[{{$page_child->id}}][{{$page_per->id}}][check]"
                                                                               {{old("page.". $page_child->id. ".". $page_per->id.".check") ? 'checked' : ''}}
                                                                           >
                                                                        <label for="page[{{$page_child->id}}][{{$page_per->id}}][check]"><span>{{$page_per->permission_name}}</span></label>
                                                                    </label>

                                                                    @if($page_per->permission_type)
                                                                        <span style="padding:0 5px 0 10px;">(</span>
                                                                        <label class="checkbox-inline">
                                                                            <input class="checkbox" type="checkbox" value="1" name="page[{{$page_child->id}}][{{$page_per->id}}][self]" id="page[{{$page_child->id}}][{{$page_per->id}}][self]"
                                                                                   {{old("page.". $page_child->id. ".". $page_per->id.".self") ? 'checked' : ''}} disabled>
                                                                            <label for="page[{{$page_child->id}}][{{$page_per->id}}][self]"></label>
                                                                            <span>Của tôi</span>
                                                                        </label>

                                                                        <span style="padding:0 10px;">|</span>
                                                                        <label class="checkbox-inline">
                                                                            <input class="checkbox" type="checkbox" value="1" name="page[{{$page_child->id}}][{{$page_per->id}}][group]" id="page[{{$page_child->id}}][{{$page_per->id}}][group]"
                                                                                   {{old("page.". $page_child->id. ".". $page_per->id.".group") ? 'checked' : ''}} disabled>
                                                                            <label for="page[{{$page_child->id}}][{{$page_per->id}}][group]"></label>
                                                                            <span>Nhóm của tôi</span>
                                                                        </label>

                                                                        <span style="padding:0 10px;">|</span>
                                                                        <label class="checkbox-inline">
                                                                            <input class="checkbox allCheckbox" type="checkbox" value="1" name="page[{{$page_child->id}}][{{$page_per->id}}][all]" id="page[{{$page_child->id}}][{{$page_per->id}}][all]"
                                                                                   {{old("page.". $page_child->id. ".". $page_per->id.".all") ? 'checked' : ''}} disabled>
                                                                            <label for="page[{{$page_child->id}}][{{$page_per->id}}][all]"></label>
                                                                            <span>All</span>
                                                                        </label>
                                                                    <span style="padding:0 10px 0 5px;">)</span>

                                                                    @endif

                                                                    <a style="float:right; font-size:18px;" href="javascript:{}" class="unCheckAll checkboxRight mr-1"><i class="fas fa-times"></i></a>
                                                                    <a style="float:right; font-size:18px;" href="javascript:{}" class="checkAll checkboxRight mr-4"><i class="fas fa-check"></i></a>
                                                                    <div style="clear:both;"></div>
                                                                </div>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        <div class="d-flex align-items-center">
                                                            <a style="font-size:18px;" href="javascript:{}" class="checkAll checkboxBotttom" id="checkAll"><i class="fas fa-check mr-2"></i>all</a>
                                                            <span style="padding:0 10px; font-size:18px;">|</span>
                                                            <a style="font-size:18px;" href="javascript:{}" class="unCheckAll checkboxBotttom"><i class="fas fa-times mr-2"></i>all</a>
                                                        </div>
                                                    </td>
                                                </tr>

                                            <!-- End permission -->
                                        @empty
                                            <tr class="">
                                                <td class="" align="center" width="4%">
                                                    |--
                                                </td>
                                                <td align="center" width="4%">
                                                    |--
                                                </td>
                                                <td class="">

                                                    @foreach($page_permission as $page_per)
                                                        @if($page_per->is_file == $page->is_page_file)
                                                            @if($page_per->is_duplicate == 1 && $page->is_duplicate == 0)
                                                            @else
                                                                <div style="border-bottom:1px dotted #ccc; padding:2px 0;">
                                                                    <label class="checkbox-inline title" for="page[{{$page->id}}][{{$page_per->id}}][check]">
                                                                        <input class="checkbox firstCheckbox" type="checkbox" value="1" name="page[{{$page->id}}][{{$page_per->id}}][check]" id="page[{{$page->id}}][{{$page_per->id}}][check]"
                                                                            {{old("page.". $page->id. ".". $page_per->id.".check") ? 'checked' : ''}}>
                                                                        <label for="page[{{$page->id}}][{{$page_per->id}}][check]"><span>{{$page_per->permission_name}}</span></label>
                                                                    </label>

                                                                    @if($page_per->permission_type)

                                                                        <span style="padding:0 5px 0 10px;">(</span>
                                                                        <label class="checkbox-inline">
                                                                            <input class="checkbox" type="checkbox" value="1" name="page[{{$page->id}}][{{$page_per->id}}][self]" id="page[{{$page->id}}][{{$page_per->id}}][self]"
                                                                                   {{old("page.". $page->id. ".". $page_per->id.".self") ? 'checked' : ''}} disabled>
                                                                            <label for="page[{{$page->id}}][{{$page_per->id}}][self]"></label>
                                                                            <span>Của tôi</span>
                                                                        </label>

                                                                        <span style="padding:0 10px;">|</span>
                                                                        <label class="checkbox-inline">
                                                                            <input class="checkbox" type="checkbox" value="1" name="page[{{$page->id}}][{{$page_per->id}}][group]" id="page[{{$page->id}}][{{$page_per->id}}][group]"
                                                                                   {{old("page.". $page->id. ".". $page_per->id.".group") ? 'checked' : ''}} disabled>
                                                                            <label for="page[{{$page->id}}][{{$page_per->id}}][group]"></label>
                                                                            <span>Nhóm của tôi</span>
                                                                        </label>

                                                                        <span style="padding:0 10px;">|</span>
                                                                        <label class="checkbox-inline">
                                                                            <input class="checkbox allCheckbox" type="checkbox" value="1" name="page[{{$page->id}}][{{$page_per->id}}][all]" id="page[{{$page->id}}][{{$page_per->id}}][all]"
                                                                                   {{old("page.". $page->id. ".". $page_per->id.".all") ? 'checked' : ''}} disabled>
                                                                            <label for="page[{{$page->id}}][{{$page_per->id}}][all]"></label>
                                                                            <span>All</span>
                                                                        </label>
                                                                        <span style="padding:0 10px 0 5px;">)</span>

                                                                    @endif

                                                                    <a style="float:right; font-size:18px;" href="javascript:{}" class="unCheckAll checkboxRight mr-1"><i class="fas fa-times"></i></a>
                                                                    <a style="float:right; font-size:18px;" href="javascript:{}" class="checkAll checkboxRight mr-4"><i class="fas fa-check"></i></a>
                                                                    <div style="clear:both;"></div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach

                                                    <div class="d-flex align-items-center">
                                                        <a style="font-size:18px;" href="javascript:{}" class="checkAll checkboxBotttom" id="checkAll"><i class="fas fa-check mr-2"></i>all</a>
                                                        <span style="padding:0 10px; font-size:18px;">|</span>
                                                        <a style="font-size:18px;" href="javascript:{}" class="unCheckAll checkboxBotttom"><i class="fas fa-times mr-2"></i>all</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    @empty
                                        <tr>
                                            <td colspan="3">Không có dữ liệu các trang</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center my-3">
                            <button class="btn btn-success mr-3">Hoàn tất</button>
                            <input type="button" class="btn btn-outline-secondary" id="reset_btn" value="Làm lại" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('Script')
    <script>
        jQuery(document).ready(function ($) {
            $(function () {
                // Auto disable non checked
                $('input[type="checkbox"].checkbox.firstCheckbox').map((k,v) => $(v).parent().siblings().find('input:checkbox').map( (key,value) => $(value).prop('disabled', !$(v).is(':checked'))))
                // Handle change of check box
                $('input[type="checkbox"]').change(function (){
                    if ($(this).hasClass('checkbox firstCheckbox')){
                        $(this).is(":checked") ? $(this).parent().siblings().find('input:checkbox').map((k, v) => $(v).prop('disabled', !$(this).is(":checked")))
                            : $(this).parent().siblings().find('input:checkbox').not('firstCheckbox').map((k, v) => $(v).prop('disabled', !$(this).is(":checked")).prop('checked', $(this).is(":checked")))
                    }
                    if ($(this).hasClass('checkbox allCheckbox')){
                        $(this).parent().siblings().find('input:checkbox').not(".firstCheckbox").map((k, v) => {$(v).prop('checked', false).prop('disabled', $(this).is(":checked"))})
                    }
                });
                // Check all right
                $('a.checkAll.checkboxRight').click(function () {
                    $(this).siblings().find('input:checkbox').prop('disabled', true).prop('checked',false)
                    $(this).siblings().find('input:checkbox.firstCheckbox').prop('checked', true).prop('disabled', false)
                    $(this).siblings().find('input:checkbox.allCheckbox').prop('checked', true).prop('disabled', false)
                })
                $('a.unCheckAll.checkboxRight').click(function () {
                    $(this).siblings().find('input:checkbox').prop('disabled', true)
                    $(this).siblings().find('input:checkbox.firstCheckbox').prop('checked',false).prop('disabled', false)
                    $(this).siblings().find('input:checkbox.allCheckbox').prop('checked', false).prop('disabled', true)
                })
                // Check all right
                $('a.checkAll.checkboxBotttom').click(function () {
                    $(this).parent().siblings().find('input:checkbox').prop('disabled', true).prop('checked',false)
                    $(this).parent().siblings().find('input:checkbox.firstCheckbox').prop('checked', true).prop('disabled', false)
                    $(this).parent().siblings().find('input:checkbox.allCheckbox').prop('checked', true).prop('disabled', false)
                })
                $('a.unCheckAll.checkboxBotttom').click(function () {
                    $(this).parent().siblings().find('input:checkbox').prop('disabled', true).prop('checked',false)
                    $(this).parent().siblings().find('input:checkbox.firstCheckbox').prop('checked', false).prop('disabled', false)
                    $(this).parent().siblings().find('input:checkbox.allCheckbox').prop('checked', false).prop('disabled', true)
                })
                // Reset form
                $('#reset_btn').click(function () {
                    $('input:checkbox').prop('checked', false)
                    $('input[type="text"]').val('').attr('value','')
                    toastr.success("Làm mới thành công");
                    var body = $("html, body");
                    body.stop().animate({scrollTop:0}, 500, 'swing');
                })
            });
        });
    </script>
    <script>
        // toastr.options = {
        //     "preventDuplicates": true
        // }
        @if(count($errors) > 0)
            toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>
@endsection
