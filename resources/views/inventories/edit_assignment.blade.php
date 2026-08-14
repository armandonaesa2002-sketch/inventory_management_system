<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assignment</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="inventory-body">
<div class="container">
    <section class="card">
        <header class="form-header">
            <div>
                <h1 class="form-title">Edit Assignment</h1>
                <p class="form-subtitle">Update user and location for this asset.</p>
            </div>
            <a href="{{route('inventory.display_index')}}" class="btn btn-outline btn-sm">
                Back to List
            </a>
        </header>

        @if(session('info'))
            <div class="alert alert-success">
                {{ session('info') }}
            </div>
        @endif

        <div class="asset-tag-label">
            <span class="label">Asset Tag:</span>
            <span class="value">{{$assignment->asset->asset_tag}}</span>
        </div>

        <form action="{{route('inventory.update_assignment', ['assignment' => $assignment])}}" method="POST">
            @csrf
            @method('put')
<div class="form-row">

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="assignment[user_name]" class="input"
        placeholder="User" value="{{$assignment->user_name}}" required>
    </div>
    
    <div class="form-group">
        <label>Department</label>
        <select name="assignment[department]" class="select" required>
            <option value="">Select Department</option>
            <option value="Warehouse"              {{old('assignment.department', $assignment->department) == 'Warehouse' ? 'selected' : ''}}>Warehouse</option>
            <option value="Marketing"              {{old('assignment.department', $assignment->department) == 'Marketing' ? 'selected' : ''}}>Marketing</option>
            <option value="Accounting"             {{old('assignment.department', $assignment->department) == 'Accounting' ? 'selected' : ''}}>Accounting</option>
            <option value="Visual Merch"           {{old('assignment.department', $assignment->department) == 'Visual Merch' ? 'selected' : ''}}>Visual Merch</option>
            <option value="Store Devt"             {{old('assignment.department', $assignment->department) == 'Store Devt' ? 'selected' : ''}}>Store Dev't</option>
            <option value="Management"             {{old('assignment.department', $assignment->department) == 'Management' ? 'selected' : ''}}>Management</option>
            <option value="Human Resources"         {{old('assignment.department', $assignment->department) == 'Human Resources' ? 'selected' : ''}}>Human Resources</option>
            <option value="E-Commerce"             {{old('assignment.department', $assignment->department) == 'E-Commerce' ? 'selected' : ''}}>E-Commerce</option>
            <option value="Brand Team"             {{old('assignment.department', $assignment->department) == 'Brand Team' ? 'selected' : ''}}>Brand Team</option>
            <option value="Learning Devt"          {{old('assignment.department', $assignment->department) == 'Learning Devt' ? 'selected' : ''}}>Learning Dev't</option>
            <option value="Information Technology" {{old('assignment.department', $assignment->department) == 'Information Technology' ? 'selected' : ''}}>Information Technology</option>
            <option value="Regulatory"             {{old('assignment.department', $assignment->department) == 'Regulatory' ? 'selected' : ''}}>Regulatory</option>
            <option value="Logistics"              {{old('assignment.department', $assignment->department) == 'Logistics' ? 'selected' : ''}}>Logistics</option>
            <option value="Triple A"               {{old('assignment.department', $assignment->department) == 'Triple A' ? 'selected' : ''}}>Triple A</option>
            <option value="Sales Team"             {{old('assignment.department', $assignment->department) == 'Sales Team' ? 'selected' : ''}}>Sales Team</option>
            <option value="Tiktok"                 {{old('assignment.department', $assignment->department) == 'Tiktok' ? 'selected' : ''}}>Tiktok</option>
                    <option value="Security Team"          {{old('assignment.department', $assignment->department) == 'Security Team' ? 'selected' : ''}}>Security Team</option>
                </select>
            </div>
        </div>
            <div class="form-row">

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="assignment[location]" class="input"
                    placeholder="Location" value="{{$assignment->location}}" required>
                </div>
                <div class="form-group">
                    <label>Designation</label>
                    <input type="text" name="assignment[designation]" class="input" 
                    placeholder="Designation" value="{{$assignment->designation}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Inclusion</label>
                    <input type="text" name="assignment[inclusion]" class="input" 
                    placeholder="Inclusion" value="{{$assignment->inclusion}}">
                </div>
                <div class="form-group">
                    <label>Prepared By</label>
                    <select name="assignment[prepared_by]" class="select" required>
                        <option value="">Select here</option>
                        <option value="Ben Amora" {{old('assignment.prepared_by', $assignment->prepared_by) == 'Ben Amora' ? 'selected' : ''}}>Ben Amora</option>
                        <option value="Sam Garcia" {{old('assignment.prepared_by', $assignment->prepared_by) == 'Sam Garcia' ? 'selected' : ''}}>Sam Garcia</option>
                        <option value="Hans Jimenez" {{old('assignment.prepared_by', $assignment->prepared_by) == 'Hans Jimenez' ? 'selected' : ''}}>Hans Jimenez</option>
                        <option value="Cohen Flores" {{old('assignment.prepared_by', $assignment->prepared_by) == 'Cohen Flores' ? 'selected' : ''}}>Cohen Flores</option>
                        <option value="Armando Naesa" {{old('assignment.prepared_by', $assignment->prepared_by) == 'Armando Naesa' ? 'selected' : ''}}>Armando Naesa</option>
                    </select>
                </div>

            </div>

            <div class="step-actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{route('inventory.display_index')}}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </section>
</div>
</body>
</html>
