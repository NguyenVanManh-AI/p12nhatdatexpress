@extends('Admin.Layouts.Master')
@section('Title', 'Thêm khuyến mãi | Mã khuyến mãi')
@section('Style')
<link rel="stylesheet" type="text/css" href="{{ asset("system/css/admin-project.css")}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section('Content')
<section>
 <div class="row m-0 px-3 pt-3">
  <ol class="breadcrumb mt-1">
    <li class="recye px-2 pt-1  check">
      <a href="{{route('admin.promotion.list-news-promotion')}}">
        <i class="fa fa-th-list mr-1"></i>Danh sách
      </a>
    </li>
    @if($check_role == 1  ||key_exists(5, $check_role))
    <li class="phay ml-2">
      /
    </li>
    <li class="recye px-2 pt-1 ml-1">
      <a href="{{route('admin.promotion.news-trash')}}">
        Thùng rác
      </a>
    </li>
    @endif
    @if($check_role == 1  ||key_exists(1, $check_role))
    <li class="ml-2 phay">
      /
    </li>
    <li class="add px-2 pt-1 ml-1 check active">

        <i class="fa fa-edit mr-1"></i>Thêm

    </li>
    @endif
  </ol>
</div>
  <h4 class="text-center font-weight-bold mb-0 mt-2">THÊM TIN KHUYẾN MÃI</h4>
<form action="{{route('admin.promotion.post-add-news-promotion')}}" method="post" enctype="multipart/form-data" id="add-news-promotion">
  @csrf
  <div class="row m-0 p-3">
    <div class="col-12 p-0">
     <label>Tiêu đề bài viết <span class="required"></span></label>
     @if($errors->has('news_title'))
     <small class="text-danger mt-1 float-right" style="font-size: 90%">
      {{$errors->first('news_title')}}
    </small>
    @endif
    <input class="form-control required" type="text" name="news_title" >

  </div>
  <div class="col-12 p-0 mt-2">
   <label>Mô tả ngắn <span class="required"></span></label>
   @if($errors->has('news_description'))
   <small class="text-danger mt-1 float-right" style="font-size: 90%">
    {{$errors->first('news_description')}}
  </small>
  @endif
  <textarea class="form-control" name="news_description"></textarea>
</div>

<div class="col-12 p-0 mt-3">
 <label>Mã khuyến mãi áp dụng <span class="required"></span></label>
 <div class="form-group select-tags">
  <style type="text/css">
    .chosen-container-multi .chosen-choices{
      padding:  4px 5px 8px 8px !important;
      height: 38px !important;
      background-image: none !important;
    }
  </style>
  <select name="promotion_id" class="chosen" data-order="true"  id="multiselect"  style="height: 38px" >
    <option value="" selected="">Không đính kèm mã</option>
    @foreach ($list_code_0 as $item)

    <option value="{{$item->id}}">[Mã: {{$item->promotion_code}}] {{$item->promotion_type ? 'Tặng' : 'Giảm'}}: {{$item->value}}% - Số lượng mã: {{$item->user_get}}/{{$item->num_use}} - Ngày khả dụng: {{\Carbon\Carbon::parse($item->date_from)->format('d/m/Y')}} đến {{\Carbon\Carbon::parse($item->date_to)->format('d/m/Y')}} </option>
    @endforeach
  </select>




</div>

</div>
<div class="col-12 col-sm-12 col-md-3 col-lg-3 p-0 ">
 <label>Ảnh đại diện</label><br>
 @if($errors->has('image'))
 <small class="text-danger mt-1 float-right" style="font-size: 90%">
  {{$errors->first('image')}}
</small>
@endif
<div >
 <div class="button-choose-image text-center " style="width: 100%;border:1px solid #ccc">
  <p>Chọn hình ảnh</p>
  <input accept="image/*" type='file' id="imgInp" name="image" style="width: 100%">
</div>
<img class="mt-2" id="blah" src="{{ asset("system/image/upload-file.png")}}" alt="your image"  width="100%" height="120px" style="border: 1px solid #ccc" />

</div>

</div>

<div class="col-12 p-0 mt-2">
  <label>Nội dung <span class="required"></span></label>
  @if($errors->has('news_content'))
  <small class="text-danger mt-1 float-right" style="font-size: 90%">
    {{$errors->first('news_content')}}
  </small>
  @endif
  <textarea class="js-admin-tiny-textarea"  name="news_content" style="width: 100%;height: 300px" required>

  </textarea>
  <div id="editerInput"></div>


</div>



<div class="add-category col-12 p-0 mtdow10">



  <div class="row m-0 mt-3">
    <div class="col-6">
      <button class="btn btn-primary float-right" style="border-radius: 0;">Hoàn tất</button>
    </div>
    <div class="col-6">
      <button class="btn btn-secondary" style="border-radius: 0;" onclick="resetForm()">Làm lại</button>
    </div>
  </div>

</div>
</div>
</form>
</div>





</div>



</section>
<!-- /.content -->
@endsection
@section('Script')
{{-- editor --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('system/js/validate/add-news-promotion.validate.js') }}"></script>
<script src="js/table.js"></script>

<script type="text/javascript">
 $(document).ready(function(){
  $("#s2id_autogen1").keyup(function(){
    $('#checkall').removeAttr('Checked','Checked');
    $('#checkprivate').removeAttr('Checked','Checked');
  });

  $("#s2id_autogen1").click(function(){
   if($('#select2-search-choice div').text()!= null){
    $('#checkall').removeAttr('Checked','Checked');
    $('#checkprivate').removeAttr('Checked','Checked');
  }
});

});
</script>

<script type="text/javascript">
  $(".chosen").chosen({
    width: "300px",
    enable_search_threshold: 10
  }).change(function(event)
  {
    if(event.target == this)
    {
      var value = $(this).val();
      $("#result").text(value);
    }
  });
  // $('.chosen-search-input').val("Nhập danh sách")
</script>
<script type="text/javascript">
 imgInp.onchange = evt => {
  const [file] = imgInp.files
  if (file) {
    blah.src = URL.createObjectURL(file)
    $('#blah').show();
  }
}
</script>

@endsection
