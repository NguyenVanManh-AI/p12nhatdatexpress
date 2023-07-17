@extends('Admin.Layouts.Master')
@section('Title', 'Danh sách dự án | Quản lý dự án')
@section('Style')
@endsection
@section('Content')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-event.css")}}">

<div class="row m-0 p-3">
  <div class="col-12 p-0">
    <div class="box-dash mt-4 pt-4">
      <h3 class="title-info-reponsive font-weight-bold">BỘ LỌC</h3>
      <div class="row m-0 pt-2 ">
        <div class="col-12 col-sm-12 col-md-5 col-lg-5 box_input px-0">
         <div class="input-reponsive-search pr-3">
           <input class="form-control required" type="" name="" placeholder="Nhập từ khóa (Tên tài khoản, sđt, mã giao dịch...)">
         </div>
       </div>
       <div class="search-reponsive col-12 col-sm-12 col-md-7 col-lg-7 pl-0">
        <div class="row m-0">
          <div class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-0">
            <div class="search-reponsive ">
              <select id="tinhtrang">
                <option>Tình trạng</option>
                <option>Nancy</option>
                <option>Peter</option>
                <option>Jane</option>
              </select>
            </div>
          </div>
          <div class="mtdow10 search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pr-2 pl-4">
           <input class="form-control float-left" type="date" placeholder="Từ ngày" required>
         </div>
         <div class="search-reponsive input-reponsive-search col-12 col-sm-12 col-md-4 col-lg-4 pl-3 pr-2">
          <input class="form-control float-right" type="date" placeholder="Đến ngày" required>
        </div>
      </div>
    </div>
    

    
    

    <div class="container pb-3">
      <div class="row">
        <div class="mtdow10 col text-center">
         <button class=" mtdow10 search-button btn btn-primary w-100" style="width: 130px !important"><i class="fa fa-search mr-2" aria-hidden="true"></i>Tìm kiếm</button>
       </div>
     </div>
   </div>
 </div>
</div>
</div>
</div>



<!-- Main content -->
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
                <th scope="col" style="width: 10%">
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
                <th scope="col"  style="width: 22%">Tài khoản</th>
                <th scope="col" style="width: 13%">Mã thanh toán</th>
                <th scope="col" style="width: 7%;min-width: 100px">Gói tin</th>               
                <th scope="col" style="width: 14%">Số tiền</th>
                  <th scope="col" style="width: 10%">Ngày mua</th>
                    <th scope="col" style="width: 22%">Hình thức TT</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="active">
                  <input type="checkbox" class="select-item checkbox" name="select-item" />
                </td>
                <td>10</td>
                <td class="event-button-dropdow bg-pending">
                  <div class="dropdown">
                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Chờ duyệt
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="#">Chờ duyệt</a>
                      <a class="dropdown-item" href="#">Chưa duyệt</a>
                      <a class="dropdown-item" href="#">Đã duyệt</a>
                    </div>
                  </div>
                </td>

                <td class="event-title pt-3">
                  <span class="font-weight-bold " style="color:#384579">Trương Đình Anh A</span>
                  <br>
                  <p>anhkhoatruong3@gmail.com</p>
                </td>
                <td class="">
                  <span>NDE235565</span>
                </td>
                <td class="">
                  
                  <span>Cao cấp 3</span>
                </td>
                <td>
                  <span>5.000.000 VNĐ</span>
                </td>
                <td>
                  <span>19/10/2021</span>
                </td>
                <td>
                  <span>Chuyển khoản</span>
                </td>
              </tr>


              <tr>
                <td class="active">
                  <input type="checkbox" class="select-item checkbox" name="select-item" />
                </td>
                <td>10</td>
                <td class="event-button-dropdow bg-approve">
                  <div class="dropdown">
                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Đã duyệt
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="#">Chờ duyệt</a>
                      <a class="dropdown-item" href="#">Chưa duyệt</a>
                      <a class="dropdown-item" href="#">Đã duyệt</a>
                    </div>
                  </div>
                </td>

                <td class="event-title pt-3">
                  <span class="font-weight-bold " style="color:#384579">Trương Đình Anh A</span>
                  <br>
                  <p>anhkhoatruong3@gmail.com</p>
                </td>
                <td class="">
                  <span>NDE235565</span>
                </td>
                <td class="">
                  
                  <span>Cao cấp 3</span>
                </td>
                <td>
                  <span>5.000.000 VNĐ</span>
                </td>
                <td>
                  <span>19/10/2021</span>
                </td>
                <td>
                  <span>Momo</span>
                </td>
              </tr>

              <tr>
                <td class="active">
                  <input type="checkbox" class="select-item checkbox" name="select-item" />
                </td>
                <td>10</td>
                <td class="event-button-dropdow bg-notapprove">
                  <div class="dropdown">
                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Không duyệt
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="#">Chờ duyệt</a>
                      <a class="dropdown-item" href="#">Chưa duyệt</a>
                      <a class="dropdown-item" href="#">Đã duyệt</a>
                    </div>
                  </div>
                </td>

                <td class="event-title pt-3">
                  <span class="font-weight-bold " style="color:#384579">Trương Đình Anh A</span>
                  <br>
                  <p>anhkhoatruong3@gmail.com</p>
                </td>
                <td class="">
                  <span>NDE235565</span>
                </td>
                <td class="">
                  
                  <span>Cao cấp 3</span>
                </td>
                <td>
                  <span>5.000.000 VNĐ</span>
                </td>
                <td>
                  <span>19/10/2021</span>
                </td>
                <td>
                  <span>Momo</span>
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
    $('#tinhtrang').chosen();

  });
</script>
<script type="text/javascript">
  $('#quanlygoitin').addClass('active');
  $('#danhsachmuagoitin').addClass('active');
  $('#nav-quanlygoitin').addClass('menu-is-opening menu-open');
</script>
<!-- /.content -->
@endsection

@section('Script')
<script src="js/table.js"></script>

@endsection
