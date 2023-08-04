@extends('user.layouts.master')

@section('title', 'Danh sách khách hàng | Quản lý khách hàng')

@section('content')
    <section class="content customer-managements position-relative">
        <div class="container-fluid customer-management-content">
            <div class="filter block-dashed">
                <h3 class="title">Tìm khách hàng theo</h3>
                <form action="{{route('user.customer')}}" method="get" class="form-filter">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Họ tên</label>
                            <input type="text" name="fullname" class="form-control " value="{{request()->fullname}}" placeholder="Nhập họ tên">
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone_number" class="form-control" value="{{request()->phone_number}}" placeholder="Nhập số điện thoại">
                        </div>

                        <div class="col-md-2 form-group">
                            <label>Tình trạng khách hàng</label>
                            <select name="status" class="custom-select cs-select" data-placeholder="Chọn tình trạng">
                                {{show_select_option($status, 'id', 'param_name', 'status',request()->status)}}
                            </select>
                        </div>

                        <div class="col-md-2 form-group">
                            <label>Từ ngày</label>
                            <input type="date" name="from_date" class="form-control" placeholder="Từ ngày" value="{{request()->from_date}}">
                        </div>

                        <div class="col-md-2 form-group">
                            <label>Đến ngày</label>
                            <input type="date" name="to_date" class="form-control" placeholder="Đến ngày" value="{{request()->to_date}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Nghề Nghiệp</label>
                            <select name="job" class="custom-select cs-select" data-placeholder="Chọn nghề nghiệp">
                                {{show_select_option($jobs, 'id', 'param_name', 'job', request()->job)}}
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Tỉnh/thành phố</label>
                            <select name="province" class="custom-select province cs-select" data-placeholder="Chọn tỉnh/ thành phố">
                                {{show_select_option($provinces, 'id', 'province_name', 'province', request()->province)}}
                            </select>
                        </div>

                        <div class="col-md-2 form-group">
                            <label>Quận/huyện</label>
                            <select name="district" class="custom-select district cs-select" data-placeholder="Chọn quận/huyện">
                                {{show_select_option($districts, 'id', 'district_name', 'district', request()->district)}}
                            </select>
                        </div>

                        <div class="col-md-2 form-group">
                            <label>Nguồn phát sinh</label>
                            <select name="source" class="custom-select cs-select" data-placeholder="Chọn nguồn">
                                {{show_select_option($sources, 'id', 'param_name', 'source', request()->source)}}
                            </select>
                        </div>
                        
                        <div class="col-md-2 form-group" style="margin-top: 28px">
                            <button type="submit" style="min-height: 32.5px" class="btn bg-blue-light w-100"><i class="fas fa-search mr-3"></i>Tìm kiếm</button>
                        </div>
                    </div>
            </form>
            </div>
        <div class="title-more-customers">
            <a href="#" data-toggle="modal" data-target="#create-customer">Thêm khách hàng <i class="fas fa-plus-circle"></i></a>
        </div>
        <div class="table-responsive more-customers-table p-3">
            <table class="table">
                <thead>
                <tr>
                    <th class="stt">STT</th>
                    <th class="w-350px">Tên Khách hàng</th>
                    <th>Ngày thêm</th>
                    <th>Nguồn</th>
                    <th>Tình trạng</th>
                    <th>Ghi chú</th>
                    <th class="setting">Cài đặt</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($customers as $index => $customer)
                        <tr>
                            <td class="text-center">{{($customers->currentPage()-1)*5 + $index + 1}}</td>
                            <td>
                                <div class="user-info text-center">
                                    <x-user.avatar
                                        image-class="cus_avatar"
                                        width="60"
                                        height="60"
                                        rounded="30"
                                        avatar="{{ asset($customer->image_url??SystemConfig::REPLACE_AVATAR_IMAGE) }}"
                                    />
                                    {{-- <div class="avatar">
                                        <img class='cus_avatar' src="{{asset($customer->image_url??SystemConfig::REPLACE_AVATAR_IMAGE)}}" alt="">
                                    </div> --}}
                                    <span class="id d-none">{{ $customer->id }}</span>
                                    <div class="more-customer-information">
                                        <a href="#" class="user-name d-block fullname">{{$customer->fullname}}</a>
                                        <p class="job mb-0" data-job="{{$customer->job}}">{{$customer->job_name}}</p>
                                        <p class="mb-0 text-secondary fs-14">{{date('d/m/Y', $customer->birthday)}}</p>
                                        <input type="text" class="birthday" value="{{date('Y-m-d',$customer->birthday)}}" hidden>
                                    </div>
                                    <div class="address-information fs-14">
                                        <div class="list-contact">
                                            <div class="item mb-2">
                                                <div class="info">
                                                    <x-user.phone-number :phone="$customer->phone_number" class="link-red-flat phone__copy-small phone__copy-link">
                                                        <x-slot name="icon">
                                                          <div class="mr-2">
                                                            <i class="fas fa-phone-alt"></i>
                                                          </div>
                                                        </x-slot>
                                                    </x-user.phone-number>
                                                    <span class="phone-number d-none"> {{ $customer->phone_number }}</span>
                                                </div>
                                            </div>
                                            <div class="item mb-2">
                                                <div class="info">
                                                    <i class="fas fa-envelope mr-2"></i>
                                                    <a class="black email" href="mailto:{{auth()->guard('user')->user()->email}}"> {{$customer->email}}</a>
                                                </div>
                                            </div>
                                            @if($customer->location)
                                                <div class="item mb-2">
                                                    <div class="info">
                                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                                        <span class="address"> {{$customer->address}}</span>
                                                        <span class="district" data-district="{{$customer->district_id}}">, {{$customer->district_name}}</span>
                                                        <span class="province" data-province="{{$customer->province_id}}">, {{$customer->province_name}}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($customer->classified)
                                            <div class="item mb-2">
                                                <div class="info">
                                                    <i class="fas fa-paperclip mr-2"></i>Đường dẫn đăng ký:
                                                    <a class="text-light-cyan" target="_blank" href="{{ $customer->classified->getShowUrl() ?: 'javascript:void(0);' }}">
                                                        {{ $customer->classified_name }}
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{vn_date($customer->created_at)}}</td>
                            <td class="text-center">
                                <a class="source text-color-{{ strtolower($customer->source) }}" data-source="{{$customer->cus_source}}">
                                    {{ $customer->source }}
                                </a>
                            </td>
                            <td class="cus_status text-center" data-status="{{$customer->cus_status}}">{{$customer->status}}</td>
                            {{-- <td class="note-customer cus_note"> {{$customer->note}}</td> --}}
                            <td class="note-customer">
                                <span class="cus_note">{{ $customer->note }}</span>
                                <span data-toggle="modal" data-target="#customer-edit-note-{{ $customer->id }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-plus"></i>
                                </span>
                                @include('user.customer.partials._edit-note-popup', [
                                    'customer' => $customer
                                ])
                            </td>
                            <td>
                                <a href="#" class="setting-item text-light-cyan edit mb-2 edit-customer"  data-toggle="modal" data-target="#edit-customer"><i class="fas fa-cog"></i> Chỉnh sửa</a>
                                <a href="{{route('user.delete-customer', $customer->id)}}" class="setting-item text-red mb-2 delete-alert"> <i class="fas fa-times"></i> Xóa</a>
    {{--                            <a href="#" class="setting-item send-mail mb-2" data-toggle="modal" data-target="#modalSendMail2"><i class="fas fa-envelope-square"></i>Gửi mail</a>--}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-pagination">
            <div class="left"></div>
            <div class="right">
                {{ $customers->render('user.page.pagination') }}
            </div>
        </div>
        </div>
        <!-- modal them khách hang -->
        @include('user.customer.popup.create-customer')
        <!-- modal chinh sua -->
        @include('user.customer.popup.edit-customer')
        <!-- popup guimail -->
        @include('user.customer.popup.send-mail-customer')
    </section>
@endsection

@section('script')
    <script src="{{asset('user/js/customer.js')}}"></script>
@endsection
