function resetForm(){
    document.getElementById("add-promotion").reset();
}


$("#filter-promotion").validate({
    rules: {
        // date_from: {
        //     required : true,
        // },
        // date_to: {
        //     required : true,
        // },
    },

    messages: {
        // date_from: {
        //     required: "Vui lòng chọn ngày",
        // },
        // date_to: {
        //     required: "Vui lòng chọn ngày",
        // },

    },

    submitHandler: function(form) {
        var date_from = new Date($('#handleDateTo').val()).valueOf()
        var date_to = new Date($('#handleDateFrom').val()).valueOf()

        if (date_from < date_to){
            $('#appendDateError').html('<label id="num_use-error" class="error" for="num_use">Ngày bắt đầu hiển thị phải lớn hơn ngày kết thúc</label>')

        }else{
         form.submit();

     }
 }
});
