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
          <?php $subTotal = 0;$sumsgst=0;$sumcgst=0; ?>
          @foreach($products as $key =>$product)
          @php
            $cgstAmount     = isset($product->cgst_amount) ? intval($product->cgst_amount) : 0;
            $saleRate       = isset($product->sale_rate) ? intval($product->sale_rate) : 0;
            $percentage     = $cgstAmount / 100;
            $gstAmount      = $percentage * $saleRate;
            $totalGstAmount = $gstAmount * intval($product->quantity);
            $subTotal       = $subTotal+$product->subtotal;
            $sumsgst        = $sumsgst + $product->sgst_amount;
            $sumcgst        = $sumcgst + $product->cgst_amount;
        @endphp
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
                                <td style="text-align:right">
                                    @php
                                        $totalDiscount = $product->discount_rate ?? 0;
                                        $rate = $product->sale_rate ?? 0;
                                        $qty = $product->quantity ?? 0;
                                        $freeQty = $product->free ?? 0;
                                        $actualQty = $qty - $freeQty;
                                        $actualQty = $actualQty > 0 ? $actualQty : 0;
                                        $amount = $rate * $actualQty;
                                        $subTotalAll = $subTotal > 0 ? $subTotal : 1; // avoid division by zero
                                        $itemDiscount = ($amount / 100) * $totalDiscount;
                                        $netSubtotal = $amount - $itemDiscount;
                                          \Log::info('TOTAL  Value', [ 
    'totalDiscount' => $totalDiscount, 
    'rate' => $rate,
    'qty' => $qty,
    'freeQty' => $freeQty,
    'actualQty' => $actualQty,
    'amount' => $amount,
    'subTotalAll' => $subTotalAll,
    'itemDiscount' => $itemDiscount,
    'netSubtotal' => $netSubtotal
]);
                                    @endphp
                                    {{ number_format($netSubtotal, 2, '.', ',') }}
                                </td>
            </tr>

          @endforeach
        <tr style="height:4px !important;" >
            <td colspan="7" style="padding:23px 12px;"><b></b></td>
            <td style="text-align:left;border:none; color:#1250b7;"><b>Sub Total</b></td>
            <td>
                <table style="border:none;">
                    <tr>
                    <td style="text-align:right;border:none;color:#1250b7; ">
                         @php
                                        $totalDiscount = $product->discount_rate ?? 0;
                                        $rate = $product->sale_rate ?? 0;
                                        $qty = $product->quantity ?? 0;
                                        $freeQty = $product->free ?? 0;
                                        $actualQty = $qty - $freeQty;
                                        $actualQty = $actualQty > 0 ? $actualQty : 0;
                                        $amount = $rate * $actualQty;
                                        $subTotalAll = $subTotal > 0 ? $subTotal : 1; // avoid division by zero
                                        $itemDiscount = ($amount / 100) * $totalDiscount;
                                        $netSubtotal = $amount - $itemDiscount;
                                    @endphp
                                    {{ number_format($netSubtotal, 2, '.', ',') }}
                    </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php
            $taxAmount = 0.00;
            if (isset($invoice_details->tax_amount) && !is_null($invoice_details->tax_amount) && $invoice_details->tax_amount > 0) {
                $taxAmount = $invoice_details->tax_amount / 2;
            }
            $singleTaxAmount = number_format($taxAmount, 2, '.', ',');
        ?>
        <tr>
            <td colspan="7" >
                <b style="text-decoration: underline;  font-weight:bold;">Terms & Conditions:</b><br>
                1) Goods once sold will not be taken back or exchanged.<br>
                2) Bills not paid due date will attract 24% interest.<br>
                3) All disputes subject to Jurisdiction only.<br>
                4) Prescribed Sales Tax declaration will be given.<br><br><br>
                @php
                    // Calculate grand total as above
                    $subTotalNew = 0;
                    foreach ($products as $prod) {
                        $rate = $prod->sale_rate ?? 0;
                        $qty = $prod->quantity ?? 0;
                        $freeQty = $prod->free ?? 0;
                        $actualQty = $qty - $freeQty;
                        $actualQty = $actualQty > 0 ? $actualQty : 0;
                        $subTotalNew += $rate * $actualQty;
                    }
                    $totalDiscount = $product->discount_rate ?? 0;
                    $subTotalAll = $subTotalNew > 0 ? $subTotalNew : 1; // avoid division by zero
                    $discountedTotal = 0;
                    foreach ($products as $prod) {
                        $rate = $prod->sale_rate ?? 0;
                        $qty = $prod->quantity ?? 0;
                        $freeQty = $prod->free ?? 0;
                        $actualQty = $qty - $freeQty;
                        $actualQty = $actualQty > 0 ? $actualQty : 0;
                        $amount = $rate * $actualQty;
                        $itemDiscount = ($amount / 100) * $totalDiscount;
                        $discountedTotal += ($amount - $itemDiscount);
                    }
                    $sgstAmount = ($invoice_details->total_amount - $invoice_details->tax_amount) * ($sumsgst / 100);
                    $cgstAmount = ($invoice_details->total_amount - $invoice_details->tax_amount) * ($sumcgst / 100);
                    $grandTotal = $discountedTotal + $sgstAmount + $cgstAmount;
                @endphp
                <b>Rs.&nbsp;{{ ucfirst(numToWordsRec(round($grandTotal))) }}&nbsp;only</b>
            </td>
            <td style="padding:0px;" colspan="2">
                <table style="border:none;">
                    <tr><td style="text-align:left;border:none;"><b>Bill Disc</b></td>
                    <td style="text-align:right;border:none;">{{ $invoice_details->total_discount? number_format($invoice_details->total_discount,2):"0.00"}}</td>
                    </tr>
                    <tr><td style="text-align:left;border:none;"><b>SGST</b></td>
                    <td style="text-align:right;border:none;">{{number_format((($invoice_details->total_amount-$invoice_details->tax_amount) *($sumsgst/100)),2) }}</td>
                    </tr>
                    <tr><td style="text-align:left;border:none;"><b>CGST</b></td>
                    <td style="text-align:right;border:none;">{{number_format((($invoice_details->total_amount-$invoice_details->tax_amount) *($sumcgst/100)),2) }}</td>
                    </tr>
                    <tr><td style="text-align:left;border:none;"><b>Round Off</b></td>
                    <td style="text-align:right;border:none;">0.00</td>
                    </tr>
                    <tr style="background-color:#1250b7;"><td style="text-align:left;border:none; color:white;"><b>Grand Total</b></td>
                    <td style="text-align:right;border:none;color:white; font-weight:bold;">
                        @php
                            // Calculate subtotal using item-wise logic
                            $subTotalNew = 0;
                            foreach ($products as $prod) {
                                $rate = $prod->sale_rate ?? 0;
                                $qty = $prod->quantity ?? 0;
                                $freeQty = $prod->free ?? 0;
                                $actualQty = $qty - $freeQty;
                                $actualQty = $actualQty > 0 ? $actualQty : 0;
                                $subTotalNew += $rate * $actualQty;
                            }
                            $totalDiscount = $product->discount_rate ?? 0;
                            $subTotalAll = $subTotalNew > 0 ? $subTotalNew : 1; // avoid division by zero
                            $discountedTotal = 0;
                            foreach ($products as $prod) {
                                $rate = $prod->sale_rate ?? 0;
                                $qty = $prod->quantity ?? 0;
                                $freeQty = $prod->free ?? 0;
                                $actualQty = $qty - $freeQty;
                                $actualQty = $actualQty > 0 ? $actualQty : 0;
                                $amount = $rate * $actualQty;
                                $itemDiscount = ($amount / 100) * $totalDiscount;
                                $discountedTotal += ($amount - $itemDiscount);
                            }
                            $sgstAmount = ($invoice_details->total_amount - $invoice_details->tax_amount) * ($sumsgst / 100);
                            $cgstAmount = ($invoice_details->total_amount - $invoice_details->tax_amount) * ($sumcgst / 100);
                            $grandTotal = $discountedTotal + $sgstAmount + $cgstAmount;
                            \Log::info('grandTotal', ['grandTotal' => $grandTotal]);
                        @endphp
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
