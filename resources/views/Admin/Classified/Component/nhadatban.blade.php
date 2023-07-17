<script src="js/classified/slick.js"></script>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        $(".property-banner-slide").slick({
            slidesToShow: 1,

            slidesToScroll: 1,

            dots: true,

            arrows: true,

            infinite: true,

            autoplay: true,

            autoplaySpeed: 3000,

            prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',

            nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
        });
    });
</script>

<script type="text/javascript">
    $('#tab-switcher-1').addClass('active');
    $('#tab-switcher-2').removeClass('active');
    $('#tab-switcher-3').removeClass('active');
    $('#tab-1').removeClass('d-none');
    $('#tab-2').addClass('d-none');
    $('#tab-3').addClass('d-none');
    $('#tab-switcher-1').click(function() {
        $('#tab-switcher-1').addClass('active');
        $('#tab-switcher-2').removeClass('active');
        $('#tab-switcher-3').removeClass('active');
        $('#tab-1').removeClass('d-none');
        $('#tab-2').addClass('d-none');
        $('#tab-3').addClass('d-none');
    })
    $('#tab-switcher-2').click(function() {
        $('#tab-switcher-1').removeClass('active');
        $('#tab-switcher-2').addClass('active');
        $('#tab-switcher-3').removeClass('active');
        $('#tab-1').addClass('d-none');
        $('#tab-2').removeClass('d-none');
        $('#tab-3').addClass('d-none');
    })
    $('#tab-switcher-3').click(function() {
        $('#tab-switcher-1').removeClass('active');
        $('#tab-switcher-2').removeClass('active');
        $('#tab-switcher-3').addClass('active');
        $('#tab-1').addClass('d-none');
        $('#tab-2').addClass('d-none');
        $('#tab-3').removeClass('d-none');
    })
</script>

