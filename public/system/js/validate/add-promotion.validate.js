function resetForm(){
    document.getElementById("add-promotion").reset();
}




$("#add-promotion").validate({
    rules: {
        quanlity_code: {
            required : true,
            max: 200,
            min:1,
            digits:true
        },
        num_use: {
            required : true,
            min:1,
            max:1000,
            digits:true
        },
        value: {
            required : true,
            max: 100,
            min:1,
            digits:true
        },
    },

    messages: {
        quanlity_code: {
            required: "Vui lòng nhập số lượng | ",
            max: "Số lượng mã tối đa là 200 | ",
            min: "Vui lòng tạo 1 mã trở lên",
            digits:"Vui lòng nhập một số nguyên",
        },
        num_use: {
            required: "Vui lòng nhập số lần",
            min: "Nhập tối thiểu là 1 lần dùng",
            max: "Nhập tối đa 1000 lần dùng",
            digits:"Vui lòng nhập một số nguyên",
        },
        value: {
            required: "Vui lòng nhập phần trăm",
            min: "Vui lòng nhập từ 1% đến 100%",
            max: "Vui lòng nhập từ 1% đến 100%",
            digits:"Vui lòng nhập một số nguyên",
        },
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