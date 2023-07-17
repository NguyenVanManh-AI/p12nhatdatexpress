<div class="bottom">
  <div class="mb-2 text-danger">
    <span>Tối đa 10 mẫu mail</span>
  </div>
  <div class="title-manager text-center">
    <h4 class="color-white">Danh sách mail đã lưu</h4>
  </div>
  <div class="table-responsive">
    <table class="table table-sendmail table-introduce">
      <tbody>
        <tr class="title-sendmail">
          <th>STT</th>
          <th>Tiêu đề </th>
          <th>Cài đặt</th>
        </tr>
        @foreach ($temlate_mail as $item)
          <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td class="td-content-send">{{ $item->mail_header }}</td>
            <td class="setting-table-1 text-left">
              <div class="row">
                <div class="col-6">
                  <a href="{{ route('user.campaigns.create', ['user_mail_template_id' => $item->id]) }}" class="mr-2 mb-2 d-inline-block text-success">
                    <i class="fas fa-chart-pie"></i>
                    Tạo chiến dịch
                  </a>
                </div>
                <div class="col-6">
                  <a
                    href="javascript:void(0);"
                    class="mr-2 mb-2 d-inline-block text-success"
                    data-toggle="modal"
                    data-target="#user-mail-view-content-{{ $item->id }}"
                  >
                    <i class="fas fa-eye"></i>
                    Xem
                  </a>
                  {{-- view content modal --}}
                  @include('user.mail.template.partials._view-content', [
                    'userMail' => $item
                  ])
                </div>
                <div class="col-6">
                  <a href="{{ route('user.edit-template-mail', $item->id) }}" class="text-cyan mr-2 mb-2 d-inline-block">
                    <i class="fas fa-cog"></i>
                    Chỉnh sửa
                  </a>
                </div>
                <div class="col-6">
                  <a href="{{ route('user.delete-template-mail', $item->id) }}" class="text-danger delete-alert mb-2 d-inline-block">
                    <i class="fas fa-times"></i>
                    Xóa
                  </a>
                </div>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
