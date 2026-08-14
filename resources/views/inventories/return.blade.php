<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Asset</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="inventory-body">
<div class="container">
    <section class="card">
        <header class="form-header">
            <div>
                <h1 class="form-title">Return Asset</h1>
                <p class="form-subtitle">Mark this device as returned and set its new status.</p>
            </div>
            <a href="{{route('inventory.display_index')}}" class="btn btn-outline btn-sm">
                Back to List
            </a>
        </header>

        <form action="{{route('inventory.confirm_return', ['assignment' => $assignment])}}" method="post">
            @csrf
            @method('PUT')

            <h2 class="step-title">Assignment Information</h2>

            <div class="form-row">
                <div class="form-group">
                    <label>Asset Tag</label>
                    <input type="text" class="input" value="{{$assignment->asset->asset_tag}}" readonly>
                </div>
                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" class="input" value="{{$assignment->asset->brand}}" readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Model</label>
                    <input type="text" class="input" value="{{$assignment->asset->model}}" readonly>
                </div>
                <div class="form-group">
                    <label>Serial Number</label>
                    <input type="text" class="input" value="{{$assignment->asset->serial_number}}" readonly>
                </div>
            </div>

            <div class="form-group">
                <label>Currently assigned to</label>
                <input type="text" class="input" value="{{$assignment->user_name}}" readonly>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Status</label>
                    <select name="return[status]" class="select" required>
                        <option value="">-- Select --</option>
                        <option>Spare</option>
                        <option>For Repair</option>
                        <option>Decommissioned</option>
                        <option>Lost</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Remarks</label>
                <textarea rows="3" name="return[remarks]" class="textarea" placeholder="Enter remarks..." required></textarea>
            </div>

            <div class="step-actions">
                <button type="submit" class="btn btn-primary">Confirm Return</button>
                <a href="{{route('inventory.display_index')}}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </section>
</div>
</body>
</html>
