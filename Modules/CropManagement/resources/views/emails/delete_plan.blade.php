<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crop Plan Deleted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: ltr;
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            color: #e74c3c;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        .details {
            background-color: #f0f0f0;
            padding: 15px;
            margin-top: 15px;
            border-radius: 6px;
            font-size: 14px;
            color: #555;
        }
        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Crop Plan Deleted</h1>
        <p>Dear User,</p>
        <p>We would like to inform you that the following crop plan has been deleted from the system:</p>

        <div class="details">
            <strong>Plan ID:</strong> {{ $cropPlan->id }}<br>
            <strong>Crop:</strong> {{ $cropPlan->crop->name ?? 'N/A' }}<br>
            <strong>Land:</strong> {{ $cropPlan->land->name ?? 'N/A' }}<br>
            <strong>Planner:</strong> {{ $cropPlan->planner->name ?? 'N/A' }}<br>
            <strong>Planned Planting Date:</strong> {{ optional($cropPlan->planned_planting_date)->format('Y-m-d') ?? 'N/A' }}<br>
            <strong>Planned Harvest Date:</strong> {{ optional($cropPlan->planned_harvest_date)->format('Y-m-d') ?? 'N/A' }}<br>
            <strong>Seed Quantity:</strong> {{ $cropPlan->seed_quantity }}<br>
            <strong>Area Size:</strong> {{ $cropPlan->area_size }} m²<br>
            <strong>Status:</strong> {{ ucfirst($cropPlan->status) }}<br>
        </div>

        <p>If you need more information, please contact our support team.</p>
        <p>Thank you for using the Crop Management System.</p>

        <div class="footer">
            &copy; {{ date('Y') }} Crop Management System - All rights reserved
        </div>
    </div>
</body>
</html>
