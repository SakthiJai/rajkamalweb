<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>


        body {
            font-family: 'Heebo', sans-serif;
            font-size:12px;
        }
        .invoice-box table tr.heading td{
            background: #363636;
            color: #fff;
            padding: 6px;
        }

        .status{
            border: 1px solid #a9a9a9;
            margin-top: 40px;
            display: flex;
            align-items: center;
        }
        .table-three {
            border-left: 1px solid #a9a9a9;
            width: 100%;
        }




        .text-right {
            text-align: right;
        }


        .tableheading{
    background-color:#1250b7 !important;
    color:white;
}
.invoicenumbers{
    background-color: #edf2fa;
    height:20px;
}
.salebill{
    font-size:13px;
}

.maintabing{
    margin-top: -7px;
}
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    border:1px solid #EEE !important;
    padding: 13px;
    text-align: left;
}
th {
    color:white;
    font-size:12px;
}
.center {
    text-align: center;
}

.bold {
    font-weight: bold;
}

.right-align {
    text-align: right;
}
.condition{
    font-size: 14px;
    font-weight:bold;
      color:black;
      text-decoration: underline;
}

.subheading{
    font-weight:bold;
    font-size:14px;
    color:blue;
}


    </style>
</head>

<body>

@php
    $subTotal = 0;
    $totalDiscountAmount = 0;
    $totalCgst = 0;
    $totalSgst = 0;
    $grandTotal = 0;
@endphp

<div id="invoiceholder">


