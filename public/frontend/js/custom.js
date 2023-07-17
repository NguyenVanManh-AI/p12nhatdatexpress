/**
 * Is loading for autoload
 * @type {number}
 */
var is_loading = 0;

/**
 * Number get for autoload
 * @type {number}
 */
var no = 0;

/**
 * Is click status
 * @type {number}
 */

/**
 * Number get for autoload
 * @type {number}
 */

const MAX_LOAD = 20;

//ajax loading page
$(document).on({
    ajaxStart: function () {
        // loading('show');
    },
    ajaxStop: function () {
        // loading('hide');
    },
});

// Click outer input auto load
$('body').on('click', '.auto_load', function () {
    $(this).find('input:checkbox').prop('checked', !$(this).find('input:checkbox').is(':checked')).trigger('change')
})

/**
 * Sleep
 * @param ms
 * @returns {Promise<unknown>}
 */
function sleep(ms) {
    // return new Promise(resolve => setTimeout(resolve, ms));
    return new Promise(
        resolve => setTimeout(resolve, ms)
    );
}

/**
 * Hidden text input date when click or select date
 * @param txtDateElement
 */
function hiddenInputTextDate(txtDateElement) {
    if ($(txtDateElement).siblings("input").val())
        $(txtDateElement).hide()
}

/**
 * Get district by id province
 *
 * @param element
 * @param url
 * @param district_select_id
 * @param old_province
 * @param old
 * @returns {Promise<void>}
 */
async function get_district(element, url, district_select_id, old_province = null, old = null) {
    var province_id = old_province || $(element).find(":selected").val();
    $(district_select_id).empty();
    $(district_select_id).append("<option selected value=''>-- Quận/huyện --</option>");
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            province_id
        },
        success: function (data) {
            $.each(data['districts'], function (index, value) {
                let selected = "";
                if (old != null) {
                    selected = old == value.id ? "selected" : ""
                }
                district_value = "<option value='" + value.id + "' " + selected + " >" + value.district_name + "</option>";
                $(district_select_id).append(district_value);
            });
        }

    });
    $(district_select_id).trigger('change');
}

//load ward khi district thay doi
async function get_ward(element, url, ward_select_id, old_district = null, old = null) {
    var district_id = old_district || $(element).find(":selected").val();
    $(ward_select_id).empty();
    $(ward_select_id).append("<option selected value=''>Phường/xã</option>");
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            district_id
        },
        success: function (data) {
            $.each(data['wards'], function (index, value) {
                let selected = "";
                if (old != null) {
                    selected = old == value.id ? "selected" : ""
                }
                district_value = "<option value='" + value.id + "' " + selected + ">" + value.ward_name + "</option>";
                $(ward_select_id).append(district_value);
            });
        }
    });
}

/**
 * Select Province by district (data-province=province_id)
 *
 * @param elementSelectDistrict
 * @param elementSelectProvince
 * @param dataAttr
 */
async function get_province_by_district(elementSelectDistrict, elementSelectProvince, dataAttr = 'province') {
    let province_selected = $(elementSelectDistrict).find(":selected").data(dataAttr);
    let change = $(elementSelectDistrict).val()
    if (province_selected) {
        let selected = change
        $(elementSelectProvince).val(province_selected).trigger('change')
        await sleep(1000)
        $(elementSelectDistrict).val(selected).trigger('change')
    }
}


// get province by name
function get_province_by_name(province_name, url, province_select_id) {
    return $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            province_name: province_name.trim()
        },
        success: function (data) {
            if(data['province']) {
            $(province_select_id).val(data['province'].id);
            $(province_select_id).trigger('change');
            }
            return data;
        }
    });
}

// get province by name
function get_district_by_name(district_name, url, province_id, district_select_id) {
    return $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            district_name: district_name.trim(),
            province_id
        },
        success: function (data) {
            $(district_select_id).val(data['district']?.id);
            $(district_select_id).trigger('change');
            return data;
        }
    });
}

