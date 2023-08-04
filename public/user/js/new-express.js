$(document).ready(function () {
    let $parents = $('.user-create-express-page .user-express-form'),
        bannerData = null,
        oldCategory= $parents.find('select[name="category"]').attr('data-selected'),
        oldParadigm = $parents.find('select[name="paradigm"]').attr('data-selected');

    getParadigm = (paradigmId = null) => {
        let $paradigm = $parents.find('select[name="paradigm"]'),
            categoryId = $parents.find('select[name="category"]').val();

        $paradigm.empty();
        $paradigm.append("<option selected value=''>Chọn mô hình</option>");
        $.ajax({
            url: '/params/ajax-get-child-group',
            type: "GET",
            dataType: "json",
            data: {
                parent_id: categoryId
            },
            success: data => {
                $.each(data['child_group'], function (index, value) {
                    let selected = paradigmId == value.id ? 'selected' : '',
                        paradigmValue = "<option value='" + value.id + "' " + selected + " >" + value.group_name + "</option>";

                    $paradigm.append(paradigmValue);

                });

                if (data['child_group'] && paradigmId)
                    $paradigm.trigger('change')
            }
        });
    }

    processPositionBanner = () => {
        let banner_position = $parents.find('input[name="banner_position"]:checked').val(),
            banner_group = $parents.find('input[name="banner_group"]:checked').val(),
            banner_type = $parents.find('input[name="banner_type"]:checked').val(),
            paradigm_id = $parents.find('select[name="paradigm"]').val(),
            $noteBox = $parents.find('.note-box'),
            $noteContentBox = $parents.find('.note-box .note-content');

        if (!banner_group || !banner_position || !banner_type) return

        $noteBox.find('.c-overlay-loading').addClass('loading')

        $.ajax({
            url: '/params/banner-group-price-data',
            type: 'GET',
            data: {
                'banner_position': banner_position,
                'banner_group': banner_group,
                'banner_type':  banner_type,
                'paradigm_id': paradigm_id
            },
            success: res => {
                $noteBox.find('.c-overlay-loading').removeClass('loading')
                $noteContentBox.empty()

                if (res && res.data && res.data.bannerData) {
                    // add note
                    bannerData = res.data.bannerData

                    let _html = `<p class="fs-15">Vị trí quảng cáo
                            <strong class="text-danger">${bannerData.banner_name || ''}</strong>
                            có kích thước ${bannerData.banner_width || ''}x${bannerData.banner_height || ''},
                            phí hiển thị mỗi ngày <strong class="text-success">${bannerData.banner_coin_price || ''} Express Coins</strong>
                        </p>`;

                    $noteContentBox.html(_html)
                }
            },
            error: () => {
                $noteBox.find('.c-overlay-loading').removeClass('loading')
            }
        })
    }

    checkedBanner = () => {
        let banner_position = $parents.find('input[name="banner_position"]:checked').val();

        $parents.find('.preview-box .banner-image').removeClass('active')
        $parents.find(`.preview-box .banner-image[data-checked="${banner_position}"]`).addClass('active')
    }

    checkedPosition = () => {
        let banner_type = $parents.find('input[name="banner_type"]:checked').val()
        $parents.find('.device-show-position').addClass('d-none')

        if (banner_type == 'D') {
            $parents.find('.desktop-show').removeClass('d-none')
            $parents.find('input[name="banner_position"][value="L"]').prop('checked', true).trigger('change')
        } else {
            $parents.find('.mobile-show').removeClass('d-none')
            $parents.find('input[name="banner_position"][value="C"').prop('checked', true).trigger('change')
        }
    }

    checkedParadigm = () => {
        let banner_group = $parents.find('input[name="banner_group"]:checked').val(),
            $paradigmBox = $parents.find('.select-paradigm-box');

        banner_group == 'H' ? $paradigmBox.addClass('d-none') : $paradigmBox.removeClass('d-none')
    }

    processPositionBanner()

    $parents.find('input[name="banner_type"]').on('change', function () {
        processPositionBanner()
        checkedPosition()
        checkedBanner()
    });

    $parents.find('input[name="banner_position"]').on('change', function () {
        processPositionBanner()
        checkedBanner()
    });

    $parents.find('input[name="banner_group"]').on('change', function () {
        processPositionBanner()
        checkedParadigm()
    });

    $parents.find('select[name="paradigm"]').on('change', function () {
        processPositionBanner()
    });

    $parents.find('select[name="category"]').on('change', function () {
        getParadigm()
        processPositionBanner()
    });

    if (oldCategory) {
        getParadigm(oldParadigm)
    }

    nextToStep2 = () => {
        let banner_group = $parents.find('input[name="banner_group"]:checked').val(),
            category = $parents.find('select[name=category]').val();

        if (banner_group == 'C' && !category) {
            toastr.error('Vui lòng chọn chuyên mục!');
            return;
        }

        $('.js-view-upload-image-box').fancybox()

        $parents.find('input[name="banner_date"]').daterangepicker(
            {
                opens: 'right',
                alwaysShowCalendars: false,
                minDate: new Date(),
                showDropdowns: true,
                parentEl: '.display-time .display-time__input-calendar',
                locale: {
                    applyLabel: 'Đồng ý',
                    cancelLabel: 'Hủy bỏ',
                    daysOfWeek: [
                        'CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'
                    ],
                    monthNames: [
                        'Tháng Một',
                        'Tháng Hai',
                        'Tháng Ba',
                        'Tháng Bốn',
                        'Tháng Năm',
                        'Tháng Sáu',
                        'Tháng Bảy',
                        'Tháng Tám',
                        'Tháng Chín',
                        'Tháng Mười',
                        'Tháng Mười Một',
                        'Tháng Mười Hai',
                    ],
                },
            },
        ).trigger('click');

        // should change if have better solution
        // disabled close when click outside, cancel, apply button
        $parents.find('input[name="banner_date"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).trigger('click');
        });
        $parents.find('input[name="banner_date"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).trigger('click');
        });
        daterangepicker.prototype.outsideClick = function(e) {}
        // end should change

        goToStep(2)
    }

    //upload banner image
    $('.button-upload').on('click', function () {
        $('input[name=select_banner_image]').trigger('click');
    });

    $('input[name=select_banner_image]').on('change', function () {
        let file = this.files[0];

        if (file) {
            let reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function () {
                let _html = `<img class="object-contain" src="${reader.result}" alt="">
                            <input type="text" name="banner_image" value="${reader.result}" hidden>`;

                $parents.find('.upload-images .upload-image').html(_html)
                $parents.find('.upload-preview-box .js-view-upload-image-box').attr('href', reader.result)
                $parents.find('.upload-preview-box .upload-preview__action').removeClass('d-none')
            }
        }
    });

    $('body').on('click', '.upload-preview-box .js-remove-image', function () {
        let $previewBox = $parents.find('.upload-preview-box');

        // reset input value
        $('input[name=select_banner_image]').val('')
        $previewBox.find('.upload-image').empty()
        $previewBox.find('.upload-preview__action').addClass('d-none')
    })

    nextToStep3 = () => {
        //check da upload banner image
        let banner_image = $parents.find('input[name=banner_image]').val();
        if (!banner_image) {
            toastr.error('Vui lòng tải ảnh banner!')
            return;
        }

        //chon ngay dang banner
        let banner_date = $('input[name=banner_date]').val();
        if(!banner_date) {
            toastr.error('Vui lòng chọn ngày đăng banner!')
            return;
        }

        let bannerGroup = $parents.find('input[name="banner_group"]').data('text'),
            dCategory = $parents.find('select[name="category"] option:selected').val() ? ' - ' + $parents.find('select[name="category"] option:selected').text() : '',
            dParadigm = $parents.find('select[name="paradigm"] option:selected').val() ? ' - ' + $parents.find('select[name="paradigm"] option:selected').text() : '',
            paradigmText = bannerGroup + dCategory + dParadigm,
            dTime = $parents.find('input[name=banner_date]').val(),
            dDevice = $parents.find('input[name="banner_type"]').data('text'),
            dPosition = bannerData ? bannerData.banner_name : '',
            dPrice = bannerData ? bannerData.banner_coin_price : 0,
            // calculator number of days
            timeArr = dTime.split('-'),
            diffInDays = (new Date(timeArr[1].trim()) - new Date(timeArr[0].trim())) / (1000 * 60 * 60 * 24),
            totalPrice = parseInt(dPrice) * (parseInt(diffInDays) + 1);

        $('span#display-position').text(dPosition)
        $('span#display-paradigm').text(paradigmText)
        $('span#display-time').text(dTime)
        $('span#display-days').text(diffInDays)
        $('span#display-device').text(dDevice)
        $('span#display-coin').text(totalPrice)
        // need calculator total amount | price - voucher..
        $('span#display-total-amount').text(totalPrice)

        goToStep(3)
    }

    goToStep = step => {
        $parents.find('.step-section').addClass('d-none');
        $parents.find(`.step-section.step-${step}`).removeClass('d-none');
    }

    $parents.find('.btn-payout').on('click', function (e) {
        e.preventDefault();

        let dTime = $parents.find('input[name=banner_date]').val()
            timeArr = dTime.split('-'),
            datas = {
                date_from: timeArr[0],
                date_to: timeArr[1],
            };

        Object.entries(datas).forEach(([key, data]) => {
            let input = $('<input>')
                .attr('type', 'hidden')
                .attr('name', key)
                .val(data)

            $parents.append(input);
        });

        $parents.trigger('submit')
    })
})
