$(document).ready(function () {
    // tinymce.init({
    //     selector: '.mytextarea',
    //     menubar:false,
    //     height: 430,
    //     language: 'vi_VN',
    //     plugins: [
    //         'a11ychecker advcode advlist anchor autolink codesample fullscreen help image  tinydrive',
    //         ' lists link media noneditable powerpaste preview',
    //         ' searchreplace table template visualblocks wordcount'
    //     ],
    //     toolbar:
    //         'undo redo | fontsizeselect | bold italic underline strikethrough| superscript subscript | hr | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent |  forecolor backcolor | link unlink anchor | image insertfile',
    //     toolbar_mode: 'floating',
    //     tinycomments_mode: 'embedded',
    //     tinycomments_author: 'Author name',
    //     autosave_ask_before_unload: false,
    //     powerpaste_allow_local_images: true,
    //     image_title: true,
    //     automatic_uploads: true,
    //     file_picker_types: 'image',
    //     file_picker_callback: function (cb, value, meta) {
    //         var input = document.createElement('input');
    //         input.setAttribute('type', 'file');
    //         input.setAttribute('accept', 'image/*');
    //         input.onchange = function () {
    //             var file = this.files[0];
    //             var reader = new FileReader();
    //             reader.onload = function () {
    //                 var id = 'blobid' + (new Date()).getTime();
    //                 var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
    //                 var base64 = reader.result.split(',')[1];
    //                 var blobInfo = blobCache.create(id, file, base64);
    //                 blobCache.add(blobInfo);
    //                 cb(blobInfo.blobUri(), { title: file.name });
    //             };
    //             reader.readAsDataURL(file);
    //         };
    //         input.click();
    //     },
    // });
    // $('.link-a').each(function () {
    //     if (window.location.href.indexOf($(this).attr('href')) != -1 && $(this).attr('href') != "#") {
    //         $(this).parentsUntil('item-account').addClass('activee').siblings().removeClass('activee');
    //     }
    // });

    // $('.link-a-child').each(function () {
    //     if (window.location.href.indexOf($(this).attr('href')) != -1 && $(this).attr('href') != "#") {
    //         $(this).parents('.item-menu-child').css({'background-color': '#21337f', 'color': '#fff'});
    //         $(this).css('color', '#fff');
    //     }
    // });

    //fancybox

    //copy link ref
    //copy link ref
    //copy link ref
    $('.copy-link-account').click(function() {
        var copyText = $('.link-need-copy').text();
        copyToClipboard(copyText);
        Swal.fire('Sao chép thành công');

    });

    //delete -alert
    $('.delete-alert').click(function (event){
        event.preventDefault();
        Swal.fire({
            title: 'Xác nhận xóa',
            text: "Nhấn đồng ý thì sẽ tiến hành xóa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = $(this).attr('href');
            }
        });
    });

    //revert alert
    $('.accept-alert').click(function (event){
        event.preventDefault();
        Swal.fire({
            title: 'Xác nhận thao tác',
            text: "Nhấn đồng ý thì sẽ tiến hành thao tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = $(this).attr('href');
            }
            else {
                return false;
            }
        });

    });
});

function open_current_popup(popup_id) {
    if (popup_id) {
        $(popup_id).modal('show');

    }
}



//copy text to clipboard
function copyToClipboard(text) {
    var sampleTextarea = document.createElement("textarea");
    document.body.appendChild(sampleTextarea);
    sampleTextarea.value = text; //save main text in it
    sampleTextarea.select(); //select textarea contenrs
    document.execCommand("copy");
    document.body.removeChild(sampleTextarea);
}

function intToDate(intTime) {
    let date = new Date(intTime);
    date = date.getDate() + '-' + date.getMonth() +'-' + date.getFullYear();
    return date;
}

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




