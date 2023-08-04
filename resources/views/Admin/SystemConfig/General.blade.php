@extends("Admin.Layouts.Master")
@section('Title', 'Cấu hình chung | Cấu hình hệ thống')
@section('Content')
<body>
    <div class="card-header border-bottom">
        <h4 class="m-0 text-center text-bold" >CẤU HÌNH HỆ THỐNG</h4>
    </div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item p-3">


            <div class="row">
            <div class="col">
                <form action="{{route('admin.system.general')}}" method="post" enctype="multipart/form-data">
                    @csrf
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="feFirstName">Link Facebook</label>
                    <input type="text" class="form-control" name="facebook"  placeholder=""
                    value="@if(old('facebook')){{old('facebook')}}@elseif(isset($system->facebook)){{$system->facebook}}@else{{""}}@endif">
                      @if($errors->has('facebook'))
                          <small id="passwordHelp" class="text-danger">
                              {{$errors->first('facebook')}}
                          </small>
                      @endif
                  </div>
                  <div class="form-group col-md-4">
                    <label for="feLastName">Link Youtube</label>
                    <input type="text" class="form-control" name="youtube" placeholder=""
                            value="@if(old('youtube')){{old('youtube')}}@elseif(isset($system->youtube)){{$system->youtube}}@else{{""}}@endif">
                      @if($errors->has('youtube'))
                          <small id="passwordHelp" class="text-danger">
                              {{$errors->first('youtube')}}
                          </small>
                      @endif
                  </div>
                    <div class="form-group col-md-4">
                        <label for="feLastName">Link LinkedIn</label>
                        <input type="text" class="form-control" name="linkedlin" placeholder=""
                               value="@if(old('linkedlin')){{old('linkedlin')}}@elseif(isset($system->linkedlin)){{$system->linkedlin}}@else{{""}}@endif">
                        @if($errors->has('linkedlin'))
                            <small id="passwordHelp" class="text-danger">
                                {{$errors->first('linkedlin')}}
                            </small>
                        @endif
                    </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-4 d-flex flex-column justify-content-between">
                    <label for="">Mail nhận thông báo</label>
                    <input type="email" class="form-control" name="mail_notification" placeholder=""
                           value="@if(old('mail_notification')){{old('mail_notification')}}@elseif(isset($system->mail_notification)){{$system->mail_notification}}@else{{""}}@endif">
                      @if($errors->has('mail_notification'))
                      <small id="passwordHelp" class="text-danger">
                          {{$errors->first('mail_notification')}}
                      </small>
                       @endif
                  </div>
                  <div class="form-group col-md-4">
                    <label for="">Thông báo nạp Express Coin / Gói tin</label>
                    <input type="text" class="form-control" name="mail_deposit"
                           value="@if(old('mail_deposit')){{old('mail_deposit')}}@elseif(isset($system->mail_deposit)){{$system->mail_deposit}}@else{{""}}@endif"
                           placeholder="Mỗi mail cách nhau bằng dấu phảy">
                      @if($errors->has('mail_deposit'))
                          <small id="passwordHelp" class="text-danger">
                              {{$errors->first('mail_deposit')}}
                          </small>
                      @endif
                  </div>
                 <div class="form-group col-md-4 d-flex flex-column justify-content-between">
                        <label for="">Mail thông báo quảng cáo Express</label>
                        <input type="text" class="form-control" name="mail_express"
                               value="@if(old('mail_express')){{old('mail_express')}}@elseif(isset($system->mail_express)){{$system->mail_express}}@else{{""}}@endif"
                               placeholder="Mỗi mail cách nhau bằng dấu phảy">
                     @if($errors->has('mail_express'))
                         <small id="passwordHelp" class="text-danger">
                             {{$errors->first('mail_express')}}
                         </small>
                     @endif
                 </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                      <label for="">Google Map</label>
                      <input type="text" class="form-control" name="google_map" placeholder=""
                             value="@if(old('google_map')){{old('google_map')}}@elseif(isset($system->google_map)){{$system->google_map}}@else{{""}}@endif">
                        @if($errors->has('google_map'))
                            <small id="passwordHelp" class="text-danger">
                                {{$errors->first('google_map')}}
                            </small>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Tăng số lượng tin rao tại mục BDS nổi bật (Tin ảo)</label>
                        <select name="post_fake" class="form-control">
                          <option
                              @if(old('post_fake')==100)
                              {{"selected"}}
                              @elseif(isset($system->post_fake)&&($system->post_fake==100))
                              {{"selected"}}
                              @else{{""}}
                              @endif
                              value="100">x100</option>
                          <option
                              @if(old('post_fake')==10)
                              {{"selected"}}
                              @elseif(isset($system->post_fake)&&($system->post_fake==10))
                              {{"selected"}}
                              @else{{""}}
                              @endif
                              value="10">x10</option>
                          <option
                              @if(old('post_fake')==1)
                              {{"selected"}}
                              @elseif(isset($system->post_fake)&&($system->post_fake==1))
                              {{"selected"}}
                              @else{{""}}
                              @endif
                              value="1">x1</option>
                        </select>
                        @if($errors->has('post_fake'))
                            <small id="passwordHelp" class="text-danger">
                                {{$errors->first('post_fake')}}
                            </small>
                        @endif
                    </div>

                   <div class="form-group col-md-4 ">
                       <div class="row d-flex justify-content-start align-items-center">
                           <div class="col-md-8">
                               <div class="form-group">
                                   <label for="">{{$percent_affiliate->config_name}}</label>
                                   <input type="number" class="form-control" name="{{$percent_affiliate->config_code}}" min="0" max="999999" placeholder="{{$percent_affiliate->config_name}}"
                                          value="{{old($percent_affiliate->config_code) ?? $percent_affiliate->config_value}}">
                                   @if($errors->has($percent_affiliate->config_code))
                                       <small id="passwordHelp" class="text-danger">
                                           {{$errors->first($percent_affiliate->config_code)}}
                                       </small>
                                   @endif
                               </div>
                           </div>
                           <div class="col-md-4 d-flex justify-content-start align-items-center mt-2">
                               <input type="checkbox" name="{{$is_email_campaign->config_code}}" id="{{$is_email_campaign->config_code}}"
                                      value="1" {{$is_email_campaign->config_value == 1 ? "checked" : ""}}>
                               <label for="{{$is_email_campaign->config_code}}" style="margin: 0; padding-left: 5px"> {{$is_email_campaign->config_name}}</label>
                           </div>
                       </div>
                  </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="linkFb">Nhúng vào Header</label>
                        <textarea style="background: rgba(220,220,220,0.4)" class="form-control dip" name="header">@if(old('header')){{old('header')}}@elseif(isset($system->header)){{$system->header}}@else{{""}}@endif</textarea>
                        @if($errors->has('header'))
                            <small id="passwordHelp" class="text-danger">
                                {{$errors->first('header')}}
                            </small>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="linkFb">Nhúng vào Body</label>
                        <textarea style="background: rgba(220,220,220,0.4)" class="form-control dip" name="body">@if(old('body')){{old('body')}}@elseif(isset($system->body)){{$system->body}}@else{{""}}@endif</textarea>
                        @if($errors->has('body'))
                            <small id="passwordHelp" class="text-danger">
                                {{$errors->first('body')}}
                            </small>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="linkFb">Nhúng vào Footer</label>
                        <textarea style="background: rgba(220,220,220,0.4)" class="form-control dip" name="footer">@if(old('footer')){{old('footer')}}@elseif(isset($system->footer)){{$system->footer}}@else{{""}}@endif</textarea>
                        @if($errors->has('footer'))
                            <small id="passwordHelp" class="text-danger">
                                {{$errors->first('footer')}}
                            </small>
                        @endif
                    </div>
                </div>
                <?php
                    $logoLists = [
                        [
                            'name' => 'logo1',
                            'label' => 'Logo chân trang'
                        ],
                        [
                            'name' => 'logo2',
                            'label' => 'Logo bộ công thương'
                        ],
                        [
                            'name' => 'logo3',
                            'label' => 'Logo DMCA'
                        ],
                        [
                            'name' => 'logo4',
                            'label' => 'Logo'
                        ],
                        [
                            'name' => 'banner',
                            'label' => 'Banner'
                        ],
                    ];
                ?>
                <div class="row">
                    @foreach($logoLists as $logo)
                        <div class="col10-md-2">
                            <div class="form-group">
                                <label>{{ data_get($logo, 'label') }}</label>
                                <x-common.file-box-input
                                    name="{{ data_get($logo, 'name') }}"
                                    old-name="{{ 'old_' . data_get($logo, 'name') }}"
                                    image-value="{{ data_get($system, data_get($logo, 'name')) ? asset(data_get($system, data_get($logo, 'name'))) : null }}"
                                    preview-inline
                                />
                            </div>
                        </div>
                    @endforeach
                </div>

                <div>
                    <div class="form-group">
                        <label for="linkFb">Văn bản giới thiệu công ty </label>
                        <textarea rows="9" class="form-control footer-text" name="introduce">@if(old('introduce')){{old('introduce')}}@elseif(isset($system->introduce)){{$system->introduce}}@else{{""}}@endif</textarea>
                        @if($errors->has('introduce'))
                            <small id="passwordHelp" class="text-danger">
                                {{$errors->first('introduce')}}
                            </small>
                        @endif
                    </div>
                </div>

                {{-- <div class="form-row">
                    <div class="col-md-8">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Logo chân trang</label>
                                <div class="choose-image" id="uploadLogo">
                                    <div class="card-body p-0">
                                        <div id="actionsImgLogo" class="row">
                                            <div class="col-12 h-100">
                                                <div class="btn-group">
                                                  <div class="col d-flex justify-content-center flex-column fileinput-buttonLogo">
                                                      <img src="{{$system->logo1 ? asset($system->logo1) : asset("image/upload-file.png") }}" style="height: 90px;max-width: 100%;max-height: 100%" alt="" id="imglogo1">
                                                      <span class="my-1">Kéo & Thả ảnh tại đây!</span>
                                                      <div class="btn btn-primary btn-sm col">
                                                          <span>Tải ảnh lên</span>
                                                      </div>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="file" name="logo1" id="logo1" value="{{old('logo1')}}">
                                </div>
                                @if($errors->has('logo1'))
                                    <small id="passwordHelp" class="text-danger">
                                        {{$errors->first('logo1')}}
                                    </small>
                                @endif

                            </div>
                            <div class="form-group col-md-4">
                                <label>Logo bộ công thương</label>
                                <div class="choose-image" id="choose-image-BCT">
                                    <div class="card-body p-0">
                                        <div id="actionsImgBCT" class="row">
                                            <div class="col-12 h-100">
                                                <div class="btn-group">
                                                    <div class="col d-flex justify-content-center flex-column fileinput-buttonBCT">
                                                        <img src="{{$system->logo2 ? asset($system->logo2) : asset("image/upload-file.png") }}" style="height: 90px;max-width: 100%;max-height: 100%" alt="" id="imglogo2">
                                                        <span class="my-1">Kéo & Thả ảnh tại đây!</span>
                                                        <div class="btn btn-primary btn-sm col">
                                                            <span>Tải ảnh lên</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="file" id="logo2" name="logo2">
                                    </div>
                                </div>
                                @if($errors->has('logo2'))
                                    <small id="passwordHelp" class="text-danger">
                                        {{$errors->first('logo2')}}
                                    </small>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label>Logo CDMA</label>
                                <div class="choose-image" id="choose-image-CDMA">
                                    <div class="card-body p-0">
                                        <div id="actionsImgCDMA" class="row">
                                            <div class="col-12 h-100">
                                                <div class="btn-group">
                                                    <div class="col d-flex justify-content-center flex-column fileinput-buttonCDMA">
                                                        <img src="{{$system->logo3 ? asset($system->logo3) : asset("image/upload-file.png") }}" style="height: 90px;max-width: 100%;max-height: 100%" alt="" id="imglogo3">
                                                        <span class="my-1">Kéo & Thả ảnh tại đây!</span>
                                                        <div class="btn btn-primary btn-sm col">
                                                            <span>Tải ảnh lên</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="file" id="logo3" name="logo3">
                                    </div>
                                </div>
                                @if($errors->has('logo3'))
                                    <small id="passwordHelp" class="text-danger">
                                        {{$errors->first('logo3')}}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="linkFb">Văn bản giới thiệu công ty </label>
                        <textarea rows="9" class="form-control footer-text" name="introduce">@if(old('introduce')){{old('introduce')}}@elseif(isset($system->introduce)){{$system->introduce}}@else{{""}}@endif</textarea>
                        @if($errors->has('introduce'))
                            <small id="passwordHelp" class="text-danger">
                                {{$errors->first('introduce')}}
                            </small>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-8">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Logo </label>
                                <div class="choose-image" id="uploadLogo">
                                    <!-- .card -->
                                    <div class="card-body p-0">
                                        <div id="actionsImgLogo" class="row">
                                            <div class="col-12 h-100">
                                                <div class="btn-group">
                                                  <div class="col d-flex justify-content-center flex-column fileinput-buttonLogo">
                                                      <img src="{{$system->logo4 ? asset($system->logo4) : asset("image/upload-file.png") }}" style="height: 90px;max-width: 100%;max-height: 100%" alt="" id="imglogo4">
                                                      <span class="my-1">Kéo & Thả ảnh tại đây!</span>
                                                      <div class="btn btn-primary btn-sm col">
                                                          <span>Tải ảnh lên</span>
                                                      </div>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="file" name="logo4" id="logo4" value="{{old('logo4')}}">
                                    <!-- /.card -->

                                </div>
                                @if($errors->has('logo4'))
                                    <small id="passwordHelp" class="text-danger">
                                        {{$errors->first('logo4')}}
                                    </small>
                                @endif

                            </div>
                            <div class="form-group col-md-4">
                                <label>Banner</label>
                                <div class="choose-image" id="choose-image-BCT">
                                    <!-- .card -->
                                    <div class="card-body p-0">
                                        <div id="actionsImgBCT" class="row">
                                            <div class="col-12 h-100">
                                                <div class="btn-group">
                                                    <div class="col d-flex justify-content-center flex-column fileinput-buttonBCT">
                                                        <img src="{{$system->banner ? asset($system->banner) : asset("image/upload-file.png") }}" style="height: 90px;max-width: 100%;max-height: 100%" alt="" id="imgbanner">
                                                        <span class="my-1">Kéo & Thả ảnh tại đây!</span>
                                                        <div class="btn btn-primary btn-sm col">
                                                            <span>Tải ảnh lên</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="file" id="banner" name="banner">
                                    </div>
                                    <!-- /.card -->

                                </div>
                                @if($errors->has('banner'))
                                    <small id="passwordHelp" class="text-danger">
                                        {{$errors->first('banner')}}
                                    </small>
                                @endif
                            </div>

                        </div>
                    </div>

                </div> --}}

                {{-- // --}}

                <div class="form-row , mt-2" >
                    <div class="form-group col-md-6">
                      <label for="">Link App Store</label>
                      <input type="text" class="form-control" name="apple_store" placeholder=""
                             value="@if(old('apple_store')){{old('apple_store')}}@elseif(isset($system->apple_store)){{$system->apple_store}}@else{{""}}@endif">
                        @if($errors->has('apple_store'))
                            <small id="passwordHelp" class="text-danger">
                                {{$errors->first('apple_store')}}
                            </small>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                      <label for="">Link CH Play</label>
                      <input type="text" class="form-control" name="ch_play" placeholder=""
                             value="@if(old('ch_play')){{old('ch_play')}}@elseif(isset($system->ch_play)){{$system->ch_play}}@else{{""}}@endif">
                        @if($errors->has('ch_play'))
                            <small id="passwordHelp" class="text-danger">
                                {{$errors->first('ch_play')}}
                            </small>
                        @endif
                    </div>

                  </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Văn bản giới thiệu công ty chân trang</label>
                        <textarea rows="5" class="form-control h-155" name="text_footer" >@if(old('text_footer')){{old('text_footer')}}@elseif(isset($system->text_footer)){{$system->text_footer}}@else{{""}}@endif</textarea>
                        @if($errors->has('text_footer'))
                            <small id="passwordHelp" class="text-danger">
                                {{$errors->first('text_footer')}}
                            </small>
                        @endif
                    </div>
                    <div class="form-group col-md-6 info-company">
                        <label for="">Thông tin công ty</label>
{{--                        <ul class="list-info">--}}
{{--                            <li><i class="fas fa-map-marker-alt"></i> 259 Hải Phòng, P. Tân Chính, Q. Thanh Khê, Đà Nẵng</li>--}}
{{--                            <li><i class="fas fa-phone"></i> 090.777.2638</li>--}}
{{--                            <li><i class="fas fa-envelope"></i> info@nhadatexpress.vn</li>--}}
{{--                            <li><i class="fas fa-globe"></i> MST: 0402007735</li>--}}
{{--                        </ul>--}}
                        <textarea rows="5" name="info_company" class="form-control h-155">@if(old('info_company')){{old('info_company')}}@elseif(isset($system->info_company)){{$system->info_company}}@else{{""}}@endif</textarea>
                        @if($errors->has('info_company'))
                            <small id="passwordHelp" class="text-danger">
                                {{$errors->first('info_company')}}
                            </small>
                        @endif
                    </div>
                 </div>

                    @if($check_role == 1 || key_exists(2, $check_role))
                        <div class="text-center">
                            <button type="submit" class="btn btn-outline-success">Lưu</button>
                        </div>
                    @endif
                </form>

{{--                <div class="block-dashed">--}}
{{--                    <form action="{{route('admin.system.mail.testmail')}}" method="post">--}}
{{--                        @csrf--}}
{{--                    <h3 class="title">Cấu hình mail</h3>--}}
{{--                    <div class="form-row">--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <label for="">SMTP</label>--}}
{{--                            <input type="text" name="smtp" value="{{old('smtp')?old('smtp'):"smtp.gmail.com"}} " class="form-control">--}}

{{--                        </div>--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <label for="">Tài khoản gửi</label>--}}
{{--                            <input type="text" name="account" required--}}
{{--                                   value="{{(session('account'))?session('account'):""}}"--}}
{{--                                   class="form-control">--}}
{{--                            <small id="accounts" class="text-danger"></small>--}}

{{--                            @if($errors->has('account'))--}}
{{--                                <small id="passwordHelp" class="text-danger">--}}
{{--                                    {{$errors->first('account')}}--}}
{{--                                </small>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-row">--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <label for="">Cổng SMTP</label>--}}
{{--                            <input type="text" name="portsmtp" value="{{old('portsmtp')?old('portsmtp'):"587"}}"--}}
{{--                                   class="form-control">--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <label for="">Mật khẩu</label>--}}
{{--                            <input type="password" required--}}
{{--                                   value="{{(session('password'))?session('password'):""}}"--}}
{{--                                   name="password" class="form-control">--}}
{{--                            <small id="password_custom" class="text-danger"></small>--}}
{{--                            @if($errors->has('password'))--}}
{{--                                <small id="passwordHelp" class="text-danger">--}}
{{--                                    {{$errors->first('password')}}--}}
{{--                                </small>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-row">--}}
{{--                        <div class="form-group col-md-6 text-right">--}}
{{--                            <input  type="submit"  class="btn btn-success test-data" value="Test">--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <input type="button" class="save-data btn btn-primary" value="Lưu">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
                <div class="mail-config mb-4 mt-5">
                    <h4 class="text-center font-weight-bold mb-0 mt-2 mb-2">CẤU HÌNH EMAIL</h4>
                    <div class="box-mail-config">
                        <div class="row m-0 pb-3">
                            <div class="col-12 p-0">
                                <div class="row m-0">
                                    <div id="campent-box" class="  col-12 col-sm-12 col-md-12 col-lg-12 p-0" style="border: 1px solid #ccc">
                                        <div class="w-100 p-0 br1 pb-2">
                                            <div class=" col-12 p-0">
                                                <div class="w-100 bar-box">
                                                    <p class="text-center mb-0 font-weight-bold text-white">Thêm mail cấu hình</p>
                                                </div>
                                            </div>
                                            {{-- link submit form đến controller --}}
                                            <form  action="{{-- {{route('admin.email-campaign.post-add-mail-config')}} --}}" method="post" id="add-mail-config">
                                                @csrf
                                                <div class="row m-0 pt-2 pr-2">
                                                    <div class="col-2 mt-2">
                                                        <p class="float-right font-weight-bold mb-0" style="line-height: 37px">SMTP</p>
                                                    </div>
                                                    <div class="col-10 mt-2">
                                                        <input class="form-control required" type="text" name="mail_host" value="" placeholder="ex: smtp.gmail.com" id="mail_host">
                                                    </div>
                                                    <div class="col-2 mt-2">
                                                        <p class="float-right font-weight-bold mb-0 " style="line-height: 37px">Cổng</p>
                                                    </div>
                                                    <div class="col-10 mt-2">
                                                        <input class="form-control required" type="number" name="mail_port" placeholder="ex: 587" id="mail_port" value="587">
                                                    </div>

                                                    <div class="col-12 mt-2">
                                                        <div class="row">
                                                            <div class="col-5 col-sm-5 col-md-2 col-lg-2">
                                                                <p class="float-right font-weight-bold mb-0" style="line-height: 37px">Tên đăng nhập</p>
                                                            </div>
                                                            <div class="col-7 col-sm-7 col-md-10 col-lg-10 ">
                                                                <input class="form-control required" type="text" name="mail_username" id="mail_username">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-5 col-sm-5 col-md-2 col-lg-2 my-2">
                                                        <p class="float-right font-weight-bold mb-0" style="line-height: 37px">Mật khẩu</p>
                                                    </div>
                                                    <div class="col-7 col-sm-7 col-md-10 col-lg-10 my-2">
                                                        <input class="form-control required" type="text" name="mail_password" id="mail_password">
                                                    </div>

                                                    <div class="col-10 offset-2 d-flex">
                                                        <div class="support-vay2">
                                                            <label class="form-control-409 mr-2">
                                                                <input type="radio" name="mail_encryption" value="tls" id="check_tls" checked>
                                                            </label>
                                                            <label for="check_tls">Bật TLS</label>
                                                        </div>
                                                        {{--                                                        <button class="btn btn-primary br-0 brr0" id="defaultConfig" type="button">Mặc định</button>--}}
                                                        {{--                                                        <div style="height: 38px; padding-bottom: 6px"></div>--}}
                                                        <div class="support-vay2">
                                                            <label class="form-control-409 ml-5 mr-2">
                                                                <input type="radio" value="ssl" name="mail_encryption" id="check_ssl">
                                                            </label>
                                                            <label for="check_ssl">Bật SSL</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="text-center" id="result-status" style="font-size: 95%">
                                                </div>

                                                @if($canAddMailConfig)
                                                <div class="row m-0 pb-3">
                                                    <div id="button-order-by1" class="col-6 mt-2">
                                                        <div id="loading-button-submit"  class="btn bg-primary float-right" style="border-radius: 0;cursor: context-menu;opacity: 0.8;display: none">
                                                            <div class="d-flex">
                                                                <span class="mr-2">Đang thêm email</span>
                                                                <div class="spinner-border" role="status" style="width: 1.5rem;height: 1.5rem">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button onclick="submitButton()" disabled="" id="submit-button" type="button" class="btn btn-primary float-right" style="border-radius: 0;">Hoàn tất</button>
                                                    </div>
                                                    <div id="button-order-by2" class="col-6 mt-2">
                                                        <button id="send-try-button" onclick="sendMailTest(event)"  class="btn btn-secondary" style="border-radius: 0;">Gửi thử</button>
                                                        <div id="loading-button"  class="btn bg-secondary" style="border-radius: 0;cursor: context-menu;opacity: 0.8;display: none">
                                                            <div class="d-flex">
                                                                <span class="mr-2">Đang kiểm tra </span>
                                                                <div class="spinner-border" role="status" style="width: 1.5rem;height: 1.5rem">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($mail->count()>0)
                <div class="table table-responsive">
                <table class="table table-bordered text-center table-custom" id="table">
                    <thead>
                    <tr>
                        <th scope="col" >STT</th>
                        <th scope="col">SMPT</th>
                        <th scope="col">PORT</th>
                        <th scope="col">USERNAME</th>
                        <th scope="col">PASSWORD</th>
                        <th scope="col" >Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>

                        @foreach($mail as $key=> $item )
                            <tr>
                                <td class=" ">
                                    {{$key+1}}
                                </td>

                                <td class="">
                                    {{$item->mail_host}}
                                </td>
                           <td class="">
                                    {{$item->mail_port}}
                                </td>
                                <td class="">
                                    {{$item->mail_username}}
                                </td>
                                <td class="">
{{--                                    {{$item->mail_password}}--}}
                                    *************
                                </td>

                                <td>
                                    {{-- wrong should check by mail campaign permission --}}
                                    <div class="d-flex flex-row justify-content-around align-content-center table_action">
                                        @if( $check_role == 1 || key_exists(2, $check_role ))
                                        <div><i class="fas fa-cog mr-2"></i><a href="{{route('admin.system.mail.edit',[$item->id,\Crypt::encryptString($item->created_by)])}}" class="text-primary stretched-link">Chỉnh sửa</a></div>
                                        @endif
                                            @if($check_role == 1 || key_exists(7, $check_role ))
                                        <div><i class="fas fa-times mr-2"></i><a onclick="deleteitem({{$item->id}},'{{\Crypt::encryptString($item->created_by)}}')" class="text-danger stretched-link action_delete">Xóa</a></div>
                                            @endif
                                    </div>
                                </td>
                            </tr>
                    @endforeach

                    </tbody>
                </table>
                </div>
                @endif
            </div>
            </div>



      </ul>

 </body>
@endsection
@section('Style')
<style>
label:not(.form-check-label):not(.custom-file-label){
    font-weight: 700;
}
.info-company .list-info {
    list-style: none;
    margin-bottom: 0;
    position: absolute;
    top: 35px;
    left: 18px;
    padding-left: 5px;
}
 .info-company .list-info li {
    margin-bottom: 10px;
    font-size: 14px;
    color: #838383;
}
.info-company .list-info {
    list-style: none;
    margin-bottom: 0;
    position: absolute;
    top: 35px;
    left: 18px;
}
.info-company .list-info li i{
    margin-right: 7px;
}
.block-dashed {
    padding-left: 110px;
    padding-right: 110px;
}

.block-dashed {
    margin: 30px 0;
    padding: 30px 30px 14px;
    position: relative;
    border: 1px dashed #c2c5d6;
}
.block-dashed .title {
    display: inline-block;
    font-size: 18px;
    padding: 0 20px;
    background-color: #fff;
    font-weight: 500;
    line-height: 1;
    margin-bottom: 0;
    text-transform: uppercase;
    color: #000;
    position: absolute;
    top: -9px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1;
}
.choose-image {
    border: 1px solid #d7d7d7;
    /*height: 114px;*/
    min-height: 230px;
    text-align: center;
    position: relative;
    display: flex;
    align-items: center;
}
.choose-image .btn-upload {
    border-radius: 3px;
    background-color: #00a8ec;
    color: #fff;
    padding: 6px 8px;
    margin-top: 5px;
    line-height: 1;
}
.choose-image input[type="file"] {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    opacity: 0;
    cursor: pointer;
}
.limit-string {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1; /* number of lines to show */
    line-clamp: 1;
    -webkit-box-orient: vertical;
}
.mail-config .form-control {
    display: block;
    width: 100%;
    height: 38px !important;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 0;
}
.mail-config .support-vay2 {
    margin-top: 10px !important;
    display: flex;
}
.mail-config .bar-box {
    height: 38px;
    background: #034076;
    line-height: 38px;
}
</style>
@endsection
@section('Script')

    <script>
        @if(count($errors) > 0)
        toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>
    <script>
        // const logo1 = document.getElementById('logo1')
        // const previewLogo1 = document.getElementById('imglogo1')
        // logo1.onchange = evt => {
        //     const [file] = logo1.files
        //     if (file) {
        //         previewLogo1.src = URL.createObjectURL(file)
        //     }
        // }
        // const logo2 = document.getElementById('logo2')
        // const previewLogo2 = document.getElementById('imglogo2')
        // logo2.onchange = evt => {
        //     const [file] = logo2.files
        //     if (file) {
        //         previewLogo2.src = URL.createObjectURL(file)
        //     }
        // }
        // const logo3 = document.getElementById('logo3')
        // const previewLogo3 = document.getElementById('imglogo3')
        // logo3.onchange = evt => {
        //     const [file] = logo3.files
        //     if (file) {
        //         previewLogo3.src = URL.createObjectURL(file)
        //     }
        // }
        // const logo4 = document.getElementById('logo4')
        // const previewLogo4 = document.getElementById('imglogo4')
        // logo4.onchange = evt => {
        //     const [file] = logo4.files
        //     if (file) {
        //         previewLogo4.src = URL.createObjectURL(file)
        //     }
        // }
        // const banner = document.getElementById('banner')
        // const previewBanner = document.getElementById('imgbanner')
        // banner.onchange = evt => {
        //     const [file] = banner.files
        //     if (file) {
        //         previewBanner.src = URL.createObjectURL(file)
        //     }
        // }
    </script>
    <script>
        $(".save-data").click(function(event){
            event.preventDefault();
            $('#accounts').text("");
            $('#password_custom').text("");
            let smtp = $("input[name=smtp]").val();
            let port = $("input[name=portsmtp]").val();
            let account = $("input[name=account]").val();
            let password = $("input[name=password]").val();
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "/admin/system-config/add-mail",
                type:"POST",
                data:{
                    smtp:smtp,
                    portsmtp:port,
                    account:account,
                    password:password,
                    _token: _token
                },
                success:function(response){

                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công ',
                        text: 'Refresh',
                        // footer: '<a href="">Why do I have this issue?</a>'
                    })
                    window.location.href="/admin/system-config";
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Lỗi , vui lòng kiểm tra các trường',

                        // footer: '<a href="">Why do I have this issue?</a>'
                    });

                    $('#accounts').text(error.responseJSON.errors.account[0]);
                    $('#password_custom').text(error.responseJSON.errors.password[0]);
                    // console.log(error.responseJSON.errors);
                }
            });
        });
    </script>
    <script>
        function deleteitem(id)
        {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Sau khi xóa sẽ không thể khôi phục",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href= "/admin/system-config/delete-mail/"+id;
                    Swal.fire(
                        'Đã xóa!',
                        'Xóa thành công',
                        'thành công'
                    )
                }
            })
        }
    </script>
{{--JS OF ADD MAIL--}}

