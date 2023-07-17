<div class="reply-section block-comment">
    <div class="avatar">
        <img src="{{\App\Helpers\Helper::get_path_user_image($comment->user->user_detail->image_url ?? null) ??
            \App\Helpers\Helper::get_path_admin_image($comment->admin->image_url ?? null)}}" alt="">
    </div>
    <div class="comment comment-child">
        <div class="action d-flex justify-content-end">
            <i class="fas fa-ellipsis-h text-gray" data-toggle="dropdown" role="button"></i>
            <div class="dropdown-menu">
                @if(( \Illuminate\Support\Facades\Auth::guard('admin')->check() && $comment->admin_id == \Illuminate\Support\Facades\Auth::guard('admin')->id() )
                    || (\Illuminate\Support\Facades\Auth::check() && $comment->user_id == \Illuminate\Support\Facades\Auth::id() ) )
                    <a class="dropdown-item edit-comment-child" href="#" data-id="{{$comment->id}}">Sửa</a>
                @endif
                @if(( \Illuminate\Support\Facades\Auth::guard('admin')->check() && $comment->admin_id == \Illuminate\Support\Facades\Auth::guard('admin')->id() )
                    || (\Illuminate\Support\Facades\Auth::check() && $comment->user_id == \Illuminate\Support\Facades\Auth::id() ) )
                    <a class="dropdown-item delete-comment" href="#" data-id="{{$comment->id}}">Xóa</a>
                @endif
            </div>
        </div>
        <div class="name">{{$comment->user_detail->fullname ?? $comment->admin->admin_fullname}}</div>
        <div class="date">Bình luận vào {{ \Carbon\Carbon::parse($comment->created_at)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s')}}</div>
        <div class="rate">
            @if($comment->user_id)
                @for($ii =1 ;$ii <= 5;$ii++)
                    @if($ii > $comment->star)
                        <i class="far fa-star"></i>
                    @else
                        <i class="fas fa-star"></i>
                    @endif
                @endfor
            @endif
        </div>
        <div class="content text-break">
            {{$comment->comment_content}}
        </div>
        <div class="mb-3 content text-comment">
            @csrf
            <input type="hidden" name="project_id" value="{{$comment->project_id }}">
            <input type="hidden" name="parent_id" value="{{$comment->id }}">
            <input type="hidden" name="id" value="{{$comment->id }}">
            <textarea name="comment_content" class="rep-home edit-box"
                      placeholder="Nhập bình luận...">{{$comment->comment_content}}</textarea>
            <small class="text-danger error-message-custom" id="msg_reply_{{$comment->id}}_comment_content"></small>
            <input type="submit" id="reply-comment">
            <span class="reply-note d-block">Nhấn enter để gửi, nhấn shift + enter để xuống dòng</span>
            <input type="button" class="btn btn-sm bg-danger cancel-edit-comment-child" value="Hủy">
        </div>
    </div>
</div>
