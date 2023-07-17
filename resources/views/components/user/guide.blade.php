<div class="rounded-0 {{ $attributes['class'] ?: 'card mh-450' }}">
  <h5 class="card-header bg-dark-main text-white text-center fs-16 rounded-0">Hướng dẫn</h5>
  <div class="card-body text-break">
    {!! data_get($guide, 'config_value') !!}
  </div>
</div>