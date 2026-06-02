<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Conversion Rates Report</title>
    <style>
        @page {
            margin: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333; 
        }

        .header-banner {
            background-color: #4a5568; 
            color: white;
            padding: 40px;
            position: relative;
        }

        .header-banner::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            width: 100%;
            height: 20px;
            background: linear-gradient(45deg, #4a5568 25%, transparent 25%),
                        linear-gradient(-45deg, #4a5568 25%, transparent 25%);
            background-size: 20px 20px;
        }

        .company-logo {
            text-align: right;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .report-title {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
        }

        .report-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin-top: 10px;
        }

        .content {
            padding: 40px;
        }

        .meta-info {
            background: #f8f9fa; 
            border: 1px solid #e2e6ea; /* رمادي متوسط */
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .meta-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .meta-info-item {
            border-right: 1px solid #dee2e6; 
            padding-right: 15px;
        }

        .meta-info-item:last-child {
            border-right: none;
        }

        .meta-info-label {
            font-size: 12px;
            color: #6c757d; /* رمادي متوسط */
            margin-bottom: 5px;
        }

        .meta-info-value {
            font-size: 14px;
            font-weight: bold;
            color: #343a40; 
        }

        .stats-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border: 1px solid #dee2e6; 
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05); /* ظل خفيف */
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #495057; 
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #6c757d; /* رمادي متوسط */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05); /* ظل خفيف */
            border: 1px solid #dee2e6; 
        }

        th {
            background-color: #e9ecef; 
            padding: 12px 15px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            color: #495057; 
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #dee2e6; 
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6; 
            font-size: 14px;
            color: #495057; 
        }

        tr:last-child td {
            border-bottom: none;
        }

        .conversion-rate {
            font-weight: bold;
            color: #28a745; /* أخضر رسمي بدلاً من الأزرق */
        }

        .conversion-rate::after {
            content: '%';
        }

        .footer {
            margin-top: 40px;
            padding: 20px 40px;
            background: #f8f9fa; 
            font-size: 12px;
            color: #6c757d; /* رمادي متوسط */
            text-align: center;
            border-top: 1px solid #dee2e6; 
        }

        .page-number {
            text-align: center;
            font-size: 12px;
            color: #6c757d; 
            margin-top: 20px;
        }

        .page-number::after {
            content: counter(page);
        }

        .highlight {
            color: #495057; 
            font-weight: bold;
        }
        
        .summary-box {
            margin-top: 30px; 
            padding: 20px; 
            background: #f8f9fa; 
            border-radius: 8px;
            border: 1px solid #dee2e6; 
        }
        
        .summary-box h3 {
            margin: 0 0 10px 0; 
            color: #343a40; 
        }
        
        .summary-box p {
            margin: 0; 
            color: #495057; 
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header-banner">
        <div class="company-logo">
            {{ config('app.name') }}
        </div>
        <h1 class="report-title">Conversion Rates Report</h1>
        <p class="report-subtitle">Detailed analysis of job applications and conversion rates</p>
    </div>

    <div class="content">
        <div class="meta-info">
            <div class="meta-info-grid">
                <div class="meta-info-item">
                    <div class="meta-info-label">Generated On</div>
                    <div class="meta-info-value">{{ $generatedAt }}</div>
                </div>
                <div class="meta-info-item">
                    <div class="meta-info-label">Generated By</div>
                    <div class="meta-info-value">{{ $user->name }}</div>
                </div>
                @if($user->role == 'company-owner')
                    <div class="meta-info-item">
                        <div class="meta-info-label">Company</div>
                        <div class="meta-info-value">{{ $user->company->name }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="stats-summary">
            <div class="stat-card">
                <div class="stat-value">{{ $conversionRates->count() }}</div>
                <div class="stat-label">Active Jobs</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    {{ $conversionRates->sum('viewCount') }}
                </div>
                <div class="stat-label">Total Views</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    {{ $conversionRates->sum('totalCount') }}
                </div>
                <div class="stat-label">Total Applications</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40%">Job Title</th>
                    <th style="width: 20%">Views</th>
                    <th style="width: 20%">Applications</th>
                    <th style="width: 20%">Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conversionRates as $rate)
                    <tr>
                        <td>
                            <strong>{{ $rate->title }}</strong>
                            @if($user->role == 'admin')
                                <br>
                                <span style="font-size: 12px; color: #6c757d;">
                                    {{ $rate->company->name }}
                                </span>
                            @endif
                        </td>
                        <td>{{ number_format($rate->viewCount) }}</td>
                        <td>{{ number_format($rate->totalCount) }}</td>
                        <td class="conversion-rate">{{ $rate->conversionRate }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($conversionRates->count() > 0)
            <div class="summary-box">
                <h3>Summary</h3>
                <p>
                    Average Conversion Rate: 
                    <strong class="highlight">
                        {{ number_format($conversionRates->avg('conversionRate'), 2) }}%
                    </strong>
                </p>
                <p style="margin-top: 5px;">
                    Highest Converting Job: 
                    <strong class="highlight">
                        {{ $conversionRates->sortByDesc('conversionRate')->first()->title }}
                        ({{ number_format($conversionRates->sortByDesc('conversionRate')->first()->conversionRate, 2) }}%)
                    </strong>
                </p>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>This report was automatically generated from {{ config('app.name') }} Dashboard</p>
        <p>Confidential Document - {{ now()->format('Y') }} © All Rights Reserved</p>
    </div>

    <div class="page-number">Page </div>
</body>
</html>