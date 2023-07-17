@if($results->count())
  <ul class="dropdown-menu-list relative scrollable">
    @foreach($results as $result)
    <li class="border-bottom">
      <a
        class="flex-start w-100 p-2 hover-bg-gray"
        href="{{
          $type == 'classified'
            ? route('home.classified.detail', [$result->group->getLastParentGroup(), $result->classified_url])
            : route('home.project.project-detail', [$result->project_url])
        }}"
      >
        <div class="square-size-45 mr-2 bg-white">
          <img class="lazy object-cover" data-src="{{ asset($result->getThumbnailUrl()) }}">
        </div>

        <div class="d-flex flex-column">
          <div class="flex-start">
            @if($type == 'classified' && $result->isHighlight())
              <img class="icon-squad-2 mr-1" src="{{ asset('frontend/images/unnamed.gif') }}">
            @endif

            <h4 class="result-title text-uppercase text-ellipsis text-break ellipsis-2 fs-12 mb-0 {{ $type == 'classified' && ($result->isVip() || $result->isHighlight()) ? 'link-red-flat' : 'link' }}">
              {{ $type == 'classified' ? $result->classified_name : $result->project_name }}
            </h4>
          </div>
          <span class="fs-12 text-grey miw-content">
            {{ data_get($result, 'location.district.district_name') . ', ' . data_get($result, 'location.province.province_name') }}
          </span>
        </div>
      </a>
    </li>
    @endforeach
  </ul>
  <div class="search-result-body__footer w-100 bg-white flex-between">
    <p class="fs-12 p-2 mb-0 user-select-none">
      Tìm thấy {{ $results->count() }} kết quả
    </p>
    <span class="js-close-search-dropdown cursor-pointer hover-bg-gray flex-center py-2 px-3" title="Đóng">
      <i class="fas fa-caret-left"></i>
    </span>
  </div>
@else
  {{-- <p class="text-center fs-normal p-2 mb-0">{{ __('Không tìm thấy kết quả nào.') }}</p> --}}
@endif