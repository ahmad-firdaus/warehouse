<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: index.php"); exit; }
include 'config.php';

// Ringkasan Data untuk Dashboard
$p_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$s_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM suppliers"))['total'];
$in_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM incoming_stock"))['total'] ?? 0;
$out_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM outgoing_stock"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - WMS</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; background: #f4f6f9; }
        .sidebar { width: 250px; background: #343a40; color: white; padding: 20px; }
        .sidebar h3 { text-align: center; margin-bottom: 30px; color: #fff; }
        .sidebar a { display: block; color: #c2c7d0; padding: 12px; text-decoration: none; border-radius: 4px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: #495057; color: white; }
        .content { flex: 1; padding: 30px; }
        .header-content { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #dee2e6; padding-bottom: 10px; margin-bottom: 20px; }
        .grid-box { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-top: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-left: 5px solid #007bff; }
        .card.supplier { border-left-color: #28a745; }
        .card.stok-masuk { border-left-color: #ffc107; }
        .card.stok-keluar { border-left-color: #dc3545; }
        .card h4 { color: #6c757d; margin-bottom: 10px; }
        .card p { font-size: 28px; font-weight: bold; color: #333; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 15px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        th, td { padding: 12px; border: 1px solid #dee2e6; text-align: left; }
        th { background: #007bff; color: white; }
        .btn { padding: 8px 12px; border: none; border-radius: 4px; color: white; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-add { background: #28a745; margin-bottom: 15px; display: inline-block; }
        .btn-danger { background: #dc3545; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-control { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        @media (max-width: 768px) { body { flex-direction: column; } .sidebar { width: 100%; } }
    </style>
</head>
<body>

    <div class="sidebar">
        <h3>WMS GUDANG</h3>
        <p style="text-align:center; margin-bottom:20px; color:#ffc107;">Halo, <?= $_SESSION['username']; ?> (<?= ucfirst($_SESSION['role']); ?>)</p>
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="produk.php">Product Management</a>
        <a href="supplier.php">Supplier Management</a>
        <a href="stok_masuk.php">Incoming Stock</a>
        <a href="stok_keluar.php">Outgoing Stock</a>
        <a href="laporan.php">Reports</a>
        <a href="logout.php" style="color: #ff8080; margin-top: 30px;">Logout</a>
    </div>

    <div class="content">
        <div class="header-content">
            <h2>Dashboard Utama</h2>
        </div>
        
        <div class="grid-box">
            <div class="card">
                <h4>Total Jenis Produk</h4>
                <p><?= $p_count; ?></p>
            </div>
            <div class="card supplier">
                <h4>Total Supplier</h4>
                <p><?= $s_count; ?></p>
            </div>
            <div class="card stok-masuk">
                <h4>Total Barang Masuk</h4>
                <p><?= $in_count; ?> Unit</p>
            </div>
            <div class="card stok-keluar">
                <h4>Total Barang Keluar</h4>
                <p><?= $out_count; ?> Unit</p>
            </div>
        </div>
    </div>

</body>
</html>