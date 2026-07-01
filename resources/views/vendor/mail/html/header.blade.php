@props(['url'])
@php
    $settings = \App\Models\SiteSetting::current();
    $logoPath = $settings?->email_logo_path;
@endphp
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if ($logoPath)
<img src="{{ asset('storage/' . $logoPath) }}" class="logo" alt="{{ config('app.name') }}">
@elseif (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo-v2.1.png" class="logo" alt="Laravel Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