// get province by name
function get_ward_by_name(ward_name, url, district_id, ward_select_id) {
    return $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            ward_name: ward_name.trim(),
            district_id
        },
        success: function (data) {
            if (!data || !data['ward'] || !data['ward'].id) return

            let ward_id = data['ward'].id;
            // $(ward_select_id).val(ward_id);
            // $(ward_select_id).trigger('change');

            var temp = $(ward_select_id).val(ward_id);
            temp.change();
            //$(ward_select_id).val(ward_id).change();
            return data;
        }
    });
}

/**
 * Load progess when group change
 *
 * @param element
 * @param url
 * @param progress_select_id
 * @param old_group
 * @param old
 */
function get_progress(element, url, progress_select_id, old_group = null, old = null) {
    var group_id = old_group || $(element).find(":selected").val();
    $(progress_select_id).empty();
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            group_id
        },
        success: function (data) {
            $.each(data['progress'], function (index, value) {
                let selected = ""
                if (old != null) {
                    selected = old == value.id ? "selected" : ""
                }
                progress_value = "<option value='" + value.id + "' " + selected + ">" + value.progress_name + "</option>";
                $(progress_select_id).append(progress_value);
            });

        }
    });
}

/**
 * Load progess when group change
 *
 * @param element
 * @param url
 * @param progress_select_id
 * @param old_group
 * @param old
 */
function get_progress_by_url(element, url, progress_select_id, old_group = null, old = null) {
    var group_id = old_group || $(element).find(":selected").val();
    $(progress_select_id).empty();
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            group_id
        },
        success: function (data) {
            if (!data['progress'][0]) {
                selected = "selected";
                progress_value = "<option value='' " + selected + ">Không khả dụng</option>";
                $(progress_select_id).append(progress_value);
                $(progress_select_id).prop('disabled', true);
                return;
            }
            $(progress_select_id).prop('disabled', false);
            $.each(data['progress'], function (index, value) {
                let selected = ""
                if (old != null) {
                    selected = old == value.id ? "selected" : ""
                }
                progress_value = "<option value='" + value.id + "' " + selected + ">" + value.progress_name + "</option>";
                $(progress_select_id).append(progress_value);
            });

        }
    });
}


/**
 * Load progess when group change
 *
 * @param element
 * @param url
 * @param progress_select_id
 * @param old_group
 * @param old
 */
function get_progress_by_url_home(element, url, progress_select_id, old_group = null, old = null) {
    var group_id = old_group || $(element).find(":selected").val();
    $(progress_select_id).empty();
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            group_id
        },
        success: function (data) {
            if (!data['progress'][0]) {
                selected = "selected";
                progress_value = "<option value='' " + selected + ">Tình trạng</option>";
                $(progress_select_id).append(progress_value);
                $(progress_select_id).prop('disabled', true);
                return;
            }
            $(progress_select_id).prop('disabled', false);
            $(progress_select_id).empty();
            var progress_value = "<option value='' selected>Tình trạng</option>";
            $(progress_select_id).append(progress_value);
            $.each(data['progress'], function (index, value) {
                let selected = ""
                if (old != null) {
                    selected = old == value.id ? "selected" : ""
                }
                progress_value = "<option value='" + value.id + "' " + selected + ">" + value.progress_name + "</option>";
                $(progress_select_id).append(progress_value);
            });

        }
    });
}

/**
 * Load furniture when group change
 *
 * @param element
 * @param url
 * @param furniture_select_id
 * @param old_group
 * @param old
 */
function get_furniture(element, url, furniture_select_id, old_group = null, old = null) {
    var group_id = old_group || $(element).find(":selected").val();
    var old = old
    $(furniture_select_id).empty();
    $(furniture_select_id).prop('disabled', false);
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            group_id: group_id
        },
        success: function (data) {
            if (data['furniture'].length === 0) {
                $(furniture_select_id).prop('disabled', true);
                $(furniture_select_id).siblings().find('span.required').hide()
                return;
            }

            $(furniture_select_id).siblings().find('span.required').show()
            $(furniture_select_id).prop('disabled', false);
            $.each(data['furniture'], function (index, value) {
                let selected = ""
                if (old != null) {
                    selected = old == value.id ? "selected" : ""
                }
                furniture = "<option value='" + value.id + "' " + selected + ">" + value.furniture_name + "</option>";
                $(furniture_select_id).append(furniture);
            });

        }
    });
}

