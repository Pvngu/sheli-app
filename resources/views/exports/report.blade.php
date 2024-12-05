<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Accident Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        h1, h2, h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        h1 {
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }
        .incident {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .incident h4 {
            margin: 0;
            font-size: 1.3em;
            color: #34495e;
        }

        .incident h3 {
            margin: 0;
            font-size: 1.2em;
            color: #34495e;
        }
        .incident p {
            margin: 10px 0;
        }
        .summary-recommendations {
            margin-top: 40px;
            padding: 20px;
            background-color: #ecf0f1;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .footer {
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .footer a {
            color: #004080;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Accident Report</h1>
    <div>
        <p><strong>Date Range:</strong> {{ $startDate }} - {{ $endDate }}</p>
        <table style="margin-bottom: 40px">
            <thead>
                <tr>
                    <th>Injured Person</th>
                    <th>Reporting User</th>
                    <th>Area</th>
                    <th>Days Absent</th>
                    <th>Date of Accident</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accidents as $accident)
                    <tr>
                        <td>{{ $accident->injuredPerson->name }}</td>
                        <td>{{ $accident->reportingUser->name }}</td>
                        <td>{{ $accident->area->name }}</td>
                        <td>{{ $accident->days_absent }}</td>
                        <td>{{ $accident->date_of_accident }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $accidentSummaries !!}
    </div>
    <!-- Footer Section -->
    <div class="footer">
        © {{ $current_year }} <a href="#">{{ $company_name }}</a>. All Rights Reserved.
    </div>
</body>
</html>