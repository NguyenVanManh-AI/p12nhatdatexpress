
$(document).ready(function() {
    toastr.options = {
      'closeButton': true,
      'debug': false,
      'newestOnTop': false,
      'progressBar': false,
      'positionClass': 'toast-top-right',
      'preventDuplicates': false,
      'showDuration': '1000',
      'hideDuration': '1000',
      'timeOut': '5000',
      'extendedTimeOut': '1000',
      'showEasing': 'swing',
      'hideEasing': 'linear',
      'showMethod': 'fadeIn',
      'hideMethod': 'fadeOut',
  }
});

$("#add-mail-box").validate({
    rules: {
        title: {
            required : true,
            maxlength: 255,
            minlength:1
        },
        content: {
            required : true,
            maxlength: 25000,
            minlength:1
        },
    },
    messages: {
        title: {
            required: "Vui lòng nhập tiêu đề",
            maxlength: "Tiêu đề thư từ 1 đến 255 ký tự",
            minlength: "Tiêu đề thư từ 1 đến 255 ký tự"
        },
        content: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Tiêu đề thư từ 1 đến 25000 ký tự",
            minlength: "Tiêu đề thư từ 1 đến 25000 ký tự"
        },
    },
    submitHandler: function(form) {
       var content = tinyMCE.activeEditor.getContent();
       if (content === "" || content === null) {
        $("#editerInput").html('<label class="error" for="news_description">Vui lòng nhập nội dung</label>');
        notitication();
    } else {
        $("#editerInput").html("");
        if($('#multiselect').val() != ""){
            form.submit();
        }else{
            $("#chooseInput").html('<label class="error" for="news_description">Vui lòng chọn thành viên</label>');
            notitication();
        }

    }
},
errorPlacement: function(error, element) {
    error.insertAfter(element);
    notitication();
}

});

$("#form-add-campaign").validate({
    rules: {
        campaign_name: {
            required : true,
            maxlength: 255,
            minlength:1
        },
        start_time_hour:{
            required :false,
            max: 25,
            min:0,
            digits:true
        },
        start_time_min:{
            required :false,
            max: 60,
            min:0,
            digits:true
        }
    },
    messages: {
        campaign_name: {
            required: "Vui lòng nhập tên chiến dịch",
            maxlength: "Tên chiến dịch từ 1 đến 255 ký tự",
            minlength: "Tên chiến dịch từ 1 đến 255 ký tự",
            digits:"Vui lòng nhập đúng định dạng"
        },
        start_time_hour: {
            max: "Số giờ trong khoảng từ 0 đến 24",
            min: "Số giờ trong khoảng từ 0 đến 24",
            digits:"Vui lòng nhập đúng định dạng"
        },
        start_time_min: {
            max: "Số phút trong khoảng từ 0 đến 60",
            min: "Số phút trong khoảng từ 0 đến 60",
            digits:"Vui lòng nhập đúng định dạng"
        }
    },
    submitHandler: function(form) {

        if($('#date-send').val() == "" && $('#start-time-hour-input').val() == "" && $('#start-time-min-input').val() == ""){
            form.submit();
        }else{
            var checkError = 0;
            if($('#date-send').val() != ""){
                if($('#start-time-hour-input').val() == ""){
                    $("#error-hour").html('<label class="error">Vui lòng nhập giờ</label>');
                }
                if($('#start-time-min-input').val() == ""){
                   $("#error-min").html('<label class="error">Vui lòng nhập phút</label>');
               }
           }else{
            checkError++;
        }
        if($('#start-time-hour-input').val() != ""){
            if($('#date-send').val() == ""){
                $("#error-date").html('<label class="error">Vui lòng nhập ngày</label>');
            }
            if($('#start-time-min-input').val() == ""){
               $("#error-min").html('<label class="error">Vui lòng nhập phút</label>');
           }
       }else{
        checkError++;
    }
    if($('#start-time-min-input').val() != ""){
        if($('#date-send').val() == ""){
            $("#error-date").html('<label class="error">Vui lòng nhập ngày</label>');
        }
        if($('#start-time-hour-input').val() == ""){
           $("#error-hour").html('<label class="error">Vui lòng nhập giờ</label>');
       }
   }else{
    checkError++;
}
if(checkError==0){
 form.submit();
}else{
 notitication();
}
}
},
errorPlacement: function(error, element) {
    error.insertAfter(element);
    notitication();
}

});


