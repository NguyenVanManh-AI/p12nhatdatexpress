@extends('Admin.Layouts.Master')
@section('Title', 'Sửa mẫu email | Chiến dịch email')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
<style type="text/css">
    .error{
        color: #dc3545;
    }
</style>
@endsection
@section('Content')
    <section>
        <div>
            <div class="col-sm-12 mbup30">
                <div class="row m-0 px-2 pt-3">
                    <ol class="breadcrumb mt-1">
                        <li class="recye px-2 pt-1 check">
                            <a href="{{route('admin.email-campaign.list-template')}}">
                                <i class="fa fa-th-list mr-1"></i>Danh sách
                            </a>
                        </li>
                    </ol>
                </div>
            </div><!-- /.col -->
            <h4 class="text-center font-weight-bold mb-0 mt-2">SỬA MẪU MAIL</h4>
            <div class="px-2">
                @if($check_role == 1  || key_exists(1, $check_role))
                    <form id="add-mail-template"
                          action="{{route('admin.email-campaign.post-edit-mail-template',[$getTemplate->id,\Crypt::encryptString($getTemplate->created_by)])}}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row m-0 pb-3">
                            <div id="campent-box" class="col-12 col-sm-12 col-md-12 col-lg-12 p-0">
                                <div class="col-12  p-2">
                                    <label class="float-left">Tiêu đề <span class="required"></span></label>
                                    <input class="form-control required" type="" name="template_title"
                                           placeholder="Xin chào %ten_nguoi_nhan%"
                                           value="{{$getTemplate->template_title}}">
                                </div>
                                <div class="col-12 p-2 pdx8i">
                                    <label class="float-left">Nội dung</label>
                                    <textarea class="js-admin-tiny-textarea" value="{{ (old('template_content')) ?? ""}}"
                                              name="template_content" style="width: 100%;height: 300px">{{old('template_content') ?? $getTemplate->template_content}}</textarea>
                                    <div id="editerInput"></div>
                                    <p class="mt-1 mb-0">Ghi chú: chèn <span
                                            class="promotion-warring">%ten_nguoi_nhan%</span> vào nội dung để hệ thống tự
                                        thay đổi tên phù hợp khi gửi mail nhiều người
                                    </p>
                                </div>
                            </div>

                            <div id="button-order-by1" class="col-12 mt-2 text-center">
                                <button type="submit" class="btn btn-primary" style="border-radius: 0;">Hoàn tất
                                </button>
                            </div>

                        </div>
                    </form>
                @endif
            </div>
        </div>
    </section>
<!-- /.content -->
@endsection
@section('Script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"
        integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('system/js/validate/validate.validate.js') }}"></script>
<script type="text/javascript">
    var check = 1;

    function notitication() {
        if (check == 1) {
            toastr.error('Vui lòng kiểm tra các trường');
            check++;
        }
    }
</script>
@endsection
