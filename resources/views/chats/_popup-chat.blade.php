<?php
  $activeOtherUser = $conversation->getOtherUserChat($actionUser);
?>

@if($conversation)
  @if($activeOtherUser && $activeOtherUser->getAvatarUrl())
    <span class="mini-icon">
      <x-user.avatar
        width="60"
        height="60"
        rounded="30"
        :is-fancy-box="false"
        avatar="{{ $activeOtherUser->getAvatarUrl() }}"
      />
    </span>
  @else
    <span class="mini-icon">
      <i class="fas fa-comments"></i>
    </span>
  @endif

  <div class="support-popup-chat-box d-flex flex-column" data-token="{{ $conversation->token }}">
    <div class="popup-chat__header bg-main border-bottom py-2 px-3 flex-start">
      <div class="popup-chat__header-user flex-between w-100">
        <div class="flex-start flex-1">
          @if($activeOtherUser && $activeOtherUser->getAvatarUrl())
            <div class="popup-chat__header-avatar mr-2 position-relative">
              <x-user.avatar
                width="40"
                height="40"
                rounded="20"
                :is-fancy-box="false"
                avatar="{{ $activeOtherUser->getAvatarUrl() }}"
              />
              <span class="status online"></span>
            </div>
          @endif
          <span class="popup-chat__header-user-name flex-1 c-w-1 text-white text-ellipsis mr-2">
            @if($activeOtherUser)
              {{ $activeOtherUser->getChatName() }}
            @elseif($conversation->isSupport())
              Hỗ trợ
            @endif
          </span>
        </div>

        <div class="popup-chat__header-actions flex-start">
          <a href="javascript:void(0);" class="popup-chat__actions-icon text-white js-minimum-chat mr-2">
            <i class="fas fa-minus"></i>
          </a>
          <a href="javascript:void(0);" class="popup-chat__actions-icon text-white js-close-chat">
            <i class="fas fa-times"></i>
          </a>
        </div>

        {{-- <div class="popup-chat__header-detail">
          <div class="content conversation">
            <div class="openConversation">
              <span class="popup-chat__header-user-name text-ellipsis">
                {{ $activeOtherUser ? $activeOtherUser->getChatName() : '' }}
              </span>
            </div>
          </div>

          <div class="conversationActions">
            <span class="itemIcon unreadMessage {{ $conversation->getUnreadMessage(auth('user')->user()->id) ? '' : 'hide' }}">
              {{ $conversation->getUnreadMessage(auth('user')->user()->id) }}
            </span>

            <div class="itemIcon actionIcon settingsIcon">
              <a href="javascript:void(0)" class="openDropdown" title="{{ __('Settings') }}">
                <i class="fas fa-ellipsis-h"></i>
              </a>
              @include('chats._toggle-menu-chat')
            </div>

            <a href="#" class="itemIcon closePopupChat" title="{{ __('Close') }}">
              <i class="fas fa-times"></i>
            </a>
          </div>
        </div> --}}
      </div>
    </div>

    <div class="active-chat-box flex-1 {{ $conversation->isEnded() ? 'is-ended-chat' : '' }}  {{ $conversation->isSpammed() ? 'is-blocked-chat' : '' }}">
      <div class="show-messages-box" data-token="{{ $conversation->token }}" data-current-page="1" data-receiver-id="{{ $conversation->admin_id }}" data-is-support="{{ $conversation->is_support }}">
        @include('chats._message-chatbox', [
          'actionUser' => $actionUser,
          'chatTypes' => $chatTypes ?? [],
          'activeOtherUser' => $activeOtherUser
        ])
      </div>
    </div>
  </div>
@endif
