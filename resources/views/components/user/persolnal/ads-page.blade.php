@php
$check = \Illuminate\Support\Facades\Auth::guard('user')->id()==$item->id?true:false;
@endphp
<div class="banner-page-profile">
    <div class="container">
        <div class="banner-left banner-persolnal {{ data_get($item->detail, 'banner_left') ? "" : "not-banner-persolnal" }}">
            @if(!$item->getBannerLeftUrl())
               @if($check)
               <div class="upload-ads position-relative">
                   <img src="/frontend/images/profile/add-ads.png" alt="">
                   <input id="banner_image_left" accept="image/*"  onchange="change_banner('banner_left',this,'.banner-left','banner_left_2')" name="banner_left" type="file">
               </div>
                @endif
            @else
                <a href="#">
                    <img class="img-ads" src="{{ $item->getBannerLeftUrl() }}" alt="">
                </a>
                @if($check)
                <div class="edit-ads">
                    <i class="far fa-edit"></i>
                    <input id="banner_left_2" accept="image/*" onchange="change_banner('banner_left',this,'',)" type="file">
                </div>
                @endif
            @endif
        </div>

        <div class="banner-right {{ data_get($item->detail, 'banner_right') ? "" : "not-banner-persolnal" }} banner-persolnal">
            @if(!$item->getBannerRightUrl())
                @if($check)
                <div class="upload-ads position-relative">
                    <img src="/frontend/images/profile/add-ads.png" alt="">
                    <input id="banner_right_1" accept="image/*" onchange="change_banner('banner_right',this,'.banner-right','banner_right_2')" type="file">
                </div>
                @endif
            @else
                <a href="#">
                    <img class="img-ads" src="{{ $item->getBannerRightUrl() }}" alt="">
                </a>
                @if($check)
                <div class="edit-ads">
                    <i class="far fa-edit"></i>
                    <input id="banner_right_2" accept="image/*" onchange="change_banner('banner_right',this,'',)" type="file">
                </div>
                @endif
            @endif
        </div>
    </div>
</div>


