$(document).ready(function () {
    let __$parents = $('.classified-page')

    // old should check
    function show_get_child_option(selector, children, level=0)
    {
        $.each(children, function (index, value)
        {
            if (level == 0 && index == 0) {
                var html = `<option value=""></option><option value=${value.id}><b>${value.group_name}</b></option>`;
            }
            else {
                var html = `<option value=${value.id}><b>${value.group_name}</b></option>`;
            }
            selector.append(html);
            if (value.children)
            {
                show_get_child_option(selector, value.children, 1);
            }
        });
    }
    // end old should check

    const getParentGroupFormData = $parents => {
        const groupParentId = $parents.find('[name="classified_type"]:checked').val() || $parents.find('[name="classified_type"]').val();

        if (!groupParentId) return

        $parents.find("select[name='paradigm']").empty();
        $parents.find("select[name='unit_price']").empty();

        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/classifieds/parent-group-form-data',
                method: 'GET',
                data: {
                    group_parent_id: groupParentId
                },
                success: res => resolve(res),
                error: err => reject(err)
            })
        })
    }

    $('.classified-page input[name="classified_type"]').on('change', function() {
        __$parents.find('.c-overlay-loading.parent-group-loading').addClass('loading')

        getParentGroupFormData(__$parents)
            .then(res => {
                if (!res || !res.data) return
                const paradigms = res.data.group_paradigms,
                      unitPrices = res.data.group_unit_prices;

                if (paradigms) {
                    show_get_child_option($('select[name="paradigm"]'), paradigms)
                    $("select[name='paradigm']").trigger('change');
                }

                if (unitPrices) {
                    $.each(unitPrices, function (index, unit) {
                        html = `<option value="${unit.id}">${unit.unit_name}</option>`;
                        $("select[name='unit_price']").append(html);
                    });
                    $("select[name='unit_price']").trigger('change');
                }
            })
            .catch(() => {})
            .finally(() => {
                __$parents.find('.c-overlay-loading.parent-group-loading').removeClass('loading')
            });
    })

    //Mô hình thây đổi --> Tình trạng, nội thất thây đổi
    $('.classified-page select[name=paradigm]').change(function () {
        const paradigmId = __$parents.find('select[name=paradigm]').val();

        // old should change
        let $progress = __$parents.find("select[name='progress']"),
            $furniture = __$parents.find("select[name='furniture']");

        // clear depend select
        $progress.empty();
        $progress.append('<option value="">---Chọn---</option>')

        $furniture.empty();
        $furniture.append('<option value=""></option>')
        if (!paradigmId) return

        // should change to call only 1 api
        $.ajax({
            url: "/params/ajax-get-progress",
            type: "GET",
            dataType: "json",
            data: {
                group_id: paradigmId,
            },
            success: function (data) {
                $.each(data['progress'], function (index, value) {
                    html = `<option value="${value.id}">${value.progress_name}</option>`;
                    $progress.append(html);
                });
            }
        });

        $.ajax({
            url: '/params/ajax-get-furniture',
            type: "GET",
            dataType: "json",
            data: {
                group_id: __$parents.find('select[name=paradigm]').val(),
            },
            success: function (data) {
                $.each(data['furniture'], function (index, value) {
                    let _html = `<option value="${value.id}">${value.furniture_name}</option>`;
                    $furniture.append(_html);
                });
            }
        });
    })

    //Kiểm tra điều kiện chính chủ
    $('.classified-page input.account-verify').change(function () {
        if ($(this).is('[readonly]')) {
            toastr.error('Chỉ những tài khoản được xác thực(nạp trên 500k) mới được sử dụng tính năng này');
            $(this).prop('checked', false);
        }
    });

    const setTotalDays = () => {
        let dateFrom = moment($('.classified-page input[name=date_from]').val()),
            dateTo = moment(__$parents.find('input[name=date_to]').val()),
            days = (dateTo.diff(dateFrom, 'days')) + 1;

            __$parents.find('.total-days').text(days);
    }

    setTotalDays();

    //Kiểm tra ngày đăng tin: >= ngày hiện tại
    $('.classified-page input[name=data_from], .classified-page input[name=date_to]').on('change', function () {
        if ($('.classified-page input[name=date_from]').val() && $('.classified-page input[name=date_to]').val()) {
            let dateFrom = moment(__$parents.find('input[name=date_from]').val()),
                dateTo = moment(__$parents.find('input[name=date_to]').val()),
                days = dateTo.diff(dateFrom, 'days') + 1;

            if (days < 1 || days > 180) {
                $('input[name=date_to]').val('');
                toastr.error(days < 1 ? 'Từ ngày phải bé hơn hoặc bằng đến ngày' : `Tổng số ngày đăng: ${days}, vượt quá 180 ngày theo chính sách quản trị!`);

                days = 0;
            }

            $('.classified-page .total-days').text(days);
        }
    });

    //Lấy tiêu đề tin đăng làm tiêu đề seo
    $('.classified-page input[name=title]').on('keyup change', function () {
        __$parents.find('input[name=meta_title]').val($(this).val());
    })

    //Lấy mô tả là mô tả seo
    const changeMetaDescription = () => {
        const maxLength = 300, maxCharLength = 6;
        let content = __$parents.find('textarea[name=meta_desc]').val();

        if (content && content.length > maxLength) {
            let overSpaceIndex = content.indexOf(' ', maxLength),
                endIndex = overSpaceIndex == -1 || overSpaceIndex > maxLength + maxCharLength ? maxLength : overSpaceIndex;

            content = content.substring(0, endIndex)
        }

        __$parents.find('textarea[name=meta_desc]').val(content);
    }

    __$parents.find('textarea[name=description]').on('keyup change', function () {
        let content = __$parents.find('textarea[name=description]').val();

        __$parents.find('textarea[name=meta_desc]').val(content)
        changeMetaDescription()
    });

    changeMetaDescription()

    //Kiểm tra điều kiện mua các gói dịch vụ
    $('.classified-page input[name=classified_service]').click(function (event) {
        let service_coin = $(this).attr('service_coin');
        let coin_amount = $('.coin-amount').text();
        let day = $(this).siblings('.inp-day').first().val();
        if (parseInt(service_coin)*day > parseInt(coin_amount)) {
            toastr.error('Tài khoản không đủ số coin');
            $(this).siblings('.inp-day').first().val(1)
            return false;
        } else {
            $('.classified-page input[name=classified_package]').prop('checked', false);
        }

    });

    //kie mtra dieu kiem su dung goi tin
    $('.classified-page input[name=classified_package]').click(function () {
        let package_amount = $(this).attr('package_amount');
        let title = $(this).attr('title');
        let day = $(this).siblings('.inp-day').first().val();

        if (parseInt(package_amount) < parseInt(day)) {
            toastr.error(`Tài khoản không đủ số  ${title}`);
            $(this).siblings('.inp-day').first().val(1);
            return false;

        } else {
            $('.classified-page input[name=classified_service]').prop('checked', false);

        }
    });

    //Kiem tra ngay hop le
    $('.classified-page input.inp-day').change(function (event) {
        let classified_package = $(this).siblings('input[name=classified_package]').first();
        if (classified_package.length) {
            $(classified_package).click();
            return;
        }
        let classified_service = $(this).siblings('input[name=classified_service]').first();
        if (classified_service.length) {
            $(classified_service).click();
            return;
        }
    });

    // hình ảnh tin đăng
    // upload thư viện ảnh tin đăng
    $('.classified-page .btn-upload-image').click(function () {
        $('.classified-page input[name=temp_upload_image]').click();
    });
    $('.classified-page input[name=temp_upload_image]').change(async function () {
        let files = this.files;
        for (let file of files) {
            let reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function () {
                addSlickUploadedImage(reader.result)
            }
        }
    });
    // hình ảnh tin đăng

    //chon hinh anh project
    $('body').on('click', '.classified-page .classified-page__project-images-box .js-select-project-image', function () {
        let _imageSrc = $(this).find('img').attr('src');
        addSlickUploadedImage(_imageSrc, 'gallery_project[]')
    })

    $('body').on('click', '.classified-page .classified-page__upload-images-box .remove-land', function() {
        // should check drag switch index of images
        // old should check
        let index = $(this).parents(".slick-slide").attr("data-slick-index");
        $('.classified-page__slick-list').slick('slickRemove', index);

        resetImageSlickIndex()
    });

    const resetImageSlickIndex = () => {
        let $slickItem = $('.classified-page__upload-images-box .list-update-slider-image .slick-slide')

        if (!$slickItem || !$slickItem.length) {
            $('.classified-page__upload-images-box').addClass('d-none')
            return
        }

        $slickItem.each(function(index, ele) {
            index == 0
                ? $(this).addClass('classified-page__upload-images-background position-relative')
                : $(this).removeClass('classified-page__upload-images-background position-relative')

            $(this).attr('data-slick-index', index);
        });
    }

    const addSlickUploadedImage = (imageSrc, inputName = 'gallery[]') => {
        $('.classified-page__upload-images-box').removeClass('d-none')
        let _html = `<div class="item-slick-land">
            <div class="image-ratio-box relative">
                <div class="absolute-full">
                    <img class="object-cover shadow" src="${imageSrc}" alt="">
                    <input type="hidden" name="${inputName}" value="${imageSrc}">
                </div>
                <i class="fas fa-times cursor-pointer remove-land"></i>
            </div>
        </div>`
        $('.classified-page__slick-list').slick('slickAdd', _html);

        resetImageSlickIndex()
    }

    // Thay đổi thứ tự ảnh
    $('.classified-page .slick-track').sortable({
        axis: 'x', // Cho phép kéo thả theo chiều ngang
        containment: 'parent', // Giới hạn vị trí kéo thả trong phạm vi của phần tử cha
        tolerance: 'pointer', // Cho phép kéo thả khi con trỏ chuột trỏ vào phần tử
        revert: true,
        update: function(event, ui) {
            resetImageSlickIndex()

            // Xử lý các thao tác sau khi thay đổi vị trí các ảnh (ví dụ: lưu vị trí mới vào cơ sở dữ liệu)
            // Lấy danh sách các ảnh sau khi thay đổi vị trí
            // let images = [];
            // $('.classified-page__slick-list .item-slick-land').each(function() {
            //     let inputName = $(this).find('input[type="hidden"]').attr('name');
            //     let imageSrc = $(this).find('img').attr('src');
            //     images.push({name: inputName, src: imageSrc});
            // });
            // Xử lý các thao tác sau khi thay đổi vị trí các ảnh (ví dụ: lưu vị trí mới vào cơ sở dữ liệu)
        },
    });

    //Kiểm tra điều kiện phải nhập tất cả các trường có thuộc tính required
    // should change a.submit?
    $('.classified-page form#add-classified a.submit').on('click', function (event) {
        event.preventDefault;

        //Kiểm tra nếu đăng tin thường, thì không được đăng quá 3 tin ngày
        $classifiedService = __$parents.find('input[name=classified_package]').val();
        $checkPostValid = $(this).hasClass('post-invalid');

        if ($classifiedService == 1 && $checkPostValid) {
            toastr.error('Chỉ được đăng tối đa 3 tin/ngày, 60tin/tháng.Để tiếp tục thao tác, vui lòng nâng cấp gói tin hoặt mua dịch vụ!');
            return;
        }

        let $parentForm = $(this).parents('form#add-classified')
        $parentForm.trigger('submit');
    });

    const getProjectData = projectId => {
        let groupParentId = __$parents.find('[name="classified_type"]:checked').val() || __$parents.find('[name="classified_type"]').val();

        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/classifieds/project-form-data',
                method: 'GET',
                data: {
                    project_id: projectId,
                    group_parent_id: groupParentId
                },
                success: res => resolve(res),
                error: err => reject(err)
            })
        })
    }

    const appendProjectImages = (project, $projectImagesBox) => {
        $projectImagesBox.empty()
        $projectImagesBox.removeClass('d-none')
        if (!project || !project.images || !project.images.length || !$projectImagesBox || !$projectImagesBox.length) return

        // append images
        const images = project.images || []

        let _imagesHtml = `<p class="classified-page__project-images-title font-italic text-grey fs-normal">
            Gợi ý hình ảnh liên quan đến dự án
            <a href="/du-an/${project.project_url}.html" target="_blank" class="link-flat js-selected-project-name text-break">
                ${project.project_name}
            </a>
        </p>`;

        _imagesHtml += '<div class="classified-page__project-images d-flex scrollable-vertical">'
        images.forEach(imageUrl => {
            _imagesHtml += `<div class="image-ratio-box relative w-size-120 mr-2">
                <a href="javascript:void(0);" class="js-select-project-image absolute-full">
                    <img class="object-cover" src="${imageUrl}" alt="">
                </a>
            </div>`
        })
        _imagesHtml += '</div>'

        $projectImagesBox.html(_imagesHtml)
    }

    $('body').on('change', '.classified-page .classified-page__form select[name="project"]', function () {
        let $parents = $(this).parents('.classified-page'),
            projectId = $parents.find('.classified-page__form select[name="project"]').val();

        if (!projectId) return
        // loading for all field change when project changed
        $parents.find('.c-overlay-loading.project-loading').addClass('loading')

        getProjectData(projectId)
            .then(res => {
                if (!res || !res.data || !res.data.project) return
                const project = res.data.project,
                    location = project.location,
                    priceShow = project.project_price,
                    direction = project.direction,
                    dependencyParadigm = project.dependency_paradigm_id,
                    unitPrice = project.unit_price_id

                $parents.find('input[name="price_show"]').val(priceShow).trigger('change')
                $parents.find('input[name="price"]').val(priceShow)
                $parents.find('input[name="area"]').val(project.project_area_to)
                $parents.find('[name="unit_price"]').val(unitPrice).trigger('change')
                $parents.find('[name="paradigm"]').val(dependencyParadigm).trigger('change')
                $parents.find('select[name="direction"]').val(direction).trigger('change')

                if (location) {
                    if (location.district_id)
                    $parents.find('[name="district"]').attr('data-selected', location.district_id)

                    if (location.province_id)
                        $parents.find('[name="province"]').val(location.province_id).trigger('change')

                    if (location.address)
                        $parents.find('[name="address"]').val(location.address).trigger('change')
                }

                // meta
                $parents.find('input[name="meta_key"]').val(project.meta_key);

                // append project images
                let $projectImagesBox = $parents.find('.classified-page__project-images-box')
                appendProjectImages(project, $projectImagesBox)
            })
            .catch(() => {})
            .finally(() => {
                $parents.find('.manually-project-input').addClass('has-input-warning')
                $parents.find('.c-overlay-loading.project-loading').removeClass('loading')
            });
    })

    // price change format
    __$parents.find('input[name="price_show"]').on('keypress', function (e) {
        let charCode = (e.which) ? e.which : e.keyCode,
            oldContent = $(this).val(),
            char = String.fromCharCode(charCode)

        if (char.match(/[^0-9\.]/g) || char == '.' && oldContent.includes('.'))
            return false;
    });

    __$parents.find('input[name="price_show"]').on('change keyup', function(){
        let price = $(this).val()

        price = price.replace(/[^0-9\.]/g, '')
        __$parents.find('input[name="price"]').val(parseFloat(price));
        $(this).val(formatCurrency(price));
    });

    // format 1234567.89 to 1,234,567.89
    function formatCurrency(content) {
        if (!content) return '';

        let dot = content.includes('.') ? '.' : ''

        let natural = content.split('.')[0],
            decimal = content.split('.')[1] ? '.' + content.split('.')[1] : dot;

        return natural.replace(/\B(?=(\d{3})+(?!\d))/g, ', ') + decimal
    }

    checkFormatOldPrice = () => {
        let __oldPrice = __$parents.find('input[name="price"]').val()

        if (__oldPrice)
            __$parents.find('input[name="price_show"]').val(__oldPrice).trigger('change')
    }

    checkFormatOldPrice()
    // end price change format

    //preview

    $('.classified-page .js-classified-preview').on('click', function (event) {
        let $previewParents = $('#preview'),
            _previewCarousel = $('.classified__preview-carousel'),
            formData = $('.classified-page__form :not([name="_token"]):not([name="gallery[]"]):not([name="meta_desc"])').serialize();

        $('.preview__detail-box').html('')
        $previewParents.find('.c-overlay-loading').addClass('loading')

        _previewCarousel.find('.classified__preview-carousel-item').each(function(index) {
            _previewCarousel.trigger('remove.owl.carousel', index)
        })

        let images = $('.list-update-slider-image img').get().reverse();
        $(images).each(function (index) {
            let html = `<div class="classified__preview-carousel-item">
                <div class="image-ratio-box-half relative">
                    <div class="absolute-full bg-white">
                    <img class="object-cover" src="${$(this).attr('src')}">
                    </div>
                </div>
            </div>`;
            _previewCarousel.trigger('add.owl.carousel', [html, index]).trigger('refresh.owl.carousel');
        });

        $.ajax({
            url: '/classifieds/preview',
            type: 'GET',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: res => {
                $previewParents.find('.c-overlay-loading').removeClass('loading')

                if (res && res.data && res.data.html) {
                    $('.preview__detail-box').html(res.data.html)
                }
            },
            error: () => {
                $('.preview__detail-box').html('')
                $previewParents.find('.c-overlay-loading').removeClass('loading')
            }
        })
    });
});

//true: obj null hoặt undefine
function isNullOrUndefined(obj) {
    if (obj === undefined) {
        return true
    }
    if (obj == null) {
        return true
    }
    return false;
}


function allowDrop(event) {
    event.preventDefault();
}

function drag(event) {

}

function drop(event) {
    event.preventDefault();
    if (event.dataTransfer.items) {
        // Use DataTransferItemList interface to access the file(s)
        [...event.dataTransfer.items].forEach((item, i) => {
            // If dropped items aren't files, reject them
            if (item.kind === 'file') {
                const file = item.getAsFile();
                if (file.type.includes('image')) {
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function () {
                        let _html = `<div class="item-slick-land"><img src="${reader.result}" alt=""><input type="text" name="gallery[]" value="${reader.result}" hidden><i class="fas fa-times remove-land"></i></div>`;
                        $('.slick').slick('slickAdd', _html);
                    }
                }

            }
        });
    }
}


