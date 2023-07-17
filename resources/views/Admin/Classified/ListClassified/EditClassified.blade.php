@extends('Admin.Layouts.Master')
@section('Title', 'Sửa tin rao | Tin rao')
@section('Style')
    <style>
        #quick-post-2 {
            background-color: #F5F5F5;
        }
        .wrapper {
            position: relative;
        }
        .wrapper > i {
            position: absolute;
            color: #919191;
            font-size: 30px;
            cursor: pointer;
            top: -15px;
            right: -15px;
        }

        .head {
            background-color: #0173A7;
            display: flex;
            justify-content: center;
            padding: 20px 0;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .logos {
            display: flex;
            justify-content: center;
            padding: 25px 0;
        }

        .logos .logo-group {
            width: fit-content;
        }

        .logo-group:first-child {
            margin-right: 85px;
        }

        #quick-post .logos .logo-group img {
            display: block;
            margin: 0 auto 20px;
        }

        #quick-post .logos .logo-group .button {
            width: 100%;
            padding: 5px;
            display: flex;
            justify-content: center;
            font-weight: 700;
            border: 2px solid #EEEEEE;
            cursor: pointer;
            color: #00336C;
        }

        .logos .logo-group .button.active {
            background-color: #00AFFF;
            color: #fff;
        }

        #quick-post .desc,
        #new-post .desc {
            padding: 0 30px;
        }

        #quick-post .desc ul,
        #new-post .desc ul {
            padding: 10px;
            background-color: #fff;
            list-style-type: "-";
        }

        #new-post .desc ul {
            display: none;
        }

        #new-post .desc ul.active {
            display: block;
        }

        #quick-post .desc ul li,
        #new-post .desc ul li {
            padding-left: 10px;
        }

        #quick-post .post-button,
        #new-post .submit {
            width: fit-content;
            padding: 5px 30px;
            background-color: #0173A7;
            color: #fff;
            font-weight: 700;
            border-radius: 4px;
            margin: 25px auto 15px auto;
            cursor: pointer;
        }


        {
            top: -16px !important
        ;
            right: -16px !important
        ;
        }

        .wrapper {
            padding: 15px;
        }

        .three-box {
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #fff;
        }

        .box {
            cursor: pointer;
        }

        .box .image {
            display: flex;
            justify-content: center;
            background-color: #fff;
        }

        .box .tag {
            padding: 10px 0;
            text-align: center;
            text-transform: uppercase;
            font-weight: 700;
            border-top: 1px solid #E9ECF5;
            background-color: #fff;
        }

        .box.active .image {

            background-color: #3B8382;
        }

        /*.image img{*/
        /*    margin: 0 auto;*/
        /*}*/
        .box.active .tag {
            color: #FE870B;
        }

        .content label,
        .content textarea {
            width: 100%;
        }

        .content textarea {
            border: none;
            border-radius: 8px;
            resize: none;
            padding: 5px 10px;
            margin-bottom: 10px;
        }

        .content textarea:focus {
            outline: none;
        }

        #quick-post-title {
            height: 55px;
        }

        #quick-post-content {
            height: 446px;
        }

        .content label {
            font-size: 14px;
            font-weight: 700;
            color: #21337F;
        }

        .block-dashed {
            margin: 15px 0;
            padding: 10px 10px 5px;
            position: relative;
            border: 1px dashed #c2c5d6;
            width: 100%;
            height: 80%;
        }

        .block-dashed .check-radio {
            /*background: red;*/
            width: 100%;
            margin-top: 5%;
            display: flex;
            justify-content: space-around;
        }

        .title {
            width: 25%;
            text-align: center;
            transform: translateX(-50%);
            position: absolute;
            background: #F5F5F5;
            left: 50%;
            top: -18%;
            font-size: 100%;
            font-weight: 700;

        }

        .content label span {
            color: #FF7800;
        }

        .content .head {
            padding: 5px 0;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            text-align: center;
            background-color: #21337F;
        }

        .content .body {
            background-color: #fff;
            padding: 15px;
            font-size: 15px;
        }

        .content .body span {
            font-weight: 700;
            color: #21337F;
        }

        .title span {
            font-weight: 700;
            color: #FF0000;
        }

        .content .body a {
            color: #FF0000;
            text-decoration: underline;
        }

        .content > .col-md-4 {
            padding-top: 31px;
        }

        .note {
            margin: 0;
            font-size: 14px;
            font-style: italic;
            margin-bottom: 15px;
        }

        .filter label {
            width: 100%;
            color: #21337F;
            font-weight: 400;
            margin: 0;
        }

        .filter label span {
            color: #FF0000;
        }

        .filter label.blank {
            color: #F4F5F7;
        }

        .filter select,
        .filter input[type='text'] {
            width: 100%;
            height: 38px;
            border: none;
            border-radius: 4px;
            color: #21337F;
            margin-bottom: 15px;
            padding-left: 10px;
            background: white;
        }
        .filter input[type='number'] {
            width: 100%;
            height: 38px;
            border: none;
            border-radius: 4px;
            color: #21337F;
            margin-bottom: 15px;
            padding-left: 10px;
            background: white;
        }

        .filter select:focus,
        .filter input:focus,
        .calendar input:focus {
            border: none;
            outline: none;
        }

        .filter .checkboxes,
        .filter .checkboxes .group {
            display: flex;
        }

        .filter .checkboxes .group {
            width: 300px;
        }

        .filter .checkboxes {
            /*justify-content: space-around;*/
            margin-top: 15px;
            /*margin-right: ;*/
        }

        /*.filter .checkboxes .group {*/
        /*    align-items: center;*/
        /*}*/

        .filter .checkboxes input {
            width: 26px;
            height: 26px;
        }

        .filter .checkboxes label {
            padding-left: 5px;
        }

        .calendar {
            margin-bottom: 15px;
        }

        .calendar .head {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            color: #fff;
            padding: 5px 0;
            text-align: center;
            background-color: #00A241;
        }

        .preview .head {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            color: #fff;
            padding: 5px 0;
            text-align: center;
            background-color: #21337F;
        }

        .calendar-wrapper {
            padding: 15px;
            background-color: #fff;
        }

        .calendar input {
            width: calc(50% - 6px);
            height: 32px;
            border: 1px solid #B7B7B7;
            border-radius: 4px;
            color: #21337F;
            padding-left: 10px;
            margin-bottom: 10px;
        }

        .calendar input::placeholder {
            color: #21337F;
        }

        .calendar-wrapper input:first-child {
            margin-right: 8px;
        }

        .calendar-wrapper p {
            margin: 0;
            text-align: center;
        }

        .calendar-wrapper p span.red {
            color: #B60000;
        }

        .calendar-wrapper p span.grey {
            color: #828282;
        }

        .preview-wrapper {
            padding: 25px 15px;
            background-color: #fff;
        }

        .preview-wrapper .icon {
            padding: 5px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-wrapper .text {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
        }

        .preview .wrap {
            display: flex;
            cursor: pointer;
        }

        .preview .preview-btn .wrap {
            border: 1px solid #00A651;
            border-radius: 4px;
        }

        .preview .preview-btn .wrap .text {
            background-color: #00A651;
        }

        .preview .post-btn .wrap {
            border: 1px solid #F7941D;
            border-radius: 4px;
        }

        .preview .post-btn .wrap .text {
            background-color: #F7941D;
        }

        .block {
            padding: 10px;
            background-color: #fff;
            margin-bottom: 15px;
        }

        .slick-slide {
            margin: 0;
        }

        .slick-list {
            width: calc(100% - 60px);
            margin: auto;
        }

        .img-slide {
            position: relative;
        }

        .img-slide .slick-arrow {
            width: 22px;
            height: 22px;
            color: #fff;
            background-color: #C8C8C8;
            font-size: 16px;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
        }

        .img-slide .img-group i {
            cursor: pointer;
            position: absolute;
            top: 2px;
            right: 2px;
            z-index: 5;
        }

        .img-slide .img-group {
            position: relative;
        }

        .img-slide .slick-prev {
            left: 0;
        }

        .img-slide .slick-next {
            right: 0;
        }

        .note {
            text-align: center;
            font-style: unset;
            margin: 10px 0 0 0;
            display: block;
            width: 100%;
        }

        .img-list {
            overflow-x: scroll;
            white-space: nowrap;
        }

        .img-list img {
            width: 110px;
            margin-right: 5px;
        }

        .end .info {
            padding: 15px;
            border-radius: 8px;
            background-color: #003471;
            margin-bottom: 30px;
        }

        .end .info .title {
            font-size: 17px;
            font-weight: 700;
            color: #003471;
            margin-bottom: 10px;

        }

        .end .info .form {
            display: grid;
            grid-template-rows: 1fr 1fr;
            grid-template-columns: 1fr 1fr;
            grid-column-gap: 30px;
            grid-row-gap: 20px;
        }

        .end .info .form .group {
            position: relative;
        }

        .end .info .form input {
            width: 100%;
            height: 36px;
            border: none;
            border-radius: 8px;
            padding-left: 35px;
        }

        .end .info .form i {
            position: absolute;
            font-size: 18px;
            color: #A7A7A7;
            top: 50%;
            left: 9px;
            transform: translateY(-50%);
        }

        .end .outtro {
            background-color: #E1EEF4;
            padding: 20px 15px;
            border: 1px dashed #D0D0D0;
            text-align: justify;
        }

        .end .outtro p {
            margin: 0;
        }

        .end .outtro span {
            color: #0074EB;
        }

        .close-button {
            top: -16px !important;
            right: -17px !important;
        }

        input[name="upload-event"] {
            display: none;
        }
        .message-error-classified{
            position: absolute;
            width: 100%;
            left: 8px;
            top: 99%;
        }

        .map-box {
            background: white;
            height: 405px;
        }

        /* Slider */
        .slick-slider {
            position: relative;

            display: block;
            box-sizing: border-box;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

            -webkit-touch-callout: none;
            -khtml-user-select: none;
            -ms-touch-action: pan-y;
            touch-action: pan-y;
            -webkit-tap-highlight-color: transparent;
        }

        .slick-list {
            position: relative;

            display: block;
            overflow: hidden;

            margin: auto;
            padding: 0;
        }

        .slick-list:focus {
            outline: none;
        }

        .slick-list.dragging {
            cursor: pointer;
            cursor: hand;
        }

        .slick-slider .slick-track,
        .slick-slider .slick-list {
            -webkit-transform: translate3d(0, 0, 0);
            -moz-transform: translate3d(0, 0, 0);
            -ms-transform: translate3d(0, 0, 0);
            -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
        }

        .slick-track {
            position: relative;
            top: 0;
            left: 0;

            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .slick-track:before,
        .slick-track:after {
            display: table;

            content: '';
        }

        .slick-track:after {
            clear: both;
        }

        .slick-loading .slick-track {
            visibility: hidden;
        }

        .slick-slide {
            display: none;
            float: left;

            height: 100%;
            min-height: 1px;
        }

        [dir='rtl'] .slick-slide {
            float: right;
        }

        .slick-slide img {
            display: block;
        }

        .slick-slide.slick-loading img {
            display: none;
        }

        .slick-slide.dragging img {
            pointer-events: none;
        }

        .slick-initialized .slick-slide {
            display: block;
        }

        .slick-loading .slick-slide {
            visibility: hidden;
        }

        .slick-vertical .slick-slide {
            display: block;

            height: auto;

            border: 1px solid transparent;
        }

        .slick-arrow.slick-hidden {
            display: none;
        }

        /*///file*/
        .file .block {
            padding: 10px;
            background-color: #fff;
            margin-bottom: 15px;
        }
        .upload-item h4 {
            font-size: 18px;
            font-weight: 700;
        }
        .upload-item .button:first-of-type {
            margin-right: 10px;
        }
        .upload-item .buttons {
            display: flex;
            justify-content: center;
        }
        .upload-item .button {
            padding: 0 5px;
            font-weight: 700;
            color: #fff;
            background-color: #00A8EC;
            border-radius: 4px;
            cursor: pointer;
        }
        .upload-item {
            flex: calc(50% - 20px);
            text-align: center;
        }
        .upload-item .wrap-in {
            padding: 20px 30px;
            border: 1px dashed #C1C1C1;
            position: relative;
        }
    </style>
@endsection
@section('Content')

    @php
    $check_group = old('group_parent')??($classified->group_parent_parent_id??$classified->group_parent_id);
    @endphp
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
                    <iframe
                        src="{{url('responsive_filemanager/filemanager/dialog.php')}}?type=1&field_id=image_url_file"
                        style="width: 100%; height: 70vh"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper" id="quick-post-2">
        <form action="{{route('admin.classified.edit',$classified->id)}}" id="post_edit" method="post">
            @csrf
            <input type="hidden" value="{{$check_group}}" id="group_parent" name="group_parent">
{{--            <input type="hidden" class="group_parent" name="group_parent" id="group_parent"--}}
{{--                   value="{{(old('group_parent')?old('group_parent'):$classified_type)}}">--}}
            <input type="hidden" name="created_at" value="{{$classified->created_at}}">
            <div class="row three-box">
                <div class="col-md-4 box {{$check_group=='2'?"active":""}}" id="nhadatban" data-group_id="2">
                    <div class="image">
                        <img class="" src="{{url('system/image/quick-post-sell.png')}}">
                    </div>
                    <div class="tag">
                        Đăng tin nhà đất bán
                    </div>
                </div>
                <div class="col-md-4 box {{$check_group=='10'?"active":""}}"
                     id="nhadatchothue" data-group_id="10">
                    <div class="image">
                        <img src="{{url('system/image/quick-post-rent.png')}}">
                    </div>
                    <div class="tag">
                        Đăng tin nhà đất cho thuê
                    </div>
                </div>
                <div class="col-md-4 box {{$check_group=='18'?"active":""}}" id="canmuacanthue" data-group_id="18">

                    <div class="image">
                        <img src="{{url('system/image/quick-post-buy.png')}}">
                    </div>
                    <div class="tag">
                        Đăng tin cần mua/cần thuê
                    </div>
                </div>
            </div>

            <div class="row content">
                <div class="col-md-8">
                    <label for="">Tiêu đề tin đăng
                        <span class="sell">nhà đất bán *</span>
                        <span class="rent">nhà đất cho thuê *</span>
                        <span class="buy-rent">cần mua, cần thuê *</span>
                    </label>
                    <textarea placeholder="Tối đa 99 ký tự" maxlength="99" name="title"
                              id="quick-post-title">{{old('title')??$classified->classified_name}}</textarea>
                    @if($errors->count()>0 || $errors->has('title'))
                        <small class="text-danger error-message-custom py-2">
                            {{$errors->first('title')}}
                        </small>
                    @endif
                    <label for="">Nội dung tin đăng <span>*</span></label>
                    <textarea class="form-control" placeholder="Tối thiểu 50 ký tự" name="content" minlength="50"
                              id="quick-post-content">{{old('content')??$classified->classified_description}}</textarea>
                    @if($errors->count()>0  || $errors->has('content'))
                        <small class="text-danger error-message-custom py-2">
                            {{$errors->first('content')}}
                        </small>
                    @endif
                    <span class="note">Nội dung tin đăng không bao gồm hình ảnh</span>
                    <label for="">Đường dẫn video</label>
                    <textarea placeholder="Nhập đường dẫn" maxlength="99" name="video_url"
                              id="quick-post-title">{{old('video_url')??$classified->video}}</textarea>
                    @if($errors->count()>0 || $errors->has('video_url'))
                        <small class="text-danger error-message-custom py-2">
                            {{$errors->first('video_url')}}
                        </small>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="head">Mẹo đăng tin hiệu quả</div>
                    <div class="body">
                        <p><span>Hình ảnh: </span>Các tin đăng có hình ảnh &amp; rõ nét sẽ hiệu quả hơn các tin đăng
                            không có hình ảnh. Nếu có thể, hãy tối ưu kích thước hình ảnh của bạn nhỏ hơn 200Kb để tăng
                            tốc độ trải nghiệm của người dùng khi xem tin.</p>
                        <p><span>Tiêu đề: </span>Thỏa mãn các tiêu chí kích thích sự tò mò của khách hàng, khẳng định
                            được giá trị của sản phẩm đang bán.</p>
                        <p><span>Nội dung: </span>Cần đầy đủ thông tin và minh bạch về giá, pháp lý. Các tin đăng có nội
                            dung không minh bạch sẽ không được duyệt hiển thị trên hệ thống.</p>
                        <p><span>Tối ưu hiển thị trên Google: </span>Giúp tin đăng của bạn có thứ hạng cao trên Google
                            và được nhiều người tiếp cận.</p>
                        <p><span>Xem thêm: </span><a href="#">Cách đăng tin bất động sản hiệu quả</a></p>
                    </div>
                </div>
            </div>
            <div class="row filter">
                <div class="col-md-8">
                    <label for="">Thuộc dự án (nếu có)</label>
                    <select  name="project" id="project" >
                        <option value="">Dự án</option>
                        @foreach($project as $item)
                            <option {{$item->id == old('project')??$classified->project_id?"selected":""}} value="{{$item->id}}">{{$item->project_name}}</option>
                        @endforeach
                    </select>
                    @if($errors->count()>0&&$errors->has('project'))
                        <small class="text-danger error-message-custom py-2">
                            {{$errors->first('project')}}
                        </small>
                    @endif
                    <div class="row">
                        <div class="col-md-4 checked_canmuacanthue buy-rent">
                            <div class="block-dashed">
                                <h6 class="title">Loại<span>*</span></h6>
                                <div class="check-radio">
                                    <div class="form-check-inline">
                                        <input type="radio" id="radiocanmua" value="19" class="radiocanmua"
                                               name="canmuacanthue">
                                        <label for="radiocanmua" class="ml-1" style="font-weight: 500">Cần mua</label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input type="radio" id="radiocanthue" value="20" class="radiocanmua"
                                               name="canmuacanthue">
                                        <label for="radiocanthue" class="ml-1" style="font-weight: 500">Cần thuê</label>
                                    </div>
                                </div>
                            </div>
                            @if($errors->has('canmuacanthue') || $errors->has('canmuacanthue'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('canmuacanthue') ?? $errors->first('canmuacanthue')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="">Diện tích
                                <span>*</span>
                            </label>
                            <div class="d-flex">
                                <input style="border-radius:4px 0 0 4px" type="number" class="classified_area"
                                       name="classified_area" value="{{old('classified_area')??$classified->classified_area}}" required>
                                <select required style="border-radius: 0" name="dientich">
                                    <option value="7">m2</option>
                                </select>
                            </div>
                            @if($errors->count()>0 || $errors->has('classified_area'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('classified_area')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-4 giaban">
                            <label class="sell" for="">{{$properties[7]}}<span>*</span></label>
                            <label class="rent" for="">{{$properties[8]}}<span>*</span></label>
                            <label class="buy-rent" for="">{{$properties[9]}}<span>*</span></label>
                            <div class="d-flex">
                                <input style="border-radius:4px 0 0 4px" name="giaban" type="number"
                                       value="{{(old('giaban')??$classified->classified_price)!=null?(old('giaban')??(int)$classified->classified_price):""}}">
                                <select required style="border-radius: 0" name="donviban">
                                    @foreach($gianhadatban as $item)
                                        <option
                                            {{($classified->price_unit_id == $item->id)?"selected":""}} value="{{$item->id}}">{{$item->unit_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->count()>0 || $errors->has('giaban'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('giaban')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-4">

                        {{--                            Pháp lý--}}
                            <label for="">{{$properties[17]}}<span>*</span></label>
                            <select required name="phaply">
                                @foreach($phaply as $item)
                                    <option
                                        {{old('phaply')??$classified->classified_juridical==$item->id?"selected":""}} value="{{$item->id}}">{{$item->param_name}}</option>
                                @endforeach
                            </select>
                            @if($errors->count()>0 || $errors->has('phaply'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('phaply') }}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-4 noithat">
{{--                            phòng ngủ--}}
                            <label for="">{{$properties[12]}}</label>
                            <select required name="phongngu" class="noithat_null" id="phongngu">
                                @foreach($phongngu as $item)
                                    <option {{old('phongngu')??$classified->num_bed == $item->id?"selected":""}} value="{{$item->id}}">{{$item->param_name}}</option>
                                @endforeach
                            </select>
                            @if($errors->count()>0 && $errors->has('phongngu'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('phongngu')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-4">
{{--                            phòng vệ sinh--}}
                            <label for="">{{$properties[13]}}</label>
                            <select required name="phongvesinh" class="noithat_null" id="phongvesinh">
                                @foreach($phongvesinh as $item)
                                    <option {{old('phongvesinh')??$classified->num_toilet == $item->id?"selected":""}} value="{{$item->id}}">{{$item->param_name}}</option>
                                @endforeach
                            </select>
                            @if($errors->count()>0 && $errors->has('phongvesinh'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('phongvesinh')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-4">
{{--                            Hướng--}}
                            <label for="">{{$properties[16]}}</label>
                            <select required name="huong" id="huong">
                                @foreach($huong as $item)
                                    <option
                                        {{old('huong')??$classified->classified_direction==$item->id?"selected":""}} value="{{$item->id}}">{{$item->direction_name}}</option>
                                @endforeach
                            </select>
                            @if($errors->count()>0 && $errors->has('huong'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('huong')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-4 rent">
{{--                            Ở tối đa--}}
                            <label for="">{{$properties[6]}}</label>
                            <select required name="nguoitoida" class="noithat_null rent" id="nguoitoida">
                                @foreach($nguoitoida as $item)
                                    <option {{old('nguoitoida')??$classified->num_people == $item->id?"selected":""}} value="{{$item->id}}">{{$item->param_name}}</option>
                                @endforeach
                            </select>
                            @if($errors->count()>0 && $errors->has('nguoitoida'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('nguoitoida')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-4 rent">
{{--                            cọc trước--}}
                            <label for="">{{$properties[11]}}</label>
                            <select required name="coctruoc" id="coctruoc">
                                @foreach($coctruoc as $item)
                                    <option  {{old('coctruoc')??$classified->advance_stake==$item->id?"selected":""}}  value="{{$item->id}}">{{$item->param_name}}</option>
                                @endforeach
                            </select>
                            @if($errors->count()>0 && $errors->has('coctruoc'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('coctruoc')}}
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="row">

                    </div>
                    <div class="row" id="locationRequest">
                        <div class="col-md-4">
                            <label for="">Vị trí <span>*</span></label>
                            <input type="text" name="duong" id="duong" value="{{old('duong')??$classified->address}}">
                            @if($errors->has('duong') || $errors->has('duong'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('duong') ?? $errors->first('duong')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="" class="blank">Tỉnh/thành phố</label>
                            <select required name="tinh" id="tinh"
                                    onchange="get_district(this,'{{route('param.get_district')}}','#huyen',null,null)">
                                <?php
                                if (old('tinh') !== null) {
                                    $value = old('tinh');
                                } else {
                                    $value = $classified->province_id;
                                }
                                ?>
                                @foreach($tinh as $item)
                                    <option
                                        {{($value == $item->id)?"selected":""}} value="{{$item->id}}">{{$item->province_name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tinh') || $errors->has('tinh'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('tinh') ?? $errors->first('tinh')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="" class="blank">Quận/huyện</label>
                            <select required  name="huyen" id="huyen"
                                    onchange="get_ward(this,'{{route('param.get_ward')}}','#xa','{{old('huyen')}}',null)">
                                <option value="" disabled selected>Quận/huyện</option>
                            </select>
                            @if($errors->has('huyen') || $errors->has('huyen'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('huyen') ?? $errors->first('huyen')}}
                                </small>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="" class="blank">Blank</label>
                            <select required name="xa" id="xa">
                                <option value="" disabled selected>Phường/xã</option>
                            </select>
                            @if($errors->has('xa') || $errors->has('xa'))
                                <small class="text-danger error-message-custom py-2">
                                    {{$errors->first('xa') ?? $errors->first('xa')}}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
{{--                    mô hình --}}
                    <label for="">{{$properties[2]}}<span>*</span></label>
                    <select required name="group_id" id="mohinh"></select>
                    @if($errors->count()>0 && $errors->has('group_id'))
                        <small class="text-danger error-message-custom py-2">
                            {{$errors->first('group_id')}}
                        </small>
                    @endif
{{--                    tình trạng--}}
                    <label for="">{{$properties[5]}}<span>*</span></label>
                    <select required name="tinhtrang" id="tinhtrang">
                        <option value="" selected disabled>Không khả dụng</option>
                    </select>
                    @if($errors->has('tinhtrang') || $errors->has('tinhtrang'))
                        <small class="text-danger error-message-custom py-2">
                            {{$errors->first('tinhtrang') ?? $errors->first('tinhtrang')}}
                        </small>
                    @endif
                    <div>
{{--                        nội thất--}}
                        <label for="">{{$properties[18]}}</label>
                        <select required name="noithat" id="noithat">
                            <option value="" selected disabled>Không khả dụng</option>
                        </select>
                        @if($errors->has('noithat') || $errors->has('noithat'))
                            <small class="text-danger error-message-custom py-2">
                                {{$errors->first('noithat') ?? $errors->first('noithat')}}
                            </small>
                        @endif
                    </div>
                    <div class="checkboxes mt-4">
{{--                         tin chính chủ --}}
                        <div class="group">
                            <input type="checkbox" name="is_monopoly" {{old('is_monopoly')??$classified->is_monopoly ==1?"checked":""}}>
                            <label for="">{{$properties[0]}}</label>
                        </div>
{{--                         miễn môi giới--}}
                        <div class="group">
                            <input type="checkbox" name="is_broker" {{old('is_broker')??$classified->is_broker ==1?"checked":""}}>
                            <label for="">{{$properties[1]}}</label>
                        </div>
                    </div>
{{--                    internet --}}
                    <div class="rent buy-rent">
                        <div class="checkboxes">
                            <div class="group">
                                <input type="checkbox" name="is_internet" {{old('is_internet')??$classified->is_internet ==1?"checked":""}}>
                                <label for="">{{$properties[19]}}</label>
                            </div>
                            <div class="group">
                                <input type="checkbox" name="is_freezer" {{old('is_freezer')??$classified->is_freezer ==1?"checked":""}}>
{{--                                điều hòa--}}
                                <label for="">{{$properties[14]}}</label>
                            </div>
                        </div>
                        <div class="checkboxes">
                            <div class="group">
                                <input type="checkbox" name="is_balcony" {{old('is_balcony')??$classified->is_balcony ==1?"checked":""}}>
{{--                                    ban công--}}
                                <label for="">{{$properties[20]}}</label>
                            </div>
                            <div class="group">
                                <input type="checkbox"
                                       name="is_mezzanino" {{old('is_mezzanino')??$classified->is_mezzanino ==1?"checked":""}}>
{{--                                gác lửng --}}
                                <label for="">{{$properties[22]}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row meta">
                <div class="col-md-8">
                    <div class="map-box map-box-reponsive col-sm-12 col-md-12">
                        <div id="map" class="w-100 h-100"></div>
                        <input type="hidden" name="map_latitude" id="map_latitude"
                               value="{{ old('map_latitude') ?? $classified->map_latitude}}">
                        <input type="hidden" name="map_longtitude" id="map_longtitude"
                               value="{{ old('map_longtitude') ??  $classified->map_longtitude}}">
                        <small class="text-danger error-message-custom" id="msg_map_longtitude"></small>
                        @if($errors->has('map_latitude') || $errors->has('map_longtitude'))
                            <small class="text-danger error-message-custom py-2">
                                {{$errors->first('map_latitude') ?? $errors->first('map_longtitude')}}
                            </small>
                        @endif
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="preview">
                        <div class="head">Xem trước &amp; Đăng tin</div>
                        <div class="preview-wrapper">
                            <div class="row">
                                <div class="col-md-6 preview-btn">
                                    <div class="wrap">
                                        <div class="text preview-classified">Xem trước</div>
                                        <div class="icon">
                                            <img src="{{url('system/images/icons/preview-icon.png')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 post-btn">
                                    <div class="wrap ">
                                        <button type="submit"  class="text">Cập nhật </button>
                                        <div class="icon">
                                            <img src="{{url('system/images/icons/upload-icon.png')}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row file">
                <div class="col-md-8">
                    <div class="block upload">
                        <div class="upload-item">
                            <h4>Tải lên ảnh tin rao<span>*</span></h4>
                            <div class="wrap-out">
                                <div class="wrap-in choose-image" data-toggle="modal" data-target="#modalFile">
                                    <img src="{{url('system/image/upload-file.png')}}" style="width: 100px">
{{--                                    <p>Bạn có thể chọn nhiều hình ảnh để tải lên cùng một lúc. Thay đổi thứ tự hình ảnh--}}
{{--                                        bằng Kéo &amp; Thả.</p>--}}
                                    <div class="buttons">
{{--                                        <div class="button button-upload">Tải ảnh lên</div>--}}
                                        <input type="hidden" id="image_url_file" name="image_url"
                                               value="{{$classified->image_perspective}}"
                                               onchange="classified_previewImgFromInputText(this,'#add-gallery', '#image_url_order')">
                                        <input type="hidden" id="image_url_order" name="image_url_order"
                                               value="{{old('image_url_order')}}">
                                        <div class="button button-select">Chọn ảnh có sẵn</div>
                                        <input type="file" name="upload-event">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="block">
                        <div class="img-slide" id="add-gallery">
                        </div>
                    </div>
                    <div class="recommend">
                        Gợi ý hình ảnh liên quan
                    </div>
                    <div class="block">
                        <div class="img-list" id="image_project"></div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                @include('user.classified.partials._meta-form', [
                    'classified' => $classified,
                    'canEdit' => true
                ])
            </div>

            <div class="row end">
                <div class="col-md-8">
                    <div class="info">
                        <div class="title">Thông tin liên hệ</div>
                        <div class="form">
                            <div class="group">
                                <i class="fas fa-user"></i>
                                <input type="text" value="{{$classified->contact_name}}" name="contact_name" placeholder="Nhập Họ &amp; Tên">
                                @if($errors->has('contact_name') || $errors->has('contact_name'))
                                    <small class="text-danger message-error-classified">
                                        {{$errors->first('contact_name') ?? $errors->first('contact_name')}}
                                    </small>
                                @endif
                            </div>

                            <div class="group">
                                <i class="fas fa-phone"></i>
                                <input type="text" name="contact_phone" value="{{$classified->contact_phone}}" placeholder="Nhập Số điện thoại">
                                @if($errors->has('contact_phone') || $errors->has('contact_phone'))
                                    <small class="text-danger message-error-classified">
                                        {{$errors->first('contact_phone') ?? $errors->first('contact_phone')}}
                                    </small>
                                @endif
                            </div>

                            <div class="group">
                                <i class="fas fa-envelope"></i>
                                <input type="text" name="contact_email" value="{{$classified->contact_email}}" placeholder="Nhập Email">
                                @if($errors->has('contact_email') || $errors->has('contact_email'))
                                    <small class="text-danger message-error-classified">
                                        {{$errors->first('contact_email') ?? $errors->first('contact_email')}}
                                    </small>
                                @endif
                            </div>
                            <div class="group">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text" name="contact_address" value="{{$classified->contact_address}}" placeholder="Nhập địa chỉ">
                                @if($errors->has('contact_address') || $errors->has('contact_address'))
                                    <small class="text-danger message-error-classified">
                                        {{$errors->first('contact_address') ?? $errors->first('contact_address')}}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="modal fade " id="previewModal" tabindex="-1"  role="dialog" aria-labelledby="previewModalLabel"
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
                            <div id="content-preview">
{{--                                @include('Admin.Classified.Component.nhadatban')--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('Script')
    <script src="js/main_classified.js"></script>
    <script src="js/classified/slick.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script type="text/javascript" src="js/map.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={!! $google_api_key !!}&callback=callback&libraries=&v=weekly"
        async></script>
        <script>
            @if(count($errors) > 0)
            toastr.error("Vui lòng kiểm tra các trường");
        @endif
    </script>

    <script>
        $('.preview-classified').click( function (){

                    let data = $('#post_edit').serializeArray();
                    var data_map = $('#map').html();
                    console.log(data);
                $('#content-preview').empty();
                   $.ajax({
                        type:"POST",
                        url : "{{route('admin.classified.previewnhadatban')}}",
                        data: { data,_token: '{{csrf_token()}}' },
                        success: function (result) {
                            $('#content-preview').html(result);
                            $('#previewModal').modal('show');
                        },
                    }).fail(function(data, textStatus, errorThrown){
                        toastr.error('Vui lòng kiểm tra các trường');
                    });
        });
    </script>
{{--    <script>--}}
{{--        $(document).ready(function () {--}}
{{--            if ('{{old('group_parent')}}' != "") {--}}
{{--                var group_parent = '{{old('group_parent')}}';--}}
{{--                if (group_parent == "nhadatchothue") {--}}
{{--                    if ($('.box').hasClass('active')) {--}}
{{--                        $('.box').removeClass('active');--}}
{{--                        $('#nhadatchothue').addClass('active');--}}
{{--                    }--}}
{{--                    $('#nhadatchothue').addClass('active');--}}
{{--                } else if (group_parent == "nhadatban") {--}}
{{--                    if ($('.box').hasClass('active')) {--}}
{{--                        $('.box').removeClass('active');--}}
{{--                        $('#nhadatban').addClass('active');--}}
{{--                    }--}}
{{--                    $('#nhadatban').addClass('active');--}}
{{--                } else {--}}
{{--                    if ($('.box').hasClass('active')) {--}}
{{--                        $('.box').removeClass('active');--}}
{{--                        $('#canmuacanthue').addClass('active');--}}
{{--                        $('.canmuacanthue').show();--}}
{{--                    }--}}
{{--                    $('.canmuacanthue').show();--}}
{{--                    $('#canmuacanthue').addClass('active');--}}
{{--                    if (group_parent == "canmua") {--}}
{{--                        $('.nhadatchothue').hide();--}}
{{--                        $('.giathue').hide();--}}
{{--                        $('.giaban').show();--}}
{{--                        $("input[name=canmuacanthue][value='19']").prop('checked', true);--}}
{{--                    } else {--}}
{{--                        $('.nhadatchothue').show();--}}
{{--                        $('.giathue').show();--}}
{{--                        $('.giaban').hide();--}}
{{--                        $("input[name=canmuacanthue][value='20']").prop('checked', true)--}}
{{--                    }--}}
{{--                }--}}
{{--            } else {--}}
{{--                var group_parent = "{{$classified_type}}";--}}
{{--                if (group_parent == "nhadatchothue") {--}}
{{--                    if ($('.box').hasClass('active')) {--}}
{{--                        $('.box').removeClass('active');--}}
{{--                        $('#nhadatchothue').addClass('active');--}}
{{--                    }--}}
{{--                    $('#nhadatchothue').addClass('active');--}}

{{--                } else if (group_parent == "nhadatban") {--}}
{{--                    if ($('.box').hasClass('active')) {--}}
{{--                        $('.box').removeClass('active');--}}
{{--                        $('#nhadatban').addClass('active');--}}
{{--                    }--}}
{{--                    $('#nhadatban').addClass('active');--}}
{{--                } else {--}}
{{--                    if ($('.box').hasClass('active')) {--}}
{{--                        $('.box').removeClass('active');--}}
{{--                        $('#canmuacanthue').addClass('active');--}}
{{--                        $('.canmuacanthue').show();--}}
{{--                    }--}}
{{--                    $('#canmuacanthue').addClass('active');--}}
{{--                    if (group_parent == "canmua") {--}}
{{--                        $('.nhadatchothue').hide();--}}
{{--                        $('.giathue').hide();--}}
{{--                        $('.giaban').show();--}}
{{--                        $("input[name=canmuacanthue][value='19']").prop('checked', true);--}}
{{--                    } else {--}}
{{--                        $('.nhadatchothue').show();--}}
{{--                        $('.giathue').show();--}}
{{--                        $('.giaban').hide();--}}
{{--                        $("input[name=canmuacanthue][value='20']").prop('checked', true)--}}
{{--                    }--}}
{{--                }--}}
{{--            }--}}

{{--            get_noithat('#group_id', '{{route('param.get_furniture')}}', '#noithat', {{old('group_id') ??$classified->group_id}}, {{old('noithat') ?? $classified->classified_furniture ?? "null"}});--}}
{{--            get_tinhtrang('#group_id', '{{route('param.get_progress')}}', '#tinhtrang', {{old('group_id') ?? $classified->group_id}}, {{old('trangthai') ?? $classified->classified_progress ?? "null"}})--}}
{{--        });--}}
{{--        $('#inputAvatar').change(function () {--}}

{{--        });--}}
{{--    </script>--}}
{{--    <script>--}}
{{--        if ('{{$classified_type == "nhadatchothue"}}') {--}}
{{--            $('.canmuacanthue').hide();--}}
{{--            var group_id = 10;--}}
{{--            if (group_id != "" && group_id != "18") {--}}
{{--                var url = "{{route('param.get_child')}}";--}}
{{--                $.ajax({--}}
{{--                    url: url,--}}
{{--                    type: "GET",--}}
{{--                    dataType: "json",--}}
{{--                    data: {--}}
{{--                        group_id: group_id--}}
{{--                    },--}}
{{--                    success: function (data) {--}}
{{--                        $('#mohinh').html('');--}}
{{--                        $('#mohinh').append(data['group_child']);--}}
{{--                        var group_id = '{{$classified->group_id}}';--}}
{{--                        $('#mohinh').val(group_id).select();--}}
{{--                        get_tinhtrang('#mohinh', '{{route('param.get_progress')}}', '#tinhtrang', null, '{{$classified->classified_progress}}')--}}
{{--                        get_noithat('#mohinh', '{{route('param.get_furniture')}}', '#noithat', null, '{{$classified->classified_furniture}}')--}}
{{--                    }--}}
{{--                });--}}
{{--            } else {--}}
{{--                $('#mohinh').html('<option selected disabled>Mô hình</option>');--}}
{{--            }--}}
{{--        }--}}
{{--        if ('{{$classified_type == "canmua"}}') {--}}
{{--            $('.canmuacanthue').show();--}}
{{--            $('.giathue').hide();--}}
{{--            var group_id = 19;--}}
{{--            if (group_id != "") {--}}
{{--                var url = "{{route('param.get_child')}}";--}}
{{--                $.ajax({--}}
{{--                    url: url,--}}
{{--                    type: "GET",--}}
{{--                    dataType: "json",--}}
{{--                    data: {--}}
{{--                        group_id: group_id--}}
{{--                    },--}}
{{--                    success: function (data) {--}}
{{--                        $('#mohinh').html('');--}}
{{--                        $('#mohinh').append(data['group_child']);--}}
{{--                        var group_id = '{{$classified->group_id}}';--}}
{{--                        $('#mohinh').val(group_id).select();--}}
{{--                        get_tinhtrang('#mohinh', '{{route('param.get_progress')}}', '#tinhtrang', null, '{{$classified->classified_progress}}')--}}
{{--                        get_noithat('#mohinh', '{{route('param.get_furniture')}}', '#noithat', null, '{{$classified->classified_furniture}}')--}}
{{--                    }--}}
{{--                });--}}
{{--            } else {--}}
{{--                $('#mohinh').html('<option selected disabled>Mô hình</option>');--}}
{{--            }--}}
{{--        }--}}
{{--        if ('{{$classified_type == "canthue"}}') {--}}
{{--            $('.canmuacanthue').show();--}}
{{--            $('.giathue').show();--}}
{{--            var group_id = 20;--}}
{{--            if (group_id != "") {--}}
{{--                var url = "{{route('param.get_child')}}";--}}
{{--                $.ajax({--}}
{{--                    url: url,--}}
{{--                    type: "GET",--}}
{{--                    dataType: "json",--}}
{{--                    data: {--}}
{{--                        group_id: group_id--}}
{{--                    },--}}
{{--                    success: function (data) {--}}
{{--                        $('#mohinh').html('');--}}
{{--                        $('#mohinh').append(data['group_child']);--}}
{{--                        var group_id = '{{$classified->group_id}}';--}}
{{--                        $('#mohinh').val(group_id).select();--}}
{{--                        get_tinhtrang('#mohinh', '{{route('param.get_progress')}}', '#tinhtrang', null, '{{$classified->classified_progress}}')--}}
{{--                        get_noithat('#mohinh', '{{route('param.get_furniture')}}', '#noithat', null, '{{$classified->classified_furniture}}')--}}
{{--                    }--}}
{{--                });--}}
{{--            } else {--}}
{{--                $('#mohinh').html('<option selected disabled>Mô hình</option>');--}}
{{--            }--}}
{{--        }--}}
{{--        if ('{{$classified_type == "nhadatban"}}') {--}}
{{--            $('.canmuacanthue').hide();--}}
{{--            $('.giathue').hide();--}}
{{--            var group_id = 2;--}}
{{--            if (group_id != "" && group_id != "18") {--}}
{{--                var url = "{{route('param.get_child')}}";--}}
{{--                $.ajax({--}}
{{--                    url: url,--}}
{{--                    type: "GET",--}}
{{--                    dataType: "json",--}}
{{--                    data: {--}}
{{--                        group_id: group_id--}}
{{--                    },--}}
{{--                    success: function (data) {--}}
{{--                        $('#mohinh').html('');--}}
{{--                        $('#mohinh').append(data['group_child']);--}}
{{--                        var group_id = '{{$classified->group_id}}';--}}
{{--                        $('#mohinh').val(group_id).select();--}}
{{--                        get_tinhtrang('#mohinh', '{{route('param.get_progress')}}', '#tinhtrang', null, '{{$classified->classified_progress}}')--}}
{{--                        get_noithat('#mohinh', '{{route('param.get_furniture')}}', '#noithat', null, '{{$classified->classified_furniture}}')--}}
{{--                    }--}}
{{--                });--}}
{{--            } else {--}}
{{--                $('#mohinh').html('<option selected disabled>Mô hình</option>');--}}
{{--            }--}}
{{--        }--}}
{{--    </script>--}}
{{--    <script>--}}
{{--        let isClicked = false;--}}

{{--        function callback() {--}}
{{--            let initLocation = {lat: {{$classified->map_latitude}}, lng: {{$classified->map_longtitude}}}--}}
{{--            console.log(initLocation);--}}
{{--            initMap('map', 'input#map_latitude', 'input#map_longtitude', initLocation);--}}
{{--        }--}}

{{--        async function callbackMarkerToAddress(result) {--}}
{{--            $('#duong').val('');--}}
{{--            isClicked = true;--}}
{{--            let province = await get_province_by_name(result.province_name, '{{route('param.get_province_name')}}', '#tinh')--}}
{{--            await sleep(500)--}}
{{--            const district = await get_district_by_name(result.district_name, '{{route('param.get_district_name')}}', province['province'].id, '#huyen')--}}
{{--            await sleep(500)--}}
{{--            const ward = await get_ward_by_name(result.ward_name, '{{route('param.get_ward_name')}}', district['district'].id, '#xa')--}}
{{--            await sleep(500)--}}
{{--            if (ward['ward']?.id != null) {--}}
{{--                $('#duong').val(result.road_name)--}}
{{--            }--}}
{{--            isClicked = false--}}
{{--        }--}}

{{--        $('#locationRequest select').change(function () {--}}
{{--            if (!isClicked)--}}
{{--                setGoogleMap('#duong', '#huyen', '#xa', '#tinh')--}}
{{--        });--}}
{{--    </script>--}}
{{--    <script>--}}
{{--        $(document).ready(function () {--}}
{{--            classified_previewImgFromInputText('#image_url_file','#add-gallery', '#image_url_order')--}}
{{--        });--}}
{{--        $('.box').click(function () {--}}
{{--            if ($('.box').hasClass('active')) {--}}
{{--                $('.box').removeClass('active');--}}
{{--                $(this).addClass('active');--}}
{{--                var group_id = $(this).data('group_id');--}}
{{--                if (group_id != "" && group_id != "18") {--}}
{{--                    var url = "{{route('param.get_child')}}";--}}

{{--                    $.ajax({--}}
{{--                        url: url,--}}
{{--                        type: "GET",--}}
{{--                        dataType: "json",--}}
{{--                        data: {--}}
{{--                            group_id: group_id--}}
{{--                        },--}}
{{--                        success: function (data) {--}}
{{--                            $('#mohinh').html('');--}}
{{--                            $('#mohinh').append(data['group_child']);--}}
{{--                        }--}}
{{--                    });--}}
{{--                } else {--}}
{{--                    $('#mohinh').html('<option selected disabled>Mô hình</option>');--}}
{{--                }--}}
{{--                if (group_id != "" && group_id == 18) {--}}
{{--                    var url = "{{route('param.get_child')}}";--}}

{{--                    $.ajax({--}}
{{--                        url: url,--}}
{{--                        type: "GET",--}}
{{--                        dataType: "json",--}}
{{--                        data: {--}}
{{--                            group_id: 19--}}
{{--                        },--}}
{{--                        success: function (data) {--}}
{{--                            $('#mohinh').html('');--}}
{{--                            $('#mohinh').append(data['group_child']);--}}
{{--                        }--}}
{{--                    });--}}
{{--                } else {--}}
{{--                    $('#mohinh').html('<option selected disabled>Mô hình</option>');--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}
{{--        $('.canmuacanthue').change(function () {--}}
{{--            var group_id = $(this).val();--}}
{{--            if (group_id != "" && group_id != "18") {--}}
{{--                var url = "{{route('param.get_child')}}";--}}

{{--                $.ajax({--}}
{{--                    url: url,--}}
{{--                    type: "GET",--}}
{{--                    dataType: "json",--}}
{{--                    data: {--}}
{{--                        group_id: group_id--}}
{{--                    },--}}
{{--                    success: function (data) {--}}
{{--                        $('#mohinh').html('');--}}
{{--                        $('#mohinh').append(data['group_child']);--}}
{{--                    }--}}
{{--                });--}}
{{--            } else {--}}
{{--                $('#mohinh').html('<option selected disabled>Mô hình</option>');--}}
{{--            }--}}
{{--            if (group_id == 19) {--}}
{{--                $('.group_parent').val('canmua');--}}
{{--                $('.giathue').hide();--}}
{{--                $('.giaban').show();--}}
{{--                $('.nhadatchothue').hide();--}}
{{--                $('.canmuacanthue').show();--}}
{{--            }--}}
{{--            if (group_id == 20) {--}}
{{--                $('.group_parent').val('canthue');--}}
{{--                $('.giathue').show();--}}
{{--                $('.giaban').hide();--}}
{{--                $('.nhadatchothue').show();--}}
{{--                $('.canmuacanthue').show();--}}
{{--            }--}}
{{--        });--}}
{{--        $('#mohinh').change(function () {--}}
{{--            get_noithat('#mohinh', '{{route('param.get_furniture')}}', '#noithat', '{{old('noithat')}}', null, '.noithat', '.noithatnull');--}}
{{--            get_tinhtrang('#mohinh', '{{route('param.get_progress')}}', '#tinhtrang', '{{old('tinhtrang')}}', '{{$classified->classified_progress}}');--}}
{{--        });--}}
{{--    </script>--}}
{{--    <script>--}}
{{--        $(document).ready(function(){--}}
{{--        $('#nhadatban').click(function () {--}}
{{--            $('.group_parent').val('nhadatban');--}}
{{--            $('.giathue').hide();--}}
{{--            $('.giaban').show();--}}
{{--            $('.nhadatchothue').hide();--}}
{{--            $('.canmuacanthue').hide();--}}
{{--        });--}}
{{--        $('#nhadatchothue').click(function () {--}}
{{--            $('.group_parent').val('nhadatchothue');--}}
{{--            $('.giathue').show();--}}
{{--            $('.giaban').hide();--}}
{{--            $('.nhadatchothue').show();--}}
{{--            $('.canmuacanthue').hide();--}}
{{--        });--}}
{{--        $('#canmuacanthue').click(function () {--}}
{{--            $('.group_parent').val('canmua');--}}
{{--            var value = $('input[name=canmuacanthue]:checked').val();--}}
{{--            if (value == 19) {--}}
{{--                $('.nhadatchothue').hide();--}}
{{--                $('.giathue').hide();--}}
{{--                $('.giaban').show();--}}
{{--            } else {--}}
{{--                $('.nhadatchothue').show();--}}
{{--                $('.giathue').show();--}}
{{--                $('.giaban').hide();--}}
{{--            }--}}
{{--            $('.canmuacanthue').show();--}}
{{--            $('.nhadatchothue').hide();--}}
{{--        });--}}
{{--    });--}}
{{--    </script>--}}
    <script>
        $('#project').change(function(){
            var project_id =$('#project').val();
            if(project_id!=-1){
                // ajax
                var url = "{{route('param.get_image_project')}}";
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    data: {
                        project_id: project_id
                    },
                    success: function (data) {
                        let image_array =[];
                        if(data['project_image'] == '') return;
                        $('#image_project').empty();
                        try {
                            image_array =JSON.parse(data['project_image']);
                        }catch (e) {
                            image_array.push(data['project_image']);
                        }
                        for(let image of image_array){
                            $('#image_project').append(`<img class="choose_project" data-content="`+image+`" src="`+image+`">`);
                        }
                    }
                });
            }
        });
        // click add image from project

    </script>
    <script>

            $('#image_project').on('click','.choose_project',function(){
                var image = $(this).data('content');
                var list_image = $('#image_url_file');
                push_image_to_array(list_image,image);
                classified_previewImgFromInputText('#image_url_file','#add-gallery', '#image_url_order')
            });
            // submit fomr

    </script>
    <script>
        $('.submit_form').click(function (){
            $('#post_edit').submit();
        });
        function get_group(url , group_id){
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                data: {
                    group_id:group_id,
                },
                success: function (data) {

                    $('#mohinh').html('');
                    $('#mohinh').append(data['group_child']);
                    var select_group_old = '{{$classified->group_id}}';
                    $('#mohinh').val(select_group_old).select();
                    get_tinhtrang('#mohinh', '{{route('param.get_progress')}}', '#tinhtrang', null, '{{$classified->classified_progress}}')
                    get_noithat('#mohinh', '{{route('param.get_furniture')}}', '#noithat', null, '{{$classified->classified_furniture}}','.noithat_null')
                }
            });
        }
        $(document).ready(function (){
            get_district('#tinh', '{{route('param.get_district')}}', '#huyen', {{old('tinh') ?? $classified->province_id}}, {{old('huyen') ?? $classified->district_id}})
            get_ward('#huyen', '{{route('param.get_ward')}}', '#xa', {{old('huyen') ?? $classified->district_id}}, {{old('xa') ?? $classified->ward_id}})
            var group = '{{$classified->group_parent_parent_id??$classified->group_parent_id}}';
            // group  == 2 nhà đất bán
            // == 10 nhà đất cho thuê
            // == 18 Cần mua cần thuê
             if(group == '2'){
                 $('.rent').hide();
                 $('.buy-rent').hide();
                 $('.sell').show();
             }
            if(group == '10'){
                $('.sell').hide();
                $('.buy-rent').hide();
                $('.rent').show();
            }
            if(group == '18'){
                $('.sell').hide();
                $('.rent').hide();
                $('.buy-rent').show();

            }
            var group_id =   $('.three-box').find('.active');
                    var url = "{{route('param.get_child')}}";
                    get_group(url,group_id.data('group_id'));
        });
        $('body').on('change','#mohinh',function (){
            var group = $(this).val();
            get_tinhtrang('#mohinh', '{{route('param.get_progress')}}', '#tinhtrang', null, '{{$classified->classified_progress}}')
            get_noithat('#mohinh', '{{route('param.get_furniture')}}', '#noithat', null, '{{$classified->classified_furniture}}','.noithat_null')
        });
        $('body').on('click','.three-box .box',function(){
                var selected =  $('.three-box').find('.active');
                $(selected).removeClass('active');
                $(this).addClass('active');
                var group_id =  $(this).data('group_id');
                $('#group_parent').val(group_id);
            if(group_id ==2){
                $('.rent').hide();
                $('.buy-rent').hide();
                $('.sell').show();
            }
            if(group_id == 10){
                $('.sell').hide();
                $('.buy-rent').hide();
                $('.rent').show();
            }
            if(group_id == 18){
                $('.sell').hide();
                $('.rent').hide();
                $('.buy-rent').show();
            }
            var url = "{{route('param.get_child')}}";
            get_group(url,group_id);
        });
        $('.radiocanmua').on('change',function (){
            var url = "{{route('param.get_child')}}";
            get_group(url,$(this).val());
        });
    </script>
    <script>
            let isClicked = false;
            function callback() {
                let initLocation = {lat: {{ $classified->map_latitude ?: 0 }}, lng: {{ $classified->map_longtitude ?: 0 }}}
                initMap('map', 'input#map_latitude', 'input#map_longtitude', initLocation);
            }
            async function callbackMarkerToAddress(result) {
                $('#duong').val('');
                isClicked = true;
                let province = await get_province_by_name(result.province_name, '{{route('param.get_province_name')}}', '#tinh')
                await sleep(500)
                const district = await get_district_by_name(result.district_name, '{{route('param.get_district_name')}}', province['province'].id, '#huyen')
                await sleep(500)
                const ward = await get_ward_by_name(result.ward_name, '{{route('param.get_ward_name')}}', district['district'].id, '#xa')
                await sleep(500)
                if (ward['ward']?.id != null) {
                    $('#duong').val(result.road_name)
                }
                isClicked = false
            }
            $('#locationRequest select').change(function () {
                if (!isClicked)
                    setGoogleMap('#duong', '#huyen', '#xa', '#tinh')
            });
        </script>
@endsection