/**
 * Load furniture when group change
 *
 * @param element
 * @param url
 * @param furniture_select_id
 * @param old_group
 * @param old
 */
function get_furniture_by_url(element, url, furniture_select_id, old_group = null, old = null) {
    var group_id = old_group || $(element).find(":selected").val();
    var old = old
    $(furniture_select_id).empty();
    // $(furniture_select_id).prop('disabled', true);
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            group_id: group_id
        },
        success: function (data) {
            if (!data['furniture'][0]) {
                selected = "selected";
                furniture = "<option value='' " + selected + ">Không khả dụng</option>";
                $(furniture_select_id).append(furniture);
                $(furniture_select_id).prop('disabled', true);
                return;
            }
            if (data['furniture'].length === 0) {
                $(furniture_select_id).prop('disabled', true);
                $(furniture_select_id).siblings().find('span.required').hide()
                return;
            }

            $(furniture_select_id).siblings().find('span.required').show()
            $(furniture_select_id).prop('disabled', false);
            $.each(data['furniture'], function (index, value) {
                let selected = ""
                if (old != null) {
                    selected = old == value.id ? "selected" : ""
                }
                furniture = "<option value='" + value.id + "' " + selected + ">" + value.furniture_name + "</option>";
                $(furniture_select_id).append(furniture);
            });

        }
    });
}


/**
 * Load furniture when group change
 *
 * @param element
 * @param url
 * @param furniture_select_id
 * @param old_group
 * @param old
 */
function get_furniture_by_url_home(element, url, furniture_select_id, old_group = null, old = null) {
    var group_id = old_group || $(element).find(":selected").val();
    var old = old
    $(furniture_select_id).empty();
    // $(furniture_select_id).prop('disabled', true);
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            group_id: group_id
        },
        success: function (data) {
            if (!data['furniture'][0]) {
                selected = "selected";
                furniture = "<option value='' " + selected + ">Nội thất</option>";
                $(furniture_select_id).append(furniture);
                $(furniture_select_id).prop('disabled', true);
                return;
            }
            if (data['furniture'].length === 0) {
                $(furniture_select_id).prop('disabled', true);
                $(furniture_select_id).siblings().find('span.required').hide()
                return;
            }

            $(furniture_select_id).siblings().find('span.required').show()
            $(furniture_select_id).prop('disabled', false);
            var furniture = "<option value='' selected>Nội thất</option>";
            $(furniture_select_id).append(furniture);
            $.each(data['furniture'], function (index, value) {
                let selected = ""
                if (old != null) {
                    selected = old == value.id ? "selected" : ""
                }
                furniture = "<option value='" + value.id + "' " + selected + ">" + value.furniture_name + "</option>";
                $(furniture_select_id).append(furniture);
            });

        }
    });
}

/**
 * Get children group
 *
 * @param element
 * @param url
 * @param child_group_select_id
 * @param parentOld
 * @returns {Promise<void>}
 */
async function get_children_group(element, url, child_group_select_id, parentOld = null) {
    var parent_id = parentOld || $(element).find(":selected").val();
    $(child_group_select_id).empty();

    $(child_group_select_id).append("<option selected value=''>Chọn chuyên mục</option>");
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            parent_id
        },
        success: function (data) {
            $.each(data['group_child'], function (index, value) {
                group_value = "<option data-url='" + value.group_url + "' value='" + value.group_url + "'>" + value.group_name + "</option>";
                $(child_group_select_id).append(group_value);
            });
        }

    });
    $(child_group_select_id).trigger('change');
}

