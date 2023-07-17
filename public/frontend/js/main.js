jQuery(document).ready(function($) {
    $('.list-forcus').owlCarousel({
        loop: true,
        margin: 20,
        // autoplay: true,
        nav: true,
        dots: false,
        navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>'],
        items: 4,
        responsive:{
            0:{
                items:1,
                nav:false,
                },
            600:{
                items:2,
                nav:false,
                 },
            800:{
                items:3,
                nav:false,
            },
             1000:{
                items:4,
                nav:false,
                margin: 5,
                }
            }
    });
    // $("body").on("click", ".btn-place", function (event) {
    //     event.preventDefault();
    //     $("#map-fixed").show();
    //     $("#layout").show();
    // });
    $('.btn-plus, .btn-minus').on('click', function(e) {
      const isNegative = $(e.target).closest('.btn-minus').is('.btn-minus');
      const input = $(e.target).closest('.form-gate').find('input');
      if (input.is('input')) {
        input[0][isNegative ? 'stepDown' : 'stepUp']()
      }
    })
    $('.btn-default').on('click', function(e) {
      var value = $(this).attr("data-value");
      $("#myNumber").val(value);
    })
    $("body").on("click", ".upload .button-upload", function (event) {
        event.preventDefault();
        $(this).siblings("input").trigger("click");
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
    $('#upload-img').on("change", function (event) {
        readURL(this, ".img-old-info");
    });

    $('#upload-img-ava').on("change", function (event) {
        readURL(this, ".avatar-introduce");
    });

    $('body').on('click', '.exitMail', function(event) {
        event.preventDefault();
        Swal.fire({
          position: 'inherit',
          icon: 'success',
          title: 'Thoát cấu hình',
          showConfirmButton: false,
          timer: 1500
        });
    });
    $('body').on('click', '.saveMail', function(event) {
        event.preventDefault();
        Swal.fire({
          position: 'inherit',
          icon: 'success',
          title: 'Lưu thành công ',
          showConfirmButton: false,
          timer: 1500
        });
    });

    var images = document.images;
    for (var i=0; i<images.length; i++) {
        images[i].src = images[i].src.replace(/\btime=[^&]*/, 'time=' + new Date().getTime());
    }
});
