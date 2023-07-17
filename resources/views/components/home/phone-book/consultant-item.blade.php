<div class="col-md-4 col-sm-6">
    <div class="item">
        <div class="image px-2 pt-2">
            <div class="image-ratio-box-square relative w-100">
              <a href="{{ $item->getExpertAvatar() }}" class="absolute-full js-fancy-box">
                <img class="object-cover lazy" data-src="{{ $item->getExpertAvatar() }}" alt="">
              </a>
            </div>
            <div class="box-rating position-absolute start-0 bottom-0 w-100 text-center py-2">
                {!! renderRating($item->rating) !!}
            </div>
        </div>

        <div class="info">
            <a href="{{ route('trang-ca-nhan.dong-thoi-gian', $item->user_code) }}" class="name font-weight-bold w-100">
                <h3 class="bold fs-18 text-ellipsis w-100 {{ $item->is_highlight ? 'link-red-flat' : 'link-dark' }}">
                  {{ $item->getFullName() }}
                </h3>
            </a>

            <p class="address flex-start mb-2 fs-14">
                <i class="fas fa-map-marker-alt"></i>
                <span>
                    {{ $item->location ? $item->location->getSortAddress() : '' }}
                </span>
            </p>
            {{-- <p class="email">
                <i class="fas fa-envelope"></i>
                <x-common.mail mail="{{ $item->email }}" />
            </p> --}}
            <div class="contact flex-wrap">
                <x-user.phone-number
                    :user="$item"
                    class="text-gray fs-14"
                >
                    <x-slot name="icon">
                    <i class="content-icon fas fa-phone-alt"></i>
                    </x-slot>
                </x-user.phone-number>

                <ul class="list-social">
                    <li class="fb"><a href="{{$item->facebook}}"><i class="fab fa-facebook-f"></i></a></li>
                    <li class="twitter"><a href="{{$item->twitter}}"><i class="fab fa-tiktok"></i></a></li>
                    <li class="google"><a href="#"><i class="fab fa-google"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
