// ajax set default headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on({
    ajaxStart: function(){
    },
    ajaxStop: function() {
        if ( typeof loadingImage == 'function' ) {
            // lazyload image
            loadingImage()
        }
        
        // init select2 // should change
        if ($('.select2').length) {
            $('.select2').each(function() {
                let _this = $(this)
                _this.select2({
                    theme: _this.data('select2-theme') == 'bootstrap4' ? 'bootstrap4' : 'default', // default or bootstrap4
                    maximumSelectionLength: _this.data('select2-max-length'),
                    allowClear: true,
                    placeholder: _this.data('placeholder') || '-- Tất cả --',
                    language: 'vi',
                    dropdownParent: _this.data('select2-parent') ? $(_this.data('select2-parent')) : null
                })
            })
        }
    },
});

const showError = error => {
    if (error && error.responseJSON && error.responseJSON.errors) {
        const errors = Object.values(error.responseJSON.errors)
        if (errors[0] && errors[0][0])
        toastr.error(errors[0][0])
    } else if (error && error.responseJSON && error.responseJSON.message) {
        toastr.error(error.responseJSON.message)
    }
}

const showMessage = res => {
    if (res && res.message)
        res.success ? toastr.success(res.message) : toastr.error(res.message)
}
