

// hiển thị form reply
$('body').on('click','.list-comment .comment-item .comment-act .reply',function() {
    $(this).parents('.comment-right').children(".comment-bottom-1").css('display', 'block');
    $(this).parents(".comment-bottom").siblings(".comment-bottom-1").find(".box-comment-1").focus();
});
// reply comment
$("body").on('keypress', 'textarea.box-comment-1', function(e) {
    if (e.which == 13) {
        e.preventDefault();
        var url_Data = $(this).data('url');
        var token = $(this).data('token');
        var comment_id = $(this).data('comment_id');
        var hi = $(this).val();
        if (hi != "") {
            $(this).val('');
            var formData = new FormData();
            formData.append('content_comment', hi);
            formData.append('_token', token);
            formData.append('comment_id', comment_id);
            var select =$(this).parents(".comment-right").children(".comment-bottom").children(".comment-reply");
            $.ajax({
                type: 'post',
                url: url_Data,
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    showMessage(data)

                    select.addClass('show');
                    select.append(
                        `<div class="comment-item d-flex">
                                            <div class="comment-left">
                                                <div class="avatar">
                                                    <a href="/`+data['avatar']+`">
                                            <img src="/`+data['avatar']+`" alt="">
                                                     </a>
                                                </div>
                                            </div>
                                            <div class="comment-right">
                                                <div class="comment-top">
                                                    <div class="comment-user">
                                                        <a href="#">` + data['fullname'] + `</a>
                                                    </div>
                                                    <div class="comment-content text-break">
                                                           ` + data['comment']['comment_content'] + `
                                                    </div>
                                                    <div class="comment-more">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </div>
                                                    <div style="display: none" class="count-reaction">
                                                        <i class="fas fa-thumbs-up"></i> <span>0</span>
                                                    </div>
                                                </div>
                                                <div class="comment-bottom comment_`+data['comment']['id']+`">
                                                    <div class="comment-act mt-1">
                                                        <span class="like like_comment" data-comment_id="`+data['comment']['id']+`">Thích</span>•<span class="reply">Trả lời</span>•<span class="time-cm">` + data['date'] + `</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`);

                },
                error: function (error) {
                    showError(error)
                    // toastr.error(error.responseJSON);
                }

            });
        }
    }
});
// like unlike comment rating
$('body').on('click','.reply-comment .like',function(e) {
    var id = $(this).data('id');
    var url = $(this).data('url');
    var selected = $(this);
    var like =  $(this).children('.count_like').html();
    like = parseInt(like);
    $.ajax({
        type:'get',
        url:url,
        success:function (data){
            if(data == 'like'){
                selected.addClass('active');
                selected.children('.count_like').empty();
                selected.children('.count_like').append(like+1);
            }
            else{
                if(selected.hasClass('active')) {
                    selected.removeClass('active');
                    selected.children('.count_like').empty();
                    selected.children('.count_like').append(like - 1);
                }
            }
        },
        error : function(error){
            toastr.error(error.responseJSON);
        }
    });
    e.preventDefault();
});
// hiển thị form trả lời
$('body').on('click','.reply-elu', function(n) {
    n.preventDefault();
    $(this).parents(".reply-comment").siblings('.form-comment-content-reply').css('display', 'block');
});
