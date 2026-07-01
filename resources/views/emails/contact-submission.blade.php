@php
    use Illuminate\Support\HtmlString;
@endphp

<x-mail::message>
# New Inquiry from {{ $submission->name }}

<x-mail::panel>
**{{ $submission->name }}** — {{ $submission->email }}
@if ($submission->phone)
  • {{ $submission->phone }}
@endif
</x-mail::panel>

@if ($submission->wedding_date)
**Wedding Date:** {{ $submission->wedding_date->format('F j, Y') }}
@endif

@if ($submission->venue)
**Venue / Location:** {{ $submission->venue }}
@endif

@if ($submission->message)
---

{{ $submission->message }}
@endif

<x-mail::button :url="url('/admin/contact-submissions/' . $submission->id)">
View in Admin
</x-mail::button>
</x-mail::message>
