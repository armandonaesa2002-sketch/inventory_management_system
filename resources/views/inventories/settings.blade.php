<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=" {{ asset('css/dashboard.css') }}">
    <title>Settings</title>
</head>

<body class="inventory-body">
    <div class="app-layout">
        <div class="mobile-sidebar-toggle">
            <button
                type="button"
                id="mobileSidebarToggle"
                aria-label="Open sidebar">
                ☰
            </button>
        </div>
        <aside class="sidebar" id="sidebar">

            <div class="sidebar-header">

                <div class="sidebar-logo">
                    INVENTORY
                </div>

                <button
                    type="button"
                    class="sidebar-toggle"
                    id="sidebarToggle"
                    aria-label="Toggle sidebar">
                    ☰
                </button>

            </div>

            <nav class="sidebar-nav">

                <a href="{{route('inventory.display_index')}}" class="sidebar-link">
                    <span class="sidebar-icon">⌂</span>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <a href="{{ route('inventory.ink_stock') }}" class="sidebar-link">
                    <span class="sidebar-icon">▣</span>
                    <span class="sidebar-text">Ink Stock</span>
                </a>

                <a href="{{route('inventory.settings')}}" class="sidebar-link active">
                    <span class="sidebar-icon">⚒</span>
                    <span class="sidebar-text">Settings</span>
                </a>

            </nav>
            <a href="{{ route('logout') }}"
                class="sidebar-link sidebar-logout"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="sidebar-icon">⏻</span>
                <span class="sidebar-text">Logout</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

        </aside>
        <main class="main-content">
            <div class="container">
                <header class="top-bar">
                    <div>
                        <h1>Settings</h1>
                        <p class="text-muted">Manage preferences of inventory management</p>
                    </div>
                </header>

                <section class="card" id="assets">
                    <div class="section-header">
                        <h2>Device Type Configuration</h2>

                        <div class="section-header-actions">
                            <button type="button"
                                class="btn btn-primary"
                                onclick="openDatatypeModal()">
                                + Add Device Type
                            </button>
                        </div>
                    </div>


                    @if(session('success-devicetype'))
                    <div class="alert alert-success" id="datatypeMessage">
                        {{session('success-devicetype')}}
                    </div>
                    @endif
                    <div class="table-wrapper">
                        <table class="table">
                            <thead class="caps">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Asset Prefix</th>
                                    <th>Status</th>
                                    <th>Basic Info</th>
                                    <th>Purchase</th>
                                    <th>Hardware</th>
                                    <th>License & Notes</th>
                                    <th colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['device_types'] as $device_type)
                                <tr>
                                    <td>{{$device_type->code}}</td>
                                    <td>{{$device_type->name}}</td>
                                    <td>{{$device_type->asset_prefix}}</td>
                                    <td>
                                        <span class="status-badge {{$device_type->is_active == true ? 'status-inuse' : 'status-inactive'}}">
                                            {{$device_type->is_active == true ? 'Active' : 'Inactive'}}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{$device_type->has_basic == true ? 'status-inuse' : 'status-inactive'}}">
                                            {{$device_type->has_basic == true ? 'Active' : 'Inactive'}}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="status-badge {{$device_type->has_purchase == true ? 'status-inuse' : 'status-inactive'}}">
                                            {{$device_type->has_purchase == true ? 'Active' : 'Inactive'}}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{$device_type->has_hardware == true ? 'status-inuse' : 'status-inactive'}}">
                                            {{$device_type->has_hardware == true ? 'Active' : 'Inactive'}}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{$device_type->has_license_notes == true ? 'status-inuse' : 'status-inactive'}}">
                                            {{$device_type->has_license_notes == true ? 'Active' : 'Inactive'}}
                                        </span>
                                    </td>

                                    <td colspan="2">

                                        <button type="button"
                                            class="btn btn-sm btn-outline"
                                            onclick="openEditDatatypeModal(this)"
                                            data-id="{{ $device_type->id }}"
                                            data-code="{{ $device_type->code }}"
                                            data-name="{{ $device_type->name }}"
                                            data-prefix="{{ $device_type->asset_prefix }}"
                                            data-hardware="{{ $device_type->has_hardware ? '1' : '0' }}"
                                            data-purchase="{{ $device_type->has_purchase ? '1' : '0' }}"
                                            data-license="{{ $device_type->has_license_notes ? '1' : '0' }}">
                                            Edit
                                        </button>



                                        <form action="{{ route('device_types.toggle', $device_type->id) }}"
                                            method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('PATCH')

                                            @if ($device_type->is_active)
                                            <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Deactivate this device type?')">
                                                Deactivate
                                            </button>
                                            @else
                                            <button type="submit"
                                                class="btn btn-sm btn-primary"
                                                onclick="return confirm('Reactivate this device type?')">
                                                Reactivate
                                            </button>
                                            @endif
                                        </form>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10">No record found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($errors->devicetype->any() && session('device_type_form_mode') === 'add_device_type')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            openDatatypeModal();
                        });
                    </script>
                    @elseif (session('edit_device_type_id'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const button = document.querySelector(
                                `[data-id="{{ session('edit_device_type_id') }}"]`
                            );

                            if (button) {
                                openEditDatatypeModal(button);
                            }
                        });
                    </script>
                    @endif
                    <!-- {{-- ADD DATATYPE MODAL --}} -->
                    <div id="addDatatypeModal" class="modal">
                        <div class="modal-content">

                            <button type="button"
                                class="modal-close"
                                onclick="closeDatatypeModal()">
                                &times;
                            </button>

                            <h2 class="modal-title" id="datatypeModalTitle">Add Device Type</h2>
                            <p class="modal-text" id="datatypeModalText">Configure a new device type.</p>

                            <form action="{{route('device_type')}}" id="datatypeForm" method="POST">
                                @if ($errors->devicetype->any())
                                <div class="alert alert-danger" id="datatypeMessage">

                                    @foreach ($errors->devicetype->all() as $error)
                                    <p>{{ $error }}</p>
                                    @endforeach

                                </div>
                                @endif
                                @if (session('device_info'))
                                <div class="alert alert-success" id="datatypeMessage">
                                    {{ session('device_info') }}
                                </div>
                                @endif

                                @csrf
                                <input type="hidden" id="datatypeMethod" name="_method" value="POST">
                                <div class="form-row">

                                    <div class="form-group">
                                        <label for="code">Code</label>
                                        <input type="text" id="code" class="input" name="code" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" class="input" name="name" required>
                                    </div>
                                </div>

                                <div class="form-row">

                                    <div class="form-group">
                                        <label for="asset_prefix">Asset Prefix</label>
                                        <input type="text" id="asset_prefix" name="asset_prefix" class="input" required>
                                    </div>
                                </div>
                                <div class="form-row">

                                    <div class="form-group">
                                        <input type="hidden" name="has_basic" id="has_basic" value="1">
                                        <label class="radio-label">
                                            <input type="checkbox" checked disabled>
                                            Basic Info
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label class="radio-label">
                                            <input type="checkbox" name="has_hardware" id="has_hardware" value="1">
                                            Hardware
                                        </label>
                                    </div>
                                </div>
                                <div class="form-row">

                                    <div class="form-group">
                                        <label class="radio-label">
                                            <input type="checkbox" name="has_purchase" id="has_purchase" value="1">
                                            Purchase
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="radio-label">
                                            <input type="checkbox" name="has_license_notes" id="has_license_notes" value="1">
                                            License & Notes
                                        </label>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 10px; margin-top: 18px;">
                                    <button type="submit" id="datatypeSubmit" class="btn btn-primary">
                                        Save Datatype
                                    </button>
                                    <button type="button" class="btn btn-outline" onclick="closeDatatypeModal()">Cancel</button>
                                </div>

                            </form>
                        </div>
                    </div>

                </section>

                <section class="card" id="department">
                    <div class="section-header">
                        <h2>Department Configuration</h2>

                        <div class="section-header-actions">
                            <button type="button"
                                class="btn btn-primary"
                                onclick="openDepartmentModal()">
                                + Add Department
                            </button>
                        </div>
                    </div>


                    @if(session('success-department'))
                    <div class="alert alert-success" id="datatypeMessage">
                        {{session('success-department')}}
                    </div>
                    @endif
                    <div class="table-wrapper">
                        <table class="table">
                            <thead class="caps">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['departments'] as $department)
                                <tr>
                                    <td>{{$department->code}}</td>
                                    <td>{{$department->name}}</td>
                                    <td>
                                        <span class="status-badge {{$department->is_active == true ? 'status-inuse' : 'status-inactive'}}">
                                            {{$department->is_active == true ? 'Active' : 'Inactive'}}
                                        </span>
                                    </td>
                                    <td colspan="2">

                                        <button type="button"
                                            class="btn btn-sm btn-outline"
                                            onclick="openEditDepartmentModal(this)"
                                            data-deptid="{{ $department->id }}"
                                            data-deptcode="{{ $department->code }}"
                                            data-deptname="{{ $department->name }}">
                                            Edit
                                        </button>



                                        <form action="{{route('department.toggle', $department->id)}}"
                                            method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('PATCH')

                                            @if ($department->is_active)
                                            <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Deactivate this department?')">
                                                Deactivate
                                            </button>
                                            @else
                                            <button type="submit"
                                                class="btn btn-sm btn-primary"
                                                onclick="return confirm('Reactivate this department?')">
                                                Reactivate
                                            </button>
                                            @endif
                                        </form>

                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">No record found</td>
                                </tr>

                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($errors->department->any() && session('edit_department_id'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const button = document.querySelector(
                                `[data-deptid="{{ session('edit_department_id') }}"]`
                            );

                            if (button) {
                                openEditDepartmentModal(button);
                            }
                        });
                    </script>

                    @elseif (session('info') && session('edit_department_id'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const button = document.querySelector(
                                `[data-deptid="{{ session('edit_department_id') }}"]`
                            );

                            if (button) {
                                openEditDepartmentModal(button);
                            }
                        });
                    </script>
                    @endif
                    <!-- {{-- ADD DEPARTMENT MODAL --}} -->
                    <div id="addDepartmentModal" class="modal">
                        <div class="modal-content">

                            <button type="button"
                                class="modal-close"
                                onclick="closeDepartmentModal()">
                                &times;
                            </button>

                            <h2 class="modal-title" id="departmentModalTitle">Add Department</h2>
                            <p class="modal-text" id="departmentModalText">Configure a new department.</p>

                            <form action="{{route('department')}}" id="departmentForm" method="POST">
                                @if ($errors->department->any())
                                <div class="alert alert-danger" id="departmentMessage">

                                    @foreach ($errors->department->all() as $error)
                                    <p>{{ $error }}</p>
                                    @endforeach

                                </div>
                                @endif
                                @if (session('info'))
                                <div class="alert alert-success" id="departmentMessage">
                                    {{ session('info') }}
                                </div>
                                @endif

                                @if ($errors->department->any() && session('department_form_mode') === 'add')
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        document.getElementById('addDepartmentModal').style.display = 'block';
                                    });
                                </script>
                                @endif

                                @csrf
                                <input type="hidden" id="departmentMethod" name="_method" value="POST">
                                <div class="form-row">

                                    <div class="form-group">
                                        <label for="dept_code">Code</label>
                                        <input type="text" id="dept_code" class="input" name="dept_code"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="dept_name">Name</label>
                                        <input type="text" id="dept_name" class="input" name="dept_name"
                                            required>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 10px; margin-top: 18px;">
                                    <button type="submit" id="departmentSubmit" class="btn btn-primary">
                                        Save Department
                                    </button>
                                    <button type="button" class="btn btn-outline" onclick="closeDepartmentModal()">Cancel</button>
                                </div>

                            </form>
                        </div>
                    </div>



                </section>


                <!-- PREPARED BY -->
                <section class="card" id="preparedby">
                    <div class="section-header">
                        <h2>Prepared By Configuration</h2>

                        <div class="section-header-actions">
                            <button type="button"
                                class="btn btn-primary"
                                onclick="openPreparedbyModal()">
                                + Add Name
                            </button>
                        </div>
                    </div>


                    @if(session('success-preparedby'))
                    <div class="alert alert-success" id="datatypeMessage">
                        {{session('success-preparedby')}}
                    </div>
                    @endif
                    <div class="table-wrapper">
                        <table class="table">
                            <thead class="caps">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['preparedby_options'] as $preparedby)
                                <tr>
                                    <td>{{$preparedby->code}}</td>
                                    <td>{{$preparedby->name}}</td>
                                    <td>
                                        <span class="status-badge {{$preparedby->is_active == true ? 'status-inuse' : 'status-inactive'}}">
                                            {{$preparedby->is_active == true ? 'Active' : 'Inactive'}}
                                        </span>
                                    </td>
                                    <td colspan="2">

                                        <button type="button"
                                            class="btn btn-sm btn-outline"
                                            onclick="openEditPreparedbyModal(this)"
                                            data-prepid="{{ $preparedby->id }}"
                                            data-prepcode="{{ $preparedby->code }}"
                                            data-prepname="{{ $preparedby->name }}">
                                            Edit
                                        </button>



                                        <form action="{{route('preparedby.toggle', $preparedby->id)}}"
                                            method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('PATCH')

                                            @if ($preparedby->is_active)
                                            <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Deactivate this name?')">
                                                Deactivate
                                            </button>
                                            @else
                                            <button type="submit"
                                                class="btn btn-sm btn-primary"
                                                onclick="return confirm('Reactivate this name?')">
                                                Reactivate
                                            </button>
                                            @endif
                                        </form>

                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">No record found</td>
                                </tr>

                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($errors->preparedby->any() && session('edit_preparedby_id'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const button = document.querySelector(
                                `[data-prepid="{{ session('edit_preparedby_id') }}"]`
                            );

                            if (button) {
                                openEditPreparedbyModal(button);
                            }
                        });
                    </script>

                    @elseif (session('info-preparedby') && session('edit_preparedby_id'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const button = document.querySelector(
                                `[data-prepid="{{ session('edit_preparedby_id') }}"]`
                            );

                            if (button) {
                                openEditPreparedbyModal(button);
                            }
                        });
                    </script>
                    @endif
                    <!-- {{-- ADD PREPARED BY MODAL --}} -->
                    <div id="addPreparedbyModal" class="modal">
                        <div class="modal-content">

                            <button type="button"
                                class="modal-close"
                                onclick="closePreparedbyModal()">
                                &times;
                            </button>

                            <h2 class="modal-title" id="preparedbyModalTitle">Add Prepared by</h2>
                            <p class="modal-text" id="preparedbyModalText">Configure a new name.</p>

                            <form action="{{route('preparedby')}}" id="preparedbyForm" method="POST">
                                @if ($errors->preparedby->any())
                                <div class="alert alert-danger" id="preparedbyMessage">

                                    @foreach ($errors->preparedby->all() as $error)
                                    <p>{{ $error }}</p>
                                    @endforeach

                                </div>
                                @endif
                                @if (session('info-preparedby'))
                                <div class="alert alert-success" id="preparedbyMessage">
                                    {{ session('info-preparedby') }}
                                </div>
                                @endif

                                @if ($errors->preparedby->any() && session('preparedby_form_mode') === 'add_preparedby')
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        document.getElementById('addPreparedbyModal').style.display = 'block';
                                    });
                                </script>
                                @endif

                                @csrf
                                <input type="hidden" id="preparedbyMethod" name="_method" value="POST">
                                <div class="form-row">

                                    <div class="form-group">
                                        <label for="prep_code">Code</label>
                                        <input type="text" id="prep_code" class="input" name="prep_code"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="prep_name">Name</label>
                                        <input type="text" id="prep_name" class="input" name="prep_name"
                                            required>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 10px; margin-top: 18px;">
                                    <button type="submit" id="preparedbySubmit" class="btn btn-primary">
                                        Save Name
                                    </button>
                                    <button type="button" class="btn btn-outline" onclick="closePreparedbyModal()">Cancel</button>
                                </div>

                            </form>
                        </div>
                    </div>



                </section>

                <section class="card settings-card">
                    <div class="section-header" id="account">
                        <div>
                            <h2>Account Settings</h2>
                            <p class="text-muted">Update your information account.</p>
                        </div>
                        <div class="section-header-actions">
                            <button type="button"
                                class="btn btn-primary"
                                onclick="openPreparedbyModal()">
                                Update Password
                            </button>
                        </div>

                    </div>
                    @if(session('no_change'))
                    <div class="alert alert-success">
                        {{session('no_change')}}
                    </div>
                    @endif
                    @if(session('success-update'))
                    <div class="alert alert-success">
                        {{session('success-update')}}
                    </div>
                    @endif

                    <form action="{{route('update_info', $data['user']->id)}}#account" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="input" type="text" id="name" name="name" value="{{$data['user']->name}}" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="input" type="email" id="email" name="email" value="{{$data['user']->email}}" required>
                            </div>
                        </div>

                        <div class="settings-actions">
                            <button class="btn btn-primary" type="submit">Save Changes</button>
                        </div>
                    </form>
                </section>
            </div>
        </main>

    </div>

    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>

</body>

</html>