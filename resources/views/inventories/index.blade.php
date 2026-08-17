<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="{{ asset('css/design.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <title>Inventory System</title>
</head>
<body class="inventory-body">
    <div class="app-layout">

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

        <a href="#dashboard" class="sidebar-link active">
            <span class="sidebar-icon">⌂</span>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <a href="#assets" class="sidebar-link">
            <span class="sidebar-icon">▣</span>
            <span class="sidebar-text">Assets</span>
        </a>

        <a href="#assignment" class="sidebar-link">
            <span class="sidebar-icon">♙</span>
            <span class="sidebar-text">User Assignment</span>
        </a>

        <a href="#repair_history" class="sidebar-link">
            <span class="sidebar-icon">⚒</span>
            <span class="sidebar-text">Repair History</span>
        </a>

    </nav>

</aside>
        <!-- MAIN CONTENT -->
        <main class="main-content">

    <div class="container">
        <header class="top-bar">
            <h1>INVENTORY MANAGEMENT SYSTEM</h1>
        </header>
    
        <div class="stats-grid">
    <div class="stat-card stat-total">
        <span class="stat-label">Total Assets</span>
        <span class="stat-value">{{$data['total_assets'] ?? 0}}</span>
    </div>

    <div class="stat-card stat-available">
        <span class="stat-label">Available</span>
        <span class="stat-value">{{$data['total_available'] ?? 0}}</span>
    </div>

    <div class="stat-card stat-assigned">
        <span class="stat-label">Assigned</span>
        <span class="stat-value">{{$data['total_assigned'] ?? 0}}</span>
    </div>

    <div class="stat-card stat-repair">
        <span class="stat-label">For Repair</span>
        <span class="stat-value">{{$data['total_repair'] ?? 0}}</span>
    </div>

    <div class="stat-card stat-decom">
        <span class="stat-label">Decommissioned</span>
        <span class="stat-value">{{$data['total_decommissioned'] ?? 0}}</span>
    </div>

    <div class="stat-card stat-lost">
        <span class="stat-label">Lost</span>
        <span class="stat-value">{{$data['total_lost'] ?? 0}}</span>
    </div>
