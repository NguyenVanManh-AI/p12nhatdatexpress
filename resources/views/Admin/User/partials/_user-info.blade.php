<div class="text-center">
  <x-user.avatar
    image-class="cus_avatar"
    width="60"
    height="60"
    rounded="30"
    avatar="{{ asset($user->getAvatarUrl()) }}"
  />

  <div class="d-flex flex-column mb-2">
    <span class="text-main fs-20 font-weight-bold mb-1">
      {{ data_get($user->detail, 'fullname') }}
    </span>
    <span class="text-success mb-1">
      {{ data_get($user->user_type, 'type_name') }}
    </span>
    <span class="mb-1">
      Cấp bậc: <span class="text-dark-cyan">{{ data_get($user->level, 'level_name', 'Mới') }}</span>
    </span>
    <span class="text-muted">
      {{ date('d/m/Y', data_get($user->detail, 'birthday')) }}
    </span>
  </div>

  <div class="text-left">
    <div>
      <div class="mb-1 text-dark bold">
        <x-user.phone-number :user="$user" class="link-red-flat phone__copy-small phone__copy-link">
          <x-slot name="icon">
            <span class="icon-squad-2 mr-2">
              <i class="fas fa-phone-alt"></i>
            </span>
          </x-slot>
        </x-user.phone-number>
      </div>
      @if($user->email)
        <div class="mb-1 flex-start">
          <span class="icon-squad-2 mr-2">
            <i class="fas fa-envelope"></i>
          </span>
          <a class="link-dark" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
        </div>
      @endif
      <div class="mb-1 flex-start">
        <span class="icon-squad-2 mr-2">
          <i class="fas fa-map-marker-alt"></i>
        </span>
        {{ $user->getFullAddress() }}
      </div>
      <div class="flex-start">
        <span class="icon-squad-2 mr-2">
          <i class="fas fa-file-alt"></i>
        </span>
        {{ $user->isEnterprise() ? 'MST' : 'CMND/CCCD' }}: {{ data_get($user->detail, 'tax_number') }}
      </div>
    </u>
  </div>
</div>
