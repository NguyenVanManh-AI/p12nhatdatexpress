//load furniture khi group thay doi
function get_noithat(element, url, furniture_select_id, old_group = null, old = null,hide) {
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
                // $(furniture_select_id).siblings().find('span.required').hide()
                var furniture = "<option value=''>Không khả dụng</option>";
                $(furniture_select_id).append(furniture);
                // alert('dsa');
                    $(hide).prop('disabled',true);
                return;
            }
            $(furniture_select_id).siblings().find('span.required').show()
            $(furniture_select_id).html('');
            $.each(data['furniture'], function (index, value) {
                let selected = ""
                if (old != null) {
                    selected = old == value.id ? "selected" : ""
                }
                furniture = "<option value='" + value.id + "' " + selected + ">" + value.furniture_name + "</option>";
                $(furniture_select_id).append(furniture);
            });
            $(hide).prop('disabled',false);
            // $(show).show();
            // $(hidden).hide();
        }

    });
}
//load progess khi group thay doi
function get_tinhtrang(element, url, progress_select_id, old_group = null, old = null) {
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
            $(progress_select_id).html('');
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

function push_image_to_array(arrayId,image){
    let imageList =[];
    try {
        imageList = JSON.parse($(arrayId).val());
    } catch (e) {
        imageList.push(arrayId.val());

    }
    imageList.push(image);

      $(arrayId).val(JSON.stringify(imageList,false));

}


/**
 * Preview from array input
 * @param inputTextFileElementId
 * @param previewElementId
 * @param prefix
 */
async function classified_previewImgFromInputText(inputTextFileElementId, previewElementId, inputOrderElementId = null, prefix = '', old = null) {

    let imageList = [];
    $(previewElementId).filter('.slick-initialized').slick('unslick');
    $(previewElementId).empty();
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

    // let index = imageList.length - 1;
    let index = 0;
    for (let image of imageList) {
        $(previewElementId).append(`<div class="img-group remove remove-${index}" data-id='${index}' style="width: 100px;height: 100px; display: inline-block;">
           <img style="width: 100%" src="`+prefix+ image + `">
           <i class="fas  fa-times-circle " ></i>
           </div>`);
        $(`.remove-${index}`).click(function () {
            numIndex = $(this).data('id')

            imageList = deleteItemFromArray(imageList, numIndex)
            $(inputTextFileElementId).val(imageList)
            classified_previewImgFromInputText(inputTextFileElementId, previewElementId, inputOrderElementId, prefix).then(() => {
                if (inputOrderElementId != null)
                    updateOrderImage(previewElementId, inputOrderElementId)
            })
            $(this).parent(".pip").remove();
        });
        index++;
    }

    $(previewElementId).slick({
            slidesToShow: 4,

            slidesToScroll: 1,

            arrows: true,

            infinite: true,

            autoplay: true,

            autoplaySpeed: 2000,

            prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',

            nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
        }
    );


}

