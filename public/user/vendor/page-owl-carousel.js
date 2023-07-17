(() => {
    // level page
    if ($('.user-level-page .user-level__carousel') && $('.user-level-page .user-level__carousel').length) {
        let currentLevel = $('.user-level-page .user-level__current-box').data('current-index') || 0

        $('.user-level-page .user-level__carousel').owlCarousel({
            loop: true,
            margin: 20,
            startPosition: currentLevel,
            lazyLoad: true,
            nav: true,
            dots: false,
            navText: ['<span class="c-arrow arrow-left"></span>', '<span class="c-arrow arrow-right"></span>'],
            center: true,
            smartSpeed: 900,
            responsive: {
                0: {
                    items: 1,
                    nav: true,
                },
                601: {
                    items: 2,
                    nav: true,
                },
                768: {
                    items: 3,
                    nav: true,
                },
            },
        });
    }

    // user statistics page
    $('.user-statistics-page .user-statistics__group-carousel').owlCarousel({
        loop: true,
        autoplay: true,
        lazyLoad: true,
        autoplayHoverPause:true,
        margin: 40,
        nav: true,
        dots: false,
        navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>'],
        smartSpeed: 900,
        responsive: {
            0: {
                margin: 0,
                items: 1,
                nav: true,
            },
            576: {
                margin: 20,
                items: 2,
                nav: true,
            },
            768: {
                items: 3,
                nav: true,
                margin: 20,
            },
            992: {
                margin: 40,
            },
            1025: {
                items: 4,
                nav: true,
            },
        },
    });

    // promotion page
    $('.promotion-page .promotion-page__banner-carousel').owlCarousel({
        loop: true,
        autoplay: true,
        lazyLoad: true,
        autoplayHoverPause:true,
        smartSpeed: 900,
        nav: true,
        navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>'],
        items: 1,
    });

    // user support page
    $('.user-support-page .user-support__staff-carousel').owlCarousel({
        margin: 40,
        nav: true,
        dots: false,
        navText: ['<span class="c-arrow arrow-left"></span>', '<span class="c-arrow arrow-right"></span>'],
        smartSpeed: 900,
        lazyLoad: true,
        responsive: {
            0: {
                margin: 0,
                items: 1,
            },
            576: {
                margin: 20,
                items: 2,
            },
            768: {
                items: 3,
                margin: 20,
            },
            1200: {
                items: 4,
            },
        },
    });

    $('.classified__preview-carousel').owlCarousel({
        // loop: true,
        autoplay: true,
        lazyLoad: true,
        autoplayHoverPause:true,
        margin: 0,
        dots: true,
        nav:true,
        navText: ['<span class="c-arrow arrow-left"></span>', '<span class="c-arrow arrow-right"></span>'],
        smartSpeed: 900,
        items: 1,
    });

    $('.detail-banner__carousel').owlCarousel({
        // loop: true,
        autoplay: true,
        lazyLoad: true,
        autoplayHoverPause:true,
        margin: 0,
        dots: true,
        nav:true,
        navText: ['<span class="c-arrow arrow-left"></span>', '<span class="c-arrow arrow-right"></span>'],
        smartSpeed: 900,
        items: 1,
    });

    $('.classified-slide__carousel').owlCarousel({
        loop: true,
        // autoplay: true,
        lazyLoad: true,
        autoplayHoverPause:true,
        margin: 10,
        nav: true,
        dots: false,
        navText: ['<span class="c-arrow arrow-left arrow-red-small"></span>', '<span class="c-arrow arrow-right arrow-red-small"></span>'],
        smartSpeed: 900,
        responsive: {
            0: {
                items: 1,
            },
            601: {
                items: 2,
            },
            800: {
                items: 3,
            },
            1000: {
                items: 4,
                margin: 20,
            }
        },
    });

    $('.user-package__carousel').owlCarousel({
        loop: true,
        autoplay: true,
        lazyLoad: true,
        autoplayHoverPause:true,
        margin: 10,
        nav: true,
        dots: false,
        navText: ['<span class="c-arrow arrow-left arrow-red-small"></span>', '<span class="c-arrow arrow-right arrow-red-small"></span>'],
        smartSpeed: 900,
        responsive: {
            0: {
                items: 1,
            },
            601: {
                items: 2,
                margin: 20,
            },
            991: {
                items: 3,
                margin: 30,
            }
        },
    });

    $('.focus-property__carousel').owlCarousel({
        // loop: true,
        autoplay: true,
        margin: 10,
        lazyLoad: true,
        nav: true,
        dots: false,
        navText: ['<span class="c-arrow arrow-left"></span>', '<span class="c-arrow arrow-right"></span>'],
        smartSpeed: 900,
        responsive: {
            0: {
                items: 1,
            },
            601: {
                items: 2,
            },
            768: {
                items: 3,
                margin: 15,
            },
        },
    });

    $('.hot-area__carousel').owlCarousel({
        loop: true,
        margin: 0,
        // autoplay: true,
        nav:true,
        dots: false,
        navText: ['<span class="c-arrow arrow-left arrow-red-small"></span>', '<span class="c-arrow arrow-right arrow-red-small"></span>'],
        items: 1,
        responsive:{
            0:{
                items:1,
                nav:true,
            },
            768:{
                nav: false,
            },
        }
    });
})()
