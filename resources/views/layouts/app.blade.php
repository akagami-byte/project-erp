<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ERP System')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        
        /* Layout */
        .app-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }
        
        .sidebar-header p {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-top: 5px;
        }
        
        .sidebar-menu {
            padding: 15px 0;
        }
        
        .menu-section {
            margin-bottom: 10px;
        }
        
        .menu-section-title {
            padding: 10px 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.6;
        }
        
        .menu-item {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: #fff;
        }
        
        .menu-item.active {
            background: rgba(255,255,255,0.2);
            border-left-color: #fff;
            font-weight: 600;
        }
        
        .menu-item i {
            margin-right: 10px;
            width: 20px;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }
        
        /* Header */
        .page-header {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        
        .breadcrumb {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .breadcrumb a {
            color: #3498db;
            text-decoration: none;
        }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2980b9;
        }
        
        .btn-success {
            background: #27ae60;
            color: white;
        }
        
        .btn-success:hover {
            background: #229954;
        }
        
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        
        .btn-warning:hover {
            background: #d68910;
        }
        
        .btn-secondary {
            background: #95a5a6;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        
        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .form-control.is-invalid {
            border-color: #e74c3c;
        }
        
        .invalid-feedback {
            color: #e74c3c;
            font-size: 0.8rem;
            margin-top: 5px;
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 35px;
        }
        
        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        .badge-primary {
            background: #cce5ff;
            color: #004085;
        }
        
        /* Grid */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
        }
        
        .col {
            padding: 10px;
        }
        
        .col-6 {
            width: 50%;
        }
        
        .col-4 {
            width: 33.33%;
        }
        
        .col-3 {
            width: 25%;
        }
        
        /* Actions */
        .actions {
            display: flex;
            gap: 5px;
        }
        
        /* Stats */
        .stats-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stats-card h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }
        
        .stats-card p {
            color: #6c757d;
            margin: 5px 0 0 0;
            font-size: 0.9rem;
        }
        
        /* Utility */
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mb-3 {
            margin-bottom: 15px;
        }
        
        .mb-4 {
            margin-bottom: 20px;
        }
        
        .mt-3 {
            margin-top: 15px;
        }
        
        .d-flex {
            display: flex;
        }
        
        .justify-between {
            justify-content: space-between;
        }
        
        .gap-2 {
            gap: 10px;
        }
        
        /* Items Table */
        .items-table input {
            width: 100px;
        }
        
        .items-table .product-select {
            width: 200px;
        }
        
        /* Calculation Display */
        .calculation-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            text-align: right;
        }
        
        .calculation-box .total {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>ERP System</h2>
                <p>Simple Business ERP</p>
            </div>
            
            <nav class="sidebar-menu">
                <div class="menu-section">
                    <div class="menu-section-title">Dashboard</div>
                    <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i></i> Dashboard
                    </a>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">Inventory</div>
                    <a href="{{ route('stock_opname.index') }}" class="menu-item {{ request()->routeIs('stock_opname.*') ? 'active' : '' }}">
                        <i></i> Stock Opname
                    </a>
                    <a href="{{ route('purchase.goods_receipt.index') }}" class="menu-item {{ request()->routeIs('purchase.goods_receipt.*') ? 'active' : '' }}">
                        <i></i> Goods Receipt
                    </a>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">Purchasing</div>
                    <a href="{{ route('purchase.index') }}" class="menu-item {{ request()->routeIs('purchase.index', 'purchase.create', 'purchase.show', 'purchase.destroy') ? 'active' : '' }}">
                        <i></i> Purchase
                    </a>
                    <a href="{{ route('purchase.invoice.index') }}" class="menu-item {{ request()->routeIs('purchase.invoice.*') ? 'active' : '' }}">
                        <i></i> Invoice
                    </a>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">Sales</div>
                    <a href="{{ route('sales.index') }}" class="menu-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                        <i></i> Sales Order
                    </a>
                </div>
                
                <!-- Accounting Menu -->
                <div class="menu-section">
                    <div class="menu-section-title">Accounting</div>
                    <a href="{{ route('accounting.index') }}" class="menu-item {{ request()->routeIs('accounting.*') ? 'active' : '' }}">
                        <i></i> General Journal
                    </a>
                    <a href="{{ route('chart-of-accounts.index') }}" class="menu-item {{ request()->routeIs('chart-of-accounts.*') ? 'active' : '' }}">
                        <i></i> Master COA
                    </a>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">Master Data</div>
                    <a href="{{ route('products.index') }}" class="menu-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i></i> Master Product
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="menu-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                        <i></i> Suppliers
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1>@yield('title', 'Dashboard')</h1>
                    @hasSection('breadcrumb')
                        <div class="breadcrumb">@yield('breadcrumb')</div>
                    @endif
                </div>
                @yield('header-actions')
            </div>
            
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Content -->
            @yield('content')
        </main>
    </div>
    
    <!-- Scripts -->
    <script>
        // Auto calculate total for items
        function calculateItemTotal(row) {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const total = qty * price;
            row.querySelector('.subtotal-input').value = total.toFixed(2);
            calculateGrandTotal();
        }
        
        function calculateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                grandTotal += qty * price;
            });
            const grandTotalEl = document.getElementById('grand-total');
            if (grandTotalEl) {
                grandTotalEl.textContent = grandTotal.toFixed(2);
            }
        }
        
        // Remove item row
        function removeItemRow(button) {
            const tbody = document.getElementById('items-tbody');
            if (tbody && tbody.children.length > 1) {
                button.closest('tr').remove();
                calculateGrandTotal();
            }
        }
    </script>
    
    @yield('scripts')
</body>
</html>

