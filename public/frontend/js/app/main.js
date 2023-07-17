(() => {
    // scroll down fixed header
    let __lastScrollTop = 0,
        __fixedHeaderOffsetTop = $('#home-header-cntr .header-bottom').offset().top,
        __fixedHeaderHeight = 45,
        __headerHeight = $('#home-header-cntr').height();

    document.addEventListener('scroll', (e) => {
        let _this = $(this),
            $homeHeader = $('#home-header-cntr'),
            position = _this.scrollTop(),
            currentOffset = window.pageYOffset || document.documentElement.scrollTop,
            footerRectTop = $('#footer-cntr')[0].getBoundingClientRect().top,
            $fixedUrBannerFilter = $('#urban-fixed-filter'),
            isHasUrBannerFilter = $fixedUrBannerFilter && $fixedUrBannerFilter.length;

        // project detail scroll show link description
        if (isHasUrBannerFilter) {
            let $urbanFilter = $('#urban-filter')[0],
                distanceFilterTop = currentOffset + $urbanFilter.getBoundingClientRect().top;

            distanceFilterTop < position
                ? $fixedUrBannerFilter.addClass('in')
                : $fixedUrBannerFilter.removeClass('in')
        }

        // show header fixed
        if (currentOffset > __lastScrollTop && __fixedHeaderOffsetTop < position && footerRectTop > __fixedHeaderHeight && !isHasUrBannerFilter) {
            $homeHeader.addClass('in')
            $('body').css("margin-top", `${__headerHeight}px`);
        } else {
            $homeHeader.removeClass('in')
            $('body').css("margin-top", '');
        }

        __lastScrollTop = currentOffset <= 0 ? 0 : currentOffset;
        // end show header fixed

        let footerOffset = $('#footer-cntr').offset().top,
            bannerHeight = 600,
            footerPos = footerOffset - bannerHeight - 25;

        // banner fixed // old should check change
        if(position != 0) {
            $('.banner-qc').addClass('fixbox');
        } else {
            $('.banner-qc').removeClass('fixbox');
            $('.banner-qc').css({'top' :  '' , 'position' : ''});
        }

        if(position >= footerPos) {
            const expertPos = $homeHeader.hasClass('in') ? footerPos - __headerHeight : footerPos

            $('.banner-qc.fixbox').css({'top' : expertPos + 'px' , 'position' : 'absolute'});
        } else {
            $('.banner-qc.fixbox').css({'top' : '0' , 'position' : 'fixed'});
        }
        // end banner fixed
    });
})()
