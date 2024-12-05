<!DOCTYPE html>
<html>
<head>
    <title>Audit Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .audit-container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #004080;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
        }
        .content {
            padding: 10px;
        }
        .section-title {
            font-size: 18px;
            color: #004080;
            margin-bottom: 10px;
            border-bottom: 1px solid #004080;
            padding-bottom: 5px;
        }
        .content p {
            margin: 5px 0;
            line-height: 1.4;
        }
        .content .info-grid {
            width: 100%;
            display: table;
        }
        .content .info-grid p {
            display: table-row;
        }
        .content .info-grid p strong {
            display: table-cell;
            padding-right: 10px;
        }
        .content .info-grid p span {
            display: table-cell;
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
    <div class="audit-container">
        <!-- Header Section -->
        <div class="header">
            {{-- <img src="{{ $logo_url }}" alt="Business Logo"> --}}
            <h1>Audit Report</h1>
        </div>

        <!-- Content Section -->
        <div class="content">
            <!-- Audit Information -->
            <h2 class="section-title">Audit Information</h2>
            <div class="info-grid">
                <p><strong>Audit Name:</strong> <span>{{ $audit_name }}</span></p>
                <p><strong>Audit Date:</strong> <span>{{ $audit_date }}</span></p>
                <p><strong>Auditor Name:</strong> <span>{{ $auditor_name }}</span></p>
                <p><strong>Area:</strong> <span>{{ $area }}</span></p>
            </div>

            <!-- Findings -->
            <h2 class="section-title">Findings</h2>
            <p>{{ $findings }}</p>

            <!-- Corrective Actions -->
            <h2 class="section-title">Corrective Actions</h2>
            <p>{{ $corrective_actions }}</p>

            <!-- Images Section -->
            @if(count($images) > 0)
                <h2 class="section-title">Images</h2>
                <div class="images" style="text-align: center">
                    @foreach($images as $image)
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path("/uploads/audits/" . $image))) }}" alt="Audit Image" style="width: 75%; text-align: center; height: auto; margin-bottom: 20px;">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Footer Section -->
        <div class="footer">
            © {{ $current_year }} <a href="#">{{ $company_name }}</a>. All Rights Reserved.
        </div>
    </div>
</body>
</html>