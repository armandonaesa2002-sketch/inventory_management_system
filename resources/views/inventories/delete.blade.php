<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Asset</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="inventory-body">
<div class="container">
    <section class="card card-danger">
        <header class="form-header">
            <div>
                <h1 class="form-title">Delete Asset</h1>
                <p class="form-subtitle">
                    This action is permanent and will remove related records.
                </p>
            </div>
            <a href="{{route('inventory.display_index')}}" class="btn btn-outline btn-sm">
                Back to List
            </a>
        </header>

        @if(session('info'))
            <div class="alert alert-success">
                {{session('info')}}
            </div>
        @endif

        <div class="alert alert-danger">
            This process has cascade effect and <strong>cannot be undone</strong>. Proceed with caution.
        </div>

        <form action="{{route('inventory.delete', ['asset' => $asset])}}" method="post">
            @csrf
            @method('delete')

            <div class="form-row">
                <div class="form-group">
                    <label>Asset Tag</label>
                    <input type="text" value="{{$asset->asset_tag}}" class="input" disabled>
                </div>
                <div class="form-group">
                    <label>Device Type</label>
                    <input type="text" value="{{$asset->device_type_label}}" class="input" disabled>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Model</label>
                    <input type="text" value="{{$asset->model}}" class="input" disabled>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <input type="text" value="{{$asset->status}}" class="input" disabled>
                </div>
            </div>

            <div class="form-group">
                <label>Affected Assignments</label>
                <input type="text" value="{{$assignment_count}} record(s)" class="input" disabled>
            </div>

            <div class="step-actions">
                <button type="submit" class="btn btn-danger">
                    Permanently Delete
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
