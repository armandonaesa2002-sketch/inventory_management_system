<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Assignment</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="inventory-body">
<div class="container">
    <section class="card card-danger">
        <header class="form-header">
            <div>
                <h1 class="form-title">Delete Assignment</h1>
                <p class="form-subtitle">
                    This will remove this user’s assignment for the asset.
                </p>
            </div>
            <a href="{{route('inventory.display_index')}}" class="btn btn-outline btn-sm">
                Back to List
            </a>
        </header>

        <div class="alert alert-danger">
            This process <strong>cannot be undone</strong>. Proceed with caution.
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Asset Tag</label>
                <input type="text" class="input" value="{{$assignment->asset->asset_tag}}" disabled>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="input" value="{{$assignment->user_name}}" disabled>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Department</label>
                <input type="text" class="input" value="{{$assignment->department}}" disabled>
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" class="input" value="{{$assignment->location}}" disabled>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Designation</label>
                <input type="text" class="input" value="{{$assignment->designation}}" disabled>
            </div>
            <div class="form-group">
                <label>Inclusion</label>
                <input type="text" class="input" value="{{$assignment->inclusion}}" disabled>
            </div>
        </div>

        <form action="{{route('inventory.remove_assignment', ['assignment' => $assignment])}}" method="post">
            @csrf 
            @method('delete')
            <div class="step-actions">
                <button type="submit" class="btn btn-danger">
                    Delete Assignment
                </button>
                <a href="{{route('inventory.display_index')}}" class="btn btn-outline">
                    Cancel
                </a>
            </div>
        </form>
    </section>
</div>
</body>
</html>
