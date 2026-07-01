<div class="space-y-4 p-4 rounded-lg border border-gray-200 max-h-96 overflow-y-auto bg-gray-50">
    @forelse($replies as $reply)
        @php
            $isHighlighted = $reply->id === ($highlightId ?? null);
            $isAdmin = $reply->sender_type === 'admin';
        @endphp
        <div class="p-4 rounded-lg text-sm border border-gray-200 shadow-sm {{ $isHighlighted ? 'ring-1 ring-gray-400 bg-gray-100' : ($isAdmin ? 'bg-gray-100' : 'bg-white') }}">
            <div class="flex justify-between items-start mb-1">
                <span class="font-semibold text-xs {{ $isAdmin ? 'text-gray-800' : 'text-gray-600' }}">
                    {{ $isAdmin ? 'Admin' : $reply->sender_name }}
                </span>
                <span class="text-xs text-gray-400">
                    {{ $reply->created_at->format('M j, Y g:i A') }}
                </span>
            </div>
            <p class="m-0 text-gray-700 whitespace-pre-wrap">{{ $reply->body }}</p>
        </div>
    @empty
        <p class="text-gray-400 text-sm text-center py-4">No messages yet.</p>
    @endforelse
</div>
