$(document).ready(function () {

    //Thay doi bia
    $('.update-images-cover').click(function (){
        $('input[name=background_img]').click();
    });

    $('input[name=background_img]').change(function(){
        readInputImage(this, '.update-images-cover');

    });

    //uploads giay phep kinh doanh khi tao user
    $('#btn-create-user-gpkd').click(function (){
        $('#create-user-gpkd').click();
    });

    //Chọn ảnh khách hàng
    $('.wrap-in').click(function (){
        $('input[name=avatar]').click();
    });

    $('input[name=avatar]').change(function(){
        readInputImage(this, '.wrap-in');
    });



    //Phương thức thanh toán
    $('.nav-tabs .nav-item').click(function () {
        var payment_id = $(this).attr('data-internalid');
        $("input[name=payment_method]").val(payment_id);
    });



    //Thông tin mua gói
    $('.btn-deposit-package').click(function(){
        var parent = $(this).parents('.post-upgrade');
        var package_id = parent.find("input[name='package_temp_id']").val();
        $("input[name='package_id']").val(package_id);
        var package_name = parent.find(".package_name").text();
        $('.select_package').text(package_name);
    });

    //Chia sẻ tin vip
    $('.modal_share_vip_amount').click(function(){
        var parent = $(this).parents('tr');
        var ref_name = parent.find('.user_ref_name').text();
        $('.user_ref_info').val(ref_name);
        var ref_code = parent.find('.user_ref_code').val();
        $('input[name="user_ref_share_vip"]').val(ref_code);
    })

    tinymce.init({
        selector: '.mytextarea',
        menubar:false,
        height: 430,
        language: 'vi_VN',
        plugins: [
            'a11ychecker advcode advlist anchor autolink codesample fullscreen help image  tinydrive',
            ' lists link media noneditable powerpaste preview',
            ' searchreplace table template visualblocks wordcount'
        ],
        toolbar:
            'undo redo | fontsizeselect | bold italic underline strikethrough| superscript subscript | hr | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent |  forecolor backcolor | link unlink anchor | image insertfile',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        autosave_ask_before_unload: false,
        powerpaste_allow_local_images: true,
        image_title: true,
        automatic_uploads: true,
        file_picker_types: 'image',
        file_picker_callback: function (cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function () {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function () {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), { title: file.name });
                };
                reader.readAsDataURL(file);
            };
            input.click();
        },
    });

    $('.delete-alert').click(function (event){
        event.preventDefault();
        Swal.fire({
            title: 'Xác nhận xóa',
            text: "Nhấn đồng ý thì sẽ tiến hành xóa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = $(this).attr('href');
            }
        });
    });

    $('.accept-alert').click(function (event){
        event.preventDefault();
        Swal.fire({
            title: 'Xác nhận thao tác',
            text: "Nhấn đồng ý thì sẽ tiến hành thao tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = $(this).attr('href');
            }
        });
    });



});



function open_failed_form_in_modal(current_form)
{
    var open_form = '#'+ current_form;
    $(open_form).modal('show');
}

$(document).on({
    ajaxStart: function(){
        $("body").addClass("loading");
    },
    ajaxStop: function(){
        $("body").removeClass("loading");
    }
});

function show_get_child_option(selector, children)
{
    $.each(children, function (index, value)
    {
        var html = `<option value=${value.id}><b>${value.group_name}</b></option>`
        selector.append(html);
        if (value.children)
        {
            show_get_child_option(selector, value.children);
        }

    });

}