<section>

    <div class=" single-main">

        <div class="property-main-content">
            {{-- @if(count($image_array)>0)--}}
            <div class="property-banner-slide  slick-slider slick-dotted">
                @foreach(json_decode($data['hinhanh']) as $item)
                <img src="{{asset($item)}}" style="width: 100%; display: inline-block;">
                @endforeach
            </div>
            {{-- @endif--}}
            <div class="px-2 pt-3">
                <h2 class="single-title">{{$data['title']}}</h2>
                <div class="single-meta">
                    <div class="left">
                        @if($data['group']== '2')
                        <div class="price">
                            <i class="fas fa-tags"></i> @if($data['giaban']!='')
                            {{$data['group_parent']== 'canmua'?"~":""}}{{$data['giaban']}} {{$data['donviban']}}
                            @else {{"Liên hệ"}} @endif
                        </div>
                        @endif
                        @if($data['group_parent']== 'nhadatchothue' || $data['group_parent']== 'canthue' )
                        <i class="fas fa-tags"></i> @if($data['giathue']!='')
                        {{$data['group_parent']== 'canthue'?"~":""}}{{$data['giathue']}} {{ $data['donvithue']}}
                        @else {{"Liên hệ"}} @endif
                        @endif
                        <div class="area">
                            <i class="fas fa-vector-square"></i>{{$data['classified_area']}} m2
                        </div>
                        <div class="location">
                            <i class="fas fa-map-marker-alt"></i>{{($data['huyen']!=-1)?$data['huyen']:""}}
                            , {{($data['tinh']!=-1)?$data['tinh']:""}}
                        </div>
                    </div>
                    <div class="right">
                        <span class="share-button">
                            <i class="fas fa-share-alt"></i>Chia sẻ
                            <div class="share-sub" style="display: none;">
                                <a class="item">
                                    <i class="fab fa-facebook-square"></i>
                                    Facebook
                                </a>
                                <a class="item">
                                    <i class="fab fa-twitter-square"></i>
                                    Twitter
                                </a>
                                <a class="item">
                                    <i class="fab fa-pinterest-square"></i>
                                    Pinterest
                                </a>
                                <a class="item">
                                    <i class="zalo-icon"></i>
                                    Zalo
                                </a>
                            </div>
                        </span>
                        <span class="report-button">
                            <i class="fas fa-exclamation"></i>Báo cáo
                        </span>
                        <span class="view-count">
                            <i class="fas fa-chart-bar"></i>Lượt xem: <span>300</span>
                        </span>
                    </div>
                </div>
                <div class="detail">
                    <p>{!! $data['content'] !!}</p>
                    <div class="table-wrapper">
                        {{-- <div class="table-information table">--}}
                        {{-- <div class="table-head">Thông tin chi tiết</div>--}}
                        {{-- <div class="item cl-1 row-1">--}}
                        {{-- <span class="node">{{$properties[0]->name}}</span>--}}
                        {{-- <span class="name green">--}}
                        {{-- @if(isset($data['is_monopoly']))--}}
                        {{-- <i class="fas fa-check"></i>--}}
                        {{-- @else--}}
                        {{-- {{"---"}}--}}
                        {{-- @endif--}}
                        {{-- </span>--}}
                        {{-- </div>--}}
                        {{-- <div class="item cl-1 row-2">--}}
                        {{-- @if($data['group_parent']=='nhadatban' || $data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[1]->name}}</span>--}}
                        {{-- <span class="name green">@if(isset($data['is_broker']))--}}
                        {{-- <i class="fas fa-check"></i>--}}
                        {{-- @else--}}
                        {{-- {{"---"}}--}}
                        {{-- @endif--}}
                        {{-- </span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='canmua' || $data['group_parent']=='canthue')--}}
                        {{-- <span class="node">{{$properties[2]->name}}</span>--}}
                        {{-- <span class="name red">@if($data['mohinh']==-1)--}}
                        {{-- {{"---"}}--}}
                        {{-- @else--}}
                        {{-- {{$data['mohinh']}}--}}
                        {{-- @endif</span>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- <div class="item cl-1 row-3">--}}
                        {{-- @if($data['group_parent']=='nhadatban')--}}
                        {{-- <span class="node">{{$properties[3]->name}}</span>--}}
                        {{-- <span class="name blue">@if($data['duan']==-1)--}}
                        {{-- {{"---"}}--}}
                        {{-- @else--}}
                        {{-- {{$data['duan']}}--}}
                        {{-- @endif</span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='nhadatchothue' ||$data['group_parent']=='canmua' ||$data['group_parent']=='canthue' )--}}
                        {{-- <span class="node">{{$properties[4]->name}}</span>--}}
                        {{-- <span--}}
                        {{-- class="name">{{($data['huyen']!=-1)?$data['huyen']:""}}, {{($data['tinh']!=-1)?$data['tinh']:""}}</span>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- <div class="item cl-1 row-4">--}}
                        {{-- @if($data['group_parent']=='nhadatban')--}}
                        {{-- <span class="node">{{$properties[2]->name}}</span>--}}
                        {{-- <span class="name red">@if($data['mohinh']==-1)--}}
                        {{-- {{"---"}}--}}
                        {{-- @else--}}
                        {{-- {{$data['mohinh']}}--}}
                        {{-- @endif</span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[5]->name}}</span>--}}
                        {{-- <span class="name">{{$data['tinhtrang']}}</span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='canmua' || $data['group_parent']=='canthue')--}}
                        {{-- <span class="node">{{$properties[16]->name}}</span>--}}
                        {{-- <span class="name blue">{{$data['huong']}}</span>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- @if($data['group_parent']=='nhadatban' || $data['group_parent']=='nhadatchothue')--}}
                        {{-- <div class="item cl-1 row-5">--}}
                        {{-- @if($data['group_parent']=='nhadatban')--}}
                        {{-- <span class="node">{{$properties[4]->name}}</span>--}}
                        {{-- <span--}}
                        {{-- class="name">{{($data['huyen']!=-1)?$data['huyen']:""}}, {{($data['tinh']!=-1)?$data['tinh']:""}}</span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[6]->name}}</span>--}}
                        {{-- <span--}}
                        {{-- class="name">{{isset($data['noithat'])?$data['nguoitoida']:"Không khả dụng"}}</span>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- @endif--}}
                        {{-- <div class="item cl-2 row-1">--}}
                        {{-- @if($data['group_parent']=='nhadatban')--}}
                        {{-- <span class="node">{{$properties[7]->name}}</span>--}}
                        {{-- <span class="name red"><strong>--}}
                        {{-- @if($data['giaban']!='')--}}
                        {{-- {{$data['giaban']}} {{$data['donviban']}}--}}
                        {{-- @else {{"Liên hệ"}} @endif--}}
                        {{-- </strong></span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[8]->name}}</span>--}}
                        {{-- <span class="name red"><strong>--}}
                        {{-- @if($data['giathue']!='')--}}
                        {{-- {{$data['giathue']}} {{ $data['donvithue']}}--}}
                        {{-- @else {{"Liên hệ"}} @endif--}}
                        {{-- </strong></span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='canmua')--}}
                        {{-- <span class="node">{{$properties[9]->name}}</span>--}}
                        {{-- <span class="name red"><strong>--}}
                        {{-- @if($data['giaban']!='')--}}
                        {{-- ~{{$data['giaban']}} {{$data['donviban']}}--}}
                        {{-- @else {{"Liên hệ"}} @endif--}}
                        {{-- </strong></span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='canthue')--}}
                        {{-- <span class="node">{{$properties[9]->name}}</span>--}}
                        {{-- <span class="name red"><strong>--}}
                        {{-- @if($data['giathue']!='')--}}
                        {{-- ~{{$data['giathue']}} {{$data['donvithue']}}--}}
                        {{-- @else {{"Liên hệ"}} @endif--}}
                        {{-- </strong></span>--}}
                        {{-- @endif--}}

                        {{-- </div>--}}
                        {{-- <div class="item cl-2 row-2">--}}
                        {{-- @if($data['group_parent']=='nhadatban')--}}
                        {{-- <span class="node">{{$properties[10]->name}}</span>--}}
                        {{-- <span class="name red"><strong>{{$data['classified_area']}} m2</strong></span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[11]->name}}</span>--}}
                        {{-- <span class="name"><strong>{{$data['coctruoc']}}</strong></span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='canmua' || $data['group_parent']=='canthue')--}}
                        {{-- <span class="node">{{$properties[10]->name}}</span>--}}
                        {{-- <span--}}
                        {{-- class="name red"><strong>~{{$data['classified_area']}} m2</strong></span>@endif--}}
                        {{-- </div>--}}
                        {{-- <div class="item cl-2 row-3">--}}
                        {{-- @if($data['group_parent']=='nhadatban' ||$data['group_parent']=='canmua' || $data['group_parent']=='canthue')--}}
                        {{-- <span class="node">{{$properties[12]->name}}</span>--}}
                        {{-- <span--}}
                        {{-- class="name">{{isset($data['noithat'])?$data['phongngu']:"Không khả dụng"}}</span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[10]->name}}</span>--}}
                        {{-- <span class="name red">{{$data['classified_area']}} m2</span>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- <div class="item cl-2 row-4">--}}
                        {{-- @if($data['group_parent']=='nhadatban')--}}
                        {{-- <span class="node">{{$properties[13]->name}}</span>--}}
                        {{-- <span--}}
                        {{-- class="name">{{isset($data['noithat'])?$data['phongvesinh']:"Không khả dụng"}}</span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[12]->name}}</span>--}}
                        {{-- <span--}}
                        {{-- class="name">{{isset($data['noithat'])?$data['phongngu']:"Không khả dụng"}}</span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='canmua' || $data['group_parent']=='canthue')--}}
                        {{-- <span class="node">{{$properties[15]->name}}</span>--}}
                        {{-- <span class="name yellow">{{isset($data['is_freezer'])?"Yêu cầu":"---"}}</span>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- @if($data['group_parent']=='nhadatban' || $data['group_parent']=='nhadatchothue')--}}
                        {{-- <div class="item cl-2 row-5">--}}
                        {{-- @if($data['group_parent']=='nhadatban')--}}
                        {{-- <span class="node">{{$properties[5]->name}}</span>--}}
                        {{-- <span class="name">{{$data['tinhtrang']}}</span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[14]->name}}</span>--}}
                        {{-- <span class="name">{{isset($data['is_freezer'])?"Có":"Không"}}</span>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- @endif--}}
                        {{-- <div class="item cl-3 row-1">--}}
                        {{-- @if($data['group_parent']=='nhadatban' || $data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[16]->name}}</span>--}}
                        {{-- <span class="name blue">{{$data['huong']}}</span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='canmua' || $data['group_parent']=='canthue')--}}
                        {{-- <span class="node">{{$properties[22]->name}}</span>--}}
                        {{-- <span class="name">{{isset($data['is_mezzanino'])?"Yêu cầu":"---"}}</span>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- <div class="item cl-3 row-2">--}}
                        {{-- @if($data['group_parent']=='nhadatban')--}}
                        {{-- <span class="node">{{$properties[17]->name}}</span>--}}
                        {{-- <span class="name">{{$data['phaply']}}</span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[19]->name}}</span>--}}
                        {{-- <span class="name">{{isset($data['is_internet'])?"Có":"Không"}}</span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='canmua' || $data['group_parent']=='canthue')--}}
                        {{-- <span class="node">{{$properties[19]->name}}</span>--}}
                        {{-- <span class="name yellow">{{isset($data['is_internet'])?"Yêu cầu":"---"}}</span>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- <div class="item cl-3 row-3">--}}
                        {{-- @if($data['group_parent']=='nhadatban')--}}
                        {{-- <span class="node">{{$properties[18]->name}}</span>--}}
                        {{-- <span class="name">--}}
                        {{-- {{isset($data['noithat'])?$data['noithat']:"Không khả dụng"}}--}}
                        {{-- </span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[20]->name}}</span>--}}
                        {{-- <span class="name">{{isset($data['is_balcony'])?"Có":"Không"}}</span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='canmua' || $data['group_parent']=='canthue')--}}
                        {{-- <span class="node">{{$properties[20]->name}}</span>--}}
                        {{-- <span class="name">{{isset($data['is_balcony'])?"Yêu cầu":"----"}}</span>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- <div class="item cl-3 row-4">--}}
                        {{-- @if($data['group_parent']=='nhadatban' ||$data['group_parent']=='canmua' || $data['group_parent']=='canthue')--}}
                        {{-- <span class="node">{{$properties[23]->name}}</span>--}}
                        {{-- <span--}}
                        {{-- class="name green"><strong>{{$data['created_at']!=null?date('d/m/Y',$data['created_at']):""}}</strong></span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[22]->name}}</span>--}}
                        {{-- <span class="name">{{isset($data['is_mezzanino'])?"Có":"Không"}}</span>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- @if($data['group_parent']=='nhadatban' || $data['group_parent']=='nhadatchothue')--}}
                        {{-- <div class="item cl-3 row-5">--}}
                        {{-- @if($data['group_parent']=='nhadatban')--}}
                        {{-- <span class="node"></span>--}}
                        {{-- <span class="name"></span>--}}
                        {{-- @endif--}}
                        {{-- @if($data['group_parent']=='nhadatchothue')--}}
                        {{-- <span class="node">{{$properties[23]->name}}</span>--}}
                        {{-- <span--}}
                        {{-- class="name green"><strong>{{$data['created_at']!=null?date('d/m/Y',$data['created_at']):""}}</strong></span>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- @endif--}}
                        {{-- </div>--}}
                        {{-- @if($data['group_parent'] == '2')
                        @elseif($data['group_parent'] == '10')

                        @else
                        @endif --}}
                        <x-news.classified.item-table :item="$items" />
                    </div>
                    <div class="single-tabs">
                        <div class="tab-switchers">
                            <div class="switcher   active" id="tab-switcher-1" data-tabid="tab-1">Bản đồ</div>
                            @if($data['video_url']!="")
                            <div class="switcher " id="tab-switcher-2" data-tabid="tab-2">Video</div>
                            @endif
                        </div>
                        <div class="tabs">
                            <div class="tab1 d-none" id="tab-1">
                                <div class="map-box map-box-reponsive col-sm-12 col-md-12">
                                    <iframe src="https://www.google.com/maps?q={{$data['map_latitude']}},{{$data['map_longtitude']}}&hl=vi&z=16&amp;output=embed" width="100%" height="600px" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                </div>
                            </div>
                            @if($data['video_url']!="")
                            <div class="tab2 d-none" id="tab-2">
                                <iframe width="100%" height="450" src="{{$data['video_url']}}" title="YouTube video player" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>