/**
 * Autoload item
 *
 * @param element
 * @param url
 * @param data
 * @param appendElement
 * @param autoloadElement
 */
function autoload(element, url, data, appendElement, autoloadElement = '.auto_load') {
    if (no >= MAX_LOAD) {
        no = 0;
        $(element).prop('checked', false).trigger('change');
        return;
    }

    if ($(element).length && is_loading === 0) {
        var stateLoad = $(autoloadElement).offset().top - $(autoloadElement).height(),
            output = '';
        if ($(element).is(":checked") && $(window).scrollTop() + $(window).height() - 25 >= stateLoad) {
            var num_cur = $(element).attr("data-start");
            data.num_cur = num_cur;
            data.page = num_cur;

            $.ajax({
                type: "POST",
                url: url,
                data: data,
                beforeSend: function () {
                    is_loading = 1;
                },
                success: function (string) {
                    no++;
                    output = JSON.parse(JSON.stringify(string));

                    if (output.html != '') {
                        $(element).attr("data-start", parseInt(num_cur) + parseInt(output.num));
                        $(appendElement).append(output.html);
                        is_loading = 0;

                        // lazy image should move to global when finish
                        loadingImage()
                    } else {
                        $(autoloadElement).remove();
                    }
                },
                complete: function () {
                    // loading('hide');
                }
            }).fail(function () {
                $(autoloadElement).remove();
            })
        }
    }
}

/**
 * Handle Loading
 * @param s
 * @param o
 */
function loading(s, o) {
    var l;

    if (!o) {
        o = $('body');
        if (!o.children('#tth-loading').length) {
            $('<div id="tth-loading"></div>').appendTo(o);
        }
        l = o.children('#tth-loading');
    } else {
        if (!o.children('.loading').length) {
            $('<div class="loading"></div>').appendTo(o);
        }
        l = o.children('.loading');
    }

    if (o.css('position') == 'static') {
        o.css('position', 'relative');
    }

    if (s == 'show') {
        l.stop(true, true).fadeIn();
    } else {
        l.stop(true, true).fadeOut();
    }
}

showTextCount = (ed) => {
    let $parent = $(ed.targetElm).parent('.text-need-show-count'),
        descriptionEl = $parent.find('textarea')
        $wordCount = $parent.find('.word-count');

    if (descriptionEl && $wordCount && $wordCount.length) {
        let content = ed.getContent().replace(/<(.|\n)*?>/g, '')
            content = content.replace(/\&[a-z]{1,6}\;/g, 'x')

        $wordCount.text(content.length || 0);
    }
}

/**
 * Initialize tinyMCE Editor
 * @param element
 */
function initTinyMCE(element) {
    tinymce.init({
        selector: element,
        height: 450,
        language: 'vi_VN',
        // plugins: [
        //     ' advlist anchor autolink codesample fullscreen help image tinydrive',
        //     ' lists link media noneditable preview',
        //     ' searchreplace table template visualblocks wordcount'
        // ],
        // plugins: 'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        plugins: 'advlist anchor autolink codesample fullscreen help image lists link media noneditable preview searchreplace table template visualblocks wordcount',
        toolbar:
            'undo redo | fontsizeselect | bold italic underline strikethrough| superscript subscript | hr | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent |  forecolor backcolor | link unlink anchor | responsivefilemanager',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        autosave_ask_before_unload: false,
        powerpaste_allow_local_images: true,

        image_advtab: true,
        setup:function(ed) {
            ed.on('init', function (e) {
                showTextCount(ed)
            });
            ed.on('keyup', function(e) {
                showTextCount(ed)
            });
        }
    });
}

/**
 * Set google map
 * should check and move to one file
 * @param road
 * @param ward
 * @param district
 * @param province
 */
