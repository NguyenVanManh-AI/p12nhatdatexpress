@extends('Admin.Layouts.Master')
@section('Content')
<section class="content">
    <div class="container-fluid">
        <!-- Main row -->
        <div class="user-info text-center my-4">
            <div class="avatar mb-4">
                <img src="dist/img/user-avatar.png" alt="" id="imgAvatar" class="elevation-2">
                <div class="edit-avatar upload-avatar">
                    <i class="fas fa-pencil-alt" style="cursor: pointer"></i>
                    <input type="file" name="avatar" id="inputAvatar" accept="image/*">
                </div>
            </div>
            <div class="user-name mb-2">Nguyễn Bảo Anh</div>
            <div class="user-role mb-2">Quản lý nội dung</div>
            <div class="date-join">Ngày tham gia: <span class="text-green">11/12/2020</span></div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="small-box">
                    <p class="small-box-footer bg-black">Tổng bài viết</p>
                    <div class="inner bg-blue-blue">
                        <h3 class="large">650</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box">
                    <p class="small-box-footer">Bài viết trong tháng</p>
                    <div class="inner">
                        <h3>140</h3>
                       <div class="d-flex align-items-center analytics">
                           <p class="sort-down m-0"><a href="javascript:;"><i class="fas fa-sort-down icon-custom"></i>Giảm 10% </a> so với tháng trước</p>
                       </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box">
                    <p class="small-box-footer">Bài viết trong tuần</p>
                    <div class="inner">
                        <h3>35</h3>
                        <div class="d-flex align-items-center analytics">
                            <p class="sort-up"><a href="javascript:;"><i class="fas fa-sort-up"></i>Tăng 15% </a> so với tuần trước</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="table-title mt-3 font-weight-bold">Danh sách bài viết</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tiêu đề</th>
                    <th>Số lượng từ</th>
                    <th>Ngày đăng</th>
                    <th class="dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                            Chuyên mục
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" style="">
                            <a href="#" class="dropdown-item">
                                Nhà đất bán
                            </a>
                            <a href="#" class="dropdown-item">
                                Cần mua - Cần thuê
                            </a>
                            <a href="#" class="dropdown-item">
                                Nhà đất cho thuê
                            </a>
                        </div>
                    </th>
                    <th class="dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                            Lượt xem
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" style="">
                            <a href="#" class="dropdown-item">
                                Tăng dần
                            </a>
                            <a href="#" class="dropdown-item">
                                Giảm dần
                            </a>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @for($i = 10; $i >= 1; $i--)
                    <tr>
                        <td>{{$i}}</td>
                        <td><a href="#" class="title">BÁN ĐẤT NỀN PHÚ LONG - CHU LAI RIVERSIDE GIÁ CHỈ 8 TRIỆU/M2 - LH: 070.373.4162</a></td>
                        <td>500</td>
                        <td>19/10/2020</td>
                        <td>Nhà đất bán</td>
                        <td><span class="text-green">300</span></td>
                    </tr>
                @endfor
            </tbody>
        </table>

         <div class="table-bottom d-flex align-items-center justify-content-between mb-4">
 <div class="text-left d-flex align-items-center">
     <div class="manipulation d-flex mr-4">
         <img src="image/manipulation.png" alt="">
         <select name="" class="custom-select">
             <option value="">Thao tác</option>
             <option value="">20</option>
             <option value="">30</option>
         </select>
     </div>
     <div class="display d-flex align-items-center mr-4">
         <span>Hiển thị:</span>
         <select name="" class="custom-select">
             <option value="">10</option>
             <option value="">20</option>
             <option value="">30</option>
         </select>
     </div>
     <div class="view-trash">
         <a href="#"><i class="far fa-trash-alt"></i> Xem thùng rác</a>
         <span class="count-trash">1</span>
     </div>
 </div>
 <div class="text-right d-flex">
     <div class="count-item">Tổng cộng: 372 items</div>
    <ul class="pagination">
        <li class="page-item active"><span class="page-link">1</span></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">4</a></li>
        <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">»</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">» »</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
 </div>
</div>            <!-- /Main row -->
    </div><!-- /.container-fluid -->
</section>

@endsection

