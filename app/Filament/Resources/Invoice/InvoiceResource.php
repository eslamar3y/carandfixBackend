<?php

namespace App\Filament\Resources\Invoice;

use App\Filament\Resources\Invoice\Pages\ManageInvoices;
use App\Models\Invoice;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $slug = 'invoices';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('nav.Invoices');
    }

    public static function getModelLabel(): string
    {
        return __('Invoice');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.Invoices');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.Invoices');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Invoice::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('customer_name')
                ->label('Customer Name / اسم المستخدم')
                ->required(),
            DatePicker::make('generated_at')
                ->label('Date / تاريخ')
                ->default(now())
                ->required(),
            TextInput::make('payment_method')
                ->label('Payment Method / طريقة الدفع')
                ->default('CASH')
                ->required(),
            Repeater::make('items')
                ->label(__('Items'))
                ->columnSpanFull()
                ->schema([
                    TextInput::make('description')->label('Description')->required(),
                    \Filament\Forms\Components\Select::make('unit')
                        ->label('Unit / الوحدة')
                        ->options(['SRV' => 'SRV', 'PCS' => 'PCS'])
                        ->default('SRV')
                        ->required(),
                    TextInput::make('qty')->label('Qty / الكمية')->numeric()->required()->default(1)->extraInputAttributes(['onwheel' => 'return false']),
                    TextInput::make('unit_price')->label('Price / سعر الوحدة')->numeric()->required()->extraInputAttributes(['onwheel' => 'return false']),
                    TextInput::make('amount')->label('Amount / المبلغ')->numeric()->required()->extraInputAttributes(['onwheel' => 'return false']),
                ])
                ->columns(5)
                ->addActionLabel(__('Add Item')),
            TextInput::make('gross_amount')
                ->label('Gross Amount / المبلغ')
                ->required(),
            TextInput::make('discount_amount')
                ->label('Discount / الخصم'),
            TextInput::make('net_amount')
                ->label('Net Amount / الصافي')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchOnBlur()
            ->columns([
                TextColumn::make('id')->label('Invoice #')->sortable(),
                TextColumn::make('customer_name')->label('Customer')->searchable(),
                TextColumn::make('gross_amount')->label('Gross')->prefix('Qrs. '),
                TextColumn::make('net_amount')->label('Net')->prefix('Qrs. '),
                TextColumn::make('generated_at')->label('Date')->date(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalWidth('7xl')
                    ->mutateRecordDataUsing(fn(array $data, Model $record): array => array_merge($data, [
                        'id' => $record->id,
                        'customer_name' => $record->customer_name ?? '-',
                        'payment_method' => $record->payment_method ?? 'CASH',
                        'generated_at_formatted' => $record->generated_at?->format('Y-m-d') ?? '-',
                    ]))
                    ->form(fn(Schema $schema): Schema => $schema->columns(1)->components([
                        \Filament\Schemas\Components\Html::make(function ($get) {
                        $html = static::renderInvoiceHtml($get);
                            preg_match('/<body>(.*?)<\/body>/s', $html, $match);
                            $body = $match[1] ?? $html;
                            preg_match('/<style>(.*?)<\/style>/s', $html, $styleMatch);
                            $style = $styleMatch[1] ?? '';
                            return '<div style="background:#e8e8e8;padding:10px;overflow-x:auto;min-height:100vh">
                                <div style="background:white;width:210mm;min-height:297mm;padding:10mm;margin:0 auto;box-shadow:0 2px 12px rgba(0,0,0,0.15);box-sizing:border-box">
                                    <style>' . $style . '</style>
                                    ' . $body . '
                                </div>
                            </div>';
                        }),
                    ])),
                Action::make('downloadPdf')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->action(function (Model $record) {
                        $data = array_merge($record->toArray(), [
                            'customer_name' => $record->customer_name ?? '-',
                            'generated_at_formatted' => $record->generated_at?->format('Y-m-d') ?? '-',
                        ]);
                        $get = fn(string $key) => $data[$key] ?? null;
                        $html = static::renderInvoiceHtml($get, forPdf: true);
                        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
                        $filename = str_pad($record->id ?? 0, 6, '0', STR_PAD_LEFT) . '.pdf';
                        return response()->streamDownload(fn() => print($pdf->output()), $filename);
                    }),
                EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        $items = $data['items'] ?? [];
                        if (is_array($items)) {
                            foreach ($items as &$item) {
                                if (!isset($item['description']) && isset($item['description_en'])) {
                                    $item['description'] = $item['description_en'];
                                }
                            }
                            $data['items'] = $items;
                        }
                        return $data;
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ])
            ->defaultSort('created_at', 'desc');
    }

