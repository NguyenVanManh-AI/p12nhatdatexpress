<footer id="footer-cntr">
  {!! $getConfig->footer !!}
  <div class="container-fluid">
    <div class="row footer">
      <div class="col-12 col-xl-3 px-15 information">
        <div class="logo-footer c-mb-10">
          <a href="#"><img class="lazy" data-src="{{ asset($getConfig->logo1) }}"></a>
        </div>
        <div class="content-footer">
          {!! nl2br($getConfig->text_footer) !!}
        </div>
      </div>
      <div class="col-12 col-lg-4 col-xl-2 quick-link">
        <h4 class="widget-title">Đường dẫn nhanh</h4>
        <ul class="log">
          @if (auth()->guard('user')->check())
            <li>
              <a href="{{ route('user.personal-info') }}" class="text-white">
                <img src="/images/icon/tick.png">
                <span>Tài khoản</span>
              </a>
            </li>
          @else
            <li>
              <a href="#" class="js-open-register-modal text-white">
                <img src="/images/icon/tick.png">
                <span>Đăng ký</span>
              </a>
            </li>
            <li>
              <a href="#" class="text-white" data-toggle="modal" data-target="#login-modal">
                <img src="/images/icon/tick.png">
                <span>Đăng nhập</span>
              </a>
            </li>
          @endif
          <li>
            <a href="/nha-dat-cho-thue" class="text-white">
              <img src="/images/icon/tick.png">
              <span>Nhà đất cho thuê</span>
            </a>
          </li>
          <li>
            <a href="/nha-dat-ban" class="text-white">
              <img src="/images/icon/tick.png">
              <span>Nhà đất bán</span>
            </a>
          </li>
          <li>
            <a href="/danh-ba/chuyen-vien-tu-van" class="text-white">
              <img src="/images/icon/tick.png">
              <span>Danh bạ</span>
            </a>
          </li>
          @if (auth()->guard('user')->check())
            <li>
              <a href="{{ route('user.add-classified', 'nha-dat-ban') }}" class="text-white">
                <img src="/images/icon/tick.png">
                <span>Đăng tin</span>
              </a>
            </li>
          @else
            <li>
              <a href="#" class="js-open-add-classified-modal text-white">
                <img src="/images/icon/tick.png">
                <span>Đăng tin</span>
              </a>
            </li>
          @endif
        </ul>
      </div>
      <div class="col-12 col-lg-4 px-15 address">
        <h4 class="widget-title">Công ty cpđt & công nghệ nhà đất express</h4>
        <ul class="c-mb-10">
          @foreach (preg_split("/\r\n|\n|\r/", $getConfig->info_company) as $companyInfo)
            <li>
              @switch($loop->index)
                @case(0)
                  <i class="fas fa-map-marker-alt"></i>
                @break
                @case(1)
                  <i class="fas fa-phone-alt"></i>
                @break
                @case(2)
                  <i class="far fa-envelope"></i>
                @break
                @case(3)
                  <i class="fas fa-globe"></i>
                @break
                @default
              @endswitch
              <span>{{ trim($companyInfo) }}</span>
            </li>
          @endforeach
        </ul>
        <div>
          <ul class="flex-start">
            <li class="mr-3">
              <a href="{{ $getConfig->facebook }}">
                <img src="{{ asset('frontend/images/facebook.png') }}">
              </a>
            </li>
            <li class="mr-3">
              <a href="{{ $getConfig->youtube }}">
                <img src="{{ asset('frontend/images/Youtube.png') }}">
              </a>
            </li>
            <li class="mr-3">
              <a href="{{ $getConfig->linkedlin }}">
                <img style="height: 50px" src="{{ asset('frontend/images/Logotikktok.png') }}">
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-12 col-lg-4 col-xl-3 px-15 contact-box">
        <h4 class="widget-title">Nhận bảng tin Express</h4>
        <div class="contact-form relative c-mb-30">
          <form class="form-search rounded">
            <input type="text" name="s" class="form-control search-input" placeholder="Nhập email của bạn">
            <button class="btn btn-blue btn-submit btn-register-footer">
              Đăng ký
            </button>
          </form>
        </div>
        <div class="flex-between c-mb-15">
          <a href="{{ $getConfig->apple_store }}">
            <img src="/images/icon/appstore.png">
          </a>
          <a href="{{ $getConfig->ch_play }}">
            <img src="/images/icon/google-play.png">
          </a>
        </div>
        <div class="flex-between contact-logo">
          <img class="lazy" data-src="{{ asset($getConfig->logo2) }}">
          <img class="lazy" data-src="{{ asset($getConfig->logo3) }}">
        </div>
      </div>
    </div>
  </div>
  <div class="center-footer c-mb-35">
    <div class="text-center">
      {!! nl2br($getConfig->introduce) !!}
    </div>
  </div>
  <div class="c-py-15 text-center border-y-white">
    <div class="container-fluid">
      <ul class="flex-between flex-wrap">
        @foreach ($getStaticMenu as $item)
          <li class="text-center c-m-10">
            <a class="text-white" href="/{{ $item->page_url }}">
              <i class="fas fa-chevron-right fs-13 c-mr-10"></i>
              <span>{{ $item->page_title }}</span>
            </a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
  <div class="copy-right text-center">
    <p class="mb-0">Copyright @ {{ now()->year }} Nhadatexpress - All rights reserved.</p>
  </div>
</footer>
