@extends('Admin.MailBox.MasterMail')
@section('ContentMail')
<section>
  <div id="message">
    <div class="header">
      <h1 class="page-title"><a class="icon circle-icon glyphicon glyphicon-chevron-left trigger-message-close"></a>{{$detail->mail_title}}<span class="grey"></span></h1>
      <p class="info-mail">Từ <a href="javascript:{}">
        @if($detail->object_type == 1)
        @foreach ($user as $item )
        @if($item->id ==$detail->created_by)
        <a href="javascript:{}">{{$item->username}}</a>
        @endif
        @endforeach
        @endif
        @if($detail->object_type == 0)
        @foreach ($admin as $item )
        @if($item->id ==$detail->created_by)
        {{$item->admin_username}}
        @endif
        @endforeach
        @endif
      </a> đến
      @if($detail->object_type == 1)
      Hệ thống
      @endif
      @if($detail->object_type == 0 && $detail->user_id == null)
      @foreach ($admin as $item )
      @if($item->id ==$detail->created_by)
      <a href="javascript:{}">{{$item->admin_username}} </a>
      @endif
      @endforeach
      @elseif($detail->object_type == 0 && $detail->user_id != null)
      @foreach ($user as $item )
      @if($item->id ==$detail->user_id)
      <a href="javascript:{}">{{$item->username}} </a>
      @endif
      @endforeach
      @endif
      , Ngày gửi <a href="javascript:{}">{{date('d/m/Y',$detail->send_time)}}</a> - {{date(' H:i',$detail->send_time)}}</p>
    </div>
    <div id="message-nano-wrapper" class="nano has-scrollbar">
      <div class="nano-content" tabindex="0"  style="right: -17px; ">
        <ul class="message-container">
          <li class="received">
            <div class="details">
              <div class="left">
                @if($detail->object_type == 1)
                @foreach ($user as $item )
                @if($item->id ==$detail->created_by)
                <a href="#">{{$item->username}}</a>
                @endif
                @endforeach
                @endif
                @if($detail->object_type == 0)
                @foreach ($admin as $item )
                @if($item->id ==$detail->created_by)
                {{$item->admin_username}}
                @endif
                @endforeach
                @endif
                <div class="arrow orange"></div>
                @if($detail->object_type == 1)
                Hệ thống
                @endif
                @if($detail->object_type == 0 && $detail->user_id != null)
                @foreach ($user as $user3 )
                @if($user3->id ==$detail->user_id)
                <a href="javascript:{}">{{$user3->username}} </a>
                @endif
                @endforeach
                @endif
              </div>
              <div class="right">{{date('d/m/Y H:i',$detail->send_time)}}</div>
            </div>
            <div class="message">
              <p>{!!$detail->mail_content!!}</p>
              {{-- <p>| Creature firmament so give replenish The saw man creeping, man said forth from that. Fruitful multiply lights air. Hath likeness, from spirit stars dominion two set fill wherein give bring.</p> --}}
              {{-- <p>| Gathering is. Lesser Set fruit subdue blessed let. Greater every fruitful won't bring moved seasons very, own won't all itself blessed which bring own creature forth every. Called sixth light.</p> --}}
            </div>
            <div class="tool-box"><a href="javascript:{}" class="circle-icon small glyphicon glyphicon-share-alt"></a><a href="javascript:{}" class="circle-icon small red-hover glyphicon glyphicon-remove"></a><a href="javascript:{}" class="circle-icon small red-hover glyphicon glyphicon-flag"></a></div>
          </li>
        </ul>
      </div>
      @foreach ($childDetail as $item )
      <div class="nano-content" tabindex="0" style="right: -17px;width: 100%">
        <ul class="message-container">
          <li class="received">
            <div class="details">
              <div class="left">
                @if($item->object_type ==1)
                @foreach ($user as $user1 )
                @if($user1->id ==$item->created_by)
                <a href="javascript:{}">{{$user1->username}}</a>
                @endif
                @endforeach
                @endif
                @if($item->object_type ==0)
                @foreach ($admin as $admin1 )
                @if($admin1->id ==$item->created_by)
                {{$admin1->admin_username}}
                @endif
                @endforeach
                @endif
                <div class="arrow orange"></div>
                @if($item->object_type == 1)
                Hệ thống
                @endif
                @if($item->object_type == 0 && $item->user_id != null)
                @foreach ($user as $user2 )
                @if($user2->id ==$item->user_id)
                <a href="javascript:{}">{{$user2->username}} </a>
                @endif
                @endforeach
                @endif
              </div>
              <div class="right">{{date('d/m/Y H:i',$item->send_time)}}</div>
            </div>
            <div class="message">
              <p>{!!$item->mail_content!!}</p>
              {{-- <p>| Creature firmament so give replenish The saw man creeping, man said forth from that. Fruitful multiply lights air. Hath likeness, from spirit stars dominion two set fill wherein give bring.</p> --}}
              {{-- <p>| Gathering is. Lesser Set fruit subdue blessed let. Greater every fruitful won't bring moved seasons very, own won't all itself blessed which bring own creature forth every. Called sixth light.</p> --}}
            </div>
            <div class="tool-box"><a href="javascript:{}" class="circle-icon small glyphicon glyphicon-share-alt"></a><a href="javascript:{}" class="circle-icon small red-hover glyphicon glyphicon-remove"></a><a href="javascript:{}" class="circle-icon small red-hover glyphicon glyphicon-flag"></a></div>
          </li>
        </ul>
      </div>
      @endforeach
      <div class="nano-pane">
        <div class="nano-slider" style="height: 20px; transform: translate(0px, 0px);"></div>
      </div>

    </div>
    <form action="{{route('admin.mail.reply',[$detail->id])}}" method="post">
      @csrf
    <div class="px-4 pb-3">
      <label>Trả lời thư</label>
      <textarea class="js-admin-tiny-textarea form-control ml-1" name="reply" style="width: 100%;border-radius:0;"></textarea>
    </div>

    <div class="row m-0 pb-3">
      <div class="col-6">
        <a style="border-radius: 0" href="{{route('admin.mailbox.list')}}" class="btn btn-primary float-right">
        Quay lại
      </a>
      </div>
      <div class="col-6">
        <button type="submit" class="btn btn-dark "  style="border-radius: 0">Trả lời</button>
      </div>
    </div>
  </form>
  </div>
