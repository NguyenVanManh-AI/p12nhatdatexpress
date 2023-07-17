function niceNumber(num) {
    let result = "";
    if(num >= 1000000 && num < 1000000000) {
        result = (num/1000000).toFixed(2);
    }
    if(num >= 1000000000) {
        result = (num/1000000000).toFixed(2);
    }
    return result;
}
jQuery(document).ready(function ($) {
    $(".preview-slide").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        infinite: true,
        autoplay: true,
        arrows: false,
    });
    $(".project-banner-slide").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
    });
    $(".buy-rent-banner-slide").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        dots: true,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
    });
    $(".property-banner-slide").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        arrows: true,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
        responsive: [
            {
                breakpoint: 769,
                settings: {
                    customPaging: function(slider, i) {
                        var src = this.$slides.eq(i).find("img").attr("src");
                        return `<img src="${src}"/>`;
                    },
                }
            }
        ]
    });
    var zxc = $(".img-slide").slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        infinite: false,
        autoplay: false,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
    });
    $('body').on('click', '.img-slide .img-group i', function(event) {
        let slide = $(this).parents('.slick-slide'),
            slide_index = slide.index();
        zxc.slick('slickRemove', slide_index);
    });
    $(".vote-bar").each(function (index, el) {
        let bar = $(this).find(".vote-bar-inside"),
            bar_val = $(this).find("input[name=yes]").val(),
            bar_val_rev = $(this).find("input[name=no]").val(),
            total_val = +bar_val + +bar_val_rev,
            bar_percent = (bar_val / total_val) * 100;
        $(this)
            .find(".vote-bar-inside")
            .css("width", bar_percent + "%");
    });
    $("body").on("click", ".single-tabs .switcher", function (event) {
        event.preventDefault();
        let tabid = $(this).data("tabid");
        if ($(this).hasClass("active") == false) {
            $(this).addClass("active");
            $(this).siblings(".switcher").removeClass("active");
        }
        if ($("#" + tabid).hasClass("active") == false) {
            $("#" + tabid).addClass("active");
            $("#" + tabid)
                .siblings(".tab")
                .removeClass("active");
        }
    });
    var result_max_w = 0;
    $(".result-group").each(function (index, el) {
        let span = $(this).find("span");
        if (result_max_w < span.width()) {
            result_max_w = span.width();
        }
    });
    $(".result-group").each(function (index, el) {
        let span = $(this).find("span");
        if (span.width() <= result_max_w) {
            span.css("width", result_max_w + "px");
        }
    });
    var total_review = $(".review-count input").val();
    $(".result-group").each(function (index, el) {
        let bar_input = $(this).find("input"),
            bar_w = (bar_input.val() / total_review) * 100,
            bar = $(this).find(".result-bar-inside");
        bar.css("width", bar_w + "%");
    });
    $("body").on("mouseover", ".project-comment > .head i", function (event) {
        let head = $(this).parents(".head");
        head.addClass("hovered");
    });
    $("body").on("mouseout", ".project-comment > .head i", function (event) {
        let head = $(this).parents(".head");
        head.removeClass("hovered");
    });
    $("body").on("click", ".item-foot", function (event) {
        event.preventDefault();
        event.stopPropagation();
        var item = $(this).parents(".item"),
            table = item.find(".detail"),
            arrow = $(this).find('i.fas');
        if (arrow.hasClass("fa-angle-double-down") == true) {
            arrow.removeClass("fa-angle-double-down");
            arrow.addClass("fa-angle-double-up");
            arrow.css("color", "#FF0A0A");
        } else {
            arrow.removeClass("fa-angle-double-up");
            arrow.addClass("fa-angle-double-down");
            arrow.css("color", "#3AAD00");
        }
        table.slideToggle();
    });
    // $("body").on("click", ".list-item-account .item-account i.fa-chevron-right", function (event) {
    //     event.preventDefault();
    //     let item = $(this).parents(".item-account"),
    //         item_sib = item.siblings(".item-account"),
    //         sub = item.next(".sub"),
    //         item_sib_sub = item_sib.next(".sub");
    //     if (!item.hasClass("sub-toggle")) {
    //         item.addClass("sub-toggle");
    //         sub.slideDown();
    //         item_sib.removeClass("sub-toggle");
    //         item_sib_sub.slideUp();
    //     } else {
    //         item.removeClass("sub-toggle");
    //         sub.slideUp();
    //     }
    // });
    $("body").on("click", ".project-vote .vote-button", function (event) {
        event.preventDefault();
        let block = $(this).parents(".project-vote"),
            block_sib = block.siblings(".project-vote-enable");
        block.hide();
        block_sib.show();
    });
    $("body").on("click", ".popup .close-button", function (event) {
        event.preventDefault();
        let popup = $(this).parents(".popup"),
            layout = popup.siblings("#layout");
        popup.hide();
        layout.hide();
    });
    $("body").on("click", ".popup .wrapper > i", function (event) {
        event.preventDefault();
        let popup = $(this).parents(".popup"),
            layout = popup.siblings("#layout");
        popup.hide();
        layout.hide();
    });
    $("body").on("click", "#layout", function (event) {
        event.preventDefault();
        let popup = $(this).siblings(".popup");
        $(this).hide();
        popup.hide();
        $('.m-menu-icon').removeClass('active');
        $('#m-menu').removeClass('show');
    });
    $("body").on("click", ".map-overlay", function (event) {
        event.preventDefault();
        let map = $("#map-fixed");
        map.show();
        $("#layout").show();
    });
    $("body").on("mouseover", ".receiver label", function (event) {
        event.preventDefault();
        let stars = $(this).prevAll();
        $(this).children('i').removeClass("far");
        stars.children('i').removeClass("far");
        $(this).children('i').addClass("fas");
        stars.children('i').addClass("fas");
        $(this).children('i').css("color", "#FFD62B");
        stars.children('i').css("color", "#FFD62B");
    });
    $("body").on("mouseout", ".receiver label", function (event) {
        event.preventDefault();
        if(!$('input[name="rating"]:checked').val()){
            let stars = $(this).siblings('label').children('i.fas');
            $(this).children('i').removeClass("fas");
            stars.removeClass("fas");
            $(this).children('i').addClass("far");
            stars.addClass("far");
            $(this).children('i').css("color", "#454545");
            stars.css("color", "#454545");
        }
        else {
            var value = $('input[name="rating"]:checked').val();
            let stars = $(this).siblings('label:nth-child('+value+')').nextAll('label');
            // stars.children('i').css('color','red');
            // $(this).children('i').removeClass("fas");
            stars.children('i.fas').removeClass("fas");
            // $(this).children('i').addClass("far");
            stars.children('i').addClass("far");
            // $(this).children('i').css("color", "#454545");
            stars.children('i').css("color", "#454545");
        }
    });
    $('.project-review .review-receiver input[type="radio"]').on('change',function (e){
        // $('input[type="radio"]').on('change',function (e){
        e.preventDefault();
        var value = $(this).val();
        let stars = $(this).parents('label:nth-child('+value+')').nextAll('label');
        stars.children('i.fas').removeClass("fas");
        stars.children('i').addClass("far");
        stars.children('i').css("color", "#454545");
        // stars.empty();
    });

    $("body").on('click', '#m-menu .post-button', function(event) {
    	event.preventDefault();
    	let post_popup = $("#new-post"),
            layout = $("#layout");
        post_popup.show();
        layout.show();
        $('#m-menu').removeClass('show');
        $('.m-menu-icon').removeClass('active');
    });
    $("body").on("click", ".log .btn-login", function (event) {
        event.preventDefault();
        let login_popup = $("#login"),
            layout = $("#layout");
        $('*[data-wrap=".wrapper-login"]').addClass("active");
        $('*[data-wrap=".wrapper-login"]').siblings(".log-switch").removeClass("active");
        $("#login .wrap").removeClass("active");
        $("#login .wrapper-login").addClass("active");
        login_popup.show();
        login_popup.css('top', $(window).scrollTop() + 30);
        layout.show();
        $('#m-menu').removeClass('show');
        $('.m-menu-icon').removeClass('active');
    });
    $("body").on("click", ".log .btn-regis", function (event) {
        event.preventDefault();
        let login_popup = $("#login"),
            layout = $("#layout");
        $('*[data-wrap=".wrapper-register"]').addClass("active");
        $('*[data-wrap=".wrapper-register"]').siblings(".log-switch").removeClass("active");
        $("#login .wrap").removeClass("active");
        $("#login .wrapper-register").addClass("active");
        login_popup.show();
        login_popup.css('top', $(window).scrollTop() + 30);
        layout.show();
        $('#m-menu').removeClass('show');
        $('.m-menu-icon').removeClass('active');
    });
    $("body").on("click", "#login .log-switch", function (event) {
        event.preventDefault();
        let wrap_data = $(this).data("wrap"),
            wrap = $(wrap_data);
        if ($(this).hasClass("active") == false) {
            $(this).addClass("active");
            $(this).siblings(".log-switch").removeClass("active");
        }
        if (wrap.hasClass("active") == false) {
            wrap.addClass("active");
            wrap.siblings(".wrap").removeClass("active");
        }
    });
    $("body").on("click", "#login .type-switcher .tag", function (event) {
        event.preventDefault();
        let type = $(this).data("type"),
            content = $(type);
        if ($(this).hasClass("active") == false) {
            $(this).addClass("active");
            content.addClass("active");
        }
        $("#login .tag").not($(this)).removeClass("active");
        content.siblings(".type-wrapper").removeClass("active");
    });
    $("body").on("click", "#new-post .login-button", function (event) {
        event.preventDefault();
        $("#login").show();
        $("#layout").show();
        $(this).parents(".popup").hide();
    });
    $("body").on("click", ".expert .register", function (event) {
        event.preventDefault();
        $('*[data-wrap=".wrapper-register"]').addClass("active");
        $('*[data-wrap=".wrapper-register"]').siblings(".log-switch").removeClass("active");
        $("#login .wrap").removeClass("active");
        $("#login .wrapper-register").addClass("active");
        $("#login").show();
        $("#layout").show();
        $("#login").css('top', $(window).scrollTop() + 30);
    });
    $("body").on("click", "#new-post .post-button", function (event) {
        event.preventDefault();
        $(this).parents(".popup").hide();
        $("#quick-post-2").show();
        $("#quick-post-2").css('top',$(window).scrollTop() + 30);
        $("#layout").show();
        zxc.slick('refresh');
    });
    $("body").on("click", "#quick-post-2 .three-box .box", function (event) {
        event.preventDefault();
        if ($(this).hasClass("active") == false) {
            $(this).addClass("active");
        }
        $(this).siblings(".box").removeClass("active");
    });
    $("body").on("click", ".item .location a", function (event) {
        event.preventDefault();
        $("#map-fixed").show();
        $("#map-fixed").css('top', $(window).scrollTop() + 30 + 'px');
        $("#layout").show();
    });

    $("body").on("click", ".project-event .button", function (event) {
        event.preventDefault();
        $("#create-event").show();
        $("#create-event").css('top',$(window).scrollTop() + 30);
        $("#layout").show();
    });
    // $("body").on("click", ".widget .add-event .btn-add-event", function (event) {
    //     event.preventDefault();
    //     $("#create-event").show();
    //     $("#layout").show();
    // });
    $("body").on("click", ".holder", function (event) {
        event.preventDefault();
        if ($(this).hasClass("holder holder-off") == true) {
            $(this).removeClass("holder-off");
            $(this).addClass("holder-on");
        } else {
            $(this).removeClass("holder-on");
            $(this).addClass("holder-off");
        }
    });
    $("body").on("click", ".single-meta .report-button", function (event) {
        event.preventDefault();
        if ($(this).hasClass('disable-action')) return
        $("#report").show();
        $("#layout").show();
    });
    $("body").on("click", ".share-button", function (event) {
        // event.preventDefault();
        $(this).find(".share-sub").show();
    });
    $(document).mouseup(function (e) {
        var container = $(".share-sub");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    });
    $("body").on("click", ".upload-btn-license", function (event) {
        event.preventDefault();
        $(this).siblings('input[type=file]').trigger("click");
    });
    function readURL(input, name) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(name).attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $('input[name="upload-cover"]').on("change", function (event) {
        readURL(this, ".upload-cover");
        $(".upload-cover").parents(".upload-image").css({ opacity: "1", height: "auto" });
    });
    $('input[name="upload-letter"]').on("change", function (event) {
        readURL(this, ".upload-letter");
        $(".upload-letter").parents(".upload-image").css({ opacity: "1", height: "auto" });
    });
    $('input[name="upload-license"]').on('change', function(event) {
        readURL(this, ".upload-license");
        $(this).siblings("input[type=submit]").show();
    });
    // should change
    $("body").on("click", ".remove-img", function (event) {
        event.preventDefault();
        let input = $(this).data("input");
        $(this).parents(".popup").find('input[name="' + input + '"]').val("");

        if ($(this).hasClass('is-opacity-class')) {
            $(this).parents(".upload-image").addClass('opacity-hide')
            $(this).parents(".upload-image").find('input').val('')
        } else {
            $(this).parents(".upload-image").css({ opacity: "0", height: "0" });
        }

        $(this).siblings(".upload-img").attr("src", "");
    });
    // end should change
    $("body").on("click", ".right .sort", function (event) {
        event.preventDefault();
        $(this).find(".sort-sub").toggle();
    });
    $("body").on('click', '.right .sort-sub li', function(event) {
        event.preventDefault();
        let data = $(this).html(),
            selected = $(this).parents('.sort-sub').siblings('.selected');
            selected.html(data);
    });
    $(document).mouseup(function (e) {
        var container = $(".sort-sub");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    });
    // $("body").on("click", "a.view-map", function (event) {
    //     event.preventDefault();
    //     $("#map-fixed-project").show();
    //     $('#map-fixed-project').css('top', $(window).scrollTop() + 30 + 'px');
    //     $("#layout").show();
    // });
    $("body").on("click", ".btn-register", function (event) {
        event.preventDefault();
        let login_popup = $("#login"),
            layout = $("#layout");
        $('*[data-wrap=".wrapper-register"]').addClass("active");
        $('*[data-wrap=".wrapper-register"]').siblings(".log-switch").removeClass("active");
        $("#login .wrap").removeClass("active");
        $("#login .wrapper-register").addClass("active");
        login_popup.show();
        login_popup.css('top', $(window).scrollTop() + 30);
        layout.show();
    });
    $("body").on("click", ".hide-phone", function (event) {
        event.preventDefault();
        event.stopPropagation();
        let number = $(this).data("phone"),
            display = $(this).siblings("a").find(".display-phone");
        $(this).hide();
        $(display).addClass('copy_phone');
        display.html(number);
    });
    $('body').on('click','.copy_phone',function (e){
        e.preventDefault();
        e.stopPropagation();
        var input = $('<input>');
        $('body').append(input);
        var temp = $(this).html();
        input.val(temp).select();
        document.execCommand("copy");
        input.remove();
        toastr.success('Đã copy');
    });
    $("body").on("click", ".meta-item .hide-phone", function (event) {
        event.preventDefault();
        let number = $(this).data("phone"),
            display = $(this).siblings("a");
        $(this).hide();
        display.html(number);
    });
    // $("body").on('click', '.place .button', function(event) {
    //     event.preventDefault();
    //     $('#map-fixed').show();
    //     $('#map-fixed').css('top', $(window).scrollTop() + 30 + 'px');
    //     $('#layout').show();
    // });
    // $('body').on('click', '.agency .button.red', function(event) {
    //     event.preventDefault();
    //     let agency = $(this).parents('.agency');
    //     agency.find('.meta').hide();
    //     agency.find('.social').hide();
    //     agency.find('.reg-form').show();
    //     agency.find('.wrapper-basic').hide();
    //     agency.find('.wrapper-regis').show();
    // });
    // $('body').on('click', '.agency .button.grey', function(event) {
    //     event.preventDefault();
    //     let agency = $(this).parents('.agency');
    //     agency.find('.meta').show();
    //     agency.find('.social').show();
    //     agency.find('.reg-form').hide();
    //     agency.find('.wrapper-basic').show();
    //     agency.find('.wrapper-regis').hide();
    // });
    $('#re-value').ionRangeSlider({
        min: 500,
        max: 10000,
        from: 1500,
        step: 100,
        skin: "round",
        onStart: function(data) {
            $('#re-value-input').prop("value", data.from);
            let val = ( $('#re-value-input').val() * $('#money-input-percent').val() ) / 100;
            $('#money-input').val(val);
        },
        onChange: function(data) {
            $('#re-value-input').prop("value", data.from);
            let val = ( $('#re-value-input').val() * $('#money-input-percent').val() ) / 100;
            $('#money-input').val(val);
        },
        onUpdate: function(data) {
            $('#re-value-input').prop("value", data.from);
            let val = ( $('#re-value-input').val() * $('#money-input-percent').val() ) / 100;
            $('#money-input').val(val);
        }
    });
    $('#money').ionRangeSlider({
        min: 1,
        max: 100,
        from: 70,
        step: 1,
        skin: "round",
        onStart: function(data) {
            $('#money-input-percent').prop("value", data.from);
            let val = ( $('#re-value-input').val() * $('#money-input-percent').val() ) / 100;
            $('#money-input').val(val);
        },
        onChange: function(data) {
            $('#money-input-percent').prop("value", data.from);
            let val = ( $('#re-value-input').val() * $('#money-input-percent').val() ) / 100;
            $('#money-input').val(val);
        },
        onUpdate: function(data) {
            $('#money-input-percent').prop("value", data.from);
            let val = ( $('#re-value-input').val() * $('#money-input-percent').val() ) / 100;
            $('#money-input').val(val);
        }
    });
    $('#time').ionRangeSlider({
        min: 1,
        max: 30,
        from: 10,
        step: 1,
        skin: "round",
        onStart: function(data) {
            $('#time-input').prop("value", data.from);
        },
        onChange: function(data) {
            $('#time-input').prop("value", data.from);
        },
        onUpdate: function(data) {
            $('#time-input').prop("value", data.from);
        }
    });
    $('#rate').ionRangeSlider({
        min: 1,
        max: 10,
        from: 8,
        step: 1,
        skin: "round",
        onStart: function(data) {
            $('#rate-input').prop("value", data.from);
        },
        onChange: function(data) {
            $('#rate-input').prop("value", data.from);
        },
        onUpdate: function(data) {
            $('#rate-input').prop("value", data.from);
        }
    });
    $('#re-value-input').on('change keyup', function(event) {
        event.preventDefault();
        let val = $(this).prop("value"),
            min = 500,
            max = 10000;
        if (val < min) {
            val = min;
        } else if (val > max) {
            val = max;
        }
        $('#re-value').data("ionRangeSlider").update({
            from: val
        });
    });
    $('#money-input-percent').on('change keyup', function(event) {
        event.preventDefault();
        let val = $(this).prop("value"),
            min = 1,
            max = 100;
        if (val < min) {
            val = min;
        } else if (val > max) {
            val = max;
        }
        $('#money').data("ionRangeSlider").update({
            from: val
        });
    });
    $('#time-input').on('change keyup', function(event) {
        event.preventDefault();
        let val = $(this).prop("value"),
            min = 1,
            max = 30;
        if (val < min) {
            val = min;
        } else if (val > max) {
            val = max;
        }
        $('#time').data("ionRangeSlider").update({
            from: val
        });
    });
    $('#rate-input').on('change keyup', function(event) {
        event.preventDefault();
        let val = $(this).prop("value"),
            min = 1,
            max = 10;
        if (val < min) {
            val = min;
        } else if (val > max) {
            val = max;
        }
        $('#rate').data("ionRangeSlider").update({
            from: val
        });
    });
    const single_chart = $('#single-chart');
    if(single_chart && single_chart.length > 0) {
    const chart = new Chart(single_chart,{
        type: 'doughnut',
        data: {
            labels: ['Lãi cần trả','Cần trả trước','Gốc cần trả'],
            datasets: [{
                label: "Test",
                data: [800000000,450000000,1050000000],
                backgroundColor: ['#EA9851','#1154D4','#403D64']
            }]
        },
        options: {
            cutoutPercentage: 80,
            legend: {
                display: false
            }
        }
    });
    }
    $('body').on('click', '.item-setting-account.delete', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa?',
            text: "Hành động này không thể hoàn lại!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có',
            cancelButtonText: 'Không'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Đã xóa!'
                )
            }
        });
    });
    $('body').on('click', '.item-setting-account.refresh a', function(event) {
        event.preventDefault();
        $('.use-code-payment').show();
        $('.overlay').show();
    });
    $('body').on('click', '.list-button-post-land .btn', function(event) {
        event.preventDefault();
        let form = $(this).data("form");
        $('.list-button-post-land .btn').removeClass('active');
        $(this).addClass('active');
        $('.check-payment-post-land').removeClass('active');
        $(form).addClass('active');
    });
    $('#datepicker, #datepicker-1').on('change', function(event) {
        event.preventDefault();
        if($(this).val() != "") {
            $(this).css('background-image','none');
        } else {
            $(this).css('background-image','url(../../images/calendar-icon.png)');
        }
    });
    $(window).on('scroll', function(event) {
        let scroll = $(window).scrollTop(),
            offset = $('footer').offset().top,
            position = $('footer').offset().top - 650;
        if($(window).scrollTop() != 0) {
            $('.banner-qc').addClass('fixbox');
        } else {
            $('.banner-qc').removeClass('fixbox');
            $('.banner-qc').css({'top' :  '' , 'position' : ''});
        }
        if(scroll >= position) {
            $('.banner-qc.fixbox').css({'top' : position + 'px' , 'position' : 'absolute'});
        } else {
            $('.banner-qc.fixbox').css({'top' : '0' , 'position' : 'fixed'});
        }
    });
    $('body').on('click', '.m-menu-icon', function(event) {
        event.preventDefault();
        $(this).toggleClass('active');
        $('#layout').show();
        $('#m-menu').addClass('show');
    });
    $('.has-child').each(function(index, el) {
        $(this).append('<i class="fas fa-chevron-down"></i>');
    });
    $('body').on('click', '.has-child > i', function(event) {
        event.preventDefault();
        $(this).siblings('.sub').slideToggle();
        $(this).siblings('.sub').find('.sub').slideUp();
    });
    $(window).on('load resize', function(event) {
        var total_height = $('header').outerHeight() + $('.single-title').outerHeight(true) + $('.single-meta').outerHeight(true) + $('.single-contact-buttons').outerHeight(true) + 30;
        if($(window).width() <= 768) {
            setTimeout(function(){
                $('.property-banner-slide').css('height','calc(100vh - '+total_height+'px)');
                $('.project-banner-slide').css('height','calc(100vh - '+total_height+'px)');
                $('.buy-rent-banner-slide').css('height','calc(100vh - '+total_height+'px)');
            },1000);
        }
    });
    // $('body').on('click', '.reaction .reply', function(event) {
    //     event.preventDefault();
    //     $(this).toggleClass('active');
    //     $(this).parents('.comment-item').find('.reply-form').toggleClass('active');
    // });
    $('body').on('click', '.upload-btn', function(event) {
        event.preventDefault();
        $(this).siblings('input').trigger('click');
    });
    $('body').on('click', '.single-relate-more', function(event) {
        event.preventDefault();
        $(this).hide();
        $(this).siblings('.single-relate-list').find('.item.hide').each(function(index, el) {
            $(this).removeClass('hide');
        });
    });
    // $(window).on('load', function(event) {
    //     event.preventDefault();
    //     let id_active = $('.category-switcher').attr('id');
    //     // alert(id_active);
    //     $('.category-switcher .switcher').each(function(index, el) {
    //         if($(this).attr('data-tab-active') == id_active) {
    //             $(this).trigger('click');
    //         }
    //     });
    // });
    $(window).on('scroll', function(event) {
        if($(window).scrollTop() > 0) {
            $('.single-contact-buttons').css({'position': 'fixed','bottom': '15xp'});
        } else {
            $('.single-contact-buttons').css('position','relative');
        }
    });
});
