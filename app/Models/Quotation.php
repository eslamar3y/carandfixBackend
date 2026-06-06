<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'customer_name', 'ref_no', 'generated_at', 'attn', 'from_person',
        'title', 'fax', 'your_ref', 'subject', 'items', 'sub_total',
        'total_words', 'delivery', 'terms', 'footer_note',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'date',
            'items' => 'array',
        ];
    }

    protected static function booted()
    {
        static::saving(function (self $quotation) {
            $number = (int) ((float) ($quotation->sub_total ?? 0));
            $quotation->total_words = ucfirst(static::numberToWords($number));
        });
    }

    private static function numberToWords(int $number): string
    {
        $ones = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
            'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
        $tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

        if ($number < 20) return $ones[$number];
        if ($number < 100) {
            $remainder = $number % 10;
            return $tens[intval($number / 10)] . ($remainder ? ' ' . $ones[$remainder] : '');
        }
        if ($number < 1000) {
            $remainder = $number % 100;
            $result = $ones[intval($number / 100)] . ' hundred';
            return $result . ($remainder ? ' and ' . static::numberToWords($remainder) : '');
        }
        if ($number < 1000000) {
            $remainder = $number % 1000;
            $result = static::numberToWords(intval($number / 1000)) . ' thousand';
            return $result . ($remainder ? ' ' . static::numberToWords($remainder) : '');
        }
        return (string) $number;
    }
}
