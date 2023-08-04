<!-- modal them khách hang -->
<div class="modal fade" id="create-customer" role="dialog">
{{-- tabindex="-1" add for allow esc close --}}
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header flex-center bg-sub-main">
                <h4 class="modal-title text-white fs-18">CẬP NHẬT DANH SÁCH KHÁCH HÀNG</h4>
                <button type="button" class="close custom-close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('user.create-customer')}}" class="form form-chat form-popup " method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @include('user.customer.partials._form', [
                        'select2Parent' => '#create-customer'
                    ])

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Thêm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
