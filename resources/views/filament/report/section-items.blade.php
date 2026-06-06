@php
    /** @var array $items */
    $items ??= isset($getState) ? $getState() : [];
@endphp

@if($items && count($items) > 0)
    <div style="background:#111827;border-radius:8px;overflow:hidden">
        @foreach($items as $item)
            @php
                $dotColor = match ($item['status'] ?? '') {
                    'good', 'excellent' => '#22c55e',
                    'fair', 'moderate'   => '#f97316',
                    'poor', 'bad'        => '#ef4444',
                    default              => '#6b7280',
                };
            @endphp
            <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-bottom:1px solid #1f2937">
                <div style="width:14px;height:14px;border-radius:50%;background:{{ $dotColor }};flex-shrink:0"></div>
                <div style="flex:1;color:#f3f4f6;font-size:14px">{{ $item['name'] ?? '' }}</div>
                @if(!empty($item['notes']))
                    <div style="color:#9ca3af;font-size:12px;text-align:right;max-width:300px">{{ $item['notes'] }}</div>
                @endif
            </div>
        @endforeach
    </div>
@else
    <div style="color:#6b7280;padding:16px;text-align:center;font-size:13px">—</div>
@endif
