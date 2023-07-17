@extends('Admin.Layouts.Master')
@section('Title', 'Hòm Thư | Admin')
@section('Style')
    <style>
        :root{
            --topSelected: 20px;
        }
        .item{
            cursor: pointer
        }
        .list-mail p{
            float: left
        }
        .mailbox {
            display: flex;
            align-items: start;
            padding: 20px;
            width: 100%;
        }
        .content{
            /* background-color: #e9ecf5; */
        }
        .mailbox .nav-pills .nav-link.active {
            color: #be2020 !important;
            background-color: #f0ecec;
        }
        .mailbox .nav-pills .nav-link {
            color: #7c7c7c !important;
            text-align: center;
            background: #ffffff;
            margin: 1px;
        }
        .mailbox .nav-pills .nav-link {
            color: #7c7c7c !important;
            text-align: center;
            background: #ffffff;
            margin: 1px;
        }
        .mailbox .search-mail {
            padding: 15px 20px 35px;
        }

        .mailbox .search {
            position: relative;
        }
        .mailbox .search-input {
            width: 250px;
            border-radius: 20px;
            height: 30px;
            padding-left: 40px;
            background-color: #ebebeb;
        }
        .mailbox .search-mail p {
            margin-bottom: 10px;
            color: #3391da;
            text-align: center;
        }
        .mailbox button.btn-search {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            border: none;
            background: 0 0;
            width: 50px;
            color: #747474;
            font-size: 14px;
        }
        .mailbox .nav-pills .nav-link.active {
            color: #be2020 !important;
            background-color: #f0ecec;
        }
        .mailbox .nav-pills .nav-link {
            color: #7c7c7c !important;
            text-align: center;
            background: #ffffff;
            margin: 1px;
        }
        .mailbox {
            background-color: #e9ecf5;
            /* padding: 25px !important; */
        }
        .mailbox .list-mail {
            margin-left: 25px;
            padding: 15px;
            position: relative;
            margin-bottom: 40px;
            min-height: 100px;
        }
        .mailbox .list-mail:before {
            position: absolute;
            content: "";
            left: -20px;
            top: var(--topSelected);
            border-right: 20px solid #fff;
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
        }
        .mailbox .list-mail .item {
            display: flex;
            padding: 10px 0;
        }
        .mailbox .list-mail .item:not(:last-child) {
            border-bottom: 1px solid #e1e1e1;
        }
        .mailbox .list-mail .color-star {
            color: #bebebe;
            margin-right: 10px;
        }
        .mailbox .list-mail .active {
            color: #ffd000;
        }
        .mailbox .list-mail .color-star:hover{
            color: #ffd000;
        }
        .mailbox .list-mail .active:hover{
            color: #bebebe!important;
        }
        .mailbox .list-mail .item p {
            margin-bottom: 0;
            overflow: hidden;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            display: -webkit-box;
            font-size: 14px;
        }
        .content-gray {
            color: #919191;
        }
        .mailbox .list-mail .item .post-time {
            float: right;
            color: #456c8d;
            font-size: 14px;
        }
        .post-time {
            font-size: 0.9375rem;
            color: #777777;
            display: inline-block;
        }
        .checkbox {
            margin-right: 10px;
            margin-top: 2px;
        }
        .tab-content{
            width: 100%;
        }
        .table-bottom{
            margin-left: 24px;
        }
        @media only screen and (max-width: 1280px){
            .mailbox .list-mail .item p {
                height: 40px;
            }
        }
        .list-mail img{
            display: none;
        }
        @section('StyleMail') @show
    </style>
@endsection

