$(document).ready(function (){
    $('#login select[name=province]').change(function (){
        parent = $(this).parentsUntil('form');
        district_element = parent.find('select[name=district]');
        district_element.empty();
        district_element.append('<option value="" selected disabled>---Chọn---</option>')
        $.ajax({
            url: location.origin+'/params/ajax-get-district',
            type: "GET",
            dataType: "json",
            data: {
                province_id: $(this).val()
            },
            success: function (data) {
                $.each(data['districts'], function (index, value)
                {
                    district_element.append(`<option value="${value.id}">${value.district_name}</option>`);
                });
            }

        });
        $('#login select[name=district]').trigger('change');

    });
    $('#login select[name=district]').change(function (){
        parent = $(this).parentsUntil('form');
        ward_element = parent.find('select[name=ward]');
        ward_element.empty();
        ward_element.append('<option value="" selected disabled>---Chọn---</option>')
        $.ajax({
            url: location.origin+'/params/ajax-get-ward',
            type: "GET",
            dataType: "json",
            data: {
                district_id: $(this).val()
            },
            success: function (data) {
                $.each(data['wards'], function (index, value)
                {
                    ward_element.append(`<option value="${value.id}">${value.ward_name}</option>`);
                });
            }

        });
    });

    $('#login .type-switcher .tag').click(function (){
        var current_tag = $(this).attr('data-type');
        $(current_tag).removeClass('d-none');
        parent = $(this).parentsUntil('.type-switcher');
        parent.children('div.tag').each(function (index, obj){
            tag =  $(obj).attr('data-type');
            if (tag != current_tag)
            {
                $(tag).addClass('d-nopopupne');
            }
        });
    })
});

$('#login .save .reset-password').click(function (event) {
    event.preventDefault();
    $('.popup').hide();
    $("#layout").hide();
    $("#reset-password").css("display", "block");
    $("#reset-password").show();

    $("#layout").show();
});
