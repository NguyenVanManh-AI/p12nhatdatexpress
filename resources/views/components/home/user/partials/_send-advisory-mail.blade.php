<strong>Họ & tên:</strong> {{ data_get($params, 'fullname') }}</p>

<p><strong>Số điện thoại:</strong> {{ data_get($params, 'phone_number') }}</p>

<p><strong>Email:</strong> {{ data_get($params, 'email') }}</p>

<p><strong>Note:</strong> {{ data_get($params, 'note') }}</p>

@if(data_get($params, 'registration.url'))
<p>
  <strong>Đường dẫn đăng ký:</strong>
  <a href="{{ data_get($params, 'registration.url')}}">
    {{ data_get($params, 'registration.name') }}
  </a>
</p>
@endif

<p><strong>Ngày gửi:</strong> {{ data_get($params, 'created_at') ? data_get($params, 'created_at')->format('d-m-Y H:i') : '' }}</p>