<script type="text/javascript">
    let typeButton = "test";
    let token = '{{ csrf_token() }}';
    let sendMailOk = false;
    let checkAddMailConfig = true
    function submitButton(){
        typeButton= "submit";
        submitMail()
    }
    function sendMailTest(e){
        e.preventDefault()
        $('#result-status').empty()
        typeButton= "test";
        if(checkAddMailConfig == true){
            $('#send-try-button').hide();
            $('#loading-button').show();
            $("#submit-button").attr("disabled", true);
            $.ajax({
                url : "{{route('admin.email-campaign.test-mail-config')}}",
                type : "post",
                data : {
                    '_token': token,
                    mail_host : $('#mail_host').val(),
                    mail_port : $('#mail_port').val(),
                    mail_encryption : $('[name="mail_encryption"]:checked').val(),
                    mail_username : $('#mail_username').val(),
                    mail_password : $('#mail_password').val(),
                },
                success : function (response){
                    $('#send-try-button').show();
                    $('#loading-button').hide();
                    $('#result-status').html(response);
                    // checkAddMailConfig = false;
                    if(response == "<p class='text-success mb-0'>Gửi thử thành công! Bạn có thể thêm email này</p>"){
                        sendMailOk=true;
                        $("#submit-button").attr("disabled", false);
                        $("#send-try-button").attr("disabled", true);
                        disbleButton();
                    }
                },
                error: err => {
                    showError(err)
                    $('#result-status').html(`<p class='text-danger mb-0'>Gửi thử thất bại! Vui lòng kiểm tra lại thông tin cấu hình</p>`);
                    $('#send-try-button').show();
                    $('#loading-button').hide();
                }
            });
        }
    }

    function submitMail(){
        typeButton= "submit";
        $('#result-status').empty()
        if(checkAddMailConfig == true){
            $('#submit-button').hide();
            $('#loading-button-submit').show();
            $("#send-try-button").attr("disabled", true);
            $.ajax({
                url : "{{route('admin.email-campaign.post-add-mail-config')}}",
                type : "post",
                data : {
                    '_token': token,
                    mail_host : $('#mail_host').val(),
                    mail_port : $('#mail_port').val(),
                    mail_encryption : $('[name="mail_encryption"]:checked').val(),
                    mail_username : $('#mail_username').val(),
                    mail_password : $('#mail_password').val(),
                    is_config: 1
                },
                success : function (response){
                    if(response == "Thêm thành công"){
                        window.location.reload();
                    }else{
                        $('#submit-button').show();
                        $('#loading-button-submit').hide();
                        $('#result-status').html(response);
                        $("#send-try-button").attr("disabled", false);
                        $("#submit-button").attr("disabled", true);
                    }
                },
                error: function(data){
                    $('#result-status').html(`<p class='text-danger mb-0'>Thêm thất bại! Vui lòng kiểm tra lại thông tin cấu hình</p>`);
                    $('#submit-button').show();
                    $('#loading-button-submit').hide();
                    $("#send-try-button").attr("disabled", false);
                }

            });
        }
    }

    function disbleButton(){
        $('#mail_host').attr("disabled", true);
        $('#mail_port').attr("disabled", true);
        $('[name="mail_encryption"]').attr("disabled", true);
        $('#mail_username').attr("disabled", true);
        $('#mail_password').attr("disabled", true);
    }

</script>
<script type="text/javascript">
    var check =1;
    function notitication(){
        if(check == 1){
            toastr.error('Vui lòng kiểm tra các trường');
            check++;
        }
    }
</script>
@endsection

