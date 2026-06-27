@php
    /** @var \App\Models\MediaFile $record */
    $record = $getRecord();
    $src = $record?->url();
    $type = $record?->type;
@endphp

<div style="display:flex;align-items:center;justify-content:flex-start;">
    @if ($type === 'image' && $src)
        <img
            src="{{ $src }}"
            style="width:2.5rem;height:2.5rem;object-fit:cover;border-radius:0.25rem;flex-shrink:0;"
            loading="lazy"
            alt=""
        />
    @elseif ($type === 'video')
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 40 40"
            style="width:2.5rem;height:2.5rem;flex-shrink:0;"
        >
            <rect width="40" height="40" fill="#f3f4f6" rx="4"/>
            <path d="M16 13.33v13.34l10.67-6.67z" fill="#9ca3af"/>
        </svg>
    @else
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 40 40"
            style="width:2.5rem;height:2.5rem;flex-shrink:0;"
        >
            <rect width="40" height="40" fill="#f3f4f6" rx="4"/>
            <path d="M22 10H14a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V14l-6-4z" fill="#d1d5db" stroke="#9ca3af"/>
            <path d="M22 10v4h4" fill="none" stroke="#9ca3af"/>
        </svg>
    @endif
</div>
