@extends('user.persolnal.master')

@if($params['user'])
    @section('title', $params['user']->getSeoTitle())
    @section('description', $params['user']->getSeoDescription() ?: data_get($home_seo_config, 'meta_desc', 'Nhà đất express'))
    @section('image', $params['user']->getSeoBanner())
@endif

@section('content')
    <x-user.persolnal.ads-page :item="$params['user']"></x-user.persolnal.ads-page>
    <div class="frame-back-1">
        <a href="/" class="item back-home">
            <i class="fas fa-home"></i>
        </a>
        <span class="scrollTop item">
            <i class="fas fa-arrow-up"></i>
        </span>
    </div>
    <main class="main">
        <x-user.persolnal.header :item="$params['user']"></x-user.persolnal.header>

        <div class="profile-content">
            <div class="container bg-unset">
                <div class="row">
                    <div class="col-md-4">
                        <div class="content-left">
                            <x-user.persolnal.side-bar :item="$params['user']"></x-user.persolnal.side-bar>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="content-right">
                            <div class="evaluate">
                                <div class="row omega">
                                    <div class="col-4">
                                        <div class="count-evaluate d-flex flex-column h-100">
                                            <div class="title-count-evaluate">
                                                <span>Xếp hạng</span>
                                            </div>
                                            <div class="content-count-evaluate flex-1">
                                                <h2>{{ data_get($rating, 'total') ?: 5 }}/5</h2>
                                                {{-- <img src="{{ asset('/user/images/profile/sao.png') }}" alt=""> --}}
                                                {{-- <img src="{{ asset('/images/icons/yellow-star.svg') }}" alt=""> --}}
                                                <div class="mb-1">
                                                    <x-common.color-star :stars="data_get($rating, 'total') ?: 5"/>
                                                </div>
                                                <p>{{$params['user']->rating_user->count()/3}} đánh giá</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-8">

                                        <div class="count-evaluate">
                                            <div class="title-count-evaluate">
                                                <span>Đánh giá chi tiết</span>
                                            </div>
                                            <div class="content-detail-evaluate">
                                                <div class="category-detail-evaluate">
                                                    <p class="fs-normal fw-500">Khả năng tư vấn</p>
                                                    <div class="flex-start">
                                                        <x-common.color-star :stars="data_get($rating, '1')" type="icon" />
                                                        <span class="ml-3">({{$params['user']->rating_user->where('rating_type',1)->count()}})</span>
                                                    </div>
                                                </div>
                                                <div class="category-detail-evaluate">
                                                    <p class="fs-normal fw-500">Mức độ tin cậy</p>
                                                    <div class="flex-start">
                                                        <x-common.color-star :stars="data_get($rating, '2')" type="icon" />

                                                        <span class="ml-3">({{$params['user']->rating_user->where('rating_type',2)->count()}})</span>
                                                    </div>
                                                </div>
                                                <div class="category-detail-evaluate">
                                                    <p class="fs-normal fw-500">Am hiểu về thị trường</p>
                                                    <div class="flex-start">
                                                        <x-common.color-star :stars="data_get($rating, '3')" type="icon" />

                                                        {{-- <img src="../images/profile/saothitruong.png" alt=""> --}}
                                                        <span class="ml-3">({{$params['user']->rating_user->where('rating_type',3)->count()}})</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if((\Illuminate\Support\Facades\Auth::guard('user')->id()??$params['user']->id)!=$params['user']->id
                                && (\DB::table('user_rating')->where(['user_id'=>\Illuminate\Support\Facades\Auth::guard('user')->id()??-1,'rating_user_id'=>$params['user']->id])->count()<1)
                                )
                            <form action="" method="post">
                            @csrf
                            <div class="evaluate-account">
                                <h5 class="fs-normal fw-500">Đánh giá tài khoản <span class="text-blue">{{ data_get($params, 'user.user_detail.fullname') }}</span></h5>

                                <div class="send-evaluate">
                                    <div class="row alphas">
                                        <div class="col-md-4 text-center">
                                            <div class="content-send-evaluate">
                                                <p>Khả năng tư vấn</p>
                                                <div>
                                                    <x-common.color-star type="icon-action" action-input-name="rate"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="content-send-evaluate">
                                                <p>Mức độ tin cậy</p>
                                                <div>
                                                    <x-common.color-star type="icon-action" action-input-name="rate_1"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="content-send-evaluate">
                                                <p>Am hiểu về thị trường</p>
                                                <div>
                                                    <x-common.color-star type="icon-action" action-input-name="rate_2"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="submit" class="btn btn-light-cyan send" value="Gửi đánh giá">
                                </div>
                            </div>
                        </form>
                            @endif
                            <div class="review-avaluate">
                                <h5>Bình luận
                                    @if(!\Illuminate\Support\Facades\Auth::guard('user')->check())
                                        <a href="javascript:void(0);" class="fs-normal fw-normal link-light-cyan js-open-login">( Đăng nhập để bình luận )</a>
                                    @endif
                                </h5>
                                <div class="form-send-avaluate">
                                    <form action="">
                                        <textarea name="content_comment" rows="5" class="add-comment-aveluate w-100" placeholder="Nhập bình luận....."></textarea>
                                        {{ show_validate_error($errors, 'content_comment') }}

                                        <button type="button" class="btn btn-light-cyan send-comment js-send-comment">Gửi bình luận</button>
                                    </form>
                                </div>
                                <div class="show-comment-avaluate">
                                    @forelse($comment as $i)
                                    <div class="content-comment-avaluate">
                                        <div class="row">
                                            <div class="col-1">
                                                <div class="avatar-comment">
                                                    <img class="object-cover" src="{{ asset($i->user->getExpertAvatar()) }}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-11">
                                                <div class="content-comment-customer">
                                                    <div class="name-comment-customer">{{$i->user->user_detail->fullname}}</div>
                                                    <div class="comment-date">Bình luận vào ngày {{date('d/m/Y',$i->created_at)}}</div>
                                                    @php
                                                    $array_rating = $i->user->rangting->where('rating_user_id',$params['user']->id)->pluck('star');
                                                    $value='';
                                                    @endphp
                                                    @if($user->id??-1 != $params['user']->id)
                                                    <div class="rating-customer">
                                                        @if(count($array_rating)==0)
                                                        {{"(Chưa đánh giá)"}}
                                                        @else
                                                            <div>
                                                            @for($count=1;$count<= (int)round($array_rating->avg());$count++)
                                                               <i class="fa fa-star text-warning"></i>
                                                            @endfor

                                                            </div>
                                                        @endif

                                                    </div>
                                                    @endif
                                                    <div class="posts-more dropdown">
                                                        <i class="fas fa-ellipsis-h" data-toggle="dropdown"></i>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#" class="dropdown-item">
                                                                Sửa
                                                            </a>
                                                            <a href="#" class="dropdown-item">
                                                                Xóa
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="comment-customer text-break">{{$i->comment_content}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="reply-comment">
                                            <button type="submit" data-url="{{route('trang-ca-nhan.like-rating',$i->id)}}" data-id="{{$i->id}}" class="like {{in_array($user->id??-1,$i->like->pluck('user_id')->toArray())?"active":""}}"><img src="/frontend/images/profile/like.png" alt="">Hữu ích(<span class="count_like">{{$i->like->count()}}</span>)</button>
                                            <button type="submit" class="reply-elu"><img src="/frontend/images/profile/reply.png" alt="">Trả lời</button>
                                        </div>
                                        <div class="form-comment-content-reply" style="display: none;">
                                            <div class="row">
                                                <div class="col-1">
                                                    <div class="avatar-rep-comment">
                                                        <img src="{{asset($user->user_detail->image_url??'')}}" alt="">
                                                    </div>
                                                </div>
                                                <div class="col-11">
                                                    <div class="content-comment-customer">
                                                        <div class="name-comment-customer">{{$user->user_detail->fullname??'Đăng nhập để bình luận'}}</div>
                                                        <textarea name="content_comment" data-comment_id = "{{$i->id}}" rows="4" style="width: 100%;" class="add-reply-comment-aveluate reply_comment_rating" placeholder="Nhập bình luận....."></textarea>
{{--                                                        <div class="posts-more dropdown">--}}
{{--                                                            <i class="fas fa-ellipsis-h" data-toggle="dropdown"></i>--}}
{{--                                                            <div class="dropdown-menu dropdown-menu-right">--}}
{{--                                                                <a href="#" class="dropdown-item">--}}
{{--                                                                    Sửa--}}
{{--                                                                </a>--}}
{{--                                                                <a href="#" class="dropdown-item">--}}
{{--                                                                    Xóa--}}
{{--                                                                </a>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content-reply">
                                            @if ($i->child->count()>0)
                                            @foreach($i->child as $item)
                                            <div class="row item-content-reply">
                                                <div class="col-1">
                                                    <div class="avatar-rep-comment">
                                                        <img src="{{asset($i->user->user_detail->image_url)}}" alt="">
                                                    </div>
                                                </div>
                                                <div class="col-11">
                                                    <div class="content-comment-customer">
                                                        <div class="name-comment-customer">{{$item->user->user_detail->fullname}}</div>
                                                        <div class="comment-date">Bình luận vào ngày {{date('d/m/Y',$item->created_at)}}</div>
                                                        @php
                                                            $array_rating = $item->user->rangting->where('rating_user_id',$params['user']->id)->pluck('star');
                                                            $value='';
                                                        @endphp
                                                        @if($user->id??-1 != $params['user']->id)
                                                            <div class="rating-customer">
                                                                @if(count($array_rating)==0)
                                                                    {{"(Chưa đánh giá)"}}
                                                                @else
                                                                    <div>
                                                                        @for($count=1;$count<= (int)round($array_rating->avg());$count++)
                                                                            <i class="fa fa-star text-warning"></i>
                                                                        @endfor

                                                                    </div>
                                                                @endif

                                                            </div>
                                                        @endif
                                                        <div class="comment-customer text-break">{{$item->comment_content}}</div>
                                                        <div class="posts-more dropdown">
                                                            <i class="fas fa-ellipsis-h" data-toggle="dropdown"></i>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a href="#" class="dropdown-item">
                                                                    Sửa
                                                                </a>
                                                                <a href="#" class="dropdown-item">
                                                                    Xóa
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                        <h6 class="text-center  no-rating-comment">Chưa có bình luận</h6>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
@section('Style')
    <style>
    .avatar-rep-comment{
        max-width: 45px;
        max-height: 45px;
        border-radius: 50%;
        overflow: hidden;
    }
    .avatar-rep-comment img{
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        border-radius: 50%;
    }
    .reply-comment .active{
        background: rgb(55, 169, 255)!important;
        color:white!important;
    }
    .title-count-evaluate{
        padding: 4px 0;
    }
    .category-detail-evaluate span{
        padding-top: 0;
    }
    .category-detail-evaluate {
        align-items: center;
    }
    .content-send-evaluate p{
        font-size: 16px;
    }
    @media only screen and (max-width: 1650px) {
        .category-detail-evaluate p {
            font-size: 14px;
        }

        .profile-content .content-right {
            padding: 35px 0 35px 21px
        }
    }
    @media only screen and (max-width: 1365px) {
        .category-detail-evaluate p{
            width: 50%!important;
        }
    }
    input[class="rating"]{
        display: none;
    }
    </style>
@endsection
@section('script')
    <script>

</script>
<script>
    $('body').on('click', '.js-send-comment', function() {
        sendComment()
    });

    $('body').on('keypress','textarea.add-comment-aveluate', function (e){
        if(e.which ==13){
            e.preventDefault();
            sendComment()
        }
    });

    const sendComment = () => {
            var hii = $('textarea.add-comment-aveluate').val();
            if (hii != "") {

                var form = new FormData();
                form.append('content_comment',hii);
                form.append('_token','{{csrf_token()}}');
                var url = '{{route('trang-ca-nhan.comment-rating',$params['user']->user_code)}}';
                $('textarea.add-comment-aveluate').val('');
                $.ajax({
                    type:'post',
                    url :url,
                    data : form,
                    processData: false,
                    contentType: false,
                    cache:false,
                    success:function (data){
                        if(data['star'] == '-1'){
                            var ranting = '(Chưa đánh giá)';
                        }
                        else {
                            var ranting=''
                            for(i = 1; i <= data['star'];i++)
                             ranting += '<i class="fa fa-star text-warning"></i>';
                        }
                        if(data['check_rating'] ==2 ) ranting='';
                        $('.show-comment-avaluate').prepend(`
                        <div class="content-comment-avaluate">
                        <div class="row">
                            <div class="col-1">
                                <div class="avatar-comment">
                                    <img src="/`+data['avatar']+`" alt="">
                                </div>
                            </div>
                            <div class="col-11">
                                <div class="content-comment-customer">
                                    <div class="name-comment-customer">`+data['fullname']+`</div>
                                    <div class="comment-date">Bình luận vào ngày `+data['created_at']+`</div>
                                    <div class="rating-customer">
                                    `+ranting+`
                                    </div>
                                    <div class="comment-customer text-break">` + hii + `</div>
                                </div>
                            </div>
                        </div>
                        <div class="reply-comment">
                            <button type="submit" class="like" data-url="/trang-ca-nhan/like-rating/`+data['id']+`" data-id="`+data['id']+`"><img src="/frontend/images/profile/like.png" alt="">Hữu ích(<span class="count_like">0</span>)</button>
                            <button type="submit" class="reply-elu"><img src="/frontend/images/profile/reply.png" alt="">Trả lời</button>
                        </div>
                        <div class="form-comment-content-reply" style="display: none;">
                                            <div class="row">
                                                <div class="col-1">
                                                    <div class="avatar-rep-comment">
                                                        <img src="/`+data['avatar']+`" alt="">
                                                    </div>
                                                </div>
                                                <div class="col-11">
                                                    <div class="content-comment-customer">
                                                        <div class="name-comment-customer">`+data['fullname']+`</div>
                                                        <textarea  name="content_comment" data-comment_id = "`+data['id']+`" rows="4" style="width: 100%;" class="add-reply-comment-aveluate reply_comment_rating" placeholder="Nhập bình luận....."></textarea>
                                                        <div class="posts-more dropdown">
                                                            <i class="fas fa-ellipsis-h" data-toggle="dropdown"></i>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a href="#" class="dropdown-item">
                                                                    Sửa
                                                                </a>
                                                                <a href="#" class="dropdown-item">
                                                                    Xóa
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content-reply"></div>`);
                    },
                    error :function (error){
                        if(error.status == 403){
                            toastr.error(error.responseJSON);
                        }
                        if(error.status == 404){
                            toastr.error(error.responseJSON);
                        }
                    }
                })
            }
    }

    $('body').on('keypress','textarea.reply_comment_rating',function (e){
            if(e.which ==13){
                e.preventDefault();
                var hii =  $(this).val();
                if (hii != "") {
                    var comment_id = $(this).data('comment_id');
                    var comment = $(this).parents(".content-comment-avaluate").find(".content-reply");
                    var form = new FormData();
                    form.append('content_comment',hii);
                    form.append('comment_id',comment_id);
                    form.append('_token','{{csrf_token()}}');
                    var url = '{{route('trang-ca-nhan.comment-rating',$params['user']->user_code)}}';
                    $('textarea.reply_comment_rating').val('');
                    $.ajax({
                        type:'post',
                        url :url,
                        data : form,
                        processData: false,
                        contentType: false,
                        cache:false,
                        success:function (data){
                            if(data['star'] == '-1'){
                                var ranting = '(Chưa đánh giá)';
                            }
                            else {
                                var ranting=''
                                for(i = 1; i <= data['star'];i++)
                                    ranting += '<i class="fa fa-star text-warning"></i>';
                            }
                            if(data['check_rating'] ==2 ) ranting='';
                           comment.prepend(`<div class="row item-content-reply">
                            <div class="col-1">
                                <div class="avatar-rep-comment">
                                    <img src="/`+data['avatar']+`" alt="">
                                </div>
                            </div>
                            <div class="col-11">
                                <div class="content-comment-customer">
                                    <div class="name-comment-customer">`+data['fullname']+`</div>
                                    <div class="comment-date">Bình luận vào ngày `+data['created_at']+`</div>
                                    <div class="rating-customer">
                                    `+ranting+`
                                    </div>
                                    <div class="comment-customer text-break">` + hii + `</div>
                                    <div class="posts-more dropdown">
                                        <i class="fas fa-ellipsis-h" data-toggle="dropdown"></i>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item">
                                                Sửa
                                            </a>
                                            <a href="#" class="dropdown-item">
                                                Xóa
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`);
                        },
                        error :function (error){
                            if(error.status == 403){
                                toastr.error(error.responseJSON);
                            }
                            if(error.status == 404){
                                toastr.error(error.responseJSON);
                            }
                        }

                    });
                }

            }

    });
</script>
@endsection
