// ajax set default headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// $(document).on({
//     ajaxStart: function(){
//     },
//     ajaxStop: function(){
//     },
// });

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
