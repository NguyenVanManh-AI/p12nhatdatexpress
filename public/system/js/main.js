jQuery(document).ready(function () {
    // Before loaded page
    beforeLoaded()
    //ajax loading page
    // $(document).on({
    //     ajaxStart: function(){
    //         $("body").addClass("loading");
    //     },
    //     ajaxStop: function(){
    //         $("body").removeClass("loading");
    //     },
    // });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function zero(num) {
        return num >= 0 && num < 10 ? "0" + num : num + "";
    }

    setInterval(function () {
        var now = new Date();
        var strDateTime = [
            [
                zero(now.getHours()),
                zero(now.getMinutes()),
                zero(now.getSeconds()),
            ].join(":"),
        ];
        document.querySelector("#current-time").innerHTML = strDateTime;
    }, 1000);

    var url = window.location;
    // Start find Url
    url = url.toString().split('?')[0];
    url = findUrl(url);
    // End find url

    // for sidebar menu but not for treeview submenu
    $("ul.nav-sidebar .nav-link")
        .filter(function() {
            return this.href == url;
        })
        .addClass("active");

    $("ul.nav-sidebar .nav-link")
        .filter(function() {
            return this.href == url;
        })
        .parent()
        .siblings()
        .find(".nav-link")
        .removeClass("active");

    // for treeview which is like a submenu
    $("ul.nav-treeview .nav-link")
        .filter(function () {
            return this.href == url;
        })
        .addClass("active");
    $("ul.nav-treeview .nav-link")
        .filter(function () {
            return this.href == url;
        })
        .parents(".nav-item")
        .addClass("menu-open");
    $("ul.nav-treeview .nav-link")
        .filter(function () {
            return this.href == url;
        })
        .parents(".nav-treeview")
        .siblings()
        .removeClass("active");
    $("ul.nav-treeview .nav-link")
        .filter(function () {
            return this.href == url;
        })
        .parents(".nav-item")
        .siblings()
        .find(".nav-link")
        .removeClass("active")

    // should add title in html. Create goTop component and change all use class js-go-to-top instead
    $('#btnTop').attr('title', 'Về đầu trang')

    $('#btnTop').on('click', function () {
        var body = $("html, body");
        body.stop().animate({scrollTop:0}, 500, 'swing');
    })
});

/**
 * Set Min Max with 2 input element
 * @param inputElementStart
 * @param inputElementEnd
 */
function setMinMaxDate(inputElementStart, inputElementEnd){
    if ($(inputElementStart).val()) $(inputElementEnd).attr('min',$(inputElementStart).val());
    if ($(inputElementEnd).val()) $(inputElementStart).attr('max',$(inputElementEnd).val());

    $(inputElementStart).change(function (){
        $(inputElementEnd).attr('min',$(inputElementStart).val());
    });
    $(inputElementEnd).change(function (){
        $(inputElementStart).attr('max',$(inputElementEnd).val());
    });
}

/**
 * Hidden text input date when click or select date
 * @param txtDateElement
 */
function hiddenInputTextDate(txtDateElement){
    if($(txtDateElement).siblings("input").val())
        $(txtDateElement).hide()
}

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

