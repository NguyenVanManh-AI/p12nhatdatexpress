@extends('user.layouts.master')

@section('title', 'Đăng tin mới')

@section('css')
    <style>
        .inp-day {
            width: 50px!important;
            height: 30px!important;
            font-size: 1.1rem;
        }

    </style>
@endsection
@section('content')
    <div class="p-3 border-bottom border-white">
        <div class="row">
            <div class="col-md-4 type-postcol">
                <div class="content-type-post  {{request()->group == 'nha-dat-ban'?'color':null}}">
                    <a href="{{route('user.add-classified', 'nha-dat-ban')}}"><img src="{{asset('frontend/user/images/house.png')}}" alt=""></a>
                </div>
                <div class="title-type-post {{request()->group == 'nha-dat-ban'?'color':null}}">
                    <h5><a href="{{route('user.add-classified', 'nha-dat-ban')}}">Đăng tin nhà đất bán</a></h5>
                </div>
            </div>
            <div class="col-md-4 type-postcol">
                <div class="content-type-post  {{request()->group == 'nha-dat-cho-thue'?'color':null}}">
                    <a href="{{route('user.add-classified', 'nha-dat-cho-thue')}}"><img src="{{asset('frontend/user/images/house2.png')}}" alt=""></a>
                </div>
                <div class="title-type-post {{request()->group == 'nha-dat-cho-thue'?'color':null}}">
                    <h5><a href="{{route('user.add-classified', 'nha-dat-cho-thue')}}">Đăng tin nhà đất cho thuê</a></h5>
                </div>
            </div>
            <div class="col-md-4 type-postcol">
                <div class="content-type-post  {{request()->group == 'can-mua-can-thue'?'color':null}}">
                    <a href="{{route('user.add-classified', 'can-mua-can-thue')}}"><img src="{{asset('frontend/user/images/house3.png')}}" alt=""></a>
                </div>
                <div class="title-type-post {{request()->group == 'can-mua-can-thue'?'color':null}}">
                    <h5><a href="{{route('user.add-classified', 'can-mua-can-thue')}}">Đăng tin cần mua/cần thuê</a></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="form-post-land classified-page js-change-address-load-map js-has-map-address" data-group="{{ request()->group }}">
        <form action="{{route('user.post-add-classified', request()->group)}}" id="add-classified" class="classified-post classified-page__form" method="post" enctype="multipart/form-data">
            @csrf
            @include('user.classified.partials._title-form', [
                'group' => $group,
                'guide' => $guide,
                'classified' => $classified,
            ])

            <div class="search-project-land">
                <div class="form-search-project-land">
                    <div class="row">
                        @include('user.classified.partials._project-form', [
                            'project' => $project,
                            'group' => $group,
                            'group_parent_id' => null,
                            'classified' => $classified,
                            'location' => new \App\Models\Classified\ClassifiedLocation,
                            'account_verify' => $account_verify
                        ])
                    </div>

                    <div class="map-form-land">
                        <div class="row">
                            <div class="col-12 col-md-8">
                                <div class="mb-3">
                                    <x-common.map
                                        id="classified-page__map"
                                        lat-name="latitude"
                                        long-name="longtitude"
                                        lat-value="{{ old('latitude') }}"
                                        long-value="{{ old('longtitude') }}"
                                    />
                                </div>
                                <div>
                                    <div class="upload-image-land mb-3">
                                        <div class="title-upload-img-land">
                                            <div class="block upload pt-0"  >
                                                <div class="upload-item mr-0">
                                                    <div class="wrap-out bg-white p-3">
                                                        <div class="wrap-in" >
                                                            <input ondragover="allowDrop(event)" type="file" id="temp_upload_image" name="temp_upload_image" accept="image/*" multiple >
                                                            <img src="{{asset('user/images/icon/upload-logo.png')}}">
                                                            <div class="logo-note">
                                                                Kéo & Thả ảnh tại đây!
                                                            </div>
                                                            <p>Bạn có thể chọn nhiều hình ảnh để tải lên cùng một lúc. Thay đổi thứ tự hình ảnh bằng Kéo & Thả.</p>
                                                            <div class="buttons">
                                                                <button type="button" class="btn text-white btn-upload-image btn-blue">Tải ảnh lên</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="classified-page__upload-images-box update-slider-image mb-4 {{ old('gallery') || old('gallery_project') ? '' : 'd-none' }}">
                                        <div class="classified-page__slick-list list-update-slider-image slick mb-2">
                                            @foreach(old('gallery')??[] as $item)
                                                <div class="item-slick-land">
                                                    <div class="image-ratio-box relative">
                                                        <div class="absolute-full">
                                                            <img class="object-cover shadow" src="{{ $item }}" alt="">
                                                            <input type="hidden" name="gallery[]" value="{{ $item }}">
                                                        </div>
                                                        <i class="fas fa-times cursor-pointer remove-land"></i>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @foreach(old('gallery_project')??[] as $item)
                                                <div class="item-slick-land">
                                                    <div class="image-ratio-box relative">
                                                        <div class="absolute-full">
                                                            <img class="object-cover shadow" src="{{ $item }}" alt="">
                                                            <input type="hidden" name="gallery_project[]" value="{{ $item }}">
                                                        </div>
                                                        <i class="fas fa-times cursor-pointer remove-land"></i>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="notication-update-slider-image">Hình ảnh tin đăng</div>
                                        <input type="text" id="inp-images-list" onchange="imagesList(this)" hidden>
                                    </div>
                                    {{show_validate_error($errors, 'gallery')}}

                                    {{-- should change add fields step by step keep position--}}
                                    @include('user.classified.partials._form', [
                                        'selectedProject' => $project_active ?? null
                                    ])

                                    @include('user.classified.partials._meta-form', [
                                        'classified' => $classified,
                                    ])
                                </div>
                                <div class="notication-form-bottom-1">
                                    {!! $guide->where('config_code', 'N007')->first()->config_value !!}
                                </div>
                            </div>
                            <div class="col-md-4 time-right classified-time-preview">
                                <div class="child-time-right sticky top-3">
                                    <div>
                                        <div class="time-post-project">
                                            <div class="title-time-post">Lịch đăng tin</div>
                                            <div class="bg-white px-3 py-2">
                                                <div class="total-date">
                                                    <div class="sice-date form-group">
                                                        <label for="date_from">Từ ngày</label>
                                                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ old('date_from', now()->format('Y-m-d')) }}">
                                                    </div>
                                                    <div class="until-date form-group">
                                                        <label for="date_to">Đến ngày</label>
                                                        <input type="date" name="date_to" id="date_to" value="{{ old('date_to', now()->addDays(59)->format('Y-m-d')) }}" class="form-control" >
                                                    </div>
                                                </div>
                                                <div class="total-date-post-land">Tổng số ngày đăng: <span class="important total-days">60</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="create-post-land">
                                            <div class="title-time-post">Xem trước & đăng tin</div>
                                            <div class="bg-white px-3 py-2">
                                                <div class="total-date-post-land">Bạn đang có
                                                    <span class="important">{{ data_get($package, 'normal') }}</span> tin thường ,
                                                    <span class="important">{{ data_get($package, 'vip') }} </span> tin vip ,
                                                    <span class="important"> {{ data_get($package, 'highlight') }} </span> tin nổi bật &
                                                    <span class="important coin-amount">{{$coin_amount}}</span> Coin</div>
                                                <div class="list-button-post-land">
                                                    <button class="btn use-pakage-post mr-2 {{ old('classified_package') || !old('classified_service') ? 'active' : '' }}" data-form="#use-package">Dùng gói tin</button>
                                                    <button class="btn use-coin {{ old('classified_service') ? 'active' : '' }}" data-form="#use-coin">Dùng Express Coin</button>
                                                </div>
                                                <div class="check-payment-post-land {{ old('classified_package') || !old('classified_service') ? 'active' : '' }}" id="use-package">
                                                    <div class="row p-2">
                                                        <div class="col-12 form-check d-flex align-items-center">
                                                            <input type="radio" name="classified_package" value="1" {{ old('classified_package') == 1 ? 'checked' : '' }} class="form-check-input mt-0" id="pakage-1" title="tin Thường" package_amount="{{ data_get($package, 'normal') }}" checked/>
                                                            <label class="form-check-label text-bold" for="pakage-1">Đăng tin Thường</label>
                                                        </div>
                                                        <div class="col-12 form-check d-flex align-items-center">
                                                            <input type="radio" name="classified_package" value="2" {{ old('classified_package') == 2 ? 'checked' : '' }} class="form-check-input mt-0" id="pakage-2" title="tin Vip" package_amount="{{ data_get($package, 'vip') }}"/>
                                                            <label class="form-check-label text-bold" for="pakage-2">Đăng tin Vip  - </label>
                                                            <input type="number" name="num_day" class="form-control-sm border ml-2 inp-day"  min="1" max="1000" value="{{ old('classified_package') == 2 ? old('num_day') : 1 }}">
                                                            <label class="form-check-label text-bold" for="pakage-2">Ngày</label>
                                                        </div>
                                                        <div class="col-12 form-check d-flex align-items-center">
                                                            <input type="radio" name="classified_package" value="3" {{ old('classified_package') == 3 ? 'checked' : '' }} class="form-check-input mt-0" id="pakage-3" title="Nổi bật" package_amount="{{ data_get($package, 'highlight') }}"/>
                                                            <label class="form-check-label text-bold" for="pakage-3">Đăng tin Nổi bật  -</label>
                                                            <input type="number" name="num_day" class="form-control-sm border ml-2 inp-day"  min="1" max="1000" value="{{ old('classified_package') == 3 ? old('num_day') : 1 }}">
                                                            <label class="form-check-label text-bold" for="pakage-2">Ngày</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="check-payment-post-land {{ old('classified_service') ? 'active' : '' }}" id="use-coin">
                                                    <div class="row p-2">
                                                        @foreach($service_fee as $item)
                                                            <div class="col-12 form-check d-flex align-items-center">
                                                                <input type="radio" name="classified_service" value="{{$item->id}}" {{ old('classified_service') == $item->id ? 'checked' : '' }} class="form-check-input  mt-0" id="coin-{{$item->id}}" service_coin="{{$item->service_coin}}"/>
                                                                <label class="form-check-label text-bold" for="coin-{{$item->id}}">{{$item->service_name}} <span class="important">(-{{$item->service_coin}} Coin)</span></label>
                                                                <input type="number" name="num_day" class="form-control-sm border ml-2 inp-day"  min="1" max="1000" value="{{ old('classified_service') == $item->id ? old('num_day') : 1 }}">
                                                                <label class="form-check-label text-bold" for="">Ngày</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <x-common.captcha />
                                                </div>

                                                <div class="list-payment-post-land d-flex justify-content-end">
                                                    <div class="group">
                                                        <a type="button" class="btn btn-success js-classified-preview" data-toggle="modal" data-target="#preview">Xem trước</a>
                                                        <i class="fas fa-tv"></i>
                                                    </div>
                                                    <div class="group">
                                                        <a href="javascript:void(0);" class="btn post submit {{$classified_post?'post-invalid':null}}" >Đăng</a>
                                                        <i class="fas fa-arrow-up"></i>
                                                    </div>
                                                </div>
                                                <div class="notication-add-coin">
                                                    <p class="error-add-coin"><span>!</span></p>
                                                    <p>Nếu tài khoản của bạn không đủ coin để thực hiện thao tác. Vui lòng đăng tin thường hoặc <a href="{{route('user.deposit')}}">Nạp thêm Express Coin</a></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('popup')
    @include('user.classified.popup-preview')
@endsection
@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?key={!!$google_api_key!!}&callback=callback&libraries=places&v=weekly" defer></script>
    <script type="text/javascript" src="{{ asset('common/js/new-map.js') }}"></script>

    <script src="{{asset('user/js/classified.js')}}"></script>

    <script type="text/javascript">
        let initLocation = {
            lat: parseFloat($('.classified-page [name="latitude"]').val() || 0),
            lng: parseFloat($('.classified-page [name="longtitude"]').val() || 0),
        }

        function callback() {
            initMap('classified-page__map', '.classified-page [name="latitude"]', '.classified-page [name="longtitude"]');
        }
    </script>
@endsection
