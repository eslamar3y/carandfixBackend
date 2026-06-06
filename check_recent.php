<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Recent orders:\n";
\App\Models\Order::latest()->take(10)->get()->each(function($o) {
    $b = $o->battery_voltage_id ?? 'null';
    echo " ID:{$o->id} type:{$o->type} battery:{$b} created:{$o->created_at}\n";
});

echo "\nTotal with battery_voltage_id:\n";
\App\Models\Order::whereNotNull('battery_voltage_id')->get()->each(function($o) {
    echo " ID:{$o->id} type:{$o->type} battery:{$o->battery_voltage_id}\n";
});

echo "\nDone\n";
