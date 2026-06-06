@php
    $scores = array_filter([
        $record->chassis_percent,
        $record->exterior_percent,
        $record->road_test_percent,
        $record->power_train_percent,
        $record->electrical_percent,
        $record->braking_percent,
        $record->suspension_percent,
        $record->ac_cooling_percent,
    ], fn($v) => $v !== null);
    $avg = count($scores) > 0 ? round(array_sum($scores) / count($scores)) : 0;

    [$gaugeColor, $label] = match (true) {
        $avg <= 50 => ['#ef4444', __('Bad')],
        $avg <= 60 => ['#6b7280', __('Poor')],
        $avg <= 70 => ['#f97316', __('Fair')],
        $avg <= 80 => ['#eab308', __('Good')],
        $avg <= 90 => ['#3b82f6', __('Very Good')],
        default    => ['#22c55e', __('Excellent')],
    };

    $radius = 54;
    $circ = 2 * pi() * $radius;
    $offset = $circ * (1 - $avg / 100);
@endphp

<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;padding:16px">
    <svg width="160" height="160" viewBox="0 0 120 120">
        <circle cx="60" cy="60" r="{{ $radius }}" fill="none" stroke="#1f2937" stroke-width="8" />
        <circle cx="60" cy="60" r="{{ $radius }}" fill="none" stroke="{{ $gaugeColor }}" stroke-width="8"
            stroke-dasharray="{{ $circ }}" stroke-dashoffset="{{ $offset }}"
            stroke-linecap="round" transform="rotate(-90,60,60)" />
        <text x="60" y="54" text-anchor="middle" fill="#f3f4f6" font-size="28" font-weight="bold" font-family="system-ui">{{ $avg }}%</text>
        <text x="60" y="76" text-anchor="middle" fill="#9ca3af" font-size="12" font-family="system-ui">{{ $label }}</text>
    </svg>
    <div style="margin-top:6px;font-size:13px;color:#6b7280">
        {{ __('Decision') }}: <span style="color:{{ $gaugeColor }};font-weight:600">{{ $record->final_decision ?? __('N/A') }}</span>
    </div>
</div>
