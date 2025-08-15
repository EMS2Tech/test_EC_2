<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Profile of {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #eee;
            margin-bottom: 10px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            padding: 5px;
            background-color: #ecf0f1;
            margin-top: 20px;
            margin-bottom: 15px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-grid td {
            padding: 8px 0;
            vertical-align: top;
        }
        .info-grid .label {
            font-weight: bold;
            width: 200px;
        }
        .document-photo {
            max-width: 250px;
            border: 1px solid #ccc;
            padding: 5px;
            border-radius: 4px;
            margin-top: 5px;
        }
        .address-box {
            padding: 10px;
            border: 1px solid #ecf0f1;
            border-radius: 4px;
            background-color: #fdfdfd;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            @if($photographData)
                <img src="{{ $photographData }}" alt="Profile Picture" class="profile-pic">
            @endif
            <h1>{{ $user->name }}</h1>
            <p>{{ $user->email }}</p>
        </div>

        <div class="section-title">Personal Information</div>
        <table class="info-grid">
            <tr><td class="label">Full Name:</td><td>{{ $application->full_name }}</td></tr>
            <tr><td class="label">Student ID:</td><td>{{ $student->student_id ?? 'Not Available' }}</td></tr>
            <tr><td class="label">Name with Initials:</td><td>{{ $application->name_with_initials }}</td></tr>
            <tr><td class="label">Email Address:</td><td>{{ $application->email_address }}</td></tr>
            <tr><td class="label">Mobile Number:</td><td>+{{ $application->contact_number }}</td></tr>
            <tr><td class="label">WhatsApp Number:</td><td>{{ $application->whatsapp_number ?? 'N/A' }}</td></tr>
            <tr><td class="label">Birthday:</td><td>{{ $application->birthday->format('F j, Y') }}</td></tr>
        </table>

        <div class="section-title">Nationality Details</div>
        <table class="info-grid">
            <tr><td class="label">Nationality:</td><td>{{ $application->nationality }}</td></tr>
            @if ($application->nationality === 'Sri Lanka')
                <tr><td class="label">NIC Number:</td><td>{{ $application->nic_number ?? 'N/A' }}</td></tr>
                @if ($nicPhotoData)
                    <tr>
                        <td class="label">NIC Photo:</td>
                        <td><img src="{{ $nicPhotoData }}" alt="NIC Photo" class="document-photo"></td>
                    </tr>
                @endif
            @else
                <tr><td class="label">Other Nationality:</td><td>{{ $application->other_nationality ?? 'N/A' }}</td></tr>
                @if ($passportPhotoData)
                    <tr>
                        <td class="label">Passport Photo:</td>
                        <td><img src="{{ $passportPhotoData }}" alt="Passport Photo" class="document-photo"></td>
                    </tr>
                @endif
            @endif
        </table>

        <div class="section-title">Address</div>
        <div class="address-box">
            <p>{{ $application->address }}</p>
        </div>
    </div>

</body>
</html>