function resetForm(){
    document.getElementById("add-news-promotion").reset();
    $('#blah').attr("src","/system/image/upload-file.png");
}




$("#add-news-promotion").validate({
    rules: {
        news_title: {
            required : true
        },
        news_description: {
            required : true
        },
        news_content: {
            required : true
        },
    },

    messages: {
        news_title: {
            required: "Hãy nhập tiêu đề bài viết"
        },
        news_description: {
            required: "Hãy nhập mô tả bài viết"
        },
        news_content: {
            required: "Hãy nhập nội dung bài viết"
        },
    },

    submitHandler: function(form) {
        var content = tinyMCE.activeEditor.getContent();
        if (content === "" || content === null) {
            $("#editerInput").html('<label class="error" for="news_description">Hãy nhập nội dung</label>');
            
        } else {
            $("#editerInput").html("");
            form.submit();
        }     
    }
});