function previewOneImgFromInputText(inputTextFileElementId, previewElementId, prefix = '', old = null) {
    let imageList = [];
    let image = old || $(inputTextFileElementId).val();
    if (image == '')
        return;
    if (old != null)
        $(inputTextFileElementId).val(old)
    $(previewElementId).empty()
    $(previewElementId).prepend(` <div class="pip-thumb h-100 position-relative p-1">
                  <img src="`+ prefix + image +`" style="object-fit: cover; height: 100%">
                  <span class="remove close" aria-label="Close">×</span>
                  </div>`)
    $(".remove").click(function () {
        $(this).parent(".pip-thumb").remove();
        $(inputTextFileElementId).val('')
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
 * Initialize tinyMCE Editor
 * @param element
 */
function initTinyMCE(element){
    tinymce.init({
        selector: element,
        height: 600,
        language: 'vi_VN',
        // plugins: [
        //     'advlist anchor autolink codesample fullscreen help image tinydrive',
        //     'lists link media noneditable preview',
        //     'searchreplace table template visualblocks wordcount'
        // ],
        plugins: 'advlist anchor autolink codesample fullscreen help image lists link media noneditable preview searchreplace table template visualblocks wordcount',
        toolbar:
            'undo redo | fontsizeselect | bold italic underline strikethrough| superscript subscript | hr | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent |  forecolor backcolor | link unlink anchor | responsivefilemanager',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        autosave_ask_before_unload: false,
        powerpaste_allow_local_images: true,

        image_advtab: true,
        relative_urls: true,
        external_filemanager_path:"/responsive_filemanager/filemanager/",
        filemanager_title:"File đã tải" ,
        external_plugins: { "responsivefilemanager" : "/responsive_filemanager/tinymce/plugins/responsivefilemanager/plugin.min.js"}
    });
}

function sleep(ms) {
    // return new Promise(resolve => setTimeout(resolve, ms));
    return new Promise(
        resolve => setTimeout(resolve, ms)
    );
}
/**
 * HTML Decode
 * @param input
 * @returns {string}
 */
function htmlDecode(input) {
    var doc = new DOMParser().parseFromString(input, "text/html");
    return doc.documentElement.textContent;
}
// set Iframe
function setGoogleMap(road, ward, district, province) {
    const roadIn = $(road).val();
    const wardIn = $(ward).find(":selected").val() ? " " + $(ward).find(":selected").html() : "";
    const districtIn = $(district).find(":selected").val() ? " " + $(district).find(":selected").html() + " " : "";
    const provinceIn = $(province).find(":selected").val() ? " " + $(province).find(":selected").html() + " " : "";
    const string_search = ` ${roadIn}${wardIn}${districtIn}${provinceIn}`;
    geocode({address: string_search}, '#map_latitude', '#map_longtitude')
}

function setIframeYoutube(element, iframe_id) {
    let link = $(element).val();
    $(element).siblings('#idErrorIframe').remove()
    if (link == undefined || link == '') {
        $(iframe_id).prop('src', 'https://www.youtube.com/embed/9FYXrs0jYfU')
        return;
    }
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=|\?v=)([^#&?]*).*/;
    if (link.match(regExp)) {
        link = link.replace('watch?v=', 'embed/');
        $(element).val(link)
        $(iframe_id).prop('src', link)
    } else {
        $(element).after("<span class='error-message-custom text-danger' id='idErrorIframe'>Đường dẫn không hợp lệ</span>")
    }
}

// get province by name
// old should check
function get_province_by_name(province_name, url, province_select_id) {
    // isClicked = true;
    return $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            province_name: province_name.trim()
        },
        success: function (data) {
            $(province_select_id).val(data['province']?.id);
            $(province_select_id).trigger('change');
            // isClicked = false
            return data;
        }
    });
}

// get province by name
function get_district_by_name(district_name, url, province_id, district_select_id) {
    // isClicked = true;
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
            // isClicked = false;
            return data;
        }
    });
}

// get province by name
function get_ward_by_name(ward_name, url, district_id, ward_select_id) {
    // isClicked = true;
    return $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {
            ward_name: ward_name.trim(),
            district_id
        },
        success: function (data) {
            let ward_id = data['ward'].id;
            // $(ward_select_id).val(ward_id);
            // $(ward_select_id).trigger('change');

            var temp = $(ward_select_id).val(ward_id);
            temp.change();
            //$(ward_select_id).val(ward_id).change();
            // isClicked = false;
            return data;
        }
    });
}