<!-- jQuery -->

<style>
    /* Slider */
    .slick-slider {
        position: relative;

        display: block;
        box-sizing: border-box;

        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;

        -webkit-touch-callout: none;
        -khtml-user-select: none;
        -ms-touch-action: pan-y;
        touch-action: pan-y;
        -webkit-tap-highlight-color: transparent;
    }

    .slick-list {
        position: relative;

        display: block;
        overflow: hidden;

        margin: 0;
        padding: 0;
    }

    .slick-list:focus {
        outline: none;
    }

    .slick-list.dragging {
        cursor: pointer;
        cursor: hand;
    }

    .slick-slider .slick-track,
    .slick-slider .slick-list {
        -webkit-transform: translate3d(0, 0, 0);
        -moz-transform: translate3d(0, 0, 0);
        -ms-transform: translate3d(0, 0, 0);
        -o-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
    }

    .slick-track {
        position: relative;
        top: 0;
        left: 0;

        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .slick-track:before,
    .slick-track:after {
        display: table;

        content: '';
    }

    .slick-track:after {
        clear: both;
    }

    .slick-loading .slick-track {
        visibility: hidden;
    }

    .slick-slide {
        display: none;
        float: left;

        height: 100%;
        min-height: 1px;
    }

    [dir='rtl'] .slick-slide {
        float: right;
    }

    .slick-slide img {
        display: block;
    }

    .slick-slide.slick-loading img {
        display: none;
    }

    .slick-slide.dragging img {
        pointer-events: none;
    }

    .slick-initialized .slick-slide {
        display: block;
    }

    .slick-loading .slick-slide {
        visibility: hidden;
    }

    .slick-vertical .slick-slide {
        display: block;

        height: auto;

        border: 1px solid transparent;
    }

    .slick-arrow.slick-hidden {
        display: none;
    }

    .property-banner-slide,
    .buy-rent-banner-slide,
        {
        position: relative;
    }

    .project-banner-slide .slick-arrow,
    .property-banner-slide .slick-arrow,
    .buy-rent-banner-slide .slick-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #A7A7A7;
        color: #fff;
        font-size: 23px;
        line-height: 0.6;
        font-weight: 700;
        opacity: 0.8;
        border: none;
        border-radius: 50%;
        z-index: 3;
    }

    .project-banner-slide .slick-prev,
    .property-banner-slide .slick-prev,
    .buy-rent-banner-slide .slick-prev {
        left: 10px;
    }

    .project-banner-slide .slick-next,
    .property-banner-slide .slick-next,
    .buy-rent-banner-slide .slick-next {
        right: 10px;
    }

    .project-banner-slide .slick-slide,
    .project-banner-slide .slick-list,
    .property-banner-slide .slick-slide,
    .property-banner-slide .slick-list,
    .buy-rent-banner-slide .slick-slide,
    .buy-rent-banner-slide .slick-list {
        margin: 0 !important;
    }

    .property-banner-slide .slick-dots {
        display: flex;
        align-items: center;
    }

    .property-banner-slide .slick-dots li.slick-active {
        width: 12px;
        height: 12px;
        background-color: #FEA602;
    }

    .property-banner-slide .slick-dots li.slick-active {
        width: 12px;
        height: 12px;
        background-color: #FEA602;
    }

    .slick-dots li {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin: 0 5px;
        display: inline-block;
        background-color: #fff;
        cursor: pointer;
    }

    .property-banner-slide .slick-dots button {
        display: none;
    }

    .slick-dots {
        position: absolute;
        bottom: 15px;
        left: 50%;
        transform: translateX(-50%);
    }

    .single-title {
        text-transform: uppercase;
        color: #1F5496;
        font-size: 26px;
        margin-bottom: 20px;
        word-break: break-word;
        font-weight: 700;
        text-align: start;
    }

    .single-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .single-meta {
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid #dedede;
    }

    .single-meta .left,
    .single-meta .right {
        display: flex;
        align-items: center;
    }

    .single-meta .left>div {
        margin-right: 15px;
        color: #BE4242;
        font-weight: 700;
    }

    .single-meta .left i {
        margin-right: 5px;
        color: #9C9C9C;
    }

    .single-meta .share-button {
        padding: 5px 10px;
        color: #fff;
        background-color: #08B4E4;
        cursor: pointer;
        margin-right: 15px;
        position: relative;
    }

    .single-meta .right i {
        margin-right: 5px;
    }

    .single-meta .right .report-button {
        padding: 5px 10px;
        color: #fff;
        background-color: #FF352B;
        cursor: pointer;
        margin-right: 15px;
    }

    .single-meta .right .view-count i {
        color: #9C9C9C;
    }

    .single-meta .right i {
        margin-right: 5px;
    }

    .single-meta .right .view-count span {
        color: #08B4E4;
    }

    @media only screen and (max-width: 1650px) {
        .table-wrapper {
            overflow-x: scroll;
        }
    }

    .table .table-head {
        color: #E28500;
        font-weight: 700;
        padding: 5px 10px;
        background-color: #EBEBEB;
    }

    .table-information {
        display: grid;
        grid-template-columns: 1fr 0.8fr 0.8fr;
        grid-template-rows: repeat(6, 1fr);
        font-size: 15px;
    }

    .table-information .table-head {
        grid-row: 1/2;
        grid-column: 1/4;
    }

    .table-information .cl-1.row-1 {
        grid-row: 2/3;
        grid-column: 1/2;
    }

    .table-information .node {
        display: inline-block;
        flex: 0 0 100px;
    }

    .table-information .name.green {
        color: #228C36;
    }

    .table-information .name {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }


    .table-information .cl-1.row-2 {
        grid-row: 3/4;
        grid-column: 1/2;
    }


    .table-information .cl-1.row-3 {
        grid-row: 4/5;
        grid-column: 1/2;
    }

    .table-information .item {
        display: flex;
        padding: 0 10px;
        line-height: 2;
        color: #707070;
        border-top: 1px solid #E1E1E1;
        overflow: hidden;
    }

    .table-information .item:not(.cl-3) {
        border-right: 1px solid #E1E1E1;
    }

    .table-information .cl-1.row-4 {
        grid-row: 5/6;
        grid-column: 1/2;
    }

    .table-information .cl-1.row-5 {
        grid-row: 6/7;
        grid-column: 1/2;
    }

    .table-information .cl-2.row-1 {
        grid-row: 2/3;
        grid-column: 2/3;
    }

    .table-information .cl-2.row-2 {
        grid-row: 3/4;
        grid-column: 2/3;
    }

    .table-information .cl-2.row-3 {
        grid-row: 4/5;
        grid-column: 2/3;
    }

    .table-information .cl-2.row-4 {
        grid-row: 5/6;
        grid-column: 2/3;
    }

    .table-information .cl-2.row-5 {
        grid-row: 6/7;
        grid-column: 2/3;
    }

    .table-information .cl-3.row-1 {
        grid-row: 2/3;
        grid-column: 3/4;
    }

    .table-information .cl-3.row-2 {
        grid-row: 3/4;
        grid-column: 3/4;
    }

    .table-information .cl-3.row-3 {
        grid-row: 4/5;
        grid-column: 3/4;
    }

    .table-information .cl-3.row-4 {
        grid-row: 5/6;
        grid-column: 3/4;
    }

    .table-information .cl-3.row-5 {
        grid-row: 6/7;
        grid-column: 3/4;
    }

    .table-information .name.blue {
        color: #2277C5;
    }

    .table-information .name.red {
        color: #D60000;
    }

    .single-tabs .tab-switchers {
        display: flex;
        background-color: #EBEBEB;
        border: 1px solid #E7E7E7;
        margin-bottom: 15px;
    }

    .single-tabs .switcher {
        padding: 5px 20px;
        font-weight: 700;
        color: #19A0E2;
        cursor: pointer;
    }

    .single-tabs .switcher.active {
        color: #fff;
        background-color: #1F5496;
    }
</style>
