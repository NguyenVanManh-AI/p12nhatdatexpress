@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách dự án | Quản lý dự án')
@section('Style')
@endsection
@section('Content')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-event.css")}}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style type="text/css">
  .box-anatilist{
    width: 100%;height: 110px;
  }
  .number-anatilist{
    font-size: 210%
  }
  .green-box{
    background: #35b848;
  }
  .blue-box{
    background: #246fb2;
  }
  .red-box{
    background: #ff6620;
  }
  .box-anitilist-mini{
    height: 110px;background: #efeef6;
  }
  .box-anitilist-mini > div{
    height: 30px;background: #c3c2cf
  }
  .fz-90{
    font-size: 90%;
  }
  .cusor-point{
    cursor: pointer
  }
  @media only screen and (max-width: 779px) {
    .box-anitilist{
      padding-left: 0px !important;
      padding-right: 0px !important;
    }



  }
  .avatar-box{
    width: 50px;height: 50px;background: green;margin: auto;border-radius: 50%;border: 4px solid #f1f1f1;position: relative
  }
  .avatar-box img{
    border-radius: 50%;
  }
  .avatar-box > div{
    position: absolute;width: 17px;height: 17px;background: #f1f1f1;top: -5px;right: -5px;border-radius: 50%;font-size: 50%;line-height: 16px
  }
  .name-text{
    color: #40465d
  }
  .icon-info .icon{
    width: 17px;height: 17px;font-size: 80%
  }
  .icon-info .text-phone{
    height: 20px;font-size: 90%;padding-top: 2px
  }
</style>
<div class="row m-0 p-3">
  <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-0">
    <div>
      <div class="row m-0">
        <div class="box-anitilist col-12 col-sm-12 col-md-6 col-lg-6 p-2">
          <div class="box-anitilist-mini">
            <div class="pt-1">
              <p class="font-weight-bold text-center">Tổng số thành viên</p>
            </div>
            <p class="font-weight-bold text-center number-anatilist mt-2">3.467</p>
          </div>
        </div>
        <div class="box-anitilist col-12 col-sm-12 col-md-6 col-lg-6 p-2">
          <div class="box-anitilist-mini">
            <div class="pt-1">
              <p class="font-weight-bold text-center">Thành viên mới trong tuần</p>
            </div>
            <p class="font-weight-bold text-center number-anatilist mt-2 mb-0">23</p>
            <p class="text-success fz-90" style=""><i class="fa fa-caret-up mx-2" aria-hidden="true"></i>Tăng 15% <span class="text-dark">so mới tuần trước</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-12 col-md-6 col-lg-6 p-0">
    <div>
      <div class="row m-0">
       <div class="box-anitilist col-12 col-sm-12 col-md-4 col-lg-4 p-2">
        <div class="box-anitilist-mini">
          <div class="pt-1">
            <p class="font-weight-bold text-center">TK Cá nhân</p>
          </div>
          <p class="font-weight-bold text-center number-anatilist mt-2 mb-0">1.000</p>
          <p class="text-success fz-90 float-left" style=""><i class="fa fa-caret-up mx-2" aria-hidden="true"></i>Tăng 15%</p>
          <p class="fz-90 float-right text-primary mr-2 cusor-point">Xem</p>
        </div>
      </div>
      <div class="box-anitilist col-12 col-sm-12 col-md-4 col-lg-4 p-2">
        <div class="box-anitilist-mini">
          <div class="pt-1">
            <p class="font-weight-bold text-center">TK Doanh nghiệp</p>
          </div>
          <p class="font-weight-bold text-center number-anatilist mt-2 mb-0">1.467</p>
          <p class="text-danger fz-90 float-left" style=""><i class="fa fa-caret-down mx-2" aria-hidden="true"></i>Giảm 10%</p>
          <p class="fz-90 float-right text-primary mr-2 cusor-point">Xem</p>
        </div>
      </div>
      <div class="box-anitilist col-12 col-sm-12 col-md-4 col-lg-4 p-2">
        <div class="box-anitilist-mini">
          <div class="pt-1">
            <p class="font-weight-bold text-center">CVTV</p>
          </div>
          <p class="font-weight-bold text-center number-anatilist mt-2 mb-0">1.000</p>
          <p class="text-success fz-90 float-left" ><i class="fa fa-caret-up mx-2" aria-hidden="true"></i>Tăng 15%</p>
          <p class="fz-90 float-right text-primary mr-2 cusor-point">Xem</p>
        </div>
      </div>
    </div>
  </div>
