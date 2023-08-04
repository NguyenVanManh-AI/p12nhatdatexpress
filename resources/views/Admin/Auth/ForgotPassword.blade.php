<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nhà đất express | Quên mật khẩu</title>
    <base href="{{asset('system')}}/">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    {{-- should create layout --}}
    <link rel="stylesheet" href="css/toastr/toastr.min.css">
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


            <p class="login-box-msg">Vui lòng nhập email</p>

            <form action="{{route('admin.forgot_password')}}" method="post">
                @csrf
                <div class="input-group">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div>
                    @if($errors->has('email'))
                        <small id="passwordHelp" class="text-danger">
                            {{$errors->first('email')}}
                        </small>
                    @endif
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary btn-block">Đặt lại mật khẩu</button>
                </div>
            </form>


            <p class="mb-1 mt-2">
                <a href="{{route('admin_login')}}">Đăng nhập</a>
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
<script src="js/toastr/toastr.min.js"></script>
{!! Toastr::message() !!}
</body>
</html>
