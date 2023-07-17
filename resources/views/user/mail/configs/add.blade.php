@extends('user.layouts.master')

@section('title', 'Cấu hình mail')

@section('content')
  <x-user.breadcrumb active-label="Cấu hình mail" />

  <div class="mail-configuration px-3 pb-3 user-mail-config-page">
    @include('user.mail.configs.partials._form', [
        'routeLink' => route('user.post-config-mail'),
        'mailConfig' => $mailConfig,
        'guide' => $guide,
    ])

    <div class="bottom">
      <div class="title-manager text-center">
        <h4 class="text-white fs-18 py-2">Danh sách mail gửi</h4>
      </div>
      <table class="table-sendmail table-introduce">
        <tbody>
          <tr class="title-sendmail">
            <th>STT</th>
            <th>Tên tài khoản</th>
            <th>Số mail đã gửi</th>
            <th>Cài đặt</th>
          </tr>
          @foreach ($userMailConfigs as $mail)
            <tr>
              <td>{{ $loop->index + 1 }}</td>
              <td>{{ $mail->mail_username }}</td>
              <td>{{ $mail->num_mail }}</td>
              <td class="setting-table">
                <a href="{{ route('user.edit-config-mail', $mail->id) }}">
                  <span class="text-cyan">
                    <i class="fa fa-cog"></i>&nbsp Chỉnh sửa
                  </span>
                </a>
                <a href="{{ route('user.delete-config-mail', $mail->id) }}" class="delete-alert">
                  <span class="text-danger">
                    <i class="fa fa-trash"></i>&nbsp Xóa
                  </span>
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
