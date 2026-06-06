<div style="text-align: center; padding: 0.5rem;" wire:poll.1s="refresh">
    <div class="clock-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 0.25rem; color: #6366f1;">
        {{ __('Time') }}
    </div>
    <div class="clock-value" style="font-size: 2rem; font-weight: 700; color: #374151;">
        {{ $time }}
    </div>
</div>

<style>
    .dark .clock-label { color: #818cf8 !important; }
    .dark .clock-value { color: #f3f4f6 !important; }
</style>
