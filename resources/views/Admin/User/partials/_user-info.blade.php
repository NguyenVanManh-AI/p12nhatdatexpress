<div>
  <div class="info-account mb-2">
    {{ $item->id }}
    <div class="avatar">
      <img src="{{ asset($item->user_image ?? SystemConfig::USER_AVATAR) }}" alt="" style="object-fit: cover; border-radius: 50%">
    </div>
    <div class="name mt-2">
      <span>
        {{ data_get($item->detail, 'fullname') }}
      </span>
    </div>
    <div class="text-green">
      <span>
        {{ data_get($item->user_type, 'type_name') }}
      </span>
    </div>
    <div class="rank">
      Cấp bậc: <span>{{ data_get($item->level, 'level_name', 'Mới') }}</span>
    </div>
    <div class="birth-date">
      {{ date('d/m/Y', data_get($item->user_detail, 'birthday')) }}
    </div>
  </div>
  <ul class="info-contact text-left">
    <li>
      <i class="fas fa-phone-alt"></i>
      <a href="tel:{{ $item->phone_number}}" class="text-red mr-1 text-bold show-phone">{{$item->phone_number}}</a>
    </li>
    <li>
      <i class="fas fa-envelope"></i>
      <a href="mailto:{{ $item->email }}">{{ $item->email }}</a></li>
    <li>
      <i class="fas fa-map-marker-alt"></i>
      {{ data_get($item->user_location, 'address') . ', ' . $item->district .', ' . $item->province}}</li>
    <li>
      <i class="fas fa-file-alt"></i>
      MST/ CMND: {{ data_get($item->user_detail, $business ? 'tax_number' : 'personal_id') }} </li>
  </ul>
</div>
