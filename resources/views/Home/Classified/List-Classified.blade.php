@extends('Home.Layouts.Master')

@section('Title', $group->meta_title)
@section('Keywords', $group->meta_key)
@section('Description', $group->meta_desc)
@section('Image', $group->getSEOBanner())

@section('Content')
    <div id="page-property" class="list-search-category-box js-parents-loadmore pt-2">
        <div class="row">
            <div class="col-md-12 banner-mobile">
                <div class="banner">
                    <h2>{{$group->group_name}}</h2>
                    <div class="legend">Giải pháp tiếp cận hơn <span>10,000+</span> khách hàng mỗi ngày</div>
                </div>
                <div class="search">
                    <div class="head" class="">Tìm kiếm Express</div>
                    <div class="search-list">
                        <div class="item" style="background-color: #128CBB">&lt;1 tỷ</div>
                        <div class="item" style="background-color: #0076A3">1 - 3 tỷ</div>
                        <div class="item" style="background-color: #004A80">3 - 5 tỷ</div>
                        <div class="item" style="background-color: #003471">5 - 7 tỷ</div>
                        <div class="item-full">
                            <img src="{{asset('frontend/images/compass.png')}}" alt=""> tìm bất động sản gần đây
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 search-banner list-classified-search-box d-inline-block">
                <div class="row">
                    <div class="search-tool search-tool-width col-md-3-7 mb-3">
                        <x-home.classified.search.category-form />
                    </div>
                    <div class="banner-slide-width col-md-7-3 mb-3 md-hide">
                        <div class="search-image" style="background-image: url({{ asset($group->image_banner??'/frontend/images/banner-duan.jpg') }})"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="category-search-results-section">
            <x-news.classified.search-results
                :group="$group"
                :classifieds="$classifieds"
            />
        </div>
    </div>
