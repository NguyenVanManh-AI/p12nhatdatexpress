@extends('user.layouts.master')
@section('title', 'Hòm thư')
@section('css')
    <link rel="stylesheet" src="{{asset('user/css/mailbox.css')}}">

@endsection
@section('content')
    <div class="mailbox row p-1 mail-page">
        <div class="nav flex-column nav-pills col-12 col-md-3 p-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <div class="bg-white">
                {{-- should change --}}
                <?php
                    $titleMap = [
                        [
                            'text' => 'Tất cả thư'
                        ],
                        [
                            'text' => 'Thư đã đọc'
                        ],
                        [
                            'text' => 'Thư chưa đọc'
                        ],
                        [
                            'text' => 'Thư được ghim'
                        ],
                        [
                            'text' => 'Thông báo tin đăng'
                        ],
                        [
                            'text' => 'Thông báo tài khoản'
                        ],
                        [
                            'text' => 'Thông báo từ hệ thống'
                        ],
                    ];
                ?>
                @foreach ($titleMap as $titleKey => $title)
                    <a class="nav-link text-left rounded-0 {{ request()->mail_status == $titleKey ? 'active' : '' }} {{ $titleKey == 0 ? 'bg-gray' : '' }}"  href="{{ route('user.mailbox', $titleKey) }}">
                        <b>{{ data_get($title, 'text') }}</b>
                    </a>
                @endforeach
               
                {{-- <a class="nav-link text-left {{request()->mail_status == 0?'active':null}} bg-gray"  href="{{route('user.mailbox', 0)}}">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    <b>Tất cả thư</b>
                </a>
                <a class="nav-link text-left {{request()->mail_status == 1?'active':null}}"  href="{{route('user.mailbox', 1)}}">
                    <i class="fa fa-envelope-open" aria-hidden="true"></i>
                    <b>Thư đã đọc</b>
                </a>
                <a class="nav-link text-left {{request()->mail_status == 2?'active':null}}"  href="{{route('user.mailbox', 2)}}">
                    <i class="fa fa-comments" aria-hidden="true"></i>
                    <b>Thư chưa đọc</b>
                </a>
                <a class="nav-link text-left {{request()->mail_status == 3?'active':null}}"  href="{{route('user.mailbox', 3)}}">
                    <i class="fas fa-mail-bulk" aria-hidden="true"></i>
                    <b>Thư được ghim</b>
                </a>
                <a class="nav-link text-left {{request()->mail_status == 4?'active':null}}"  href="{{route('user.mailbox', 4)}}">
                    <i class="fa fa-folder" aria-hidden="true"></i>
                    <b>Thông báo tin đăng</b>
                </a>
                <a class="nav-link text-left {{request()->mail_status == 5?'active':null}}"  href="{{route('user.mailbox', 5)}}">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <b>Thông báo tài khoản</b>
                </a>
                <a class="nav-link text-left {{request()->mail_status == 6?'active':null}}"  href="{{route('user.mailbox', 6)}}">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                    <b>Thông báo từ hệ thống</b>
                </a> --}}
                <span class="text-blue w-100 text-left px-3 py-2 d-block fs-16">Tìm kiếm</span>
                <form action="" class="form-group mail-page__search-box mb-0 relative"> 
                    <span class="flex-center position-absolute">
                        <i class="fas fa-search"></i>                
                    </span>
                    <input class="form-control rounded-pill bg-gray " type="text" placeholder="Nhập từ khóa" name="search" value="{{ request()->search }}" >                  
                </form>
            </div>
        </div>
        <div class="col-12 col-md-9 h-100 mailbox-list p-3">
            <div class="h-100 bg-white mail-page__list relative" data-status="{{ request()->mail_status }}">
                @foreach($mails as $mail)
                    <a href="{{route('user.mailbox-detail', $mail->id)}}" class="mail-page__list-item relative hover-bg-gray d-inline-block w-100 px-3">
                        <div class="item @if($loop->index != 0) border-top @endif">
                            <div class="flex-start py-2">
                                <span
                                    class="small js-pin-mail hover-opacity {{ $mail->mailbox_pin == 1 ? 'text-yellow' : 'text-grey' }} mr-2"
                                    title="{{ $mail->mailbox_pin == 1 ? 'Bỏ ghim' : 'Ghim' }}"
                                    data-id="{{ $mail->id }}"
                                >
                                    <i class="fas fa-star"></i>
                                </span>
                                <span class="text-ellipsis mw-50 {{ $mail->mailbox_status == 1 ? '' : 'bold' }}">{{ $mail->mail_title }}</span>
                                &nbsp;-&nbsp;
                                <p class="text-grey text-ellipsis flex-1 mb-0">
                                    {!! strip_tags($mail->mail_content) !!}
                                </p>
                                <span class="text-blue">
                                    {{ \App\Helpers\Helper::getHumanTimeWithPeriod($mail->send_time) }} 
                                </span>
                            </div>
                        </div>
                        <x-common.loading class="inner small"/>
                    </a>
                @endforeach
            </div>
            <div class="table-pagination alpha">
                <div class="left"></div>
                <div class="right">
                    {{ $mails->render('user.page.pagination', ['mails' => $mails]) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
(() => {
    let isPinning = false;

    $('body').on('click', '.mail-page .js-pin-mail', function(event) {
        event.preventDefault()

        let _this = $(this),
            $item = _this.parents('.mail-page__list-item'),
            id = _this.data('id'),
            status = $item.parents('.mail-page__list').data('status');

        if (!id || isPinning) return

        isPinning = true
        $item.find('.c-overlay-loading').addClass('loading')

        $.ajax({
            url: `/thanh-vien/mails/${id}/pin`,
            method: 'POST',
            success: res => {
                isPinning = false
                $item.find('.c-overlay-loading').removeClass('loading')

                if (res && res.data) {
                    const data = res.data

                    data.is_pinned
                        ? $item.find('.js-pin-mail').addClass('text-yellow').removeClass('text-grey').attr('title', 'Bỏ ghim')
                        : $item.find('.js-pin-mail').addClass('text-grey').removeClass('text-yellow').attr('title', 'Ghim')

                    // bỏ ghim tại mục thư đã đc ghim, ẩn khỏi danh sách
                    if (!data.is_pinned && status == 3)
                        $item.remove()

                    if (res.message)
                        res.success
                            ? toastr.success(res.message)
                            : toastr.error(res.message)
                }
            },
            error: err => {
                isPinning = false
                $item.find('.c-overlay-loading').removeClass('loading')
                if (err && err.responseJSON && err.responseJSON.message)
                    toastr.error(err.responseJSON.message)
            }
        })
    })
}) ()
</script>
@endsection
