<div class="row keyword-form group-load-address">
  <div class="col-md-6">
    <x-common.text-input
      label="Lượt tìm kiếm"
      name="views"
      type="number"
      step="1"
      value="{{ old('views', $keyword->views) }}"
      required
    />
  </div>

  <div class="col-md-6">
    <x-common.select2-input
      label="Loại"
      name="target_type"
      :items="$types"
      item-text="label"
      item-value="value"
      :items-current-value="old('target_type', $keyword->target_type)"
      required
    />
  </div>
  <div class="col-md-6 is-type-district {{ old('target_type', $keyword->target_type) == 'App\Models\District' ? '' : 'd-none' }}">
    <x-common.select2-input
      label="Tỉnh/Thành Phố"
      input-class="province-load-district"
      name="province_id"
      placeholder="-- Tỉnh/Thành Phố  --"
      :items="$provinces"
      item-text="province_name"
      items-select-name="province_id"
      :items-current-value="old('province_id', data_get($keyword->featuredable, 'province_id'))"
      required
    />
  </div>
  <div class="col-md-6 is-type-district {{ old('target_type', $keyword->target_type) == 'App\Models\District' ? '' : 'd-none' }}">
    <x-common.select2-input
      label="Quận/Huyện"
      input-class="district-province"
      name="district_id"
      placeholder="-- Quận/Huyện --"
      :data-selected="old('district_id', $keyword->target_id)"
      required
    />
  </div>

  <div class="col-md-6 is-type-group {{ old('target_type', $keyword->target_type) == 'App\Models\Group' ? '' : 'd-none' }}">
    <div class="form-group">
      <label>
        Mô hình
        <span class="text-danger">*</span>
      </label>
      <select name="paradigm_id" class="custom-select select2 {{ $errors->has('paradigm_id') ? 'is-invalid' : '' }}">
        <option value="">--- Mô hình ---</option>
        @foreach($paradigms as $item)
          <option value="{{ $item->id }}" {{ old('paradigm_id', $keyword->target_id) == $item->id ? 'selected' : '' }}>{{ $item->group_name }}</option>
          @if($item->has('children'))
            @foreach($item->children as $i)
              <option value="{{ $i->id }}" {{ old('paradigm_id', $keyword->target_id) == $i->id ? 'selected' : '' }}>--- {{ $i->group_name }}</option>
              @if($i->has('children'))
                @foreach($i->children as $ii)
                  <option value="{{ $ii->id }}" {{ old('paradigm_id', $keyword->target_id) == $ii->id ? 'selected' : '' }}>------ {{ $ii->group_name }}</option>
                @endforeach
              @endif
            @endforeach
          @endif
        @endforeach
      </select>

      {{ showError($errors, 'paradigm_id') }}
    </div>
  </div>

  <div class="col-md-6">
    <x-common.select2-input
      label="Tình trạng"
      name="is_active"
      :items="$statuses"
      item-text="label"
      item-value="value"
      :items-current-value="old('is_active', $keyword->is_active)"
      required
    />
  </div>
</div>

@push('scripts')
  <script type="text/javascript">
    (() => {
      $('.keyword-form [name="target_type"]').on('change', function() {
        let type = $(this).val();

        switch (type) {
          case 'App\\Models\\Group':
            $('.keyword-form .is-type-district').addClass('d-none');
            $('.keyword-form .is-type-group').removeClass('d-none');
            break;
          case 'App\\Models\\District':
            $('.keyword-form .is-type-group').addClass('d-none');
            $('.keyword-form .is-type-district').removeClass('d-none');
            break;
          default:
            $('.keyword-form .is-type-group').addClass('d-none');
            $('.keyword-form .is-type-district').addClass('d-none');
            break;
        }
      })
    })()
  </script>
@endpush