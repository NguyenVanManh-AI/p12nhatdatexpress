(() => {
    // tinymce
    tinymce.init({
        selector: '.js-admin-tiny-textarea',
        height: 400,
        language: 'vi_VN',
        // plugins: [
        //     'advlist anchor autolink codesample fullscreen help image tinydrive',
        //     'lists link media noneditable preview',
        //     'searchreplace table template visualblocks wordcount'
        // ],
        plugins: 'advlist anchor autolink codesample fullscreen help image lists link media noneditable preview searchreplace table template visualblocks wordcount',
        toolbar:
            'undo redo | fontsizeselect | bold italic underline strikethrough| superscript subscript | hr | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent |  forecolor backcolor | link unlink anchor | image | responsivefilemanager',
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

    tinymce.init({
        selector: '.js-tiny-textarea',
        height: 450,
        language: 'vi_VN',
        // plugins: [
        //     ' advlist anchor autolink codesample fullscreen help image tinydrive',
        //     ' lists link media noneditable preview',
        //     ' searchreplace table template visualblocks wordcount'
        // ],
        // plugins: [
        //     'advlist autolink link image lists charmap print preview hr anchor pagebreak',
        //     'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        //     'table emoticons template paste help'
        //   ],
        plugins: 'advlist anchor autolink codesample fullscreen help image lists link media noneditable preview searchreplace table template visualblocks wordcount',
        // should check toolbar for admin and user different
        toolbar:
            'undo redo | fontsizeselect | bold italic underline strikethrough| superscript subscript | hr | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent |  forecolor backcolor | link unlink anchor | image | responsivefilemanager',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        autosave_ask_before_unload: false,
        powerpaste_allow_local_images: true,
        image_advtab: true,
    });

    // fancybox
    if ($('.js-fancy-box').length) {
        $('.js-fancy-box').each(function() {
            $(this).fancybox()
        })
    }

    // init select2
    if ($('.select2').length) {
        $('.select2').each(function() {
            let _this = $(this)
            _this.select2({
                theme: _this.data('select2-theme') == 'bootstrap4' ? 'bootstrap4' : 'default', // default or bootstrap4
                maximumSelectionLength: _this.data('select2-max-length'),
                allowClear: true,
                placeholder: _this.data('placeholder') || '-- Tất cả --',
                language: 'vi',
                dropdownParent: _this.data('select2-parent') ? $(_this.data('select2-parent')) : null
            })
        })
    }
})()
