<p>
  @if(isset($label))
    <strong>{{ $label }}:</strong>
  @endif

  @if ($item->is_deleted)
    <span class="text-red">Đã xóa</span>
  @elseif($item->is_show)
    <span class="text-success">Hiển thị</span>
  @elseif(get_class($item) == \App\Models\Event\Event::class && $item->is_confirmed == 1)
    <span class="text-success">Hiển thị</span>
  @else
    <span class="text-red">Chặn hiển thị</span>
  @endif
</p>
