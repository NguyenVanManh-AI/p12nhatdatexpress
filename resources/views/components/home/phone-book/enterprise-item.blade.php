<div class="item rounded enterprise-list__item">
  <div class="image py-2">
    <div class="image-ratio-box-square relative w-100">
      <a href="{{ $item->getExpertAvatar() }}" class="absolute-full js-fancy-box">
        <img class="object-cover lazy" data-src="{{ $item->getExpertAvatar() }}" alt="">
      </a>
    </div>
    {!! renderRating($item->rating) !!}
  </div>

  <div class="info">
    <a href="{{ route('trang-ca-nhan.dong-thoi-gian', $item->user_code) }}" class="name font-weight-bold">
      <h3 class="title bold text-ellipsis w-100 {{ $item->is_highlight ? 'link-red-flat' : 'link-dark' }}">
        {{ $item->getFullName() }}
      </h3>
    </a>
    <div class="text-ellipsis ellipsis-2">
      <p class="item-content desc">
        {{ \Illuminate\Support\Str::limit(data_get($item->detail, 'intro'), 100, '...') }}
      </p>
    </div>
    <div class="item-content project">
      <i class="content-icon fas fa-bullseye"></i>
      <span>
        Dự án đang triển khai: {{ $item->projects->pluck('project_name')->join(', ') ?: '--' }}
      </span>
    </div>
    <div class="item-content phone">
      <x-user.phone-number
        :user="$item"
      >
        <x-slot name="icon">
          <i class="content-icon fas fa-phone-alt"></i>
        </x-slot>
      </x-user.phone-number>
    </div>
    <div class="item-content address">
      <i class="content-icon fas fa-map-marker-alt"></i>
      <span>
        {{ $item->location ? $item->location->getSortAddress() : '' }}
      </span>
    </div>
  </div>
</div>
