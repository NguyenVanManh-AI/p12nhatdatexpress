<!-- modal them khách hang -->
<div class="modal fade" id="edit-customer" role="dialog" aria-hidden="true">
{{-- tabindex="-1" add for allow esc close --}}
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header flex-center bg-sub-main">
                <h4 class="modal-title text-white fs-18">CẬP NHẬT THÔNG TIN KHÁCH HÀNG</h4>
                <button type="button" class="close custom-close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('user.update-customer')}}" class="form form-chat form-popup " method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @include('user.customer.partials._form', [
                        'select2Parent' => '#edit-customer'
                    ])

                    <input type="number" value="{{ old('id') }}" name="id" hidden>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