function setGoogleMap(road, ward, district, province, elementAddress = null, elementLat = '#map_latitude', elementLng = '#map_longtitude') {
    const roadIn = $(road).val();
    const wardIn = $(ward).find(":selected").val() ? " " + $(ward).find(":selected").html() : "";
    const addressIn = elementAddress ? $(elementAddress).val() : '';
    const districtIn = $(district).find(":selected").val() ? " " + $(district).find(":selected").html() + " " : "";
    const provinceIn = $(province).find(":selected").val() ? " " + $(province).find(":selected").html() + " " : "";
    const string_search = ` ${addressIn}${roadIn}${wardIn}${districtIn}${provinceIn}`;
    geocode({address: string_search}, elementLat, elementLng)
}


// /**
//  * Set google map
//  * @param road
//  * @param ward
//  * @param district
//  * @param province
//  */
// function setGoogleMap(road, ward, district, province) {
//     console.log(road, ward, district, province)
//         const roadIn = $(road).val();
//     const wardIn = $(ward).find(":selected").val() ? " " + $(ward).find(":selected").html() : "";
//     const districtIn = $(district).find(":selected").val() ? " " + $(district).find(":selected").html() + " " : "";
//     const provinceIn = $(province).find(":selected").val() ? " " + $(province).find(":selected").html() + " " : "";
//     const string_search = ` ${roadIn}${wardIn}${districtIn}${provinceIn}`;
//     geocode({address: string_search}, '#map_latitude', '#map_longtitude')
// }

/**
 * Convert string to slug
 * @param element
 * @param element_target
 */
function changeToSlug(element, element_target) {
    var title, slug;
    //Lấy text từ thẻ input title
    title = $(element).val();
    //Đổi chữ hoa thành chữ thường
    slug = title.toLowerCase();
    //Đổi ký tự có dấu thành không dấu
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    slug = slug.replace(/đ/gi, 'd');
    //Xóa các ký tự đặt biệt
    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
    //Đổi khoảng trắng thành ký tự gạch ngang
    slug = slug.replace(/ /gi, "-");
    //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
    //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
    slug = slug.replace(/\-\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-/gi, '-');
    slug = slug.replace(/\-\-/gi, '-');
    //Xóa các ký tự gạch ngang ở đầu và cuối
    slug = '@' + slug + '@';
    slug = slug.replace(/\@\-|\-\@|\@/gi, '');
    //In slug ra textbox có id “slug”
    $(element_target).val(slug);
}

/**
 * Get Content TINYMCE EDITOR (Without any HTML tags)
 * @param tinyMCEId
 * @param event
 * @param element_target
 * @param limit
 */
function getTinyContentWithoutHTML(tinyMCEId, event, element_target = null, limit = null) {
    if (!tinyMCE.get(tinyMCEId)) return
    tinyMCE.get(tinyMCEId).on(event, function (e) {
        let text = this.getContent({format: "text"})
        text = text.replace(/\n\n/g, ' ').trim()
        if (limit !== null) {
            text = fixedSizeString(text, limit);
        }
        if (element_target !== null) {
            $(element_target).val(text.replace(/\s{2,}/g, ' ').trim()).trigger('keyup')
        }
        return text.replace(/\s{2,}/g, ' ').trim();
    });
}

/**
 * Limit String
 * @param size
 * @param value
 * @returns {string}
 */
function fixedSizeString(value, size) {
    return value.padEnd(size).substring(0, size);
}

/**
 * Open current Popup
 * @param popup_id
 */
function open_current_popup(popup_id) {
    $(popup_id).modal('show');
}

/**
 * Open current Popup
 * @param popup_id
 */
function open_display_current_popup(popup_id, action = null) {
    // should check and improve
    if (popup_id) {
        if (action)
            $(popup_id).find('form').attr('action', action);

        $(popup_id).show();
        $("#layout").show();
    }
}

/**
 * Scroll to element
 * Recommend ID
 * @param element
 * @param time
 */
function scroll_to_element(element, time = 2000) {
    $('html, body, .main').animate({
        scrollTop: $(element).offset().top - 10000
    }, time);
}