@endsection
@section('Script')
    <script>
        $(document).ready(function (){
            // $('.search-tool-form-advance').hide();
            // if('{{str_contains(request()->path(),'nha-dat-ban')}}'){
            //     $('.switch-sell').addClass('active');
            //     $('.switch-sell').parents('.category-switcher').siblings('.property-type').addClass('active');
            // } else if('{{str_contains(request()->path(),'nha-dat-cho-thue')}}'){
            //     $('.switch-rent').addClass('active');
            //     $('.switch-sell').parents('.category-switcher').siblings('.rent-type').addClass('active');

            // }else if('{{str_contains(request()->path(),'du-an')}}'){
            //     $('.switch-project').addClass('active');
            //     $('.switch-sell').parents('.category-switcher').siblings('.project-type').addClass('active');
            // } else if('{{str_contains(request()->path(),'can-mua-can-thue/can-thue')}}'){
            //     $('.switch-buyrent').addClass('active');
            //     $('.switch-buyrent').parents('.category-switcher').siblings('.buyrent-type').addClass('active');
            // }
            // else {
            //     $('.switch-buysell').addClass('active');
            //     $('.switch-buysell').parents('.category-switcher').siblings('.buysell-type').addClass('active');
            // }
            // // set default value
            // // classified sell
            // @if(request()->has('district_id'))
            // get_district('#province_sell', '{{route('param.get_district')}}', '#district_sell', null, '{{$_GET['district_id']??-1}}')
            // get_district('#province_rent', '{{route('param.get_district')}}', '#district_rent', null, '{{$_GET['district_id']??-1}}')
            // get_district('#province_project', '{{route('param.get_district')}}', '#district_project', null, '{{$_GET['district_id']??-1}}')
            // @endif

            // $('#district_sell').change(function () {
            //     get_province_by_district(this, '#province_sell')
            // })
            // $('#district_rent').change(function () {
            //     get_province_by_district(this, '#province_rent')
            // })
            // $('#district_project').change(function () {
            //     get_province_by_district(this, '#province_project')
            // })


            // get_progress_by_url_home('#group_sell','{{route('param.get_progress_by_url')}}','.progress_sell',null,'{{$_GET['progress']??-1}}')
            // get_furniture_by_url_home('#group_sell','{{route('param.get_furniture_by_url')}}','.furniture_sell',null,'{{$_GET['furniture']??-1}}')

            // // classified rent
            // get_progress_by_url_home('#group_rent','{{route('param.get_progress_by_url')}}','.progress-rent',null,'{{$_GET['progress']??-1}}')
            // {{--get_furniture_by_url_home('#group_rent','{{route('param.get_furniture_by_url')}}','.furniture_rent',null,'{{$_GET['furniture']??-1}}')--}}
            // // project
            // get_progress_by_url_home('#group_project','{{route('param.get_progress_by_url')}}','.progress_project',null,'{{$_GET['progress']??-1}}')
            // get_furniture_by_url_home('#group_project','{{route('param.get_furniture_by_url')}}','.furniture_project',null,'{{$_GET['furniture']??-1}}')
        });
        // $('input.search-sell').on('click',function (e){
        //     e.preventDefault();
        //     var province = $('#province_sell option:selected').data('url');
        //     var url ='';
        //     if(province){
        //         url += "{{route('home.classified.location','')}}";
        //         url +=province;
        //         if($('.group_sell_url').val()!='mo-hinh'){
        //             url+= '/'+$('.group_sell_url').val();
        //         }
        //     }
        //     else
        //     {
        //         var group_parent = $(this).parents('.submit').siblings('select.group_sell_url').data('group_parent');

        //         url += "{{route('home.classified.list','')}}";
        //         if($('.group_sell_url').val()!='mo-hinh'){
        //             url+='/'+group_parent+'/'+$('.group_sell_url').val();
        //         }
        //         else{
        //             url+='/'+group_parent;
        //         }
        //     }
        //     $('.form-search-sell-classified').attr('action',url);

        //     $('.form-search-sell-classified').submit();

        // });
        // $('input.search-rent').on('click',function (e){
        //     e.preventDefault();
        //     var province = $('#province_rent option:selected').data('url');
        //     var url ='';
        //     if(province){
        //         url += "{{route('home.classified.location','')}}";
        //         url +=province;
        //         if($('.group_rent_url').val()!='mo-hinh'){
        //             url+= '/'+$('.group_rent_url').val();
        //         }
        //     }
        //     else
        //     {
        //         var group_parent = $(this).parents('.submit').siblings('select.group_rent_url').data('group_parent');

        //         url += "{{route('home.classified.list','')}}";
        //         if($('.group_rent_url').val()!='mo-hinh'){
        //             url+='/'+group_parent+'/'+$('.group_rent_url').val();
        //         }
        //         else{
        //             url+='/'+group_parent;
        //         }
        //     }
        //     $('.form-search-rent-classified').attr('action',url);

        //     $('.form-search-rent-classified').submit();

        // });
        // $('input.search-project').on('click',function (e){
        //     e.preventDefault();
        //     var url ='';
        //     var group_parent = $(this).parents('.submit').siblings('select.group_project_url').data('group_parent');
        //     url += "{{route('home.project.index','')}}";
        //     if($('.group_project_url').val()!='mo-hinh'){
        //         url+='/'+$('.group_project_url').val();
        //     }

        //     $('.form-search-project').attr('action',url);

        //     $('.form-search-project').submit();

        // });
    </script>
    <script>
        $("body").on("click", ".popup .close-button", function (event) {
            event.preventDefault();
            let popup = $(this).parents(".popup");
            popup.hide();
            $("#layout").hide();
            $("#location_classified").empty()
        });

        $("body").on("click", "#layout", function (event) {
            event.preventDefault();
            $("#map-fixed").hide();
            $("#location_classified").empty()
        });

        $("body").on("click", ".location_classified", function (event) {
            event.preventDefault();
            viewMap($(this).data('id'));
        });
        // $(".auto_load").hide();

    </script>
@endsection
