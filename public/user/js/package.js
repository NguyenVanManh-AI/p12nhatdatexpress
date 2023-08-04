//Thông tin mua gói
$('.btn-deposit-package').click(function(){
    var parent = $(this).parents('.package-item:first');
    var package_id = parent.find("input[name='package_temp_id']").val();
    $("input[name=package_id]").val(package_id);
    var package_name = parent.find(".package_name:first").text();
    $('.select_package').text(package_name);
    var price = parent.find('.package-price:first').text();
    $('.pm-price').text(price + 'vnđ ');
    var coin = parent.find('.package-coin:first').text();
    $('.pm-coin-price').text(coin );
    var current_coin =$('.pm-user-coin:first').text();
    $('.total-payment-coin').text(coin);
    if (current_coin > coin) {
        $('.notification').addClass('d-none');
    }
    else {
        $('.notification').removeClass('d-none');
    }
});

$('#deposit-voucher').change(function () {
    var voucherCode = $(this).val();
    if (!voucherCode) {
        return;
    }
    $.ajax({
        url: '/thanh-vien/ajax-valid-voucher',
        async: false,
        type: "GET",
        dataType: "json",
        data: {'voucherType':0,'voucherCode': voucherCode},
        async: false,
        success: function (data) {
            if (data.voucherStatus.status) {
                toastr.success(data.voucherStatus.message);
                $('input[name=deposit_voucher]').val(voucherCode);
                let paymentCoin = parseInt($('.pm-coin-price:first').text());
                let vPercent = data.voucherStatus.voucherPercent;
                let discountCoin = 0.01*vPercent*paymentCoin??0;
                $('.discount-coin-percent').text(`${vPercent}% - ${discountCoin}`);
                $('.total-payment-coin').text(`${paymentCoin - discountCoin}`);

            } else {
                let paymentCoin = parseInt($('.pm-coin-price:first').text());
                $('.discount-coin-percent').text('0 % - 0');
                $('.total-payment-coin').text(paymentCoin);
                $('input[name=deposit_voucher]').val(null);
                toastr.error(data.voucherStatus.message);
            }
        }
    });
});

//copy link ref
$('.copy-text').click(function() {
    var copyText = $(this).prev('.copy-content').text();
    copyToClipboard(copyText);
    Swal.fire('Sao chép thành công');

});



//xac nhan thanh toan moi duoc moi xac nhan nap coin
$('input[name=confirm_payment]').change(function () {
    let transferStatus = $(this).is(':checked');
    if (transferStatus) {
        var paymentMethodId = $('a.payment-tab.active:first').attr('data-internalid');
        $('input[name=payment_method]').val(paymentMethodId);
        $('.btn-confirm').removeAttr("disabled");
    }
    else {
        $('.btn-confirm').attr('disabled', 'disabled');

    }
});
