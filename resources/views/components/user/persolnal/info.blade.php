<div class="info-user text-center">
    <div class="frame-avatar">
        <div class="avatar">
            <img id="preview_avatar" src="{{ $item->getExpertAvatar() }}" alt="">
        </div>
        @if(\Auth::guard('user')->id() == $item->id)
        <div class="upload-avatar">
            <i class="fas fa-camera"></i>
            <input id="input_avatar" type="file">
        </div>
        @endif
    </div>
    <div class="user-name">
        <h2>{{ data_get($item->detail, 'fullname') }}</h2>
    </div>
    <div class="position">
        <span>{{ data_get($item->user_type, 'type_name') }}</span>
    </div>
    <div class="user-rank mb-2">
        <span class="text-orange text-bold">Cấp bậc: {{$item->user_level->level_name}} | </span>
        <span class="join-date">Tham gia ngày: {{date('d/m/Y',$item->created_at)}}</span>
    </div>
    <div class="user-reviews">
        <div>
            <x-common.color-star class="star-curved" :stars="$item->getAvgRatingUsers() ?: 5"/>
        </div>
    </div>

    @if($item->isEnterprise() && $item->id != auth()->guard('user')->id())
        <div class="flex-center flex-wrap pt-2 pb-3">
            <div class="item follow-user btn btn-sm btn-cyan mr-3 {{ !auth('user')->id() ? 'js-open-login' : '' }}" data-id="{{$item->id}}">
                <i class="fas fa-rss"></i>
                <span class="name">
                    {{ $item->isUserFollowing(auth('user')->id()) ? 'Đang theo dõi' : 'Theo dõi' }}
                </span>
            </div>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{request()->url()}}" target="_blank" class="btn btn-sm btn-success {{ !auth('user')->id() ? 'js-open-login' : '' }}">
                <i class="fas fa-share"></i>
                <span>
                    Chia sẻ
                </span>
            </a>
        </div>
    @endif
</div>
