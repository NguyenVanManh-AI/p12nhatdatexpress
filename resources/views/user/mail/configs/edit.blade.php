@extends('user.layouts.master')

@section('title', 'Chỉnh sửa cấu hình mail')

@section('content')
  <x-user.breadcrumb
    active-label="Chỉnh sửa cấu hình mail"
    :parents="[
      [
        'label' => 'Thành viên',
        'route' => 'user.index'
      ],
      [
        'label' => 'Cấu hình mail',
        'route' => 'user.config-mail'
      ],
    ]"
  />

  <div class="mail-configuration px-3 pb-3 user-mail-config-page">
    @include('user.mail.configs.partials._form', [
        'routeLink' => route('user.podt-edit-config-mail', $mailConfig->id),
        'mailConfig' => $mailConfig,
        'guide' => $guide
    ])
  </div>
@endsection
