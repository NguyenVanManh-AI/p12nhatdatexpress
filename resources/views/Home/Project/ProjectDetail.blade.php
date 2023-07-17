@extends('Home.Layouts.Master')
@section('Title', $project->meta_title)
@section('Description', $project->meta_desc)
@section('Image', asset(json_decode($project->image_url)[0] ?? null))
@section('Style')
    <style>
        .overlay2 {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 5%;
            z-index: 99999;
            opacity: 0.7;
            background: rgba(255,255,255,0.56) url('/frontend/images/loading.gif') no-repeat center center;
        }
        .table-information .node{
            flex: 0 0 94px !important;
        }
        .chart-container {
            width: 170px;
            height: 75px;
        }
        .chart-container .total{
            top: 100%;
        }
        .table-information .name{
            text-align: left;
        }
        input[name="rating"]{
            display: none;
        }
    </style>
@endsection
@section('Content')
    @php $project_id = $project->id; @endphp
    <div id="project-single" class="project-page project-detail-page">
        <div class="container p-0">
            <div class="after-banner-fixed w-100" id="urban-fixed-filter">
                <x-home.project.target-banner
                    class="container"
                />
            </div>

            <div class="row">
                <div class="col-md-7-3 single-main">
                    <div class="project-main-content" >
                        @if($project->image_url)
                            <div class="owl-carousel owl-custom-theme owl-dot-orange owl-hover-nav owl-nav-rounded owl-drag detail-banner__carousel w-100">
                                @foreach(json_decode($project->image_url) as $image)
                                    <div class="image-ratio-box relative">
                                        <a href="{{ asset($image) }}" class="absolute-full" data-fancybox="gallery">
                                            <img class="object-cover" src="{{ asset($image) }}" alt="">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div class="after-banner" id="urban-filter">
                            <x-home.project.target-banner />
                        </div>
                        <div class="px-5 pt-4">
                            <h2 class="single-title" id="tongquan"> {{$project->project_name}}</h2>
                            <div class="single-meta">
                                <div class="left flex-1">
                                    <div class="price bold text-danger prepend-image-icon-price">
                                        {{ $project->getPriceWithUnit() }}
                                    </div>
                                    <div class="area bold text-danger prepend-image-icon-area">
                                        {{ $project->getAreaLabel() }}
                                    </div>
                                    <div class="location bold text-danger prepend-image-icon-address">
                                        <div class="text-left">{{$project->district_name}}, {{$project->province_name}}</div>
                                    </div>
                                </div>
                                <div class="right">
                                    {{-- @if(!$is_preview) --}}
                                    <a target="__blank" href="{{share_fb(url()->current())}}" class="share-button">
                                        <i class="fas fa-share-alt mr-2"></i>Chia sẻ
                                        {{--                                    <div class="share-sub" style="display: none;">--}}
                                        {{--                                        <a class="item">--}}
                                        {{--                                            <i class="fab fa-facebook-square"></i>--}}
                                        {{--                                            Facebook--}}
                                        {{--                                        </a>--}}
                                        {{--                                        <a class="item">--}}
                                        {{--                                            <i class="fab fa-twitter-square"></i>--}}
                                        {{--                                            Twitter--}}
                                        {{--                                        </a>--}}
                                        {{--                                        <a class="item">--}}
                                        {{--                                            <i class="fab fa-pinterest-square"></i>--}}
                                        {{--                                            Pinterest--}}
                                        {{--                                        </a>--}}
                                        {{--                                        <a class="item">--}}
                                        {{--                                            <i class="zalo-icon"></i>--}}
                                        {{--                                            Zalo--}}
                                        {{--                                        </a>--}}
                                        {{--                                    </div>--}}
                                    </a>
                                    <span class="report-button report-content" data-id="{{$project->id}}">
                                <i class="fas fa-exclamation"></i>Báo cáo
                                </span>
                                    {{-- @endif --}}
                                </div>
                            </div>
                            <div class="single-contact-buttons">
                                <a href="#" class="green">
                                    <i class="fas fa-phone-alt"></i>
                                    Liên hệ
                                </a>
                                <a href="#" class="blue">
                                    <i class="fas fa-user"></i>
                                    Đăng ký thông tin
                                </a>
                            </div>
                            <div class="project-detail">
                                <div class="segment mb-4" id="tongquan">
                                    {{-- <div class="segment-head">
                                        <div class="flex-start bg-white">
                                            <div class="project-page__description-icon bg-white text-blue pr-1">
                                                @if($icon_project[0]->image)
                                                    <img class="object-contain" alt="{{$icon_project[0]->description}}" src="{{asset($icon_project[0]->image)}}"/>
                                                @else
                                                    <i class="fas fa-chart-bar"></i>
                                                @endif
                                            </div>
                                            <h4 class="project-page__description-title text-uppercase text-blue mb-0">
                                                <span>Tổng quan</span>
                                            </h4>
                                        </div>
                                    </div> --}}
                                    <div class="text-break">
                                        {!! $project->project_content !!}
                                    </div>
                                </div>

                                <div class="js-need-toggle-active js-toggle-area">
                                    <div class="js-toggle-area-slide" style="display: none">
                                        <?php
                                            $hasMoreDes = false;
                                            $sectionDescriptions = [
                                                [
                                                    'field' => 'location_descritpion',
                                                    'icon_index' => 1,
                                                    'preventive_icon' => '<i class="fas fa-chart-bar"></i>',
                                                    'id' => 'vitri',
                                                    'label' => 'Vị trí'
                                                ],
                                                [
                                                    'field' => 'utility_description',
                                                    'icon_index' => 2,
                                                    'preventive_icon' => '<i class="fas fa-project-diagram"></i>',
                                                    'id' => 'tienich',
                                                    'label' => 'Tiện ích'
                                                ],
                                                [
                                                    'field' => 'ground_description',
                                                    'icon_index' => 3,
                                                    'preventive_icon' => '<i class="fas fa-vector-square"></i>',
                                                    'id' => 'matbang',
                                                    'label' => 'Mặt bằng'
                                                ],
                                                [
                                                    'field' => 'legal_description',
                                                    'icon_index' => 4,
                                                    'preventive_icon' => '<i class="fas fa-balance-scale"></i>',
                                                    'id' => 'phaply',
                                                    'label' => 'Pháp lý'
                                                ],
                                                [
                                                    'field' => 'payment_description',
                                                    'icon_index' => 5,
                                                    'preventive_icon' => '<i class="fas fa-coins"></i>',
                                                    'id' => 'thanhtoan',
                                                    'label' => 'Thanh toán'
                                                ],
                                            ];
                                        ?>

                                        @foreach($sectionDescriptions as $section)
                                            @if(data_get($project, data_get($section, 'field')))
                                                <?php $hasMoreDes = true ?>
                                                <div class="segment mb-4" id="{{ data_get($section, 'id') }}">
                                                    <div class="segment-head">
                                                        <div class="flex-start bg-white">
                                                            <div class="project-page__description-icon bg-white text-blue pr-1">
                                                                @if(data_get($icon_project, data_get($section, 'icon_index') . '.image'))
                                                                    <img class="object-contain" alt="{{ data_get($icon_project, data_get($section, 'icon_index') . '.description') }}" src="{{ asset(data_get($icon_project, data_get($section, 'icon_index') . '.image')) }}"/>
                                                                @else
                                                                    {!! data_get($section, 'preventive_icon') !!}
                                                                @endif
                                                            </div>
                                                            <h4 class="project-page__description-title text-uppercase text-blue mb-0">
                                                                <span>{{ data_get($section, 'label') }}</span>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                    <div class="text-break">
                                                        {!! data_get($project, data_get($section, 'field')) !!}
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    @if($hasMoreDes)
                                        <div class="text-center mb-3 js-toggle-area">
                                            <button class="btn btn-sm btn-warning js-toggle-active">
                                                <span class="active-hide">
                                                    Xem thêm
                                                    <i class="fas fa-chevron-down"></i>
                                                </span>
                                                <span class="active-show">
                                                    Thu gọn
                                                    <i class="fas fa-chevron-up"></i>
                                                </span>
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-end mb-3 align-items-center">
                                    <div class="mr-2">
                                        <span class="text-gray mr-1">
                                            <i class="fas fa-chart-bar"></i>
                                        </span>
                                        Lượt xem:
                                        <span class="text-light-cyan">{{ $project->num_view }}</span>
                                    </div>
                                    -
                                    <div class="ml-2 text-gray">
                                        <i class="fas fa-file-alt mr-1"></i>
                                        {{ $project->author }}
                                    </div>
                                </div>

                                <div class="detail-inner classified-detail-table-inner mb-4">
                                    <div class="detail-title">Thông tin chi tiết</div>
                                  
                                    <div class="row detail-table m-0 border-left">
                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '0.name', 'Tên dự án') }}</span>
                                                <span class="name text-ellipsis js-content-title" data-title="{{ data_get($project, 'project_name', '---') }}">
                                                    {{ data_get($project, 'project_name', '---') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '0.name', 'Giá bán') }}</span>
                                                <span class="name text-danger bold text-ellipsis js-content-title flex-1">
                                                    {{ $project->getPriceWithUnit() }}
                                                </span>
                                                <a 
                                                    href="#"
                                                    class="update update-project small text-gray" data-action="1"
                                                    data-group-id="{{$project->group_id}}"
                                                    data-href="{{route('home.project.update-project', $project->id)}}"
                                                    data-selected="{{ $project->project_progress_new ?? $project->project_progress }}"
                                                >
                                                    [Cập nhật]
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '2.name', 'Hướng nhà') }}</span>
                                                @if (data_get($project->direction, 'direction_name'))
                                                    <a class="name text-light-cyan"
                                                        href="{{ $project->group ? route('home.classified.list', ['group_url' => $project->group->getLastParentGroup(), 'direction' => $project->direction->id]) : 'javascript:void(0);' }}"
                                                    >
                                                        {{ data_get($project, 'direction.direction_name') }}
                                                    </a>
                                                @else
                                                    ---
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '3.name', 'Mô hình') }}</span>
                                                @if($project->group)
                                                    <a class="name text-danger text-ellipsis js-content-title"
                                                        href="{{ $project->group ? route('home.classified.list', [$project->group->getLastParentGroup(), $project->group->group_url]) : 'javascript:void(0);' }}"
                                                    >{{ data_get($project, 'group.group_name') }}</a>
                                                @else
                                                    ---
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '4.name', 'Giá thuê') }}</span>
                                                <span class="name text-light-cyan text-ellipsis js-content-title flex-1">
                                                    {{ $project->getRentPriceWithUnit() }}
                                                </span>
                                                <a 
                                                    href="#"
                                                    class="update update-project small text-gray" data-action="2"
                                                    data-group-id="{{$project->group_id}}"
                                                    data-href="{{route('home.project.update-project', $project->id)}}"
                                                    data-selected="{{$project->project_progress_new ?? $project->project_progress}}"
                                                >
                                                    [Cập nhật]
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '5.name', 'Quy mô') }}</span>
                                                <span class="name text-light-cyan">
                                                    {{ $project->project_scale ?: '---' }}
                                                    @if($project->project_scale)
                                                        {{ $project->unit_name }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '6.name', 'Chủ đầu tư') }}</span>
                                                <span class="name text-light-cyan">
                                                    {{ $project->investor ?: '---' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '7.name', 'Diện tích') }}</span>
                                                <span class="name text-light-cyan">
                                                    {{ $project->getAreaLabel() }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '8.name', 'Hỗ trợ vay') }}</span>
                                                <a class="name text-light-cyan"
                                                    href="{{ $project->group ? route('home.classified.list', ['group_url' => $project->group->getLastParentGroup(), 'bank_sponsor' => $project->bank_sponsor]) : 'javascript:void(0);' }}"
                                                >
                                                    {{ $project->bank_sponsor == 1 ? 'Có' : '---' }}
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '9.name', 'Vị trí') }}</span>
                                                <a class="name text-blue text-ellipsis js-content-title"
                                                    href="{{ $project->group ? route('home.classified.list', ['group_url' => $project->group->getLastParentGroup(), 'province_id' => data_get($project, 'location.province_id')]) : 'javascript:void(0);' }}">
                                                    {{ $project->getShortAddress() ?: '---' }}
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '10.name', 'Pháp lý') }}</span>
                                                <span class="name text-danger text-ellipsis">
                                                    {{ $project->param_name ?: '---' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '11.name', 'Đường') }}</span>
                                                <span class="name text-light-cyan">
                                                    {{ $project->project_road ? $project->project_road . 'm' : '---' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '12.name', 'Tình trạng') }}</span>
                                                <a class="name text-success text-ellipsis js-content-title flex-1"
                                                    href="{{ $project->group ? route('home.classified.list', ['group_url' => $project->group->getLastParentGroup(), 'group_child' => $project->group->group_url, 'progress' => $project->project_progress]) : 'javascript:void(0);' }}"
                                                >
                                                    {{ data_get($project, 'progress.progress_name', '---') }}
                                                </a>
                                                <span class="fs-14">
                                                    <a
                                                        href="#"
                                                        class="update update-project small text-gray" data-action="3"
                                                        data-group-id="{{$project->group_id}}"
                                                        data-href="{{route('home.project.update-project', $project->id)}}"
                                                        data-selected="{{$project->project_progress_new ?? $project->project_progress}}"
                                                    >
                                                        [Cập nhật]
                                                    </a>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '13.name', 'Nội thất') }}</span>
                                                <a class="name text-light-cyan text-ellipsis js-content-title"
                                                    href="{{ $project->group ? route('home.classified.list', ['group_url' => $project->group->getLastParentGroup(), 'group_child' => $project->group->group_url, 'furniture' => $project->project_furniture]) : 'javascript:void(0);' }}"
                                                >
                                                    {{ data_get($project->furniture, 'furniture_name', '---') }}
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
                                            <div class="py-1 d-flex">
                                                <span class="node">{{ data_get($properties, '14.name', 'Đăng ngày') }}</span>
                                                <span class="name text-success bold">
                                                    {{ $project->created_at ? date('d/m/Y', $project->created_at) : '---' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- should change to other global class insteal of detail-inner classified-detail-table-inner --}}
                                <div class="detail-inner classified-detail-table-inner project-page__utilities mb-4">
                                    <div class="detail-title">Hệ thống tiện ích</div>

                                    <div class="row">
                                        @forelse($utilities as $utility)
                                            <div class="col-md-2 col-sm-3 col-4 text-center">
                                                @if($utility->getImageUrl())
                                                    <div class="flex-center py-2">
                                                        <div class="project-page__utilities-icon">
                                                            <img src="{{ $utility->getImageUrl() }}" alt="">
                                                        </div>
                                                    </div>
                                                @endif

                                                <p class="mb-0 fs-12">{{ $utility->utility_name }}</p>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="p-2 mb-0 text-center">
                                                    Không có tiện ích
                                                </p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <x-home.project.calc-loan :project="$project"/>

                                <div class="single-tags">
                                    <div class="title">
                                        <i class="fas fa-tag"></i>Tìm theo từ khóa:
                                    </div>
                                    <div class="tags">
                                        @foreach($key_search as $keyword)
                                            @if($keyword['type'] == 0)
                                                <a class="text-lowercase link-light-cyan"
                                                   href="{{route('home.project.index')}}?province_id={{$project->location->district->province_id}}&district_id={{$project->location->district->id}}">
                                                   {{$keyword['title']}}
                                                   @if( $loop->index + 1 < count($key_search) )
                                                   , &nbsp;
                                                   @endif
                                                </a>
                                            @else
                                                <a class="text-lowercase link-light-cyan"
                                                   href="{{route('home.project.index')}}?province_id={{$project->location->district->province_id}}">
                                                   {{$keyword['title']}}
                                                   @if( $loop->index + 1 < count($key_search) )
                                                   , &nbsp;
                                                   @endif
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                {{--                        <x-project.project-detail-rating :message="$project->id"/>--}}
                                <div class="project-review">
                                    <div class="head">
                                        <i class="fas fa-user-circle"></i>
                                        Đánh giá
                                    </div>

                                    <div class="detail-review-result-box" data-url="/projects/rating/{{ $project->id }}">
                                        <x-common.detail.review-result
                                            :item="$project"
                                        />
                                    </div>
                                </div>

                                <div
                                    class="project-comment comment-area js-comment-section"
                                    data-url="projects"
                                    data-id="{{ $project->id }}"
                                >
                                    <x-common.detail.comment
                                        :comments="$comment"
                                        detail-type="projects"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-project.project-detail-vote :message="$project->id"/>
            </div>
            <div class="row">
                <div class="col-md-7-3 single-relate-wr">
                    <x-home.classified.relate
                        :project-id="$project_id"
                    />
                </div>
                <div class="col-md-3-7 single-near">
                    <x-project.recent-project :group="$project->group" />
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-home.classified.current/>
                </div>
            </div>
            <x-home.report-modal />
            <div class="overlay"></div>
            <x-project.popup-project/>
            <x-home.project.update-project/>
        </div>
    </div>
@endsection

@push('init_map')
    let initLocation = {
        lat: parseFloat($('.project-detail-page [name="latitude"]').val() || 0),
        lng: parseFloat($('.project-detail-page [name="longtitude"]').val() || 0),
    }

    initSimpleMap('project-detail-page__map', initLocation);
@endpush

@section('Script')
    <script>
        $('.report-content').click(function () {
            $('#report_content').show();
            $('#layout').show();
            $('#report_content').find('form').attr('action', '{{route('home.project.report-content','')}}' + '/' + $(this).data('id'));
        });
        $('body').on('click', '.button-report .report_comment', function () {
            $('#report_comment').show();
            $('#layout').show();
            $('#report_comment').find('form').attr('action', '{{route('home.project.report-comment','')}}' + '/' + $(this).data('comment_id'));
        });
    </script>
@endsection
