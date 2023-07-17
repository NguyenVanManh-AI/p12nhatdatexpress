@extends('Admin.Layouts.Master')
@section('Title', 'Thêm dự án mới | Quản lý dự án')
@section('Style')
    <link rel="stylesheet" type="text/css" href="css/admin-project.css" />
    <style type="text/css">

        select{
            -webkit-appearance: none;
            -moz-appearance: none;
            text-indent: 1px;
            text-overflow: '';
            background-image : url(../frontend/images/arrow-down.png) !important;
            background-position: top 50% right 15px !important;
            background-repeat: no-repeat !important;
            background-size: 10px !important;
        }
        .main-footer{
            margin: 0 !important;
        }
        @media only screen and (max-width: 582px) {
            .modal-dialog{
                max-width: 100% !important;
            }
            #exampleModal{
                padding-right: 0 !important;
            }
            .modal-body > div{
                height: calc(100vh - 120px) !important;
            }
            .project-banner-slide{
                height: 200px !important;
            }
            .tach-input{
                margin-bottom: 80px !important;
            }
        }
        .embed-responsive{
            height: 293px;
        }
        @media only screen and (max-width: 779px){
            .search-reponsive{
                margin-bottom: unset;
                margin-top: -25px;
            }
            .search-reponsive + label{
                margin-top: -43px;
            }
        }
    </style>
