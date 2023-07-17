{{-- should change old $properties --}}
<div class="detail-inner classified-detail-table-inner">
  <div class="detail-title">Thông tin chi tiết</div>

  <div class="row detail-table m-0 border-left">
    <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
      <div class="py-1 d-flex">
        <span class="node">{{ $properties[0]->name }}</span>
        @if ($item->is_monopoly == 1)
          <span class="name text-success bold">
            <i class="fas fa-check"></i>
          </span>
        @else
          <span class="name">
            ---
          </span>
        @endif
      </div>
    </div>

    @if(data_get($item->group, 'parent_id') == 2)
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ data_get($properties, '7.name') }}</span>
          <span class="name text-danger bold text-ellipsis js-content-title">
            <strong>
              {{ $item->getPriceWithUnit() }}
            </strong>
          </span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[16]->name }}</span>
          @if (data_get($item->direction, 'direction_name'))
            <span>
              <a class="name text-blue"
                href="{{ route('home.classified.list', [$item->group->getLastParentGroup(), '']) }}?direction={{ $item->direction->id }}">
                {{ data_get($item, 'direction.direction_name') }}
              </a>
            </span>
          @else
            ---
          @endif
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[1]->name }}</span>
          @if ($item->is_broker == 1)
            <span class="name text-success bold">
              <i class="fas fa-check"></i>
            </span>
          @else
            <span class="name">
              ---
            </span>
          @endif
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[10]->name }}</span>
          <span class="name text-danger bold text-ellipsis js-content-title d-inline-block"
            data-title="{{ $item->classified_area . ' ' . data_get($item->unit_area, 'unit_name') }}"
          >
            <strong>
              {{ $item->classified_area . ' ' . data_get($item->unit_area, 'unit_name') }}
            </strong>
          </span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[17]->name }}</span>
          <span class="name text-ellipsis js-content-title" data-title="{{ data_get($item->juridical, 'param_name', '---') }}">{{ data_get($item->juridical, 'param_name', '---') }}</span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[3]->name }}</span>
          @if (data_get($item, 'project.project_name'))
            <a class="name text-blue text-ellipsis js-content-title"
              {{-- data-title="{{ data_get($item, 'project.project_name') }}" --}}
              href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'project' => $item->project_id]) : 'javascript:void(0);' }}"
            >
              {{ data_get($item, 'project.project_name') }}
            </a>
          @else
            <span class="name ">---</span>
          @endif
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[12]->name }}</span>
          <a class="name"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'num_bed' => $item->num_bed]) : 'javascript:void(0);' }}"
          >
            {{ data_get($item->bed, 'param_name', '---') }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[18]->name }}</span>
          <a class="name text-ellipsis js-content-title"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'group_child' => $item->group->group_url, 'furniture' => $item->classified_furniture]) : 'javascript:void(0);' }}"
          >
            {{ data_get($item->furniture, 'furniture_name', '---') }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ data_get($properties, '2.name') }}</span>
          <span class="name">
            @if($item->group)
              <a class="name text-danger text-ellipsis js-content-title"
                href="{{ $item->group ? route('home.classified.list', [$item->group->getLastParentGroup(), $item->group->group_url]) : 'javascript:void(0);' }}"
              >{{ data_get($item, 'group.group_name') }}</a>
            @else
              ---
            @endif
          </span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[13]->name }}</span>
          <a class="name">
            {{ data_get($item->toilet, 'param_name', '---') }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[23]->name }}</span>
          <span class="name text-success bold"><strong>{{ date('d/m/Y', $item->created_at) }}</strong></span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[4]->name }}</span>
          <span class="name">
            <a class="name text-blue text-ellipsis js-content-title"
              href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'province_id' => data_get($item, 'location.province_id')]) : 'javascript:void(0);' }}">
              {{ $item->getShortAddress() }}
            </a>
          </span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[5]->name }}</span>
          <a class="name text-ellipsis js-content-title"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'group_child' => $item->group->group_url, 'progress' => $item->classified_progress]) : 'javascript:void(0);' }}"
          >
            {{ data_get($item, 'progress.progress_name', '---') }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <div class="item cl-3 row-5">
            <span class="node"></span>
            <span class="name"></span>
          </div>
        </div>
      </div>
    @elseif(data_get($item->group, 'parent_id') == 10)
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ data_get($properties, '8.name') }}</span>
          <span class="name text-danger bold text-ellipsis js-content-title">
            <strong>
              {{ $item->getPriceWithUnit() }}
            </strong>
          </span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[16]->name }}</span>
          @if (data_get($item->direction, 'direction_name'))
            <span>
              <a class="name text-blue"
                href="{{ route('home.classified.list', [$item->group->getLastParentGroup(), '']) }}?direction={{ $item->direction->id }}">
                {{ data_get($item, 'direction.direction_name') }}
              </a>
            </span>
          @else
            ---
          @endif
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[1]->name }}</span>
          @if ($item->is_broker == 1)
            <span class="name text-success bold">
              <i class="fas fa-check"></i>
            </span>
          @else
            <span class="name">
              ---
            </span>
          @endif
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[11]->name }}</span>
          <a class="name text-ellipsis js-content-title"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'advance_value' => $item->advance_stake]) : 'javascript:void(0);' }}"
          >
            {{ data_get($item->advance, 'param_name', '---') }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[19]->name }}</span>
          <a class="name"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'internet' => $item->is_internet]) : 'javascript:void(0);' }}"
          >
            {{ $item->is_internet == 1 ? 'Có' : 'Không' }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[4]->name }}</span>
          <span class="name">
            <a class="name text-blue text-ellipsis js-content-title"
              href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'province_id' => data_get($item, 'location.province_id')]) : 'javascript:void(0);' }}"
            >
              {{ $item->getShortAddress() }}
            </a>
          </span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[10]->name }}</span>
          <span
            class="name text-danger bold text-ellipsis js-content-title"><strong>{{ $item->classified_area . ' ' . data_get($item->unit_area, 'unit_name') }}</strong></span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[20]->name }}</span>
          <a class="name"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'balcony' => $item->is_balcony]) : 'javascript:void(0);' }}"
          >
            {{ $item->is_balcony == 1 ? 'Có' : 'Không' }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[5]->name }}</span>
          <a class="name text-ellipsis js-content-title"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'group_child' => $item->group->group_url, 'progress' => $item->classified_progress]) : 'javascript:void(0);' }}"
          >
            {{ data_get($item, 'progress.progress_name', '---') }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[12]->name }}</span>
          <a class="name"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'num_bed' => $item->num_bed]) : 'javascript:void(0);' }}"
          >
            {{ data_get($item->bed, 'param_name', '---') }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[22]->name }}</span>
          <a class="name"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'mezzanino' => $item->is_mezzanino]) : 'javascript:void(0);' }}"
          >
            {{ $item->is_mezzanino == 1 ? 'Có' : 'Không' }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[6]->name }}</span>
          <a class="name"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'num_people' => $item->num_people]) : 'javascript:void(0);' }}"
          >
            {{ data_get($item->people, 'param_name', '---') }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[14]->name }}</span>
          <a class="name"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'freezer' => $item->is_freezer]) : 'javascript:void(0);' }}"
          >
            {{ $item->is_freezer == 1 ? 'Có' : 'Không' }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[23]->name }}</span>
          <span class="name text-success bold"><strong>{{ date('d/m/Y', $item->created_at) }}</strong></span>
        </div>
      </div>
    @else
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          @if($item->group_parent_url == 'can-mua')
          <span class="node">{{ data_get($properties, '7.name') }}</span>
          @endif
          @if($item->group_parent_url == 'can-thue')
          <span class="node">{{ data_get($properties, '8.name') }}</span>
          @endif
          <span class="name text-danger bold text-ellipsis js-content-title">
            {{ $item->getPriceWithUnit() }}
            {{-- {{ $item->classified_price != null ? '~' . number_format($item->classified_price, 0, '', '.') . ' ' . data_get($item->unit_price, 'unit_name') : 'Liên hệ' }} --}}
          </span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[22]->name }}</span>
          <a class="name"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'mezzanino' => $item->is_mezzanino]) : 'javascript:void(0);' }}"
          >
            {{ $item->is_mezzanino == 1 ? 'Có' : 'Không' }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ data_get($properties, '2.name') }}</span>
          <span class="name">
            @if($item->group)
              <a
                class="name text-danger text-ellipsis js-content-title"
                href="{{ route('home.classified.list', [$item->group->getLastParentGroup(), $item->group->group_url]) }}"
              >
                {{ data_get($item, 'group.group_name') }}
              </a>
            @else
              ---
            @endif
          </span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[10]->name }}</span>
          <span class="name text-danger bold text-ellipsis js-content-title">
            <strong>
              {{ $item->classified_area . ' ' . data_get($item->unit_area, 'unit_name') }}
            </strong>
          </span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[19]->name }}</span>
          <a class="name"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'internet' => $item->is_internet]) : 'javascript:void(0);' }}"
          >
            {{ $item->is_internet == 1 ? 'Có' : 'Không' }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[4]->name }}</span>
          <span class="name">
            <a class="name text-blue text-ellipsis js-content-title"
              href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'province_id' => data_get($item, 'location.province_id')]) : 'javascript:void(0);' }}">
              {{ $item->getShortAddress() }}
            </a>
          </span>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[12]->name }}</span>
          <a class="name"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'num_bed' => $item->num_bed]) : 'javascript:void(0);' }}"
          >
            {{ data_get($item->bed, 'param_name', '---') }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[20]->name }}</span>
          <a class="name"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'balcony' => $item->is_balcony]) : 'javascript:void(0);' }}"
          >
            {{ $item->is_balcony == 1 ? 'Có' : 'Không' }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[16]->name }}</span>
          @if (data_get($item->direction, 'direction_name'))
            <span>
              <a class="name text-blue"
                href="{{ route('home.classified.list', [$item->group->getLastParentGroup(), '']) }}?direction={{ $item->direction->id }}"
              >
                {{ data_get($item, 'direction.direction_name') }}
              </a>
            </span>
          @else
            ---
          @endif
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[14]->name }}</span>
          <a class="name"
            href="{{ $item->group ? route('home.classified.list', ['group_url' => $item->group->getLastParentGroup(), 'freezer' => $item->is_freezer]) : 'javascript:void(0);' }}"
          >
            {{ $item->is_freezer == 1 ? 'Có' : 'Không' }}
          </a>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 col-12 detail-item border border-left-0">
        <div class="py-1 d-flex">
          <span class="node">{{ $properties[23]->name }}</span>
          <span class="name text-success bold"><strong>{{ date('d/m/Y', $item->created_at) }}</strong></span>
        </div>
      </div>
    @endif
  </div>
</div>