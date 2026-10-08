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
                    <h1>INVENTORY MANAGEMENT SYSTEM</h1>
                </header>
            </div>
        </main>
    </div>
    <script src="{{ asset('js/sidebar.js') }}"></script>

</body>

</html>