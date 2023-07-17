@extends('Admin.Layouts.Master')

@section('Title', 'Chỉnh sửa người dùng')

@section('Content')
    <section class="content">
        <div class="container-fluid">
            <h4 class="text-bold text-center m-3">CHỈNH SỬA NGƯỜI DÙNG</h4>
            <form action="" method="post">
                @csrf
            <div class="row pt-5">
                <div class="col-md-4">
                    <x-common.text-input
                        label="Tài khoản"
                        name="username"
                        value="{{ $user->username }}"
                        readonly
                    />
                </div>
                <div class="col-md-4">
                    <x-common.text-input
                        label="{{ $user->user_type_id == 3 ? 'Mã số thuế': 'Số CMND/CCCD' }}"
                        name="tax_number"
                        value="{{ old('tax_number', data_get($user->detail, 'tax_number')) }}"
                    />
                </div>
                <div class="col-md-4">
                    <x-common.text-input
                        label="Mật khẩu"
                        type="password"
                        name="password"
                    />
                </div>
                <div class="col-md-4">
                    <x-common.text-input
                        label="Nhập lại mật khẩu"
                        type="password"
                        name="password_confirmation"
                    />
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success">Lưu</button>
                </div>
            </div>
            </form>
        </div>
    </section>
@endsection

