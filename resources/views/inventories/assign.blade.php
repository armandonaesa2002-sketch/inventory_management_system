<!DOCTYPE html>
<html>
<head>
    <title>Assignment</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="inventory-body">
<div class="container">
    <section class="card">
        <header class="form-header">
            <div>
                <h1 class="form-title">Assign Asset</h1>
                <p class="form-subtitle">Link a device to a user and department.</p>
            </div>
            <a href="{{route('inventory.display_index')}}" class="btn btn-outline btn-sm">
                Back to List
            </a>
        </header>

        <form action="{{route('inventory.assign_user')}}" method="POST">
            @csrf
            @method('put')

            <div class="form-group">
                <label for="asset_id">Device Assignment</label>
                <select name="asset_id" id="asset_id" class="select" required>
                    <option value="" selected>Select Device</option>
                    @foreach($assets as $asset)
                        <option value="{{$asset->id}}">
                            {{$asset->asset_tag}} - {{$asset->brand}} - {{$asset->model}} - {{$asset->serial_number}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="user_name" class="input" placeholder="User" required>
                </div>
                
                <div class="form-group">
                    <label>Department</label>
                    <select name="department" class="select" required>
                        <option value="">Select Department</option>
                        <option>Warehouse</option>
                        <option>Marketing</option>
                        <option>Accounting</option>
                        <option>Visual Merch</option>
                        <option>Store Dev't</option>
                        <option>Management</option>
                        <option>Human Resources</option>
                        <option>E-Commerce</option>
                        <option>Brand Team</option>
                        <option>Learning Dev't</option>
                        <option>Information Technology</option>
                        <option>Regulatory</option>
                        <option>Logistics</option>
                        <option>Triple A</option>
                        <option>Sales Team</option>
                        <option>Tiktok</option>
                        <option>Security Team</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="input" placeholder="Location" required>
                </div>
                <div class="form-group">
                    <label>Designation</label>
                    <input type="text" name="designation" class="input" placeholder="Designation">
                </div>

            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Inclusion</label>
                    <input type="text" name="inclusion" class="input" placeholder="Inclusion">
                </div>
                <div class="form-group">
                    <label>Prepared By</label>
                    <select name="prepared_by" id="" class="select" required>
                        <option value="">Select here</option>
                        <option>Ben Amora</option>
                        <option>Sam Garcia</option>
                        <option>Hans Jimenez</option>
                        <option>Cohen Flores</option>
                        <option>Armando Naesa</option>
                    </select>
                </div>

            </div>

            <div class="step-actions">
                <button type="submit" class="btn btn-primary">Assign User</button>
                <a href="{{route('inventory.display_index')}}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </section>
</div>
</body>

</html>