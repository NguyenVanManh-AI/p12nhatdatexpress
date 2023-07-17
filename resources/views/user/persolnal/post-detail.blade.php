@extends('user.persolnal.master')

@section('title', $user->getSeoTitle())
@section('description', $user->getSeoDescription() ?: data_get($home_seo_config, 'meta_desc', 'Nhà đất express'))
@section('image', $user->getSeoBanner())

@section('content')
    <x-user.persolnal.ads-page :item="$user"></x-user.persolnal.ads-page>
    <div class="frame-back-1">
        <a href="/" class="item back-home">
            <i class="fas fa-home"></i>
        </a>
        <span class="scrollTop item">
            <i class="fas fa-arrow-up"></i>
        </span>
    </div>
    <main class="main">
        <x-user.persolnal.header :item="$user"></x-user.persolnal.header>
        <div class="profile-content">
            <div class="container bg-unset">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="mt-3">
                            <div class="posts-item">
                                <div class="header-posts">
                                    <div class="user-posts">
                                        <div class="avatar">
                                            <a href="{{route('trang-ca-nhan.dong-thoi-gian',$post->user->user_code)}}">
                                                <img src="{{asset($post->user->user_detail->image_url)}}" alt="">
                                            </a>
                                        </div>
                                        <div class="user-name">
                                            <a href="{{route('trang-ca-nhan.dong-thoi-gian',$post->user->user_code)}}">{{$post->user->user_detail->fullname}}</a>
                                            <span
                                                class="post-time">{{\App\Helpers\Helper::get_time_to($post->created_at)}}</span>
                                        </div>
                                    </div>
                                    <div class="posts-more group-report">
                                        <div class="button-report">
                                            <span><i class="fas fa-ellipsis-h"></i></span>
                                            <div class="report-main">
                                                <ul>
                                                    <li>
                                                        <a class="report-content" data-post_id="{{$post->id}}"
                                                            href="javascript:{}">Báo cáo</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="posts-content">
                                    <div class="info-desc mb-2">
                                        <div class="excerpt text-break">
                                            {{$post->post_content}}
                                        </div>
                                        {{--                                            <span class="show-hide">Hiện thêm &gt;&gt;</span>--}}
                                    </div>
                                    @if($post->post_image!=null)
                                        @if(count(json_decode($post->post_image))== 1)
                                            <div class="posts-gallery">
                                                <div class="one-image">
                                                    <a href="{{asset(json_decode($post->post_image)[0])}}"
                                                        data-fullscreenmode="true" data-transition="slide"
                                                        class="html5lightbox" data-group="set{{$post->id}}"
                                                        data-width="100%" data-height="400">
                                                        <img src="{{asset(json_decode($post->post_image)[0])}}"
                                                                class="h-400">
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        @if(count(json_decode($post->post_image))== 2)
                                            <div class="posts-gallery">
                                                <div class="row two-image">
                                                    @foreach(json_decode($post->post_image) as $posttem)
                                                        <a href="{{asset($posttem)}}" data-fullscreenmode="true"
                                                            data-transition="slide" class="html5lightbox col-6"
                                                            data-group="set{{$post->id}}" data-width="100%"
                                                            data-height="400"><img src="{{asset($posttem)}}"
                                                                                    class="h-400"></a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        @if(count(json_decode($post->post_image))== 3)
                                            <div class="posts-gallery">
                                                <div class="row d-flex three-image">
                                                    <div class="col-8">
                                                        <a href="{{asset(json_decode($post->post_image)[0])}}"
                                                            data-fullscreenmode="true" data-transition="slide"
                                                            class="html5lightbox" data-group="set{{$post->id}}"
                                                            data-width="100%" data-height="400"><img
                                                                src="{{asset(json_decode($post->post_image)[0])}}"
                                                                class="h-400"></a>
                                                    </div>
                                                    <div class="col-4 d-flex flex-column justify-content-between">
                                                        <a href="{{asset(json_decode($post->post_image)[1])}}"
                                                            data-fullscreenmode="true" data-transition="slide"
                                                            class="html5lightbox" data-group="set{{$post->id}}"
                                                            data-width="100%" data-height="400"><img
                                                                src="{{asset(json_decode($post->post_image)[1])}}"
                                                                class="h-198"></a>
                                                        <a href="{{asset(json_decode($post->post_image)[2])}}"
                                                            data-fullscreenmode="true" data-transition="slide"
                                                            class="html5lightbox" data-group="set{{$post->id}}"
                                                            data-width="100%" data-height="400"><img
                                                                src="{{asset(json_decode($post->post_image)[2])}}"
                                                                class="h-198"></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if(count(json_decode($post->post_image)) == 4)
                                            <div class="posts-gallery">
                                                <div class="one-image">
                                                    <a href="{{asset(json_decode($post->post_image)[0]??'')}}"
                                                        data-fullscreenmode="true" data-transition="slide"
                                                        class="html5lightbox" data-group="set{{$post->id}}"
                                                        data-width="100%" data-height="400">
                                                        <img src="{{asset(json_decode($post->post_image)[0]??'')}}"
                                                                class="h-340">
                                                    </a>
                                                </div>
                                                <div class="row mt-1">
                                                    <div class="col-4">
                                                        <a href="{{asset(json_decode($post->post_image)[1]??'')}}"
                                                            data-fullscreenmode="true" data-transition="slide"
                                                            class="html5lightbox" data-group="set{{$post->id}}"
                                                            data-width="100%" data-height="400">
                                                            <img src="{{asset(json_decode($post->post_image)[1]??'')}}"
                                                                    class="h-200">
                                                        </a>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="{{asset(json_decode($post->post_image)[2]??'')}}"
                                                            data-fullscreenmode="true" data-transition="slide"
                                                            class="html5lightbox" data-group="set{{$post->id}}"
                                                            data-width="100%" data-height="400">
                                                            <img src="{{asset(json_decode($post->post_image)[2]??'')}}"
                                                                    class="h-200">
                                                        </a>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="{{asset(json_decode($post->post_image)[3]??'')}}"
                                                            data-fullscreenmode="true" data-transition="slide"
                                                            class="html5lightbox" data-group="set{{$post->id}}"
                                                            data-width="100%" data-height="400">
                                                            <img src="{{asset(json_decode($post->post_image)[3]??'')}}"
                                                                    class="h-200">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    @php

                                        @endphp

                                    <div class="posts-statistic">
                                        <div class="left">
                                            <div class="stt-reaction item ">
                                                <div class="icon">
                                                    <i class="fas fa-thumbs-up"></i>
                                                </div>
                                                <div class="info">
                                                    <span class="num_like{{$post->id}}">{{$post->like->count()}}</span>
                                                    Lượt thích
                                                </div>
                                            </div>
                                        </div>
                                        <div class="right">
                                            <div class="stt-comment item">
                                                <div class="icon">
                                                    <i class="far fa-comment-dots"></i>
                                                </div>
                                                <div class="info">
                                                    <span
                                                        class="comment_number{{$post->id}}">{{$post->comment->count()}}</span>
                                                    Bình luận
                                                </div>
                                            </div>
                                            <div class="posts-view item">
                                                <div class="icon">
                                                    <i class="far fa-eye"></i>
                                                </div>
                                                <div class="info">
                                                    {{$post->num_view}} Lượt xem
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="posts-act">
                                        <div
                                            class="posts-reaction like like-post item {{$post->like->where('post_id',$post->id)->where('user_id',\Illuminate\Support\Facades\Auth::guard('user')->id()??-1)->count()>=1?"active":""}}"
                                            data-id="{{$post->id}}">
                                            <i class="fas fa-thumbs-up"></i>
                                            <span>Thích</span>
                                        </div>
                                        <div class="act-comment item">
                                            <i class="far fa-comment-alt"></i>
                                            <span>Bình luận</span>
                                        </div>
                                        <div class="item dropdown">
                                            <a class="posts-share item" data-toggle="dropdown">
                                                <i class="fas fa-share"></i>
                                                <span>Chia sẻ</span>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a href="#" class="dropdown-item">
                                                    <div class="item">
                                                        <i class="far fa-share-square"></i> Chia sẻ ngay
                                                    </div>
                                                </a>
                                                <a href="#" class="dropdown-item">
                                                    <div class="item">
                                                        <i class="far fa-edit"></i> Chia sẻ lên bảng tin
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="posts-comment">
                                        @if(auth()->guard('user')->check())
                                            <div class="form-comment">
                                                <div class="form-left">
                                                    <div class="avatar">
                                                        <img
                                                            src={{asset(auth()->guard('user')->user()->user_detail->image_url??'')}} alt="">
                                                    </div>
                                                </div>
                                                <div class="form-right">
                                                    <form action="" method="post">
                                                        <textarea class="box-comment" data-id="{{$post->id}}"
                                                                    data-url="{{route('trang-ca-nhan.add-comment',$post->id)}}"
                                                                    data-token="{{csrf_token()}}"
                                                                    placeholder="Viết bình luận"></textarea>
                                                    </form>
                                                    <div class="d-flex pl-3 pr-3 justify-content-between">
                                                        <span>Nhấn Enter để đăng</span>
                                                        <span class="show-hide-comment">Xem bình luận</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="list-comment" id="list-comment{{$post->id}}">

                                            @foreach($post->comment->where('parent_id',null) as $comment)
                                                <div class="comment-item d-flex">
                                                    <div class="comment-left">
                                                        <div class="avatar">
                                                            <a href="{{asset($comment->user->user_detail->image_url??'')}}">
                                                                <img
                                                                    src="{{asset($comment->user->user_detail->image_url??'')}}"
                                                                    alt="">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="comment-right">
                                                        <div class="comment-top">
                                                            <div class="group-report" style="display: flex">
                                                                <div class="comment-user">
                                                                    <a href="#">{{$comment->user->user_detail->fullname}}</a>
                                                                </div>
                                                                <div class="button-report">
                                                                    <span><i class="fas fa-ellipsis-h"></i></span>
                                                                    <div class="report-main"
                                                                            style="z-index: 5;background: white">
                                                                        <ul>
                                                                            <li>
                                                                                <a class="report_comment"
                                                                                    data-comment_id="{{$comment->id}}"
                                                                                    href="javascript:{}">Báo cáo</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="comment-content text-break">
                                                                {{$comment->comment_content}}
                                                            </div>

                                                            {{--                                                            <div class="comment-more">--}}
                                                            {{--                                                                <i class="fas fa-ellipsis-h"></i>--}}
                                                            {{--                                                            </div>--}}

                                                            <div
                                                                style="display:{{$comment->like->count()>0?"block":"none"}}"
                                                                class="count-reaction">
                                                                <i class="fas fa-thumbs-up"></i>
                                                                <span>{{$comment->like->count()}}</span>
                                                            </div>

                                                        </div>
                                                        <div class="comment-bottom comment_{{$comment->id}}">
                                                            <div class="comment-act mt-1">
                                                                <span
                                                                    class="like like_comment {{in_array(\Illuminate\Support\Facades\Auth::guard('user')->id()??-1,$comment->like->pluck('user_id')->toArray())?"active":""}}"
                                                                    data-comment_id="{{$comment->id}}">Thích</span>•<span
                                                                    class="reply"
                                                                    data-comment_id="{{$comment->id}}">Trả lời</span>•<span
                                                                    class="time-cm">{{\App\Helpers\Helper::get_time_to($comment->created_at)}}</span>
                                                            </div>
                                                            <div class="comment-reply">
                                                                @if($comment->child->count()>0)
                                                                    <div class="count-reply">
                                                                        <i class="fas fa-reply"></i>{{$comment->child->count()}}
                                                                        Phản hồi
                                                                    </div>
                                                                    @foreach($comment->child as $child)
                                                                        <div class="comment-item d-flex">
                                                                            <div class="comment-left">
                                                                                <div class="avatar">
                                                                                    <a href="{{asset($child->user->user_detail->image_url??SystemConfig::USER_AVATAR)}}">
                                                                                        <img
                                                                                            src="{{asset($child->user->user_detail->image_url??SystemConfig::USER_AVATAR)}}"
                                                                                            alt="">
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                            <div class="comment-right">
                                                                                <div class="comment-top">
                                                                                    <div class="group-report"
                                                                                            style="display: flex">
                                                                                        <div class="comment-user">
                                                                                            <a href="#">{{$child->user->user_detail->fullname}}</a>
                                                                                        </div>
                                                                                        <div class="button-report">
                                                                                            <span><i
                                                                                                    class="fas fa-ellipsis-h"></i></span>
                                                                                            <div class="report-main"
                                                                                                    style="z-index: 5;background: white">
                                                                                                <ul>
                                                                                                    <li>
                                                                                                        <a class="report_comment"
                                                                                                            data-comment_id="{{$child->id}}"
                                                                                                            href="javascript:{}">Báo
                                                                                                            cáo</a>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="comment-content text-break">
                                                                                        {{$child->comment_content}}
                                                                                    </div>
                                                                                    {{-- <div class="comment-more">
                                                                                        <i class="fas fa-ellipsis-h"></i>
                                                                                    </div> --}}
                                                                                    <div
                                                                                        style="display:{{$child->like->count()>0?"block":"none"}}"
                                                                                        class="count-reaction">
                                                                                        <i class="fas fa-thumbs-up"></i>
                                                                                        <span
                                                                                            class="count_like_comment">{{$child->like->count()}}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div
                                                                                    class="comment-bottom comment_{{$child->id}}">
                                                                                    <div class="comment-act mt-1">
                                                                                        <span
                                                                                            class="like like_comment {{in_array(\Illuminate\Support\Facades\Auth::guard('user')->id()??-1,$child->like->pluck('user_id')->toArray())?"active":""}}"
                                                                                            data-comment_id="{{$child->id}}">Thích</span>•<span
                                                                                            class="reply"
                                                                                            data-comment_id="{{$comment->id}}">Trả lời</span>•<span
                                                                                            class="time-cm">{{\App\Helpers\Helper::get_time_to($child->created_at)}}</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>

                                                        </div>
                                                        <div class="comment-bottom-1">
                                                            <div class="form-comment">
                                                                <div class="form-right">
                                                                    <form action="" method="post">
                                                                        <textarea class="box-comment-1"
                                                                                    data-comment_id="{{$comment->id}}"
                                                                                    data-token="{{csrf_token()}}"
                                                                                    data-url="{{route('trang-ca-nhan.add-comment',$post->id)}}"
                                                                                    placeholder="Viết bình luận"></textarea>
                                                                    </form>
                                                                    <div
                                                                        class="d-flex pl-3 pr-3 justify-content-between">
                                                                        <span>Nhấn Enter để đăng</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
