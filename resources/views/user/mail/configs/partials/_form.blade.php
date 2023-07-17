<div class="top row d-flex justify-content-between">
  <div class="configuration ml-2 configuration-mail p-0">
    <div class="title-manager text-center">
      <h4 class="color-white">Cấu hình mail gửi</h4>
    </div>
    <div class="content border-0">
      <form action="{{ $routeLink }}" class="js-current-config-form" method="post">
        @csrf
        <div class="main-form bg-white">
          <div class="row">
            <div class="col">
              <div class="row d-flex justify-content-between align-items-center mb-2">
                <div class="col">
                  <div class="form-group row">
                    <div class="col-md-3 text-md-right">
                      <label class="bold">SMTP</label>
                    </div>
                    <div class="col-md-9">
                      <input type="text" name="mail_host" class="form-control" placeholder="ex: smtp.gmail.com"
                        value="{{ old('mail_host', $mailConfig->mail_host) }}" data-default="smtp.gmail.com">
                      {{ show_validate_error($errors, 'mail_host') }}
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-3 text-md-right">
                      <label class="bold">Cổng</label>
                    </div>
                    <div class="col-md-9">
                      <div class="row">
                        <div class="col-md-4 col-6">
                          <input type="number" name="mail_port" class="form-control" data-default="465"
                            placeholder="465" value="{{ old('mail_port', $mailConfig->mail_port) }}">
                        </div>
                        <div class="col-md-4 col-6">
                          <button type="button" class="btn btn-default bold js-default-form" title="Mặc định">
                            Mặc định
                          </button>
                        </div>
                      </div>
                      {{ show_validate_error($errors, 'mail_port') }}
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-9 offset-md-3">
                      <div class="row">
                        <div class="col-md-4 col-6">
                          <label class="c-form-check form-check-grey">
                            <strong>Bật SSL</strong>
                            <input type="radio" name="mail_encription" value="ssl"
                              {{ old('mail_encription', $mailConfig->mail_encription) != 'tls' ? 'checked' : '' }}
                              class="c-form-check-input">
                            <span class="c-form-check-mark"></span>
                          </label>
                        </div>
                        <div class="col-md-4 col-6">
                          <label class="c-form-check form-check-grey">
                            <strong>Bật TLS</strong>
                            <input type="radio" name="mail_encription" value="tls"
                              {{ old('mail_encription', $mailConfig->mail_encription) == 'tls' ? 'checked' : '' }}
                              class="c-form-check-input">
                            <span class="c-form-check-mark"></span>
                          </label>
                        </div>
                      </div>
                      {{ show_validate_error($errors, 'mail_encription') }}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-3 text-md-right">
                      <label class="bold">Tên đăng nhập</label>
                    </div>
                    <div class="col-md-9">
                      <input type="email" name="mail_username" class="form-control"
                        value="{{ old('mail_username', $mailConfig->mail_username) }}">
                      {{ show_validate_error($errors, 'mail_username') }}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-3 text-md-right">
                      <label class="bold">Mật khẩu</label>
                    </div>
                    <div class="col-md-9">
                      <input type="text" name="mail_password" class="form-control"
                        value="{{ old('mail_password', $mailConfig->mail_password) }}">
                      {{ show_validate_error($errors, 'mail_password') }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="c-pl-15">
          <div class="row">
            <div class="col-md-9 offset-md-3">
              <div class="flex-between flex-wrap">
                <button type="button" class="btn btn-normal js-clear-form mb-3">
                  <img class="icon-squad-2" src="{{ asset('user/images/icon/quit-icon.png') }}" alt="">
                  <span class="pl-4 pr-5">
                    Thoát
                  </span>
                </button>
                <a href="javascript:void(0);" class="btn btn-normal mb-3" data-toggle="modal" data-target="#user-mail-test">
                  <img class="icon-squad-2" src="{{ asset('user/images/icon/send-icon.png') }}" alt="">
                  <span class="pl-4 pr-5">
                    Gửi thử
                  </span>
                </a>
                <button type="submit" class="btn btn-normal mb-3">
                  <img class="icon-squad-2" src="{{ asset('user/images/icon/save-icon.png') }}" alt="">
                  <span class="pl-4 pr-5">
                    Lưu
                  </span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="configuration-instruct mr-2">
    <div class="tutorial">
      <div class="title-manager text-center">
        <h4 class="color-white">Hướng dẫn</h4>
      </div>
      <div class="content configuration-instruct-content text-main">
        {!! $guide->config_value ?? '' !!}
      </div>
    </div>
  </div>

  <div class="modal fade" id="user-mail-test" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header flex-center bg-sub-main">
          <h4 class="modal-title text-white fs-18 p-0">Gửi thử mail</h4>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form class="js-send-test-form">
          <div class="modal-body">
            <x-common.text-input
              label="Email người nhận"
              name="user_mail"
              value="{{ old('user_mail', auth('user')->user()->email) }}"
              required
            />
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">
              Hủy
            </button>
            <button type="button" class="btn btn-success js-send-test relative">
              <x-common.loading class="inner send-mailtest-loading small"/>
              Gửi thử mail
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts_children')
  <script type="text/javascript">
    (() => {
      $('.user-mail-config-page .js-default-form').on('click', function() {
        $('[data-default]').each(function() {
          $(this).val($(this).data('default'))
        })
        $('[name="mail_encription"][value="ssl"]').prop('checked', true)
      });

      const sendMailTest = () => {
        let formData = new FormData($('.js-current-config-form')[0]),
            userMail = $('#user-mail-test [name="user_mail"]').val();

        formData.append('user_mail', userMail)
        $('.c-overlay-loading.send-mailtest-loading').addClass('loading')
        $('.js-send-test').prop('disabled', true)

        $.ajax({
          url: '/thanh-vien/mail-configs/test',
          method: 'POST',
          contentType: false,
          processData: false,
          dataType: 'json',
          data: formData,
          success: res => {
            $('.c-overlay-loading.send-mailtest-loading').removeClass('loading')
            $('.js-send-test').prop('disabled', false)

            if (res && res.message)
              res.success ? toastr.success(res.message) : toastr.error(res.message)

            $("#user-mail-test").modal('hide');
          },
          error: error => {
            $('.c-overlay-loading.send-mailtest-loading').removeClass('loading')
            $('.js-send-test').prop('disabled', false)

            if (error && error.responseJSON && error.responseJSON.errors) {
                const errors = Object.values(error.responseJSON.errors)
                if (errors[0] && errors[0][0])
                toastr.error(errors[0][0])
            } else if (error && error.responseJSON && error.responseJSON.message) {
                toastr.error(error.responseJSON.message)
            }
          },
        })
      }

      $('#user-mail-test .js-send-test').on('click', function () {
        sendMailTest()
      })

      $('#user-mail-test .js-send-test-form').on('submit', function (e) {
        e.preventDefault();
        sendMailTest()
      })
    })()
  </script>
@endpush
