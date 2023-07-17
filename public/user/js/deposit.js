$(document).ready(function() {
    //kiem tra voucher hop le
    $('#deposit-voucher').change(function () {
        var voucherCode = $(this).val();
        if (!voucherCode) {
            return;
        }
        $.ajax({
            url: 'ajax-valid-voucher',
            type: "GET",
            dataType: "json",
            data: {'voucherType':1,'voucherCode': voucherCode},
            async: false,
            success: function (data) {
                if (data.voucherStatus.status) {
                    toastr.success(data.voucherStatus.message);
                    $('input[name=deposit_voucher]').val(voucherCode);
                } else {
                    toastr.error(data.voucherStatus.message);
                }
            }
        });
    });

    //Thông tin nạp coin
    $('.btn-deposit-coin').click(function () {
        $('#show-payment-amount').text($('#deposit-amount :selected').text());
        $("input[name=deposit_amount]").val($('#deposit-amount :selected').val());

    });

    //xac nhan thanh toan moi duoc moi xac nhan nap coin
    $('input[name=confirm_payment]').change(function () {
        let transferStatus = $(this).is(':checked');
        if (transferStatus) {
            $('.btn-confirm').removeAttr("disabled");
        }
        else {
            $('.btn-confirm').attr('disabled', 'disabled');

        }
    });

    $("select[name='deposit_type']").change(function (){
        $("select[name='deposit_code']").empty();
        $("select[name='deposit_code']").append(`<option value="" selected></option>`);
        $.ajax({
            url: '/params/ajax-get-deposit',
            type: "GET",
            dataType: "json",
            data: {
                'deposit_type': $(this).val(),
            },
            async: false,
            success: function (data) {
                $.each(data['deposit_list'], function (index, value) {
                    let depositTime = new Date(value.deposit_time*1000);
                    console.log(depositTime)
                    let depositDate = depositTime.getDate()+'/'+ depositTime.getMonth() + '/' + depositTime.getFullYear();
                    html =  `<option value="${value.deposit_code}">${value.deposit_code} - ${depositDate}</option>`;
                    $("select[name='deposit_code']").append(html);
                });
            }
        });
    });

});
