<link rel="stylesheet" href="{{asset('frontend/css/slick.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/plusb.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/responsive.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/responsiveb.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/responsiveh.css')}}">
<link rel="stylesheet" href="{{asset('system/css/project-view.css')}}">

<div class="project-comment p-4 pt-0">
    <div class="head">
        Để lại bình luận
        @if(\Illuminate\Support\Facades\Auth::guard('admin')->check() == false)
        @guest('web')
             <a href="#">(Đăng nhập để bình luận)</a> <i class="fas fa-info-circle"></i>
        @endguest
        @endif
        <div class="head-information">
            <span>Lưu ý:</span> Spam thông tin sản phẩm, bán sản phẩm khác hoặc có từ ngữ bị cấm trong nội dung bình luận sẽ bị khóa tài khoản vĩnh viễn tùy mức độ vi phạm mà không cần báo trước
        </div>
        <div class="head-arrow"></div>
    </div>
    <form action="#" id="comment-form" class="mb-3">
        @csrf
        <input type="hidden" name="project_id" value="{{$project_id}}">
        <textarea name="comment_content" id="comment-box" class="home-text" placeholder="Nhập bình luận..."></textarea>
        <small class="text-danger error-message-custom" id="msg_comment_content"></small>
        <input type="button" id="send-comment" class="send-home-text" value="Gửi bình luận">
    </form>
    <div class="comment-section">
        @forelse($comments as $item)
            <x-home.project.parent-comment :comment="$item"></x-home.project.parent-comment>
        @empty
        @endforelse
    </div>

{{--    <div class="pagination">--}}
{{--        <a href="#" class="prev">Trước</a>--}}
{{--        <span class="current">1</span>--}}
{{--        <em>|</em>--}}
{{--        <a href="#">2</a>--}}
{{--        <em>|</em>--}}
{{--        <a href="#">3</a>--}}
{{--        <em>|...</em>--}}
{{--        <a href="#">5</a>--}}
{{--        <a href="#" class="next">Sau</a>--}}
{{--    </div>--}}
    <!-- Pagniate thay thế -->
        {{ $comments->render('Admin.Layouts.Pagination') }}
