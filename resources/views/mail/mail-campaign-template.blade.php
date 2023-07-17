<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="vi">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="color-scheme" content="light">
  <meta name="supported-color-schemes" content="light">
  <title>{{ $title }}</title>
  <style type="text/css">
    a {
      text-decoration: none !important;
    }

    a:hover {
      text-decoration: underline !important;
    }

    .text-underline {
      text-decoration: underline !important;
    }

    #active_deposit {
      text-decoration: none !important;
      color: white;
      display: inline-block;
      width: 40%;
      height: 100%;
      background-color: rgb(8, 102, 196);
      padding: 20px 0px;
      font-size: 20px;
      font-weight: bold;
      border-radius: 10px;
    }

    .link {
      color: #0687d1 !important;
    }

    .link:hover {
      color: #036299 !important;
    }

    .link-cyan,
    .text-cyan {
      color: #00c1ef !important;
    }

    .link-cyan:hover {
      color: #04abd5 !important;
    }

    .link-red {
      color: #ff0000 !important;
    }

    .link-red:hover {
      color: #b20303 !important;
    }

    .link-warning {
      color: #ffc107 !important;
    }

    .link-warning:hover {
      color: #e2ac09 !important;
    }
  </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0; background-color: #f2f3f8;color:#414141;" leftmargin="0">
  <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
    style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
    <tr>
      <td>
        <table style="background-color: #f2f3f8;margin:0 auto;padding:4rem 4rem 0 4rem;" width="100%" border="0"
          align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"
                style="padding:2rem;min-height:400px;background:#fff; border-radius:3px;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                <tr>
                  <td>
                    {!! $message !!}
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          @if (isset($unsubscribeLink) && $unsubscribeLink)
            <tr>
              <td>
                <p style="text-align: center;">
                  Email này được gửi từ website <a href="{{ url('/') }}" class="link">nhadatexpres.vn</a> để hủy đăng ký và không nhận email <br>
                  này trong tương lai vui lòng click vào nút <a href="{{ $unsubscribeLink }}" class="link-red text-underline">hủy đăng ký</a>
                </p>
              </td>
            </tr>
          @else
            <tr>
              <td style="text-align:center;padding: 20px 0 10px">
                <a href="{{ url('/') }}" title="Trang chủ">
                  <img src="{{ asset(SystemConfig::logo()) }}" class="logo" alt="Nhà đất express Logo"
                    style="height: 50px;">
                </a>
              </td>
            </tr>
            <tr>
              <td style="text-align:center;">
                Được gửi bởi công ty CPĐT & Công nghệ <a href="{{ url('/') }}" class="link-warning"><strong>Nhà Đất Express</strong></a> <br>
                <span class="text-cyan">
                  <a href="{{ url('/') }}" class="link-cyan" title="Trang chủ">Trang chủ</a> |
                  <a href="{{ url('/nha-dat-ban') }}" class="link-cyan" title="Nhà Đất bán">Nhà Đất bán</a> |
                  <a href="{{ url('/nha-dat-cho-thue') }}" class="link-cyan" title="Nhà Đất Cho Thuê">Nhà Đất Cho Thuê</a> |
                  <a href="{{ url('/can-mua-can-thue') }}" class="link-cyan" title="Cần Mua Cần Thuê">Cần Mua Cần Thuê</a> |
                  <a href="{{ url('/tieu-diem') }}" class="link-cyan" title="Tiêu Điểm">Tiêu Điểm</a>
                </span>
              </td>
            </tr>
          @endif
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
