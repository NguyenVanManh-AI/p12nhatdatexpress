jQuery(document).ready(function($) {
    $(".show-hide").on("click", function() {
        $(this).parent(".info-desc").toggleClass("show");
        // $(this).fadeOut();
        if ($(this).parent(".info-desc").hasClass("show")) {
            $(this).html("Ẩn bớt >>");
        } else {
            $(this).html("Xem thêm >>");
        }
    });

    $('body').on("click",'.posts-comment .comment-act .like', function() {
        $(this).toggleClass("active");
    });

    $(".posts-comment .comment-act .reply").on("click", function() {
        $(this).parents(".posts-comment").find(".box-comment").focus();
    });

    $(".posts-content .posts-act .act-comment").on("click", function() {
        $(this)
            .parent(".posts-act")
            .siblings(".posts-comment")
            .find(".box-comment")
            .focus();
    });

    $(".posts-comment .comment-reply .count-reply").on("click", function() {
        $(this).parent(".comment-reply").addClass("show");
        $(this).fadeOut();
    });

    $("body").on(
        "click",
        ".box-function .box-like, .box-function .box-dislike",
        function() {
            $(this).toggleClass("active");
            $(this).siblings().removeClass("active");
        }
    );

    function readURL(input, name) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(name).attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // $("body").on("change", '.edit-ads input[type="file"]', function() {
    //     readURL(this, $(this).parents(".ads").find("img.img-ads"));
    // });

    // $('.upload-avatar input[type="file"]').on("change", function() {
    //     readURL(this, $(this).parents(".upload-avatar").siblings(".avatar").find("img"));
    // });

    // $('.upload-ads input[type="file"]').on("change", function(event) {
    //     let fileList = event.target.files[0],
    //         $this = $(this);
    //     var reader = new FileReader();
    //     $this.parents(".ads-left").removeClass("not-ads");
    //     reader.onload = function(e) {
    //         $this.parents(".ads-left").append(`
    //             <a href="#">
    //                 <img class="img-ads" src="${e.target.result}" alt="">
    //             </a>
    //             <div class="edit-ads">
    //                 <i class="far fa-edit"></i>
    //                 <input type="file">
    //             </div>
    //         `);
    //         $this.parent('.upload-ads').remove();
    //     };
    //     reader.readAsDataURL(fileList);
    // });

    function adsProfile() {
        let adsLeft = $(".banner-page-profile .banner-left"),
            adsRight = $(".banner-page-profile .banner-right"),
            ads = $(".banner-page-profile"),
            offsetLeft = $(".page-profile .main").offset().left,
            positions = $("footer").offset().top - 650;

        $(window).bind("scroll", function() {
            let top = $(window).scrollTop();
            if ($(window).scrollTop() != 0) {
                ads.addClass("banner-fixed");
            } else {
                ads.removeClass("banner-fixed");
                ads.css({ top: "", position: "" });
            }

            if (top >= positions) {
                $(".banner-page-profile.banner-fixed").css({
                    top: positions + "px",
                    position: "absolute",
                });
            } else {
                $(".banner-page-profile.banner-fixed").css({ top: "5px", position: "fixed" });
            }
        });

        // setTimeout(function() {
        //     adsLeft.css("left", offsetLeft - ads.outerWidth() - 15 + "px");
        //     adsRight.css("right", offsetLeft - ads.outerWidth() - 15 + "px");

        //     $(window).on("resize", function() {
        //         let offsetLeft = $(".page-profile .main").offset().left;
        //         adsLeft.css("left", offsetLeft - ads.outerWidth() - 15 + "px");
        //         adsRight.css("right", offsetLeft - ads.outerWidth() - 15 + "px");
        //     });
        // }, 1000);
    }
    if ($(".page-profile .main").length > 0) {
        adsProfile();
    }

    $(".frame-back-1 .scrollTop").on("click", function() {
        $("html").animate({ scrollTop: 0 }, "slow");
    });

    $(window).bind("scroll", function() {
        if ($(window).scrollTop() > 300) {
            $(".frame-back-1 .scrollTop").css("display", "flex");
            $(".frame-back-1").css("right", 60);
        } else {
            $(".frame-back-1 .scrollTop").css("display", "none");
            $(".frame-back-1").css("right", 125);
        }
    })

    $("body").on("click", ".show-hide-comment", function() {
        var listCmt = $(this).parents(".posts-comment").find(".list-comment")
        listCmt.toggleClass("show");
        if (listCmt.hasClass("show")) {
            $(this).parents(".posts-comment").find(".show-hide-comment").html('Ẩn bình luận');
        } else {
            $(this).parents(".posts-comment").find(".show-hide-comment").html("Xem bình luận");
        }
    });
});

function readURL(input, name) {
    if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function(e) {

            $(name).attr("src", e.target.result);

        };

        reader.readAsDataURL(input.files[0]);

    }

}
$('.edit-avatar input[type="file"]').on("change", function() {

    readURL(this, $(this).parent(".edit-avatar").siblings("img"));

});