@endsection
@section('Content')
    <!-- Main content -->
    <div class="bg-white box-main-reponsive box-main">
        <form id="form" action="{{route('admin.request.write',[$project->id,Crypt::encryptString($project->confirmed_by)])}}" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="row">
                <div class="box-input-reponsive col-sm-12 col-md-6 box-info-left-top pr-4" style="height: initial">
                    <label>{{optional($properties[0])->name ?? "Tên dự án"}} <span class="required"></span> </label>
                    <input class="form-control required" type="text" name="project_name" placeholder="Khu đô thị mới Thuận Phước"
                           id="project_name" onblur="changeToSlug(this, '#project_url')" value="{{old('project_name') ?? $project->project_name}}">
                    <small class="text-danger error-message-custom" id="msg_project_name"></small>
                    @if($errors->has('project_name'))
                        <small class="text-danger error-message-custom">
                            {{$errors->first('project_name')}}
                        </small>
                    @endif
                    <div>
                        <label class="mt-3">{{optional($properties[3])->name ?? 'Mô hình'}} <span class="required"></span> </label><br>
                        <select class="form-control select2" style="width: 100%;height: 34px !important;" name="group_id" id="group_id"
                                onchange="get_progress(this, '{{route('param.get_progress')}}', '#progress', {{old('project_progress')}});
                                    get_furniture(this, '{{route('param.get_furniture')}}', '#furniture')">
                            <option selected="selected" disabled value="">Chọn mô hình</option>
                            @foreach($category as $item)
                                <option value="{{$item->id}}" {{old('group_id') == $item->id ? 'selected="selected"' : "" }}>{{$item->group_name}}</option>
                            @endforeach
                        </select>
                        <small class="text-danger error-message-custom" id="msg_group_id"></small>
                        @if($errors->has('group_id'))
                            <small class="text-danger error-message-custom">
                                {{$errors->first('group_id')}}
                            </small>
                        @endif
                    </div>
                    <div id="locationRequest">
                        <label class="mt-3">{{optional($properties[9])->name ?? 'Vị trí'}}<span class="required"></span> </label><br>
                        <select class="form-control select2 " name="province_id" id="province" data-placeholder="Tỉnh / Thành phố"
                                onchange="get_district(this, '{{route('param.get_district')}}', '#district', null, {{old('province_id')}})">
                            <option selected="selected" disabled value="">Tỉnh / Thành phố</option>
                            @foreach($province as $item)
                                <option value="{{$item->id}}" {{old('province_id') == $item->id || $project->province_id == $item->id ? "selected" : ""}}>{{$item->province_name}}</option>
                            @endforeach
                        </select>
                        <small class="text-danger error-message-custom" id="msg_province_id"></small>
                        @if($errors->has('province_id'))
                            <small class="text-danger error-message-custom">
                                {{$errors->first('province_id')}}
                            </small>
                        @endif
                        <div class="space-line"></div>
                        <select class="form-control select2" name="district_id" id="district" data-placeholder="Quận / Huyện"
                                onchange="get_ward(this, '{{route('param.get_ward')}}', '#ward', {{old('province_id') ?? "null"}} , {{old('district_id') ?? "null"}})">
                        </select>
                        <small class="text-danger error-message-custom" id="msg_district_id"></small>
                        @if($errors->has('district_id'))
                            <small class="text-danger error-message-custom">
                                {{$errors->first('district_id')}}
                            </small>
                        @endif
                        <div class="space-line"></div>
                        <select class="form-control select2" id="ward" name="ward_id" data-placeholder="Xã / Phường">
                        </select>
                        <small class="text-danger error-message-custom" id="msg_ward_id"></small>
                        @if($errors->has('ward_id'))
                            <small class="text-danger error-message-custom">
                                {{$errors->first('ward_id')}}
                            </small>
                        @endif
                        <div class="space-line"></div>
                        <input class="form-control required" type="text" name="address" id="road" placeholder="Địa chỉ đường" value="{{old('address')}}" />
                        <small class="text-danger error-message-custom" id="msg_address"></small>
                        @if($errors->has('address'))
                            <small class="text-danger error-message-custom">
                                {{$errors->first('address')}}
                            </small>
                        @endif
                    </div>
                    <!-- partial -->
                </div>
                <div class="map-box map-box-reponsive col-sm-12 col-md-6 " style="height: inherit">
                    <div id="map" class="w-100 h-100"></div>
                    <input type="hidden" name="map_latitude" id="map_latitude" value="{{old('map_latitude')}}">
                    <input type="hidden" name="map_longtitude" id="map_longtitude" value="{{old('map_longtitude')}}">
                    <small class="text-danger error-message-custom" id="msg_map_longtitude"></small>
                    @if($errors->has('map_latitude') || $errors->has('map_longtitude'))
                        <small class="text-danger error-message-custom py-2">
                            {{$errors->first('map_latitude') ?? $errors->first('map_longtitude')}}
                        </small>
                    @endif
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 bg-white" id="can-be-margin">
                    <div class="box-dash">
                        <h3 class="title-info-reponsive">THÔNG TIN CHI TIẾT</h3>
                        <div class="row pt-2 pr-3">
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 box_input ">
                                <label class="mt-3">{{optional($properties[1])->name ?? 'Giá bán'}}</label>
                                <div>
                                    <input class="form-control required select" type="number" name="project_price" value="{{old('project_price')}}"
                                           maxlength="6" oninput="maxLengthInput(this)" />
                                    <select id="giatien" class="pl-2 float-right" name="price_unit_id">
                                        @foreach($unit_sell as $item)
                                            <option value="{{$item->id}}" {{old('price_unit_id') == $item->id ? "selected" : ""}}>{{$item->unit_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-danger error-message-custom" id="msg_project_price"></small>
                                @if($errors->has('project_price'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('project_price')}}
                                    </small>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 box_input mtdow10">
                                <label class="mt-3">{{optional($properties[4])->name ?? 'Giá thuê'}}</label>
                                <div>
                                    <input class="form-control required select" type="number" name="project_rent_price" value="{{old('project_rent_price')}}"
                                           maxlength="6" oninput="maxLengthInput(this)" />
                                    <select id="giatien" class="pl-2"   name="project_unit_rent_id">
                                        @foreach($unit_rent as $item)
                                            <option value="{{$item->id}}" {{old('project_unit_rent_id') == $item->id ? "selected" : ""}}>{{$item->unit_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-danger error-message-custom" id="msg_project_rent_price"></small>
                                @if($errors->has('project_rent_price'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('project_rent_price')}}
                                    </small>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 box_input mtdow10">
                                <label class="mt-3">{{optional($properties[7])->name ?? 'Diện tích'}} (m<sup>2</sup>) <span class="required"></span></label>
                                <div>
                                    <input class="dientich form-control" placeholder="Từ" name="project_area_from" type="number" value="{{old('project_area_from')}}"
                                           maxlength="6" oninput="maxLengthInput(this)" />
                                    <div class=" space-area text-center font-weight-bold mx-1">
                                        -
                                    </div>
                                    <input class="dientich form-control" placeholder="Đến" name="project_area_to" type="number" value="{{old('project_area_to')}}"
                                           maxlength="6" oninput="maxLengthInput(this)" />
                                </div>
                                <small class="text-danger error-message-custom" id="msg_project_area_from"></small>
                                @if($errors->has('project_area_from') || $errors->has('project_area_to'))
                                    <small class="text-danger error-message-custom">
                                        @if($errors->has('project_area_from'))
                                            {{ $errors->first('project_area_from') }}
                                        @else
                                            {{ $errors->first('project_area_to')}}
                                        @endif
                                    </small>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 box_input mtdow10">
                                <label class="mt-3">{{optional($properties[5])->name ?? 'Quy mô'}} (ha)</label>
                                <input class="form-control required" placeholder="" name="project_scale" type="number" value="{{old('project_scale')}}"
                                       maxlength="6" oninput="maxLengthInput(this)" />
                                <small class="text-danger error-message-custom" id="msg_project_scale"></small>
                                @if($errors->has('project_scale'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('project_scale')}}
                                    </small>
                                @endif
                            </div>

                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 select-box">
                                <label>{{optional($properties[12])->name ?? 'Tình trạng'}} <span class="required"></span></label>
                                <br>
                                <select class="form-control select2" style="width: 100%;height: 34px !important;" name="project_progress" id="progress">
                                </select>
                                <small class="text-danger error-message-custom" id="msg_project_progress"></small>
                                @if($errors->has('project_progress'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('project_progress')}}
                                    </small>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 select-box mtdow10">
                                <label>{{optional($properties[6])->name ?? 'Chủ dầu tư'}}</label>
                                <br>
                                <input class="form-control required" placeholder="" name="investor" value="{{old('investor') ?? $project->investor}}"/>
                                <small class="text-danger error-message-custom" id="msg_investor"></small>
                                @if($errors->has('investor'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('investor')}}
                                    </small>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 select-box mtdow10">
                                <label>{{optional($properties[10])->name ?? 'Pháp lý'}}</label>
                                <br>
                                <div class="form-group">
                                    <select class="form-control select2" style="width: 100%;height: 34px !important;" name="project_juridical">
                                        @foreach($legal as $item)
                                            <option value="{{$item->id}}" {{old('project_juridical') == $item->id ? "selected" : "" }}>{{$item->param_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-danger error-message-custom" id="msg_project_juridical"></small>
                                @if($errors->has('project_juridical'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('project_juridical')}}
                                    </small>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 select-box mtdow10">
                                <label>{{optional($properties[13])->name ?? 'Nội thất'}} <span class="required"></span></label>
                                <br>
                                <select class="form-control select2" style="width: 100%;height: 34px !important;" name="project_furniture" id="furniture">
                                </select>
                            </div>

                            <div class=" mt15 col-12 col-sm-12 col-md-4 col-lg-3 select-box mtdow10">
                                <label>{{optional($properties[2])->name ?? 'Hướng nhà'}} <span class="required"></span></label>
                                <br>
                                <select class="form-control select2" style="width: 100%;height: 34px !important;" name="project_direction">
                                    @foreach($direction as $item)
                                        <option value="{{$item->id}}" {{old('project_direction') == $item->id ? "seleted" : ""}}>{{$item->direction_name}}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger error-message-custom" id="msg_project_direction"></small>
                                @if($errors->has('project_direction'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('project_direction')}}
                                    </small>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 box_input mt15">
                                <label>{{optional($properties[11])->name ?? 'Mặt tiền đường'}} (m2)</label>
                                <input class="form-control required" name="project_road" type="text" value="{{old('project_road')}}">
                                <small class="text-danger error-message-custom" id="msg_project_road"></small>
                                @if($errors->has('project_road'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('project_road')}}
                                    </small>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 select-box">
                                <br>
                                <div class="d-flex align-items-center">
                                    <label class="search-reponsive form-control-409 mr-2">
                                        <input type="checkbox" value="1" class="checkbox-forced-colors-checked" id="bank_sponsor" {{old('bank_sponsor') == 1 ? "checked" : ""}} name="bank_sponsor">
                                    </label>
                                    <label for="bank_sponsor">{{optional($properties[8])->name ?? 'Hỗ trợ vay'}}</label>
                                </div>
                                <small class="text-danger error-message-custom" id="msg_bank_sponsor"></small>
                                @if($errors->has('bank_sponsor'))
                                    <small class="text-danger error-message-custom">
                                        {{$errors->first('bank_sponsor')}}
                                    </small>
                                @endif
                            </div>
                            <div class="warring2-reponsive col-12 mb-3">
                            <span>Khi để trống mục
                                <a href="javacript:{}" class="text-primary">Giá bán</a> và
                                <a href="javacript:{}" class="text-primary">Giá cho thuê</a>
                                hệ thống sẽ tự động hiển thị mặc định là
                                <a href="javacript:{}" class="text-success">Đang cập nhật</a>
                            </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 bg-white">
                    <div class="box-dash info">
                        <h3 class="title-info-reponsive">HỆ THỐNG TIỆN ÍCH</h3>
                        <div class="row pt-4 pb-3">
                            @foreach($utility as $item)
                                <div class="col-6 col-sm-6 col-md-3 col-lg-3 box_checkbox">
                                    <div class="support-vay2">
                                        <label class="form-control-409 mr-2">
                                            <input type="checkbox" class="checkbox-forced-colors-checked" id="utility-{{$item->id}}" name="list_utility[{{$item->id}}]"
                                                   value="{{$item->id}}" {{old("list_utility.". $item->id) ? 'checked' : ''}} />
                                        </label>
                                        <label for="utility-{{$item->id}}" class="font-weight-normal">{{$item->utility_name}}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 p-2">
                    {{--                    <label>Ảnh dự án</label>--}}
                    {{--                    <br>--}}
                    <div style="height: 340px; background: white;border: 1px solid #b7b7b7;padding:8px">
                        <div class="choose-image d-flex flex-column justify-content-center align-items-center" data-toggle="modal" data-target="#modalFile" style="cursor: pointer">
                            <img src="{{ asset("system/images/icons/upload-file.png")}}" class="mt-4">
                            {{--                                <span class="desc d-block">Kéo & Thả ảnh tại đây!</span>--}}
                            <p >Bạn có thể chọn nhiều hình ảnh để tải lên cùng một lúc. <br>
                                Thay đổi thứ tự hình ảnh bằng Kéo & Thả.</p>
                            <span class="btn btn-upload">Tải ảnh lên</span>
                            <input type="hidden" id="image_url_file" name="image_url" value="{{old('image_url')}}" onchange="previewImgFromInputText(this, '#add-gallery', '#image_url_order')">
                            <input type="hidden" id="image_url_order" name="image_url_order" value="{{old('image_url_order')}}">
                            {{--<input type="file" id="files" name="image_url[]" multiple accept="image/*">--}}
                        </div>
                    </div>
                    <small class="text-danger error-message-custom" id="msg_image_url"></small>
                    @if($errors->has('image_url'))
                        <small class="text-danger error-message-custom">
                            {{$errors->first('image_url')}}
                        </small>
                    @endif
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 p-2">
                    {{--                <label>&nbsp;</label>--}}
                    {{--                <br />--}}
                    <ul class="form-row add-gallery p-0 py-1" id="add-gallery" style="height: 340px; border:1px solid #ccc; align-content: flex-start; overflow-y: scroll">
                    </ul>
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 p-2">
                    {{--                    <label>Video</label>--}}
                    {{--                    <br />--}}
                    <input class="form-control required mb-2" placeholder="Nhập đường dẫn video youtube" name="video" id="video" onblur="setIframeYoutube(this, '#iframeYoutube')" value="{{old('video')}}">
                    <small class="text-danger error-message-custom" id="msg_video"></small>
                    @if($errors->has('video'))
                        <small class="text-danger error-message-custom">
                            {{$errors->first('video')}}
                        </small>
                    @endif
                    <div class="embed-responsive embed-responsive-16by9 add-video">
                        <iframe width="100%" height="100%" src="https://www.youtube.com/embed/9FYXrs0jYfU"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen id="iframeYoutube">
                        </iframe>
                    </div>
                </div>
            </div>
            <div class="row mt-5 px-2">
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 p-0">
                    <div class="tongquan menu nav-item nav-link active h34">
                        <img src="{{ asset("system/images/icons/icon-tab1.png")}}">
                        <span class="ml-2 menu-editer-reponsive">TỔNG QUAN</span>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 p-0">
                    <div class="vitri menu nav-item nav-link h34">
                        <img src="{{ asset("system/images/icons/icon-location.png")}}">
                        <span class="ml-2 menu-editer-reponsive">VỊ TRÍ</span>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 p-0">
                    <div class="tienich menu nav-item nav-link h34">
                        <img src="{{ asset("system/images/icons/icon-share.png")}}">
                        <span class="ml-2 menu-editer-reponsive">TIỆN ÍCH</span>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 p-0">
                    <div class="matbang menu nav-item nav-link h34">
                        <img src="{{ asset("system/images/icons/icon-tab2.png")}}">
                        <span class="ml-2 menu-editer-reponsive">MẶT BẰNG</span>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 p-0">
                    <div class="phaply menu nav-item nav-link h34">
                        <img src="{{ asset("system/images/icons/icon-tab3.png")}}">
                        <span class="ml-2 menu-editer-reponsive">PHÁP LÝ</span>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 p-0">
                    <div class="thanhtoan menu nav-item nav-link h34">
                        <img src="{{ asset("system/images/icons/icon-tab4.png")}}">
                        <span class="ml-2 menu-editer-reponsive">THANH TOÁN</span>
                    </div>
                </div>
            </div>
            <div class="row px-2">
                <div class="tongquan-editer tiny-box">
               <textarea class="js-admin-tiny-textarea" id="project_content" name="project_content" style="width: 100%;height: 300px">
                {{old('project_content')}}
              </textarea>
                </div>
                <div class="vitri-editer tiny-box hide">
                 <textarea class="js-admin-tiny-textarea" id="location_descritpion" name="location_descritpion" style="width: 100%;height: 300px">
                  {{old('location_descritpion')}}
                </textarea>
                </div>
                <div class="tienich-editer tiny-box hide">
                 <textarea class="js-admin-tiny-textarea" id="utility_description" name="utility_description" style="width: 100%;height: 300px">
                  {{old('utility_description')}}
                </textarea>
                </div>
                <div class="matbang-editer tiny-box hide">
                 <textarea class="js-admin-tiny-textarea" id="ground_description" name="ground_description" style="width: 100%;height: 300px">
                  {{old('ground_description')}}
                </textarea>
                </div>
                <div class="phaply-editer tiny-box hide">
                 <textarea class="js-admin-tiny-textarea" id="legal_description" name="legal_description" style="width: 100%;height: 300px">
                  {{old('legal_description')}}
                </textarea>
                </div>
                <div class="thanhtoan-editer tiny-box hide">
                 <textarea class="thanhtoan-editer js-admin-tiny-textarea" id="payment_description" name="payment_description"
                           style="width: 100%;height: 400px">
                   {{old('payment_description')}}
                 </textarea>
                </div>
                <input type="hidden" id="num_word" name="num_word" value="{{old('num_word')}}"/>
                <small class="text-danger error-message-custom" id="msg_project_content"></small>
                @if($errors->has('project_content'))
                    <small class="text-danger error-message-custom">
                        {{$errors->first('project_content')}}
                    </small>
                @endif
                <small class="text-danger error-message-custom" id="msg_location_descritpion"></small>
                @if($errors->has('location_descritpion'))
                    <small class="text-danger error-message-custom">
                        {{$errors->first('location_descritpion')}}
                    </small>
                @endif
                <small class="text-danger error-message-custom" id="msg_utility_description"></small>
                @if($errors->has('utility_description'))
                    <small class="text-danger error-message-custom">
                        {{$errors->first('utility_description')}}
                    </small>
                @endif
                <small class="text-danger error-message-custom" id="msg_ground_description"></small>
                @if($errors->has('ground_description'))
                    <small class="text-danger error-message-custom">
                        {{$errors->first('ground_description')}}
                    </small>
                @endif
                <small class="text-danger error-message-custom" id="msg_legal_description"></small>
                @if($errors->has('legal_description'))
                    <small class="text-danger error-message-custom">
                        {{$errors->first('legal_description')}}
                    </small>
                @endif
                <small class="text-danger error-message-custom" id="msg_payment_description"></small>
                @if($errors->has('payment_description'))
                    <small class="text-danger error-message-custom">
                        {{$errors->first('payment_description')}}
                    </small>
                @endif
            </div>
            <div class="px-2">
                <div>
                    <h5 class="mt-4" style="text-align: center;">TỐI ƯU HIỂN THỊ</h5>
                </div>
                <div class="box-seo-main-reponsive row mt-4 mb-5">
                    <div class="box-seo-reponsive col-12 col-sm-12 col-md-6 col-lg-6 box_input p-0 pr-2">
                        <label>Đường dẫn thân thiện</label>
                        <input class="form-control required" id="project_url" name="project_url" value="{{old('project_url')}}" />
                        <small class="text-danger error-message-custom" id="msg_project_url"></small>
                        @if($errors->has('project_url'))
                            <small class="text-danger error-message-custom">
                                {{$errors->first('project_url')}}
                            </small>
                        @endif
                    </div>
                    <div class="box-seo-reponsive col-12 col-sm-12 col-md-6 col-lg-6 box_input p-0 pl-2">
                        <label>Tiêu đề dự án</label>
                        <input class="form-control required" id="meta_title" name="meta_title" value="{{old('meta_title')}}" />
                        <small class="text-danger error-message-custom" id="msg_meta_title"></small>
                        @if($errors->has('meta_title'))
                            <small class="text-danger error-message-custom">
                                {{$errors->first('meta_title')}}
                            </small>
                        @endif
                    </div>
                    <div class="box-seo-reponsive col-12 col-sm-12 col-md-6 col-lg-6 box_input p-0 pr-2 tach-input"
                         style="height: 100px">

                        <label>Từ khóa trên công cụ tìm kiếm</label>
                        <textarea class="w-100 p-2" name="meta_key" id="meta_keyword" rows="5" style="height: auto;">{{old('meta_key')}}</textarea>
                        <small class="text-danger error-message-custom" id="msg_meta_key"></small>
                        @if($errors->has('meta_key'))
                            <small class="text-danger error-message-custom">
                                {{$errors->first('meta_key')}}
                            </small>
                        @endif
                    </div>
                    <div class="box-seo-reponsive col-12 col-sm-12 col-md-6 col-lg-6 box_input p-0 pl-2">
                        <label>Mô tả trên công cụ tìm kiếm</label>
                        <textarea class="w-100 p-2" name="meta_desc" rows="5" id="meta_desc">{{old('meta_desc')}}</textarea>
                        <small class="text-danger error-message-custom" id="msg_meta_desc"></small>
                        @if($errors->has('meta_desc'))
                            <small class="text-danger error-message-custom">
                                {{$errors->first('meta_desc')}}
                            </small>
                        @endif
                    </div>
                    <div style="clear: both"></div>

                </div>
                <div class="warring-reponsive" class="col-12 p-0 mt-5" style="z-index: 9999" >
                    Các thông số tối ưu hiển thị sẽ được hệ thống tự động nhập vào, người viết dự án có thể tùy chỉnh
                    nếu cần.
                </div>
                <div class="row mt-4 mb-4 row text-center d-flex justify-content-center">
                    <button class="btn btn-primary mr-2" style="width: 120px;border-radius: 0 !important" type="button" id="btnPreview">
                        Xem trước
                    </button>
                    <div class=" p-0 pl-2">
                        <button class="btn btn-success" style="width: 120px;border-radius: 0 !important">Đăng</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- Modal IMAGE SIDE -->
        <div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFile" aria-hidden="true">
            <div class="modal-dialog modal-file" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn ảnh dự án</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe src="{{url('responsive_filemanager/filemanager/dialog.php')}}?type=1&field_id=image_url_file" frameborder="0"
                                style="width: 100%; height: 70vh"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal IMAGE THUMB -->
        <div class="modal fade" id="modalFileThumb" tabindex="-1" role="dialog" aria-labelledby="modalFileThumb" aria-hidden="true">
            <div class="modal-dialog modal-file" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn ảnh thumbnail</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe src="{{url('responsive_filemanager/filemanager/dialog.php')}}?multiple=0&type=1&field_id=image_url_file_thumb" frameborder="0"
                                style="width: 100%; height: 70vh"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
        {{-- popup --}}
        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width:90%;">
                <div class="modal-content" style="height: 100% !important;">
                    <div class="modal-header">
                        <h5 class="modal-title">Xem trước</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div style="width: 100%;height: calc(100vh - 150px);overflow-y: scroll">
                            <div id="content-preview"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- popup --}}
        @endsection

        @section('Script')
            <script
                src="https://maps.googleapis.com/maps/api/js?key={!! $google_api_key !!}&callback=callback&libraries=&v=weekly"
                async></script>
            <script type="text/javascript" src="js/map.js"></script>
            <script type="text/javascript">
                @if(old('image_thumbnail'))
                previewOneImgFromInputText('#image_url_file_thumb', '#add-thumbnail', '', '{{old('image_thumbnail')}}')
                @endif
                @if(old('image_url'))
                previewImgFromInputText('#image_url_file', '#add-gallery', '#image_url_order', '', '{{old('image_url')}}')
                @endif

                let isClicked = false;
                function callback(){
                    initMap('map','input#map_latitude','input#map_longtitude');
                }
                async function callbackMarkerToAddress(result){
                    $('#road').val('')
                    isClicked = true;
                    let province = await get_province_by_name(result.province_name, '{{route('param.get_province_name')}}' , '#province')
                    await sleep(500)
                    const district = await get_district_by_name( result.district_name, '{{route('param.get_district_name')}}', province['province'].id, '#district')
                    await sleep(500)
                    const ward = await get_ward_by_name(result.ward_name, '{{route('param.get_ward_name')}}', district['district'].id, '#ward')
                    await sleep(500)
                    if (ward['ward']?.id != null){
                        $('#road').val(result.road_name)
                    }
                    isClicked = false
                }
                $(document).ready(async function () {
                    $( "#add-gallery" ).sortable({connectWith: ".form-row.add-gallery", stop: function( event, ui ) {
                            updateOrderImage('#add-gallery', '#image_url_order')
                        }}).disableSelection();
                    await get_district('#province_id', '{{route('param.get_district')}}', '#district', {{old('province_id') ?? $project->province_id ?? "null"}}, {{old('district_id') ?? $project->district_id ?? "null"}})
                    await sleep(500)
                    await get_ward('#district_id', '{{route('param.get_ward')}}', '#ward', {{old('district_id') ?? $project->district_id ?? "null"}} , {{old('ward_id') ?? $project->ward_id ?? "null"}})
                    await sleep(500)
                    $('#road').val({{$project->address ?? ""}})
                    setGoogleMap('#road', '#ward', '#district', '#province')
                    get_furniture('#group_id', '{{route('param.get_furniture')}}', '#furniture', {{old('group_id') ?? "null"}} , {{old('project_furniture') ?? "null"}});
                    get_progress('#group_id', '{{route('param.get_progress')}}', '#progress', {{old('group_id') ?? "null"}}, {{old('project_progress')}})

                    $("#previewModal").on('hide.bs.modal', function(){
                        $('#content-preview').empty()
                    });
                    let msgArray= []
                    async function getPreviewProject(url, form){
                        let data = $(form).serializeArray();
                        data.push({name: 'project_content', 'value': tinyMCE.get('project_content').getContent()});
                        data.push({name: 'location_descritpion', 'value': tinyMCE.get('location_descritpion').getContent()})
                        data.push({name: 'utility_description', 'value': tinyMCE.get('utility_description').getContent()});
                        data.push({name: 'ground_description', 'value': tinyMCE.get('ground_description').getContent()});
                        data.push({name: 'legal_description', 'value': tinyMCE.get('legal_description').getContent()});
                        data.push({name: 'payment_description', 'value': tinyMCE.get('payment_description').getContent()});
                        jQuery.ajax({
                            url: url,
                            type: "POST",
                            // dataType: "json",
                            data: { data },
                            success: function (result) {
                                $('#content-preview').html(result)
                                jQuery('#previewModal').modal('show');
                            },
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            msgArray.map((e) => $(e).empty())
                            let neededKeys = ['group_id', 'province_id', 'district_id'];
                            let canBeMargin = neededKeys.every(key => Object.keys(jqXHR.responseJSON).includes(key))
                            if(canBeMargin) $('#can-be-margin').addClass('mt-5')
                            else $('#can-be-margin').removeClass('mt-5')

                            toastr.error("Vui lòng kiểm tra các trường");
                            for(let i in jqXHR.responseJSON){
                                // toastr.error(jqXHR.responseJSON[i])
                                msgArray.push('#msg_'+ i);
                                $('#msg_'+ i).html(jqXHR.responseJSON[i]).show().next('.text-danger.error-message-custom').hide()
                            }
                        });
                    }
                    $('#btnPreview').click( async function () {
                        let a = await getPreviewProject('{{route('admin.project.preview')}}', '#form')
                    })

                    // map - Chinh
                    $('#locationRequest input[type="text"]').blur(function () {
                        if ($(this).val() === '')
                            return;
                        setGoogleMap('#road', '#ward', '#district', '#province')
                    })
                    $('#furniture').siblings().find('span.required').hide()
                    $('#locationRequest select').change(function () {
                        if (!isClicked)
                            setGoogleMap('#road', '#ward', '#district', '#province')
                    })
                    $('#project_name').keyup(function () {
                        $('#meta_title').val($(this).val())
                        $('#meta_keyword').html($(this).val())
                    })
                    changeToSlug('#project_name', '#project_url');
                    $('#project_name').trigger('keyup')
                    getTinyContentWithoutHTML('project_content', 'blur', '#meta_desc', 200)
                    getTinyWordCount('project_content', 'blur', '#num_word')

                    $("#thumb").on("change", function (e) {
                        $("#add-thumbnail").prepend(` <div class="pip-thumb h-100 position-relative p-1">
                  <img src="`+ URL.createObjectURL(e.target.files[0]) +`" style="object-fit: cover; height: 100%">
                  <span class="remove close" aria-label="Close">×</span>
                  </div>`)
                        $(".remove").click(function () {
                            $(this).parent(".pip-thumb").remove();
                            $('#thumb').val('')
                        });
                    });
                    function previewFile(fileList, f, index){
                        var fileReader = new FileReader();
                        fileReader.onload = (function (e) {
                            var file = e.target;
                            $("#add-gallery").prepend(` <li class="col-md-4 position-relative pip mb-1" style="list-style: none">
                      <img src="`+ e.target.result +`" height="100px" width="100%">
                      <span class="remove close remove-${index}" aria-label="Close" data-id='${index}'>×</span>
                      </li>`)
                            $(`.remove-${index}`).click(function () {
                                numIndex = $(this).data('id')
                                removeFile('files',numIndex)
                                $(this).parent(".pip").remove();
                            });
                        });
                        fileReader.readAsDataURL(f);
                    }
                    // Vinh
                    if (window.File && window.FileList && window.FileReader) {
                        $("#files").on("change", function (e) {
                            $('#add-gallery').empty();
                            var newFileList  = Array.from(e.target.files);
                            var files = e.target.files;
                            for (var i = 0; i < newFileList.length; i++) {
                                var f = files[i]
                                previewFile(e,f, i);
                            }
                        });
                    } else {
                        alert("Your browser doesn't support to File API")
                    }
                });
            </script>
            <script type="text/javascript">
                $('#quanlyduan').addClass('active');
                $('#themduanmoi').addClass('active');
                $('#nav-quanlyduan').addClass('menu-is-opening menu-open');
            </script>
            <!-- Page specific script -->
            <script type="text/javascript">
                $(".tongquan").on("click", function () {
                    $(".tongquan").addClass("active")
                    $(".vitri").removeClass("active")
                    $(".tienich").removeClass("active")
                    $(".matbang").removeClass("active")
                    $(".phaply").removeClass("active")
                    $(".thanhtoan").removeClass("active")

                    $(".tongquan-editer").removeClass("hide")
                    $(".vitri-editer").addClass("hide")
                    $(".tienich-editer").addClass("hide")
                    $(".matbang-editer").addClass("hide")
                    $(".phaply-editer").addClass("hide")
                    $(".thanhtoan-editer").addClass("hide")
                });
                $(".vitri").on("click", function () {
                    $(".tongquan").removeClass("active")
                    $(".vitri").addClass("active")
                    $(".tienich").removeClass("active")
                    $(".matbang").removeClass("active")
                    $(".phaply").removeClass("active")
                    $(".thanhtoan").removeClass("active")

                    $(".tongquan-editer").addClass("hide")
                    $(".vitri-editer").removeClass("hide")
                    $(".tienich-editer").addClass("hide")
                    $(".matbang-editer").addClass("hide")
                    $(".phaply-editer").addClass("hide")
                    $(".thanhtoan-editer").addClass("hide")
                });
                $(".tienich").on("click", function () {
                    $(".tongquan").removeClass("active")
                    $(".vitri").removeClass("active")
                    $(".tienich").addClass("active")
                    $(".matbang").removeClass("active")
                    $(".phaply").removeClass("active")
                    $(".thanhtoan").removeClass("active")

                    $(".tongquan-editer").addClass("hide")
                    $(".vitri-editer").addClass("hide")
                    $(".tienich-editer").removeClass("hide")
                    $(".matbang-editer").addClass("hide")
                    $(".phaply-editer").addClass("hide")
                    $(".thanhtoan-editer").addClass("hide")
                });

                $(".matbang").on("click", function () {
                    $(".tongquan").removeClass("active")
                    $(".vitri").removeClass("active")
                    $(".tienich").removeClass("active")
                    $(".matbang").addClass("active")
                    $(".phaply").removeClass("active")
                    $(".thanhtoan").removeClass("active")

                    $(".tongquan-editer").addClass("hide")
                    $(".vitri-editer").addClass("hide")
                    $(".tienich-editer").addClass("hide")
                    $(".matbang-editer").removeClass("hide")
                    $(".phaply-editer").addClass("hide")
                    $(".thanhtoan-editer").addClass("hide")
                });
                $(".phaply").on("click", function () {
                    $(".tongquan").removeClass("active")
                    $(".vitri").removeClass("active")
                    $(".tienich").removeClass("active")
                    $(".matbang").removeClass("active")
                    $(".phaply").addClass("active")
                    $(".thanhtoan").removeClass("active")

                    $(".tongquan-editer").addClass("hide")
                    $(".vitri-editer").addClass("hide")
                    $(".tienich-editer").addClass("hide")
                    $(".matbang-editer").addClass("hide")
                    $(".phaply-editer").removeClass("hide")
                    $(".thanhtoan-editer").addClass("hide")
                });
                $(".thanhtoan").on("click", function () {
                    $(".tongquan").removeClass("active")
                    $(".vitri").removeClass("active")
                    $(".tienich").removeClass("active")
                    $(".matbang").removeClass("active")
                    $(".phaply").removeClass("active")
                    $(".thanhtoan").addClass("active")

                    $(".tongquan-editer").addClass("hide")
                    $(".vitri-editer").addClass("hide")
                    $(".tienich-editer").addClass("hide")
                    $(".matbang-editer").addClass("hide")
                    $(".phaply-editer").addClass("hide")
                    $(".thanhtoan-editer").removeClass("hide")
                });
                @if(count($errors) > 0)
                toastr.error("Vui lòng kiểm tra các trường");
                @endif
            </script>
@endsection
