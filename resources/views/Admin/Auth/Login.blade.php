<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nhà đất express | Đăng nhập</title>
        <base href="{{asset('system')}}/">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:{}">
            <img src="{{asset('frontend/images/logo.png')}}" alt="logo" style="width: 255px">
        </a>
    </div>
    <!-- /.login-logo -->
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Đăng nhập</p>

            <form action="{{route('admin_login')}}" method="post">
                @csrf
                <div class="input-group">
                    <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="Tài khoản">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div>
                    @if($errors->has('username'))
                        <small class="text-danger">
                            {{$errors->first('username')}}
                        </small>
                    @endif
                </div>

                <div class="input-group mt-3">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div>
                    @if($errors->has('password'))
                        <small class="text-danger">
                            {{$errors->first('password')}}
                        </small>
                    @endif
                </div>

                <div class="mt-3">
                    <x-common.captcha />
                </div>

                <div class="row mt-3">
{{--                    <div class="col-12">--}}
{{--                        <div class="icheck-primary">--}}
{{--                            <input type="checkbox" id="remember">--}}
{{--                            <label for="remember">--}}
{{--                                Nhớ tôi--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <!-- /.col -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mb-1 mt-2">
                <a href="{{route('admin.forgot_password')}}">Quên mật khẩu</a>
            </p>

        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
{!! Toastr::message() !!}

<script type="text/javascript">

    setInterval(refreshToken, 3600000); // 3600000 = 1 hour

    function refreshToken(){
        $.get('/refresh-csrf').done(function(data){
            $('form input[name="_token"]').first().val(data);
        });
    }

</script>
</body>
</html>
