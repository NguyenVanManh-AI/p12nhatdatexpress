@extends('Admin.Layouts.Master')

@section('Title', 'Nạp express coin | Nạp Express coin')

@section('Content')
<section class="content">
    <div class="container-fluid">
        <!-- Main row -->
        {{-- @if($check_role == 1  || key_exists(1, $check_role)) --}}
        <div class="block-dashed">
            <h3 class="title">Nạp express coin</h3>
            <div class="form-group col-md-4">
            </div>
            <form action="{{route('admin.coin.add')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Tài khoản người dùng</label>
                        <input  type="text" name="username_express" id="username_express" class="form-control" placeholder="Tài khoản" value="{{old('username')}}">
                        @if($errors->has('username_express'))
                        <small  class="text-danger">
                            {{$errors->first('username_express')}}
                        </small>
                        @endif
                        @if(\Session::has('error_user'))
                        <small  class="text-danger">
                            {{Session::get('error_user')}}
                        </small>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label>Số tiền nạp</label>

                        <input value="{{old('amount_express')}}" type="number" id="amount_express" name="amount_express" class="form-control" placeholder="Giá thường">
                        @if($errors->has('amount_express'))
                        <small  class="text-danger">
                            {{$errors->first('amount_express')}}
                        </small>
                        @endif

                    </div>
                    <div class="col-md-4 " style="padding-top:35px">Số coin nhận được <span id="coin_amount"> 0</span>(chưa tính ưu đãi cấp bậc)</div>
                    
                </div>
               
            
               
            </div>
            <div class="text-center">
               
                    <a href="{{route('admin.coin.list')}}" class="btn btn-primary"> 
                        Quay lại
                    </a>
                <button type="submit" class="btn bg-success"><i class="fas fa-plus-circle"></i> Nạp </button>
            </div>
            @if($errors->has('file'))
            <div class="text-center">
                <small style="margin-left:-10%" class="text-danger text-center">
                    {{$errors->first('file')}}
                </small>
            </div>
            @endif
        </form>
    </div>
   </div>
   
</div>

</div>
</section>
@endsection

@section('Style')
<style>
    .block-dashed {
        margin: 30px 0;
        padding: 30px 30px 14px;
        position: relative;
        border: 1px dashed #c2c5d6;
    }
    .block-dashed .title {
        display: inline-block;
        font-size: 18px;
        padding: 0 20px;
        background-color: #fff;
        font-weight: 500;
        line-height: 1;
        margin-bottom: 0;
        text-transform: uppercase;
        color: #000;
        position: absolute;
        top: -9px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1;
    }

    .upload-avatar {
        position: relative;
    }
    .upload-avatar input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        object-fit: cover;
    }
    .bg-blue-light {
        background-color: #00bff3 !important;
        color: #fff !important;
    }
    .bg-blue-light:hover{
        text-decoration: underline;
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
    .content .table th, .content .table td {
        border: 1px solid #b7b7b7;
        text-align: center;
        font-size: 14px;
        color: #0d0d0d;
        vertical-align: middle;
        position: relative;
    }
    .content .table td .setting-item {
        display: block;
        color: #0090ff;
        font-size: 14px;
        text-align: left;
        min-width: 150px;
    }
    .content .table .setting-item:hover{
        text-decoration: none;
    }
    .content .table td .setting-item.delete {
        color: #ff0000;
        cursor: pointer;
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
    .content .user-info .avatar {
        position: relative;
        display: inline-block;
    }
    .content .table .user-info .avatar img {
        width: 60px;
        height: 60px;
        border-width: 3px;
    }

    .content .user-info .avatar img {
        width: 125px;
        height: 125px;
        border-radius: 50%;
        border: 5px solid #e9f2f4;
    }
    .content .table .user-info .avatar .edit-avatar {
        width: 20px;
        height: 20px;
        font-size: 10px;
        line-height: 20px;
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
    .preview {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 200px;
    }
    .preview img{
        display: block;
        width: 100%;
        height: auto;
    }
    /*Dropdown*/
    .dropdown-custom{
        background-color: #337ab7;
        border-color: #2e6da4;
        color: #fff;
    }
    .dropdown-custom:hover{
        color: white;
        background-color: #286090;
        border-color:  #204d74;
    }

</style>

@endsection
@section('Script')
<script src="js/table.js"></script>
<script>
$('#amount_express').on('input',function(){
        var amount = $(this).val();
       $('#coin_amount').empty();
       $('#coin_amount').append(" "+ parseInt(amount/100));
});
</script>
@endsection
