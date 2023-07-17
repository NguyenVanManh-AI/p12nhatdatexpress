<input type="hidden" name="user_last_read_id" value="{{ $conversation->getLastMessageOfUser($actionUser) ? $conversation->getLastMessageOfUser($actionUser)->id : null }}">

<div class="load-more">
  <div class="loading" style="display: none;">
    <div class="loading-box text-center">
      <i class="fa-spin fas fa-sync-alt fa-2x"></i>
    </div>
  </div>
</div>

<x-common.loading class="active-chat-box__load-more-messages inner inner-block small" />

<div class="list-message">
  @if ($conversationMessages)
    @foreach ($conversationMessages->reverse() as $message)
      @if($actionUser)
        <?php $nextMessage = $conversationMessages->get(count($conversationMessages) - $loop->index - 2) ?>
        {{-- $loop->index + 1 --}}
        <div class="box-message {{ $message->isSelf($actionUser) ? 'self' : '' }} {{ $message->type }}" data-id="{{ $message->id }}">
          @if($message->senderable && !$message->isSelf($actionUser)
            && (!$nextMessage || !(data_get($nextMessage->senderable, 'channel_token') === data_get($message->senderable, 'channel_token') && $nextMessage->senderable_id === $message->senderable_id))
          )
            <div class="avatar" data-title="{{ $message->senderable->getChatName() }}">
              <img src="{{ $message->senderable->getAvatarUrl() }}" alt="">
            </div>
          @endif
          <div class="content" data-time="{{ $message->created_at->format('m/d/Y H:i') }}">
            <div class="message">{{ $message->message }}</div>
          </div>
          @if($message->isSelf($actionUser) && $conversation->getOtherUserLastReadMessage($actionUser)
            && $conversation->getOtherUserLastReadMessage($actionUser)->id == $message->id)
            <div class="message-seen">
              Đã xem <i class="fas fa-check"></i>
            </div>
          @endif
        </div>
      @endif
    @endforeach
  @endif
</div>

<div class="box-message active-chat-box__typing d-none">
  @if($activeOtherUser && $activeOtherUser->getAvatarUrl())
  <div class="avatar" data-title="{{ $activeOtherUser->getChatName() }}">
    <img src="{{ $activeOtherUser->getAvatarUrl() }}" alt="">
  </div>
  @endif
  <div class="content">
    <div class="message">
      <div id="wave">
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
      </div>
    </div>
  </div>
</div>

<span class="jump-to-last d-none" title="Mới nhất">
  <i class="fas fa-chevron-down"></i>
</span>

<div class="active-chat-box__footer">
  @if(isset($chatTypes) && count($chatTypes))
    <div class="active-chat__chat-type flex-start scrollable-vertical px-3 pt-2">
      @foreach ($chatTypes as $type)
        <div class="radio-with-button miw-content mr-2 mb-2 ">
          <input type="radio" name="chat_type" value="{{ $type }}" data-default="Tôi cần hỗ trợ {{ strtolower($type) }}">
          <button type="button" class="btn button-with-radio text-dark shadow active-chat__chat-type-button">
            {{ $type }}
          </button>
        </div>
      @endforeach
    </div>
  @endif

  @if(!$conversation->isEnded())
    <div class="chat-input send-message-box flex-start p-2 border-top">
      @if(!$actionUser->isSupport() && $conversation->isSpammed())
        <span class="">Vui lòng thử lại sau 24h hệ thống nhận thấy dấu hiệu spam</span>
      @else
        <input type="text" class="form-control send-message message flex-1 p-0 ml-2 border-0 mw-100" placeholder="Nhập nội dung..." autocomplete="off">
        
        {{-- <div class="input-icon attach hover-bg-gray">
          <div class="icon" title="Thêm file">
            <i class="fas fa-paperclip"></i>
          </div>
          <input type="file" name="attachment" accept="image/*,video/*">
        </div> --}}

        <a href="javascript:void(0)" class="input-icon send-icon hover-bg-gray" title="Gửi">
          <i class="fas fa-paper-plane"></i>
        </a>
      @endif
    </div>
  @endif

  <div class="active-chat__rating-box flex-between flex-wrap py-1 px-2 border-top position-relative">
    @if(!$actionUser->isSupport())
      <small class="mr-2">
        Đánh giá cuộc hội thoại
      </small>
    @endif

    <div class="active-chat__rating">
      @if(!$actionUser->isSupport())
        <x-common.color-star
          class="icon-size-22 {{ !$conversation->id || !$conversation->canChat() ? 'disable-rating' : '' }}"
          type="icon-action"
          action-input-name="rating"
          stars="{{ $conversation->rating }}"
        />
      @else
        <x-common.color-star
          class="icon-size-22"
          type="icon"
          stars="{{ $conversation->rating }}"
        />
      @endif
    </div>

    @if(!$conversation->isEnded() && $actionUser->isSupport())
      <form action="{{ route('admin.conversations.end', $conversation) }}" class="d-inline-block" method="POST">
        @csrf
        <a href="javascript:void(0);" class="link-red-flat submit-accept-alert fs-14 bold" data-action="kết thúc hội thoại" title="Kết thúc hội thoại">
          Kết thúc hội thoại
        </a>
      </form>
    @endif
    <x-common.loading class="inner small" />
  </div>
</div>
