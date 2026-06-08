<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: index.php"); exit; }
include 'config.php';

$search = "";
if (isset($_GET['keyword'])) {
    $search = mysqli_real_escape_string($conn, $_GET['keyword']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Laporan Gudang</title></head>
<body style="display: flex; min-height: 100vh; background: #f4f6f9; font-family: Arial;">

    <div style="width: 250px; background: #343a40; color: white; padding: 20px;">
        <h3>WMS GUDANG</h3><br>
        <a href="dashboard.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Dashboard</a>
        <a href="produk.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Product Management</a>
        <a href="supplier.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Supplier Management</a>
        <a href="stok_masuk.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Incoming Stock</a>
        <a href="stok_keluar.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Outgoing Stock</a>
        <a href="laporan.php" style="display:block; color:white; padding:12px; text-decoration:none; background:#495057; border-radius:4px;">Reports</a>
        <a href="logout.php" style="display:block; color:#ff8080; padding:12px; text-decoration:none; margin-top:30px;">Logout</a>
    </div>

    <div style="flex: 1; padding: 30px;">
        <h2>Product Stock Report</h2><br>

        <form action="" method="GET" style="margin-bottom:20px;">
            <input type="text" name="keyword" value="<?= $search; ?>" placeholder="Cari berdasarkan nama produk..." style="padding:10px; width:300px; border:1px solid #ccc; border-radius:4px;">
            <button type="submit" style="padding:10px 15px; background:#007bff; color:white; border:none; border-radius:4px; cursor:pointer;">Cari</button>
            <a href="laporan.php" style="padding:10px; background:#6c757d; color:white; text-decoration:none; border-radius:4px; margin-left:5px;">Reset</a>
        </form>

        <table style="width:100%; border-collapse:collapse; background:white;">
            <thead>
                <tr style="background:#007bff; color:white;">
                    <th style="padding:12px;">Kode</th>
                    <th style="padding:12px;">Nama Produk</th>
                    <th style="padding:12px;">Kategori</th>
                    <th style="padding:12px;">Stok Akhir Tersedia</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM products";
                if ($search != "") {
                    $query .= " WHERE product_name LIKE '%$search%'";
                }
                $res = mysqli_query($conn, $query);
                if(mysqli_num_rows($res) == 0) {
                    echo "<tr><td colspan='4' style='text-align:center; padding:20px;'>Data tidak ditemukan.</td></tr>";
                }
                while ($row = mysqli_fetch_assoc($res)):
                ?>
                <tr>
                    <td style="padding:10px;"><?= $row['product_code']; ?></td>
                    <td style="padding:10px;"><?= $row['product_name']; ?></td>
                    <td style="padding:10px;"><?= $row['product_category']; ?></td>
                    <td style="padding:10px; font-weight:bold; color: <?= ($row['product_stock'] < 5)?'red':'black'; ?>;"><?= $row['product_stock']; ?> Unit</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>