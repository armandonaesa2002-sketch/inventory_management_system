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
                            <a class="btn btn-primary" href="{{route('inventory.create')}}">+ Add Datatypes</a>
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
                                    <th colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td colspan="3">
                                        <a class="btn btn-sm btn-outline" href="">Edit</a>

                                        <a class="btn btn-sm btn-danger" href="">Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                </section>

                <section class="card settings-card">
                    <div class="settings-section-heading">
                        <div>
                            <h2>Account Settings</h2>
                            <p class="text-muted">I-update ang impormasyon ng account.</p>
                        </div>
                    </div>

                    <form>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="input" type="text" id="name" name="name">
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="input" type="email" id="email" name="email">
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
    <script src="{{ asset('js/sidebar.js') }}"></script>

</body>

</html>