<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Voucher</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
    }
    .voucher-container {
      max-width: 800px;
      margin: 20px auto;
      border: 2px solid black;
      padding: 15px;
      background: white;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    td, th {
      padding: 8px;
      text-align: left;
    }
    .header-table {
      width: 100%;
      border-bottom: 2px solid black;
      margin-bottom: 10px;
    }
    .logo {
      width: 80px;
      height: auto;
    }
    .school-name {
      font-size: 22px;
      font-weight: bold;
      color: red;
    }
    .sub-header {
      font-size: 16px;
      font-weight: bold;
    }
    .address {
      font-size: 14px;
    }
    .voucher-title {
      font-size: 20px;
      font-weight: bold;
      text-decoration: underline;
      text-align: center;
    }
    .highlight {
      color: red;
      font-weight: bold;
    }
    .content-table td {
      border-bottom: 1px solid black;
    }
    .amount-box {
      border: 2px solid black;
      font-size: 20px;
      font-weight: bold;
      padding: 10px;
      text-align: center;
    }
    .footer-table {
      width: 100%;
      margin-top: 20px;
    }
    .footer-table td {
      text-align: center;
      vertical-align: bottom;
      width: 25%;
      padding: 10px;
    }
    .print-button {
      margin-top: 20px;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }
    @media print {
      .print-button {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="voucher-container">
    <!-- Header Table -->
    <table class="header-table">
      <tr>
        <td style="width: 100px;">
          <img src="kunigal.jpg" alt="Logo" class="logo" />
        </td>
        <td style="text-align: center;">
          <div class="school-name">Arvind International School</div>
          <div class="sub-header">
            Smt. Gangamma Educational and Charitable Trust (R)
          </div>
          <div class="address">
            Girigowdanapalya Gate, Maddur Road, Kunigal-572130
          </div>
        </td>
      </tr>
    </table>

    <!-- Voucher Details Table -->
    <table class="content-table">
      <tr>
        <td>V.No. : <span class="highlight">10220</span></td>
        <td class="voucher-title">VOUCHER</td>
        <td>Date: 13/02/2025</td>
      </tr>
      <tr>
        <td colspan="3"><strong>Account :</strong> Others</td>
      </tr>
      <tr>
        <td colspan="3"><strong>Paid to :</strong> Siddaraju</td>
      </tr>
      <tr>
        <td colspan="3"><strong>Cheque/Cash/DD :</strong> Cash</td>
      </tr>
      <tr>
        <td colspan="3"><strong>Purpose :</strong> 2 Hostel camera recharge amount paid</td>
      </tr>
      <tr>
        <td colspan="3"><strong>Amount of Rs. :</strong> Five hundred Rupees only.</td>
      </tr>
    </table>

    <!-- Footer Table -->
    <table class="footer-table">
      <tr>
        <td>
          <div class="amount-box">Rs. : 500.00</div>
        </td>
        <td>Signature</td>
        <td>Accountant</td>
        <td>Authorised Signature</td>
      </tr>
    </table>
  </div>
  <button class="print-button" onclick="window.print()">Print</button>
</body>
</html>