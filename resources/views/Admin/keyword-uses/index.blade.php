@extends('Admin.Layouts.Master')

@section('Title', 'Từ khóa nổi bật')

@section('Content')
  <x-admin.breadcrumb
    active-label="Từ khóa nổi bật"
    :parents="[
      [
        'label' => 'Admin',
        'route' => 'admin.thongke'
      ]
    ]"
  />
  <section class="content">
    <div class="container-fluid">
      <!-- Filter -->
      <div class="filter c-dashed-box mt-0">
        <h3 class="title">Bộ lọc</h3>

        <form action="{{ route('admin.keywords.index') }}" method="get" class="form-filter">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <x-common.text-input
                  label="Từ khóa"
                  name="keyword"
                  value="{{ request()->keyword }}"
                  placeholder="Nhập nội dung..."
                />
              </div>
            </div>

            <div class="col-md-6">
              <div class="row">
                <div class="col-md-6">
                  <x-common.select2-input
                    label="Tình trạng"
                    name="is_active"
                    :items="$statuses"
                    item-text="label"
                    item-value="value"
                    placeholder="Tất cả"
                    items-current-value="{{ request()->is_active }}"
                  />
                </div>
                @if($check_role == 1 || key_exists(7, $check_role))
                  <div class="col-md-6">
                    <x-common.select2-input
                      label="Lọc theo"
                      name="trashed"
                      :items="$deleteStatuses"
                      item-text="label"
                      item-value="value"
                      placeholder="Tất cả"
                      items-current-value="{{ request()->trashed }}"
                    />
                  </div>
                @endif
              </div>
            </div>
            <div class="col-md-3">
              <x-common.select2-input
                label="Loại"
                name="target_type"
                :items="$types"
                item-text="label"
                item-value="value"
                placeholder="Tất cả"
                items-current-value="{{ request()->target_type }}"
              />
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

      @if($check_role == 1 || key_exists(1, $check_role))
        <div class="flex-end mb-3">
          <a href="{{ route('admin.keywords.create') }}" class="btn btn-success btn-sm" title="Thêm">
            Thêm
            <i class="fas fa-plus"></i>
          </a>
        </div>
      @endif

      <div class="table table-responsive table-bordered table-hover">
        <table class="table">
          <thead class="thead-main">
            <tr>
              {{-- <th class="text-center"><input type="checkbox" class="select-all checkbox" name="select-all" /></th> --}}
              <th class="text-center">#</th>
              <th>Nội dung</th>
              <th class="text-center">Lượt tìm kiếm</th>
              <th class="text-center">Tình trạng</th>
              <th class="text-center">Ngày tạo</th>
              <th class="text-center">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <input type="hidden" name="action" id="action_list" value="">
            @foreach ($featuredKeywords as $keyword)
              <tr>
                {{-- <td class="text-center">
                  <input type="checkbox" class="select-item checkbox" name="select_item[]" value="{{$keyword->id}}" />
                  <input type="hidden" class="select-item checkbox" name="select_item_created[{{$keyword->id}}]" value="{{\Crypt::encryptString($keyword->confirmed_by)}}" />
                </td> --}}
                <td class="text-center">
                  {{ ($featuredKeywords->currentPage() - 1) * $featuredKeywords->perPage() + $loop->index + 1 }}
                </td>
                <td>
                  @switch($keyword->target_type)
                      @case(App\Models\Group::class)
                        <span>
                          <strong>Mô hình: </strong>
                          {{ data_get($keyword->featuredable, 'group_name') }}
                        </span>
                        @break
                      @case(App\Models\District::class)
                        <span>
                          <strong>Quận/Huyện: </strong>
                          {{ data_get($keyword->featuredable, 'district_name') }}
                        </span>
                        @break
                      @default
                  @endswitch
                </td>
                <td class="text-center">
                  {{ $keyword->views }}
                </td>
                <td class="text-center">
                  <span class="badge badge-{{ $keyword->is_active ? 'success' : 'danger' }} px-3">
                    {{ $keyword->is_active ? 'Hiện' : 'Ẩn' }}
                  </span>
                </td>
                <td class="text-center">
                  {{ $keyword->created_at->format('d-m-Y') }}
                </td>
                <td>
                  <div class="flex-end">
                    @if(!$keyword->deleted_at)
                      @if($check_role == 1 || key_exists(2, $check_role))
                        <a href="{{ route('admin.keywords.edit', $keyword) }}" class="btn btn-sm btn-info mb-2 mr-2" title="Chỉnh sửa">
                          <i class="fas fa-edit"></i>
                        </a>
                      @endif
                      @if($check_role == 1 || key_exists(5, $check_role))
                        <form action="{{ route('admin.keywords.delete', $keyword) }}" class="d-inline-block" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="btn btn-sm btn-danger mb-2 mr-2 submit-accept-alert" data-action="xóa" title="Xóa">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      @endif
                    @else
                      @if($check_role == 1 || key_exists(6, $check_role))
                        <form action="{{ route('admin.keywords.restore', $keyword) }}" class="d-inline-block" method="POST">
                          @csrf
                          @method('PUT')
                          <button type="button" class="btn btn-sm btn-success mb-2 mr-2 submit-accept-alert" data-action="khôi phục" title="Khôi phục">
                            <i class="fas fa-redo"></i>
                          </button>
                        </form>
                      @endif

                      <x-admin.force-delete-button
                        :check-role="$check_role"
                        id="{{ $keyword->id }}"
                        url="{{ route('admin.keywords.force-delete-multiple') }}"
                        is-button
                      />
                    @endif
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <form action="" class="force-delete-item-form d-none" method="POST">
        @csrf
        <input type="hidden" name="ids">
      </form>

      <!-- /Main row -->
      <div class="table-bottom d-flex align-items-center justify-content-between mb-4  pb-5">
        <div class="text-left d-flex align-items-center">
          <div class="display d-flex align-items-center mr-4">
            <a href="javascript:void(0);" class="mr-2">
              <img src="{{ asset('/system/image/manipulation.png') }}" class="js-go-to-top cursor-pointer py-1"
                title="Về đầu trang" alt="">
              </a>
            <span class="mr-2">Hiển thị:</span>
            <form method="get" id="paginateform" action="{{ route('admin.classified.list') }}">
              <select class="custom-select" id="paginateNumber" name="items">
                <option @if (request()->items == 10) {{ 'selected' }} @endif value="10">
                  10
                </option>
                <option @if (request()->items == 20) {{ 'selected' }} @endif value="20">
                  20
                </option>
                <option @if (request()->items == 30) {{ 'selected' }} @endif value="30">
                  30
                </option>
              </select>
            </form>
          </div>
          {{-- @if ($check_role == 1 || key_exists(8, $check_role))
            <div class="view-trash">
              <a href="{{ route('admin.classified.listtrash') }}" class=" text-primary"><i
                  class="text-primary far fa-trash-alt"></i>
                Xem thùng rác</a>
              <span class="count-trash">{{ $trash_count }}</span>
            </div>
          @endif --}}
        </div>
        <div class="d-flex align-items-center">
          <div class="count-item">Tổng cộng: {{ $featuredKeywords->total() }} mục</div>
          @if ($featuredKeywords)
            {{ $featuredKeywords->render('Admin.Layouts.Pagination') }}
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection
