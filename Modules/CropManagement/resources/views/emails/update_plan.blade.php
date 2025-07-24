<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crop Plan Updated</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2e7d32;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
        }

        .label {
            font-weight: bold;
            width: 40%;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2> Crop Plan Updated Successfully</h2>

        <p>Hello,</p>

        <p>The following crop plan has been updated:</p>

        <table>
            <tr>
                <td class="label">Crop:</td>
                <td>{{ $cropPlan->crop->getTranslation('name', app()->getLocale()) ?? 'N/A' }}</td>

            </tr>
            <tr>
                <td class="label">Land:</td>
                <td>{{ $cropPlan->land->id ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Planned Planting Date:</td>
                <td>{{ $cropPlan->planned_planting_date }}</td>
            </tr>
            <tr>
                <td class="label">Planned Harvest Date:</td>
                <td>{{ $cropPlan->planned_harvest_date }}</td>
            </tr>
            <tr>
                <td class="label">Actual Planting Date:</td>
                <td>{{ $cropPlan->actual_planting_date ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Actual Harvest Date:</td>
                <td>{{ $cropPlan->actual_harvest_date ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Seed Quantity:</td>
                <td>{{ $cropPlan->seed_quantity }} kg</td>
            </tr>
            <tr>
                <td class="label">Seed Expiry Date:</td>
                <td>{{ $cropPlan->seed_expiry_date }}</td>
            </tr>
            <tr>
                <td class="label">Area Size:</td>
                <td>{{ $cropPlan->area_size }} m²</td>
            </tr>
            <tr>
                <td class="label">Status:</td>
                <td><strong>{{ ucfirst($cropPlan->status) }}</strong></td>
            </tr>
        </table>

        <p>Thank you for keeping the system updated </p>

        <div class="footer">
            This is an automated message from the GreenLand Crop Management System.
        </div>
    </div>
</body>
</html>