var checkAddMailConfig=false;
$("#add-mail-config").validate({
    rules: {
        mail_host: {
            required : true,
            maxlength: 250,
            minlength:1
        },
        mail_port: {
            required : true,
            max: 100000,
            min:0
        },
        mail_username:{
            required : true,
            maxlength: 250,
            minlength:1
        },
        mail_password:{
            required : true,
            maxlength: 250,
            minlength:6
        }
    },
    messages: {
        mail_host: {
            required: "Vui lòng nhập SMTP",
            maxlength: "SMTP từ 1 đến 250 ký tự",
            minlength: "Tiêu đề email từ 1 đến 250 ký tự"
        },
        mail_port: {
            required: "Vui lòng nhập cổng",
            max: "Cổng có giá trị từ 0 đến 100000",
            min: "Cổng có giá trị từ 0 đến 100000"
        },
        mail_username: {
            required: "Vui lòng nhập tên đăng nhập",
            maxlength: "Tên đăng nhập từ 1 đến 250 ký tự",
            minlength: "Tên đăng nhập từ 1 đến 250 ký tự"
        },
        mail_password: {
            required: "Vui lòng nhập mật khẩu",
            maxlength: "Mật khẩu từ 6 đến 250 ký tự",
            minlength: "Mật khẩu từ 6 đến 250 ký tự"
        }
    },
    submitHandler: function(form) {
        checkAddMailConfig=true;
        if(typeButton == 'test'){
            sendMailTest()
        }else{
            submitMail()
        }

    },
    errorPlacement: function(error, element) {
        error.insertAfter(element);
        notitication();
    }

});


$("#add-mail-template").validate({
    rules: {
        template_title: {
            required : true,
            maxlength: 250,
            minlength:1
        }
        ,
        template_content: {
            required : true,
            maxlength: 3000,
            minlength:1
        }
    },
    messages: {
        template_title: {
            required: "Vui lòng nhập tiêu đề",
            maxlength: "Tiêu đề email từ 1 đến 250 ký tự",
            minlength: "Tiêu đề email từ 1 đến 250 ký tự"
        },
        template_content: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Tiêu đề email từ 1 đến 3000 ký tự",
            minlength: "Tiêu đề email từ 1 đến 3000 ký tự"
        }
    },
    submitHandler: function(form) {
       var content = tinyMCE.activeEditor.getContent();
       if (content === "" || content === null) {
        $("#editerInput").html('<label class="error" for="news_description">Vui lòng nhập nội dung</label>');
        notitication();
    } else {
        $("#editerInput").html("");
        form.submit();
    }
},
errorPlacement: function(error, element) {
    error.insertAfter(element);
    notitication();
}

});

$("#edit-properties").validate({
    rules: {
        tenduan: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        giaban: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        huongnha: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        mohinh: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        giathue: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        quymo: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        chudautu: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        dientich: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        hotrovay: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        vitri: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        phaply: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        duong: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        tinhtrang: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        noithat: {
            required : true,
            maxlength: 100,
            minlength:3
        },
        dangngay: {
            required : true,
            maxlength: 100,
            minlength:3
        },
    },

    messages: {
        tenduan: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        giaban: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        huongnha: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        mohinh: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        giathue: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        quymo: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        chudautu: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        dientich: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        hotrovay: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        vitri: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        phaply: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        duong: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        tinhtrang: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        noithat: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },
        dangngay: {
            required: "Vui lòng nhập nội dung",
            maxlength: "Nội dung gồm 3 đến 100 ký tự",
            minlength: "Nội dung gồm 3 đến 100 ký tự",
        },

    },

    submitHandler: function(form) {
       form.submit();
   },
   errorPlacement: function(error, element) {
    error.insertAfter(element);
    notitication();
}
});
$("#edit-setting-project").validate({
    rules: {
        ngattrang: {
            required : true,
            max: 255,
            min:1,
            digits:true
        },
        icon1: {
            // required : true,
            maxlength: 255,
            // minlength:1
        },
        icon2: {
            // required : true,
            maxlength: 255,
            // minlength:1
        }
        ,
        icon3: {
            // required : true,
            maxlength: 255,
            // minlength:1
        }
        ,
        icon4: {
            // required : true,
            maxlength: 255,
            // minlength:1
        }
        ,
        icon5: {
            // required : true,
            maxlength: 255,
            // minlength:1
        },
        icon6: {
            // required : true,
            maxlength: 255,
            // minlength:1
        }
    },
    messages: {
        ngattrang: {
            required: "Vui lòng nhập số tin hiển thị",
            max: "Số tin hiển thị từ 1 đến 255",
            min: "Số tin hiển thị từ 1 đến 255",
            digits:"Nhập một số nguyên",
        },
        icon1: {
            required: "Vui lòng chọn hình ảnh",
            max: "Đường dẫn từ 1 đến 255 ký tự",
            min: "Đường dẫn từ 1 đến 255 ký tự"
        },
        icon2: {
            required: "Vui lòng chọn hình ảnh",
            max: "Đường dẫn từ 1 đến 255 ký tự",
            min: "Đường dẫn từ 1 đến 255 ký tự"
        },
        icon3: {
            required: "Vui lòng chọn hình ảnh",
            max: "Đường dẫn từ 1 đến 255 ký tự",
            min: "Đường dẫn từ 1 đến 255 ký tự"
        },
        icon4: {
            required: "Vui lòng chọn hình ảnh",
            max: "Đường dẫn từ 1 đến 255 ký tự",
            min: "Đường dẫn từ 1 đến 255 ký tự"
        },
        icon5: {
            required: "Vui lòng chọn hình ảnh",
            max: "Đường dẫn từ 1 đến 255 ký tự",
            min: "Đường dẫn từ 1 đến 255 ký tự"
        },
        icon6: {
            required: "Vui lòng chọn hình ảnh",
            max: "Đường dẫn từ 1 đến 255 ký tự",
            min: "Đường dẫn từ 1 đến 255 ký tự"
        }
    },

    submitHandler: function(form) {
        form.submit();
    },
    errorPlacement: function(error, element) {
        error.insertAfter(element);
        notitication()
    }
});