@section('Content')
    <div class="col-sm-12 mbup30">
        <div class="row m-0 px-2 pt-3">
            <ol class="breadcrumb mt-1">
                <li class="recye px-2 pt-1 check active">
                    <a >
                        <i class="fa fa-th-list mr-1"></i>Danh sách
                    </a>
                </li>
                {{-- kiểm tra phân quyền thùng rác --}}
                @if($check_role == 1  ||key_exists(5, $check_role))
                    <li class="phay ml-2 " style="margin-top: 2px !important">
                        /
                    </li>
                    <li class="recye px-2 pt-1 ml-1">
                        <a href="{{route('admin.mailbox.trash')}}">
                            Thùng rác
                        </a>
                    </li>
                @endif
                {{-- kiểm tra phân quyền thêm --}}
                @if($check_role == 1  ||key_exists(1, $check_role))
                    <li class="ml-2 phay">
                        /
                    </li>
                    <li class="add px-2 pt-1 ml-1 check">
                        <a href="{{route('admin.mail.add')}}">
                            <i class="fa fa-edit mr-1"></i>Thêm
                        </a>
                    </li>
                @endif
            </ol>
        </div>
    </div>
    <section class="content" style="background: #e9ecf5">
        <div class="mailbox">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="position: sticky;position: -webkit-sticky;top: 15px;">
                <a class="nav-link text-left"  href="{{route('admin.mailbox.list')}}" role="tab" aria-controls="v-pills-home" aria-selected="true">Tất cả thư</a>
                <a class="nav-link text-left"  href="{{route('admin.mailbox.trash')}}" role="tab" aria-controls="v-pills-trashhome" aria-selected="false">Thư đã xóa</a>
                <a class="nav-link text-left"  href="{{route('admin.mailbox.read')}}" role="tab" aria-controls="v-pills-profile" aria-selected="false">Thư đã đọc</a>
                <a class="nav-link text-left"  href="{{route('admin.mailbox.unread')}}" role="tab" aria-controls="v-pills-messages" aria-selected="false">Thư chưa đọc</a>
                <a class="nav-link text-left"  href="{{route('admin.mailbox.pin')}}" role="tab" aria-controls="v-pills-settings" aria-selected="false">Thư đươc ghim</a>
                <a class="nav-link text-left"  href="{{route('admin.mailbox.nofitication')}}" role="tab" aria-controls="v-pills-postings" aria-selected="false">Thông báo tin đăng</a>
                <a class="nav-link text-left"  href="{{route('admin.mailbox.nofitication-acc')}}" role="tab" aria-controls="v-pills-account" aria-selected="false">Thông báo tài khoản</a>
                <a class="nav-link text-left"  href="{{route('admin.mailbox.nofitication-system')}}" role="tab" aria-controls="v-pills-system" aria-selected="false">Thông báo từ hệ thống</a>
                <div class="search-mail bg-white ">
                    <p class="text-left">Tìm kiếm</p>
                    <div class="search">
                        <form class="form-search" method="get" action="{{route('admin.mailbox.list')}}">
                            <input type="text" class="form-control search-input" name="search" placeholder="Nhập từ khóa" autocomplete="off" value="{{isset($_GET['search'])?$_GET['search']:""}}">
                            <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            @section('ContentMail') @show
        </div>
    </section>
@endsection

@section('Script')
    <script>
        $(".detail_mail").click(function() {
            var id = $(this).data('id');
            var url = "{{route('admin.mail.detail',':id')}}";
            url = url.replace(':id',id);
            if(id != null){
                window.location.href=url;
            }
        });
        $(".color-star").click(function() {
            var id = $(this).data('id');
            var class_active = 'position'+id;
            var _token = "{{csrf_token()}}";
            var url ="{{route('admin.mail.pin',':id')}}";
            url = url.replace(':id',id);
            // alert(id);
            $.ajax({
                type: "GET",
                dataType: "json",
                url: url,
                data:{
                    _token:_token,
                    id:id,

                },
                success: function (data) {
                    if($('.'+class_active).hasClass('active')){
                        $('.'+class_active).removeClass('active');

                    }
                    else {
                        $('.'+class_active).addClass('active');
                    }

                    // alert($(this));
                },
                error: function() {
                    toastr.error('Lỗi không xác định');
                }

            });

        });
        $('.moveToTrash').click(function () {
            // var id = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Sau khi xóa sẽ chuyển vào thùng rác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {

                    $('#formtrash').submit();

                }
            });
        });
        $('.unToTrash').click(function () {
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: "Nhấn đồng ý thì sẽ tiến hành khôi phục !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {

                    $('#formtrash').submit();

                }
            });
        });
    </script>
    <script type="text/javascript">
        // change effect
        const hrefTarget = window.location.href;
        const index = $('.mailbox > .nav-pills > .nav-link').filter(function(k, v) {
            return this.href == hrefTarget;
        }).addClass("active");

        const heightOfItem = $('.mailbox .nav-pills .nav-link').height();
        let index_exists = index.prevObject.index(index);
        if(index_exists < 0) {
            index_exists = 0;
            $('.mailbox > .nav-pills > .nav-link').first().addClass('active')
        }
        const margin = index_exists == 0 ? 10 : index_exists > 1 ? heightOfItem * (index_exists + 1) + (index_exists * 16) : heightOfItem * (index_exists + 1);
        $('.mailbox .list-mail').css('--topSelected', margin + 'px')
        $('.list-mail').css('min-height', margin + 50 + 'px')
    </script>
    @section('ScriptMail') @show
@endsection
