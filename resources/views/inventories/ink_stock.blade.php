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
                                @foreach($data['ink_stock'] as $ink)
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
                                    <td>{{$ink->created_at}}</td>
                                    <td><button>Edit</button><button>delete</button></td>
                                </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>

                </section>



            </div>
        </main>
    </div>

    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>

</body>

</html>