</div>

        <section class="card" id="assets">
            <div class="section-header">
                <h2>Assets</h2>

                <div class="section-header-actions">
                    <form method="GET" action="{{ route('inventory.display_index') }}#assets" class="search-form">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="input"
                            placeholder="Search tag, serial, brand, or model...">

                        <select name="device_type" class="select">
                            <option value="">All Device Types</option>
                            <option value="laptop" {{ request('device_type') == 'laptop' ? 'selected' : '' }}>
                                Laptop
                            </option>
                            <option value="desktop" {{ request('device_type') == 'desktop' ? 'selected' : '' }}>
                                Desktop
                            </option>
                            <option value="minipc" {{ request('device_type') == 'minipc' ? 'selected' : '' }}>
                                Mini PC
                            </option>
                            <option value="aio" {{ request('device_type') == 'aio' ? 'selected' : '' }}>
                                All in One
                            </option>
                            <option value="mac" {{ request('device_type') == 'mac' ? 'selected' : '' }}>
                                Mac
                            </option>
                            <option value="printer" {{ request('device_type') == 'printer' ? 'selected' : '' }}>
                                Printer
                            </option>
                        </select>

                        <select name="status" class="select">
                            <option value="">All Status</option>
                            <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>
                                Available
                            </option>
                            <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>
                                Assigned
                            </option>
                            <option value="For repair" {{ request('status') == 'For repair' ? 'selected' : '' }}>
                                For Repair
                            </option>
                            <option value="Decommissioned" {{ request('status') == 'Decommissioned' ? 'selected' : '' }}>
                                Decommissioned
                            </option>
                            <option value="Lost" {{ request('status') == 'Lost' ? 'selected' : '' }}>
                                Lost
                            </option>
                        </select>
                        
                        <button type="submit" class="btn btn-primary">
                            Search
                        </button>

                        @if(request('search') || request('device_type') || request('status'))
                            <a href="{{ route('inventory.display_index') }}" class="btn btn-outline">
                                Clear
                            </a>
                        @endif
                    </form>

                    <div class="section-header-buttons">
                        <a href="{{ route('inventory.export', [
                                'search' => request('search'),
                                'device_type' => request('device_type'),
                                'status' => request('status'),
                            ]) }}" class="btn btn-primary">Export Excel</a>
                        <a class="btn btn-primary" href="{{route('inventory.create')}}">+ Add Item</a>
                        <a class="btn btn-primary" href="{{route('inventory.create_multiple')}}">+ Add Multiple Item</a>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success">
                {{session('success')}}
            </div>
            @endif
            <div class="table-wrapper">
                <table class="table">
                    <thead class="caps">
                        <tr>
                            <th>Asset Tag</th>
                            <th>Device Type</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Serial Number</th>
                            <th>Status</th>
                            <th colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['assets'] as $asset)
                        <tr>
                            <td>{{$asset->asset_tag}}</td>
                            <td>{{$asset->device_type_label}}</td>
                            <td>{{$asset->brand}}</td>
                            <td>{{$asset->model}}</td>
                            <td>{{$asset->serial_number}}</td>
                            <td>
                                <span class="status-badge {{$asset->status === 'In Use' ? 'status-inuse' : ($asset->status === 'Returned' ? 'status-returned' : 'status-other')}}">
                                    {{$asset->status}}
                                </span>
                            </td>
                            <td colspan="3">
                                <a class="btn btn-sm btn-outline" href="{{route('inventory.edit', ['asset' => $asset])}}">Edit</a>
                
                                <a class="btn btn-sm btn-danger" href="{{route('inventory.remove', ['asset' => $asset])}}">Delete</a>
                            
                                <a class="btn btn-sm btn-secondary" href="{{route('inventory.view_asset', ['asset' => $asset])}}">View</a>
                            </td>
                        </tr>
                         @empty
                            <tr>
                                <td colspan="7">No record found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">
                {{ $data['assets']->links() }}
            </div>

        </section>

        <section class="card" id="assignment">
            <div class="section-header">
                <h2>User Assignment</h2>

                <div class="section-header-actions">
                    <form method="GET" action="{{ route('inventory.display_index') }}#assignment" class="search-form">
                        <input
                            type="text"
                            name="assignment_search"
                            value="{{ request('assignment_search') }}"
                            class="input"
                            placeholder="Search tag or user...">

                        <select name="department" class="select">
                            <option value="">All Department</option>
                            <option value="Warehouse"              {{request('department') == 'Warehouse' ? 'selected' : ''}}>Warehouse</option>
                            <option value="Marketing"              {{request('department') == 'Marketing' ? 'selected' : ''}}>Marketing</option>
                            <option value="Accounting"             {{request('department') == 'Accounting' ? 'selected' : ''}}>Accounting</option>
                            <option value="Visual Merch"           {{request('department') == 'Visual Merch' ? 'selected' : ''}}>Visual Merch</option>
                            <option value="Store Devt"             {{request('department') == 'Store Devt' ? 'selected' : ''}}>Store Dev't</option>
                            <option value="Management"             {{request('department') == 'Management' ? 'selected' : ''}}>Management</option>
                            <option value="Human Resource"         {{request('department') == 'Human Resource' ? 'selected' : ''}}>Human Resources</option>
                            <option value="E-Commerce"             {{request('department') == 'E-Commerce' ? 'selected' : ''}}>E-Commerce</option>
                            <option value="Brand Team"             {{request('department') == 'Brand Team' ? 'selected' : ''}}>Brand Team</option>
                            <option value="Learning Devt"          {{request('department') == 'Learning Devt' ? 'selected' : ''}}>Learning Dev't</option>
                            <option value="Information Technology" {{request('department') == 'Information Technology' ? 'selected' : ''}}>Information Technology</option>
                            <option value="Regulatory"             {{request('department') == 'Regulatory' ? 'selected' : ''}}>Regulatory</option>
                            <option value="Logistics"              {{request('department') == 'Logistics' ? 'selected' : ''}}>Logistics</option>
                            <option value="Triple A"               {{request('department') == 'Triple A' ? 'selected' : ''}}>Triple A</option>
                            <option value="Sales Team"             {{request('department') == 'Sales Team' ? 'selected' : ''}}>Sales Team</option>
                            <option value="Tiktok"                 {{request('department') == 'Tiktok' ? 'selected' : ''}}>Tiktok</option>
                            <option value="Security Team"          {{request('department') == 'Security Team' ? 'selected' : ''}}>Security Team</option>
                        </select>

                        <select name="assignment_status" class="select">
                            <option value="">All Status</option>
                            <option value="In Use" {{ request('assignment_status') == 'In Use' ? 'selected' : '' }}>
                                In Use
                            </option>
                            <option value="Returned" {{ request('assignment_status') == 'Returned' ? 'selected' : '' }}>
                                Returned
                            </option>
                            <option value="For repair" {{ request('assignment_status') == 'For repair' ? 'selected' : '' }}>
                                For Repair
                            </option>
                            <option value="Decommissioned" {{ request('assignment_status') == 'Decommissioned' ? 'selected' : '' }}>
                                Decommissioned
                            </option>
                            <option value="Lost" {{ request('assignment_status') == 'Lost' ? 'selected' : '' }}>
                                Lost
                            </option>
                        </select>
                        
                        <button type="submit" class="btn btn-primary">
                            Search
                        </button>

                        @if(request('assignment_search') || request('department') || request('assignment_status'))
                            <a href="{{ route('inventory.display_index') }}" class="btn btn-outline">
                                Clear
                            </a>
                        @endif
                    </form>

                    <div class="section-header-buttons">

                        <a class="btn btn-primary" href="{{route('inventory.assign')}}">Assign Asset</a>
                    </div>
                </div>
            </div>

            @if(session('success-assignment'))
                <div class="alert alert-success">
                    {{session('success-assignment')}}
                </div>
            @endif

            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ASSET TAG</th>
                            <th>USER</th>
                            <th>DEPARTMENT</th>
                            <th>LOCATION</th>
                            <th>DATE ASSIGNED</th>
                            <th>DATE UPDATED</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th colspan="3">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['assignments'] as $assignment)
                        @php 
                            $has_new_user = $assignment->asset
                                ->assignment()
                                ->where('id', '>', $assignment->id)
                                ->exists();
                                

                            $latestRepair = $data['repair_history']
                                        ->where('asset_id', $assignment->asset_id)
                                        ->where('assignment_id', $assignment->id)
                                        ->sortByDesc('created_at')
                                        ->first();

                            $canUndoRepair = $latestRepair &&
                                $latestRepair->repair_status === 'Completed' &&
                                $latestRepair->repair_status_after === 'repaired';


                               
                        @endphp
                        <tr>
                            <td>{{$assignment->asset->asset_tag}}</td>
                            <td>{{$assignment->user_name}}</td>
                            <td>{{$assignment->department}}</td>
                            <td>{{$assignment->location}}</td>
                            <td>{{$assignment->created_at}}</td>
                            <td>{{$assignment->updated_at}}</td>
                            <td>
                                <span class="status-badge {{$assignment->status === 'In Use' ? 'status-inuse' : ($assignment->status === 'Returned' ? 'status-returned' : 'status-other')}}">
                                    {{$assignment->status}}
                                </span>
                            </td>
                            <td>{{$assignment->remarks}}</td>

                            @if(!$has_new_user)
                                @if($assignment->status === 'In Use')
                                    <td colspan="4">
                                        <a class="btn btn-sm btn-outline" href="{{route('inventory.edit_assignment', ['assignment' => $assignment])}}">Edit</a>
                                    
                                        <a class="btn btn-sm btn-danger" href="{{route('inventory.delete_assignment', ['assignment' => $assignment])}}">Delete</a>
                                    
                                        <a class="btn btn-sm btn-secondary" href="{{route('inventory.returnpage_assignment', ['assignment' => $assignment])}}">Return</a>
                                        <a class="btn btn-sm btn-outline" href="{{route('inventory.liability_form', ['assignment' => $assignment])}}">Print Liability</a>
                                    

                                    @if($canUndoRepair)
                                    

                                        <form action="{{route('inventory.undo_return', ['assignment' => $assignment])}}"
                                        method="POST"
                                        onsubmit="return confirm('Undo action for this asset?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-outline">
                                            Undo Action
                                        </button>
                                    </form>
                                    @endif
                                </td>
                                @else
                                    <td colspan="4">
                                        <div style="display: flex; gap:5px;">

                                            <form action="{{route('inventory.undo_return', ['assignment' => $assignment])}}"
                                                method="POST"
                                                onsubmit="return confirm('Undo action for this asset?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-outline">
                                                    Undo Action
                                                </button>
                                            </form>
                                            @if($assignment->status === 'For Repair')
                                            
                                            <button onclick="openCompleteModal()" class="btn btn-sm btn-outline status-returned modalreturn" 
                                                    data-id="{{ $assignment->id }}"
                                                    data-asset="{{ $assignment->asset->asset_tag }}">
                                                Mark as Complete
                                            </button>
                                        </div>
                                        @endif
                                    </td>
                                @endif
                            @else
                                <td colspan="3" class="text-muted">
                                    Reassigned - Action Unavailable
                                </td>
                            @endif
                        </tr>
                         @empty
                            <tr>
                                <td colspan="9">No record found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">
                {{ $data['assignments']->links() }}
            </div>
        </section>


