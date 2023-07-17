(() => {
    $('body').on('click', '.category-search-form .search-footer .open-advance-menu', function (event) {
        event.preventDefault();

        if ($(this).hasClass('blur')) return

        $(this).toggleClass('is-open');
    })

    const appendOptions = (element, [...datas], value, text, label, checkDisabled) => {
        if (checkDisabled && !datas.length) {
            element.html(`<option value="selected">${label}</option>`);
            element.prop('disabled', true);
            return;
        }
        element.prop('disabled', false);
        element.empty()
        datas.unshift({
            [text]: label,
        })

        const selected = element.attr('data-selected')

        $.each(datas, (i, data) => {
            element.append($('<option>', {
                value: data[value] || '',
                text: data[text] || '',
                selected: data[value] == selected
            }));
        });
    }

    // get search form data
    const getSearchFormData = async ($parents, category = null) => {
        let selectedCategory = category || $parents.find('input[name="category"]:checked').val()

        if (!selectedCategory) return

        // loading
        $parents.find('.c-overlay-loading').addClass('loading')

        await
            $.ajax({
                url: '/search-classifieds/form-data',
                method: 'GET',
                dataType: 'JSON',
                data: {
                    category: selectedCategory
                },
                success: res => {
                    $parents.find('.c-overlay-loading').removeClass('loading')

                    if (res && res.data && res.data.searchData) {
                        let data = res.data.searchData,
                            paradigms = data.paradigms || [],
                            areas = data.areas || [],
                            prices = data.prices || [],
                            $paradigmSelect = $parents.find('select[name="paradigm"]'),
                            $priceSelect = $parents.find('select[name="price"]'),
                            $areaSelect = $parents.find('select[name="area"]');

                        // appends data to search select
                        // paradigm select
                        appendOptions($paradigmSelect, paradigms, 'group_url', 'group_name', '-- Mô hình --')
                        // price select
                        appendOptions($priceSelect, prices, 'value', 'label', '-- Mức giá --')
                        // area select
                        appendOptions($areaSelect, areas, 'value', 'label', '-- Diện tích --')

                        // replace home price
                        if (prices.length)
                            replaceHomePrice(prices)
                    }
                },
                error: () => {
                    location.reload();
                }
            });
    }

    const showAdvancedMenuItems = (category) => {
        let advanceItems = [],
            $parents = $('.category-search-form');

        // show advance menu items depend by category
        switch (category) {
            case 'nha-dat-ban':
                advanceItems = [
                    'project', 'progress', 'direction',
                    'num_bed', 'monopoly', 'broker', 'furniture'
                ]
                break;
            case 'nha-dat-cho-thue':
                advanceItems = [
                    'project', 'progress', 'direction', 'num_people',
                    'num_bed', 'monopoly', 'advance_value', 'internet',
                    'freezer', 'balcony', 'mezzanino'
                ]

                break;
            case 'du-an':
                advanceItems = [
                    'progress', 'furniture', 'direction', 'loan_support', 'ecommerce',
                    'shopping-mall', 'parking', 'gym', 'park', 'spa',
                    'pool', 'kindergarten'
                ]
                break;
            case 'can-mua':
            case 'can-thue':
                advanceItems = [
                    'project', 'progress', 'direction', 'num_bed', // 'furniture'
                    'monopoly', 'internet', 'balcony', 'mezzanino', 'freezer'
                ]
                break;
            default:
                break;
        }

        $parents.find('.advance-search-box .advance-search__item').addClass('d-none')

        advanceItems.forEach(item => {
            $parents.find(`.advance-search-box .advance-search__item.advance-search__${item}`).removeClass('d-none')
        })

        // should reset advance input field (remove data & disable)
    }

    const getParadigmSearchData = ($parents, paradigm) => {
        let selectedCategory = $parents.find('input[name="category"]:checked').val()
        if (!selectedCategory) return

        // should change
        if (!paradigm) {
            // disable progress & furniture select
            let $furnitureSelect = $categorySearchForm.find('.advance-search-box select[name="furniture"]'),
                $progressSelect = $categorySearchForm.find('.advance-search-box select[name="progress"]');

            appendOptions($furnitureSelect, [], 'id', 'furniture_name', '-- Nội thất --', true)
            appendOptions($progressSelect, [], 'id', 'progress_name', '-- Tình trạng --', true)
            return
        }

        let $numBedSelect = $categorySearchForm.find('.advance-search-box select[name="num_bed"]');

        // disable numbed select
        if (!paradigm || paradigm == 'cho-thue-dat' || paradigm == 'cho-thue-mat-bang') {
            $numBedSelect.val('').trigger('change');
            $numBedSelect.prop('disabled', true);
        } else {
            $numBedSelect.prop('disabled', false);
        }
        // end should change

        $('.category-search-form .c-overlay-loading').addClass('loading')

        $.ajax({
            url: '/search-classifieds/paradigm-data',
            method: 'GET',
            dataType: 'JSON',
            data: {
                paradigm: paradigm,
                category: selectedCategory
            },
            success: res => {
                $('.category-search-form .c-overlay-loading').removeClass('loading')
                let $parents = $('.category-search-form')

                if (res && res.data && res.data.paradigmData) {
                    let data = res.data.paradigmData,
                        furnitures = data.furnitures || [],
                        progresses = data.progresses || [],
                        $furnitureSelect = $parents.find('.advance-search-box select[name="furniture"]'),
                        $progressSelect = $parents.find('.advance-search-box select[name="progress"]');

                    // appends data to search advance
                    // furniture select
                    appendOptions($furnitureSelect, furnitures, 'id', 'furniture_name', '-- Nội thất --', true)
                    // progress select
                    appendOptions($progressSelect, progresses, 'id', 'progress_name', '-- Tình trạng --', true)
                }
            },
            error: () => {
                // location.reload();
            }
        });
    }

    const checkInputData = () => {
        let url = new URL(window.location.href),
            params = new URLSearchParams(url.search),
            parentGroupPath = url.pathname.split('/')[1],
            pathParadigm = url.pathname.split('/')[2],
            selectFields = [
                'paradigm',
                'province_id',
                'price',
                'area',
            ],
            validParentGroups = [
                'nha-dat-ban', 'nha-dat-cho-thue', 'du-an',
                'can-mua-can-thue'
            ];

        // group child auto select paradigm
        if (validParentGroups.includes(parentGroupPath) && pathParadigm)
            $(`.category-search-form select[name="paradigm"]`).val(pathParadigm).trigger('change')

        selectFields.forEach(select => {
            let data = params.get(select)

            if (data) {
                $(`.category-search-form select[name="${select}"]`).val(data).trigger('change')
            }
        })
    }

    window.onload = function () {
        checkInputData()
    }

    let $categorySearchForm = $('.category-search-form')

    if ($categorySearchForm && $categorySearchForm.length) {
        // get selected category
        let selectedCategory = $categorySearchForm.find('.change-category-box input[name="category"]:checked').val()
        getSearchFormData($categorySearchForm)
        showAdvancedMenuItems(selectedCategory)
    }

    $('.category-search-form .change-category-box input[name="category"]').on('change', function () {
        let $parents = $(this).parents('.category-search-form'),
            category = $parents.find('input[name="category"]:checked').val()

        // remove blur search footer
        $parents.find('.search-footer .btn-search').removeClass('blur')
        $parents.find('.search-footer .open-advance-menu').removeClass('blur')

        getSearchFormData($categorySearchForm)
        showAdvancedMenuItems(category)
    })

    $('body').on('change', '.category-search-form .select-field-box select[name="paradigm"]', function () {
        let paradigm = $(this).val()

        getParadigmSearchData($categorySearchForm, paradigm)
    })

    // old should change
    $('.category-search-form .search-form-box').on('submit', function (e) {
        e.preventDefault();

        let cate_child = '',
            $parents = $('.category-search-form'),
            category = $parents.find('.change-category-box input[name="category"]:checked').val(),
            url = `/search-classified/${category}`,
            pathname = window.location.pathname;

        // check fix phần sort cho /vi-tri/nha-dat-{ha-noi} hiện tại return ko search
        if (!category) return

        if (category == 'du-an') {
            url = `/search-project`,
                cate_child = $('.category-search-form select[name="paradigm"]').select2('val');
        }
        else if (category == 'nha-dat-ban') {
            cate_child = $('.category-search-form select[name="paradigm"]').select2('val');
        }
        else if (category == 'nha-dat-cho-thue') {
            cate_child = $('.category-search-form select[name="paradigm"]').select2('val');
        }
        else if (category == 'can-mua') {
            cate_child = $('.category-search-form select[name="paradigm"]').select2('val');
            category = cate_child ? 'can-mua-can-thue' : 'can-mua-can-thue/' + category;
        }
        else if (category == 'can-thue') {
            cate_child = $('.category-search-form select[name="paradigm"]').select2('val');
            category = cate_child ? 'can-mua-can-thue' : 'can-mua-can-thue/' + category;
        }

        if (pathname == '/can-mua-can-thue') {
            category = 'can-mua-can-thue';
            url = `/search-classified/${category}`
        }

        var data = $(this).serialize();
        if (sort_param) {
            data = data + '&sort=' + sort_param;
        }

        var link = '';
        link = category;
        if (cate_child) {
            link = link + '/' + cate_child;
            url = url + '/' + cate_child;
        }
        link = link + '?' + data;

        getSearchResults(url, data, link);
    });

    const getSearchResults = (url, data, link = null, method = 'GET') => {
        if (!url || !data || !link) return
        $('.c-overlay-loading:not(.inner)').addClass('loading')

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: res => {
                $('.c-overlay-loading:not(.inner)').removeClass('loading')

                if (res && res.data) {
                    const html = res.data.html
                    const banner_image = res.data.banner_image

                    if (html)
                        $('#category-search-results-section').html(html);

                    if (banner_image) {
                        $('.list-classified-search-box').find('.banner-slide-width .search-image').css({
                            'background-image': `url(${banner_image})`
                        })
                    }

                    // lazy loading
                    loadingImage()
                }

                if (link)
                    window.history.pushState("", "", window.location.origin + '/' + link);

                resetAutoLoadAction()
            },
            error: () => {
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
            }
        });
    }

    let sort_param;

    $('body').on('click', '.js-classified-sort-list .js-search-classified-sort', function (e) {
        e.preventDefault();
        let _this = $(this),
            $resultBox = _this.parents('.category-search-results-box');

        sort_param = _this.attr('data-value');
        $('.js-classified-sort-list .classified-sort-list__text').text(_this.text())

        const isSearchNear = $resultBox.find('.js-search-near-individual').hasClass('d-none') ? true : false;

        if (isSearchNear) {
            let _this = $(this),
            $parents = _this.parents('.js-need-load-individual');

            $parents.find('.js-search-near-individual').addClass('d-none')
            $parents.find('.js-disable-search-near-individual').removeClass('d-none')

            resetAutoLoadAction()
            classifiedPage = 0;
            $parents.find('.js-lists-box').empty()
            $parents.find('.pagination .paged').addClass('d-none')

            loadMoreItems()
        } else {
            $categorySearchForm.find('.search-form-box').trigger('submit');
        }
    });
    // end old should change

    // home search box
    const replaceHomePrice = homePrices => {
        // replace home price
        let $parents = $('.home-banner-top-section'),
            desktopPrices = homePrices.slice(0, 5);

        if ($parents && $parents.length) {
            $($parents.find('.choose-price-box .price-list')).empty()
            $.each(desktopPrices, function (i, homePrice) {
                $($parents.find('.choose-price-box .price-list')).append(`
                    <li class="item js-choose-price-btn c-mx-10 mb-2" data-value="${homePrice.value}">
                        <span class="item-content">
                            ${homePrice.sub_label || homePrice.label}
                        </span>
                    </li>
                `);
            });
        }

        // replace home price mobile
        let $mobileParents = $('.banner-top-mobile-section'),
            mobilePrices = homePrices.slice(0, 4);
        if ($mobileParents && $mobileParents.length) {
            $($mobileParents.find('.choose-price-box .price-list')).empty()
            $.each(mobilePrices, function (i, homePrice) {
                $($mobileParents.find('.choose-price-box .price-list')).append(`
                    <span class="item js-choose-price-btn" data-value="${homePrice.value}">
                        ${homePrice.mobile_label || homePrice.sub_label || homePrice.label}
                    </span>
                `);
            });
        }
    }

    // desktop
    $('.home-banner-top-section .home-search-form-box .category-box input[name="category"]').on('change', function (e) {
        let $parents = $(this).parents('.home-banner-top-section');
        getSearchFormData($parents)
    });

    // mobile
    $('.banner-top-mobile-section .home-search-mobile-form select[name="category"]').on('change', function (e) {
        let $parents = $(this).parents('.banner-top-mobile-section'),
            category = $parents.find('select[name="category"]').val();

        getSearchFormData($parents, category)
    });

    $('.home-banner-top-section .banner-top-inner .home-search-form-box .home-search-inner .category-box .radio-with-button input[type=radio]').on('change', function () {
        if ($(this).is(':checked')) {
            $('.home-banner-top-section .advance-button__text').removeClass('d-none');
        }
    });

    // home open advanced search
    $('.home-banner-top-section .open-advanced-button').on('click', function (e) {
        // $('.home-banner-top-section .home-advanced-box').slideToggle( 'fast' );
        if ($('.home-banner-top-section .banner-top-inner .home-search-form-box .home-search-inner .category-box .radio-with-button input[type=radio]').is(':checked')) {
            $('.home-banner-top-section .home-advanced-box').slideToggle('fast');
        }
    })

    // chon muc gia home
    $('body').on('click', '.js-home-search-box .js-choose-price-btn', function (e) {
        let _this = $(this),
            price = _this.data('value'),
            $parents = _this.parents('.js-home-search-box'),
            selectPrice = $parents.find('[name="price"]').val();

        _this.addClass('active').siblings().removeClass('active')

        if (price != selectPrice)
            $parents.find('select[name="price"]').val(price).trigger('change')
    })

    $('body').on('change', '.js-home-search-box select[name="price"]', function (e) {
        let _this = $(this),
            $parents = _this.parents('.js-home-search-box'),
            price = _this.val(),
            choosePrice = $parents.find('.js-choose-price-btn.active').data('value')

        if (price != choosePrice) {
            $parents.find('.item').removeClass('active')
            $parents.find(`.item[data-value="${price}"]`).trigger('click')
        }
    })

    $('.js-home-search-form').on('click', 'input, select, .select2-container, .js-home-search__footer', function () {
        let _this = $(this),
            $parents = _this.parents('.js-home-search-box'),
            category = $parents.find('[name="category"]:checked').val() || $parents.find('[name="category"]').val();

        if (!category) {
            $parents.find('[name="category"]').parents('.form-group').addClass('is-flicking')
            setTimeout(function () {
                $parents.find('[name="category"]').parents('.form-group').removeClass('is-flicking')
            }, 800)
        }
    })

    $('.js-home-search-form .js-search-btn').on('click', function (e) {
        let _this = $(this),
            $parents = _this.parents('.js-home-search-box'),
            $form = $parents.find('.js-home-search-form'),
            category = $parents.find('[name="category"]:checked').val() || $parents.find('[name="category"]').val();

        if (!category) return
        $form.attr('action', category).trigger('submit')
    })
    // end home search box

    // my location
    $('body').on('click', '.js-search-near', function (event) {
        event.preventDefault;
        $('.search-location input[name="accept_location"]').val(1);
        getMyLocationToAddress()
    })

    $('body').on('click', '.js-select-near', function (event) {
        event.preventDefault;
        setLocationToAddress()
    })

    $('body').on('click', '.js-disable-search-near', function (event) {
        event.preventDefault;
        let url = $(this).data('url');
        if (!url) return

        window.location.href = url;
    })

    let individualPages = {},
        individualLoading = {};

    // get geolocation | not use now
    const getDistrictByName = districtName => {
        return new Promise((resolve, reject) => {
            return $.ajax({
                url: '/params/ajax-get-district-name',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    district_name: districtName.trim(),
                },
                success: res => resolve(res.district),
                error: err => reject(err)
            });
        })
    }

    const getProvinceByName = provinceName => {
        return new Promise((resolve, reject) => {
            return $.ajax({
                url: '/params/ajax-get-province-name',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    province_name: provinceName.trim(),
                },
                success: res => resolve(res.province),
                error: err => reject(err)
            });
        })
    }

    const setGeolocation = (...[provinceId, districtId]) => {
        return new Promise((resolve, reject) => {
            return $.ajax({
                url: '/params/set-geolocation',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    province_id: provinceId,
                    district_id: districtId,
                },
                success: res => resolve(res),
                error: err => reject(err)
            });
        })
    }

    const callbackLocationToAddress = async result => {
        let districtId, provinceId;

        await getDistrictByName(result.district_name)
            .then(district => {
                if (district && district.id)
                    districtId = district.id
            })

        if (!districtId) {
            await getProvinceByName(result.district_name)
                .then(province => {
                    if (province && province.id)
                        provinceId = province.id
                })
        }

        if (districtId || provinceId) {
            await setGeolocation([provinceId, districtId])
        }
    }

    // const getGeolocation = () => {
    //     if (navigator.geolocation) {
    //         navigator.geolocation.getCurrentPosition(
    //             function (position) {
    //                 const pos = {
    //                     lat: position.coords.latitude,
    //                     lng: position.coords.longitude,
    //                 };
    //                 let loc = new google.maps.LatLng(pos.lat, pos.lng)
    //                 markerToAddress({ location: loc }).then((r) => callbackLocationToAddress(r));
    //             },
    //             function () {
    //                 // alert('Không hỗ trợ định vị');
    //             }
    //         );
    //     } else {
    //         // alert('Không hỗ trợ định vị');
    //     }
    // }
    // end get geolocation

    // search near individual
    $('body').on('click', '.js-need-load-individual:not(.category-search-results-box) .js-search-near-individual', function (event) {
        event.preventDefault;

        let _this = $(this),
            $parents = _this.parents('.js-need-load-individual'),
            url = $parents.data('individual-url');
        $parents.find('.js-search-near-individual').addClass('d-none')
        $parents.find('.js-disable-search-near-individual').removeClass('d-none')
        $parents.find('.js-individual-list').empty()

        if (!url) return
        individualPages[url] = 1
        loadNearIndividual(_this)
    })

    // disable search near individual
    $('body').on('click', '.js-need-load-individual:not(.category-search-results-box) .js-disable-search-near-individual', function (event) {
        event.preventDefault;

        let _this = $(this),
            $parents = _this.parents('.js-need-load-individual'),
            url = $parents.data('individual-url');
        $parents.find('.js-disable-search-near-individual').addClass('d-none')
        $parents.find('.js-search-near-individual').removeClass('d-none')
        $parents.find('.js-individual-list').empty()

        if (!url) return
        individualPages[url] = 1
        loadNearIndividual(_this)
    })

    // load more individual
    $('body').on('click', '.js-need-load-individual .js-individual-load-more', function (e) {
        e.preventDefault();

        let _this = $(this),
            $parents = _this.parents('.js-need-load-individual'),
            url = $parents.data('individual-url');

        if (!url) return
        individualPages[url]++
        loadNearIndividual(_this)
    })

    const loadNearIndividual = ele => {
        let $parents = ele.parents('.js-need-load-individual'),
            url = $parents.data('individual-url');

        if (!url || individualLoading[url]) return

        let acceptNear = $parents.find('.js-search-near-individual').hasClass('d-none') ? true : false;

        individualLoading[url] = true
        $parents.find('.c-overlay-loading').addClass('loading')

        $.ajax({
            url: url,
            type: 'GET',
            data: {
                page: individualPages[url],
                accept_near: acceptNear ? 1 : 0
            },
            success: res => {
                // loading
                individualLoading[url] = false
                $parents.find('.c-overlay-loading').removeClass('loading')

                if (res && res.data) {
                    const moreEstateHtml = res.data.html;

                    if (moreEstateHtml) {
                        $parents.find('.js-individual-list').append(moreEstateHtml)
                        loadingImage()
                    }

                    if (res.data.onLastPage) {
                        $parents.find('.list-more .js-individual-load-more').addClass('d-none')
                    } else {
                        $parents.find('.list-more .js-individual-load-more').removeClass('d-none')
                    }
                }
            },
            error: () => {
                // loading
                individualLoading[url] = false
                $parents.find('.c-overlay-loading').removeClass('loading')
                $parents.find('.list-more .js-individual-load-more').addClass('d-none')
            }
        })
    }
    // end my location

    // load more category item
    let classifiedPage = parseInt($('#category-search-results-section .category-search-results-box').data('current-page')) || 1,
        moreClassifiedsLoading = false,
        groupType = $('.category-search-results-box').data('group-type'),
        firstPage = classifiedPage,
        resetCount = 10; // by default auto reset auto load after loaded 10 pages

    // category search near individual
    $('body').on('click', '.js-need-load-individual.category-search-results-box .js-search-near-individual', function (event) {
        event.preventDefault;

        let _this = $(this),
            $parents = _this.parents('.js-need-load-individual');

        $parents.find('.js-search-near-individual').addClass('d-none')
        $parents.find('.js-disable-search-near-individual').removeClass('d-none')

        resetAutoLoadAction()
        classifiedPage = 0;
        $parents.find('.js-lists-box').empty()
        $parents.find('.pagination .paged').addClass('d-none')

        loadMoreItems()
    })

    // category disable search near individual
    $('body').on('click', '.js-need-load-individual.category-search-results-box .js-disable-search-near-individual', function (event) {
        event.preventDefault;

        let _this = $(this),
            $parents = _this.parents('.js-need-load-individual');

        $parents.find('.js-search-near-individual').removeClass('d-none')
        $parents.find('.js-disable-search-near-individual').addClass('d-none')

        resetAutoLoadAction()
        classifiedPage--;
        $parents.find('.js-lists-box').empty()
        $parents.find('.pagination .paged').removeClass('d-none')

        loadMoreItems()
    })

    const resetAutoLoadAction = () => {
        groupType = $('.category-search-results-box').data('group-type')
        let $autoLoadInput = $(`.list-search-category-box .js-load-more-item`);

        classifiedPage = parseInt($('#category-search-results-section .category-search-results-box').data('current-page')) || 1
        firstPage = classifiedPage
        moreClassifiedsLoading = false
        $autoLoadInput.prop('checked', false)
        $autoLoadInput.parents('.auto_load').removeClass('on')
        document.removeEventListener('scroll', scrollAutoLoad)
    }

    const autoLoadMoreItems = (url, $listsElement) => {
        if (moreClassifiedsLoading || !$listsElement || !$listsElement.length) return

        // auto load resetCount pages need click again
        if (classifiedPage != firstPage && (classifiedPage - firstPage) % resetCount == 0)
            resetAutoLoadAction()

        classifiedPage++

        // loading
        moreClassifiedsLoading = true
        $listsElement.parent().find('.c-overlay-loading').addClass('loading')
        let acceptNear = $listsElement.parents('.js-need-load-individual').find('.js-search-near-individual').hasClass('d-none') ? true : false;

        $.ajax({
            url: url,
            type: 'GET',
            data: {
                page: classifiedPage,
                accept_near: acceptNear ? 1 : 0,
                load_individual: true
            },
            success: res => {
                moreClassifiedsLoading = false
                $listsElement.parent().find('.c-overlay-loading').removeClass('loading')

                if (res && res.data) {
                    const moreClassifiedHtml = res.data.html;

                    if (moreClassifiedHtml) {
                        $listsElement.append(moreClassifiedHtml)
                        loadingImage()
                    }

                    if (res.data.onLastPage) {
                        $listsElement.parents('.js-parents-loadmore').find('.auto_load').addClass('d-none')
                        resetAutoLoadAction();
                    } else {
                        $listsElement.parents('.js-parents-loadmore').find('.auto_load').removeClass('d-none')
                    }
                }
            },
            error: () => {}
        })
    }

    const loadMoreItems = () => {
        let params = $('.category-search-form .search-form-box').serialize();
        // params = window.location.search

        if (sort_param) {
            params += `&sort=${sort_param}`
        }

        const pathName = window.location.pathname,
            moreUrlPath = $('.category-search-results-box').data('group-type') == 'project' ? '/more-project-item' : '/more-item',
            moreUrl = moreUrlPath + pathName + '?' + params;

        autoLoadMoreItems(moreUrl, $('.list-search-category-box .js-lists-box'))
    }

    $('body').on('change', `.list-search-category-box #autoload`, function () {
        $(this).parents('.auto_load').toggleClass('on');

        if ($(this).prop('checked')) {
            document.addEventListener('scroll', scrollAutoLoad)
            loadMoreItems()
        } else {
            document.removeEventListener('scroll', scrollAutoLoad)
        }
    })

    const scrollAutoLoad = () => {
        let item = document.getElementsByClassName(`${groupType}-item-box`)[0];

        if (!item) return

        let style = item.currentStyle || window.getComputedStyle(item),
            itemMarginBottom = parseInt(style.marginBottom),
            itemPerRow = 1

        if (groupType == 'project') {
            if ($(window).width() >= 768) {
                itemPerRow = 3
            } else if ($(window).width() >= 576) {
                itemPerRow = 2
            }
        }

        const listRows = Math.ceil($(`.${groupType}-item-box`).length / itemPerRow)

        const needLoadMore = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body
            .scrollTop) +
            window.innerHeight > $('.js-lists-box').offset().top + ($(`.${groupType}-item-box`).outerHeight() +
                itemMarginBottom) * listRows

        if (needLoadMore)
            loadMoreItems()
    }
    // end load more item

    // click category form input when not selected category
    $('.category-search-form .search-form-box ').on('click', 'input, select, .select2-container, .btn-search, .open-advance-menu, .icon-search', function () {
        // e.preventDefault()
        // e.stopPropagation()
        let _this = $(this),
            $parents = _this.parents('.category-search-form'),
            category = $parents.find('input[name="category"]:checked').val();

        if (!category) {
            $parents.find('.change-category-box').addClass('is-flicking')
            setTimeout(function () {
                $parents.find('.change-category-box').removeClass('is-flicking')
            }, 800)
        }
    })
})()
