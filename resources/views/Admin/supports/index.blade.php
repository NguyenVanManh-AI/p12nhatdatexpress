@extends('Admin.Layouts.Master')
@section('Title', 'Hỗ trợ kỹ thuật')

@section('Content')
  <section class="content pt-4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">
          <div class="card rounded-0 c-card">
            <div class="card-header rounded-0 d-flex py-2">
              <h3 class="card-title w-100 text-center text-uppercase text-black font-weight-bold fs-normal">
                Tổng hội thoại
              </h3>
              <div class="card-tools position-absolute top-0 end-0 p-1 m-0 mt-1">
                <button type="button" class="btn btn-tool text-dark" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body pb-2">
              <div class="py-3">
                <h2 class="bold text-center mb-0">{{ data_get($statisticsData, 'all_count') }}</h2>
              </div>
              <div class="flex-end">
                <a href="javascript:void(0);" class="text-cyan fs-normal">
                  &nbsp;
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card rounded-0 c-card {{ 1 ? 'border border-danger' : '' }}">
            <div class="card-header rounded-0 d-flex py-2">
              <h3 class="card-title w-100 text-center text-uppercase text-black font-weight-bold fs-normal">
                Đang chờ trả lời
              </h3>
              <div class="card-tools position-absolute top-0 end-0 p-1 m-0 mt-1">
                <button type="button" class="btn btn-tool text-dark" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body pb-2">
              <div class="py-3">
                <h2 class="bold text-center mb-0">{{ data_get($statisticsData, 'pending_count') }}</h2>
              </div>
              <div class="flex-end">
                <a href="javascript:void(0);" class="text-cyan fs-normal">
                  Xem
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="card rounded-0 c-card">
            <div class="card-header rounded-0 d-flex py-2">
              <h3 class="card-title w-100 text-center text-uppercase text-success font-weight-bold fs-normal">
                Đang chat
              </h3>
              <div class="card-tools position-absolute top-0 end-0 p-1 m-0 mt-1">
                <button type="button" class="btn btn-tool text-dark" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body pb-2">
              <div class="py-3">
                <h2 class="bold text-center mb-0">{{ data_get($statisticsData, 'chatting_count') }}</h2>
              </div>
              <div class="flex-end">
                <a href="javascript:void(0);" class="text-cyan fs-normal">
                  Xem
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card rounded-0 c-card">
            <div class="card-header rounded-0 d-flex py-2">
              <h3 class="card-title w-100 text-center text-uppercase text-danger font-weight-bold fs-normal">
                Đánh giá
              </h3>
              <div class="card-tools position-absolute top-0 end-0 p-1 m-0 mt-1">
                <button type="button" class="btn btn-tool text-dark" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body pb-2">
              <div class="py-3">
                <h2 class="bold text-center mb-0">{{ data_get($statisticsData, 'has_rating_count') }}</h2>
              </div>
              <div class="flex-end">
                <a href="javascript:void(0);" class="text-cyan fs-normal">
                  Xem
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter -->
      <div class="filter c-dashed-box">
        <h3 class="title">Bộ lọc</h3>

        <form action="{{ route('admin.support.index') }}" method="get" class="form-filter">
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
                      input-class="js-date-placeholder"
                      :type="old('date') ? 'date' : 'text'"
                      value="{{ request()->date }}"
                      placeholder="Thời gian"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <x-common.select2-input
                      name="account_type"
                      placeholder="Loại tài khoản"
                      :items="$accountTypes"
                      item-text="type_name"
                      items-current-value="{{ request()->account_type }}"
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
              {{-- <th class="text-center"><input type="checkbox" class="select-all checkbox" name="select-all" /></th> --}}
              <th class="text-center">#</th>
              <th class="text-center">Tên tài khoản</th>
              <th class="text-center">Nội Dung</th>
              <th class="text-center">Mục</th>
              <th class="text-center">Tình trạng</th>
              <th class="text-center">Cài đặt</th>
            </tr>
          </thead>
          <tbody>
            {{-- <form action="{{ route('admin.support.index') }}" id="trash_list" method="post"> --}}
              @csrf
              <input type="hidden" name="action" id="action_list" value="">
              @foreach ($conversations as $conversation)
                <tr>
                  {{-- <td>
                    <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{ conversation->id }}" />
                    <input type="hidden" class="select-item checkbox" name="select_item_created[{{ conversation->id }}]" value="{{ \Crypt::encryptString(conversation->confirmed_by) }}" />
                  </td> --}}
                  <td>
                    {{ ($conversations->currentPage() - 1) * $conversations->perPage() + $loop->index + 1 }}
                  </td>
                  <td>
                    @include('admin.supports.partials._user-info', [
                      'user' => $conversation->sender,
                      'conversation' => $conversation
                    ])
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
                        <span class="text-light-cyan">
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
                    @if($conversation->isSpammed())
                      <div class="text-danger">
                        Đã chặn spam: 
                        {{ $conversation->remainingSpammedTime() }}
                      </div>
                    @endif
                  </td>
                  <td>
                    <div>
                      <div class="mb-1">
                        <span class="icon-small-size mr-1">
                          <i class="fas fa-{{ $conversation->isEnded() ? 'sign-out-alt' : 'comments' }}"></i>
                        </span>
                        <a href="javascript:void(0);" class="support__open-conversation text-{{ $conversation->isEnded() ? 'danger' : 'success' }}" data-token="{{ $conversation->token }}">
                          {{ $conversation->isEnded() ? 'Xem hội thoại' : 'Phản hồi' }}
                        </a>
                        @if($conversation->getUnreadMessage(auth('admin')->user()))
                          <span class="badge badge-danger badge-danger">{{ $conversation->getUnreadMessage(auth('admin')->user()) }}</span>
                        @endif
                      </div>

                      @if($check_role == 1 || key_exists(5, $check_role))
                        <div class="mb-1">
                          <form action="{{ route('admin.conversations.spam', $conversation) }}" class="d-inline-block" method="POST">
                            @csrf
                            <span class="icon-small-size mr-1">
                              <i class="fas fa-times"></i>
                            </span>
                            <a href="javascript:void(0);" class="link-red-flat submit-accept-alert" data-action="chặn spam" title="Chặn spam">Chặn spam</a>
                          </form>
                        </div>
                        @if($conversation->isSpammed())
                          <div class="mb-1">
                            <form action="{{ route('admin.conversations.un-spam', $conversation) }}" class="d-inline-block" method="POST">
                              @csrf
                              <span class="icon-small-size mr-1">
                                <i class="fas fa-check"></i>
                              </span>
                              <a href="javascript:void(0);" class="text-success submit-accept-alert" data-action="bỏ chặn spam" title="Bỏ chặn spam">Bỏ chặn spam</a>
                            </form>
                          </div>
                        @endif
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            {{-- </form> --}}
          </tbody>
        </table>
      </div>
      <!-- /Main row -->
    </div>
    <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
      <div class="text-left d-flex align-items-center">
        <div class="manipulation d-flex mr-4 ">
          <img src="image/manipulation.png" alt="" id="btnTop">
          <div class="btn-group ml-1">
            <button type="button" class="btn dropdown-toggle dropdown-custom" data-toggle="dropdown"
              aria-expanded="false" data-flip="false" aria-haspopup="true">
              Thao tác
            </button>
            <div class="dropdown-menu">
              @if ($check_role == 1 || key_exists(5, $check_role))
                <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
                  <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"
                    style="color: white !important;font-size: 15px"></i>Thùng rác
                  <input type="hidden" name="action" value="trash">
                </a>
              @else
                <p>Không đủ quyền</p>
              @endif
            </div>
          </div>
        </div>

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
        {{-- @if ($check_role == 1 || key_exists(8, $check_role))
          <div class="view-trash">
            <a href="{{ route('admin.support.listtrash') }}" class=" text-primary"><i
                class="text-primary far fa-trash-alt"></i>
              Xem thùng rác</a>
            <span class="count-trash">{{ $trash_count }}</span>
          </div>
        @endif --}}
      </div>
      <div class="d-flex align-items-center">
        <div class="count-item">Tổng cộng: {{ $conversations->total() }} items</div>
        @if ($conversations)
          {{ $conversations->render('Admin.Layouts.Pagination') }}
        @endif
      </div>
    </div>


  </section>
@endsection

@section('Style')
@endsection

@section('Script')
  <script type="text/javascript"></script>
@endsection
