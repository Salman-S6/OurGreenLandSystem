<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>New Crop Plan Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 650px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            padding: 25px;
        }
        h1 {
            color: #2a7ae2;
            text-align: center;
            margin-bottom: 25px;
        }
        .section {
            margin-bottom: 20px;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            margin-left: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: left;
        }
        th {
            background-color: #e6f0ff;
            color: #2a7ae2;
        }
        .footer {
            margin-top: 35px;
            font-size: 13px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ __('New Crop Plan Created') }}</h1>

        <p>{{ __('A new crop plan has been created successfully. Here are the details:') }}</p>

        <div class="section">
            <span class="label">{{ __('Crop') }}:</span>
            <span class="value">{{ $cropPlan->crop->name['en'] ?? 'N/A' }} / {{ $cropPlan->crop->name['ar'] ?? 'لا يوجد' }}</span>
        </div>

        <div class="section">
            <span class="label">{{ __('Land ID') }}:</span>
            <span class="value">{{ $cropPlan->land->id ?? 'N/A' }}</span>
        </div>

        <div class="section">
            <span class="label">{{ __('Planned Planting Date') }}:</span>
            <span class="value">{{ $cropPlan->planned_planting_date->format('Y-m-d') ?? 'N/A' }}</span>
        </div>

        <div class="section">
            <span class="label">{{ __('Planned Harvest Date') }}:</span>
            <span class="value">{{ $cropPlan->planned_harvest_date->format('Y-m-d') ?? 'N/A' }}</span>
        </div>

        <div class="section">
            <span class="label">{{ __('Seed Type') }}:</span>
            <span class="value">
                {{ $cropPlan->seed_type['en'] ?? 'N/A' }} / {{ $cropPlan->seed_type['ar'] ?? 'لا يوجد' }}
            </span>
        </div>

        <div class="section">
            <span class="label">{{ __('Seed Quantity') }}:</span>
            <span class="value">{{ $cropPlan->seed_quantity }} kg</span>
        </div>

        <div class="section">
            <span class="label">{{ __('Seed Expiry Date') }}:</span>
            <span class="value">{{ $cropPlan->seed_expiry_date ? $cropPlan->seed_expiry_date->format('Y-m-d') : __('N/A') }}</span>
        </div>

        <div class="section">
            <span class="label">{{ __('Area Size') }}:</span>
            <span class="value">{{ $cropPlan->area_size }} m²</span>
        </div>

        <div class="footer">
            <p>{{ __('Thank you for using our Agricultural Management System.') }}</p>
        </div>
    </div>
</body>
</html>