// get district
async function get_district(element, url, district_select_id, old_province = null, old = null) {
    var province_id = old_province || $(element).find(":selected").val();
    $(district_select_id).empty();
    $(district_select_id).append("<option selected value=''>Quận/huyện</option>");
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

//reset address khi ward thay doi
function reset_address(address) {
    $(address).empty();
    $(address).val('');
}

//load progess khi group thay doi
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

//load furniture khi group thay doi
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
 * get group by banner
 * @param elementBannerGroup
 * @param elementGroup
 * @param url
 * @param old
 */
function get_group_by_banner_group(elementBannerGroup, elementGroup, url,  old = null){
    $(elementGroup).empty();
    $(elementGroup).append("<option selected value='' disabled>Chuyên mục</option>");
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        data: {},
        success: function (data) {
            $.each(data['group'], function (index, value) {
                let selected = "";
                if (old != null) {
                    selected = old == value.id ? "selected" : ""
                }
                group_value = "<option value='" + value.id + "' " + selected + " >" + value.group_name + "</option>";
                $(elementGroup).append(group_value);
            });
        }
    });
}

/**
 * REMOVE FILE
 * @param id_input
 * @param index
 */
function removeFile(id_input, index) {
    const dt = new DataTransfer()
    const input = document.getElementById(id_input)
    const files = input.files
    for (let i = 0; i < files.length; i++) {
        const file = files[i]
        if (index !== i)
            dt.items.add(file) // here you exclude the file. thus removing it.
    }
    input.files = dt.files // Assign the updates list
}

/**
 * Get Content TINYMCE EDITOR
 * @param tinyMCEId
 * @param event
 */
function getTinyContent(tinyMCEId, event) {
    tinyMCE.get(tinyMCEId).on(event, function (e) {
        return this.getContent()
    });
}

/**
 * Get Content TINYMCE EDITOR (Without any HTML tags)
 * @param tinyMCEId
 * @param event
 * @param element_target
 * @param limit
 */
