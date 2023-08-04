@extends('user.layouts.master')
@section('title', 'Danh sách tin đăng')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('content')
    <div class="content-right-account-business">
        @if($warning_forbidden_word)
            <div class="alert alert-warning" role="alert">
                <p>Tin của bạn không được duyệt vì chứa <strong class="text-danger cursor-pointer" data-title="{{ 'Các từ vi phạm: ' . implode(', ', data_get($warning_forbidden_word, 'options.forbidden_words', [])) }}">từ ngữ bị cấm</strong>. vui lòng kiểm tra lại và thay đổi nội dung. Lưu ý nếu đăng 3 tin chứa <strong class="text-danger cursor-pointer" data-title="các từ ngữ bị cấm bao gồm: các từ ngữ thô tục không hợp với văn hóa, các mặt hàng không liên quan đến bất động sản như quần áo, giày dép….)">từ ngữ bị cấm</strong> tài khoản đăng tin sẽ bị cấm đăng trong 1 tuần. 1 tuần sau nếu tiếp tục vi phạm tài khoản sẽ bị khóa. Nếu phản hồi này sai vui lòng liên hệ admin để được hỗ trợ hotline: <a class="font-weight-bold text-success" href="tel:0909992638">0909992638</a></p>
            </div>
        @endif

        <div class="content-account-business">
            <div class="search-event-top-list">
                <div class="title-list-post">Tìm kiếm theo </div>
                <form action="{{route('user.list-classified')}}" method="get">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row group-load-paradigm">
                                <div class="col-md-3">
                                    <x-common.select2-input
                                        label="Chuyên mục"
                                        input-class="category-load-paradigm"
                                        name="parent"
                                        :items="$parent"
                                        item-text="group_name"
                                        placeholder="Chọn chuyên mục"
                                        items-current-value="{{ request()->parent }}"
                                        data-selected="{{ request()->parent }}"
                                        with-child="{{ false }}"
                                    />
                                </div>
                                <div class="col-md-3">
                                    <x-common.select2-input
                                        label="Mô hình"
                                        input-class="paradigm-category"
                                        name="paradigm"
                                        :items="$paradigm"
                                        item-text="group_name"
                                        placeholder="Chọn mô hình"
                                        items-current-value="{{ request()->paradigm }}"
                                        data-selected="{{ request()->paradigm }}"
                                        with-child="{{ false }}"
                                    />
                                </div>
                                <div class="col-md-2">
                                    <label>Loại tin</label>
                                    <select name="classified_type" class="cs-select form-select form-control" data-placeholder="Chọn loại tin">
                                        <option value="0" {{ !request()->classified_type ? 'selected' : '' }}>-- Loại tin --</option>
                                        <option value="1" {{request()->classified_type == 1?'selected':null}}>Tin VIP</option>
                                        <option value="2" {{request()->classified_type == 2?'selected':null}}>Tin nổi bật</option>
                                        <option value="3" {{request()->classified_type == 3?'selected':null}}>Tin thường</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Từ ngày </label>
                                    <input type="date" name="date_from" class="form-control" value="{{request()->date_from}}">
                                </div>
                                <div class="col-md-2">
                                    <label>Đến ngày</label>
                                    <input type="date" name="date_to" class="form-control" value="{{request()->date_to}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tình trạng</label>
                                    <select name="progress" class="cs-select form-select form-control" data-placeholder="Chọn tình trạng">
                                        {{show_select_option($progress, 'id', 'progress_name', 'progress',request()->progress)}}
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Mã tin</label>
                                    <input type="text" name="classified_code" class="form-control" value="{{request()->classified_code}}">
                                </div>
                                <div class="col-md-4">
                                    <label>Nhập từ khóa</label>
                                    <input type="text" name="classified_name" class="form-control" value="{{request()->classified_name}}">
                                </div>
                                <div class="col-md-2 button-search--list pt-3">
                                    <button type="submit" class="btn btn-list-success"><i class="fas fa-search"></i>&nbsp;Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <p class="mt-2 text-muted" style="font-style: italic" >( Bộ lọc sẽ không hoạt động khi tìm kiếm bằng mã tin )</p>
            <div class="table-account-business">
                <table class="table" border="1">
                    <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="select-all checkbox">
                        </th>
                        <th>Tình trạng</th>
                        {{-- <th>Đã hiện</th> --}}
                        <th>Tiêu đề </th>
                        <th class="set-widt">Chuyên mục</th>
                        <th>Ngày hết hạn</th>
                        <th class="set-widt">Nhận tư vấn </th>
                        <th class="fixed">Cài đặt</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($classifieds as $item)
                        <tr>
                            <td class="td-checkbox">
                                <input type="checkbox" value="{{ $item->id }}" class="select-item checkbox" name="select_item[]" />
                            </td>
                            @if($item->confirmed_status == 0)
                                <td class="status-account yellow text-center">
                                    <span class="bg-white p-1 fs-14 px-2 py-1 rounded">Chờ duyệt</span>
                                </td>
                            @elseif($item->confirmed_status == 1)
                                @if($item->expired_date < time())
                                    <td class="status-account brow text-center">
                                        <span class="bg-white p-1 fs-14 px-2 py-1 rounded">Hết hạn</span>
                                    </td>
                                @else
                                    <td class="status-account text-center">
                                        <span class="bg-white p-1 fs-14 px-2 py-1 rounded">Đã duyệt</span>
                                    </td>
                                @endif
                            @else
                                <td class="status-account pink text-center">
                                    <span class="bg-white p-1 fs-14 px-2 py-1 rounded">{{ $item->confirmed_status == 3 ? 'Chứa từ cấm' : 'Không duyệt' }}</span>
                                </td>
                            @endif
                            {{-- <td class="text-center">
                                <span class="badge badge-{{ $item->is_show ? 'success' : 'danger' }}">
                                    <i class="fas fa-{{ $item->is_show ? 'check' : 'times' }}"></i>
                                </span>
                            </td> --}}
                            <td>
                                <div class="name-business-post">
                                    @if($item->isHighLight())
                                        <img src="{{ asset('user/images/icon/new.gif') }}" class="small-hot-icon mr-1" alt="">
                                    @endif
                                    <a href="{{ $item->getShowUrl() ?: 'javascript:void(0);' }}" class="{{ $item->isVip() || $item->isHighLight() ? 'link-red-flat' : '' }}">
                                        {{  strlen($item->classified_name) > 40 ? substr($item->classified_name, 0, 39) . '...' : $item->classified_name  }}
                                    </a>
                                </div>
                                <div class="list-item-name-business-account">
                                    <div class="item-name-business-account">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        <span>Đăng ngày: {{vn_date($item->active_date)}}</span>
                                    </div>
                                    <div class="item-name-business-account">
                                        <i class="fa fa-area-chart" aria-hidden="true"></i>
                                        <span>Tổng lượt xem: {{$item->num_view}}</span>
                                    </div>
                                    <div class="item-name-business-account">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <span>Lượt xem trong ngày: {{$item->num_view_today}}</span>
                                    </div>
                                </div>
                            </td>
                            <td><div class="info-account-create">{{$item->group_name}}</div></td>
                            <td><div class="info-account-create">{{vn_date($item->expired_date)}}</div></td>
                            <td><div class="info-account-create">{{ $item->customers->count() }}</div></td>
                            <td>
                                <div class="list-item-setting">
                                    @if($item->canUpgrade())
                                    <div class="item-setting-account">
                                        <img src="{{asset('user/images/icon/new.gif')}}" class="img-hot mr-1" alt="">
                                        <a
                                            href="{{ route('user.classified.upgrade', $item) }}" class="hot-title submit-upgrade"
                                        >
                                            Nâng cấp tin VIP
                                            <span class="text-red bg-transparent  m-0 p-0">({{$service->where('id', 5)->first()->service_coin}} coins)</span>
                                        </a>
                                    </div>
                                    @endcan
                                    @if($item->canRenew())
                                    <div>
                                        <form action="{{ route('user.classified.renew', $item) }}" class="d-inline-block" method="POST">
                                            @csrf
                                            <span class="icon-small-size mr-1">
                                                <i class="fas fa-redo"></i>
                                            </span>
                                            <button class="submit-accept-alert btn btn-link link fw-500 p-0" title="Nâng cấp" type="button" data-action="làm mới tin">
                                                Làm mới tin
                                                <span class="text-red bg-transparent m-0 p-0">({{$service->where('id', 4)->first()->service_coin}} coins)</span>
                                            </button>
                                        </form>
                                    </div>
                                    @endcan
                                    @if ($item->canEdit())
                                    <div>
                                        <span class="icon-small-size mr-1">
                                            <i class="fas fa-cog"></i>
                                        </span>
                                        <a href="{{route('user.edit-classified', $item->id)}}" class="link-flat">Chỉnh sửa</a>
                                    </div>
                                    @endif
                                    <div>
                                        <span class="icon-small-size mr-1">
                                            <i class="fas fa-times"></i>
                                        </span>
                                        <a href="{{route('user.delete-classified', $item->id)}}" class="link-red-flat delete-alert">Xóa</a>
                                    </div>
                                    {{-- <div class="item-setting-account ">
                                        <i class="far fa-comment-dots"></i>
                                        <a href="#" data-toggle="modal" data-target="#modalNotify">Thông báo</a><span>2</span>
                                    </div> --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <form class="d-none" id="upgrade-classified-form" action="" method="POST">
                    @csrf
                </form>
                <form action="{{ route('user.classified.delete-multiple') }}" class="delete-item-form d-none" method="POST">
                    @csrf
                    <input type="hidden" name="ids">
                </form>
            </div>
            <div class="group-option-eventlist table-bottom d-flex flex-wrap align-items-center justify-content-between mb-4 py-3">
                <div class="text-left d-flex align-items-center flex-wrap group-option-top mb-2">
                    <div class="manipulation d-flex mr-2 p-2">
                        {{-- <img src="/system/image/manipulation.png" alt="" id="btnTop"> --}}
                        <img src="{{ asset('/images/icons/redo.png') }}" class="js-go-to-top cursor-pointer rotate-90 mr-2"
                            title="Về đầu trang" alt="">

                        <div class="btn-group ml-1">
                            <button type="button" class="btn btn-blue dropdown-toggle dropdown-custom" data-toggle="dropdown"
                                aria-expanded="false" data-flip="false" aria-haspopup="true">
                                Thao tác
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item moveToTrash" type="button" href="javascript:{}">
                                    <i class="fas fa-trash-alt bg-red p-1 mr-2 rounded"></i>
                                    Thùng rác
                                    <input type="hidden" name="action" value="trash">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center justify-content-between mx-4 p-2">
                        <div class="d-flex mr-2 align-items-center">Hiển thị</div>

                        <form action="{{ route('user.list-classified') }}" method="GET">
                            <label class="select-custom2">
                                <select id="paginateNumber" name="items" onchange="submitPaginate(event, this)">
                                    <option @if (request()->items == 10) {{ 'selected' }} @endif value="10">10</option>
                                    <option @if (request()->items == 20) {{ 'selected' }} @endif value="20">20</option>
                                    <option @if (request()->items == 30) {{ 'selected' }} @endif value="30">30</option>
                                </select>
                            </label>
                        </form>
                    </div>
                    {{-- <div class="view-trash p-2">
                        <a class="fs-normal" href="javascript:void(0);">
                            <span class="mr-2 text-dark">
                                <i class="far fa-trash-alt"></i>
                            </span>
                            <span class="link-light-cyan text-underline mr-1">
                                Xem thùng rác
                            </span>
                        </a>
                        <span class="badge badge-danger badge-right badge-sm rounded-circle">{{ $countTrash }}</span>
                    </div> --}}
                </div>

                <div class="bottom-account-business mb-2">
                    <div class="row">
                        <div class="col-12 pagenate-bottom">
                            {{ $classifieds->render('user.page.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('system/js/table.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $('.submit-upgrade').click(function (event){
            event.preventDefault();
            Swal.fire({
                title: 'Xác nhận thao tác',
                text: "Nhấn đồng ý thì sẽ tiến hành thao tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#upgrade-classified-form').attr('action', $(event.target).attr('href'))
                    $('#upgrade-classified-form').submit()
                }
                else {
                    return false;
                }
            });

        });

        function submitPaginate(event) {
            const uri = window.location.toString();
            const exist = uri.indexOf('?')
            const existItems = uri.indexOf('?items')
            const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
            exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $('#paginateNumber').val() :
            window.location.href = uri.replace(re, '') + '?items=' + $('#paginateNumber').val()
        }

        (() => {
            function deleteItem(id) {
                if (!id) return
                Swal.fire({
                    title: 'Xác nhận xóa',
                    text: "Sau khi xóa sẽ chuyển vào thùng rác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'Quay lại',
                    confirmButtonText: 'Đồng ý'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('.delete-item-form input[name="ids"]').val(id)
                        $('.delete-item-form').trigger('submit')
                    }
                })
            }

            $(document).ready(function() {
                // remove click
                $('.setting-item.delete').click(function(e) {
                    e.preventDefault()
                    deleteItem($(this).data('id'))
                })

                // move to trash
                $('.dropdown-item.moveToTrash').click(function() {
                    const selectedArray = getSelected();
                    if (selectedArray) {
                    Swal.fire({
                        title: 'Xác nhận xóa',
                        text: "Sau khi xóa sẽ chuyển vào thùng rác!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        cancelButtonText: 'Quay lại',
                        confirmButtonText: 'Đồng ý'
                    }).then((result) => {
                            if (result.isConfirmed) {
                                let ids = [];
                                selectedArray.forEach(element => {
                                    if ($(element).val())
                                    ids.push($(element).val())
                                })

                                $('.delete-item-form input[name="ids"]').val(ids)
                                $('.delete-item-form').trigger('submit')
                            }
                        });
                    }
                })
                });
        }) ()
    </script>
@endsection
