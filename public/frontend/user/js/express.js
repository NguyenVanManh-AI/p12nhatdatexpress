$(document).ready(function (){

    $('select[name="categori"]').select2({ width: 'resolve' });
    $('select[name="paradigm"]').select2({ width: 'resolve' });
    $('input[name=banner][value=H]').prop('checked', true).change();
    //banner: H: trang chủ, C: trang chuyên mục
    $('input[name=banner]').change(function (){
        if ($('input[name=banner]:checked').val() == 'H')
        {
            $('.banner-group').addClass('d-none');
        }
        if ($('input[name=banner]:checked').val() == 'C')
        {
            $('.banner-group').removeClass('d-none');
            $('select[name=categori]').val(null).change();
            $('select[name=paradigm]').empty();
        }
        $('input[name=banner_type][value=D]').prop('checked', true).change();

    });

    //chuyên mục thây đổi, load mô hình
    $('select[name=categori]').change(function (){
        $('input[name=banner_type][value=D]').prop('checked', true).change();
        $('select[name=paradigm]').empty();
        var group_id = $('select[name=categori]').val();
        $.ajax({
            url: location.origin+'/params/ajax-get-child-group',
            type: "GET",
            dataType: "json",
            data: {
                parent_id: group_id
            },
            success: function (data) {
                $('select[name="paradigm"]').empty();
                $('select[name="paradigm"]').append('<option value="" disabled selected>---Chọn---</option>')
                show_get_child_option($('select[name="paradigm"]'), data['child_group'])
            }
        });

    });

    //chuyên mục con thây đổi reset all giá trị nội dung đã khỏi tạo
    $('select[name=paradigm]').change(function (){
        $('input[name=banner_type][value=D]').prop('checked', true).change();
    })

    //Banner_type: D: desktop, M:mobile
    $('input[name=banner_type]').change(function (){
        $('input[name=banner_type]:checked').parent().addClass('btn-banner-type-active');
        $('input[name=banner_type]:not(:checked)').parent().removeClass('btn-banner-type-active');

        if ($('input[name=banner_type]:checked').val() == 'D')        {
            $('.banner-pos-desktop').removeClass('d-none').addClass('d-flex');
            $('.banner-pos-mobile').addClass('d-none');
            $('.img-mobile').removeClass('d-flex').addClass('d-none');
            $('.img-desktop').removeClass('d-none').addClass('d-flex');

        }
        if ($('input[name=banner_type]:checked').val() == 'M')
        {
            $('.banner-pos-desktop').removeClass('d-flex').addClass('d-none');
            $('.banner-pos-mobile').removeClass('d-none');
            $('.img-desktop').removeClass('d-flex').addClass('d-none');
            $('.img-mobile').removeClass('d-none').addClass('d-flex');
        }
        reset_all();
    });

    $('input[name=banner_pos]').change(function()
    {
        //Nếu vị trí quản cáo là chuyên mục--> yc chọn mô hình
        if ($('input[name=banner]:checked').val() == 'C'  && $('select[name="paradigm"]').val() == null) {
            $('input[name=banner_pos]').prop('checked', false);
            toastr.error('Vui lòng chọn MÔ HÌNH, trước khi chọn vị trí đặt banner');
            return;
        }

        var banner_img = '.img-'+$('input[name=banner_pos]:checked').attr('id');
        $(banner_img).addClass('div-img-upload banner-border-select');

        var banner_pos_not_check = $('input[name=banner_pos]:not(:checked)');
        banner_pos_not_check.each(function (index){
            var banner_img = '.img-'+$(this).attr('id');
            $(banner_img).removeClass('div-img-upload banner-border-select');
            $(banner_img).find('img').attr('src', location.origin+'/system/images/banner/banner.png');

        });

        $('.note-pages').empty();

        $.ajax({
            url: location.origin+'/params/ajax-get_banner-price',
            type: "GET",
            dataType: "json",
            data: {
                banner_group: $('input[name=banner]:checked').val(),
                banner_pos: $('input[name=banner_pos]:checked').val(),
                banner_type: $('input[name=banner_type]:checked').val()
            },
            success: function (data) {
                var banner = data.banner;
                $('input[name=banner_price]').val(banner.banner_price);
                html = `<p class="notes">Ghi chú:</p>
                        <p>Vị trí quảng cáo
                            <span class="page-one">${banner.banner_name}</span>
                            có kích thước ${banner.banner_width} x ${banner.banner_height},
                            phí hiển thị mỗi ngày <span class="page-number">${banner.banner_price} Express Coins</span>
                        </p>`;
                $('.note-pages').append(html);
                total_coins();
            }
        });

    });

    $('.banner_date').change(function (){
        var input_date =  new Date($(this).val());
        var current_date = new Date();
        if (input_date < current_date.setHours(0, 0, 0, 0))
        {
            $(this).val(null);
            if ($(this).attr('name') == 'date_from')
            {
                var today = new Date();
                var current_date = today.getFullYear()+'-'+(today.getMonth()+1 < 10? '0' + (today.getMonth()+1):today.getMonth()+1)+'-'+today.getDate();
                $(this).val(current_date);
            }

            toastr.error('Thời gian hiển thị phải bằng hoặc lớn hơn ngày hiện tại');
            return;
        }
        total_coins();
    })

    $('body').on('click','div.div-img-upload', function(event){
        event.preventDefault();
        $('input[name=banner_img_file]').trigger("click");

    });
    $('input[name=banner_img_file]').change(function(){
        readInputImage(this, '.div-img-upload');

    });

})

//reset gias
function reset_all()
{
    $('input[name=banner_pos]').prop('checked', false);//ok
    $('.img-banner-left').removeClass('div-img-upload banner-border-select').find('img').attr('src', location.origin+'/system/images/banner/banner.png');
    $('.img-banner-right').removeClass('div-img-upload banner-border-select').find('img').attr('src', location.origin+'/system/images/banner/banner.png');
    $('.img-banner-mobile').removeClass('div-img-upload banner-border-select').find('img').attr('src', location.origin+'/system/images/banner/banner.png');
    $('.note-pages').empty();
    $('input[type=file][name=banner_img_file]').attr('src', '');
    $('.total_banner_coins').text(0);
    $('input[name=date_to]').val('');
    var today = new Date();
    var current_date = today.getFullYear()+'-'+(today.getMonth()+1 < 10? '0' + (today.getMonth()+1):today.getMonth()+1)+'-'+today.getDate();
    $('input[name=date_from]').val(current_date);

}

//Tính coins banner
function total_coins()
{
    $('.total_banner_coins').val(0);
    var date_from = new Date($('input[name=date_from]').val());
    var date_to = new Date($('input[name=date_to]').val());
    if ($('input[name=date_to]').val() < $('input[name=date_from]').val())
    {
        $('input[name=date_to]').val(null);
        toastr.error('ĐẾN NGÀY phải bằng hoặc lớn hơn TỪ NGÀY');
        return;
    }
    var date_diff = 1 + Math.ceil((date_to - date_from) / (1000 * 60 * 60 * 24));
    var banner_price = $('input[name=banner_price]').val();
    $('.total_banner_coins').text(isNaN(banner_price*date_diff)?0:banner_price*date_diff);
}

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




