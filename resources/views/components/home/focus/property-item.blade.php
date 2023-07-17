<div class="card express-new-item" >
    <div class="express-new-item__image-box relative">
        <a href="{{$property->video_url}}" class="express-new-item__image absolute-full html5lightbox-{{$property->id}}" data-id="{{$property->id}}" data-titlestyle="right" data-description="<h3 class='title-box'>Tiêu đề: {{$property->news_title}}</h3>
            <span class='box-meta'>
                <span class='box-view'>{{$property->num_view}} lượt xem </span>
                <span class='box-time'>• {{date('d/m/Y', $property->created_at)}}</span>
            </span>
            <div class='box-function'>
                <span class='@auth('user') box-like @endauth @if(isset($property->like_type) && $property->like_type == 1) active @endif' data-id='{{$property->id}}'><i class='fas fa-thumbs-up'></i> <span id='num_like-{{$property->id}}'>{{$property->num_like}}</span></span>
                <span class='@auth('user') box-dislike @endauth @if(isset($property->like_type) && $property->like_type == 0) active @endif' data-id='{{$property->id}}'><i class='fas fa-thumbs-down'></i> <span id='num_dislike-{{$property->id}}'>{{$property->num_dislike}}</span></span>
                <span class='box-share'><a href='https://www.facebook.com/sharer/sharer.php?u={{route('home.focus.detail', [$group->group_url, $property->news_url])}}' target='_blank'><i class='fas fa-share'></i> chia sẻ</a></span>
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
                {{$property->news_content}}
            </div>
            <div class='box-author'>
            <span>{{data_get($property->adminCreatedBy, 'admin_fullname')}}</span>
            </div>">    
            <img class="object-cover" src="{{ asset($property->image_url) }}" alt="">
        </a>
        <a href="{{$property->video_url}}" class="express-new-item__play-button poppup-video html5lightbox-{{$property->id}}" data-id="{{$property->id}}" data-titlestyle="right" data-description="<h3 class='title-box'>Tiêu đề: {{$property->news_title}}</h3>
            <span class='box-meta'>
                <span class='box-view'>{{$property->num_view}} lượt xem </span>
                <span class='box-time'>• {{date('d/m/Y', $property->created_at)}}</span>
            </span>
            <div class='box-function'>
                <span class='@auth('user') box-like @endauth @if(isset($property->like_type) && $property->like_type == 1) active @endif' data-id='{{$property->id}}'><i class='fas fa-thumbs-up'></i> <span id='num_like-{{$property->id}}'>{{$property->num_like}}</span></span>
                <span class='@auth('user') box-dislike @endauth @if(isset($property->like_type) && $property->like_type == 0) active @endif' data-id='{{$property->id}}'><i class='fas fa-thumbs-down'></i> <span id='num_dislike-{{$property->id}}'>{{$property->num_dislike}}</span></span>
                <span class='box-share'><a href='https://www.facebook.com/sharer/sharer.php?u={{route('home.focus.detail', [$group->group_url, $property->news_url])}}' target='_blank'><i class='fas fa-share'></i> chia sẻ</a></span>
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
                {{$property->news_content}}
            </div>
            <div class='box-author'>
            <span>{{data_get($property->adminCreatedBy, 'admin_fullname')}}</span>
            </div>">        
        <div class="btn-play"><i class="fas fa-play-circle" style="font-size: 50px"></i></div>     
        </a>
    </div>
    <div class="card-body">
        <h4 class="title text-ellipsis ellipsis-2 text-break fs-14">
        <a href="{{$property->video_url}}" class="html5lightbox-{{$property->id}}" data-id="{{$property->id}}" data-titlestyle="right" data-description="<h3 class='title-box'>Tiêu đề: {{$property->news_title}}</h3>
            <span class='box-meta'>
                <span class='box-view'>{{$property->num_view}} lượt xem </span>
                <span class='box-time'>• {{date('d/m/Y', $property->created_at)}}</span>
            </span>
            <div class='box-function'>
                <span class='@auth('user') box-like @endauth @if(isset($property->like_type) && $property->like_type == 1) active @endif' data-id='{{$property->id}}'><i class='fas fa-thumbs-up'></i> <span id='num_like-{{$property->id}}'>{{$property->num_like}}</span></span>
                <span class='@auth('user') box-dislike @endauth @if(isset($property->like_type) && $property->like_type == 0) active @endif' data-id='{{$property->id}}'><i class='fas fa-thumbs-down'></i> <span id='num_dislike-{{$property->id}}'>{{$property->num_dislike}}</span></span>
                <span class='box-share'><a href='https://www.facebook.com/sharer/sharer.php?u={{route('home.focus.detail', [$group->group_url, $property->news_url])}}' target='_blank'><i class='fas fa-share'></i> chia sẻ</a></span>
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
                {{$property->news_content}}
            </div>
            <div class='box-author'>
            <span>{{data_get($property->adminCreatedBy, 'admin_fullname')}}</span>
            </div>">
                {{ $property->news_title }}
            </a>
        </h4>
        {{-- <span class="post-time"><i class="far fa-clock"></i> {{get_time($property->created_at)}} </span> --}}
        {{-- <div class="desc">{{$property->news_description}}</div> --}}
    </div>
  </div>