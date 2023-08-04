        jQuery(document).ready(function() {
            $(document).on("click", '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();

                $(this).ekkoLightbox();
            });

            $(".list-property.slick").slick({
                slidesToShow: 3,

                slidesToScroll: 1,

                autoplay: true,

                autoplaySpeed: 4000,

                prevArrow: '<div class="nav-left"><i class="fas fa-chevron-left"></i></div>',

                nextArrow: '<div class="nav-right"><i class="fas fa-chevron-right"></i></div>',

                responsive: [{
                    breakpoint: 602,
                    settings: {
                        slidesToShow: 2
                    }
                }]
            });

            var zxc = $('.list-update-slider-image.slick').slick({
                slidesToShow: 4,

                slidesToScroll: 1,

                autoplay: true,

                autoplaySpeed: 4000,

                infinite: false,

                prevArrow: '<div class="nav-left list-update"><i class="fas fa-chevron-left"></i></div>',

                nextArrow: '<div class="nav-right list-update"><i class="fas fa-chevron-right"></i></div>',
                draggable: false,
                // should check why response not work exactly
                // responsive: [
                //     {
                //         breakpoint: 1024,
                //         settings: {
                //             slidesToShow: 3,
                //             slidesToScroll: 1,
                //         },
                //     },
                //     {
                //         breakpoint: 600,
                //         settings: {
                //             slidesToShow: 2,
                //             slidesToScroll: 1,
                //         },
                //     },
                //     {
                //         breakpoint: 480,
                //         settings: {
                //             slidesToShow: 1,
                //             slidesToScroll: 1,
                //         },
                //     },
                // ],
            });

            $('.item-account').on('click', function() {
                $(this).children(".menu-child").toggleClass("active");
                $(this).toggleClass('is-open')
            });

            $('.content-rights .show-more a').on('click', function(e) {
                e.preventDefault();
                $(this).fadeOut();
                $('.list-item').css("height", "auto");
                $('.icon-contact').css("padding-bottom", "20px");
            });
            $('.glbtags .show-more a').on('click', function(e) {
                e.preventDefault();
                $(this).fadeOut();
                $('.global-tags').css("height", "auto");
            });

            $('.toggle-sidebar-header').click(function() {
                /*       var te = $('.widget.widget-account').toggleClass('show');*/
                var height = $('.header-account-business').outerHeight();
                $('.content-left-account').css('background-color', '#e9ecf5');
                $('.content-left-account').toggleClass('hide');
                // $('.sidebar-footer').toggleClass('hide');
                $('.content-left-account.hide').css('top',height);
                /*        $('.layout-web').children('.col-md-3').toggleClass('hide');*/
                $('.layout-web').children('.manager-content').toggleClass('full');
                // $('.over-lay-1').toggleClass('show');
                $(window).trigger("resize");
            });

            $('.over-lay-1').click(function() {
                $('.content-left-account').removeClass('hide');
                $(this).removeClass('show');
            });

            $('.close-button-usecode').click(function() {
                $('.use-code-payment').css('display', 'none');
                $('.use-code-payment-1').css('display', 'none');
                $('.overlay').css('display', 'none');
                $('.overlay-1').css('display', 'none');
            });

            $(".reviews").click(function() {
                $(this).children('.icon').toggleClass('like');
            });

            $('.posts-more').click(function() {
                $(this).children('.modal-post-more').toggleClass('show');
            });

            $('.posts-content .detail-desc .show-hide').click(function() {
                $(this).parents('.detail-desc').find('.desc').addClass('show');
                $(this).parents('.detail-desc').find('.hide-show').css('display', 'block');
                $(this).css('display', 'none');
            });

            $('.posts-content .detail-desc .hide-show').click(function() {
                $(this).parents('.detail-desc').find('.desc').removeClass('show');
                $(this).parents('.detail-desc').find('.show-hide').css('display', 'block');
                $(this).css('display', 'none');
            });
            // lightbox video

            $(".html5lightbox").html5lightbox();

            $(".posts-reaction").on("click", function() {
                $(this).toggleClass("active");
            });

            $('.show-number').on('click', function() {
                $(this).fadeOut()
                $(this).parents(".number-phone").find(".show-phone").css("max-width", "100%");
            });
            $(".cs-select").select2({
                allowClear: true,
                placeholder: $(this).attr('data-placeholder')
            });
            $("#select-all").change(function() {
                $(".checkbox1").prop('checked', $(this).prop("checked"));
            });

            $('.use-code').click(function() {
                $('.use-code-payment').css('display', 'block');
            });
            $('.use-code-1').click(function() {
                $('.use-code-payment-1').css('display', 'block');
                $('.overlay').css('display', 'block');
            });
            $('body').on('click', '.delete-project , .del-code  , .delete-account-button ', function(event) {
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

            $('body').on('click', '.btn-success.lent-coin', function(event) {
                event.preventDefault();
                Swal.fire({
                    position: 'inherit',
                    icon: 'success',
                    title: 'Tặng tin thành công',
                    showConfirmButton: false,
                    timer: 1500
                });
            });

            $('body').on('click', '.btn-save-mail', function(event) {
                event.preventDefault();
                Swal.fire({
                    position: 'inherit',
                    icon: 'success',
                    title: 'Lưu thành công',
                    showConfirmButton: false,
                    timer: 1500
                });
            });

            $('body').on('click', '.btn-send', function(event) {
                event.preventDefault();
                Swal.fire({
                    position: 'inherit',
                    icon: 'success',
                    title: 'Gửi mail thành công ',
                    showConfirmButton: false,
                    timer: 1500
                });
            });

            $('body').on('click', '.btn-add', function(event) {
                event.preventDefault();
                Swal.fire({
                    position: 'inherit',
                    icon: 'success',
                    title: 'Cập nhật thành công',
                    showConfirmButton: false,
                    timer: 1500
                });
            });

            $('body').on('click', '.outstanding-1', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc không?',
                    text: "Bạn sẽ không thể hoàn nguyên lại điều này!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xác nhận'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'Làm nổi bật thành công !',
                            '',
                            'success'
                        )
                    }
                })
            });

            $('.copy-link-account').click(function () {
                var link = $('.link-need-copy').text();
                document.execCommand("copy");
                Swal.fire('Sao chép thành công');
                console.log(link);
            });
            $('.add-money-side').click(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Gặp sự cố trong quá trình nạp!',
                    footer: '<a href="">liên hệ với chúng tôi?</a>'
                })
            });

            function copyText1(copyText) {
                /*copyText.select();
                copyText.setSelectionRange(0, 99999);*/
                navigator.clipboard.writeText(copyText);
            }
            $('.copy-text').click(function() {
                var link = $('.copy-stk.hi').text();
                copyText1(link);
                Swal.fire('Sao chép thành công');
            });
            $('.dangky').click(function() {
                var test = $(this).parent('.item_cont').find('.basic').children('.text-basic').text();
                $('.choose-package-number').text(test);
            });

            $('body').on("click", ".dangky1", function() {
                var test1 = $(this).parent('.item_cont_packet').find('.basic').children('.text-basic').text();
                $('.choose-package-number').text(test1);
            });

            $('#confirm-payment').click(function() {
                if ($(this).prop('checked') == false) {
                    $('.btn-confirm').prop('disabled', true);
                } else {
                    $('.btn-confirm').prop('disabled', false);
                }
            });

            $(function() {
                $('.btn-confirm').prop('disabled', true);
            });
            $('.remove-img-1').click(function() {
                $('.upload-img').attr('src', '');
            });
            $(".btn-load").click(function() {
                var test = $("#categories").find('option:selected').text();
                $(".choose-package-number").text(test);
                $(".pm-price").text(test);
            });
            $("textarea#content-care").on('keypress', function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    Swal.fire({
                        position: 'inherit',
                        icon: 'success',
                        title: 'Bạn đã gửi thành công',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $(this).val("");
                }
            });
            $("textarea#input-messenger").on('keypress', function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    var hii = $(this).val();
                    if (hii != "") {
                        $(this).val('');
                        $('.center-boxchat').children(".center-content-boxchat").append(`<div class="messenger-boxchat right">
                                    <span>` + hii + `</span>
                                    <img src="../images/mess2.png">
                                </div>`);
                    }
                }
            });
            $(".btn-chat i.fas.fa-times").click(function() {
                $(".technical-assistance-chat").css('display', 'none');
            });
            $(".active").click(function() {
                $(".technical-assistance-chat").css('display', 'block');
                var name = $(this).parents(".item.text-center").children(".name").text();
                var avata = $(this).parents(".item.text-center").children(".avatar").find("img").attr("src");
                $(".top-boxchat").find("img").attr("src", avata);
                $(".top-boxchat").find("h3").text(name);
                $(".messenger-boxchat.left").find("img").attr("src", avata);
            });