private static function shapeArabicMixed(string $text): string
{
    $tokens = preg_split('/([\x{0600}-\x{06FF}]+|[0-9]+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
    $tokens = array_filter($tokens, fn($t) => $t !== '');
    $tokens = array_reverse($tokens); // قلب ترتيب الأجزاء كله - ضروري للـ PDF فقط

    require_once base_path('vendor/ar-php/ar-php/I18N/Arabic/Glyphs.php');
    $glyphs = new \I18N_Arabic_Glyphs();

    $result = '';
    foreach ($tokens as $token) {
        if (preg_match('/^[\x{0600}-\x{06FF}]+$/u', $token)) {
            $result .= $glyphs->utf8Glyphs($token, true); // شكّل + اعكس الحروف الداخلية فقط
        } else {
            $result .= $token; // أرقام/رموز/مسافات - سيبها زي ما هي، من غير أي reverse أو شيبينج
        }
    }

    return $result;
}

    public static function getPages(): array
    {
        return ['index' => ManageInvoices::route('/')];
    }

    public static function getLogoBase64(): string
    {
        $path = public_path('logoIos.png');
        if (!file_exists($path)) {
            return '';
        }
        $type = mime_content_type($path);
        $data = base64_encode(file_get_contents($path));
        return "data:{$type};base64,{$data}";
    }

    public static function getStampBase64(): string
    {
        $path = storage_path('app/public/22222.png');
        if (!file_exists($path)) {
            return '';
        }
        $type = mime_content_type($path);
        $data = base64_encode(file_get_contents($path));
        return "data:{$type};base64,{$data}";
    }

    private static function shapeArabic(string $text): string
    {
        require_once base_path('vendor/ar-php/ar-php/I18N/Arabic/Glyphs.php');
        $glyphs = new \I18N_Arabic_Glyphs();
        return $glyphs->utf8Glyphs($text);
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

    public static function renderInvoiceHtml($get, bool $forPdf = false): string
    {
        $logo = static::getLogoBase64();
        $invoiceNumber = str_pad($get('id') ?? 0, 6, '0', STR_PAD_LEFT);
        $date = $get('generated_at_formatted') ?? date('Y-m-d');
        $customerName = $get('customer_name') ?? '-';
        $paymentMethod = $get('payment_method') ?? 'CASH';

        // $s = $forPdf ? fn($t) => static::shapeArabic($t) : fn($t) => $t;
        $s = $forPdf ? fn($t) => static::shapeArabicMixed($t) : fn($t) => $t;
        $dir = $forPdf ? 'ltr' : 'rtl';
        $footerStyle = $forPdf ? 'position:fixed;bottom:0;left:0;right:0;width:100%' : 'width:100%;margin-top:30px';

        $arAddress = $s('الدوحة - قطر');
        $arStreet = $s('منطقة 15 - بناية 102');
        $arOffice = $s('ص.ب : 25666');
        $arInvoiceNo = $s('رقم الفاتورة');
        $arDate = $s('تاريخ');
        $arPaymentMethod = $s('طريقة الدفع');
        $arDescription = $s('التفاصيل');
        $arunit = $s('الوحدة');
        $arQty = $s('الكمية');
        $arUnitPrice = $s('سعر الوحدة');
        $arAmount = $s('المبلغ');
        $arGross = $s('المبلغ');
        $arDiscount = $s('الخصم');
        $arNet = $s('الصافي');

        if ($forPdf) {
            $contactPhone = '+97431261045';
        } else {
            $contactPhone = '+97431261045';
        }

        $items = $get('items') ?? [];
        if (!is_array($items)) $items = [];

        $itemsRows = '';
        $slNo = 1;
        foreach ($items as $item) {
            if (isset($item['service'])) {
                $desc = $item['service'] ?? '-';
                $unit = 'Piece';
                $qty = 1;
                $unitPrice = (float) ($item['price'] ?? 0);
                $amount = $unitPrice;
            } else {
                $desc = $item['description'] ?? ($item['description_en'] ?? '-');
                $unit = $item['unit'] ?? 'Piece';
                $qty = (int) ($item['qty'] ?? 1);
                $unitPrice = (float) ($item['unit_price'] ?? 0);
                $amount = (float) ($item['amount'] ?? ($qty * $unitPrice));
            }

            $description = $s(e($desc));

            $itemsRows .= '<tr>
                <td style="text-align:center;padding:6px 4px;border:1px solid #000;font-size:12px">' . $slNo . '</td>
                <td style="text-align:center;padding:6px 8px;border:1px solid #000;font-size:12px">' . $description . '</td>
                <td style="text-align:center;padding:6px 4px;border:1px solid #000;font-size:12px">' . e($unit) . '</td>
                <td style="text-align:center;padding:6px 4px;border:1px solid #000;font-size:12px">' . $qty . '</td>
                <td style="text-align:center;padding:6px 4px;border:1px solid #000;font-size:12px">' . number_format($unitPrice, 2) . '</td>
                <td style="text-align:center;padding:6px 4px;border:1px solid #000;font-size:12px">' . number_format($amount, 2) . '</td>
            </tr>';
            $slNo++;
        }

        $grossAmt = number_format((float) ($get('gross_amount') ?: 0), 2);
        $discAmt = $get('discount_amount') && (float) $get('discount_amount') > 0 ? number_format((float) $get('discount_amount'), 2) : '--';
        $netAmt = number_format((float) ($get('net_amount') ?: 0), 2);

        $grossWords = ucfirst(static::numberToWords((int) ($get('gross_amount') ?: 0)));
        $discountWords = $get('discount_amount') && (float) $get('discount_amount') > 0 ? ucfirst(static::numberToWords((int) $get('discount_amount'))) : '';
        $netWords = ucfirst(static::numberToWords((int) ($get('net_amount') ?: 0)));

        return '
        <!DOCTYPE html>
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            @page { margin: 10mm; size: A4 portrait; }
            body { font-family: "DejaVu Sans", sans-serif; font-size: 11px; color: #000; margin: 0; padding: 0 0 100px 0; }
            table { border-collapse: collapse; width: 100%; }
            .bordered { border: 1px solid #000; }
            .bordered td, .bordered th { border: 1px solid #000; padding: 4px 6px; font-size: 10px; }
            .header-table td { vertical-align: top; padding: 2px 4px; font-size: 10px; }
            .contact-left { text-align: left; direction: ltr; }
            .center { text-align: center; }
            .bold { font-weight: bold; }
            .title { font-size: 14px; font-weight: bold; }
            .footer { font-size: 9px; }
        </style>
        </head>
        <body>

        <!-- HEADER -->
        <table class="header-table" style="width:100%;margin-bottom:8px;direction:ltr">
            <tr>
                <td class="contact-left" style="width:30%;font-size:14px;font-weight:bold">
                    +97431261045<br>
                    Doha - Qatar<br>
                    Zone 15, Building 102<br>
                    P.O. Box: 25666<br>
                    Info@clickandfixqa.com
                </td><td class="center" style="width:40%;direction:ltr">'
                    . ($logo ? '<img src="' . $logo . '" style="max-height:100px;width:auto;display:block;margin:0 auto;" alt="Logo"><br>' : '')
                    . '<div style="font-size:18px;font-weight:bold">' . $s('كليك اند فيكس للسيارات') . '</div>
                    <div style="font-size:18px;font-weight:bold;margin-top:4px">Click and Fix</div>

                    
                </td>
                <td style="width:30%;vertical-align:top;padding:10px;font-size:14px;font-weight:bold;text-align:right">
                    <div style="direction:rtl;unicode-bidi:embed;white-space:nowrap">' . $contactPhone . '</div>
                    <div style="direction:rtl;unicode-bidi:embed;white-space:nowrap">' . $arAddress . '</div>
                    <div style="direction:rtl;unicode-bidi:embed;white-space:nowrap">' . $arStreet . '</div>
                    <div style="direction:rtl;unicode-bidi:embed;white-space:nowrap">' . $arOffice . '</div>
                </td>
            </tr>
        </table>

        <!-- CUSTOMER & INVOICE INFO -->
        <table class="bordered" style="margin-bottom:8px;font-size:12px">
            <tr>
                <td style="width:40%;padding:6px;border:1px solid #000;text-align:center"><b>Masters/Mr ' . $s('السيد\السادة') . '</b></td>
                <td style="width:30%;padding:6px;border:1px solid #000;text-align:center"><b>Invoice No. / ' . $arInvoiceNo . '</b></td>
                <td style="width:30%;padding:6px;border:1px solid #000;text-align:center"><b>Date / ' . $arDate . '</b></td>
            </tr>
            <tr>
                <td style="padding:8px;border:1px solid #000;text-align:center;font-size:14px;font-weight:bold" rowspan="2">' . $s(e($customerName)) . '</td>
                <td style="padding:6px;border:1px solid #000;text-align:center">' . e($invoiceNumber) . '</td>
                <td style="padding:6px;border:1px solid #000;text-align:center">' . e($date) . '</td>
            </tr>
            <tr>
                <td style="padding:6px;border:1px solid #000;text-align:center"><b>Payment Method / ' . $arPaymentMethod . '</b></td>
                <td style="padding:6px;border:1px solid #000;text-align:center;font-weight:bold">' . $paymentMethod . '</td>
            </tr>
        </table>

        <!-- ITEMS TABLE -->
        <table class="bordered" style="margin-bottom:8px">
            <thead>
                <tr>
                    <th style="text-align:center;padding:6px 2px;border:1px solid #000;font-size:12px;background:#f0f0f0">Sl No.</th>
                    <th style="text-align:center;padding:6px 2px;border:1px solid #000;font-size:12px;background:#f0f0f0">Description / ' . $arDescription . '</th>
                    <th style="text-align:center;padding:6px 2px;border:1px solid #000;font-size:12px;background:#f0f0f0">Unit / ' . $arunit . '</th>
                    <th style="text-align:center;padding:6px 2px;border:1px solid #000;font-size:12px;background:#f0f0f0">Qty / ' . $arQty . '</th>
                    <th style="text-align:center;padding:6px 2px;border:1px solid #000;font-size:12px;background:#f0f0f0">Unit Price (Qrs.) / ' . $arUnitPrice . '</th>
                    <th style="text-align:center;padding:6px 2px;border:1px solid #000;font-size:12px;background:#f0f0f0">Amount (Qrs.) / ' . $arAmount . '</th>
                </tr>
            </thead>
            <tbody>
                ' . $itemsRows . '
            </tbody>
        </table>

        <!-- SUMMARY -->
        <table class="bordered" style="margin-bottom:8px;width:100%">
            <tr>
                <td style="width:50%;padding:6px;border:1px solid #000;font-size:12px;text-align:center"><b>Gross Amount - ' . $arGross . '</b></td>
                <td style="width:50%;padding:6px;border:1px solid #000;text-align:center;font-size:12px">' . $grossAmt . ' ' . $grossWords . '</td>
            </tr>
            <tr>
                <td style="width:50%;padding:6px;border:1px solid #000;font-size:12px;text-align:center"><b>Discount - ' . $arDiscount . '</b></td>
                <td style="width:50%;padding:6px;border:1px solid #000;text-align:center;font-size:12px">' . $discAmt . ' ' . $discountWords . '</td>
            </tr>
            <tr>
                <td style="width:50%;padding:6px;border:1px solid #000;font-size:12px;text-align:center"><b>Net Amount - ' . $arNet . '</b></td>
                <td style="width:50%;padding:6px;border:1px solid #000;text-align:center;font-size:13px;font-weight:bold">' . $netAmt . ' ' . $netWords . '</td>
            </tr>
        </table>

        <div style="clear:both;height:20px"></div>

        <!-- FOOTER -->
        <div style="' . $footerStyle . '">
        <table style="width:100%;margin:0 auto;padding:6px 10px">
            <tr>
                <td style="width:25%;vertical-align:top;font-size:9px;text-align:left">
                    <b>Authorized Agent For</b><br>'
                    . ($logo ? '<img src="' . $logo . '" style="max-height:100px;width:auto;margin-top:4px" alt="Logo">' : '')
                . '</td>
                <td style="width:50%;vertical-align:top;font-size:12px;text-align:center">
                    <b style="font-size:14px">WARRANTY TERMS AND CONDITIONS</b><br><br>
                    <span style="font-size:10px;text-align:justify;display:inline-block;max-width:400px">
                    <ul style="list-style-type:disc;padding-left:16px;margin:4px 0">
                        <li>Total will repair power tools within 6 months from date of purchase.</li>
                        <li>The warranty does not cover parts failure due to excessive wear and tear of tool misuse.</li>
                        <li>The warranty does not apply where repairs have been attempted by unauthorized person.</li>
                    </ul>
                    </span>
                </td>
                <td style="width:25%;vertical-align:top;text-align:right;font-size:9px">
                    ' . (static::getStampBase64()
                        ? '<img src="' . static::getStampBase64() . '" style="max-width:140px;max-height:140px;width:auto;height:auto" alt="Stamp">'
                        : '') . '
                </td>
            </tr>
        </table>
        </div>

        </body>
        </html>';
    }
}
