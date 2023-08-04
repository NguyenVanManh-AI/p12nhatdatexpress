<li class="user-notify__box nav-item flex-center h-100 hover-bg-gray {{ $attributes['class'] }}">
  <a class="nav-link fs-20" data-toggle="dropdown" href="#" aria-expanded="true" title="Thông báo">
    <i class="far fa-bell"></i>
    <span class="header-action__badge user-notify-box__count badge badge-danger navbar-badge rounded-circle">{{ $notifies->count() }}</span>
  </a>
  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
    @if($notifies->count())
      <span class="dropdown-item dropdown-header"><span class="user-notify-box__count">{{ $notifies->count() }}</span> thông báo</span>
    @endif
    <div class="dropdown-divider"></div>
    @forelse($notifies as $notify)
    <a href="javascript:void(0);" class="user-notify-box__item dropdown-item flex-start position-relative">
      <i class="{{ $notify->icon }} mr-2"></i>
      <span class="text-ellipsis flex-1 mr-1" data-title="{{ $notify->content }}">
        {{ $notify->content }}
      </span>
      <span class="float-right text-muted text-sm">{{ \App\Helpers\Helper::getHumanTimeWithPeriod($notify->created_at) }}</span>
      
      <span href="javascript:void(0);" title="Ẩn" data-id="{{ $notify->id }}" class="js-read-notify text-danger position-absolute end-0 top-0 p-2 h-100 hover-bg-gray">
        <i class="fas fa-times"></i>
      </span>
    </a>
    <div class="dropdown-divider"></div>
    @empty
      <span class="dropdown-item text-center">
        Không có thông báo mới!
      </span>
    @endforelse
    {{-- <div class="dropdown-divider"></div>
    <a href="#" class="dropdown-item dropdown-footer">Xem tất cả thông báo</a> --}}
  </div>
</li>
