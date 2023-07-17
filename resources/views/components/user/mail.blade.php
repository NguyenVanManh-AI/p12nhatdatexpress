<li class="nav-item flex-center h-100 hover-bg-gray {{ $attributes['class'] }}">
  <a class="nav-link fs-20" data-toggle="dropdown" href="#" aria-expanded="true" title="Mails">
    <i class="far fa-envelope"></i>
    <span class="header-action__badge badge badge-danger navbar-badge rounded-circle">{{ $mails->count() }}</span>
  </a>
  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
    @if($mails->count())
      <span class="dropdown-item dropdown-header">{{ $mails->count() }} Mails</span>
    @endif
    <div class="dropdown-divider"></div>
    @forelse($mails as $mail)
    <a href="{{ route('user.mailbox-detail', $mail->id) }}" class="dropdown-item flex-start">
      <i class="fas fa-envelope mr-2"></i>
      <span class="text-ellipsis flex-1">
        {{ $mail->mail_title }}
      </span>
      <span class="float-right text-muted text-sm">{{ \App\Helpers\Helper::getHumanTimeWithPeriod($mail->send_time) }}</span>
    </a>
    <div class="dropdown-divider"></div>
    @empty
      <span class="dropdown-item text-center">
        Không có Mails mới!
      </span>
    @endforelse
    <div class="dropdown-divider"></div>
    <a href="{{ route('user.mailbox') }}" class="dropdown-item dropdown-footer">Xem tất cả Mails</a>
  </div>
</li>
