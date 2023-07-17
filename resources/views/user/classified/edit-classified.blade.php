@extends('user.layouts.master')
@section('title', 'Chỉnh sửa tin đăng')
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
                <div class="content-type-post {{$parent->id == 2?'color':null}}">
                    <img src="{{asset('frontend/user/images/house.png')}}" alt="">
                </div>
                <div class="title-type-post {{$parent->id == 2?'color':null}}">
                    <h5>Đăng tin nhà đất bán</h5>
                </div>
            </div>
            <div class="col-md-4 type-postcol">
                <div class="content-type-post {{$parent->id == 10?'color':null}}">
                    <img src="{{asset('frontend/user/images/house2.png')}}" alt="">
                </div>
                <div class="title-type-post {{$parent->id == 10?'color':null}}">
                    <h5>Đăng tin nhà đất cho thuê</h5>
                </div>
            </div>
            <div class="col-md-4 type-postcol">
                <div class="content-type-post {{$parent->id == 18?'color':null}}">
                    <img src="{{asset('frontend/user/images/house3.png')}}" alt="">
                </div>
                <div class="title-type-post {{$parent->id == 18?'color':null}}">
                    <h5>Đăng tin cần mua/cần thuê</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="form-post-land classified-page js-change-address-load-map js-has-map-address">
        <form action="{{route('user.post-edit-classified', $classified->id)}}" class="classified-page__form" id="add-classified" method="post" enctype="multipart/form-data">
            @csrf
            <input name="group" value="{{$parent->id}}" hidden>
            @include('user.classified.partials._title-form', [
                'group' => $parent,
                'guide' => $guide,
                'classified' => $classified,
            ])

            <div class="search-project-land">
                <div class="form-search-project-land">
                    <div class="row">
                        @include('user.classified.partials._project-form', [
                            'project' => $project,
                            'group' => $parent,
                            'group_parent_id' => $group_parent_id,
                            'classified' => $classified,
                            'location' => $location,
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
                                        lat-value="{{ old('latitude', data_get($location, 'map_latitude')) }}"
                                        long-value="{{ old('longtitude', data_get($location, 'map_longtitude')) }}"
                                    />
                                </div>
                                <div>
                                    <div class="upload-image-land mb-3">
                                        <div class="title-upload-img-land">
                                            <div class="block upload" ondrop="drop(event)" ondragover="allowDrop(event)" >
                                                <div class="upload-item mr-0">
                                                    <div class="wrap-out bg-white p-3">
                                                        <div class="wrap-in" >
                                                            <img src="{{asset('user/images/icon/upload-logo.png')}}">
                                                            <div class="logo-note">
                                                                Kéo & Thả ảnh tại đây!
                                                            </div>
                                                            <p>Bạn có thể chọn nhiều hình ảnh để tải lên cùng một lúc. Thay đổi thứ tự hình ảnh bằng Kéo & Thả.</p>
                                                            <div class="buttons">
                                                                <button type="button" class="btn text-white btn-upload-image btn-blue">Tải ảnh lên</button>
                                                                <input type="file" name="temp_upload_image" accept="image/*" multiple hidden>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="classified-page__upload-images-box update-slider-image mb-4 {{ old('gallery', $gallery) || old('gallery_project') ? '' : 'd-none' }}">
                                        <div class="classified-page__slick-list list-update-slider-image slick mb-2">
                                            @foreach(old('gallery')??$gallery as $item)
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
                                    {{ show_validate_error($errors, 'gallery') }}

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
                            <div class="col-12 col-md-4 time-right classified-time-preview">
                                <div class="child-time-right sticky top-3">
                                    <div>
                                        <div class="time-post-project">
                                            <div class="title-time-post">Lịch đăng tin</div>
                                            <div class="bg-white px-3 py-2">
                                                <div class="total-date">
                                                    <div class="sice-date form-group">
                                                        <label for="date_from">Từ ngày</label>
                                                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{old('date_from')??date('Y-m-d',$classified->active_date)}}">
                                                    </div>
                                                    <div class="until-date form-group">
                                                        <label for="date_to">Đến ngày</label>
                                                        <input type="date" name="date_to" id="date_to" class="form-control " value="{{old('date_to')??date('Y-m-d',$classified->expired_date)}}">
                                                    </div>
                                                </div>
                                                <div class="total-date-post-land">Tổng số ngày đăng: <span class="important total-days">0</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="create-post-land">
                                            <div class="title-time-post">Xem trước & đăng tin</div>
                                            <div class="bg-white px-3 py-2">
                                                <div class="mb-4">
                                                    <x-common.captcha />
                                                </div>

                                                <div class="list-payment-post-land">
                                                    <div class="group">
                                                        <a type="button" class="btn btn-success js-classified-preview" data-toggle="modal" data-target="#preview">Xem trước</a>
                                                        <i class="fas fa-tv"></i>
                                                    </div>
                                                    <div class="group">
                                                        <a href="javascript:void(0);" class="btn post submit" >Cập nhật</a>
                                                        <i class="fas fa-arrow-up"></i>
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
            initMap('classified-page__map', '.classified-page [name="latitude"]', '.classified-page [name="longtitude"]', initLocation);
        }
    </script>
@endsection
