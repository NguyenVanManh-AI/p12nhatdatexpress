$(document).ready(function () {
    $('.upload-cover').click(function () {
        let parent = $(this).parentsUntil('.upload-item');
        $(parent).find('.inp-upload-cover').click();
    })

    $('.inp-upload-cover').change(function (event) {
        let file = this.files[0];
        let reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            let parent = $(this).parentsUntil('.upload-item');
            $(parent).find('.upload-cover:first').attr('src', reader.result);
            $(parent).find('input[name=avatar]:first').val(reader.result);
        }.bind(this);

    })

    $('.edit-customer').click(function () {
        // should get customer data from api by customer id
        var current_modal = $('#edit-customer');

        // clear all old validate
        current_modal.find('.is-invalid').removeClass('is-invalid')
        current_modal.find('.invalid-feedback').remove()

        var customer = $(this).parentsUntil('tbody');
        let id = customer.find('.id').text();
        current_modal.find("input[name='id']").val(id);
        let fullname = customer.find('.fullname').text();
        current_modal.find("input[name='fullname']").val(fullname);
        let phone_number = customer.find('.phone-number').text();
        current_modal.find("input[name='phone_number']").val(phone_number);
        let email = customer.find('.email').text();
        current_modal.find("input[name='email']").val(email);
        let birthday = customer.find('.birthday').val();
        current_modal.find("input[name='birthday']").val(birthday);
        let job = customer.find('.job').attr('data-job');
        current_modal.find("select[name='job']").val(job).trigger('change');

        let district = customer.find('.district').attr('data-district');
        current_modal.find("select[name=district]").attr('data-selected', district);

        let province = customer.find('.province').attr('data-province');
        current_modal.find("select[name='province']").val(province).trigger('change');

        var address = customer.find('.address').text();
        current_modal.find("input[name='address']").val(address);
        let source = customer.find('.source').attr('data-source');
        current_modal.find("select[name=source]").val(source).trigger('change');
        var cus_status = customer.find('.cus_status').attr('data-status');
        current_modal.find("select[name='status']").val(cus_status).trigger('change');
        let note = customer.find('.cus_note').text();
        current_modal.find("textarea[name='note']").text(note);
        var cus_avatar = customer.find('.cus_avatar').attr('src');

        current_modal.find('.customer-file-input .file-input__preview .file-input__preview-image').attr('href', cus_avatar);
        current_modal.find('.customer-file-input .file-input__preview .file-input__preview-image img').attr('src', cus_avatar);
        current_modal.find('.customer-file-input .file-input__preview .file-input__preview-input').val(cus_avatar);

        if (cus_avatar) {
            current_modal.find('.customer-file-input .file-input__preview').removeClass('d-none')
        } else {
            current_modal.find('.customer-file-input .file-input__preview').addClass('d-none')
        }
    });
})
