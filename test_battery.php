<?php
// Simulate the same logic as OrderController
function test($val) {
    return in_array($val ?? '-', ['-', '-1']) ? null : $val;
}

echo "Input: null  -> " . var_export(test(null), true) . "\n";
echo "Input: '-'   -> " . var_export(test('-'), true) . "\n";
echo "Input: '-1'  -> " . var_export(test('-1'), true) . "\n";
echo "Input: '1'   -> " . var_export(test('1'), true) . "\n";
echo "Input: '2'   -> " . var_export(test('2'), true) . "\n";
echo "Done\n";
