@php
    use Modules\Store\Entities\ProductSku;

    if (isModuleActive('Store')) {
        $is_paid = $enroll->status == 1 && $enroll->is_paid == 1;
    } else {
        $is_paid = $enroll->status == 1;
    }
    $invoice_no = "ETC-INV-" . date('Y', strtotime(@$enroll->created_at)) . "-" . str_pad($enroll->id, 4, '0', STR_PAD_LEFT);
    $language_code = auth()->user()->language_code ?? 'en';

    $logo_path = public_path('images/elegant_training_logo.png');
    if (!file_exists($logo_path)) {
        $logo_path = public_path('frontend/infixlmstheme/img/elegant_training_logo.png');
    }
    if (file_exists($logo_path)) {
        $logo_src = 'data:image/png;base64,' . base64_encode(file_get_contents($logo_path));
    } else {
        $logo_src = getCourseImage(Settings('logo'));
    }

    $formatPrice = function($amount) {
        return 'AED ' . number_format((float)$amount, 2);
    };
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice_no }}</title>
    <style>
        @page {
            margin: 0;
            size: a4 portrait;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            -webkit-print-color-adjust: exact;
        }
        .top-stripe {
            height: 10px;
            background-color: #e31b23;
            width: 100%;
        }
        .bottom-stripe {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 7px;
            background-color: #1479be;
            width: 100%;
        }
        .invoice-box {
            padding: 28px 36px 20px 36px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table {
            margin-bottom: 16px;
        }
        .header-table td {
            vertical-align: top;
        }
        .logo-img {
            max-height: 52px;
            width: auto;
        }
        .title {
            color: #082a5b;
            font-size: 26px;
            font-weight: bold;
            text-align: right;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .invoice-details {
            margin-left: auto;
            margin-right: 0;
            width: auto;
            border-collapse: collapse;
        }
        .invoice-details td {
            padding: 2px 0;
            font-size: 11.5px;
        }
        .invoice-details-label {
            width: 105px;
            color: #333;
            text-align: left;
        }
        .invoice-details-value {
            color: #111;
            text-align: left;
        }
        .status-paid {
            color: #1b873f;
            font-weight: bold;
        }
        .status-unpaid {
            color: #dc2626;
            font-weight: bold;
        }
        .company-info-box {
            background-color: #f8f9fa;
            border: 1px solid #dce2e6;
            padding: 10px 14px;
            margin-bottom: 22px;
            border-radius: 2px;
        }
        .company-info-table td {
            width: 50%;
            vertical-align: top;
            font-size: 11px;
            line-height: 1.45;
            color: #333;
        }
        .company-info-table .right-info {
            text-align: right;
            color: #444;
        }
        .billing-course-table {
            margin-bottom: 24px;
            border-collapse: collapse;
        }
        .billing-course-table .header-row td {
            color: #082a5b;
            font-size: 12.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding-bottom: 5px;
            border-bottom: 2px solid #082a5b;
        }
        .billing-course-table .content-row td {
            padding-top: 8px;
            vertical-align: top;
            font-size: 11.5px;
            line-height: 1.5;
            color: #333;
        }
        .items-table {
            border: 1px solid #dce2e6;
            margin-bottom: 16px;
            border-collapse: collapse;
        }
        .items-table th {
            background-color: #082a5b;
            color: #ffffff;
            padding: 8px 10px;
            font-size: 11.5px;
            font-weight: bold;
            border-right: 1px solid #2e4d75;
        }
        .items-table th:last-child {
            border-right: none;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #dce2e6;
            border-right: 1px solid #dce2e6;
            font-size: 11.5px;
            color: #333;
        }
        .items-table td:last-child {
            border-right: none;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-table {
            width: 46%;
            float: right;
            margin-top: 4px;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 4px 6px;
            font-size: 11.5px;
        }
        .totals-table .label-col {
            text-align: right;
            color: #444;
            width: 50%;
        }
        .totals-table .val-col {
            text-align: right;
            font-weight: bold;
            color: #111;
            width: 50%;
        }
        .totals-table .total-row td {
            padding: 7px 6px;
            font-size: 12px;
            border-top: 2px solid #082a5b;
            border-bottom: 2px solid #082a5b;
        }
        .totals-table .total-label {
            font-weight: bold;
            color: #082a5b;
            text-transform: uppercase;
            text-align: right;
        }
        .totals-table .total-val {
            font-weight: bold;
            font-size: 15px;
            color: #082a5b;
            text-align: right;
        }
        .totals-table .balance-due-val {
            color: #1b873f;
            font-weight: bold;
            font-size: 12px;
        }
        .payment-info-box {
            clear: both;
            border: 1px solid #dce2e6;
            border-radius: 2px;
            margin-top: 15px;
            margin-bottom: 22px;
        }
        .payment-info-title {
            background-color: #f0f3f6;
            padding: 8px 12px;
            font-weight: bold;
            color: #082a5b;
            font-size: 11.5px;
            text-transform: uppercase;
            border-bottom: 1px solid #dce2e6;
        }
        .payment-info-table td {
            padding: 7px 12px;
            border-bottom: 1px solid #eef2f5;
            font-size: 11.5px;
        }
        .payment-info-table tr:last-child td {
            border-bottom: none;
        }
        .payment-info-label {
            width: 180px;
            color: #444;
            border-right: 1px solid #dce2e6;
        }
        .payment-info-value {
            color: #111;
        }
        .footer-note {
            font-size: 10px;
            color: #555;
            text-align: left;
            margin-top: 16px;
            margin-bottom: 16px;
            line-height: 1.4;
        }
        .footer-center {
            text-align: center;
            font-size: 9.5px;
            color: #777;
            margin-top: 14px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="top-stripe"></div>
    <div class="bottom-stripe"></div>

    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <img src="{{ $logo_src }}" alt="Elegant Training" class="logo-img">
                </td>
                <td style="width: 50%; text-align: right; vertical-align: top;">
                    <div class="title">TAX INVOICE</div>
                    <table class="invoice-details">
                        <tr>
                            <td class="invoice-details-label">Invoice No.</td>
                            <td class="invoice-details-value">{{ $invoice_no }}</td>
                        </tr>
                        <tr>
                            <td class="invoice-details-label">Invoice Date</td>
                            <td class="invoice-details-value">{{ date('d F Y', strtotime(@$enroll->created_at)) }}</td>
                        </tr>
                        <tr>
                            <td class="invoice-details-label">Payment Status</td>
                            <td class="invoice-details-value">
                                @if($is_paid || (isset($enroll->courses) && $enroll->courses->sum('purchase_price') == 0))
                                    <span class="status-paid">PAID</span>
                                @else
                                    <span class="status-unpaid">UNPAID</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="company-info-box">
            <table class="company-info-table">
                <tr>
                    <td>
                        <strong>ELEGANT PROFESSIONAL & MANAGEMENT DEVELOPMENT TRAINING</strong><br>
                        Office No. 620, Al Attar Business Center, Al Barsha 1, Dubai,<br>
                        United Arab Emirates<br>
                        TRN: 100510151200003
                    </td>
                    <td class="right-info">
                        Elegant Training Center<br>
                        Approved & Permitted by KHDA<br>
                        www.elegant-training.ae
                    </td>
                </tr>
            </table>
        </div>

        <table class="billing-course-table">
            <tr class="header-row">
                <td style="width: 50%; padding-right: 20px;">BILLED TO</td>
                <td style="width: 50%; padding-left: 20px;">COURSE DETAILS</td>
            </tr>
            <tr class="content-row">
                <td style="padding-right: 20px;">
                    <div><strong>{{ @$enroll->bill->first_name }} {{ @$enroll->bill->last_name }}</strong></div>
                    <div>{{ @$enroll->bill->email }}</div>
                    <div>{{ @$enroll->bill->phone }}</div>
                </td>
                <td style="padding-left: 20px;">
                    @if(isset($enroll->courses) && $enroll->courses->count() > 0)
                        <div>{{ @$enroll->courses->first()->course->getTranslation('title', $language_code) }}</div>
                    @elseif(isset($enroll->bookings) && $enroll->bookings->count() > 0)
                        <div>Appointment</div>
                    @elseif(isset($enroll->gifts) && $enroll->gifts->count() > 0)
                        <div>{{ @$enroll->gifts->first()->course->getTranslation('title', $language_code) }}</div>
                    @else
                        <div>Course/Training</div>
                    @endif
                    <div>Training Mode: Classroom / Live Online</div>
                    <div>Currency: AED</div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 44%; text-align: left;">Description</th>
                    <th style="width: 10%; text-align: center;">Qty</th>
                    <th style="width: 16%; text-align: right;">Unit Price</th>
                    <th style="width: 12%; text-align: center;">VAT</th>
                    <th style="width: 18%; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $tax_total = 0;
                @endphp
                
                @if(isModuleActive('Appointment') && $enroll->type=='appointment')
                    @if (isset($enroll->bookings))
                        @foreach ($enroll->bookings as $key => $item)
                            @php
                                $price = $item->purchase_price;
                                $qty = 1;
                                $item_amount = $price * $qty;
                                $item_tax = $enroll->tax ?? ($item_amount * 0.05);
                                $total += $item_amount;
                                $tax_total += $item_tax;
                            @endphp
                            <tr>
                                <td>{{ @$item->userInfo->name }} - {{ showDate($item->schedule->schedule_date) }}</td>
                                <td class="text-center">{{ $qty }}</td>
                                <td class="text-right">{{ $formatPrice($price) }}</td>
                                <td class="text-center">5%</td>
                                <td class="text-right">{{ $formatPrice($item_amount) }}</td>
                            </tr>
                        @endforeach
                    @endif
                @elseif (isModuleActive('Gift') && $enroll->courses->count() < $enroll->cart_count)
                    @foreach ($enroll->gifts as $gift)
                        @php
                            $price = $gift->course->discount_price != 0 ? $gift->course->discount_price : $gift->course->price;
                            $qty = 1;
                            $item_amount = $price * $qty;
                            $item_tax = $enroll->tax ?? ($item_amount * 0.05);
                            $total += $item_amount;
                            $tax_total += $item_tax;
                        @endphp
                        <tr>
                            <td>{{ @$gift->course->getTranslation('title', $language_code) }}</td>
                            <td class="text-center">{{ $qty }}</td>
                            <td class="text-right">{{ $formatPrice($price) }}</td>
                            <td class="text-center">5%</td>
                            <td class="text-right">{{ $formatPrice($item_amount) }}</td>
                        </tr>
                    @endforeach
                @else
                    @if (isset($enroll->courses))
                        @foreach ($enroll->courses as $key => $item)
                            @php
                                $price1 = $item->purchase_price;
                                $qty = $item->qty > 0 ? $item->qty : 1;
                                $item_amount = $price1 * $qty;
                                $item_tax = $enroll->tax ?? ($item_amount * 0.05);
                                $total += $item_amount;
                                $tax_total += $item_tax;
                            @endphp
                            <tr>
                                <td>{{ @$item->course->getTranslation('title', $language_code) }}</td>
                                <td class="text-center">{{ $qty }}</td>
                                <td class="text-right">{{ $formatPrice($price1) }}</td>
                                <td class="text-center">5%</td>
                                <td class="text-right">{{ $formatPrice($item_amount) }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endif
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td class="label-col">Subtotal</td>
                <td class="val-col">{{ $formatPrice($total) }}</td>
            </tr>
            <tr>
                <td class="label-col">VAT @ 5%</td>
                <td class="val-col">{{ $formatPrice($tax_total) }}</td>
            </tr>
            <tr class="total-row">
                <td class="total-label">TOTAL (INCL. VAT)</td>
                <td class="total-val">{{ $formatPrice($total + $tax_total) }}</td>
            </tr>
            <tr>
                <td class="label-col">Amount Paid</td>
                <td class="val-col">{{ $formatPrice($is_paid ? ($total + $tax_total) : 0) }}</td>
            </tr>
            <tr>
                <td class="label-col">Balance Due</td>
                <td class="val-col balance-due-val">{{ $formatPrice($is_paid ? 0 : ($total + $tax_total)) }}</td>
            </tr>
        </table>

        <div style="clear: both;"></div>

        <div class="payment-info-box">
            <div class="payment-info-title">PAYMENT INFORMATION</div>
            <table class="payment-info-table">
                <tr>
                    <td class="payment-info-label">Payment Method</td>
                    <td class="payment-info-value">{{ $enroll->payment_method == 'Wallet' ? __('payment.Wallet') : ($enroll->payment_method ?? 'Online Payment / Card') }}</td>
                </tr>
                <tr>
                    <td class="payment-info-label">Transaction Reference</td>
                    <td class="payment-info-value">{{ @$enroll->order_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="payment-info-label">Payment Date</td>
                    <td class="payment-info-value">{{ date('d F Y', strtotime(@$enroll->created_at)) }}</td>
                </tr>
            </table>
        </div>

        <p class="footer-note">Note: This is a sample system-generated tax invoice for LMS implementation. Student, invoice, course, payment and transaction fields can be populated automatically by the LMS.</p>

        <div class="footer-center">
            <p style="margin: 0 0 4px 0;">This is a system-generated invoice and does not require a signature.</p>
            <p style="margin: 0;">Elegant Training Center | www.elegant-training.ae</p>
        </div>
    </div>
</body>
</html>
