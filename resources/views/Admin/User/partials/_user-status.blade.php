@if($user)
<p class="{{ isset($class) ? $class : '' }}">
  @if(isset($label))
    <strong>{{ $label }}:</strong>
  @endif

  @if ($user->is_deleted)
    <span class="text-danger my-1">{{ $user->getInfoStatus() }}</span>
  @else
    @if ($user->isBlocked() || $user->isForbidden() || $user->isSpammed())
      @if ($user->isForbidden())
        <span class="text-danger my-1">Đã cấm</span>
      @endif
      @if ($user->isBlocked())
        <span class="text-danger my-1">Đã chặn</span>
      @endif
      @if ($user->isSpammed())
        <span class="text-warning my-1">Đã chặn spam</span>
      @endif
    @else
      @if ($user->is_confirmed == 0)
        <span class="text-warning">Chờ xác thực</span>
      @else
        <span class="text-green">Hoạt động</span>
      @endif
    @endif
  @endif
</p>
@endif
