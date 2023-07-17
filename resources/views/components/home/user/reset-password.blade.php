<div id="reset-password" class="popup" >
    <form action="{{route('user.post-reset-password')}}" method="post">
        @csrf
        <div class="wrapper ">
            <div class="head">
                <img src="{{asset(SystemConfig::logo())}}">
            </div>
            <div class="form-group p-3 d-flex justify-content-center flex-column">
                <p class="text-center title">ĐẶT LẠI MẬT KHẨU</p>

                <label for="">Vui lòng nhập email</label>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class=" d-flex justify-content-center">
                <button type="submit" class="post-button submit">Đặt lại mật khẩu</button>
            </div>

            <div class="close-button"><i class="fas fa-times"></i></div>
        </div>
    </form>

</div>
