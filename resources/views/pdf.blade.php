<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Gaji - {{ $record->user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f9f9f9;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: auto;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 0%;
        }

        .info,
        .honor {
            margin-top: 20px;
        }

        .info table,
        .honor table {
            width: 100%;
            border-collapse: collapse;
        }

        .info td,
        .honor td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .honor td.label {
            font-weight: bold;
        }

        .signature {
            margin-top: 40px;
            text-align: left;
        }

        @page {
            margin: 5mm 15mm 5mm 15mm;
            /* top right bottom left */
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- <center><img src="{{ public_path('image/logo.jpg')}}" style="width: 15%" alt=""></center> -->
        <center><img src="{{ public_path(Storage::url($policy->logo))}}" style="width: 30%" alt=""></center>
        <h2>Payroll Report</h2>
        <div class="info">
            <table>
                <tr>
                    <td>Name</td>
                    <td>: {{ $record->user->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>: {{ $record->user->email }}</td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td>: 085800288265</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>: Boyolali</td>
                </tr>
                <tr>
                    <td>Bulan</td>
                    <td>: {{ Carbon\Carbon::parse($record->date)->translatedFormat('F Y')}}</td>
                </tr>
                <tr>
                    <td>Position</td>
                    <td>: {{ $record->user->position }}</td>
                </tr>
                <tr>
                    <td>Level</td>
                    <td>: {{ $record->user->level }}</td>
                </tr>
                <tr>
                    <td>Account Number (Sender)</td>
                    <td>: {{ $policy->account_number ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Bank Name (Sender)</td>
                    <td>: {{ $policy->account_name ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="honor">
            <table>
                <tr>
                    <td class="label">Salary</td>
                    <td>Rp {{ number_format($record->user->salary, 0, ',','.')}}</td>
                </tr>
                <tr>
                    <td class="label">Tax</td>
                    <td>(Rp {{ number_format($record->tax, 0, ',','.')}})</td>
                </tr>
                <tr>
                    <td class="label">Deduction</td>
                    <td>(Rp {{ number_format($record->deduction, 0, ',','.')}})</td>
                </tr>
                <tr>
                    <td class="label">Final Salaries</td>
                    <td><strong>Rp {{ number_format($record->final_salary, 0, ',','.')}}</strong></td>
                </tr>
            </table>
        </div>

        <div class="signature">
            <p>Yogyakarta, {{ Carbon\Carbon::parse($record->created_at)->translatedFormat('d F Y') }}</p>
            <p>Vice of HR Department,</p>
            <br>
            <!-- <img src="{{ public_path('images/signature.png') }}" style="width: 15%;" alt=""> -->
            <img src="{{ public_path(Storage::url($policy->signature)) }}" style="width: 15%;" alt="">
            <p><strong>James Setiawan. S.H., M.M.</strong></p>
        </div>
    </div>
</body>

</html>