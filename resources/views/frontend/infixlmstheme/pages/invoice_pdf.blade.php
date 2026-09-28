@php use Modules\Store\Entities\ProductSku; @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>Invoice - ETC-INV-{{ date('Y') }}-{{ str_pad($enroll->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        .header-table {
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: top;
        }
        .logo-img {
            max-height: 80px;
        }
        .title {
            color: #1a237e;
            font-size: 32px;
            font-weight: bold;
            text-align: right;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .invoice-details {
            text-align: left;
            width: 100%;
        }
        .invoice-details td {
            padding: 4px 0;
            font-size: 13px;
        }
        .invoice-details-label {
            width: 120px;
            color: #555;
        }
        .invoice-details-value {
            color: #333;
        }
        .status-paid {
            color: #4CAF50;
            font-weight: bold;
        }
        .status-unpaid {
            color: #f44336;
            font-weight: bold;
        }
        .company-info-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .company-info-table td {
            width: 50%;
            vertical-align: top;
            font-size: 12px;
            color: #444;
        }
        .company-info-table .right-info {
            text-align: right;
        }
        .section-title {
            color: #1a237e;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #1a237e;
            padding-bottom: 5px;
        }
        .billing-course-table {
            margin-bottom: 30px;
        }
        .billing-course-table td {
            width: 50%;
            vertical-align: top;
        }
        .billing-info p, .course-info p {
            margin: 0 0 5px 0;
            font-size: 13px;
            color: #333;
        }
        .items-table th {
            background-color: #1a237e;
            color: white;
            padding: 12px;
            font-size: 13px;
            text-align: left;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        .items-table .text-right {
            text-align: right;
        }
        .items-table .text-center {
            text-align: center;
        }
        .totals-table {
            width: 50%;
            float: right;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        .totals-table td {
            padding: 6px 12px;
            text-align: right;
            font-size: 13px;
        }
        .totals-table .total-row {
            font-weight: bold;
            font-size: 15px;
            border-top: 2px solid #1a237e;
            border-bottom: 2px solid #1a237e;
            color: #1a237e;
        }
        .totals-table .balance-due {
            color: #4CAF50;
            font-weight: bold;
        }
        .payment-info-box {
            clear: both;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            margin-bottom: 30px;
        }
        .payment-info-title {
            background-color: #e9ecef;
            padding: 10px 15px;
            font-weight: bold;
            color: #1a237e;
            font-size: 13px;
        }
        .payment-info-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 13px;
        }
        .payment-info-table tr:last-child td {
            border-bottom: none;
        }
        .payment-info-label {
            width: 250px;
            color: #555;
        }
        .footer-note {
            font-size: 11px;
            color: #777;
            text-align: left;
            margin-top: 20px;
        }
        .footer-center {
            text-align: center;
            font-size: 11px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        @php
            if (isModuleActive('Store')) {
                $is_paid = $enroll->status == 1 && $enroll->is_paid == 1;
            }else {
                $is_paid = $enroll->status == 1;
            }
            $invoice_no = "ETC-INV-" . date('Y', strtotime(@$enroll->created_at)) . "-" . str_pad($enroll->id, 4, '0', STR_PAD_LEFT);
            $language_code = auth()->user()->language_code ?? 'en';
        @endphp
        <table class="header-table">
            <tr>
                <td style="width: 50%;">
                    <img src="{{ getCourseImage(Settings('logo')) }}" alt="Logo" class="logo-img">
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="title">TAX INVOICE</div>
                    <table class="invoice-details" style="float: right; width: auto;">
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
                                @if($is_paid || $enroll->courses->sum('purchase_price') == 0)
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
            <tr>
                <td style="padding-right: 20px;">
                    <div class="section-title">BILLED TO</div>
                    <div class="billing-info">
                        <p><strong>{{ @$enroll->bill->first_name }} {{ @$enroll->bill->last_name }}</strong></p>
                        <p>{{ @$enroll->bill->email }}</p>
                        <p>{{ @$enroll->bill->phone }}</p>
                    </div>
                </td>
                <td style="padding-left: 20px;">
                    <div class="section-title">COURSE DETAILS</div>
                    <div class="course-info">
                        @if(isset($enroll->courses) && $enroll->courses->count() > 0)
                            <p><strong>{{ @$enroll->courses->first()->course->getTranslation('title', $language_code) }}</strong></p>
                        @elseif(isset($enroll->bookings) && $enroll->bookings->count() > 0)
                            <p><strong>Appointment</strong></p>
                        @elseif(isset($enroll->gifts) && $enroll->gifts->count() > 0)
                            <p><strong>{{ @$enroll->gifts->first()->course->getTranslation('title', $language_code) }}</strong></p>
                        @else
                            <p><strong>Course/Training</strong></p>
                        @endif
                        <p>Training Mode: Classroom / Live Online</p>
                        <p>Currency: AED</p>
                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-center">VAT</th>
                    <th class="text-right">Amount</th>
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
                                <td>{{ @$item->userInfo->name }} - {{showDate($item->schedule->schedule_date)}}</td>
                                <td class="text-center">{{ $qty }}</td>
                                <td class="text-right">{{ getPriceFormat($price, false) }}</td>
                                <td class="text-center">5%</td>
                                <td class="text-right">{{ getPriceFormat($item_amount, false) }}</td>
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
                            <td class="text-right">{{ getPriceFormat($price, false) }}</td>
                            <td class="text-center">5%</td>
                            <td class="text-right">{{ getPriceFormat($item_amount, false) }}</td>
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
                                <td class="text-right">{{ getPriceFormat($price1, false) }}</td>
                                <td class="text-center">5%</td>
                                <td class="text-right">{{ getPriceFormat($item_amount, false) }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endif
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td>{{ getPriceFormat($total, false) }}</td>
            </tr>
            <tr>
                <td>VAT @ 5%</td>
                <td>{{ getPriceFormat($tax_total, false) }}</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL (INCL. VAT)</td>
                <td>{{ getPriceFormat($total + $tax_total, false) }}</td>
            </tr>
            <tr>
                <td>Amount Paid</td>
                <td>{{ getPriceFormat($is_paid ? ($total + $tax_total) : 0, false) }}</td>
            </tr>
            <tr>
                <td>Balance Due</td>
                <td class="balance-due">{{ getPriceFormat($is_paid ? 0 : ($total + $tax_total), false) }}</td>
            </tr>
        </table>

        <div style="clear:both;"></div>

        <div class="payment-info-box">
            <div class="payment-info-title">PAYMENT INFORMATION</div>
            <table class="payment-info-table" style="width: 100%;">
                <tr>
                    <td class="payment-info-label">Payment Method</td>
                    <td>{{ $enroll->payment_method == 'Wallet' ? __('payment.Wallet') : $enroll->payment_method }}</td>
                </tr>
                <tr>
                    <td class="payment-info-label">Transaction Reference</td>
                    <td>{{ @$enroll->order_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="payment-info-label">Payment Date</td>
                    <td>{{ date('d F Y', strtotime(@$enroll->created_at)) }}</td>
                </tr>
            </table>
        </div>

        <p class="footer-note">Note: This is a system-generated tax invoice for LMS implementation. Student, invoice, course, payment and transaction fields can be populated automatically by the LMS.</p>
        
        <div class="footer-center">
            <p>This is a system-generated invoice and does not require a signature.</p>
            <p>Elegant Training Center | www.elegant-training.ae</p>
        </div>
    </div>
</body>
</html>