</div>

</div>

<div class="row m-0 p-3" style="margin-top: -35px !important">
  <div class="col-12 p-0">
    <div class="box-dash mt-4 pt-4">
      <h3 class="title-info-reponsive font-weight-bold">BỘ LỌC</h3>
      <div class="row m-0 pt-2 ">
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 box_input px-0">
         <div class="input-reponsive-search pr-4">
           <input class="form-control required" type="" name="" placeholder="Nhập từ khóa">
         </div>
       </div>
       <div class="search-reponsive col-12 col-sm-12 col-md-8 col-lg-8 pl-0">
        <div class="row m-0">
          <div class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-6 col-lg-6 pr-2 pl-0">
           <div class="search-reponsive ">
            <select id="taikhoan">
              <option>Tài khoản</option>
              <option>Nancy</option>
              <option>Peter</option>
              <option>Jane</option>
            </select>
          </div>
        </div>
        <div class=" mtdow10 search-reponsive input-reponsive-search col-12 col-sm-12 col-md-6 col-lg-6 pl-3 pr-0">
          <input class="form-control float-right" type="date" placeholder="Thời gian tham gia" required>
        </div>
      </div>
    </div>

    <div class=" mtdow10 col-12 col-sm-12 col-md-4 col-lg-4 box_input px-0">
     <div class="input-reponsive-search pr-4">
      <div class="search-reponsive w-100">
        <select id="tinhthanhpho">
          <option>Tỉnh / Thành phố</option>
          <option>Nancy</option>
          <option>Peter</option>
          <option>Jane</option>
        </select>
      </div>
    </div>
  </div>
  <div class="search-reponsive col-12 col-sm-12 col-md-8 col-lg-8 pl-0">
    <div class="row m-0">
      <div class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-6 col-lg-6 pr-2 pl-0">
        <div class="search-reponsive ">
          <select id="quanhuyen">
            <option>Quận / Huyện</option>
            <option>Nancy</option>
            <option>Peter</option>
            <option>Jane</option>
          </select>
        </div>
      </div>
      <div class="mtdow10 search-reponsive input-reponsive-search col-12 col-sm-12 col-md-6 col-lg-6 pl-3 pr-0">
        <div class="search-reponsive ">
          <select id="capbac">
            <option>Cấp bậc</option>
            <option>Nancy</option>
            <option>Peter</option>
            <option>Jane</option>
          </select>
        </div>
      </div>
    </div>
  </div>
  <div class="mtdow10 col-12 col-sm-12 col-md-4 col-lg-4 box_input px-0">
   <div class="input-reponsive-search pr-4">
     <div class="mtdow10 search-reponsive w-100">
      <select id="tinhtrang">
        <option>Tình trạng</option>
        <option>Nancy</option>
        <option>Peter</option>
        <option>Jane</option>
      </select>
    </div>
  </div>
</div>

<div class="search-reponsive2 col-12 col-sm-12 col-md-8 col-lg-8 pl-0 pr-3 pb-2">
 <button class=" mtdow10 search-button btn btn-primary  mr-3" style="width: 130px "><i class="fa fa-search mr-2" aria-hidden="true"></i>Tìm kiếm</button>
 <button class=" search-button btn btn-primary " style="width: 270px"><i class="fa fa-pie-chart mr-2" aria-hidden="true"></i>Lên chiến dịch email maketing</button>
