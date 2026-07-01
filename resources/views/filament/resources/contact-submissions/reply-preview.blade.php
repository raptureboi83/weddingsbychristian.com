<div style="padding: 8px 0;">
    <p style="margin: 0 0 4px; font-size: 13px; color: #9ca3af;">
        From: <strong style="color: #e5e7eb;">{{ $reply->sender_name }}</strong>
        &middot; {{ $reply->created_at->format('M j, Y g:i A') }}
        @if ($reply->sender_type === 'admin')
            <span style="display: inline-block; padding: 1px 8px; border-radius: 4px; font-size: 11px; background: rgba(34, 197, 94, 0.15); color: #4ade80; margin-left: 6px;">Admin</span>
        @else
            <span style="display: inline-block; padding: 1px 8px; border-radius: 4px; font-size: 11px; background: rgba(156, 163, 175, 0.15); color: #9ca3af; margin-left: 6px;">Client</span>
        @endif
    </p>
    <div style="padding: 16px; background: rgba(255, 255, 255, 0.03); border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.06); white-space: pre-wrap; font-size: 14px; line-height: 1.6; color: #d1d5db;">
        {{ $reply->body }}
    </div>
</div>
