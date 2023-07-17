@if($is_hidden == 0)
<section class="focus">
    <div class="container">
        @if($top_1 || $list->count() > 0)
            <div class="row">
            @if($is_special_group)
                @foreach($list as $item)
                    <div class="col-md-6">
                    <div class="focus-left">
                        <div class="thumbnail">
                            <img src="{{asset($item->image_url)}}" alt="" style="height: 385px; object-fit: cover">
                            <a href="{{$item->video_url}}" class="poppup-video html5lightbox-{{$item->id}}" data-id="{{$item->id}}" data-titlestyle="right" data-description="<h3 class='title-box'>Tiêu đề: {{$item->news_title}}</h3>
                                    <span class='box-meta'>
                                        <span class='box-view'>{{$item->num_view}} lượt xem </span>
                                        <span class='box-time'>• {{date('d/m/Y', $item->created_at)}}</span>
                                    </span>
                                    <div class='box-function'>
                                        <span class='@auth('user') box-like @endauth @if(isset($item->like_type) && $item->like_type == 1) active @endif' data-id='{{$item->id}}'><i class='fas fa-thumbs-up'></i> <span id='num_like-{{$item->id}}'>{{$item->num_like}}</span></span>
                                        <span class='@auth('user') box-dislike @endauth @if(isset($item->like_type) && $item->like_type == 0) active @endif' data-id='{{$item->id}}'><i class='fas fa-thumbs-down'></i> <span id='num_dislike-{{$item->id}}'>{{$item->num_dislike}}</span></span>
                                        <span class='box-share'><a href='https://www.facebook.com/sharer/sharer.php?u={{route('home.focus.detail', [$item->group_url, $item->news_url])}}' target='_blank'><i class='fas fa-share'></i> chia sẻ</a></span>
                                        <span class='posts-more dropdown'>
                                            <i class='fas fa-ellipsis-h' data-toggle='dropdown' aria-expanded='false'></i>
                                            <div class='dropdown-menu dropdown-menu-right'>
                                                <a href='#' class='dropdown-item'>
                                                    Báo cáo
                                                </a>
                                            </div>
                                        </span>
                                    </div>
                                    <div class='box-desc'>
                                        {{$item->news_content}}
                                </div>
                                <div class='box-author'>
                                    <span>{{$item->admin_fullname}}</span>
                                    </div>">
                                @if($item->video_url)
                                <div class="btn-play"><i class="fas fa-play-circle"></i></div>
                                @endif
                            </a>
                        </div>
                        <div class="content">
                            <h3 class="title"><a href="{{$item->video_url}}" class="poppup-video html5lightbox-{{$item->id}}" data-id="{{$item->id}}" data-titlestyle="right" data-description="<h3 class='title-box'>Tiêu đề: {{$item->news_title}}</h3>
                                    <span class='box-meta'>
                                        <span class='box-view'>{{$item->num_view}} lượt xem </span>
                                        <span class='box-time'>• {{date('d/m/Y', $item->created_at)}}</span>
                                    </span>
                                    <div class='box-function'>
                                        <span class='@auth('user') box-like @endauth @if(isset($item->like_type) && $item->like_type == 1) active @endif' data-id='{{$item->id}}'><i class='fas fa-thumbs-up'></i> <span id='num_like-{{$item->id}}'>{{$item->num_like}}</span></span>
                                        <span class='@auth('user') box-dislike @endauth @if(isset($item->like_type) && $item->like_type == 0) active @endif' data-id='{{$item->id}}'><i class='fas fa-thumbs-down'></i> <span id='num_dislike-{{$item->id}}'>{{$item->num_dislike}}</span></span>
                                        <span class='box-share'><a href='https://www.facebook.com/sharer/sharer.php?u={{route('home.focus.detail', [$item->group_url, $item->news_url])}}' target='_blank'><i class='fas fa-share'></i> chia sẻ</a></span>
                                        <span class='posts-more dropdown'>
                                            <i class='fas fa-ellipsis-h' data-toggle='dropdown' aria-expanded='false'></i>
                                            <div class='dropdown-menu dropdown-menu-right'>
                                                <a href='#' class='dropdown-item'>
                                                    Báo cáo
                                                </a>
                                            </div>
                                        </span>
                                    </div>
                                    <div class='box-desc'>
                                        {{$item->news_content}}
                                </div>
                                <div class='box-author'>
                                    <span>{{$item->admin_fullname}}</span>
                                    </div>">{{$item->news_title}}</a></h3>
                            <p class="desc">{{$item->news_description}}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-md-6">
                    <div class="focus-left">
                        <div class="thumbnail">
                            <img src="{{asset($top_1->image_url)}}" alt="" style="height: 385px; object-fit: cover">
                            <a href="{{$top_1->video_url}}" class="poppup-video html5lightbox-{{$top_1->id}}" data-id="{{$top_1->id}}" data-titlestyle="right" data-description="<h3 class='title-box'>Tiêu đề: {{$top_1->news_title}}</h3>
                                    <span class='box-meta'>
                                        <span class='box-view'>{{$top_1->num_view}} lượt xem </span>
                                        <span class='box-time'>• {{date('d/m/Y', $top_1->created_at)}}</span>
                                    </span>
                                    <div class='box-function'>
                                        <span class='@auth('user') box-like @endauth @if(isset($item->like_type) && $item->like_type == 1) active @endif' data-id='{{$top_1->id}}'><i class='fas fa-thumbs-up'></i> <span id='num_like-{{$top_1->id}}'>{{$top_1->num_like}}</span></span>
                                        <span class='@auth('user') box-dislike @endauth @if(isset($item->like_type) && $item->like_type == 0) active @endif' data-id='{{$top_1->id}}'><i class='fas fa-thumbs-down'></i> <span id='num_dislike-{{$top_1->id}}'>{{$top_1->num_dislike}}</span></span>
                                        <span class='box-share'><a href='https://www.facebook.com/sharer/sharer.php?u={{route('home.focus.detail', [$top_1->group_url, $top_1->news_url])}}' target='_blank'><i class='fas fa-share'></i> chia sẻ</a></span>
                                        <span class='posts-more dropdown'>
                                            <i class='fas fa-ellipsis-h' data-toggle='dropdown' aria-expanded='false'></i>
                                            <div class='dropdown-menu dropdown-menu-right'>
                                                <a href='#' class='dropdown-item'>
                                                    Báo cáo
                                                </a>
                                            </div>
                                        </span>
                                    </div>
                                    <div class='box-desc'>
                                        {{$top_1->news_content}}
                                    </div>
                                    <div class='box-author'>
                                        <span>{{$top_1->admin_fullname}}</span>
                                    </div>">
                                @if($top_1->video_url)
                                <div class="btn-play"><i class="fas fa-play-circle"></i></div>
                                @endif

                            </a>
                        </div>
                        <div class="content">
                            <h3 class="title"><a href="{{route('home.focus.detail', [$top_1->group_url, $top_1->news_url])}}">{{$top_1->news_title}}</a></h3>
                            <p class="desc">{{$top_1->news_description}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 pr-0">
                <div class="focus-right">
                    @foreach($list as $item)
                        <div class="focus-item">
                            <div class="thumbnail">
                                <img src="{{asset($item->image_url)}}" alt="" style="height: 90px; object-fit: cover">
                                <a href="{{$item->video_url}}" class="poppup-video html5lightbox-{{$item->id}}" data-id="{{$item->id}}" data-titlestyle="right" data-description="<h3 class='title-box'>Tiêu đề: {{$item->news_title}}</h3>
                                    <span class='box-meta'>
                                        <span class='box-view'>{{$item->num_view}} lượt xem </span>
                                        <span class='box-time'>• {{date('d/m/Y', $item->created_at)}}</span>
                                    </span>
                                    <div class='box-function'>
                                        <span class='@auth('user') box-like @endauth @if(isset($item->like_type) && $item->like_type == 1) active @endif' data-id='{{$item->id}}'><i class='fas fa-thumbs-up'></i> <span id='num_like-{{$item->id}}'>{{$item->num_like}}</span></span>
                                        <span class='@auth('user') box-dislike @endauth @if(isset($item->like_type) && $item->like_type == 1) active @endif' data-id='{{$item->id}}'><i class='fas fa-thumbs-down'></i> <span id='num_dislike-{{$item->id}}'>{{$item->num_dislike}}</span></span>
                                        <span class='box-share'><a href='https://www.facebook.com/sharer/sharer.php?u={{route('home.focus.detail', [$item->group_url, $item->news_url])}}' target='_blank'><i class='fas fa-share'></i> chia sẻ</a></span>
                                        <span class='posts-more dropdown'>
                                            <i class='fas fa-ellipsis-h' data-toggle='dropdown' aria-expanded='false'></i>
                                            <div class='dropdown-menu dropdown-menu-right'>
                                                <a href='#' class='dropdown-item'>
                                                    Báo cáo
                                                </a>
                                            </div>
                                        </span>
                                    </div>
                                    <div class='box-desc'>
                                        {{$item->news_content}}
                                    </div>
                                    <div class='box-author'>
                                        <span>{{$item->admin_fullname}}</span>
                                    </div>">

                                    @if($item->video_url)
                                    <div class="btn-play"><i class="fas fa-play-circle"></i></div>
                                    @endif
                                </a>
                            </div>
                            <div class="content">
                                <h3 class="title"><a href="{{route('home.focus.detail', [$item->group_url, $item->news_url])}}">{{$item->news_title}}</a></h3>
                                <p class="desc">{{$item->news_description}}</p>
                                <span class="post-time pt-1"><i class="far fa-clock"></i> {{getHumanTimeWithPeriod($item->created_at)}}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</section>

@push('scripts_children')
    <script>
        $(function () {
            $("body").on(
                "click",
                ".box-function .box-like",
                function() {
                    let id = $(this).data('id');
                    $.ajax({
                        url: "{{route('home.focus.ajax_toggle_like','')}}/" + id,
                        method: "POST",
                        data: {
                            is_like: $(this).hasClass('active'),
                            _token: "{{csrf_token()}}",
                        },
                        success: function (data) {
                            data = JSON.parse(JSON.stringify(data))
                            if(data.status == 'success'){
                                $("#num_like-" + id ).html(data.total?.num_like)
                                $("#num_dislike-" + id ).html(data.total?.num_dislike)
                            }
                        }
                    })
                }
            );
            $("body").on(
                "click",
                ".box-function .box-dislike",
                function() {
                    let is_dislike = $(this).hasClass('active');
                    let id = $(this).data('id');
                    $.ajax({
                        url: "{{route('home.focus.ajax_toggle_dislike','')}}/" + id,
                        method: "POST",
                        data: {
                            is_dislike,
                            _token: "{{csrf_token()}}",
                        },
                        success: function (data) {
                            data = JSON.parse(JSON.stringify(data))
                            if(data.status == 'success'){
                                $("#num_like-" + id ).html(data.total?.num_like)
                                $("#num_dislike-" + id ).html(data.total?.num_dislike)
                            }
                        }
                    })
                }
            );
            // init html5 high light
            $('.poppup-video').each(function (e) {
                $(".html5lightbox-" + $(this).data('id')).html5lightbox();
            })
        })

    </script>
@endpush

@else
    <div class="pb-4"></div>
@endif
