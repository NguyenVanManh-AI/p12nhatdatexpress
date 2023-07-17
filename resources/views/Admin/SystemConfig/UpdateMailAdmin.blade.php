@extends("Admin.Layouts.Master")
@section('Content')
    <div class="container-fluid">
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
                                            <p class="text-center mb-0 font-weight-bold text-white">Sửa mail cấu hình</p>
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
                                                <input class="form-control required" type="text" name="mail_host" value="{{old('mail_host') ?? $admin_mail->mail_host}}" placeholder="ex: smtp.gmail.com" id="mail_host">
                                            </div>
                                            <div class="col-2 mt-2">
                                                <p class="float-right font-weight-bold mb-0 " style="line-height: 37px">Cổng</p>
                                            </div>
                                            <div class="col-10 mt-2">
                                                <input class="form-control required" type="number" name="mail_port" placeholder="ex: 587" id="mail_port" value="{{old('mail_port') ?? $admin_mail->mail_port}}">
                                            </div>

                                            <div class="col-12 mt-2">
                                                <div class="row">
                                                    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
                                                        <p class="float-right font-weight-bold mb-0" style="line-height: 37px">Tên đăng nhập</p>
                                                    </div>
                                                    <div class="col-7 col-sm-7 col-md-10 col-lg-10 ">
                                                        <input class="form-control required" type="text" name="mail_username" id="mail_username" value="{{old('mail_username') ?? $admin_mail->mail_username}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-5 col-sm-5 col-md-2 col-lg-2 my-2">
                                                <p class="float-right font-weight-bold mb-0" style="line-height: 37px">Mật khẩu</p>
                                            </div>
                                            <div class="col-7 col-sm-7 col-md-10 col-lg-10 my-2">
                                                <input class="form-control required" type="text" name="mail_password" id="mail_password" value="{{old('mail_password') ?? $admin_mail->mail_password}}">
                                            </div>

                                            <div class="col-10 offset-2 d-flex">
                                                <div class="support-vay2">
                                                    <label class="form-control-409 mr-2">
                                                        <input type="radio" name="mail_encryption" value="tls" id="check_tls" @if($admin_mail->mail_encryption == 'tls') checked @endif>
                                                    </label>
                                                    <label for="check_tls">Bật TLS</label>
                                                </div>
                                                {{--                                                        <button class="btn btn-primary br-0 brr0" id="defaultConfig" type="button">Mặc định</button>--}}
                                                {{--                                                        <div style="height: 38px; padding-bottom: 6px"></div>--}}
                                                <div class="support-vay2">
                                                    <label class="form-control-409 ml-5 mr-2">
                                                        <input type="radio" value="ssl" name="mail_encryption" id="check_ssl" @if($admin_mail->mail_encryption == 'ssl') checked @endif>
                                                    </label>
                                                    <label for="check_ssl">Bật SSL</label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="text-center" id="result-status" style="font-size: 95%">
                                        </div>
                                        <div class="row m-0 pb-3">
                                            <div id="button-order-by1" class="col-6 mt-2">
                                                <div id="loading-button-submit"  class="btn bg-primary float-right" style="border-radius: 0;cursor: context-menu;opacity: 0.8;display: none">
                                                    <div class="d-flex">
                                                        <span class="mr-2">Đang sửa email</span>
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
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('Style')
    <style>
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
    <script type="text/javascript">
        let typeButton = "test";
        let token = '{{ csrf_token() }}';
        let sendMailOk = false;
        let checkAddMailConfig = true

        function submitButton(){
            typeButton= "submit";
            submitMail();
        }
        function sendMailTest(e){
            e.preventDefault()
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
                        // checkAddMailConfig=false;
                        if(response == "<p class='text-success mb-0'>Gửi thử thành công! Bạn có thể thêm email này</p>"){
                            sendMailOk=true;
                            $("#submit-button").attr("disabled", false);
                            $("#send-try-button").attr("disabled", true);
                            disbleButton();
                        }
                    },
                    error: function(data){
                        $('#result-status').html(`<p class='text-danger mb-0'>Gửi thử thất bại! Vui lòng kiểm tra lại thông tin cấu hình</p>`);
                        $('#send-try-button').show();
                        $('#loading-button').hide();
                    }
                });
            }
        }

        function submitMail(){
            typeButton= "submit";
            if(checkAddMailConfig == true){
                $('#submit-button').hide();
                $('#loading-button-submit').show();
                $("#send-try-button").attr("disabled", true);
                $.ajax({
                    url : "{{route('admin.email-campaign.post-edit-mail-config',[$admin_mail->id,\Crypt::encryptString($admin_mail->created_by)])}}",
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
                        if(response == "Thêm thành công"){
                            window.location.replace("{{route('admin.system.general')}}");
                        }else{
                            $('#submit-button').show();
                            $('#loading-button-submit').hide();
                            $('#result-status').html(response);
                            $("#send-try-button").attr("disabled", false);
                            $("#submit-button").attr("disabled", true);

                        }
                    },
                    error: function(data){
                        $('#result-status').html(`<p class='text-danger mb-0'>Sửa thất bại! Vui lòng kiểm tra lại thông tin cấu hình</p>`);
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
            $('#defaultConfig').attr('disabled', true)
        }

    </script>
    <script type="text/javascript">
        var check =1;
        function notitication(){
            if(check ==1){
                toastr.error('Vui lòng kiểm tra các trường');
                check++;
            }
        }
    </script>
@endsection
