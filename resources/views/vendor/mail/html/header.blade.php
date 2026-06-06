@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo-v2.1.png" class="logo" alt="Laravel Logo">
@else
<span style="color: #ffffff; font-size: 24px; font-weight: bold;">{{ $slot }}</span>
@endif
</a>
</td>
</tr>