<!-- MARK AS DONE MODAL  -->
<div id="completeRepairModal" class="modal">
    <div class="modal-content">
        <button type="button" class="modal-close" onclick="closeCompleteModal()">
            &times;
        </button>

        <h2 class="modal-title">Complete Repair</h2>
        <p class="modal-text">
            Choose what to do with the asset after completing the repair.
            Asset Tag: <strong id="modalAssetTag"></strong>
        </p>

        <form action="" method="POST" id="completeRepairForm">
            @csrf
            <input type="hidden" name="repair_id" id="modalRepairId">

            <div class="form-group">
                <label class="radio-label">
                    <input type="radio" name="assignment_option" value="keep">
                    <span>Keep Assigned to Current User</span>
                </label>
            </div>

            <div class="form-group">
                <label class="radio-label">
                    <input type="radio" name="assignment_option" value="spare" required>
                    <span>Return to Spare Inventory</span>
                </label>
            </div>

            <div class="step-actions">
                <button type="submit" class="btn btn-primary">
                    Complete Repair
                </button>
                <button type="button" class="btn btn-outline" onclick="closeCompleteModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>




        <section class="card" id="repair_history">
            <div class="section-header">
                <h2>Repair History</h2>

                <div class="section-header-actions">
                    <form method="GET" action="{{ route('inventory.display_index') }}#repair_history" class="search-form">
                        <input
                            type="text"
                            name="repair_search"
                            value="{{ request('repair_search') }}"
                            class="input"
                            placeholder="Search tag, User...">

                        <select name="repair_status" class="select">
                            <option value="">All Status</option>
                            <option value="In progress" {{ request('device_type') == 'In progress' ? 'selected' : '' }}>
                                In progress
                            </option>
                            <option value="Completed" {{ request('device_type') == 'Completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                            <option value="Cancelled" {{ request('device_type') == 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                        
                        <button type="submit" class="btn btn-primary">
                            Search
                        </button>

                        @if(request('repair_search') || request('repair_status'))
                            <a href="{{ route('inventory.display_index') }}" class="btn btn-outline">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
            </div>


            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ASSET TAG</th>
                            <th>USER</th>
                            <th>DATE</th>
                            <th>TYPE</th>
                            <th>STATUS</th>
                            <th>DESCRIPTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['repair_history'] as $repair)
                            <tr>
                                <td>{{$repair->asset_tag}}</td>
                                <td>{{$repair->user_name}}</td>
                                <td>{{$repair->created_at}}</td>
                                <td>
                                    <span class="status-badge status-other">
                                        {{$repair->type}}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{$repair->repair_status === 'In progress' ? 'status-inuse' : ($repair->repair_status === 'Completed' ? 'status-returned' : 'btn-danger')}}">
                                        {{$repair->repair_status}}</td>
                                    </span>
                                <td>{{$repair->description}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">No record found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                </table>
            </div>

        </section>
    </div>
        </main>
    </div>

    <script src="{{ asset('js/modal.js') }}"></script>

    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const appLayout = document.querySelector('.app-layout');

        sidebarToggle.addEventListener('click', function () {
            appLayout.classList.toggle('sidebar-collapsed');
        });
    </script>
</body>
</html>