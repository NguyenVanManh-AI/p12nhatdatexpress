<div class="project-comment comment-area">
  <div class="comment-title flex-start flex-wrap mb-2">
    <h5 class="normal-title mr-2 mb-0">Để lại bình luận</h5>
    @guest('user')
      <div class="head mr-1">
        <a href="javascript:void(0);" class="link-light-cyan text-underline js-open-login">(Đăng nhập để bình luận)</a>
      </div>
    @endguest
    <span class="comment-title__notice text-dark-blue position-relative flex-1 js-need-toggle-active should-toggle-active is-active">
      <span class="comment-title__notice-icon icon-squad-2 flex-inline-center js-toggle-active is-on">
        <i class="fas fa-exclamation-circle"></i>
      </span>
      <span class="comment-title__notice-box text-dark js-toggle-area active">
        <u class="d-inline-block mr-1">Lưu ý: </u> Spam thông tin sản phẩm, bán sản phẩm khác hoặc có từ ngữ bị cấm trong nội dung bình luận sẽ bị khóa tài khoản vĩnh viễn tùy mức độ vi phạm mà không cần báo trước
        <span class="comment-title__notice-close icon-squad-2 flex-inline-center hover-opacity js-toggle-active is-on">
          <i class="fas fa-times"></i>
        </span>
      </span>
    </span>
  </div>

  <div id="comment-form">
    <textarea name="comment" id="comment-box" class="home-text" placeholder="Nhập bình luận..."></textarea>
    <input type="button" class="@auth('user') send-home-text @else js-open-login @endauth" value="Gửi bình luận">
  </div>

  <div class="comment-section">
    @foreach($comments as $comment)
      <div class="comment-item">
        <div class="avatar">
          <img src="{{ asset(data_get($comment, 'user.user_detail.avatar', 'frontend/images/personal-logo.png')) }}" alt="">
        </div>
        @php
          $authUser = App\Models\User::find(Auth::guard('user')->id()) ?? null;
        @endphp
        <div class="comment">
          <div class="group-report">
            <div class="name">
              <a href="{{ data_get($comment, 'user.user_code') ? route('trang-ca-nhan.dong-thoi-gian', data_get($comment, 'user.user_code')) : 'javascript:void(0);' }}">{{ data_get($comment->user, 'user_detail.fullname', '') }}</a>
            </div>

            <div class="button-report cursor-pointer">
              <span><i class="fas fa-ellipsis-h"></i></span>
              <div class="report-main">
                <ul>
                  @if($comment->user_id == auth()->guard('user')->id())
                    <li class="hover-bg-gray">
                      <button data-id="{{ $comment->id }}" class="border-0 bg-white hover-bg-gray w-100 js-show-edit-comment">Chỉnh
                        sửa</button>
                    </li>
                    <li class="hover-bg-gray">
                      <form class="js-delete-comment" action="" data-id="{{ $comment->id }}" method="post">
                        <button class="border-0 bg-white hover-bg-gray w-100" type="submit">Xoá</button>
                      </form>
                    </li>
                  @endif
                  <li class="hover-bg-gray">
                    <a class="report_comment" data-comment_id="{{ $comment->id }}" href="javascript:void(0)">Báo
                      cáo</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="date">Bình luận vào {{ \App\Helpers\Helper::get_time_to($comment->created_at) }}</div>
          <div class="rate">
            <x-common.color-star :stars="$comment->star" type="icon" class="yellow star-1x"/>
          </div>
          <div class="content text-break">
            {{ $comment->comment_content }}
          </div>
          @if($comment->user_id == auth()->guard('user')->id())
            <form action="{{ route("home.${detailType}.comments.update", $comment->id) }}" method="post"
              class="js-update-comment" id="form-edit-content{{ $comment->id }}">
              <textarea name="comment_content" placeholder="Nhập bình luận..." class="edit-comment">{{ $comment->comment_content }}</textarea>
              <button type="submit" class="btn-edit-content">Submit</button>
            </form>
          @endif
          <div class="flex-between">
            <div>
              <div class="reaction justify-content-start {{ $comment->children_count ? '' : 'd-none' }}">
                <div data-id={{ $comment->id }} class="show-hide-reply user-select-none ml-0">
                  <i class="far fa-comment-dots"></i>
                  Phản hồi (<span class="count_reply">{{ $comment->children_count }}</span>)
                </div>
              </div>
            </div>
            <div class="reaction">
              @if($authUser)
                <div class="like js-like-comment {{ $comment->likes->contains($authUser) ? 'liked' : '' }}" data-comment_id="{{ $comment->id }}">
                  <i class="far fa-thumbs-up"></i>
                  Hữu ích
                  (<span class="js-num-like">{{ $comment->likes_count }}</span>)
                </div>
              @else
                <div class="like js-open-login">
                  <i class="far fa-thumbs-up"></i>
                  Hữu ích
                  (<span class="js-num-like">{{ $comment->likes_count }}</span>)
                </div>
              @endif
              <div class="reply user-select-none">
                <i class="far fa-comment-dots"></i>
                Trả lời
              </div>
            </div>
          </div>

          <div class="list-reply" style="display:none">
            @if ($comment->children->count() > 0)
              @foreach ($comment->children as $childComment)
                <div class="reply-section">
                  <div class="avatar">
                    <img src="{{ asset(data_get($childComment, 'user.user_detail.avatar', 'frontend/images/personal-logo.png')) }}" alt="">
                  </div>
                  <div class="comment">
                    <div class="group-report">
                      <div class="name">{{ data_get($childComment, 'user.user_detail.fullname', '') }}</div>
                      <div class="button-report cursor-pointer">
                        <span><i class="fas fa-ellipsis-h"></i></span>
                        <div class="report-main">
                          <ul>
                            @if($childComment->user_id == auth()->guard('user')->id())
                              <li class="hover-bg-gray">
                                <button data-id="{{ $childComment->id }}"
                                  class="border-0 bg-white hover-bg-gray w-100 js-show-edit-comment">Chỉnh sửa</button>
                              </li>
                              <li class="hover-bg-gray">
                                <form class="js-delete-comment" action="" data-id="{{ $childComment->id }}" method="post">
                                  <button class="border-0 bg-white hover-bg-gray w-100" type="submit">Xoá</button>
                                </form>
                              </li>
                            @endif
                            <li class="hover-bg-gray">
                              <a class="report_comment" data-comment_id="{{ $childComment->id }}"
                                href="javascript:void(0);">Báo cáo</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="date">Bình luận vào {{ \App\Helpers\Helper::get_time_to($childComment->created_at) }}
                    </div>
                    <div class="content">
                      {{ $childComment->comment_content }}
                    </div>
                    @if($childComment->user_id == auth()->guard('user')->id())
                      <form action="{{ route("home.${detailType}.comments.update", $childComment->id) }}" method="post"
                        class="js-update-comment" id="form-edit-content{{ $childComment->id }}">
                        <textarea name="comment_content" placeholder="Nhập bình luận..." class="edit-comment">{{ $childComment->comment_content }}</textarea>
                        <button type="submit" class="btn-edit-content">Submit</button>
                      </form>
                    @endif
                  </div>
                </div>
              @endforeach
            @endif
          </div>
        </div>
        @if($authUser)
          <div class="reply-form mt-2">
            <div class="avatar">
              <img src="{{ asset(data_get($authUser, 'user_detail.avatar', 'frontend/images/personal-logo.png')) }}" alt="">
            </div>
            <div class="comment">
              <div class="name">{{ data_get($authUser, 'user_detail.fullname', '') }}</div>
              <form action="">
                <textarea data-comment_id="{{ $comment->id }}" placeholder="Nhập bình luận..." class="rep-home"></textarea>
                <input type="submit" class="button_reply_comment">
              </form>
              <span class="reply-note">Nhấn enter để gửi, nhấn shift + enter để xuống dòng</span>
            </div>
          </div>
        @else
          <div class="reply-form mt-2">
            <a href="javascript:void(0);" class="link-light-cyan js-open-login"><span class="text-underline">Đăng nhập để trả lời bình luận</span></a>
          </div>
        @endif
      </div>
    @endforeach
  </div>
  {{ $comments->render('Home.Layouts.CommentPagination') }}
</div>
