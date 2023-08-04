@extends('user.layouts.master')

@section('title', 'Hỗ trợ kỹ thuật')

@section('content')
  <div class="user-support-page">
    <div class="p-3 mb-4">
      <h2 class="text-center font-weight-bold">Nhân viên chăm sóc khách hàng</h2>
    </div>

    <div class="care-staff-box container bg-unset">
      <div class="owl-carousel owl-custom-theme owl-hover-nav owl-nav-rounded owl-nav-small owl-drag user-support__staff-carousel">
        @foreach ($careStaffs as $staff)
          <div class="care-staff__item text-center pb-3">
            <div class="image-ratio-box-square relative mb-3">
              <div class="absolute-full rounded-circle bg-white">
                <img class="object-cover rounded-circle" src="{{ $staff->getAvatarUrl() }}">
              </div>
            </div>

            <div class="care-staff__name">
              <h3 class="text-main text-ellipsis w-100 mb-0">{{ $staff->admin_fullname }}</h3>
            </div>

            <div class="care-staff-detail-box">
              <div class="mb-3">
                <x-common.color-star :stars="$staff->getLastCustomerChatRating(auth('user')->user()->id)"/>
              </div>

              <div class="care-staff__status-box mb-3">
                <span class="badge badge-pill bg-white pl-4 py-2 w-100 relative">
                  <span class="care-staff__dot-box flex-center">
                    <span class="status-dot {{ $staff->getAvailableStatus() }}"></span>
                  </span>
                  <span class="care-staff__status-text fw-normal fs-normal">
                    Đang {{ $staff->getAvailableStatus() }}
                  </span>
                </span>
              </div>

              <div class="care-staff__chat-action relative">
                <a
                  href="javascript:void(0);"
                  class="btn btn-block btn-{{ $staff->getAvailableStatus() == 'online' ? 'success' : 'gray' }} bold care-staff__btn-chat"
                  {{-- data-token="{{ $staff->getLastActiveConversationToken(auth('user')->user()->id) }}" --}}
                  data-is-support="1"
                  data-receiver-id="{{ $staff->id }}"
                >
                  Chat
                </a>
                <span class="care-staff__chat-icon flex-center">
                  <i class="far fa-comment-dots"></i>
                </span>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <hr>

    <div class="basic-chat-box container bg-unset">
      <div class="p-3 mb-4 text-center">
        <h4>
          Chat với nhân viên CSKH hoặc để lại thông tin theo mẫu dưới
        </h4>
        <h4 class="text-danger">
          Hotline hỗ trợ 24/7: 090.999.2638
        </h4>
      </div>

      <div class="basic-chat__form">
        <form action="{{ route('user.conversations.sendBasic-chat') }}" method="POST">
          @csrf
          <input type="checkbox" name="is_support" value="1" checked hidden>
          <div class="basic-chat__type-select">
            <x-common.select2-input
              class="flex-start flex-wrap"
              label-class="flex-shrink-0 mr-2"
              label="Chọn mục cần hỗ trợ"
              name="chat_type"
              placeholder="Chọn mục cần hỗ trợ"
              :items="$chatTypes"
              :items-current-value="old('chat_type')"
              required
            />
          </div>

          <x-common.textarea-input
            name="message"
            value="{{ old('message') }}"
            placeholder="Nhập nội dung"
          />

          <div class="mb-3">
            <x-common.captcha />
          </div>

          <div class="flex-between">
            <span class="font-italic">
              Thắc mắc của Khách hàng sẽ được giải đáp trong 24h. <br>
              Vui lòng kiểm tra <a class="link-light-cyan" href="{{ route('user.mailbox') }}">Hòm thư</a>
            </span>
            <button type="submit" class="btn btn-light-cyan px-4">
              Gửi
            </button>
          </div>
        </form>
      </div>
    </div>

    <hr>

    <div class="container pt-4">
      <div class="filter c-dashed-box">
        <h3 class="title">Bộ lọc</h3>

        <form action="" method="get" class="form-filter">
          <div class="row">
            <div class="col-md-7">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <x-common.text-input
                      name="keyword"
                      value="{{ request()->keyword }}"
                      placeholder="Nhập thông tin, Thời gian"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <x-common.select2-input
                      name="care_staff"
                      placeholder="Nhân viên CSKH"
                      :items="$careStaffs"
                      item-text="admin_fullname"
                      items-current-value="{{ request()->care_staff }}"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <x-common.select2-input
                      name="request_item"
                      placeholder="Mục yêu cầu"
                      :items="$chatTypes"
                      items-current-value="{{ request()->request_item }}"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <x-common.text-input
                      name="date"
                      :type="old('date') ? 'date' : 'text'"
                      value="{{ request()->date }}"
                      placeholder="Thời gian"
                      hover-date
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <x-common.select2-input
                      name="status"
                      placeholder="Tình trạng"
                      :items="$statuses"
                      items-current-value="{{ request()->status }}"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <x-common.select2-input
                      name="rating"
                      placeholder="Đánh giá"
                      :items="$ratings"
                      items-current-value="{{ request()->rating }}"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="text-center py-3">
            <button type="submit" class="btn btn-cyan rounded-0">
              <i class="fas fa-search"></i>
              Tìm kiếm
            </button>
          </div>
        </form>
      </div>
      <div class="table table-responsive table-vertical table-bordered table-hover">
        <table class="table">
          <thead class="thead-main">
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Nội Dung</th>
              <th class="text-center">Mục</th>
              <th class="text-center">Tình trạng</th>
              <th class="text-center">Cài đặt</th>
            </tr>
          </thead>
          <tbody>
              @csrf
              <input type="hidden" name="action" id="action_list" value="">
              @foreach ($conversations as $conversation)
                <tr>
                  <td>
                    {{ ($conversations->currentPage() - 1) * $conversations->perPage() + $loop->index + 1 }}
                  </td>
                  <td>
                    @if($conversation->getFirstMessage())
                      <p class="text-ellipsis ellipsis-3 text-break">
                        {{ $conversation->getFirstMessage()->message }}
                        {{-- <span class="link-red-flat">Xem</span> --}}
                      </p>
                    @endif
                    <div class="flex-between flex-wrap">
                      @if($conversation->admin)
                        <span class="mb-1">
                          <i class="fas fa-user"></i>
                          <span class="text-dark-cyan">
                            {{ $conversation->admin->getFullName() }}
                          </span>
                        </span>
                        <x-common.color-star stars="{{ $conversation->rating }}" type="icon" />
                      @endif
                    </div>
                    @if($conversation->getFirstMessage())
                      <div class="flex-between">
                        <span class="{{ $conversation->getChatTextClass() }}">
                          {{ $conversation->getChatActionLabel() }}
                        </span>
                        <span class="text-muted">
                          {{ $conversation->getFirstAdminReplyMessage() && $conversation->getFirstAdminReplyMessage()->created_at ? $conversation->getFirstAdminReplyMessage()->created_at->format('d/m/Y - H:i') : '' }}
                        </span>
                      </div>
                    @endif
                  </td>
                  <td class="text-center">
                    <strong class="text-danger">
                      {{ $conversation->type }}
                    </strong>
                  </td>
                  <td class="text-center
                  @switch($conversation->status)
                    @case(\App\Enums\ChatStatus::ACTIVE)
                        @break
                    @case(\App\Enums\ChatStatus::ENDED)
                        bg-success
                        @break
                    @case(\App\Enums\ChatStatus::PENDING)
                        bg-warning
                      @break
                    @default
                  @endswitch
                  ">
                    <p>
                      {{ $conversation->getStatusLabel() }}
                    </p>
                    {{-- @if($conversation->isSpammed())
                      <div class="text-danger">
                        Đã chặn spam: 
                        {{ $conversation->remainingSpammedTime() }}
                      </div>
                    @endif --}}
                  </td>
                  <td>
                    <div>
                      <div class="mb-1">
                        <span class="icon-small-size mr-1">
                          <i class="fas fa-{{ $conversation->isEnded() ? 'sign-out-alt' : 'comments' }}"></i>
                        </span>
                        <a href="javascript:void(0);" class="user__open-conversation text-{{ $conversation->isEnded() ? 'danger' : 'success' }}" data-token="{{ $conversation->token }}">
                          {{ $conversation->isEnded() ? 'Xem hội thoại' : 'Phản hồi' }}
                        </a>
                        @if($conversation->getUnreadMessage(auth('user')->user()))
                          <span class="badge badge-danger badge-danger">{{ $conversation->getUnreadMessage(auth('user')->user()) }}</span>
                        @endif
                      </div>
                    </div>
                  </td>
                </tr>
              @endforeach
          </tbody>
        </table>
      </div>

      <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
        <div class="text-left d-flex align-items-center">
          <a href="javascript:void(0);" class="js-go-to-top mr-2">
            <img src="{{ asset('/system/image/manipulation.png') }}" class="cursor-pointer py-1"
              title="Về đầu trang" alt="">
          </a>
  
          <div class="display d-flex align-items-center mr-4">
            <span class="mr-2">Hiển thị:</span>
            <form method="get" id="paginateform" action="">
              <select class="custom-select" id="paginateNumber" name="items">
                <option @if (isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">
                  10
                </option>
                <option @if (isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif value="20">
                  20
                </option>
                <option @if (isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif value="30">
                  30
                </option>
              </select>
            </form>
          </div>
        </div>
        
        <div class="bottom-account-business mb-2">
          <div class="row">
            <div class="col-12 pagenate-bottom">
              {{ $conversations->render('user.page.pagination') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scriptsss')
  {{-- <script src="{{ asset('common/plugins/socket.io/4.6.0/socket.io.min.js') }}"></script> --}}
  <script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/2.4.0/socket.io.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.0/echo.iife.js"></script>

  <script>
    $(function() {
      const echo = new Echo({
        broadcaster: "socket.io",
        host: window.location.hostname + ':6001'
      })

      echo.join('online')
        .here((users) => {
          users.map((user) => updateJoin(user))
        })
        .joining((user) => {
          console.log('joining', user);
          updateJoin(user)
        })
        .leaving((user) => {
          console.log('leaving', user);
          updateLeave(user)
        });
    })

    function updateJoin(user) {
      $(`#status-admin-${user.id}`).addClass('active')
      $(`#text-status-admin-${user.id}`).html('Đang online')
      $(`#btn-chat-${user.id}`).addClass('active').prop('href', `{{ route('user.generate-chat', ['', '']) }}/${user.id}`)
    }

    function updateLeave(user) {
      $(`#status-admin-${user.id}`).removeClass('active')
      $(`#text-status-admin-${user.id}`).html('Đang offline')
      $(`#btn-chat-${user.id}`).removeClass('active').prop('href', 'javascript:{}')
    }
  </script>
@endsection