function getTinyContentWithoutHTML(tinyMCEId, event, element_target = null, limit = null) {
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

function getTinyWordCount(tinyMCEId, event, targetElement){
    tinyMCE.get(tinyMCEId).on(event, function (e) {
        $(targetElement).val(this.plugins.wordcount.getCount())
    });
}

/**
 * Preview from array input
 * @param inputTextFileElementId
 * @param previewElementId
 * @param prefix
 */
async function previewImgFromInputText(inputTextFileElementId, previewElementId, inputOrderElementId = null, prefix = '', old = null) {
    let imageList = [];
    let input = old != null ? htmlDecode(old) : $(inputTextFileElementId).val();
    if (input == '')
        return;
    try {
        imageList = JSON.parse(input);
    } catch (e) {
        imageList.push(input);
    }

    if (old != null) {
        $(inputTextFileElementId).val(input)
    }
    $(previewElementId).empty()
    // let index = imageList.length - 1;
    let index = 0;

    for (let image of imageList) {
        $(previewElementId).prepend(` <li class="col-md-4 position-relative pip mb-1" style="list-style: none">
              <img src="` + prefix + image + `" height="100px" width="100%">
              <span class="remove close remove-${index}" aria-label="Close" data-id='${index}'>×</span>
              </li>`)
        $(`.remove-${index}`).click(function () {
            numIndex = $(this).data('id')
            imageList = deleteItemFromArray(imageList, numIndex)
            $(inputTextFileElementId).val(imageList)
            previewImgFromInputText(inputTextFileElementId, previewElementId, inputOrderElementId, prefix).then(() => {
                if (inputOrderElementId != null)
                    updateOrderImage(previewElementId, inputOrderElementId)
            })
            $(this).parent(".pip").remove();
        });
        index++;
    }
}

/**
 * Delete Item From Array Keep Formatting
 * @param array
 * @param index
 * @returns {string|string}
 */
function deleteItemFromArray(array, index) {
    if (typeof array == 'string') array = JSON.parse(array)
    array = array.filter(item => item !== array[index])
    return array.length > 0 ? JSON.stringify(array) : ''
}

/**
 * Update Show Order of preview Image
 * @param previewElementId
 * @param outputElementId
 */
function updateOrderImage(previewElementId, outputElementId){
    let arrOrder = []
    $(previewElementId).find('.close').map((k,v) => arrOrder.push($(v).data('id')))
    arrOrder.length > 0 ? $(outputElementId).val(JSON.stringify(arrOrder)) : $(outputElementId).val('')
}

/**
 * Submit show items
 * @param event
 * @param elementSelectPaginate
 */
function submitPaginate(event, elementSelectPaginate){
    const uri = window.location.toString();
    const exist = uri.indexOf('?')
    const existItems = uri.indexOf('?items')
    const re = /([&\?]items=\d*$|items=\d&|[?&]items=\d(?=#))/
    exist > 0 && existItems < 0 ? window.location.href = uri.replace(re, '') + '&items=' + $(elementSelectPaginate).val() : window.location.href = uri.replace(re, '') + '?items=' + $(elementSelectPaginate).val()
}

/**
 * Add Parameter items when filter form submit
 * @param elementForm
 * @param elementSelectPaginate
 */
function appendPaginateOnSubmitForm(elementForm, elementSelectPaginate){
    $(elementForm).on("submit",function(e){
        $(this).append('<input type="hidden" name="items" value="'+ $(elementSelectPaginate).val() + '" /> ');
    });

}

/**
 * Limit length of input
 * @param element
 */
function maxLengthInput(element){

   if (element.value.length > element.maxLength) element.value = element.value.slice(0, element.maxLength);
}

/**
 * Init Select2
 * @param elementSelect
 */
function initSelect2(elementSelect){
    if ($(elementSelect).length) {
        $(elementSelect).select2({
            // theme: 'bootstrap4'
            language: {
                "noResults": function () {
                    return "Không tìm thấy";
                }
            },
            placeholder: $(this).data('placeholder')
        })
    }
}

/**
 * Init date range picker
 * @param elementInput
 * @param disabledDates
 */
function initDateRangePicker(elementInput, disabledDates = null){

    $(elementInput).daterangepicker({
        timePicker: true,
        autoUpdateInput: false,
        timePickerIncrement: 30,
        drops: 'auto',
        minDate:  moment(),
        locale: {
            format: 'DD/MM/YYYY hh:mm A',
            cancelLabel: 'Hủy',
            applyLabel: 'Xác nhận'
        },
        isInvalidDate: function(date) {
            if (disabledDates && disabledDates.includes(date.format('YYYY-MM-DD'))) {
                return true;
            }
        }
    })

    $(elementInput).on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY hh:mm A') + ' - ' + picker.endDate.format('DD/MM/YYYY hh:mm A'));
    });

    $(elementInput).on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
}

/**
 * Xóa / cuối cùng
 * @param the_url
 * @returns {*}
 * @constructor
 */
function RemoveLastDirectoryPartOf(the_url)
{
    var the_arr = the_url.split('/');
    the_arr.pop();
    return( the_arr.join('/') );
}

/**
 * Find Url by Href
 * @param url
 * @returns {*}
 */
function findUrl(url){
    while ($( 'a[href*="' + url + '"].nav-link' ).length == 0)
    {
      url = RemoveLastDirectoryPartOf(url);
    }
    return url;
}

/**
 * Before loaded page
 */
function beforeLoaded() {

    $(window).scroll(function () {
        sessionStorage.scrollTop = $(this).scrollTop();
        sessionStorage.currentPage = document.location.protocol +"//"+ document.location.hostname + document.location.pathname;
    });
    $(document).ready(function () {
        if (sessionStorage.scrollTop != "undefined") {
            let current_url = document.location.protocol +"//"+ document.location.hostname + document.location.pathname;
            if (current_url == sessionStorage.currentPage) {
                $(window).scrollTop(sessionStorage.scrollTop);
            }
        }
    });

}
