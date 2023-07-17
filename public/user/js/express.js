$(document).ready(function () {
    /* ---giai doan 1: chon vi tri, chuyen muc, mo hinh ---*/
    /* chuyên mục thây đổi, load mô hình */
    $('.user-express-form select[name=category]').change(function () {
        let _this =  $(this)
            groupId = _this.val(),
            $paradigm = $('.user-express-form select[name=paradigm]')

        $paradigm.empty()

        $.ajax({
            url: '/params/ajax-get-child-group',
            method: 'GET',
            dataType: 'json',
            data: {parent_id: groupId},
            success: data => {
                show_get_child_option($paradigm, data['child_group'])
            },
            error: () => {
				location.reload();
			}
        });
    });

    /* loai thiet bi */
    $('.user-express-form .device-btn').click(function () {
        let _this = $(this);

        $('.user-express-form input[name=position]').prop('checked', false);
        $('.user-express-form .device-btn').removeClass('active')
        _this.addClass('active')

        if (_this.hasClass('btn-desktop')) {
            $('.D').removeClass('d-none');
            $('.M').addClass('d-none');
        } else {
            $('.D').addClass('d-none');
            $('.M').removeClass('d-none');
        }
    });

    /*
        Vi tri quan cao thay doi
           + thay doi ghi chu
           + thay doi img mo ta
     */
    $('input[name=position]').change(function () {
        //ghi chu
        let id = $(this).attr('id');
        let bannerName = $(`label[for=${id}]`).text();
        let bannerPrice = $(this).attr('data-coin-price');
        let bannerWidth = $(this).attr('data-banner-width');
        let bannerHeight = $(this).attr('data-banner-height');
        let _html = `<p class="notes">Ghi chú:</p>
                     <p>Vị trí quảng cáo <span class="page-one">${bannerName}</span>
                        có kích thước <span class="page-one">${bannerWidth} x ${bannerHeight}</span>
                        , phí hiển thị mỗi ngày <span class="page-one">${bannerPrice}&nbsp;coins</span></p>`;

        let layout = $(this).parents('.banner-group-type');
        let notePage = $(layout).find('.note-pages')
        $(notePage).empty();
        $(notePage).append(_html);

        //img mo ta
        let pos = $(this).attr('data-position');
        $('.express-img').removeClass('banner-border-select')
        $(`.img-${pos.toLowerCase()}`).addClass('banner-border-select');
    });

    /* chuyen tag hinh anh - phase 2*/
    nextToStep2 = () => {
        let bannerGroup = $('.nav-item.active').attr('id');//id = nav-home-tab: chuyen muc, id =  nav-profile-tab: trang chu
        var position = $('input[name=position]:checked');
        var paradigm = 1; //mac dinh trang chu

        if (bannerGroup == 'nav-home-tab') {
            //kiem tra da chon gia tri chuyen muc
            let category = $('select[name=category]').val();
            if (!category) {
                toastr.error('Vui lòng chọn chuyên mục!');
                return;

            }

            //kiem tra gia tri  mo hinh
            paradigm = $('select[name=paradigm]').val();
            if (!paradigm) {
                toastr.error('Vui lòng chọn mô hình!');
                return;

            }

            //kiem tra vi tri banner
            let validBannerPos = $(position).hasClass('C');
            if (!validBannerPos) {
                toastr.error('Vui lòng chọn vị trí banner!');
                return;

            }

        }
        if (bannerGroup == 'nav-profile-tab') {
            //kiem tra vi tri banner
            let validBannerPos = $(position).hasClass('H');
            if (!validBannerPos) {
                toastr.error('Vui lòng chọn vị trí banner!');
                return;

            }
            paradigm = 1;

        }

        let bannerWidth = $('input[name=position]:checked').attr('data-banner-width');
        let bannerHeight = $('input[name=position]:checked').attr('data-banner-height');
        $('.banner-img-size-desc').text(`Kích thước ${bannerWidth}x${bannerHeight}`);

        // $.ajax({
        //     url: 'ajax-time-express',
        //     async: false,
        //     type: "GET",
        //     dataType: "json",
        //     data: {
        //         'paradigm': paradigm,
        //         'position': position.val()
        //     },
        //     success: function (data) {
        //
        //     }
        // });

        $('input[name=banner_date]').daterangepicker(
            {
                opens: 'left',
                minDate: new Date(),
                // isInvalidDate: function(ele) {
                //     let currentDate = new Date(ele._d).getTime() / 1000;
                //     let invalidDate = false;
                //     for(let date of dateRange) {
                //         invalidDate =  currentDate >= date.date_from && currentDate <= date.date_to?true:false;
                //         if(invalidDate) {
                //             return invalidDate;
                //         }
                //     }
                //     return invalidDate;
                // }

            },
            function (start, end, label) {
            }
        );

        goToStep(2)
    }
    /* phase 2 - upload image - set time */

    //upload banner image
    $('.button-upload').click(function () {
        $('input[name=banner_image]').click();

    });

    $('input[name=banner_image]').change(function (e) {
        let bannerWidth = $('input[name=position]:checked').attr('b-w');
        let bannerHeight = $('input[name=position]:checked').attr('b-h');
        let file = this.files[0];
        let _URL = window.URL || window.webkitURL;

        if ((file = this.files[0])) {
            let img = new Image();
            var objectUrl = _URL.createObjectURL(file);
            img.onload = function () {
                // if (this.width < bannerWidth || this.height < bannerHeight) {
                //     toastr.error(`Vui lòng tải ảnh có kích thước ${bannerWidth}x${bannerHeight}!`);
                //     return
                // }

                $('.show-upload-banner-img').attr('src', window.URL.createObjectURL(file));
                _URL.revokeObjectURL(objectUrl);
            };
            img.src = objectUrl;
        }
    });

    //chuyen phase 3
    nextToStep3 = () => {
        //check da upload banner image
        let banner_image = $('input[name=banner_image]');
        if (!banner_image[0].files[0]) {
            toastr.error('Vui lòng tải ảnh banner!')
            return;
        }

        //chon ngay dang banner
        let banner_date = $('input[name=banner_date]').val();
        if(!banner_date) {
            toastr.error('Vui lòng chọn ngày đăng banner!')
            return;
        }

        let $parent = $('.user-express-form'),
            dGroup = $parent.find('.select-position.active').text(),
            dCategory = $parent.find('select[name="category"] option:selected').text(),
            dParadigm = $parent.find('select[name="paradigm"] option:selected').text(),
            paradigmText = `${dGroup} - ${dCategory} - ${dParadigm}`
            dTime = $parent.find('input[name=banner_date]').val()
            dDevice = $parent.find('.tab-device.active .device-group-btn .device-btn.active').text(),
            $position = $parent.find('input[name="position"]:checked')
            dPosition = $position.attr('data-banner-name')
            dPrice = $position.attr('data-coin-price')
            // calculator number of days
            timeArr = dTime.split('-'),
            diffInDays = (new Date(timeArr[1].trim()) - new Date(timeArr[0].trim())) / (1000 * 60 * 60 * 24),
            totalPrice = parseInt(dPrice) * parseInt(diffInDays);

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

    goToStep = (step) => {
        let $parent = $('.user-express-form')

        $parent.find('.step-section').addClass('d-none');
        $parent.find(`.step-section.phase-${step}`).removeClass('d-none');
    }

    $('.user-express-form .btn-payout').click(function (e) {
        e.preventDefault();

        let _this = $(this),
            $parent = _this.parents('.user-express-form'),
            $position = $parent.find('input[name="position"]:checked')
            dTime = $parent.find('input[name=banner_date]').val()
            timeArr = dTime.split('-'),
            datas = {
                banner: $parent.find('.select-position.active').attr('data-banner'),
                banner_group_id: $position.attr('data-banner-group-id'),
                date_from: timeArr[0],
                date_to: timeArr[1],
            };

        Object.entries(datas).forEach(([key, data]) => {
            let input = $('<input>')
                .attr('type', 'hidden')
                .attr('name', key)
                .val(data)

            $parent.append(input);
        });

        $parent.submit()
    })
})
