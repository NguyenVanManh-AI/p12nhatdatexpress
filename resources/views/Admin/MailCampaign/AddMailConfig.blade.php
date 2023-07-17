@extends('Admin.Layouts.Master')
@section('Title', 'Thêm email cấu hình | Chiến dịch email')
@section('Style')

{{-- link css project chính --}}
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
<style type="text/css">
  #error{background: red;}
</style>
@endsection
@section('Content')
<section>
  <style type="text/css">
    /* màu thông báo lỗi */
    .error{
      color: #dc3545;
    }

    /* reponsive cho điện thoại */
    @media only screen and (min-width: 600px) {
      .box-mail-config {
        width: 70%;margin: auto
      }
    }
  </style>
  <div>
    <div class="col-sm-12 mbup30">
      <div class="row m-0 px-2 pt-3">
        <ol class="breadcrumb mt-1">
          <li class="recye px-2 pt-1 check">
            <a href="{{route('admin.email-campaign.list-mail-config')}}">
              <i class="fa fa-th-list mr-1"></i>Danh sách
            </a>
          </li>
          {{-- kiểm tra phân quyền thùng rác --}}
          @if($check_role == 1  ||key_exists(5, $check_role))
          <li class="phay ml-2 " style="margin-top: 2px !important">
            /
          </li>
          <li class="recye px-2 pt-1 ml-1">
            <a href="{{route('admin.email-campaign.trash-list-mail-config')}}">
              Thùng rác
            </a>
          </li>
          @endif
          {{-- kiểm tra phân quyền thêm --}}
          @if($check_role == 1  ||key_exists(1, $check_role))
          <li class="ml-2 phay">
            /
          </li>
          <li class="add px-2 pt-1 ml-1 check active">
            <i class="fa fa-edit mr-1"></i>Thêm
          </li>
          @endif
        </ol>
      </div>
    </div>

    <h4 class="text-center font-weight-bold mb-0 mt-2 mb-2">CẤU HÌNH EMAIL</h4>
    <div class="box-mail-config">
      <div class="row m-0 px-3 pb-3">
        <div class="col-12 p-0">
          <div class="row m-0">
            <div id="campent-box" class="  col-12 col-sm-12 col-md-12 col-lg-12 p-0">
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
                    <p class="float-right font-weight-bold mb-0" style="line-height: 37px">Cổng</p>
                  </div>
                  <div class="col-5 mt-2">
                    <input class="form-control required" type="number" name="mail_port" placeholder="ex: 587" id="mail_port">
                    <div class="support-vay2">
                     <label class="form-control-409 mr-2">
                      <input type="radio" name="mail_encryption" value="tls" id="check_tls" checked>
                    </label>
                    <label for="check_tls">Bật TLS</label>
                  </div>
                </div>
                <div class="col-5 mt-2">
                  <button class="btn btn-primary br-0 brr0" id="defaultConfig" type="button">Mặc định</button>
                  <div class="support-vay2">
                   <label class="form-control-409 mr-2">
                    <input type="radio" value="ssl" name="mail_encryption" id="check_ssl">
                  </label>
                  <label for="check_ssl">Bật SSL</label>
                </div>
              </div>
              <div class="col-5 col-sm-5 col-md-2 col-lg-2 ">
                <p class="float-right font-weight-bold mb-0" style="line-height: 37px">Tên đăng nhập</p>
              </div>
              <div class="col-7 col-sm-7 col-md-10 col-lg-10 ">
                <input class="form-control required" type="text" name="mail_username" id="mail_username">
              </div>
              <div class="col-5 col-sm-5 col-md-2 col-lg-2 my-2">
                <p class="float-right font-weight-bold mb-0" style="line-height: 37px">Mật khẩu</p>
              </div>
              <div class="col-7 col-sm-7 col-md-10 col-lg-10 my-2">
                <input class="form-control required" type="text" name="mail_password" id="mail_password">
              </div>
            </div>
            <div class="text-center" id="result-status" style="font-size: 95%">
            </div>
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
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</section>
<!-- /.content -->
@endsection
@section('Script')
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
                    },
                    success : function (response){
                        if(response == "Thêm thành công"){
                            window.location.replace("{{route('admin.email-campaign.list-mail-config')}}");
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
            $('#defaultConfig').attr('disabled', true)
        }

        $('#defaultConfig').click(function () {
            $('#mail_host').val('smtp.gmail.com')
            $('#mail_port').val('587')
            $('#check_tls').prop("checked", true)
        })
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
{{-- link validate jquery --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- cấu hình validate --}}
<script src="{{ asset('system/js/validate/validate.validate.js') }}"></script>
@endsection
