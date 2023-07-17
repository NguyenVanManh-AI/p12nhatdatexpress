<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{asset('frontend/images/logo.png')}}" class="logo" alt="Nhà đất express Logo" style="height: 100%; max-height: unset; width: unset">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
