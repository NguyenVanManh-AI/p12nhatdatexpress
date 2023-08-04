(() => {
    // global js load more
    let __$loadMoreBox = $('.js-load-more__box'),
        __$loadMoreList = __$loadMoreBox.find('.js-load-more__list'),
        __firstPage = __$loadMoreBox.attr('data-first-page') || 1,
        __itemClass = __$loadMoreBox.attr('data-item-page'),
        __itemsPerRow = __$loadMoreBox.attr('data-items-per-row') || 1,
        __currentPage = __firstPage,
        __itemsPerPage = __$loadMoreBox.attr('data-items-per-page'),
        __moreUrl = __$loadMoreBox.attr('data-more-url'),
        __isMoreLoading = false,
        __resetCount = 10;

    __$loadMoreBox.on('change', '.js-load-more__toggle-auto', function (event) {
        event.preventDefault;

        $(this).parents('.js-load-more__auto-button').toggleClass('on');

        if ($(this).prop('checked')) {
            document.addEventListener('scroll', scrollAutoLoad)
            loadMoreItems()
        } else {
            document.removeEventListener('scroll', scrollAutoLoad)
        }
    })

    const scrollAutoLoad = () => {
        let item = __$loadMoreList.find(__itemClass)[0],
            $itemElement = __$loadMoreList.find(__itemClass);
        if (!item) return

        let style = item.currentStyle || window.getComputedStyle(item),
            itemMarginBottom = parseInt(style.marginBottom);

        if (__itemsPerRow == 3 && $(window).width() >= 576 && $(window).width() < 992) {
            __itemsPerRow = 2
        } else if ($(window).width() < 576) {
            __itemsPerRow = 1
        }

        const listRows = Math.ceil($itemElement.length / __itemsPerRow)

        const needLoadMore = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body
            .scrollTop) +
            window.innerHeight > __$loadMoreList.offset().top + ($itemElement.outerHeight() +
                itemMarginBottom) * listRows

        if (needLoadMore)
            loadMoreItems()
    }

    const loadMoreItems = () => {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.delete('page');
        const moreUrl = __moreUrl + (urlParams.toString() ? `?${urlParams.toString()}` : '');

        autoLoadMoreItems(moreUrl)
    }

    const resetAutoLoadAction = () => {
        __isMoreLoading = false
        __$loadMoreBox.find('.js-load-more__toggle-auto').prop('checked', false).trigger('change')
        __firstPage = __currentPage + 1
    }

    const autoLoadMoreItems = url => {
        if (__isMoreLoading || !__$loadMoreList || !__$loadMoreList.length) return

        if (__currentPage + 1 != __firstPage && (__currentPage - __firstPage + 1) % __resetCount == 0) {
            resetAutoLoadAction()
            return
        }

        __currentPage++

        // loading
        __isMoreLoading = true
        __$loadMoreList.parent().find('.c-overlay-loading').addClass('loading')

        $.ajax({
            url: url,
            type: 'GET',
            data: {
                page: __currentPage,
                items_per_page: __itemsPerPage
            },
            success: res => {
                __isMoreLoading = false
                __$loadMoreList.parent().find('.c-overlay-loading').removeClass('loading')

                if (res && res.data) {
                    const html = res.data.html;

                    if (html)
                        __$loadMoreList.append(html)

                    if (res.data.meta && res.data.meta.onLastPage) {
                        __$loadMoreBox.find('.js-load-more__auto-button').addClass('d-none')
                        resetAutoLoadAction();
                    } else {
                        __$loadMoreBox.find('.js-load-more__auto-button').removeClass('d-none')
                    }
                }
            },
            error: () => { }
        })
    }
})()
