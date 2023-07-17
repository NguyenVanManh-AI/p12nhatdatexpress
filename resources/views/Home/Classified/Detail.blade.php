@extends('Home.Layouts.Master')

@section('Title',$item->meta_title??$item->classified_name)
@section('Keywords',$item->meta_key??$item->classified_name)
@section('Description',$item->meta_desc??$item->classified_description)
@section('Image', $item->getSeoBanner())

@section('Content')
    <div id="property-single" class="classified-detail-page">
            <div class="row">
                <div class="col-md-7-3">
                    <x-home.classified.content-main :item="$item" :group="$group" :keySearch="$key_search"></x-home.classified.content-main>
                    <div class="project-review">
                        <div class="head">
                            <i class="fas fa-user-circle"></i>
                            Đánh giá
                        </div>

                        <div class="detail-review-result-box" data-url="/classifieds/rating/{{ $item->id }}">
                            <x-common.detail.review-result
                                :item="$item"
                            />
                        </div>
                    </div>

                    <div
                        class="project-comment comment-area js-comment-section"
                        data-url="classifieds"
                        data-id="{{ $item->id }}"
                    >
                        <x-common.detail.comment
                            :comments="$comment"
                            detail-type="classifieds"
                        />
                    </div>
                </div>

                <div class="col-md-3-7 mb-3">
                    @if($item->user)
                        <x-home.user.agency-detail
                            :user="$item->user"
                            advisory-url="{{ route('home.classifieds.send-advisory', $item->id) }}"
                        />
                    @else
                        <x-home.user.agency-not-login
                            :item="$item"
                            advisory-url="{{ route('home.classifieds.send-advisory', $item->id) }}"
                        />
                    @endif
                </div>
            </div>
            <div class="row" style="padding: 0">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-7-3">
                            <x-home.classified.relate
                                :classified-id="$item->id"
                            />
                        </div>
                        <div class="col-md-3-7 project-sidebar-width">
                            <x-home.classified.near :group="$group ? $group->getAncestorId() : null" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <x-home.classified.current/>
                </div>
            </div>
    </div>
    <x-home.report-modal></x-home.report-modal>
@endsection

@push('init_map')
    let initLocation = {
        lat: parseFloat($('.classified-detail-page [name="latitude"]').val() || 0),
        lng: parseFloat($('.classified-detail-page [name="longtitude"]').val() || 0),
    }

    initSimpleMap('classified-detail-page__map', initLocation);
@endpush

@section('Script')
    <script>
        $(".share-button .share-sub .item").on("click", function (event) {
            event.preventDefault();

            let url = '/chi-tiet-tin-rao/share/{{ $item->id }}';
            $.ajax({
                type: 'post',
                url: url,
                success: function (data) {
                },
                error: function (error) {
                }
            });
            window.location.href = $(this).attr('href');
        });

        $(".wrapper.wrapper-regis .red").on("click", function (event) {
            event.preventDefault();
            $(".agency .reg-form #reg-form").submit();
        });
        $(".agency .reg-form #reg-form").submit(function(event){
            event.preventDefault();
            let url = '/chi-tiet-tin-rao/customer/{{$item->user->id ?? null }}';
            $.ajax({
                type: 'post',
                url: url,
                data: $(this).serialize()+'&classified_id={{$item->id}}',
                success: data => {
                    if (data && data.message) {
                        if (data.success) {
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message);
                        }
                    }
                },
                error: err => {
                    if (err && err.responseJSON && err.responseJSON.message)
                        toastr.error(err.responseJSON.message)
                }
            });
        });
    </script>
    <script>
        $('.report-content').click(function () {
            $('#report_content').show();
            $('#layout').show();
            $('#report_content').find('form').attr('action', '{{route('home.classified.report-content','')}}' + '/' + $(this).data('id'));

        });
        $('body').on('click', '.button-report .report_comment', function () {
            $('#report_comment').show();
            $('#layout').show();
            $('#report_comment').find('form').attr('action', '{{route('home.classified.report-comment','')}}' + '/' + $(this).data('comment_id'));
        });
    </script>
@endsection