</section>
@endsection
@section('StyleMail')
<style>
  #message img{
      width: 100%;
      display: block !important;
  }
  #message{
    width: 100%;
  }
  #message .header .page-title {
    display: block;
    float: none;
    margin-bottom: 20px;
    margin-left: 30px;
    margin-top: 10px;
  }
  #message .info-mail{
      margin-left: 30px;
  }
  #message #message-nano-wrapper {
    /* position: absolute; */
    /* top: 165px; */
    /* bottom: 0;
    height: auto;
    left: 0;
    right: 0; */
    width: auto;
  }
  .message-container {
    padding: 0 30px;
  }
  #message .message-container li {
    padding: 25px;
    border: 1px solid rgba(0, 0, 0, 0.15);
    background: #FFF;
    margin: 0 0 30px 0;
    position: relative;
    list-style-type:none
  }
  #message .message-container li .details {
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    overflow: hidden;
  }
  #message .message-container li .details .left {
    float: left;
    font-weight: 600;
    color: #888;
    transition-duration: 0.3s;
  }
  #message .message-container li .details .left .arrow {
    display: inline-block;
    position: relative;
    height: 2px;
    width: 20px;
    background: rgba(0, 0, 0, 0.15);
    vertical-align: top;
    margin-top: 12px;
    margin: 12px 20px 0 15px;
    border: 0px solid rgba(0, 0, 0, 0.15);
    transition-duration: 0.3s;
  }
  #message .message-container li .details .left .arrow:after {
    position: absolute;
    top: -4px;
    left: 100%;
    height: 0;
    width: 0;
    border: inherit;
    border-width: 7px;
    border-style: solid;
    content: '';
    border-right: 0;
    border-top-color: transparent;
    border-bottom-color: transparent;
    border-top-width: 5px;
    border-bottom-width: 5px;
  }
  #message .message-container li .details .right {
    float: right;
    color: #999;
  }
  #message .message-container li .message {
    margin-bottom: 40px;
  }
  #message .message-container li .message * {
    max-width: 100%;
  }

  #message p {
    margin-bottom: 1em;
    margin-left: 20px;
  }
</style>
@endsection
