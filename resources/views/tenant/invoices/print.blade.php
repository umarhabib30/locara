<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ __('Invoice') }}</title>
    @include('common.layouts.style')
    <style>
        @media print {
            .print-image {
                max-width: 30% !important;
            }

            .status-btn-red{
                
            }
        }
    </style>
</head>

<body>

    @php
        $status = '';
        if ($invoice->status == INVOICE_STATUS_PAID) {
            $status = 'Paid';
        } else {
            $dueDate = new \DateTime($invoice->due_date);
            $currentDate = new \DateTime();

            if ($currentDate > $dueDate) {
                $status = 'Overdue';
            } else {
                $status = 'Pending';
            }
        }
    @endphp


    <div class="page-content">
        <div class="container-fluid">


            <div class="row">
                <div class="col-12">
                    <div class="invoice-preview-wrap" id="printDiv1">
                        <div class="row invoice-heading-part">
                            <div class="row" style="justify-content: end; text-align: center">
                                <div class="col-10 text-center">
                                    <img src="{{ getSettingImage('app_logo') }}"
                                    class="print-image"
                                    style="max-width: 10%; height: auto;">
                                    {{-- @if ($owner->print_name)
                                        <img src="{{ assetUrl($owner->folder_name . '/' . $owner->file_name) }}"
                                            class="print-image"
                                            style="max-width: 15%; height: auto;">
                                    @else
                                       
                                    @endif --}}
                                    <h4 style="margin-top: 5px">{{ $invoice->invoice_no }}</h4>
                                    <p>{{ $invoice->updated_at->format('d-m-Y') }}</p>
                                </div>
                                
                                <div class="col-1 text-end" style="padding-top: 45px">
                                    @if ($status === 'Paid')
                                        <div class="status-btn status-btn-green">
                                            {{ __('Paid') }}
                                        </div>
                                    @elseif ($status === 'Pending')
                                        <div class="invoice-heading-right-status-btn">
                                            {{ __('Pending') }}
                                        </div>
                                    @elseif ($status === 'Overdue')
                                        <div class="status-btn status-btn-red mx-1">
                                            {{ __('Overdue') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                           
                        </div>

                        <div class="invoice-address-part">
                            <div class="invoice-address-part-left">
                                <h4 class="invoice-generate-title">{{ __('Pay To') }}</h4>
                                <div class="invoice-address">
                                    @if ($owner->print_name)
                                        <h5>{{ $owner->print_name }}</h5>
                                        <h5>{{ Auth::user()->email }}</h5>
                                        <h6>{{ $owner->print_address }}</h6>

                                        <small>Tax ID : {{ $owner->print_tax_number }}</small>
                                    @else
                                        <h5>{{ getOption('app_name') }}</h5>
                                        <h6>{{ getOption('app_location') }}</h6>
                                        <small>{{ getOption('app_contact_number') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="invoice-address-part-right">
                                <h4 class="invoice-generate-title">{{ __('Invoice To') }}</h4>
                                <div class="invoice-address">
                                    <h5>{{ $tenant->first_name }} {{ $tenant->last_name }}</h5>
                                    <small>{{ $tenant->email }}</small>
                                    <h6>{{ $tenant->property_name }} / <small>{{ $tenant->unit_name }}</small></h6>
                                    <h6>{{ $tenant->property_zip_code }} {{ $tenant->property_city_id }}</h6>

                                </div>
                            </div>

                        </div>

                        <div class="invoice-table-part">

                            <h4 class="invoice-generate-title invoice-heading-color">{{ __('Invoice Items') }}</h4>
                            <hr>
                            <div class="table-responsive" style="overflow-x: auto;">
                                <table class="table"
                                    style="width: 100%; border-spacing: 0; border-collapse: separate;">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left; padding: 8px; background-color: #f8f9fa;">
                                                {{ __('Type') }}
                                            </th>
                                            <th style="text-align: left; padding: 8px; background-color: #f8f9fa;">
                                                {{ __('Description') }}
                                            </th>
                                            <th style="text-align: left; padding: 8px; background-color: #f8f9fa;">
                                                {{ __('Amount') }}
                                            </th>
                                            <th style="text-align: left; padding: 8px; background-color: #f8f9fa;">
                                                {{ __('Tax Rate') }}
                                            </th>
                                            <th style="text-align: left; padding: 8px; background-color: #f8f9fa;">
                                                {{ __('Sales Tax') }}
                                            </th>
                                            <th style="text-align: left; padding: 8px; background-color: #f8f9fa;">
                                                {{ __('Total') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalInvoiceAmount = 0; // Initialize total invoice amount
                                        @endphp

                                        @foreach ($items as $item)
                                            @php
                                                $taxAmount = $item->amount * ($item->tax_rate / 100); // Tax amount
                                                $totalAmount = $item->amount;
                                                $amount = $item->amount - $taxAmount;
                                                $totalInvoiceAmount += $totalAmount;
                                            @endphp

                                            <tr>
                                                <td style="text-align: left; padding: 8px;">
                                                    {{ $item->invoiceType?->name }}</td>
                                                <td style="text-align: left; padding: 8px;">{{ $item->description }}
                                                </td>
                                                <td style="text-align: left; padding: 8px;">
                                                    {{ currencyPrice($amount) }}
                                                </td>
                                                <td style="text-align: left; padding: 8px;">
                                                    {{ number_format($item->tax_rate, 2) }}%
                                                </td>
                                                <td style="text-align: left; padding: 8px;">
                                                    {{ currencyPrice($taxAmount) }}
                                                </td>
                                                <td style="text-align: left; padding: 8px;">
                                                    {{ currencyPrice($item->amount) }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- <tr>
                                            <td colspan="5" style="text-align: right; padding: 8px; font-weight: bold; background-color: #f1f1f1;">
                                                {{ __('Total Invoice Amount') }}
                                            </td>
                                            <td style="text-align: left; padding: 8px; font-weight: bold; background-color: #f1f1f1;">
                                                {{ currencyPrice($totalInvoiceAmount) }}
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>



                            <div class="show-total-box">
                                <div class="invoice-tbl-last-field">{{ __('Late Fee') }}: <span
                                        class="invoice-heading-color">{{ currencyPrice($invoice->late_fee) }}</span>
                                </div>
                                <div class="invoice-tbl-last-field">{{ __('Total') }}: <span
                                        class="invoice-heading-color">{{ currencyPrice($invoice->amount + $invoice->late_fee) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="transaction-table-part">
                            <h4 class="invoice-generate-title invoice-heading-color">{{ __('Transaction Details') }}
                            </h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="invoice-heading-color">{{ __('Date') }}</th>
                                            <th class="invoice-heading-color">{{ __('Gateway') }}</th>
                                            <th class="invoice-heading-color">{{ __('Transaction ID') }}</th>
                                            <th class="invoice-tbl-last-field invoice-heading-color">
                                                {{ __('Amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($order)
                                            <tr>
                                                <td>{{ $order?->created_at->format('d-m-Y') }}</td>
                                                <td>{{ $order?->gatewayTitle ?? __('Cash') }}</td>
                                                <td>{{ $order?->payment_id ? $order?->payment_id : $order?->transaction_id }}
                                                </td>
                                                <td class="invoice-tbl-last-field">{{ currencyPrice($order?->total) }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">{{ __('No Data Found') }}</td>
                                            </tr>
                                        @endisset
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('common.layouts.script')
    <script>
        window.print()
    </script>
</body>

</html>
