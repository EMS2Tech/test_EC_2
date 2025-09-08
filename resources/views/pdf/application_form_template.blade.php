<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Eurasian Campus Application Form</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            font-family: "Times New Roman", serif;
            margin: 0;
            padding: 0;
            line-height: 1.4;
            color: #000;
            font-size: 14px;
        }
        .container {
            width: 100%;
            max-width: 190mm;
            margin: 0 auto;
            padding: 10px 20px;
            box-sizing: border-box;
        }
        /* Header */
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5mm;
            height: 25mm;
        }
        .logo img {
            max-width: 55mm;
        }
        .center-section {
            flex: 1;
            display: flex;
            justify-content: center;
            padding-top: 5mm;
        }
        .title-box {
            text-align: center;
        }
        .application-form-box {
            background-color: #d4a574;
            padding: 5px 15px;
            font-weight: bold;
            font-size: 18px;
            border: 1px solid #333;
            color: #000;
        }
        .reg-no {
            font-size: 12px;
            font-weight: bold;
            flex-shrink: 0;
            padding-top: 10px;
        }
        /* Campus Info */
        .campus-info {
            text-align: center;
            margin-bottom: 10mm;
        }
        .campus-name {
            font-size: 18px;
            margin-bottom: 2px;
            font-weight: bold;
        }
        .address {
            font-size: 13px;
            line-height: 1.3;
        }
        /* Content Row */
        .content-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8mm;
        }
        .photos-section {
            width: 60mm;
        }
        .photo-box {
            border: 2px solid #333;
            width: 40mm;
            height: 40mm;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }
        .office-use {
            width: 110mm;
        }
        .office-table {
            border-collapse: collapse;
            width: 100%;
            border: 2px solid #333;
        }
        .office-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            border-bottom: 1px solid #333;
            font-size: 13px;
        }
        .office-table td {
            border-bottom: 1px solid #333;
            border-right: 1px solid #333;
            padding: 4px 6px;
            font-size: 12px;
            background-color: #fff;
        }
        .office-table td:last-child {
            width: 20mm;
            border-right: none;
        }
        .office-table tr:last-child td {
            border-bottom: none;
        }
        .batch-number {
            font-size: 13px;
            margin-bottom: 8mm;
            font-weight: bold;
        }
        /* Sections */
        .section1 {
            margin-bottom: 5mm;
        }
        .section1-title {
            font-size: 14px;
            font-weight: normal;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header-row">
            <div class="logo">
                <img src="{{ asset('frontend/assets/images/ec_logo.webp') }}" alt="Eurasian Campus Logo">
            </div>
            <div class="center-section">
                <div class="title-box">
                    <div class="application-form-box">APPLICATION FORM</div>
                </div>
            </div>
            <div class="reg-no">
                Reg No: {student_id}
            </div>
        </div>

        <!-- Campus Info -->
        <div class="campus-info">
            <div class="campus-name">EURASIAN CAMPUS</div>
            <div class="address">
                No.-91, Head office, Seelanada Nahimi Mw, Delthara, Piliyandala, Sri Lanka
            </div>
        </div>

        <!-- Content -->
        <div class="content-row">
            <div class="photos-section">
                <div class="photo-box">{Photographs}</div>
            </div>
            <div class="office-use">
                <table class="office-table">
                    <tr>
                        <th colspan="2">Office use only</th>
                    </tr>
                    <tr><td>NIC/PP Copy</td><td></td></tr>
                    <tr><td>Birth Certificate copy</td><td></td></tr>
                    <tr><td>Education Copies</td><td></td></tr>
                    <tr><td>National ID Card</td><td></td></tr>
                    <tr><td>Bank Slip</td><td></td></tr>
                </table>
            </div>
        </div>

        <!-- Batch -->
        <div class="batch-number">
            Batch Number - {batch_no}
        </div>

        <!-- Sections -->
        <div class="section1"><div class="section1-title">1. Course of Study : {course_name}</div></div>
        <div class="section1"><div class="section1-title">2. Name with Initials : {name_with_initials}</div></div>
        <div class="section1"><div class="section1-title">3. Address : {address}</div></div>
        <div class="section1"><div class="section1-title">4. Email Address : {email}</div></div>
        <div class="section1"><div class="section1-title">5. Date of Birth : {birthday}</div></div>
        <div class="section1"><div class="section1-title">6. Passport or NIC No : {nic_number}</div></div>
        <div class="section1"><div class="section1-title">7. Telephone No : {contact_number}</div></div>
        <div class="section1"><div class="section1-title">8. WhatsApp No : {whatsapp_number}</div></div>
    </div>
</body>
</html>
