<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">{{ $activeLabel }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          @foreach ($parents as $parent)
            <li class="breadcrumb-item">
              @if(data_get($parent, 'route') && Route::has(data_get($parent, 'route')))
                <a href="{{ route(data_get($parent, 'route')) }}">{{ data_get($parent, 'label') }}</a>
              @else
                {{ data_get($parent, 'label') }}
              @endif
            </li>
          @endforeach
          <li class="breadcrumb-item active">{{ $activeLabel }}</li>
        </ol>
      </div>
    </div>
  </div>
</div>
