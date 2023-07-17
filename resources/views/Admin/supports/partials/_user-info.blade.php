<div class="user-info-box">
  <a href="javascript:void(0);" class="text-success font-weight-bold text-ellipsis ellipsis-2 text-break">
    {{ $user->getFullName() }}
  </a>
  <div>
    <span class="text-dark">
      {{ data_get($user->type, 'type_name') }}
    </span>
  </div>
  <div class="flex-start flex-wrap">
    <div>
      <x-user.avatar
        width="85"
        height="85"
        rounded="3"
        avatar="{{ asset($user->getAvatarUrl()) }}"
      />
    </div>
    <div class="d-flex flex-column flex-wrap py-2 px-3">
      <span class="mb-1">Cấp bậc:
        <span class="text-dark-cyan">{{ data_get($user->level, 'level_name') }}</span>
      </span>
      <span class="mb-1 text-dark bold">
        <x-user.phone-number :user="$user" class="link-red-flat phone__copy-small phone__copy-link">
          <x-slot name="icon">
            <div class="mr-2">
              <i class="fas fa-phone-alt"></i>
            </div>
          </x-slot>
        </x-user.phone-number>
      </span>
      @if($user->email)
        <span class="mb-1">
          <i class="fas fa-envelope"></i>
          <a class="link-dark" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
        </span>
      @endif
      <small class="flex-start mb-1">
        <span class="mr-1">
          Trạng thái:
        </span>
        @include('Admin.User.partials._user-status', [
          'user' => $user,
          'class' => 'mb-0',
          'showSpam' => true
        ])
      </small>
      @if(isset($conversation))
        <span class="text-muted">
          {{ $conversation->created_at ? $conversation->created_at->format('d/m/Y - H:i') : '' }}
        </span>
      @endif
    </div>
  </div>
</div>