$("#add-rank-form").validate({
    rules: {
        level_name: {
            required : true,
            maxlength: 255,
            minlength:1
        },
        image_url: {
            required : true,
            maxlength: 255,
            minlength:1
        },
        percent_special: {
            required : true,
            max: 100,
            min: 0,
            digits:true
        }
        ,
        classified_min_quantity: {
            required : true,
            max: 10000,
            min:0,
            digits:true
        }
        ,
        classified_max_quantity: {
            required : true,
            max: 10000,
            min:0,
            digits:true
        }
        ,
        deposit_min_amount: {
            required : true,
            max: 2000000000000,
            min:0,
            digits:true
        },
        deposit_max_amount : {
            required : true,
            max: 2000000000000,
            min:0,
            digits:true
        }
    },
    messages: {
        level_name: {
            required: "Vui lòng nhập tiêu đề",
            maxlength: "Tiêu đề từ 1 đến 255 ký tự",
            minlength: "Tiêu đề từ 1 đến 255 ký tự"
        },
        image_url: {
            required: "Vui lòng chọn hình ảnh",
            maxlength: "Đường dẫn hình ảnh tối đa 255 ký tự",
            minlength: "Đường dẫn hình ảnh tối đa 255 ký tự"
        },
        percent_special: {
            required: "Vui lòng nhập phần trăm",
            max: "Phần trăm từ 1 đến 100",
            min: "Phần trăm từ 1 đến 100",
            digits:"Nhập một số nguyên",
        },
        classified_min_quantity: {
            required: "Vui lòng nhập số tin tối thiểu",
            max: "Số tin trong khoảng từ 0 đến 10000",
            min: "Số tin trong khoảng từ 0 đến 10000",
            digits:"Nhập một số nguyên",
        },
        classified_max_quantity: {
            required: "Vui lòng nhập số tin tối đa",
            max: "Số tin trong khoảng từ 0 đến 10000",
            min: "Số tin trong khoảng từ 0 đến 10000",
            digits:"Nhập một số nguyên",
        },
        deposit_min_amount: {
            required: "Vui lòng nhập số tiền nạp tối thiểu",
            max: "Số tin trong khoảng từ 0 đến 2000000000000",
            min: "Số tin trong khoảng từ 0 đến 2000000000000",
            digits:"Nhập một số nguyên",
        },
        deposit_max_amount: {
            required: "Vui lòng nhập số tiền nạp tối đa",
            max: "Số tin trong khoảng từ 0 đến 2000000000000",
            min: "Số tin trong khoảng từ 0 đến 2000000000000",
            digits:"Nhập một số nguyên",
        }
    },
    submitHandler: function(form) {
     form.submit();
 }

});


$("#add-border-form").validate({
    rules: {
        list_users: {
            required : true,
        },
        image_url: {
            required : true,
            maxlength: 255,
            minlength:1
        },
    },
    messages: {
        list_users: {
            required: "Vui lòng chọn tối thiểu 1 tài khoản",
        },
        image_url: {
            required: "Vui lòng chọn hình ảnh",
            maxlength: "Đường dẫn hình ảnh tối đa 255 ký tự",
            minlength: "Đường dẫn hình ảnh tối đa 255 ký tự"
        },
    },
    submitHandler: function(form) {

        if($('#multiselect').val() != ""){
            form.submit();
            $("#errorInput").html("");
        }else{
            $("#errorInput").html('<label class="error" for="news_description">Vui lòng chọn ít nhất 1 tài khoản</label>');
        }

 }

});

$("#setting-personal-page").validate({
    rules: {
        ngatbinhluandanhgia: {
            required : true,
            max: 255,
            min:1,
            digits:true
        }
        ,
        songuoibaocao: {
            required : true,
            max: 255,
            min:1,
            digits:true
        }

    },
    messages: {
        ngatbinhluandanhgia: {
            required: "Vui lòng nhập số đánh giá hiển thị",
            max: "Số đánh giá hiển thị từ 1 đến 255",
            min: "Số đánh giá hiển thị từ 1 đến 255",
            digits: "Vui lòng nhập số nguyên"
        },
        songuoibaocao: {
            required: "Vui lòng nhập số người báo cáo",
            max: "Số người báo cáo từ 1 đến 255",
            min: "Số người báo cáo từ 1 đến 255",
            digits: "Vui lòng nhập số nguyên"
        },
    },
    submitHandler: function(form) {
       form.submit();
   }
});






