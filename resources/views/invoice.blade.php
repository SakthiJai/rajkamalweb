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
                <h1 class="salebill" style="">SALE BILL</h1>
            </td>  
        </tr>
        <tr>
            <td colspan="9">
                <b>{{ $customer[0]->party_name }},</b><br>
                <span>{{ $customer[0]->Address }} -{{$customer[0]->stock_pincode}}</span><br>
                Phone: {{ $customer[0]->mobile_number }}<br>
                <p style="color:blue">E-mail Id: {{ $customer[0]->customer_email }}</p>
            </td>
            <td colspan="1">
                <div style="height:20px;">Party Name : <b>{{ $party[0]->cus_name }}</b><br></div>
                Party Add. :<b>{{ $party[0]->address }}</b><br>
                Phone No. : <b></b>{{ $party[0]->mobile_number }}<b><br>
                GSTIN :<br>
                DL No. :<br>
            </td>
        </tr>
        <tr class="invoicenumbers"  >
            <td colspan="6" ><b>GSTIN: {{ $customer[0]->gst_number }}</b></td>
            <td colspan="2" style="text-align:right"><b>Invoice No.: {{ $invoice_details->invoice_number }}</b></td>
            <td colspan="2"  style="text-align:right"><b>Invoice Date : {{ date("d-m-Y", strtotime($invoice_details->order_date)) }}</b></td>
        </tr>
    </table>
    <table  class="maintabing">
        
    <tr class="tableheading">
        <th style="width:5%">#</th>
        <th style="width:10%">Qty</th>
        <th style="width:18%">Product</th>
        <th style="width:8%">Free</th>
        <th style="width:8%">HSN</th>
        <th style="width:8%">MRP</th>
        <th style="width:8%">Rate</th>
        <th style="width:10%">Disc %</th>
        <th style="width:9%">Tax %</th>
        <th style="text-align:right">Taxable Amount</th>
          </tr>
          @php
        // Get party and company state for GST logic
        $companyState = (int)($company->state ?? 0);
        $partyState   = (int)($customer[0]->stock_state ?? 0);
                 \Log::info('CGST Percent Value', [ 
    'partyState' => $partyState, 
    'companyState' => $companyState,
    'company' => $company,]);

            
        $subTotal = 0;
        $totalCgst = 0;
        $totalSgst = 0;
        $totalIgst = 0;
        $totalCess = 0;
        @endphp
        @php 

        @endphp


        @foreach($products as $key => $product)
            @php
                // Get product values
                $qty = (float) ($product->quantity ?? 0);
                $freeQty = isset($product->freeqty) ? (float)$product->freeqty : 0;
                if ($freeQty === null || $freeQty === '' || $freeQty < 0) {
                    $freeQty = 0;
                }
                if ($qty == 0) {
                    $freeQty = 0;
                }
                if ((int)$freeQty >= (int)$qty) {
                    $freeQty = 0;
                }
                $qty = $qty - $freeQty;
                $rate = (float) ($product->sale_rate ?? 0);
                $discountPercent = (float) ($product->discount_rate ?? 0);
                $basicAmount = $qty * $rate;
                $discountValue = ($basicAmount * $discountPercent) / 100;
                $taxableAmount = $basicAmount - $discountValue;

                $cgstPercent = (float) ($product->cgst ?? 0);
                $sgstPercent = (float) ($product->sgst ?? 0);
                $igstPercent = $sgstPercent + $cgstPercent;
                $cessPercent = (float) ($product->cess ?? 0);

                // GST logic: intra/inter state

                if ($partyState !== '' && $companyState !== '') {
                    if ($partyState === $companyState) {
                        $igstPercent = 0;
                    } else {
                        $cgstPercent = 0;
                        $sgstPercent = 0;
                    }
                }

                // --- Free quantity logic (from Create.vue) ---
                $freeQty = isset($product->freeqty) ? (float)$product->freeqty : 0;
                if ($freeQty === null || $freeQty === '' || $freeQty < 0) {
                    $freeQty = 0;
                }
                if ($qty == 0) {
                    $freeQty = 0;
                }
                if ((int)$freeQty >= (int)$qty) {
                    $freeQty = 0;
                }
                $remQty = $qty - $freeQty;

                // Calculate tax values
                $cgstValue = $taxableAmount * $cgstPercent / 100;
                $sgstValue = $taxableAmount * $sgstPercent / 100;
                $igstValue = $taxableAmount * $igstPercent / 100;
                $cessValue = $taxableAmount * $cessPercent / 100;

                // Calculate row total
                $rowTotal = $taxableAmount + $cgstValue + $sgstValue + $igstValue ;

                // Accumulate totals
                $subTotal += $taxableAmount;
                $totalCgst += $cgstValue;
                $totalSgst += $sgstValue;
                $totalIgst += $igstValue;
                $totalCess += $cessValue;
            @endphp

            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $freeQty }}</td>
                <td>{{ $product->hsn_sac }}</td>
                <td>{{ $product->mrp ?? 0.00 }}</td>
                <td>{{ $rate }}</td>
                <td>{{ number_format($discountPercent,2) }}</td>
                <td>
                    @php
                        $totalTaxPercent = (float)($cgstPercent ?? 0)
                            + (float)($sgstPercent ?? 0)
                            + (float)($igstPercent ?? 0);
                    @endphp
                    {{ number_format($totalTaxPercent, 2) }}
                </td>
                <td style="text-align:right">{{ number_format($rowTotal,2) }}</td>
            </tr>

    @endforeach
                @php
                    $grandTotal = $subTotal + $totalCgst + $totalSgst + $totalIgst + $totalCess;
                    $roundedGrandTotal = round($grandTotal, 2);
                    $roundOff = $roundedGrandTotal - $grandTotal;
                @endphp
       <tr style="height:4px !important;" >
            <td colspan="8"><b></b></td>
            <td style="text-align:left;border:none; color:#1250b7;"><b>Sub Total</b></td>
            <td>
                <table style="border:none;">
                    <tr>
                    <td style="text-align:right;border:none;color:#1250b7; ">{{number_format($subTotal, 2, '.', ',')}}</td>
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
    <td colspan="7">
        <b style="text-decoration: underline;">Terms & Conditions:</b><br>
        1) Goods once sold will not be taken back or exchanged.<br>
        2) Bills not paid due date will attract 24% interest.<br>
        3) All disputes subject to Jurisdiction only.<br>
        4) Prescribed Sales Tax declaration will be given.<br><br>

        <b>Rs. {{ ucfirst(numToWordsRec($roundedGrandTotal)) }} only</b>
    </td>

    <td colspan="4" style="padding:0;">
        <table class="responsive-table" style="width:100%; border:none;">
            <thead>
                <tr>
                    <th class="tableheading" style="font-weight:bolder;font-size:13px;width:40%">Tax Name</th>
                    <th class="tableheading" style="font-weight:bolder;font-size:13px;text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @if($totalCgst > 0)
                <tr>
                    <td style="font-weight:bolder;font-size:13px;">CGST</td>
                    <td style="text-align:right;color:black;font-weight:bolder;">{{ number_format($totalCgst,2) }}</td>
                </tr>
                    @endif
                
                    @if($totalSgst > 0)
                        <tr>
                        <td style="font-weight:bolder;font-size:13px;">SGST</td>
                    <td style="text-align:right;color:black;font-weight:bolder;">{{ number_format($totalSgst,2) }}</td>
                </tr>
                    @endif
                    @if($totalIgst > 0)
                <tr>
                    <td style="font-weight:bolder;font-size:13px;">IGST</td>
                    <td style="text-align:right;color:black;font-weight:bolder;">{{ number_format($totalIgst,2) }}</td>
                </tr>
                    @endif
                     @if($totalCess > 0)
                <tr>
                    <td style="font-weight:bolder;font-size:13px;">CESS</td>
                    <td style="text-align:right;color:black;font-weight:bolder;">{{ number_format($totalCess,2) }}</td>
                </tr>
                    @endif
                <tr>
                    <td style="font-weight:bolder;font-size:13px;">Sub Total</td>
                    <td style="text-align:right;color:black;font-weight:bolder;">{{ number_format($subTotal,2) }}</td>
                </tr>
                <tr style="background-color:#1250b7;">
                    <td style="font-weight:bolder;color:white;">Grand Total</td>
                    <td style="text-align:right;color:white;font-weight:bolder;"><b>{{ number_format($subTotal + $totalCgst + $totalSgst + $totalIgst + $totalCess, 2) }}</b></td>
                </tr>
            </tbody>
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
@php
    // Get party and company state for GST logic
    $companyState = (int)($company->state ?? 0);
    $partyState   = (int)($customer[0]->stock_state ?? 0);
@endphp
