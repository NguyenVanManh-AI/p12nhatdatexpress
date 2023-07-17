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
               footerRectTop = $('#footer-cntr')[0].getBoundingClientRect().top;

          // show header fixed
          if (currentOffset > __lastScrollTop && __fixedHeaderOffsetTop < position && footerRectTop > __fixedHeaderHeight) {
               $homeHeader.addClass('in')
               $('body').css("margin-top", `${__headerHeight}px`);
          } else {
               $homeHeader.removeClass('in')
               $('body').css("margin-top", '');
          }

          __lastScrollTop = currentOffset <= 0 ? 0 : currentOffset;
          // end show header fixed
     });
})()
