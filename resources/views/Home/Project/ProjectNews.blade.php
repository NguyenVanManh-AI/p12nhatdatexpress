@forelse ($ProjectNews as $item )
<div class="col-md-4 project-item project-item-box">
    <div class="wrapper h-100">
        <div class="thumbnail thumbnail-project">
            <a href="{{ route('home.project.project-detail', $item->project_url) }}">
                <img class="lazy" data-src="{{ $item->image_thumbnail }}">
            </a>
        </div>
        <div class="content">
            <a href="{{route('home.project.project-detail', $item->project_url)}}" class="name text-ellipsis">{{$item->project_name}}</a>
            <div class="price">
                <span>Giá </span>
                <span>:
                    {{number_format($item->project_price)}}
                    {{$item->unit_name}}
                </span>
                </div>
                <div class="area">
                    <span>Diện tích</span>
                    <span>: {{number_format($item->project_scale)}}
                        ha
                    </span>
                </div>
                <div class="location">
                    <span>Vị trí</span>
                    <span>: {{$item->district_name}}, {{$item->province_name}}</span>
                </div>
                <div class="status">
                    <span>Tình trạng</span>
                    <span>:
                        <span class="green"> {{$item->progress_name}} </span>
                    </span>

                    <a href="#" class="update update-project" data-action="3" data-group-id="{{$item->group_id}}"
                       data-href="{{route('home.project.update-project', $item->id)}}"
                       data-selected="{{$item->project_progress}}">(Cập nhật)</a>
                </div>
            </div>
            <div class="map">
                <a href="javascript:void(0);" class="js-view-map view-map" data-id="{{ $item->id }}">
                    <img src="{{ asset('frontend/images/map-marker-1.png') }}">
                    Xem bản đồ
                </a>
            </div>
        </div>
    </div>
@empty
<div class="project-list-wrapper project-notfound-wrapper pt-2" style="width: 100%;border:0;">
        <div class="notfound-img">
            <img src="{{asset('frontend/images/notfound.jpg')}}">
        </div>
        <h4>Không tìm thấy dự án phù hợp</h4>
        <h5>Nếu bạn muốn hiển thị dự án đang tìm kiếm. Bạn có thể yêu cầu cập nhật thêm dự án theo mẫu dưới</h5>

            @if($errors->any())
               <div class="alert alert-danger alert-project">
                   <ul>
                       @foreach ($errors->all() as $error)
                           <li>{{$error}}</li>
                       @endforeach
                   </ul>
               </div>
            @endif

        <form action="{{route('home.project.request-project')}}" id="notfound-form" method="post">
            @csrf
            <input type="text" name="project_name" placeholder="Nhập tên dự án cần cập nhật">
            <input type="text" name="investor" placeholder="Nhập chủ đầu tư">
            <input type="text" name="address" placeholder="Nhập địa chỉ">

            <select name="province_id" id="province_request" class="select2" onchange="get_district(this, '{{route('param.get_district')}}', '#district_request', null, {{old('province_id')}})">
                <option value="">Tỉnh/Thành Phố</option>
                @foreach($provinces as $item)
                    <option data-url="{{$item->province_url}}" @if(old('province_id') == $item->id) selected @endif value="{{$item->id}}">{{$item->province_name}}</option>
                @endforeach
            </select>

            <select name="district_id" id="district_request" class="select2"
                    onchange="get_ward(this, '{{route('param.get_ward')}}', '#ward_request', {{old('province_id') ?? "null"}} , {{old('district_id') ?? "null"}})">
                <option value="">Quận/Huyện</option>
            </select>

            <select name="ward_id" id="ward_request" class="select2">
                <option value="">Phường/Xã</option>
            </select>

            <input type="submit" value="Gửi">
        </form>
    </div>
@endforelse
