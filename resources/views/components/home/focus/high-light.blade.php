@if ($is_hidden == 0)
  <section class="pt-2 pb-4 focus-highlight-box">
    <div class="">

      @if ($top_1 || $list->count() > 0)
        <div class="row">
          @if ($is_special_group)
            @foreach ($list as $index => $item)
              <div class="col-md-6 mb-md-0 {{ $index == 0 ? 'mb-3' : '' }}">
                @include('home.focus.partials._high-light-main-item', [
                  'item' => $item
                ])
              </div>
            @endforeach
          @else
            <div class="col-md-6 mb-3 mb-md-0">
              @include('home.focus.partials._high-light-main-item', [
                'item' => $top_1,
                'withList' => true
              ])
            </div>
            <div class="col-md-6">
              <div class="highlight-box__list scrollable">
                @foreach($list as $item)
                  <div class="highlight-box__list-item d-flex {{ $loop->index + 1 < count($list) ? 'c-mb-10' : '' }}">
                    <div class="image-ratio-box-75 w-size-120 relative">
                      <div
                        class="absolute-full"
                      >
                        <img class="lazy object-cover" data-src="{{ $item->getImageUrl() }}" alt="">
                        @if ($item->video_url)
                          <a href="javascript:void(0);" class="js-focus__open-html5lightbox"
                            data-id="{{ $item->id }}"
                          >
                            <span
                              class="express-new-item__btn-play hover-shadow-darker flex-center square-size-32 rounded-circle bg-white fs-16">
                              <i class="fas fa-play"></i>
                            </span>
                          </a>
                        @endif
                      </div>
                    </div>
                    <div class="highlight-box__list-item-detail d-flex flex-column justify-content-between miw-1 flex-1 pl-2">
                      <h3 class="title mb-0 text-ellipsis lh-1 pl-3 fs-normal link">
                        @if($item->video_url)
                          <a href="javascript:void(0);"
                            class="link js-focus__open-html5lightbox"
                            data-id="{{ $item->id }}"
                          >
                            {{ $item->news_title }}
                          </a>
                        @else
                          <span class="link">
                            {{ $item->news_title }}
                          </span>
                        @endif
                      </h3>
                      <p class="text-ellipsis ellipsis-2 fs-14 mb-0">
                        {{ $item->news_description }}
                      </p>
                      <div class="text-gray pr-2 fs-14">
                        <i class="far fa-clock"></i>
                        {{ getHumanTimeWithPeriod($item->created_at) }}
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      @endif
    </div>
  </section>
@else
  <div class="pb-4"></div>
@endif
