//true: obj null hoặt undefine
function isNullOrUndefined(obj)
{
    if (obj === undefined)
    {
        return true
    }
    if (obj == null)
    {
        return true
    }
    return false;
}

//Kiểm tra ngày bắt đầu, ngày kết thúc có hợp lệ
function check_date_from_to(date_from_obj, date_to_obj)
{
    $('.total-days').text(0);
    var date_from = new Date(date_from_obj.val());
    var date_to = new Date(date_to_obj.val());
    var current_date = new Date;
    var current_text_date = current_date.getFullYear()+'-'+(current_date.getMonth()+1 < 10? '0' + (current_date.getMonth()+1):current_date.getMonth()+1)+'-'+current_date.getDate();

    if (!isNullOrUndefined(date_from))
    {
        if (date_from < current_date.setHours(0, 0, 0, 0))
        {
            date_from_obj.val(current_text_date);
            toastr.error('Ngày đăng phải lớn hơn hoặc bằng ngày hiện tại');
            return;
        }
    }
    if (!isNullOrUndefined(date_to))
    {
        if (date_to < current_date.setHours(0, 0, 0, 0))
        {
            date_to_obj.val(null);
            toastr.error('Ngày đăng phải lớn hơn hoặc bằng ngày hiện tại');
            return;
        }

    }
    if (!isNullOrUndefined(date_from) && !isNullOrUndefined(date_to))
    {
        if (date_to < date_from){
            date_to_obj.val(null);
            toastr.error('Từ ngày phải bé hơn hoặt bằng đến ngày');
            return;
        }
        var days = 1 + Math.ceil((date_to - date_from) / (1000 * 60 * 60 * 24));
        if (days > 180)
        {
            date_to_obj.val(null);
            toastr.error( `Tổng số ngày đăng: ${days}, vượt quá 180 ngày theo chính sách quản trị!`);
            return;
        }
        $('.total-days').text(days);
    }

}

//Thêm ảnh vào gallery
function readInputImages(input, input_gallery, div_img_upload)
{
    if (input.files && input.files.length > 0) {
        current_filelist = input_gallery.files;
        current_length = current_filelist.length;
        const dateTransfer = new DataTransfer();
        $.each(current_filelist, function (index, value){
            dateTransfer.items.add(value)
        })

        $.each(input.files, function (index, value){
            value.gallery_id = current_length + index;
            dateTransfer.items.add(value)
            var reader = new FileReader();
            reader.onload = function (e) {
                html = `<div class="item-slick-land"><img src="${e.target.result}" alt="" gallery_id="${current_length + index}"><i class="fas fa-times remove-land"></i></div>`
                $('.slick').slick('slickAdd',html);
            }
            reader.readAsDataURL(value);
        });
        input_gallery.files = dateTransfer.files;

    }
}

//Xóa ảnh khỏi gallery
function removeInputFile(gallery_file_id, input_gallery)
{
    current_filelist = input_gallery.files;
    const dateTransfer = new DataTransfer();
    $.each(current_filelist, function (index, value){
        if (value.gallery_id != gallery_file_id)
        {
            dateTransfer.items.add(value)
        }

    })

    input_gallery.files = dateTransfer.files;
}


$(document).ready(function (){
    //Thay doi chuyen muc
    $('select[name=parent]').change(function () {
        $("select[name='paradigm']").empty();
        $("select[name='paradigm']").append('<option value="">---Chọn---</option>')
        $.ajax({
            url: location.origin+'/params/ajax-get-child-group',
            type: "GET",
            dataType: "json",
            data: {
                parent_id: $('select[name=parent]').val(),
            },
            success: function (data) {
                show_get_child_option($('select[name="paradigm"]'), data['child_group'])
                $("select[name='paradigm']").trigger('change');
            }
        });

    });


    //Mô hình thây đổi --> Tình trạng, nội thất thây đổi
    $('select[name=paradigm]').change(function (){
        $.ajax({
            url: location.origin+'/params/ajax-get-progress',
            type: "GET",
            dataType: "json",
            data: {
                group_id: $('select[name=paradigm]').val(),
            },
            success: function (data) {
                $("select[name='progress']").empty();
                $("select[name='progress']").append('<option value="">---Chọn---</option>')
                $.each(data['progress'], function (index, value) {
                    html =  `<option value="${value.id}">${value.progress_name}</option>`;
                    $("select[name='progress']").append(html);
                });
            }
        });

        $.ajax({
            url: location.origin+'/params/ajax-get-furniture',
            type: "GET",
            dataType: "json",
            data: {
                group_id: $('select[name=paradigm]').val(),
            },
            success: function (data) {
                $("select[name='furniture']").empty();
                $("select[name='furniture']").append('<option value="">---Chọn---</option>')
                $.each(data['furniture'], function (index, value) {
                    html =  `<option value="${value.id}">${value.furniture_name}</option>`;
                    $("select[name='furniture']").append(html);
                });
            }
        });
    })

    //Kiểm tra điều kiện chính chủ
    $('.checkbox-button__input').change(function () {
        if ($(this).is('[readonly]')) {
            toastr.error('Chỉ những tài khoản được xác thực(nạp trên 500k) mới được sử dụng tính năng này');
            $(this).prop('checked', false);
        }
    });

    //Kiểm tra ngày đăng tin: >= ngày hiện tại
    $('.classfied_date').change(function (){
        check_date_from_to($('input[name=date_from]'), $('input[name=date_to]'))
    });

    //upload thư viện ảnh tin đăng
    $('.btn-upload-images').click(function (){
        $('.upload-images').click();
    });

    $('input.upload-images').change(function (){
        readInputImages(this, $('input[name="classified_gallery[]"]').get(0), '.list-update-slider-image')
    });

    //Xóa ảnh khỏi thư viện
    $('body').on('click', 'i.remove-land', function (){
        var image_file = $(this).siblings('img');
        removeInputFile(image_file.attr('gallery_id'),$('input[name="classified_gallery[]"]').get(0))
    });

    //Lấy tiêu đề tin đăng làm tiêu đề seo
    $('textarea[name=title]').change(function (){
        title = $(this).val();
        $('input[name=meta_title]').val(title);
    })
    //Kiểm tra điều kiện mua các gói dịch vụ
    $('input[name=classified_service]').change(function (){
        $('input[name=classified_package]').prop('checked', false);
        service_coin = $(this).attr('service_coin');
        coin_amount = $(this).attr('coin_amount');
        if (service_coin > coin_amount)
        {
            $('input[name=classified_service]').prop('checked', false);
            toastr.error('Tài khoản không đủ số coin');
            return;
        }

    });
    // $('input[name=classified_package]').change(function (){
    //     $('input[name=classified_service]').prop('checked', false);
    //     package_amount = $(this).attr('package_amount');
    //     title = $(this).attr('title');
    //     if (package_amount == 0)
    //     {
    //         $('input[name=classified_package]').prop('checked', false);
    //         toastr.error(`Tài khoản không đủ số  ${title}`);
    //         return;
    //     }
    //
    // });
    $('input[name=date_to]').trigger('change');
});