<div id="headerimage"></div>
<div id="invoice" class="effect2">

    <table  class="maintabing">
        <tr class="invoicenumbers">
        <td colspan="10" style="text-align:center;">
                <h1 class="salebill" style="">PURCHASE RETURN BILL</h1>
            </td>
        </tr>
        <tr>
            <td colspan="9">
                <b>{{ $customer->party_name ?? '' }},</b><br>
                <span>{{ $customer->Address ?? '' }} -{{ $customer->stock_pincode ?? '' }}</span><br>
                Phone: {{ $customer->mobile_number ?? '' }}<br>
                <p style="color:blue">E-mail Id: {{ $customer->customer_email ?? '' }}<p>
            </td>
            <td colspan="1">
                <div style="height:20px;">Party Name : <b>{{ $party->cus_name ?? '' }}</b><br></div>
                Party Add. :<b>{{ $party->address ?? '' }}</b><br>
                Phone No. : <b></b>{{ $party->mobile_number ?? '' }}<b><br>
                GSTIN :<br>
                DL No. :<br>
            </td>
        </tr>
        <tr class="invoicenumbers"  >
            <td colspan="6" ><b>GSTIN: {{ $customer->gst_number ?? '' }}</b></td>
            <td colspan="2" style="text-align:right"><b>Invoice No: {{ $invoice_details->dr_number }}</b></td>
            <td colspan="2"  style="text-align:right"><b>Invoice Date : {{ date("d-m-Y", strtotime($invoice_details->order_date)) }}</b></td>
        </tr>
    </table>
    <table  class="maintabing">

          <tr class="tableheading">
            <th style="width:5%">#</th>
            <th style="width:7%">Qty</th>
            <th style="width:25%">Product</th>
            <th style="width:8%">HSN</th>
            <th style="width:8%">Free</th>
            <th style="width:8%">MRP</th>
            <th style="width:8%">Rate</th>
            <th style="width:15%">Disc(%)</th>
            <th style="text-align:right"> Amount</th>
          </tr>
          @foreach($products as $key =>$product)
          @php
            $qty = (float) ($product->quantity ?? 0);
            $freeQty = (float) ($product->free ?? 0);
            $price = (float) ($product->single_unit_price ?? 0);
            $discountPercent = (float) ($product->discount_rate ?? 0);
            $cgstPercent = (float) ($product->cgst_amount ?? 0);
            $sgstPercent = (float) ($product->sgst_amount ?? 0);

            $effectiveQty = $qty - $freeQty;
            $effectiveQty = $effectiveQty > 0 ? $effectiveQty : 0;

            $itemSubTotal = $effectiveQty * $price;
            $itemDiscountAmount = ($itemSubTotal * $discountPercent) / 100;
            $itemNetAmount = $itemSubTotal - $itemDiscountAmount;
            $itemCgstAmount = ($itemNetAmount * $cgstPercent) / 100;
            $itemSgstAmount = ($itemNetAmount * $sgstPercent) / 100;

            $subTotal += $itemNetAmount;
            $totalDiscountAmount += $itemDiscountAmount;
            $totalCgst += $itemCgstAmount;
            $totalSgst += $itemSgstAmount;
        @endphp
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$product->quantity}}</td>
                <td>{{$product->name}}</td>
                <td>{{$product->hsn_sac}}</td>
                <td>{{$product->free ?? 0}}</td>
                <td>{{$product->single_unit_price ?? 0.00}}</td>
                <td>{{$product->sale_rate ?? 0.00}}</td>
                <td>{{number_format($product->discount_rate, 2, '.', ',') ?? 0.00}}</td>
                <td style="text-align:right">{{ number_format($itemNetAmount, 2, '.', ',') }}</td>
            </tr>

          @endforeach
        @php
            $grandTotal = $subTotal + $totalCgst + $totalSgst;
            $displayDiscount = (float) ($invoice_details->total_discount ?? $totalDiscountAmount);
            $displayTaxAmount = (float) ($invoice_details->tax_amount ?? ($totalCgst + $totalSgst));
            $halfStoredTax = $displayTaxAmount / 2;
            $displaySgst = $totalSgst > 0 ? $totalSgst : $halfStoredTax;
            $displayCgst = $totalCgst > 0 ? $totalCgst : $halfStoredTax;
        @endphp
        <tr style="height:4px !important;" >
            <td colspan="7" style="padding:23px 12px;"><b></b></td>
            <td style="text-align:left;border:none; color:#1250b7;"><b>Sub Total</b></td>
            <td>
                <table style="border:none;">
                    <tr>
                    <td style="text-align:right;border:none;color:#1250b7; ">{{ number_format($subTotal, 2, '.', ',') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="7" >
                <b style="text-decoration: underline;  font-weight:bold;">Terms & Conditions:</b><br>
                1) Goods once sold will not be taken back or exchanged.<br>
                2) Bills not paid due date will attract 24% interest.<br>
                3) All disputes subject to Jurisdiction only.<br>
                4) Prescribed Sales Tax declaration will be given.<br><br><br>
                <b>Rs.&nbsp;{{ ucfirst(numToWordsRec(round($grandTotal))) }}&nbsp;only</b>
            </td>
            <td style="padding:0px;" colspan="2">
                <table style="border:none;">
                    <tr><td style="text-align:left;border:none;"><b>Bill Disc</b></td>
                    <td style="text-align:right;border:none;">{{ number_format($displayDiscount, 2, '.', ',') }}</td>
                    </tr>
                    <tr><td style="text-align:left;border:none;"><b>SGST</b></td>
                    <td style="text-align:right;border:none;">{{ number_format($displaySgst, 2, '.', ',') }}</td>
                    </tr>
                    <tr><td style="text-align:left;border:none;"><b>CGST</b></td>
                    <td style="text-align:right;border:none;">{{ number_format($displayCgst, 2, '.', ',') }}</td>
                    </tr>
                    <tr><td style="text-align:left;border:none;"><b>Round Off</b></td>
                    <td style="text-align:right;border:none;">0.00</td>
                    </tr>
                    <tr style="background-color:#1250b7;"><td style="text-align:left;border:none; color:white;"><b>Grand Total</b></td>
                    <td style="text-align:right;border:none;color:white; font-weight:bold;">
                        {{ number_format($grandTotal, 2, '.', ',') }}
                    </td>
                    </tr>
                </table>
            </td>
        </tr>
   </table>
</div>
</div>
</body>
</html>
<?php
function numToWordsRec($number) {
    $words = array(
        0 => 'zero', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five',
        6 => 'six', 7 => 'seven', 8 => 'eight',
        9 => 'nine', 10 => 'ten', 11 => 'eleven',
        12 => 'twelve', 13 => 'thirteen',
        14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty',
        90 => 'ninety'
    );

    if ($number < 20) {
        return $words[$number];
    }

    if ($number < 100) {
        return $words[10 * floor($number / 10)] .
               ' ' . $words[$number % 10];
    }

    if ($number < 1000) {
        return $words[floor($number / 100)] . ' hundred '
               . numToWordsRec($number % 100);
    }

    if ($number < 1000000) {
        return numToWordsRec(floor($number / 1000)) .
               ' thousand ' . numToWordsRec($number % 1000);
    }

    return numToWordsRec(floor($number / 1000000)) .
           ' million ' . numToWordsRec($number % 1000000);
}
?>
