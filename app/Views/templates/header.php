<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Pharmacy Management System' ?> | PharmaTrack</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Sweet Alert 2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c7da0;
            --secondary-color: #61a5c2;
            --success-color: #2a9d8f;
            --danger-color: #e63946;
            --warning-color: #f4a261;
            --dark-color: #2d3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        
        .main-content {
            margin-left: 0;
            padding: 20px;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 20px;
        }
        
        .card-header {
            background: white;
            border-bottom: 2px solid var(--primary-color);
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card i {
            font-size: 40px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .stat-card h3 {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .dataTables_wrapper {
            padding: 20px;
        }
        
        @media (min-width: 768px) {
            .main-content {
                margin-left: 260px;
            }
        }
        
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
</head>
<body>

<?php if (session()->get('isLoggedIn')): ?>
<!-- Sidebar -->
<div class="sidebar position-fixed top-0 start-0 h-100" style="width: 260px; z-index: 1000;">
    <div class="p-3 text-center border-bottom border-light">
        <i class="fas fa-hospital-user fa-3x text-white mb-2"></i>
        <h5 class="text-white mb-0">PharmaTrack</h5>
        <small class="text-white-50">Pharmacy Management System</small>
    </div>
    
    <nav class="nav flex-column p-3">
        <a href="<?= base_url('/dashboard') ?>" class="nav-link <?= ($active ?? '') == 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        
        <?php if (in_array(session()->get('role'), ['admin', 'pharmacist', 'cashier'])): ?>
        <a href="<?= base_url('/sales/pos') ?>" class="nav-link <?= ($active ?? '') == 'pos' ? 'active' : '' ?>">
            <i class="fas fa-shopping-cart"></i> Point of Sale
        </a>
        <?php endif; ?>
        
        <?php if (in_array(session()->get('role'), ['admin', 'pharmacist'])): ?>
        <a href="<?= base_url('/products') ?>" class="nav-link <?= ($active ?? '') == 'products' ? 'active' : '' ?>">
            <i class="fas fa-capsules"></i> Products
        </a>
        <a href="<?= base_url('/categories') ?>" class="nav-link <?= ($active ?? '') == 'categories' ? 'active' : '' ?>">
            <i class="fas fa-tags"></i> Categories
        </a>
        <?php endif; ?>
        
        <a href="<?= base_url('/customers') ?>" class="nav-link <?= ($active ?? '') == 'customers' ? 'active' : '' ?>">
            <i class="fas fa-users"></i> Customers
        </a>
        
        <?php if (in_array(session()->get('role'), ['admin', 'cashier'])): ?>
        <a href="<?= base_url('/sales') ?>" class="nav-link <?= ($active ?? '') == 'sales' ? 'active' : '' ?>">
            <i class="fas fa-receipt"></i> Sales History
        </a>
        <?php endif; ?>
        
        <?php if (in_array(session()->get('role'), ['admin', 'pharmacist'])): ?>
        <a href="<?= base_url('/reports') ?>" class="nav-link <?= ($active ?? '') == 'reports' ? 'active' : '' ?>">
            <i class="fas fa-chart-line"></i> Reports
        </a>
        <?php endif; ?>
        
        <?php if (session()->get('role') == 'admin'): ?>
        <a href="<?= base_url('/users') ?>" class="nav-link <?= ($active ?? '') == 'users' ? 'active' : '' ?>">
            <i class="fas fa-user-shield"></i> Users
        </a>
        <?php endif; ?>
        
        <hr class="bg-light">
        
        <a href="<?= base_url('/profile') ?>" class="nav-link">
            <i class="fas fa-user-circle"></i> Profile
        </a>
        <a href="<?= base_url('/logout') ?>" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
    
    <div class="position-absolute bottom-0 start-0 w-100 p-3 text-center border-top border-light">
        <small class="text-white-50">
            <i class="fas fa-user-check"></i> <?= session()->get('full_name') ?><br>
            <span class="badge bg-light text-dark mt-1"><?= ucfirst(session()->get('role')) ?></span>
        </small>
    </div>
</div>
<?php endif; ?>

<!-- Main Content -->
<div class="main-content">
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($errors) && !empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>