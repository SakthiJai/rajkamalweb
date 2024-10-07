<template>


    <div id="invoiceholder">


        <div id="headerimage"></div>
        <div id="invoice" class="effect2">
            <h1 class="salebill">SALE BILL</h1>
            <table class="maintabing">
                <tr>
                    <!-- Add any necessary content here -->
                </tr>
                <tr>
                    <td colspan="9">
                        <b></b><br>
                        <span></span><br>
                        Phone: <br>
                        <p style="color:blue">E-mail Id: </p>
                    </td>
                    <td colspan="1">
                        <div style="height:20px;">Party Name : <b></b><br></div>
                        Party Add. : <b></b><br>
                        Phone No. : <b></b><br>
                        GSTIN :<br>
                        DL No. :<br>
                    </td>
                </tr>
                <tr class="invoicenumbers">
                    <td colspan="6"><b>GSTIN: </b></td>
                    <td colspan="2" style="text-align:right"><b>Invoice No.: </b></td>
                    <td colspan="2" style="text-align:right"><b>Invoice Date : </b></td>
                </tr>
            </table>
            
            <table class="">
                <tr class="tableheading">
                    <th style="width:5%">#</th>
                    <th style="width:5%">Qty</th>
                    <th style="width:27%">Product</th>
                    <th style="width:8%">HSN</th>
                    <th style="width:8%">MRP</th>
                    <th style="width:8%">Rate</th>
                    <th style="width:6%">Disc</th>
                    <th style="width:6%">SGST</th>
                    <th style="width:6%">CGST</th>
                    <th style="text-align:right">Amount</th>
                </tr>
            
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align:right"></td>
                </tr>
            
                <!-- Party Previous Balance Row moved above Terms & Conditions -->
                <tr style="height:4px !important;">
                    <td colspan="9" style="padding:8px 12px;">
                        <b>Party Previous Balance: 4 Dr</b>
                    </td>
                    <td>
                        <table style="border:none;">
                            <tr>
                                <td style="text-align:left;border:none;color:#1250b7;"><b>Sub Total</b></td>
                                <td style="text-align:right;border:none;color:#1250b7;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
               
            </table>
            
            <!-- Terms & Conditions Section -->
            <table class="">
              
                
                <tr>
                    <td colspan="9">
                        <b>Terms & Conditions:</b><br>
                        1) Goods once sold will not be taken back or exchanged.<br>
                        2) Bills not paid due date will attract 24% interest.<br>
                        3) All disputes subject to Jurisdiction only.<br>
                        4) Prescribed Sales Tax declaration will be given.<br><br><br>
                        <b>Rs. only</b>
                    </td>
            
                    <!-- <td>Party Previous Balance: 4 Dr</td> -->
                    <td style="padding:0px;">
                       
                        <table style="border:none;">
                            <tr>
                                <td style="text-align:left;border:none;"><b>Bill Disc</b></td>
                                <td style="text-align:right;border:none;">1%</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;border:none;"><b>SGST</b></td>
                                <td style="text-align:right;border:none;">5.64</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;border:none;"><b>CGST</b></td>
                                <td style="text-align:right;border:none;">5.64</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;border:none;"><b>Round Off</b></td>
                                <td style="text-align:right;border:none;">0.88</td>
                            </tr>
                            
                            <tr style="background-color:#1250b7;">
                                <td style="text-align:left;border:none;color:white;"><b>Grand Total</b></td>
                                <td style="text-align:right;border:none;color:white;font-weight:bold;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            
        </div>
        </div>