/**
 * Number animation
 * @param el
 * @param startValue
 * @param endValue
 * @param incrementor
 * @param duration
 * @param delay = 0
 */
function numberAnimation(el, startValue, endValue, incrementor, duration, delay = 0) {
    anime({
        targets: el,
        textContent: [startValue, endValue],
        round: incrementor ? 1 / incrementor : 1 / 5,
        delay: delay,
        easing: 'linear', //easeInOutQuad
        duration: duration ? duration : 2000,
        update: function (a) {
            const value = a.animations[0].currentValue;
            el.innerHTML = numberWithCommas(value) + '+'
        }
    });
}

// image loading
removeLazyAttribute = (element) => {
    if (!element) return
    $(element).removeClass('lazy').removeAttr('data-src')
}

replaceDefaultImage = (element) => {
    if (!element) return
    $(element).attr('src', '/frontend/images/home/image_default_nhadat.jpg')
}

loadingImage = () => {
    // lazy loading images
    $('.lazy').Lazy({
        scrollDirection: 'vertical',
        effect: 'fadeIn',
        visibleOnly: true,
        onError: function (element) {
            $(element).attr('src', '/frontend/images/home/image_default_nhadat.jpg')
            removeLazyAttribute(element)
        },
        afterLoad: function (element) {
            removeLazyAttribute(element)
        },
    });

    // normal images error
    $('img').on('error',function () {
        replaceDefaultImage(this)
    });
    // image need loading
    $('img.img-loading').each(function() {
        $(this).one('load', function() {
            $(this).removeClass('img-loading')
        })
    })
}

$(function() {
    loadingImage()
});

/**
 * Format number
 * @param x
 * @param s
 * @returns {string}
 */
function numberWithCommas(x, s = '.') {
    x = String(x).toString();
    var afterPoint = '';
    if (x.indexOf('.') > 0)
        afterPoint = x.substring(x.indexOf('.'), x.length);
    x = Math.floor(x);
    x = x.toString();
    var lastThree = x.substring(x.length - 3);
    var otherNumbers = x.substring(0, x.length - 3);
    if (otherNumbers != '')
        lastThree = s + lastThree;
    return otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
}

/**
 * Change parameter on url
 * @param key
 * @param value
 * @returns {string}
 */
function changeParamUrl(key, value) {
    const searchParams = new URLSearchParams(window.location.search);
    searchParams.set(key, value);
    return window.location.pathname + '?' + searchParams.toString();
}

// report
// $('body').on('click', '.submit_report', function (e) {
//     e.preventDefault();
//     var left = $(this).siblings('label').children('span.left').html();
//     var right = $(this).siblings('label').children('span.right').html();
//     var value = $(this).siblings('.human').val();
//     if ((parseInt(left) + parseInt(right)) == parseInt(value)) {
//         console.log($(this));
//         $(this).form().submit();
//     } else {
//         toastr.error('Sai kết quả');
//     }
// });
$('body').on('click', '.button-report span', function (event) {
    $(this).siblings('.report-main').toggle();
    event.stopPropagation();
});
$('body').on('click', function () {
    $('.group-report .button-report .report-main').hide();
});
$("body").on("click", ".popup .close-report", function (event) {
    event.preventDefault();
    let popup = $(this).parents(".popup");
    popup.hide();
    $("#layout").hide();
});

$("body").on("click", "#layout", function (event) {
    $('.popup').hide();
});

// $('.table-head').on('click',function(){
//     // $(this).parents('.table-information').animate({scrollLeft: pWidth}, 2500);
//     pWidth = $("p.table-head").height();
//             $('table-information').animate({scrollLeft: pWidth}, 2500);
//             return false;
// });

$('body').on('click', '.scroll', function () {
    var test = $(this).siblings('.table-information').parents('.detail');
    test.animate({scrollLeft: 150});
});

//khao sat website
$(document).ready(function () {
    //lazy load anh
    $('.ct-slick-homepage').slick({
        lazyLoad: 'ondemand',
    });
});
