@php
    $submission = $reply->submission;
@endphp

<x-mail::message>
# {{ $reply->sender_name }} replied to {{ $submission->name }}'s inquiry

<x-mail::panel>
{{ $reply->body }}
</x-mail::panel>

<small style="color: #6b7280; font-size: 12px;">
Simply reply to this email to continue the conversation.
</small>
</x-mail::message>