</template>
<script>
import { onMounted, watch, ref } from "vue";
import { PlusOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import { useRoute } from "vue-router";
import common from "../../../../common/composable/common";

import DateRangePicker from "../../../../common/components/common/calendar/DateRangePicker.vue";

import ExpenseCategoryModal from '../../stock-management/purchases/ExpenseCategoryModal.vue';
import DateTimePicker from "../../../../common/components/common/calendar/DatePicker.vue"
export default {
    components: {
        PlusOutlined,
        DeleteOutlined,
        ExpenseCategoryModal,

        DateRangePicker,

        DateTimePicker,
    },
};
</script>
<style>
body {
    background: #E0E0E0;
    font-family: 'Roboto', sans-serif;
    background-image: url('');
    background-repeat: repeat-y;
    background-size: 100%;
}

::selection {
    background: #f31544;
    color: #FFF;
}

::moz-selection {
    background: #f31544;
    color: #FFF;
}

h1 {
    font-size: 1.5em;
    color: #222;
}

h2 {
    font-size: .9em;
}

h3 {
    font-size: 1.2em;
    font-weight: 300;
    line-height: 2em;
}

p {
    font-size: .7em;
    color: #666;
    line-height: 0.6;
}

#invoiceholder {
    width: 100%;
    height: 100%;
    padding-top: 50px;
}

#invoice {
    position: relative;

    margin: 0 auto;
   
    background: #FFF;
}

[id*='invoice-'] {
    /* Targets all id with 'col-' */
    border-bottom: 1px solid #EEE;
    padding: 19px;
}

#invoice-top {
    min-height: 120px;
}

#invoice-mid {
    min-height: 120px;
}

#invoice-bot {
    min-height: 250px;
}

.logo {
    float: left;
    height: 60px;
    width: 60px;

    background-size: 60px 60px;
}

.clientlogo {
    float: left;
    height: 60px;
    width: 60px;

    background-size: 60px 60px;
    border-radius: 50px;
}

.info {
    display: block;
    float: left;
    margin-top: -14px;

}

.title {
    float: right;
}

.title p {
    text-align: right;
}

#project {
    margin-left: 52%;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    padding: 5px 0 5px 15px;
    border: 1px solid #EEE
}

.tabletitle {
    padding: 5px;
    background: #1044b5;
    color:white;
}

.service {
    border: 1px solid #EEE;
}

.item {
    width: 8%;
}

.itemtext {
    font-size: .9em;
}

#legalcopy {
    margin-top: 30px;
}

form {
    float: right;
    margin-top: 30px;
    text-align: right;
}


.effect2 {
    position: relative;
}

.effect2:before,
.effect2:after {
    z-index: -1;
    position: absolute;
    content: "";
    bottom: 15px;
    left: 10px;
    width: 50%;
    top: 80%;
    max-width: 300px;
    background: #777;
    -webkit-box-shadow: 0 15px 10px #777;
    -moz-box-shadow: 0 15px 10px #777;
    box-shadow: 0 15px 10px #777;
    -webkit-transform: rotate(-3deg);
    -moz-transform: rotate(-3deg);
    -o-transform: rotate(-3deg);
    -ms-transform: rotate(-3deg);
    transform: rotate(-3deg);
}

.effect2:after {
    -webkit-transform: rotate(3deg);
    -moz-transform: rotate(3deg);
    -o-transform: rotate(3deg);
    -ms-transform: rotate(3deg);
    transform: rotate(3deg);
    right: 10px;
    left: auto;
}

.salebill {
    text-align: center;
    padding: 5px 8px; 
    font-size: 16px;
    font-weight: bold;
    background-color: #edf2fa;
}

.mainheading {
    font-weight: bold;
    font-size: 13px;
}

.contents {
    font-size: 13px;
    color: black;
    font-weight: 600;
}

.emailid {
    font-size: 13px;
    color: blue;
    font-weight: 600;
}

.personal {
    font-size: 13px;
    color: black;
    font-weight: 500;
}

.partysname {
    font-size: 13px;
    color: black;
    font-weight: 800;
}
.secondinfo{
    margin-top: 12px;
    padding: 3px 13px;
    
}
.thirdinfo{
    padding: 3px 13px;
    margin-top: 12px;

}
.fourthinfo{
    margin-top: 12px;
    padding: 5px 8px;
   
}
.rate{
    width:25%;
}
.hsnumber{
    width:12%;
}
.amounts{
    width:20%;
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

table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    border:1px solid #EEE !important;
    padding: 13px;
    text-align: left;
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
.salebill{
    font-size:14px;
}

.maintabing{
    margin-top: -7px;
}

.invoicenumbers{
    background-color: #edf2fa; 
}
.tableheading{
    background-color:#1250b7 !important;
    color:white;
}

</style>
