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

                <a href="#assets" class="sidebar-link active">
                    <span class="sidebar-icon">▣</span>
                    <span class="sidebar-text">Ink Stock</span>
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
                        <span class="stat-label">Total Ink</span>
                        <span class="stat-value">{{$data['total_ink'] ?? 0}}</span>
                    </div>

                    <div class="stat-card stat-available">
                        <span class="stat-label">In Stock</span>
                        <span class="stat-value">{{$data['total_instock'] ?? 0}}</span>
                    </div>

                    <div class="stat-card stat-assigned">
                        <span class="stat-label">Low Stock</span>
                        <span class="stat-value">{{$data['total_lowstock'] ?? 0}}</span>
                    </div>

                    <div class="stat-card stat-repair">
                        <span class="stat-label">Out of Stock</span>
                        <span class="stat-value">{{$data['total_outofstock'] ?? 0}}</span>
                    </div>
                </div>


                <!-- INK STOCK -->
                <section class="card" id="ink_stock">
                    <div class="section-header">
                        <h2>Ink Stock</h2>
                        <div class="section-header-buttons">
                            <form method="GET" action="{{ route('inventory.ink_stock') }}#ink_stock" class="search-form">
                                <input
                                    type="text"
                                    name="inkstock_search"
                                    value="{{ request('inkstock_search') }}"
                                    class="input"
                                    placeholder="Search brand, type or color...">

                                <select name="ink_status" class="select">
                                    <option value="">All Status</option>
                                    <option value="In Stock" {{ request('ink_status') == 'In Stock' ? 'selected' : '' }}>
                                        In Stock
                                    </option>
                                    <option value="Low Stock" {{ request('ink_status') == 'Low Stock' ? 'selected' : '' }}>
                                        Low Stock
                                    </option>
                                    <option value="Out of Stock" {{ request('ink_status') == 'Out of Stock' ? 'selected' : '' }}>
                                        Out of Stock
                                    </option>
                                </select>

                                <button type="submit" class="btn btn-primary">
                                    Search
                                </button>

                                @if(request('inkstock_search') || request('ink_status'))
                                <a href="{{ route('inventory.ink_stock') }}" class="btn btn-outline">
                                    Clear
                                </a>
                                @endif
                            </form>
                            <a class="btn btn-primary" href="{{route('inventory.add_ink_page')}}">+ Add Ink</a>
                            <a class="btn btn-primary" href="{{route('inventory.add_multipleink')}}">+ Add Multiple Ink</a>
                        </div>
                    </div>
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{session('success')}}
                    </div>
                    @endif

                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Brand</th>
                                    <th>Type</th>
                                    <th>Color</th>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th>Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['ink_stock'] as $ink)
                                <tr>
                                    <td>{{$ink->brand}}</td>
                                    <td>{{$ink->type}}</td>
                                    <td>{{$ink->color}}</td>
                                    <td>{{$ink->in}}</td>
                                    <td>{{$ink->out}}</td>
                                    <td>{{$ink->stock}}</td>
                                    <td>{{$ink->reorder_level}}</td>
                                    <td>
                                        <span class="status-badge {{$ink->status === 'In Stock' ? 'status-inuse' : ($ink->status === 'Low Stock' ? 'status-other' : 'alert-danger')}}">
                                            {{$ink->status}}
                                        </span>
                                    </td>
                                    <td>{{$ink->created_at->format('M d, Y h:i A')}}</td>
                                    <td colspan="2">
                                        <a class="btn btn-sm btn-outline" href="{{route('inventory.edit_ink', $ink->id)}}">Edit</a>

                                        <a class="btn btn-sm btn-danger" href="{{route('inventory.delete_ink', $ink->id)}}">Delete</a>
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
                    <div class="pagination-wrapper">
                        {{ $data['ink_stock']->fragment('ink_stock')->links() }}
                    </div>

                </section>

                <!-- INK MOVEMENT -->
                <section class="card" id="ink_transaction">
                    <div class="section-header">
                        <h2>Ink Transaction</h2>
                        <div class="section-header-buttons">
                            <form method="GET" action="{{ route('inventory.ink_stock') }}#ink_transaction" class="search-form">
                                <input
                                    type="text"
                                    name="inktransaction_search"
                                    value="{{ request('inktransaction_search') }}"
                                    class="input"
                                    placeholder="Search Ink type or type...">

                                <select name="type" class="select">
                                    <option value="">All type</option>
                                    <option value="IN" {{ request('type') == 'IN' ? 'selected' : '' }}>
                                        IN
                                    </option>
                                    <option value="OUT" {{ request('type') == 'OUT' ? 'selected' : '' }}>
                                        OUT
                                    </option>
                                    <option value="ADJUSTMENT" {{ request('type') == 'ADJUSTMENT' ? 'selected' : '' }}>
                                        ADJUSTMENT
                                    </option>
                                </select>

                                <button type="submit" class="btn btn-primary">
                                    Search
                                </button>

                                @if(request('inktransaction_search') || request('type'))
                                <a href="{{ route('inventory.ink_stock') }}" class="btn btn-outline">
                                    Clear
                                </a>
                                @endif
                            </form>
                            <a class="btn btn-primary" href="{{route('inventory.transaction_form')}}">+ Add Transaction</a>
                        </div>
                    </div>
                    @if(session('success-transaction'))
                    <div class="alert alert-success">
                        {{session('success-transaction')}}
                    </div>
                    @endif

                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ink Type</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Details</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data['ink_transaction'] as $ink_transaction)
                                <tr>
                                    @php
                                    $label = "";
                                    $class = "";
                                    if($ink_transaction->type == 'IN'){
                                    $label = "Received By: ". $ink_transaction->received_by;
                                    $class = "status-inuse";
                                    }
                                    elseif($ink_transaction->type == 'OUT'){
                                    $class = "status-other";
                                    $label = "Released To: ". $ink_transaction->released_to;
                                    }
                                    elseif($ink_transaction->type == 'ADJUSTMENT'){
                                    $label = $ink_transaction->adjustment_type ." (".$ink_transaction->remarks.")";
                                    $class = "status-returned";
                                    }
                                    @endphp
                                    <td>{{$ink_transaction->inkstock->brand}} {{$ink_transaction->inkstock->type}}: {{$ink_transaction->inkstock->color}}</td>
                                    <td>
                                        <span class="status-badge {{$class}}">
                                            {{$ink_transaction->type}}
                                        </span>
                                    </td>
                                    <td>{{$ink_transaction->quantity}}</td>
                                    <td>{{$label}}</td>
                                    <td>{{$ink_transaction->created_at->format('M d, Y h:i A')}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">No record found</td>
                                </tr>
                                @endforelse

                            </tbody>

                        </table>
                    </div>
                    <div class="pagination-wrapper">
                        {{ $data['ink_transaction']->fragment('ink_transaction')->links() }}
                    </div>
                </section>


            </div>
        </main>
    </div>

    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>

</body>

</html>