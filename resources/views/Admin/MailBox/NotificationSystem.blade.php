@extends('Admin.MailBox.MasterMail')
@section('ContentMail')
<div class="tab-content" id="v-pills-tabContent">
            <div class="">
                <div class="list-mail bg-white">
                    <form id="formtrash" action="{{route('admin.mailbox.trashlist')}}" method="POST">
                        @csrf
                  @foreach ($list as $item )
                    <div class="item">
                        <div class="checkbox">
                            <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$item->id}}"/>
                        </div>
                        <div class="color-star {{$item->mailbox_pin  == 1 ? "active":""}} position{{$item->id}}" data-id="{{$item->id}}">
                          <span class=""><i class="fas fa-star"></i></span>
                        </div>
                    <div  class="content detail_mail" data-id="{{$item->id}}">
                        <p>@if ($item->object_type == 1 && $item->mailbox_status == 0)
                            <strong>{{$item->mail_title}}</strong>
                            @else
                            <strong style="color: #919191">{{$item->mail_title}}</strong>
                        @endif
                            <span class="content-gray"> - &nbsp{!!$item->mail_content!!}&nbsp</span><span class="post-time">
                          - {{date('d/m/Y H:i',$item->send_time)}} </span>
                        </p>
                    </div>
                </div>
                @endforeach
                @if($list->count()==0 && isset($_GET['search']))
                <h6 class="text-center mt-4">Không tìm thấy</h6>
                @endif
                @if($list->count()==0 && !isset($_GET['search']) )
                <h6 class="text-center mt-4">Danh sách trống</h6>
                @endif
                    </form>
            </div>
        </div>
        <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
            <div class="text-left d-flex align-items-center">
                <div class="manipulation d-flex mr-4">
                    <img style="margin-left: 24px " src="image/manipulation.png" alt="" id="btnTop">
                    <div class="btn-group ml-1">
                        <button type="button" class="btn dropdown-toggle dropdown-custom"
                            data-toggle="dropdown"
                            aria-expanded="false" data-flip="false" aria-haspopup="true">
                        Thao tác
                        </button>
                        <div class="dropdown-menu">
                            {{-- @if($check_role == 1  ||key_exists(6, $check_role)) --}}
                            <a class="dropdown-item trash_list moveToTrash"  href="javascript:{}">
                            <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"
                                style="color: white !important;font-size: 15px"></i>Thùng rác
                            <input type="hidden" name="action" value="trash">
                            </a>
                            {{-- @else --}}
                            {{-- <p class="dropdown-item m-0 disabled">
                                Bạn không có quyền
                            </p> --}}
                            {{-- @endif --}}
                        </div>
                    </div>
                </div>
                <div class="display d-flex align-items-center mr-4">
                    <span>Hiển thị:</span>
                    <form method="get" action="{{route('admin.mailbox.nofitication-system')}}">
                        <select name="items" class="custom-select" onchange="this.form.submit()">
                        <option {{(isset($_GET['items'])&& $_GET['items']==10)?"selected":""}}  class=""
                        value="10">10
                        </option>
                        <option
                        {{(isset($_GET['items'])&& $_GET['items']==20)?"selected":""}} value="20">20
                        </option>
                        <option
                        {{(isset($_GET['items'])&& $_GET['items']==30)?"selected":""}} value="30">30
                        </option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="d-flex align-items-center" >
                <div class="count-item">Tổng cộng:  {{$list->total()}}
                    items
                </div>
                @if($list)
                {{ $list->render('Admin.Layouts.Pagination') }}
                @endif
            </div>
        </div>
    </div>
@endsection