</div>


</div>
</div>
</div>
</div>

<section class="content hiden-scroll mt-3">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <table class="table table-bordered text-center table-custom" id="table" >
            <thead>
              <tr class="contact-table">
                <th scope="row" class=" active" style="width: 3%">
                  <input type="checkbox" class="select-all checkbox" name="select-all" />
                </th>
                <th scope="col" style="width: 4%">STT</th>
                
                <th scope="col"  style="width: 30%;min-width: 230px">Tên thành viên</th>
                <th scope="col" style="width: 15%">Ngày tham gia</th>
                
                <th scope="col" style="width: 5%">
                  <div class="dropdown">
                    <button class="dropdow dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Cấp bậc
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </th> 
                <th scope="col" style="width: 14%">
                  <div class="dropdown">
                    <button class="dropdow dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Tài khoản
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </th> 
                <th scope="col" style="width: 14%">
                  <div class="dropdown">
                    <button class="dropdow dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Tình trạng
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </th>             
                <th scope="col" style="width: 22%;min-width: 220px">Cài đặt</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="active">
                  <input type="checkbox" class="select-item checkbox" name="select-item" />
                </td>
                <td>10</td>
                <td class="pt-4">

                  <div class="avatar-box">
                    <img src="https://9mobi.vn/cf/images/2015/03/nkk/hinh-anh-dep-1.jpg" width="100%" height="100%">
                    <div class="text-center">
                      <i class="fa fa-pencil" aria-hidden="true" ></i>
                    </div>
                  </div>
                  <p class="name-text font-weight-bold mb-0" >Võ Thu Thủy</p>
                  <p class="mb-0 text-success">Cá nhân</p>
                  <p class="mb-0 text-secondary">23/07/1990</p>
                  <div class="icon-info w-100 d-flex mb-1 mt-2">
                    <div class="icon pt-1">
                      <i class="fa fa-phone" aria-hidden="true"></i>
                    </div>
                    <div class=" text-phone w-100 pl-2">
                      <p class="font-weight-bold text-danger text-left">093 734 2758</p>
                    </div>
                  </div>
                  <div class="icon-info w-100 d-flex mb-1">
                    <div class="icon pt-1">
                      <i class="fa fa-envelope" aria-hidden="true"></i>
                    </div>
                    <div class="w-100 pl-2 text-phone">
                      <p class="text-left">vothuy1234@gmail.com</p>
                    </div>
                  </div>
                  <div class="icon-info w-100 d-flex mb-1">
                    <div class="icon pt-1">
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                    </div>
                    <div class="w-100 pl-2 text-phone" >
                      <p class="text-left">23 Hải phòng, Thanh Khê, Đà Nẵng</p>
                    </div>
                  </div>
                  <div class="icon-info w-100 d-flex mb-1">
                    <div class="icon pt-1">
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                    </div>
                    <div class="w-100 pl-2 text-phone">
                      <p class="text-left">MST/CMND: 191864321</p>
                    </div>
                  </div>
                </td>

                <td>

                  19/10/2020              
                </td>
                <td class="">
                  <span>Vàng</span>
                </td>
                <td class="">
                  <span class="font-weight-bold text-info">3.000</span>
                </td>
                <td class="event-button-dropdow" >
                  <div class="dropdown ">
                    <button class="w-100 event-report-dropdow dropdown-toggle text-success" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Đã xác thực
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="#">Đã xác thực</a>
                      <a class="dropdown-item" href="#">Chưa xác thực</a>

                    </div>
                  </div>
                </td>
                <td class="py-2 px-0">
                  <div>
                    <div class="float-left ml-2">
                      <i class="icon-setup fa fa-cog "></i>
                      <a href="" class="text-primary mr-3">Chỉnh sửa</a>
                    </div>
                    
                    <div class="float-left ml-2">
                      <i class="icon-setup fa fa-times " ></i>
                      <a href="" class="event-delete-button ">Xóa</a>
                    </div>
                    <br>
                    <div class="float-left ml-2">
                      <i class="icon-setup fa fa-times "></i>
                      <a href="" class="event-delete-button mr-3">Chặn</a>
                    </div>
                    
                    <div class="float-left " style="margin-left: 38px">
                      <i class="icon-setup fa fa-times " ></i>
                      <a href="" class="event-delete-button ">Cấm</a>
                    </div>
                    <br>
                    <div class="float-left ml-2">
                      <i class="icon-setup fa fa-envelope " ></i>
                      <a href="" class="text-primary">Gửi mail</a>
                    </div>
                    <br>
                    <div class="float-left ml-2 ">
                      <i class="icon-setup fa fa-user "></i>
                      <a href="" class="text-primary">Xem trang cá nhân</a>
                    </div>
                    
                    <br>
                    <div class="float-left ml-2 ">
                      <i class="icon-setup fa fa-sign-out"></i>
                      <a href="" class="text-primary">Truy cập tài khoản</a>
                    </div>
                    
                    <br>
                    <div class="float-left ml-2 ">
                      <i class="icon-setup fa fa-commenting"></i>
                      <a href="" class="text-primary">Thông báo</a>
                    </div>
                    <span class="count-trash mt-1 mr-2 float-left">1</span>
                    <br>
                    <div class="clear-both"></div>
                  </div>
                </td>
              </tr>
              
            </tbody>
          </table>
        </div>



        <div class="row m-0 mt-3" >
          <div class="w140 float-left mb-2" >
            <div class="manipulation d-flex mr-4">
              <img src="{{ asset("system/images/icons/manipulation.png")}}">
              <select id="giatien"  class="select-button">
                <option>Thao tác</option>
                <option>Nancvnnvvny</option>
                <option>Petervnvnnv</option>
                <option>Janenvvn</option>
              </select>
            </div>
          </div>
          <div class="float-left mb-2 w150">
            <div class=" d-flex mr-4">
              <span class="mr-2 ml-3">Hiển thị:</span>
              <select id="giatien" class="display-box">
                <option>10</option>
                <option>11</option>
                <option>12</option>
                <option>13</option>
              </select>
            </div>
          </div>
          <div class="float-left mb-2 w170">
            <div class="manipulation d-flex mr-4">
              <i class="far fa-trash-alt mr-2 mt-1"></i>
              <span> Xem thùng rác</span>
              <span class="count-trash mt-1">1</span>
            </div>
          </div>
          <div class="float-left mb-2" style="width: calc(100% - 820px);"></div>
          <div class="float-left mb-2 w360">
           <div class="d-flex align-items-center">
            <div class="count-item" >Tổng cộng: 372 items</div>
            <div class="count-item-reponsive" style="display: none">372 items</div>
            <div>
              <ul class="pagination pagination-custom my-0">
                <li class="page-item active"><span class="page-link">1</span></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item page-item2">
                  <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">»</span>
                    <span class="sr-only">Next</span>
                  </a>
                </li>
                <li class="page-item page-item2">
                  <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">» »</span>
                    <span class="sr-only">Next</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="clear-both"></div>
      </div>

    </div>
  </div>
</div>
</section>




<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css" />

<script type="text/javascript">
  $(document).ready(function () {
    $('#tinhthanhpho').chosen();
    $('#tinhtrang').chosen();
    $('#quanhuyen').chosen();
    $('#taikhoan').chosen();
    $('#capbac').chosen();
  });
</script>
<script type="text/javascript">
  $('#thanhvien').addClass('active');
  $('#taikhoanthuong').addClass('active');
  $('#nav-thanhvien').addClass('menu-is-opening menu-open');

</script>


<!-- /.content -->
@endsection

@section('Script')
<script src="js/table.js"></script>

@endsection