</div>
<script>
    msgArray= []
    $(document).ready(function () {
        $('#send-comment').click(function () {
            msgArray.map((e) => $(e).empty())
            commentBox = $(this).parent().find('textarea#comment-box');
            $.ajax({
                url: '{{route('project.comment.store')}}',
                type: "POST",
                data: {
                    comment_content: commentBox.val(),
                    '_token': $(this).parent().find('input[name=_token]').val(),
                    'project_id': $(this).parent().find('input[name=project_id]').val(),
                },
                success: function (data) {
                    commentBox.val('').html('');
                    $('.comment-section').prepend($(data));
                    toastr.success("Bình luận thành công");
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                toastr.error("Vui lòng kiểm tra các trường");
                for(let i in jqXHR.responseJSON){
                    // toastr.error(jqXHR.responseJSON[i])
                    msgArray.push('#msg_'+ i);
                    $('#msg_'+ i).html(jqXHR.responseJSON[i])
                }
            });
        })
    })
</script>
<script>
    msgArray = []
    $(document).ready(function () {
        // reply
        msg_reppy_Array = []
        $(document).on('keydown', '.reply-box', function (e) {
            keyCode = e.which || e.keyCode;
            if (keyCode === 13 && !e.shiftKey) {
                e.preventDefault();
                msg_reppy_Array.map((e) => $(e).empty())
                parent_id = $(this).parent().find('input[name=parent_id]').val()
                replyBox = $(this)
                $.ajax({
                    url: '{{route('project.comment.store')}}',
                    type: "POST",
                    data: {
                        comment_content: replyBox.val(),
                        '_token': $(this).parent().find('input[name=_token]').val(),
                        'project_id': $(this).parent().find('input[name=project_id]').val(),
                        parent_id
                    },
                    success: function (data) {
                        replyBox.val('').html('')
                        $('.reply-section-frame-' + parent_id).prepend($(data));
                        toastr.success("Bình luận thành công");
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    toastr.error("Vui lòng kiểm tra các trường");
                    for (let i in jqXHR.responseJSON) {
                        // toastr.error(jqXHR.responseJSON[i])
                        msg_reppy_Array.push('#msg_reply_' + parent_id + '_' + i);
                        $('#msg_reply_' + parent_id + '_' + i).html(jqXHR.responseJSON[i])
                    }
                });
            }
        })

        // edit
        msg_edit_Array = []
        $(document).on('keydown', '.edit-box', function (e) {
            keyCode = e.which || e.keyCode;
            const that = this
            if (keyCode === 13 && !e.shiftKey) {
                e.preventDefault();
                msg_edit_Array.map((e) => $(e).empty())
                parent_id = $(this).parent().find('input[name=parent_id]').val()
                id_comment = $(this).parent().find('input[name=id]').val();
                editBox = $(this)
                $.ajax({
                    url: '{{route('project.comment.update')}}',
                    type: "POST",
                    data: {
                        comment_content: editBox.val(),
                        '_token': $(this).parent().find('input[name=_token]').val(),
                        'project_id': $(this).parent().find('input[name=project_id]').val(),
                        'id': id_comment,
                        parent_id
                    },
                    success: function (data) {
                        msg_edit_Array.map((e) => $(e).empty())
                        arr = editBox.parent().parent().children('.content')
                        $(arr[0]).html(editBox.val()).removeClass('hidden')
                        $(arr[1]).removeClass('active')

                        const block_edit = $(that).parents('.comment').find('.text-comment');
                        console.log('22', $(that).parents('.comment'))
                        console.log('dsadá', block_edit)
                        block_edit.hide()
                        toastr.success("Sửa bình luận thành công");
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    toastr.error("Vui lòng kiểm tra các trường");
                    for (let i in jqXHR.responseJSON) {
                        // toastr.error(jqXHR.responseJSON[i])
                        msg_edit_Array.push('#msg_edit_' + id_comment + '_' + i);
                        $('#msg_edit_' + id_comment + '_' + i).html(jqXHR.responseJSON[i])
                    }
                });
            }
        })

        // delete
        $(document).on('click', '.delete-comment', function (e) {
            e.preventDefault()
            let id = $(this).data('id');
            let elementRemove = $(this)
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Sau khi xóa tất cả các bình luận, các bình luận đã xóa sẽ chuyển vào thùng rác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{route('project.comment.delete', '')}}' + '/' + $(this).data('id'),
                        type: "GET",
                        data: {},
                        success: function (data) {
                            elementRemove.parents('.block-comment').remove()
                            toastr.success("Xóa luận thành công");
                            $(window.selectedComment).find('span').html(parseInt($(window.selectedComment).find('span').html()) - 1)
                        }
                    }).fail(function () {
                        toastr.error("Xóa luận thất bại");
                    })
                }
            });
        })
        // edit handle click
        $(document).on('click', '.edit-comment', function (e) {
            e.preventDefault();
            $(this).parents('.comment-parent').find('.text-comment').first().show()
        })
        $(document).on('click', '.edit-comment-child', function (e) {
            e.preventDefault();
            $(this).parents('.comment-child').find('.text-comment').first().show()
        })
        $(document).on('click', '.cancel-edit-comment-parent', function (e) {
            e.preventDefault();
            $(this).parents('.comment-parent').find('.text-comment').first().hide()
        })
        $(document).on('click', '.cancel-edit-comment-child', function (e) {
            e.preventDefault();
            $(this).parents('.comment-child').find('.text-comment').first().hide()
        })

        // reply handle click
        $(document).on('click', '.reply', function (e) {
            e.preventDefault();
            const block_reply = $(this).parents('.comment-item').find('.reply-form');
            block_reply.toggleClass('d-flex')
        })

        // like comment
        $("body").on("click", ".reaction .like_comment ", function (event) {
            event.preventDefault();
            let comment_id = $(this).data('comment_id');
            let select = $(this);
            $.ajax({
                type:'get',
                url :'{{route('admin.project.like-comment','')}}'+'/'+comment_id,
                success:function (data){
                    var num_like=  select.children().children('span.num_like').html();
                    if(data == "like"){
                        select.addClass("liked");
                        select.children().children('span.num_like').html(parseInt(num_like)+1);
                    }
                    else {
                        if (select.hasClass("liked")) {
                            select.removeClass("liked");
                        }
                        select.children().children('span.num_like').html(parseInt(num_like)-1);
                    }
                },
                error : function (error){
                    toastr.error(error.responseJSON);
                }
            })

        });
    })

</script>
