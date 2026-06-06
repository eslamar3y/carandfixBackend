<div style="text-align: center; padding: 0.5rem;">
    <div class="date-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 0.25rem; color: #6366f1;">
        {{ __('Date') }}
    </div>
    <div class="date-value" style="font-size: 1.5rem; font-weight: 700; color: #374151;">
        {{ $date }}
    </div>
</div>

<style>
    .dark .date-label { color: #818cf8 !important; }
    .dark .date-value { color: #f3f4f6 !important; }
</style>
