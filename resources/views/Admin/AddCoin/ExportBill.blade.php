@extends('Admin.Layouts.Master')
@section('Content')

<section class="content">

    <div class="container-fluid">
        <!-- Filter -->
        <div class="filter block-dashed">
            <h3 class="title">Bộ lọc</h3>
            <form action="" method="get" enctype="multipart/form" class="form-filter">
                <div class="form-row">
                    <div class="col-md-5 form-group">
                        <select class="custom-select" name="bill_state">
                            <option selected value="">Tình Trạng</option>
                            <option {{(isset($_GET['bill_state']) && $_GET['bill_state'] != ''  && $_GET['bill_state'] == 0 ) ? "selected":""}} value="0">Chờ xử lý</option>
                            <option {{(isset($_GET['bill_state']) && $_GET['bill_state'] == 1 ) ? "selected":""}} value="1">Đã xử lý</option>
                            <option {{(isset($_GET['bill_state']) && $_GET['bill_state'] == 2 ) ? "selected":""}} value="2">Không xử lý</option>
                        </select>
                    </div>

                    <div class="col-md-7">
                        <div class="form-row">
                            <div class="col-md-4 form-group">
                                <input type="text" name="money" value="{{(isset($_GET['money'])&& $_GET['money']!="")?$_GET['money']:''}}" class="form-control" placeholder="Số tiền nạp">
                            </div>

                            <div class="col-md-4 form-group">
                                <input type="date" class="form-control start_day" value="{{(isset($_GET['start_day'])&& $_GET['start_day']!="")?$_GET['start_day']:''}}" name="start_day" placeholder="Từ ngày" >
                            </div>

                            <div class="col-md-4 form-group">
                                <input type="date" class="form-control end_day" value="{{(isset($_GET['end_day'])&& $_GET['end_day']!="")?$_GET['end_day']:''}}" name="end_day" placeholder="Đến ngày" >
                            </div>

                        </div>
                    </div>
                </div>

                <div class="text-center form-group">
                    <button class="btn bg-blue-light"><i class="fas fa-search"></i> Tìm kiếm</button>
                </div>
            </form>

        </div>

        <!-- / (Filter) -->

        <!-- Main row -->
        <div class="table-contents">

            <table class="table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tài khoản</th>
                        <th>Loại</th>
                        <th>Số tiền</th>
                        <th>Ngày</th>
                        <th>Thông tin xuất</th>
                        <th>Tình trạng</th>
                        <th>Cài đặt</th>
                    </tr>
                </thead>

                <tbody>
                   @forelse ($list_buy as $item )
                    <tr>
                        <td>{{$loop->index + 1}}</td>
                        <td>
                            <p class="mb-1 d-flex justify-content-start"><a class="text-cyan-blue" href="#">{{data_get($item->user_detail, 'fullname') ?? ''}}</a></p>
                            <p class="mb-1 d-flex justify-content-start"><a class="text-cyan-blue" href="mailto:{{$item->user->email}}">{{$item->user->email ?? ''}}</a></p>
                            <p class="mb-0 d-flex justify-content-start"><a class="text-cyan-blue" href="tel:{{$item->user->phone_number}}">{{$item->user->phone_number ?? ''}}</a></p>
                        </td>
                        <td>
                        @if (data_get($item->deposit, 'deposit_type')=='I')
                        <span class="">Mua gói tin</span>
                        @endif
                        @if (data_get($item->deposit, 'deposit_type')=='S')
                        <span class="">Dịch vụ</span>
                        @endif
                        @if (data_get($item->deposit, 'deposit_type')=='C')
                        <span class="">Nạp coin</span>
                        @endif
                    </td>
                        <td>{{number_format(data_get($item->deposit, 'deposit_amount'),0,'','.')}} VNĐ</td>
                        <td>{{date('d/m/Y',$item->created_at)}}</td>
                        <td>
                            <span class="view-export text-blue-light text-medium text-underline" data-toggle="modal" data-target="#exampleModalCenter{{$item->id}}">Xem</span>
                        </td>
                        {{-- <td><span class="text-orange">Chờ xử lý</span></td> --}}
                        <td>
                            @if ($item->confirm_status == 0)
                                <span class="text-warning">Chờ xử lý</span>
                            @elseif ($item->confirm_status==1)
                                <span class="text-success">Đã xử lý</span>
                            @elseif ($item->confirm_status==2)
                                <span class="text-danger ">Không xử lý</span>
                            @endif
                        </td>

                        <td>
                            <form action="{{route('admin.bill.upload_bill', [$item->id, ''])}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="upload text-center">
                                    <img src="{{asset("system/image/upload.png")}}" alt="">
                                    <p class="mb-0 mt-2 text-blue-light text-medium">Upload</p>
                                    <input type="file" class="upload_bill" onchange="this.form.submit()" name="file">
                                </div>
                            </form>
                        </td>
                    </tr>
                   @empty
                       <tr>
                           <td colspan="8">Chưa có dữ liệu</td>
                       </tr>
                   @endforelse
                </tbody>

            </table>
            <div class="d-flex align-items-center justify-content-between my-4">
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-between mx-4">
                        <div class="d-flex mr-2 align-items-center">Hiển thị</div>
                        <form action="{{route('admin.bill.list')}}" method="GET">
                            <label class="select-custom2">
                                <select id="paginateNumber" name="items" onchange="submitPaginate(event, this)">
                                    <option
                                        @if(isset($_GET['items']) && $_GET['items'] == 10) {{ 'selected' }} @endif value="10">
                                        10
                                    </option>
                                    <option
                                        @if(isset($_GET['items']) && $_GET['items'] == 20) {{ 'selected' }} @endif  value="20">
                                        20
                                    </option>
                                    <option
                                        @if(isset($_GET['items']) && $_GET['items'] == 30) {{ 'selected' }} @endif  value="30">
                                        30
                                    </option>
                                </select>
                            </label>
                        </form>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="count-item">Tổng cộng: {{$list_buy->total()}} items</div>
                    @if($list_buy)
                {{ $list_buy->render('Admin.Layouts.Pagination') }}
                    @endif
                </div>
            </div>

        </div>
        @foreach ($list_buy as $item )
            <div class="modal fade" id="exampleModalCenter{{$item->id}}" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">
                                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                                Thông tin xuất hóa đơn
                            </h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    @if($item->bill_type == 2)
                                        <th scope="row">Tên công ty</th>
                                        <td>{{$item->company_name}}</td>
                                    @else
                                        <th scope="row">Tên khách hàng</th>
                                        <td>{{data_get($item->user_detail, 'fullname') ?? ''}}</td>
                                    @endif
                                </tr>
                                @if($item->bill_type == 2)
                                    <tr>
                                        <th scope="row">Người đại diện</th>
                                        <td>{{$item->company_representative}}</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Mã số thuế</th>
                                        <td>{{$item->tax_code}}</td>
                                    </tr>
                                @endif
                                <tr>
                                    @if($item->bill_type == 2)
                                        <th scope="row">Địa chỉ</th>
                                        <td>{{$item->company_address}}</td>
                                    @else
                                        <th scope="row">Địa chỉ</th>
                                        <td>
                                            {{ data_get($item->user, 'user_location.address') }},
                                            {{ data_get($item->user, 'user_location.ward.ward_name') }},
                                            {{ data_get($item->user, 'user_location.district.district_name') }},
                                            {{ data_get($item->user, 'user_location.province.province_name') }}
                                        </td>
                                    @endif
                                </tr>

                                <tr>
                                    <th scope="row">Mục cần nhận hóa đơn</th>
                                    <td>  @if (data_get($item->deposit, 'deposit_type')=='I')
                                            <span class="">Mua gói tin</span>
                                        @endif
                                        @if (data_get($item->deposit, 'deposit_type')=='S')
                                            <span class="">Dịch vụ</span>
                                        @endif
                                        @if (data_get($item->deposit, 'deposit_type')=='C')
                                            <span class="">Nạp coin</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Số tiền</th>
                                    <td>{{number_format(data_get($item->deposit, 'deposit_amount'),0,'','.')}} VNĐ</td>
                                </tr>
                                <tr>
                                    <th scope="row">Mã giao dịch</th>
                                    <td>{{data_get($item->deposit, 'deposit_code')}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Ngày giao dịch</th>
                                    <td>{{date('d/m/Y',data_get($item->deposit, 'deposit_time'))}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Yêu cầu khác</th>
                                    <td>{{$item->bill_note ?? "Không" }}</td>
                                </tr>
                                @if($item->bill_url)
                                    <tr>
                                        <th scope="row">File đính kèm</th>
                                        <td><a href="{{asset($item->bill_url)}}" target="-_blank">Xem file đính kèm</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>

                        </div>

                    </div>

                </div>
            </div>
        @endforeach
    </div><!-- /.container-fluid -->

</section>

@endsection
@section('Style')
<link rel="stylesheet" href="{{asset('system/css/frontend/main.css')}}">
<link rel="stylesheet" href="{{asset('system/css/frontend/plusb.css')}}">
<style>

</style>
@endsection
@section('Script')
<script src="{{asset('system/js/table.js')}}"></script>
<script>
    $(document).ready(function(){
       @if(isset($_GET['start_day'])&&$_GET['start_day']!="" )
       $('.end_day').attr('min','{{$_GET['start_day']}}');
       @endif
       @if(isset($_GET['end_day'])&&$_GET['end_day']!="" )
       $('.start_day').attr('max','{{$_GET['end_day']}}');
       @endif
       $('.start_day').change(function (){
           $('.end_day').attr('min',$('.start_day').val());
       });
       $('.end_day').change(function (){
           $('.start_day').attr('max',$('.end_day').val());
       });
   });
</script>
<script>
    $(".form-filter").on("submit",function(e){
        $(this).append('<input type="hidden" name="items" value="'+ $('#paginateNumber').val() + '" /> ');
    });
</script>
@endsection
