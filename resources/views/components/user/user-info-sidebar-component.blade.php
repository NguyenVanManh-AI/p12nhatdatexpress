<div class="account-business-avatar rounded-circle d-flex">
    <a href="{{ asset($user_info->image_url??SystemConfig::REPLACE_AVATAR_IMAGE) }}" class="js-fancy-box">
        <img src="{{asset($user_info->image_url??SystemConfig::REPLACE_AVATAR_IMAGE)}}" class="rounded-circle object-cover" alt="">
    </a>
<a class="text-white" href="{{ route('user.personal-info') }}">
    <i class="fas fa-pen custom__i "></i>
</a>
</div>
<div class="account-business-profile-content d-flex justify-content-center flex-column">
    <h5 class="text-capitalize">{{$user_info->fullname}}</h5>
    <p>{{$user_info->type_name}}</p>
    <p>Cấp độ : <span class="text-capitalize">{{$user_info->level_name}}</span></p>
</div>
