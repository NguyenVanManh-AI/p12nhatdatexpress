<div class="widget widget-height widget-website survey-section c-mb-10">
  <div class="widget-title">
    <h3 class="text-center">Khảo sát website</h3>
  </div>
  <div class="flex-between border border-secondary p-2">
    <img class="c-mr-10" src="../frontend/images/Icon-survey.png" alt="">
    <span class="fs-14 text-secondary">Đánh giá của bạn sẽ góp phần xây dựng và cải thiện chất lượng dịch vụ của website
      tốt hơn.</span>
  </div>
  <div class="widget-content">
    <div class="list-event">
      @foreach ($surveyLists as $item)
        <?php $survey = $surveys->where('type', $item['key'])->first(); ?>
        <div class="item mb-2" data-type="{{ $item['key'] }}"
          data-old-rating="{{ session("web_surveys.{$item['key']}") }}">
          <div class="title pr-1">
            <h4 class="fs-15 mb-0">{{ data_get($item, 'text') }}</h4>
          </div>
          <div class="rating flex-between2">
            <div class="survey-box">
              {!! renderRating(session("web_surveys.{$item['key']}")) !!}

              <div class="box-rating-action">
                @for ($i = 1; $i < 6; $i++)
                  <i class="far fa-star cursor-pointer" data-rating="{{ $i }}"></i>
                @endfor
              </div>
            </div>
            <span class="number-servey text-blue fs-13 bold float-right">
              <span class="rating-length">
                {{ number_format(data_get($survey, 'length', 0), 0, ',', '.') }} 
              </span>
              phiếu
            </span>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
@push('scripts_children')
  <script type="text/javascript">
    (() => {
      $('body').on('click', '.survey-section .box-rating-action i', function(e) {
        e.preventDefault()
        e.stopPropagation()

        let _this = $(this),
          $parent = _this.parents('.item'),
          rating = _this.data('rating'),
          type = $parent.data('type'),
          oldRating = $parent.data('old-rating')

        $.ajax({
          url: 'guest/website-survey',
          method: 'POST',
          dataType: 'json',
          data: {
            rating: rating,
            old_rating: oldRating,
            type: type,
          },
          success: res => {
            if (res && res.data && res.data.rating) {
              let ratingData = res.data.rating,
                $actionBox = $('.survey-section').find(`.item[data-type="${ratingData.type}"]`),
                $ratingBox = $actionBox.find('.box-rating')

              if ($actionBox && $actionBox.length) {
                $actionBox.find('.rating-length').text(ratingData.length)
                $actionBox.find('.rating-avg').text(ratingData.avg_rating.toFixed(1))

                // replace stars
                $ratingBox.empty()
                for (let i = 1; i < 6; i++) {
                  $ratingBox.append(i > res.data.rating_value ? `<i class="far fa-star"></i>` :
                    `<i class="fas fa-star"></i>`)
                }

                $actionBox.data('old-rating', res.data.rating_value)
              }

              toastr.success(res.message);
            }
          }
        });
      });

      $('body').on('mouseover', '.survey-section .box-rating-action i', function(event) {
        event.preventDefault();
        let _this = $(this),
          $ratingBox = _this.parents('.box-rating-action'),
          stars = $(this).nextAll();

        $ratingBox.find('i').removeClass('far').addClass('fas')
        stars.removeClass('fas').addClass('far')
      });
    })()
  </script>
@endpush
