@foreach($posts as $i)
    <div class="posts-item">
        <div class="header-posts d-flex justify-content-between pb-2">
            <div class="user-posts d-flex justify-content-start align-items-center">
                <div class="avatar">
                    <a href="{{route('trang-ca-nhan.dong-thoi-gian',$i->user->user_code)}}">
                        <img class="object-fit-contain border rounded-circle w-100 h-100" src="{{asset($i->user->user_detail->image_url)}}" alt="">
                    </a>
                </div>
                <div class="user-name">
                    <a href="{{route('trang-ca-nhan.dong-thoi-gian',$i->user->user_code)}}">{{$i->user->user_detail->fullname}}</a>
                    <span
                        class="post-time">{{\App\Helpers\Helper::get_time_to($i->created_at)}}</span>
                </div>
            </div>
            <div class="posts-more group-report">
                <div class="button-report mr-3">
                    <span><i class="fas fa-ellipsis-h"></i></span>
                    <div class="report-main">
                        <ul>
                            <li>
                                <a class="report-content" data-post_id="{{$i->id}}"
                                    href="javascript:{}">Báo cáo</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="posts-content bg-white mb-5 p-4">
            <div class="info-desc">
                <div class="excerpt text-break">
                    {{$i->post_content}}
                </div>
                {{-- <span class="show-hide">Hiện thêm &gt;&gt;</span> --}}
            </div>
            @if($i->post_image!=null)
                @if(count(json_decode($i->post_image))== 1)
                    <div class="posts-gallery">
                        <div class="one-image">
                            <a href="{{asset(json_decode($i->post_image)[0])}}"
                                data-fullscreenmode="true" data-transition="slide"
                                class="html5lightbox" data-group="set{{$i->id}}"
                                data-width="100%" data-height="400">
                                <img src="{{asset(json_decode($i->post_image)[0])}}"
                                        class="h-400">
                            </a>
                        </div>
                    </div>
                @endif
                @if(count(json_decode($i->post_image))== 2)
                    <div class="posts-gallery">
                        <div class="row two-image">
                            @foreach(json_decode($i->post_image) as $item)
                                <a href="{{asset($item)}}" data-fullscreenmode="true"
                                    data-transition="slide" class="html5lightbox col-6"
                                    data-group="set{{$i->id}}" data-width="100%"
                                    data-height="400"><img src="{{asset($item)}}"
                                                            class="h-400"></a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if(count(json_decode($i->post_image))== 3)
                    <div class="posts-gallery">
                        <div class="row d-flex three-image">
                            <div class="col-8">
                                <a href="{{asset(json_decode($i->post_image)[0])}}"
                                    data-fullscreenmode="true" data-transition="slide"
                                    class="html5lightbox" data-group="set{{$i->id}}"
                                    data-width="100%" data-height="400"><img
                                        src="{{asset(json_decode($i->post_image)[0])}}"
                                        class="h-400"></a>
                            </div>
                            <div class="col-4 d-flex flex-column justify-content-between">
                                <a href="{{asset(json_decode($i->post_image)[1])}}"
                                    data-fullscreenmode="true" data-transition="slide"
                                    class="html5lightbox" data-group="set{{$i->id}}"
                                    data-width="100%" data-height="400"><img
                                        src="{{asset(json_decode($i->post_image)[1])}}"
                                        class="h-198"></a>
                                <a href="{{asset(json_decode($i->post_image)[2])}}"
                                    data-fullscreenmode="true" data-transition="slide"
                                    class="html5lightbox" data-group="set{{$i->id}}"
                                    data-width="100%" data-height="400"><img
                                        src="{{asset(json_decode($i->post_image)[2])}}"
                                        class="h-198"></a>
                            </div>
                        </div>
                    </div>
                @endif
                @if(count(json_decode($i->post_image)) == 4)
                    <div class="posts-gallery">
                        <div class="one-image">
                            <a href="{{asset(json_decode($i->post_image)[0]??'')}}"
                                data-fullscreenmode="true" data-transition="slide"
                                class="html5lightbox" data-group="set{{$i->id}}"
                                data-width="100%" data-height="400">
                                <img src="{{asset(json_decode($i->post_image)[0]??'')}}"
                                        class="h-340">
                            </a>
                        </div>
                        <div class="row mt-1">
                            <div class="col-4">
                                <a href="{{asset(json_decode($i->post_image)[1]??'')}}"
                                    data-fullscreenmode="true" data-transition="slide"
                                    class="html5lightbox" data-group="set{{$i->id}}"
                                    data-width="100%" data-height="400">
                                    <img src="{{asset(json_decode($i->post_image)[1]??'')}}"
                                            class="h-200">
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="{{asset(json_decode($i->post_image)[2]??'')}}"
                                    data-fullscreenmode="true" data-transition="slide"
                                    class="html5lightbox" data-group="set{{$i->id}}"
                                    data-width="100%" data-height="400">
                                    <img src="{{asset(json_decode($i->post_image)[2]??'')}}"
                                            class="h-200">
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="{{asset(json_decode($i->post_image)[3]??'')}}"
                                    data-fullscreenmode="true" data-transition="slide"
                                    class="html5lightbox" data-group="set{{$i->id}}"
                                    data-width="100%" data-height="400">
                                    <img src="{{asset(json_decode($i->post_image)[3]??'')}}"
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
                            <span class="num_like{{$i->id}}">{{$i->like->count()}}</span>
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
                                            class="comment_number{{$i->id}}">{{$i->comment->count()}}</span>
                            Bình luận
                        </div>
                    </div>
                    <div class="posts-view item">
                        <div class="icon">
                            <i class="far fa-eye"></i>
                        </div>
                        <div class="info">
                            {{$i->num_view}} Lượt xem
                        </div>
                    </div>
                </div>
            </div>
            <div class="posts-act">
                <div
                    class="posts-reaction like like-post item {{$i->like->where('post_id',$i->id)->where('user_id',\Illuminate\Support\Facades\Auth::guard('user')->id()??-1)->count()>=1?"active":""}}"
                    data-id="{{$i->id}}">
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
                @if(\Illuminate\Support\Facades\Auth::guard('user')->check())
                    <div class="form-comment">
                        <div class="form-left">
                            <div class="avatar">
                                <img class="object-fit-contain border rounded-circle w-100 h-100" src={{asset($user->user_detail->image_url??'')}} alt="">
                            </div>
                        </div>
                        <div class="form-right">
                            <form action="" method="post">
                                            <textarea class="box-comment" data-id="{{$i->id}}"
                                                        data-url="{{route('trang-ca-nhan.add-comment',$i->id)}}"
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
                <div class="list-comment" id="list-comment{{$i->id}}">

                    @foreach($i->comment->where('parent_id',null) as $comment)
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
                                            <div class="report-main">
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
                                                                    <span><i class="fas fa-ellipsis-h"></i></span>
                                                                    <div class="report-main">
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
                                                                        data-url="{{route('trang-ca-nhan.add-comment',$i->id)}}"
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
@endforeach