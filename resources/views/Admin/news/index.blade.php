@extends('Admin.Layouts.Master')

@section('Title', 'Tiêu điểm')

@section('Content')
  <x-admin.breadcrumb active-label="Tiêu điểm" :parents="[
      [
          'label' => 'Admin',
          'route' => 'admin.thongke',
      ],
  ]" />
  <section class="content">
    <div class="container-fluid">
      <div class="filter c-dashed-box mt-0">
        <h3 class="title">Bộ lọc</h3>

        <form action="" class="form-filter">
          <input type="hidden" name="items" value="{{ request()->items }}">
          <div class="row">
            <div class="col-lg-3 col-md-4">
              <div class="form-group">
                <x-common.text-input label="Từ khóa" name="keyword" value="{{ request()->keyword }}"
                  placeholder="Từ khóa..." />
              </div>
            </div>

            <div class="col-lg-3 col-md-4">
              <x-common.select2-input label="Người tạo" name="author_id" :items="$authors" item-text="admin_fullname"
                placeholder="Tất cả" items-current-value="{{ request()->author_id }}" />
            </div>

            <div class="col-lg-3 col-md-4">
              <x-common.text-input label="Ngày tạo" :type="request()->start_day ? 'date' : 'text'" name="start_day" value="{{ request()->start_day }}"
                placeholder="Từ" hover-date />
            </div>

            <div class="col-lg-3 col-md-4">
              <x-common.text-input class="mt-4 pt-1" :type="request()->end_day ? 'date' : 'text'" name="end_day" value="{{ request()->end_day }}"
                placeholder="Đến" hover-date />
            </div>

            <div class="col-lg-3 col-md-4">
              <x-common.select2-input label="Chuyên mục" name="group_id" :items="$groups" item-text="group_name"
                placeholder="Tất cả" items-current-value="{{ request()->group_id }}" />
            </div>

            <div class="col-lg-3 col-md-4">
              <x-common.select2-input label="Loại bài viết" name="type" :items="$types" item-text="label"
                item-value="value" placeholder="Tất cả" items-current-value="{{ request()->type }}" />
            </div>

            <div class="col-lg-3 col-md-4">
              <x-common.select2-input label="Tình trạng" name="is_active" :items="$statuses" item-text="label"
                item-value="value" placeholder="Tất cả" items-current-value="{{ request()->is_active }}" />
            </div>
            @if ($check_role == 1 || key_exists(8, $check_role))
              <div class="col-lg-3 col-md-4">
                <x-common.select2-input label="Lọc theo" name="trashed" :items="$deleteStatuses" item-text="label"
                  item-value="value" placeholder="Tất cả" items-current-value="{{ request()->trashed }}" />
              </div>
            @endif
          </div>

          <div class="text-center py-3">
            <a href="{{ route('admin.news.index') }}" class="btn btn-warning rounded-0">
              <i class="fas fa-times"></i>
              Xóa bộ lọc
            </a>
            <button type="submit" class="btn btn-cyan rounded-0">
              <i class="fas fa-search"></i>
              Tìm kiếm
            </button>
          </div>
        </form>
      </div>

      @if ($check_role == 1 || key_exists(1, $check_role))
        <div class="flex-end mb-3">
          <a href="{{ route('admin.news.create') }}" class="btn btn-success btn-sm" title="Thêm">
            Thêm
            <i class="fas fa-plus"></i>
          </a>
        </div>
      @endif

      <div class="table table-responsive table-vertical table-hover">
        <table class="table table-bordered">
          <thead class="thead-main">
            <tr>
              <th class="text-center"><input type="checkbox" class="select-all checkbox" name="select-all" /></th>
              <th class="text-center">#</th>
              <th>Hình ảnh</th>
              <th class="text-center" style="width: 25%">Tiêu đề</th>
              <th class="text-center">Tình trạng</th>
              <th class="text-center">Tác giả</th>
              <th class="text-center">Chuyên mục</th>
              <th class="text-center">Làm mới</th>
              <th class="text-center">Ngày đăng</th>
              <th class="text-right">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <input type="hidden" name="action" id="action_list" value="">
            @foreach ($news as $new)
              <tr>
                <td class="text-center">
                  <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{ $new->id }}" />
                  <input type="hidden" class="select-item checkbox" name="select_item_created[{{ $new->id }}]"
                    value="{{ \Crypt::encryptString($new->confirmed_by) }}" />
                </td>
                <td class="text-center">
                  {{ ($news->currentPage() - 1) * $news->perPage() + $loop->index + 1 }}
                </td>
                <td>
                  @if ($new->image_url)
                    <a class="d-inline-block" data-fancybox="image_{{ $new->id }}"
                      data-src="{{ $new->getImageUrl() }}" style="width:120px;height:90px;">
                      <img class="object-cover" src="{{ $new->getImageUrl() }}" />
                    </a>
                  @endif
                </td>
                <td>
                  @if ($new->is_show && $new->group)
                    <a href="{{ $new->group ? route('home.focus.detail', [$new->group->group_url, $new->news_url]) : 'javascript:void(0);' }}"
                      class="link" target="__blank">{{ $new->news_title }}</a>
                  @else
                    <div class="text-main">
                      {{ $new->news_title }}
                    </div>
                  @endif
                  <div class="text-muted pt-2">
                    <div class="flex-start">
                      <i class="fas fa-edit mr-2"></i>
                      <span>Số chữ: <span class="text-light-cyan">{{ strlen(strip_tags($new->news_content)) }}</span>
                      </span>
                    </div>
                    <div class="flex-start">
                      <i class="fas fa-signal mr-2"></i>
                      <span class="mt-1">Lượt xem: <span
                          class="text-light-cyan">{{ $new->num_view ?: 0 }}</span></span>
                    </div>
                  </div>
                </td>
                <td class="text-center">
                  @if ($new->is_deleted)
                    <span class="badge badge-danger px-3">
                      đã xóa
                    </span>
                  @else
                    <span class="badge badge-{{ $new->is_show ? 'success' : 'danger' }} px-3">
                      {{ $new->is_show ? 'Hiện' : 'Ẩn' }}
                    </span>
                  @endif
                  @if ($new->isAds())
                    <br>
                    <span class="badge badge-info px-3">
                      Quảng cáo
                    </span>
                  @endif
                  @if ($new->isHighlight())
                    <br>
                    <span class="badge badge-primary px-3">
                      Nổi bật
                    </span>
                  @endif
                </td>
                <td class="text-center">
                  {{ data_get($new->admin, 'admin_fullname') }}
                </td>
                <td class="text-center">
                  {{ data_get($new->group, 'group_name') }}
                </td>
                <td class="text-center">
                  <span>{{ $new->renew_at ? $new->renew_at->format('d/m/Y H:i:s') : '' }}</span>
                </td>
                <td class="text-center">
                  <span>{{ date('d/m/Y', $new->created_at) }}</span>
                </td>
                <td>
                  <div class="flex-column">
                    @if (!$new->is_deleted)
                      <form action="{{ route('admin.news.changeShow', $new) }}" class="d-inline-block text-left ml-2" method="POST">
                        @csrf
                        <button type="button" class="btn btn-link {{ $new->is_show ? 'link-red-flat' : 'link-flat' }} btn-sm p-0 submit-accept-alert"
                          data-action="{{ $new->is_show ? 'ẩn' : 'hiện' }}" title="{{ $new->is_show ? 'Ẩn' : 'Hiện' }}">
                          <span class="icon-small-size mr-1 text-dark">
                            <i class="fas fa-eye{{ $new->is_show ? '-slash' : '' }}"></i>
                          </span>
                          {{ $new->is_show ? 'Ẩn' : 'Hiện' }}
                        </button>
                      </form>

                      @if($check_role == 1  ||key_exists(2, $check_role))
                        <div class="ml-2">
                          <span class="icon-small-size mr-1 text-dark">
                            <i class="fas fa-cog"></i>
                          </span>
                          <a href="{{ route('admin.news.edit', $new) }}" class="text-primary ">Chỉnh sửa</a>
                        </div>
                      @endif

                      <x-admin.delete-button
                        :check-role="$check_role"
                        url="{{ route('admin.news.delete-multiple', ['ids' => $new->id]) }}"
                      />

                      <div>
                        <a class="btn btn-sm btn-link link-flat ml-2 p-0 dropdown dropdown-toggle" data-toggle="dropdown"
                          id="dropdownMenuButtonda" title="Nâng cấp">
                          <span class="icon-small-size mr-1 text-dark">
                            <i class="fa fa-arrow-circle-up"></i>
                          </span>
                          Nâng cấp
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonda">
                          @if (!$new->isHighlight())
                            <form action="{{ route('admin.news.highlight', $new) }}"
                              class="d-inline-block w-100 text-left hover-bg-gray" method="POST">
                              @csrf
                              <button type="button"
                                class="text-primary btn btn-link submit-accept-alert btn-block text-left"
                                data-action="nổi bật" title="Nổi bật">
                                Nổi bật
                              </button>
                            </form>
                          @endif
                          @if (!$new->isAds())
                            <form action="{{ route('admin.news.express', $new) }}"
                              class="d-inline-block w-100 text-left hover-bg-gray" method="POST">
                              @csrf
                              <button type="button"
                                class="text-info btn btn-link submit-accept-alert btn-block text-left"
                                data-action="quảng cáo" title="Quảng cáo">
                                Quảng cáo
                              </button>
                            </form>
                          @else
                            <form action="{{ route('admin.news.unExpress', $new) }}"
                              class="d-inline-block w-100 text-left hover-bg-gray" method="POST">
                              @csrf
                              <button type="button"
                                class="text-danger btn btn-link submit-accept-alert btn-block text-left"
                                data-action="hủy quảng cáo" title="Hủy quảng cáo">
                                Hủy quảng cáo
                              </button>
                            </form>
                          @endif
                        </div>
                      </div>
                    @else
                      <x-admin.restore-button
                        :check-role="$check_role"
                        url="{{ route('admin.news.restore-multiple', ['ids' => $new->id]) }}"
                      />
                      <x-admin.force-delete-button
                        :check-role="$check_role"
                        url="{{ route('admin.news.force-delete-multiple', ['ids' => $new->id]) }}"
                      />
                    @endif
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <x-admin.table-footer
        :check-role="$check_role"
        :lists="$news"
        delete-url="{{ route('admin.news.delete-multiple') }}"
        force-delete-url="{{ route('admin.news.force-delete-multiple') }}"
        restore-url="{{ route('admin.news.restore-multiple') }}"
      />
    </div>
  </section>
@endsection

@section('Script')
  <script src="{{ asset('system/js/table.js') }}" type="text/javascript"></script>
@endsection
