@extends('Home.Layouts.Master')
@section('Title',$focus->meta_title??$focus->news_title)
@section('Keywords',$focus->meta_key??$focus->news_title)
@section('Description',$focus->meta_desc??$focus->news_description)
@section('Image',asset($focus->image_header_url??'frontend/images/home/image_default_nhadat.jpg'))
@section('Content')
    <div class="page-single">
        <div class="single-top">
            <div>
                <div class="row">
                    <div class="col-md-7-3">
                        <div class="content-main">
                            <div class="post-single">
                                @if($focus->image_url)
                                <div class="image-ratio-box relative">
                                    <a href="{{ asset($focus->image_url) }}" class="absolute-full js-fancy-box">
                                        <img class="object-cover" src="{{ asset($focus->image_url) }}" alt="">
                                    </a>
                                </div>
                                @endif
                                <div class="px-2 pt-3">
                                <span class="text-uppercase text-gray fs-normal mt-0">{{$focus->group_name}}</span>
                                <h2 class="single-title mb-2">
                                    {{ $focus->news_title }}
                                </h2>

                                <div class="focus-detail__meta d-flex justify-content-between align-items-center flex-wrap negative-mr-2">
                                    <div class="flex-start flex-wrap">
                                        <span class="text-gray mb-2 mr-2">
                                            <i class="fas fa-clock"></i>
                                            {{ \App\Helpers\Helper::get_day_of_week($focus->created_at) }},
                                            {{ date('d/m/Y', $focus->created_at) }}
                                            {{ date('H:i', $focus->created_at) }}
                                        </span>
                                        <span class="text-gray mr-2 mb-2">
                                            <i class="fas fa-signal"></i> Lượt xem:
                                            <span class="text-cyan">{{ $focus->num_view ?? 0 }}</span>
                                        </span>
                                        <button type="button" class="btn btn-sm btn-cyan px-3 position-relative mr-2 mb-2 @auth('user') js-post-like @else js-open-login @endauth"
                                            data-id="{{ $focus->id }}">
                                            <x-common.loading class="inner small" />
                                            <i class="like-icon fa {{ $liked < 1 ? 'fa-thumbs-up' : 'fa-check' }}"></i>
                                            Like
                                        </button>

                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-danger px-3 mr-2 mb-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-share"></i> Share
                                            </button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{share_fb(route('home.focus.detail', [$focus->group_url , $focus->news_url]))}}" target="_blank">
                                                    <span class="fs-18 text-primary">
                                                        <i class="fab fa-facebook-square"></i>
                                                    </span>
                                                    Facebook
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    @if($focus->audio_url)
                                        <span class="mr-2 mb-2 focus-detail__meta-audio">
                                            <audio class="mw-100" controls>
                                                <source src="{{url('system/audio/news/'.$focus->audio_url)}}" id="src"/>
                                            </audio>
                                        </span>
                                    @endif
                                </div>

                                <div class="single-content">
                                    <p class="bold"><b>{{$focus->news_description}}</b></p>
                                    <x-home.focus.ads />
                                    {!! nl2br($focus->news_content) !!}
                                    <div style="clear: both"></div>
                                    <span class="single-author pt-4">
                                        <i class="fas fa-user"></i>
                                         Người đăng:
                                        <span class="author">{{$focus->admin_fullname}}</span>
                                    </span>
                                </div>
                                @if($focus->tag_list)
                                    <x-home.focus.relate :focus="$focus"/>
                                    <div class="post-tags">
                                        <i class="fas fa-tags"></i>
                                        @foreach(explode(',', $focus->tag_list) as $tag)
                                            <a href="javascript:void(0);" class="tags">#{{$tag}}</a>
                                        @endforeach
                                    </div>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3-7">
                        <div class="sidebar-right">
                            <x-home.focus.focus-day/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="single-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-7-3">
                        <div class="content-main">
                            <x-home.focus.same-group :groupId="$focus->group_id" :groupUrl="$focus->group_url"/>
                            <x-home.focus.new-focus/>
                        </div>
                    </div>
                    <div class="col-md-3-7">
                        <div class="sidebar-right">
                            <x-home.event/>
                            <x-home.focus.most-viewed/>
                            {{-- <x-home.fanpage/> --}}
                            <x-home.exchange/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