@section('Style')
<style>
  .content .user-info .avatar {
    position: relative;
    display: inline-block;
}
.content .user-info .avatar img {
    width: 125px;
    height: 125px;
    border-radius: 50%;
    object-fit:cover; /*this property does the magic*/
    border: 5px solid #e9f2f4;
}
.content .user-info .avatar .edit-avatar {
    position: absolute;
    top: 0;
    right: 0;
    width: 30px;
    height: 30px;
    line-height: 30px;
    border-radius: 50%;
    text-align: center;
    background-color: #e9ebf2;
    border: 1px solid #d7d7d7;
    color: #70747f;
    cursor: pointer;
}
.content .user-info .user-name {
    font-size: 24px;
    color: #0d0d0d;
    line-height: 1;
    font-weight: 700;
}
.content .user-info .date-join, .content .user-info .user-role {
    font-size: 18px;
    color: #676767;
    line-height: 1;
}
.content .user-info .date-join, .content .user-info .user-role {
    font-size: 18px;
    color: #676767;
    line-height: 1;
}
.small-box {
    background-color: #eeeff4;
}
.small-box {
    border-radius: 0.25rem;
    box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
    display: block;
    margin-bottom: 20px;
    position: relative;
}
.content .bg-black {
    background-color: #282828 !important;
}
.small-box>.small-box-footer {
    background-color: #c3c3cf;
    color: #000;
    margin-bottom: 0;
    font-weight: 500;
}
.content .bg-blue-blue {
    background-color: #246fb2 !important;
    color: #fff !important;
}
.small-box>.inner {
    height: 95px;
    padding-top: 25px;
    position: relative;
}

.small-box>.inner {
    padding: 10px;
}
.small-box>.inner h3.large {
    font-size: 2.25rem;
}
.small-box>.inner h3 {
    text-align: center;
    font-size: 1.625rem;
}
.analytics{
    position: relative;
}
.analytics i.fa-sort-down{
    position: absolute;
    top: 2px;
    left: 0;
}
  .analytics i.fa-sort-up{
      position: absolute;
      top: 9px;
      left: 0;
  }
.small-box>.inner p.sort-down a {
    color: var(--red);
    padding-left: 12px;
}
.small-box>.inner p.sort-up a {
    color: var(--green);
    padding-left: 12px;
}
.table-title {
    text-align: center;
    text-transform: uppercase;
    color: #282828;
    font-size: 20px;
    font-weight: 500;
    line-height: 24px;
    margin-bottom: 15px;
}
.content .table thead th {
    background-color: #034076;
    color: #fff;
    line-height: 1;
    font-weight: 400;
}
.content .table th, .content .table td {
    border: 1px solid #b7b7b7;
    text-align: center;
    font-size: 14px;
    color: #0d0d0d;
    vertical-align: middle;
    position: relative;
}
.table .dropdown .nav-link::after {
    content: '\f0d7';
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    position: absolute;
    top: 50%;
    right: 0;
    transform: translateY(-50%);
}
.table th .nav-link {
    color: #fff;
}
.table .dropdown .nav-link {
    position: relative;
}
.table .table td .title {
    display: block;
    text-align: left;
}
.table td a.title {
    color: #21337f;
    font-size: 1rem;
}



.manipulation .custom-select {
    background-color: #347ab6;
    color: #fff;
}
.table-bottom .custom-select {
    height: 32px;
}
.custom-select, .form-control {
    font-size: 14px;
}
.table-bottom .display {
    width: 125px;
}
.table-bottom .display span {
    flex: 0 0 60px;
    max-width: 60px;
    font-size: 14px;
}
.table-bottom .view-trash a i {
    color: #000;
    margin-right: 10px;
}
.table-bottom .count-trash {
    display: inline-block;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: #ff0000;
    color: #fff;
    font-size: 11px;
    text-align: center;
    line-height: 15px;
    margin-left: 5px;
}
.table-bottom .count-item {
    background-color: #eeeeee;
    font-size: 14px;
    padding: 7px 10px;
    border: 1px solid #dddddd;
    border-radius: 3px 0 0 3px;
    color: #004d79;
    height: 38px;
}

  .upload-avatar {
      position: relative;
      cursor: pointer;
  }
  .upload-avatar input[type="file"] {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
  }
</style>
@endsection

@section('Script')
    <script>
        $(document).ready( () => {
            const inputAvatar = document.getElementById('inputAvatar')
            const previewAvatar = document.getElementById('imgAvatar')
            inputAvatar.onchange = evt => {
                const [file] = inputAvatar.files
                if (file) {
                    previewAvatar.src = URL.createObjectURL(file)
                }
            }
        })
    </script>
@endsection