//            $('#star-rating-demo').rating();
//            $(function() { // Start when document ready
//                $('#star-rating').rating(); // Call the rating plugin
//            });
            $(".btn-chat i.fas.fa-window-minimize").click(function() {
                $(".technical-assistance-chat").toggleClass("active");
            });
            $(".over-lay-popup").click(function() {
                $("#login").css("display", "none");
                $(this).css("display", "none");
            });

            function readURL(input) {
                if (input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewHolder').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#filePhoto").change(function() {
                readURL(this);
                $(".img-show-upload").css("display", "block");
            });
            $(".tag").click(function() {
                $(".click-access.not-active").css("display", "none");
                $(".nitication-type-login.not-active").css("display", "none");
            });
            $('body').on('click', '.show-text-job', function() {
                $(this).siblings(".text-job-hide").css("display", "block");
                $(this).css("display", "none");
            });
            $(".text-job-hide").on('keypress', function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    var hi = $(this).val();
                    $(this).siblings(".job.hide").text(hi).css("display", "block");
                    $(this).css("display", "none");
                }
            });
            $(window).scroll(function() {
                var offsetPixels = $('.site-header-container').outerHeight();
                var offsetPixels2 = $('.position-pakage').outerHeight();
                var offsetPixels3 = $('.general-rules').outerHeight();
                var offsetPixels4 = $('.general-rules-1').outerHeight();
                var sumoff = offsetPixels + offsetPixels2 + 30;
                var sumoff1 = sumoff + offsetPixels3;
                if ($(window).scrollTop() >= offsetPixels) {
                    $('.sidebar-media-quotes').addClass('scrolling');
                    $('.list-media-sidebar').find('#media-01').addClass('active');
                    $('.list-media-sidebar').find('#media-02').removeClass('active');
                    $('.list-media-sidebar').find('#media-03').removeClass('active');
                } else {
                    $('.sidebar-media-quotes').removeClass('scrolling');
                    $('.item-media-sidebar').removeClass('active');
                }
                if ($(window).scrollTop() >= sumoff) {
                    $('.list-media-sidebar').find('#media-02').addClass('active');
                    $('.list-media-sidebar').find('#media-01').removeClass('active');
                    $('.list-media-sidebar').find('#media-03').removeClass('active');
                }
                if ($(window).scrollTop() >= sumoff1) {
                    $('.list-media-sidebar').find('#media-03').addClass('active');
                    $('.list-media-sidebar').find('#media-01').removeClass('active');
                    $('.list-media-sidebar').find('#media-02').removeClass('active');
                }
                if ($(window).scrollTop() >= sumoff1 + offsetPixels4 - 120) {
                    $('.sidebar-media-quotes').removeClass('scrolling');
                    $('.list-media-sidebar').find('#media-01').removeClass('active');
                    $('.list-media-sidebar').find('#media-02').removeClass('active');
                    $('.list-media-sidebar').find('#media-03').removeClass('active');
                }
            });
            // if ($('.news-item').length >= 8) {
            //     $('.news-item:gt(7)').hide();
            // }
            $('.view-more').on('click', function(event) {
                event.preventDefault();
                $('.news-item:gt(7)').toggle();
                $(this).css("display", "none");
            });
        });
