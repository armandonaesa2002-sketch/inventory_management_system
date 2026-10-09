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
            @php
            $assignment = $data['assignment'];
            $department = $data['department'];
            $prepared_by = $data['preparedby_options'];
            @endphp
            <div class="asset-tag-label">
                <span class="label">Asset Tag:</span>
                <span class="value">{{$assignment->asset->asset_tag}}</span>
            </div>

            <form action="{{route('inventory.update_assignment', $assignment->id)}}" method="POST">
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
                        <select name="assignment[department_id]" class="select" required>
                            <option value="">Select Department</option>
                            @foreach($department as $departments)
                            <option value="{{$departments->id}}" {{old('assignment.department_id', $assignment->department_id == $departments->id ? 'selected' : '')}}>{{$departments->name}}</option>
                            @endforeach
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
                        <select name="assignment[preparedby_id]" class="select" required>
                            <option value="">Select here</option>
                            @foreach($prepared_by as $prepared_by_who)
                            <option value="{{$prepared_by_who->id}}" {{old('assignment.preparedby_id', $assignment->preparedby_id) == $prepared_by_who->id ? 'selected' : ''}}>{{$prepared_by_who->name}}</option>
                            @endforeach
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