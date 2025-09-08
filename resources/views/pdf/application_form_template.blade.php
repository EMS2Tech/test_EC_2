<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Eurasian Campus Application Form</title>
<style>
        @page { size: A4; margin: 20mm; }
        body {
            font-family: DejaVu Serif, "Times New Roman", serif;
            margin: 0; padding: 0; line-height: 1.35; color: #000; font-size: 13px;
        }
        .container { width: 100%; }
        .mb-2 { margin-bottom: 2mm; }
        .mb-4 { margin-bottom: 4mm; }
        .mb-6 { margin-bottom: 6mm; }
        .b { font-weight: bold; }
 
        /* Header */
        .header-table { width: 100%;}
        .header-table td { vertical-align: top; }
        .logo-cell { width: 20mm; }
        .logo-img { height: 15mm; }
        .title-cell { text-align: center; }
        .reg-cell { width: 35mm; text-align: right; font-size: 12px; font-weight: bold; }
 
        .application-form-box {
            display: inline-block;
            background-color: #ebbe8fff;
            padding: 4px 12px;
            border: 1px solid #333;
            font-weight: bold;
            font-size: 15px;
        }
 
        /* Campus info */
        .campus-name { font-size: 18px; text-align: center; }
        .address { font-size: 14px; text-align: center; }
 
        /* Photo + Office use row */
        .row-table { width: 100%; border-collapse: collapse; }
        .photo-cell { width: 55mm; vertical-align: top; }
        .office-cell { padding-left: 43mm; }
.office-wrap { width: 90mm; margin-left: auto; }   /* make smaller/bigger to taste */
.office-table { width: 100%; }                     /* fills the wrapper */
 
        .photo-frame {
            border: 2px solid #333; width: 40mm; height: 40mm;
            display: block; text-align: center; line-height: 40mm; font-size: 12px; margin-bottom: 1mm;
        }
        .photo-img { width: 40mm; height: 40mm; object-fit: cover; display: block; }
 
        .office-table { width: 80%; border-collapse: collapse; border: 2px solid #333; }
        .office-table th {
            background: #f5f5f5; font-weight: bold; text-align: center; padding: 5px; border-bottom: 1px solid #333; font-size: 12px;
        }
        .office-table td {
            padding: 4px 6px; font-size: 12px; border-bottom: 1px solid #333; border-right: 1px solid #333; background: #fff;
        }
        .office-table tr:last-child td { border-bottom: none; }
        .office-table td:last-child { border-right: none; width: 22mm; }
 
        .batch { font-size: 13px; font-weight: bold; }
 
        /* Sections */
        .section { margin-bottom: 4mm; }
        .label { font-size: 13px; }
</style>
</head>
<body>
<div class="container">
 
    <!-- Header -->
<table class="header-table mb-4">
<tr>
<td class="logo-cell">
                @if(!empty($logo_base64))
<img class="logo-img" src="{{ $logo_base64 }}" alt="Eurasian Campus Logo">
                @else
<div class="b">Eurasian Campus</div>
                @endif
</td>
<td class="title-cell">
<div class="application-form-box">APPLICATION FORM</div>
</td>
<td class="reg-cell">
                Application No: {{ $student_id }}
</td>
</tr>
</table>
 
    <!-- Campus Info -->
<div class="campus-name mb-2">EURASIAN CAMPUS</div>
<div class="address mb-6">No.-91, Head office, Seelanada Nahimi Mw, Delthara, Piliyandala, Sri Lanka</div>
 
    <!-- Content Row -->
<table class="row-table mb-6">
<tr>
<td class="photo-cell">
                @if(!empty($photo_base64))
<img class="photo-img" src="{{ $photo_base64 }}" alt="Photograph">
                @else
<div class="photo-frame">Photograph</div>
                @endif
</td>
<td class="office-cell">
<div class="office-wrap">
<table class="office-table">
<tr><th colspan="2">Office use only</th></tr>
<tr><td>NIC/PP Copy</td><td></td></tr>
<tr><td>Birth Certificate copy</td><td></td></tr>
<tr><td>Education Copies</td><td></td></tr>
<tr><td>National ID Card</td><td></td></tr>
<tr><td>Bank Slip</td><td></td></tr>
</table>
</div>
</td>
</tr>
</table>
 
    <!-- Batch -->
<div class="batch mb-6">Batch Number - {{ $batch_no }}</div>
 
    <!-- Sections -->
<div class="section"><span class="label"><b>1. Course of Study: </b></span>{{ $course_name }}</div>
<div class="section"><span class="label"><b>2. Name with Initials: </b></span>{{ $name_with_initials }}</div>
<div class="section"><span class="label"><b>3. Address: </b></span>{{ $address }}</div>
<div class="section"><span class="label"><b>4. Email Address: </b></span>{{ $email }}</div>
<div class="section"><span class="label"><b>5. Date of Birth: </b></span>{{ $birthday }}</div>
<div class="section"><span class="label"><b>6. Passport or NIC No: </b></span>{{ $nic_number }}</div>
<div class="section"><span class="label"><b>7. Telephone No: </b></span>{{ $contact_number }}</div>
<div class="section"><span class="label"><b>8. WhatsApp No: </b></span>{{ $whatsapp_number }}</div>
 
</div>
</body>
</html>