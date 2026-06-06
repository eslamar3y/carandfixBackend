<?php

namespace App\Filament\Resources\Quotation;

use App\Filament\Resources\Quotation\Pages\ManageQuotations;
use App\Models\Quotation;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;
    protected static ?string $slug = 'quotations';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('Quotations');
    }

    public static function getModelLabel(): string
    {
        return __('Quotation');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Quotations');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.Invoices');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Quotation::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('customer_name')->label('Customer Name / اسم العميل')->required(),
            TextInput::make('ref_no')->label('Ref No')->required(),
            DatePicker::make('generated_at')->label('Date / تاريخ')->default(now())->required(),
            TextInput::make('attn')->label('Attn'),
            TextInput::make('from_person')->label('From'),
            TextInput::make('title')->label('Title'),
            TextInput::make('fax')->label('Fax'),
            TextInput::make('your_ref')->label('Your Ref'),
            TextInput::make('subject')->label('Subject'),
            Repeater::make('items')
                ->label(__('Items'))
                ->columnSpanFull()
                ->schema([
                    TextInput::make('description')->label('Description')->required(),
                    Select::make('unit')
                        ->label('Unit')
                        ->options(['PCS' => 'PCS', 'SRV' => 'SRV'])
                        ->default('PCS')
                        ->required(),
                    TextInput::make('qty')->label('Qty')->numeric()->required()->default(1)->extraInputAttributes(['onwheel' => 'return false']),
                    TextInput::make('unit_price')->label('Price')->numeric()->required()->extraInputAttributes(['onwheel' => 'return false']),
                    TextInput::make('amount')->label('Amount')->numeric()->required()->extraInputAttributes(['onwheel' => 'return false']),
                ])
                ->columns(5)
                ->addActionLabel(__('Add Item')),
            TextInput::make('sub_total')->label('Sub Total'),


        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchOnBlur()
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('customer_name')->label('Customer')->searchable(),
                TextColumn::make('ref_no')->label('Ref No')->searchable(),
                TextColumn::make('sub_total')->label('Total'),
                TextColumn::make('generated_at')->label('Date')->date(),

            ])
            ->recordActions([
                ViewAction::make()
                    ->modalWidth('7xl')
                    ->mutateRecordDataUsing(fn(array $data, Model $record): array => array_merge($data, [
                        'id' => $record->id,
                        'customer_name' => $record->customer_name ?? '-',
                        'ref_no' => $record->ref_no ?? '-',
                        'generated_at_formatted' => $record->generated_at?->format('Y-m-d') ?? '-',
                        'attn' => $record->attn ?? '-',
                        'from_person' => $record->from_person ?? '-',
                        'title' => $record->title ?? '-',
                        'fax' => $record->fax ?? '-',
                        'your_ref' => $record->your_ref ?? '-',
                        'subject' => $record->subject ?? '-',
                        'items' => $record->items ?? [],
                        'sub_total' => $record->sub_total ?? '0',
                        'total_words' => $record->total_words ?? '-',
                    ]))
                    ->form(fn(Schema $schema): Schema => $schema->columns(1)->components([
                        \Filament\Schemas\Components\Html::make(function ($get) {
                            $html = static::renderQuotationHtml($get);
                            preg_match('/<body>(.*?)<\/body>/s', $html, $match);
                            $body = $match[1] ?? $html;
                            preg_match('/<style>(.*?)<\/style>/s', $html, $styleMatch);
                            $style = $styleMatch[1] ?? '';
                            return '<div style="background:#e8e8e8;padding:20px;display:flex;justify-content:center;min-height:800px">
                                <div style="background:white;width:210mm;min-height:297mm;padding:10mm;box-shadow:0 2px 12px rgba(0,0,0,0.15);align-self:flex-start;box-sizing:border-box">
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
                            'ref_no' => $record->ref_no ?? '-',
                            'generated_at_formatted' => $record->generated_at?->format('Y-m-d') ?? '-',
                            'attn' => $record->attn ?? '-',
                            'from_person' => $record->from_person ?? '-',
                            'title' => $record->title ?? '-',
                            'fax' => $record->fax ?? '-',
                            'your_ref' => $record->your_ref ?? '-',
                            'subject' => $record->subject ?? '-',
                            'items' => $record->items ?? [],
                            'sub_total' => $record->sub_total ?? '0',
                            'total_words' => $record->total_words ?? '-',
                            'delivery' => $record->delivery ?? '-',
                            'terms' => $record->terms ?? '',
                            'footer_note' => $record->footer_note ?? '',
                        ]);
                        $get = fn(string $key) => $data[$key] ?? null;
                        $html = static::renderQuotationHtml($get, forPdf: true);
                        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
                        $refNumber = preg_replace('/[^0-9]/', '', $record->ref_no ?? '0');
                        $filename = str_pad($refNumber, 4, '0', STR_PAD_LEFT) . '.pdf';
                        return response()->streamDownload(fn() => print($pdf->output()), $filename);
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ManageQuotations::route('/')];
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

    public static function numberToWords(int $number): string
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

    private static function shapeArabic(string $text): string
    {
        require_once base_path('vendor/ar-php/ar-php/I18N/Arabic/Glyphs.php');
        $glyphs = new \I18N_Arabic_Glyphs();
        return $glyphs->utf8Glyphs($text);
    }

    public static function renderQuotationHtml($get, bool $forPdf = false): string
    {
        $logo = static::getLogoBase64();
        $customerName = $get('customer_name') ?? '-';
        $refNo = $get('ref_no') ?? '-';
        $date = $get('generated_at_formatted') ?? date('Y-m-d');
        $attn = $get('attn') ?? '-';
        $fromPerson = $get('from_person') ?? '-';
        $title = $get('title') ?? '-';
        $fax = $get('fax') ?? '-';
        $yourRef = $get('your_ref') ?? '-';
        $subject = $get('subject') ?? '-';
        $subTotal = $get('sub_total') ?? '0';
        $totalWords = $get('total_words') ?? '-';
        $delivery = $get('delivery') ?? '-';
        $terms = $get('terms') ?? '';
        $footerNote = $get('footer_note') ?? '';

        $s = $forPdf ? fn($t) => static::shapeArabic($t) : fn($t) => $t;

        $dir = $forPdf ? 'ltr' : 'rtl';
        $footerStyle = $forPdf ? 'position:fixed;bottom:0;left:0;right:0;width:100%' : 'width:100%;margin-top:30px';

        $arPhone = $s('الجوال:');
        $arAddress = $s('الدوحة - المنطقة 26');
        $arStreet = $s('شارع 940 - النجمة');
        $arOffice = $s('مكتب 201');
        $arEmail = $s('البريد الإلكتروني:');
        $arCustomer = $s('اسم العميل');
        $arRefNo = $s('الرقم المرجعي');
        $arDate = $s('تاريخ');
        $arAttn = $s('الموجه إلى');
        $arFrom = $s('من');
        $arTitle = $s('المسمى');
        $arFax = $s('فاكس');
        $arYourRef = $s('مرجعك');
        $arSubject = $s('الموضوع');
        $arSlNo = $s('الرقم');
        $arDescription = $s('التفاصيل');
        $arUnit = $s('الوحدة');
        $arQty = $s('الكمية');
        $arUnitPrice = $s('سعر الوحدة');
        $arAmount = $s('المبلغ');
        $arSubTotal = $s('المجموع');
        $arTotal = $s('الإجمالي');

        if ($forPdf) {
            $contactPhone = '+97477000451 ' . $arPhone;
            $contactEmail = 'info@clickandfixqa.com ' . $arEmail;
            $headingRight = $contactPhone . '<br>' . $arAddress . '<br>' . $arStreet . '<br>' . $arOffice . '<br>' . $contactEmail;
        } else {
            $contactPhone = $arPhone . ' +97477000451';
            $contactEmail = $arEmail . ' info@clickandfixqa.com';
            $headingRight = $contactPhone . '<br>' . $arAddress . '<br>' . $arStreet . '<br>' . $arOffice . '<br>' . $contactEmail;
        }

        $items = $get('items') ?? [];
        if (!is_array($items)) $items = [];

        $itemsRows = '';
        $slNo = 1;
        foreach ($items as $item) {
            $desc = $item['description'] ?? '-';
            $unit = $item['unit'] ?? 'PCS';
            $qty = (int) ($item['qty'] ?? 1);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $amount = (float) ($item['amount'] ?? ($qty * $unitPrice));
            $description = $s(e($desc));

            $itemsRows .= '<tr>
                <td style="text-align:center;padding:4px 2px;border:1px solid #000;font-size:10px">' . $slNo . '</td>
                <td style="text-align:center;padding:4px 6px;border:1px solid #000;font-size:10px">' . $description . '</td>
                <td style="text-align:center;padding:4px 2px;border:1px solid #000;font-size:10px">' . e($unit) . '</td>
                <td style="text-align:center;padding:4px 2px;border:1px solid #000;font-size:10px">' . $qty . '</td>
                <td style="text-align:center;padding:4px 2px;border:1px solid #000;font-size:10px">' . number_format($unitPrice, 2) . '</td>
                <td style="text-align:center;padding:4px 2px;border:1px solid #000;font-size:10px">' . number_format($amount, 2) . '</td>
            </tr>';
            $slNo++;
        }

        $page1 = '
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
                <td class="contact-left" style="width:30%">
                    <b>Phone:</b> +97477000451<br>
                    <b>AlDoha - Area 26</b><br>
                    <b>Str 940 - Najma</b><br>
                    <b>Office 201</b><br>
                    <b>Email:</b> Info@clickandfixqa.com
                </td>
                <td class="center" style="width:40%;direction:ltr">'
                    . ($logo ? '<img src="' . $logo . '" style="max-height:100px;width:auto;display:block;margin:0 auto" alt="Logo"><br>' : '')
                    . '<div style="font-size:10px">' . $s('كليك اند فيكس للسيارات') . '</div>
                    <div style="font-size:14px;font-weight:bold">Click and Fix</div>
                    <div style="font-size:16px;font-weight:bold;margin-top:4px">Quotation</div>
                </td>
                <td style="width:30%;vertical-align:top;padding:10px;font-size:10px;text-align:right">
                    <div style="direction:rtl;unicode-bidi:embed">' . $headingRight . '</div>
                </td>
            </tr>
        </table>

        <!-- CUSTOMER INFO TABLE -->
        <table class="bordered" style="margin-bottom:8px">
            <tr>
                <td rowspan="2" style="width:15%;padding:4px;border:1px solid #000;font-size:10px;font-weight:bold">To </td>
                <td rowspan="2" style="width:35%;padding:4px;border:1px solid #000;font-size:10px">' . $s(e($customerName)) . '</td>
                <td style="width:15%;padding:4px;border:1px solid #000;font-size:10px;font-weight:bold">Ref No </td>
                <td style="width:35%;padding:4px;border:1px solid #000;font-size:10px">' . e($refNo) . '</td>
            </tr>
            <tr>
                <td style="padding:4px;border:1px solid #000;font-size:10px;font-weight:bold">Date</td>
                <td style="padding:4px;border:1px solid #000;font-size:10px">' . e($date) . '</td>
            </tr>
            <tr>
                <td style="padding:4px;border:1px solid #000;font-size:10px;font-weight:bold">Attn </td>
                <td style="padding:4px;border:1px solid #000;font-size:10px">' . $s(e($attn)) . '</td>
                <td style="padding:4px;border:1px solid #000;font-size:10px;font-weight:bold">From </td>
                <td style="padding:4px;border:1px solid #000;font-size:10px">' . $s(e($fromPerson)) . '</td>
            </tr>
            <tr>
                <td style="padding:4px;border:1px solid #000;font-size:10px;font-weight:bold">Title </td>
                <td style="padding:4px;border:1px solid #000;font-size:10px">' . e($title) . '</td>
                <td style="padding:4px;border:1px solid #000;font-size:10px;font-weight:bold">Fax </td>
                <td style="padding:4px;border:1px solid #000;font-size:10px">' . e($fax) . '</td>
            </tr>
            <tr>
                <td style="padding:4px;border:1px solid #000;font-size:10px;font-weight:bold">Your Ref </td>
                <td style="padding:4px;border:1px solid #000;font-size:10px">' . $s(e($yourRef)) . '</td>
                <td style="padding:4px;border:1px solid #000;font-size:10px;font-weight:bold">Subject </td>
                <td style="padding:4px;border:1px solid #000;font-size:10px">' . $s(e($subject)) . '</td>
            </tr>
        </table>

        <!-- OPENING -->
        <p style="font-size:10px;margin:6px 0">Dear Sir,</p>
        <p style="font-size:10px;margin:6px 0">We have a pleasure in submitting our offer for you as follows</p>

        <!-- ITEMS TABLE -->
        <table class="bordered" style="margin-bottom:8px">
            <thead>
                <tr>
                    <th style="text-align:center;padding:6px 2px;border:1px solid #000;font-size:10px;background:#f0f0f0">' . $arSlNo . '<br>Sl No.</th>
                    <th style="text-align:center;padding:6px 2px;border:1px solid #000;font-size:10px;background:#f0f0f0">' . $arDescription . '<br>DISCRIPTION</th>
                    <th style="text-align:center;padding:6px 2px;border:1px solid #000;font-size:10px;background:#f0f0f0">' . $arUnit . '<br>Unit</th>
                    <th style="text-align:center;padding:6px 2px;border:1px solid #000;font-size:10px;background:#f0f0f0">' . $arQty . '<br>Qty</th>
                    <th style="text-align:center;padding:6px 2px;border:1px solid #000;font-size:10px;background:#f0f0f0">' . $arUnitPrice . '<br>Unit Price Qrs.</th>
                    <th style="text-align:center;padding:6px 2px;border:1px solid #000;font-size:10px;background:#f0f0f0">' . $arAmount . '<br>Amount Qrs.</th>
                </tr>
            </thead>
            <tbody>
                ' . $itemsRows . '
            </tbody>
        </table>

        <!-- TOTALS -->
        <table style="width:50%;float:right;margin-bottom:8px">
            <tr>
                <td style="padding:4px;border:1px solid #000;font-size:10px;font-weight:bold;text-align:center">Sub Total / ' . $arSubTotal . '</td>
                <td style="padding:4px;border:1px solid #000;font-size:10px;text-align:center">' . e($subTotal) . '</td>
            </tr>
            <tr>
                <td style="padding:4px;border:1px solid #000;font-size:10px;font-weight:bold;text-align:center">Total QAR / ' . $s('الريال القطري') . '</td>
                <td style="padding:4px;border:1px solid #000;font-size:10px;text-align:center"><b>' . e($totalWords) . '</b></td>
            </tr>
        </table>

        <div style="clear:both;height:20px"></div>

        <!-- FOOTER -->
        <div style="' . $footerStyle . '">
        <table style="width:100%;margin:0 auto;padding:4px 10px">
            <tr>
                <td style="width:33%;vertical-align:top;font-size:10px;text-align:center;padding:4px">
                    <b>Validity</b>
                </td>
                <td style="width:33%;vertical-align:top;font-size:10px;text-align:center;padding:4px">
                    <b>Upon Request</b>
                </td>
                <td style="width:34%;vertical-align:top;font-size:10px;text-align:center;padding:4px">
                    <b>Terms & Conditions</b>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:left;font-size:10px;padding:4px">
                    <b>Delivery:</b> ' . e($delivery) . '
                </td>
            </tr>
        </table>
        <table style="width:100%;margin:0 auto;padding:4px 10px">
            <tr>
                <td style="width:75%;vertical-align:top;font-size:9px;text-align:left;padding:2px;direction:ltr">
                    <span style="font-size:9px">' . e($footerNote ?: 'Hope our rates are acceptable to you and awaiting your valued order. Please feel to contact us on +97477000451 for any information of your interest.') . '</span>
                </td>
                <td style="width:25%;vertical-align:top;text-align:right;font-size:9px;padding:2px">
                    ' . (static::getStampBase64()
                        ? '<img src="' . static::getStampBase64() . '" style="max-width:120px;max-height:120px;width:auto;height:auto" alt="Stamp">'
                        : '') . '
                </td>
            </tr>
        </table>
        </div>

        </body>
        </html>';

        $page2Terms = $terms ?: 'All quotations and references to costs and financial commitments made or made by the
seller are based on the assumption that the correctness of the information provided is
absolutely accurate and true in all circumstances. Seller reserves the right at any stage to
renegotiate any contract, cost agreement or other related obligation in the event that any
information provided by the Buyer fails to be completely true and accurate and this offer is
expedited for 48 hours from the date of order.';

        $page2Body = '
        <!-- HEADER -->
        <table class="header-table" style="width:100%;margin-bottom:12px;direction:ltr">
            <tr>
                <td class="contact-left" style="width:30%">
                    <b>Phone:</b> +97477000451<br>
                    <b>AlDoha - Area 26</b><br>
                    <b>Str 940 - Najma</b><br>
                    <b>Office 201</b><br>
                    <b>Email:</b> Info@clickandfixqa.com
                </td>
                <td class="center" style="width:40%;direction:ltr">'
                    . ($logo ? '<img src="' . $logo . '" style="max-height:60px;width:auto" alt="Logo"><br>' : '')
                    . '<div style="font-size:10px">' . $s('كليك اند فيكس للسيارات') . '</div>
                    <div style="font-size:14px;font-weight:bold">Click and Fix</div>
                </td>
                <td style="width:30%;vertical-align:top;padding:10px;font-size:10px;text-align:right">
                    <div style="direction:rtl;unicode-bidi:embed">' . $headingRight . '</div>
                </td>
            </tr>
        </table>

        <h2 style="text-align:left;font-size:14px;margin:20px 0">Terms And Conditions</h2>

        <div style="font-size:12px;line-height:1.6;direction:ltr;text-align:left;width:210mm;margin-left:-10mm;padding:0 10mm;box-sizing:border-box">
            ' . nl2br(e($page2Terms)) . '
        </div>';
        

        if ($forPdf) {
            $page1 = str_replace('</body>', '<div style="page-break-before: always;"></div>' . $page2Body . '</body>', $page1);
            return $page1;
        }

        return $page1;
    }
}
