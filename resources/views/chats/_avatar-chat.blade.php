<div class="avatar">
  @if(\Auth::user()->hasPermission('Supporter'))
    @if($skyModel)
      <a class="openProfile" href="{{ route('models.show', $skyModel->friendly_url) }}" target="_blank" title="{{ __('Show') }}">
        <img src="{{ $activeOtherUser ? $activeOtherUser->avatar : '' }}" alt="">
      </a>
    @else
      <img src="{{ $activeOtherUser ? $activeOtherUser->avatar : '' }}" alt="">
    @endif

    <span class="status {{ $activeOtherUser ? $activeOtherUser->available_status : '' }}"
      @if($activeOtherUser && !$activeOtherUser->is_online && $activeOtherUser->last_login_at) data-title="{{ __('Last seen') . ' ' . $activeOtherUser->last_login_at->diffForHumans() }}" @endif></span>
    @else
    @if ($activeConversation->isSupport())
      <img src="{{ config('services.support.chat_icon') }}" alt="">
      <span class="status online"></span>
    @else
      @if($skyModel)
        <a class="openProfile" href="{{ route('models.show', $skyModel->friendly_url) }}" target="_blank" title="{{ __('Show') }}">
          <img src="{{ $activeOtherUser->avatar ?? '' }}" alt="">
        </a>
      @else
        <img src="{{ $activeOtherUser->avatar ?? '' }}" alt="">
      @endif

      <span class="status {{ $activeOtherUser ? $activeOtherUser->available_status : '' }}"
        @if($activeOtherUser && !$activeOtherUser->is_online && $activeOtherUser->last_login_at) data-title="{{ __('Last seen') . ' ' . $activeOtherUser->last_login_at->diffForHumans() }}" @endif></span>
    @endif
  @endif
</div>