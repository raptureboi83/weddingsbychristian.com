<x-mail::layout>
    {{-- Header --}}
    <x-slot:header>
        <x-mail::header :url="config('app.url')">
@php
    $settings = \App\Models\SiteSetting::current();
    $logoPath = $settings?->email_logo_path;
@endphp
@if ($logoPath)
{{ config('app.name') }}
@else
{{ config('app.name') }}
@endif
        </x-mail::header>
    </x-slot:header>

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        <x-slot:subcopy>
            <x-mail::subcopy>
                {{ $subcopy }}
            </x-mail::subcopy>
        </x-slot:subcopy>
    @endisset

    {{-- Footer --}}
    <x-slot:footer>
        <x-mail::footer>
            &copy; Weddingsbychristian.com. All rights reserved.
        </x-mail::footer>
    </x-slot:footer>
</x-mail::layout>
