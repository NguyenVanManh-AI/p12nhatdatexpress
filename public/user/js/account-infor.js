$(document).ready(function () {
    //avatar upload
    $('#upload-img-ava').on("change", function (event) {
        readURL(this, ".avatar-introduce");
    });

    //upgrade account
    $('input[name=inp_user_highlight]').click(function (event) {
        let checkStatus = $(this).is(':checked');
        let message = checkStatus?'Xác nhận nâng cấp tài khoản':'Xác nhận hủy nâng cấp tài khoản'
        Swal.fire({
            title: message,
            text: "Nhấn đồng ý thì sẽ tiến hành thao tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-upgrade-account').submit();
            }

        });
        return false;
    });

    //Thay doi bia
    $('.update-images-cover').click(function (){
        $('input[name=background_img]').click();
    });

    $('input[name=background_img]').change(function(){
        readInputImage(this, '.update-images-cover');

    });
});

function readInputImage(input, div_img_upload)
{
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = $(div_img_upload).find('img');
            img.attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