@section('style')
    <style>

    </style>
@endsection
@section('script')
    <script>
        $('.like-post').click(function () {
            var id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: '{{route('trang-ca-nhan.like','')}}' + '/' + id,
                success: function (data) {
                    if (data == 'like') {
                        var num = $('.num_like' + id).html();
                        num = parseInt(num);
                        $('.num_like' + id).empty();
                        $('.num_like' + id).append(num + 1);
                    } else {
                        var num = $('.num_like' + id).html();
                        num = parseInt(num);
                        $('.num_like' + id).empty();
                        $('.num_like' + id).append(num - 1);
                    }

                },
                error: function (error) {
                    toastr.error(error.responseJSON);
                }
            });

        });

        $('body').on('click', '.like_comment', function () {
            var id = $(this).data('comment_id');
            var fin = '.comment_' + id;
            var value = $(this).parents(fin).siblings('.comment-top').children('.count-reaction').children('span');
            var parent = $(this).parents(fin).siblings('.comment-top').children('.count-reaction');
            if (id != null) {
                $.ajax({
                    type: 'GET',
                    url: '{{route('trang-ca-nhan.like-comment','')}}' + '/' + id,
                    success: function (data) {

                        if (data == 'like') {

                            var number = parseInt(value.html());
                            value.empty();
                            if (number + 1 >= 1) {
                                parent.css('display', 'block');
                            }
                            value.append(number + 1);
                        } else {

                            var number = parseInt(value.html());
                            if (number - 1 <= 0) {
                                parent.css('display', 'none');
                            }
                            value.empty();
                            value.append(number - 1);
                        }

                    },
                    error: function (error) {
                        toastr.error(error.responseJSON);
                    }
                });
            }


        });
    </script>
    <script>
        // bình luận
        $("textarea.box-comment").on('keypress', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                let _this = $(this)
                var post_id = $(this).data('id');
                var url_Data = $(this).data('url');
                var token = $(this).data('token');
                if ($(this).val() != '') {
                    var content = $(this).val();
                    var formData = new FormData();
                    formData.append('content_comment', content);
                    formData.append('_token', token);
                    $.ajax({
                        type: 'post',
                        url: url_Data,
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function (data) {
                            _this.val('')
                            // toastr.success(data);
                            // append html
                            var idd = '#list-comment' + post_id;
                            var comment_class = '.comment_number' + post_id;
                            var number_comment = $(comment_class).html();
                            $(comment_class).empty();
                            $(comment_class).html(parseInt(number_comment) + 1);

                            $(idd).append(`<div class="comment-item d-flex">
                <div class="comment-left">
                    <div class="avatar">
                        <a href="/` + data['avatar'] + `">
                            <img src="/` + data['avatar'] + `" alt="">
                        </a>
                    </div>
                </div>
                <div class="comment-right">
                    <div class="comment-top">
                        <div class="comment-user">
                            <a href="#">` + data['fullname'] + `</a>
                        </div>
                        <div class="comment-content text-break">
                            ` + data['comment']['comment_content'] + `
                        </div>
                        <div class="comment-more">
                            <i class="fas fa-ellipsis-h"></i>
                        </div>
                         <div style="display: none" class="count-reaction">
                                                        <i class="fas fa-thumbs-up"></i> <span>0</span>
                         </div>
                    </div>
                    <div class="comment-bottom comment_` + data['comment']['id'] + `">
                        <div class="comment-act mt-1">
                            <span class="like like_comment" data-comment_id="` + data['comment']['id'] + `">Thích</span>•<span class="reply" data-comment_id="` + data['comment']['id'] + `">Trả lời</span>•<span class="time-cm">` + data['date'] + `</span>
                        </div>
                        <div class="comment-reply"></div>
                    </div>
                    <div class="comment-bottom-1" style="display: none;">
                        <div class="form-comment">
                            <div class="form-right">
                                <form action="" method="post">
                                    <textarea class="box-comment-1" data-token="{{csrf_token()}}" data-url="{{route('trang-ca-nhan.add-comment','')}}/` + data['comment']['user_post_id'] + `" data-comment_id="` + data['comment']['id'] + `" placeholder="Viết bình luận"></textarea>
                                </form>
                                <div class="d-flex pl-3 pr-3 justify-content-between">
                                    <span>Nhấn Enter để đăng</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`);
                            if(!$(idd).hasClass("show")){
                                $(idd).toggleClass("show");
                                $(idd).prev().find(".show-hide-comment").html('Ẩn bình luận');
                            }
                            
                            // end append
                        },
                        error: function (errors) {
                            toastr.error(error.responseJSON);
                        }
                    });
                }

            }
        });
    </script>
@endsection
@section('Style')

@endsection
