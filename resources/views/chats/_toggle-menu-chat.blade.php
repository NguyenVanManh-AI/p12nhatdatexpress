<?php $activeOtherUser = $activeOtherUser ?? null ?>

@if($activeOtherUser)
<div class="toggle-menu-chat">
  <ul class="menu-chat">
    <li class="menu-chat__item">
      <a href="javascript:void(0)" class="delete-conversation">
        Xóa tin nhắn
      </a>
    </li>
  </ul>
</div>
@endif