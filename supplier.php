<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: index.php"); exit; }
include 'config.php';


if (isset($_POST['add_supplier'])) {
    if ($_SESSION['role'] !== 'staff') { echo "<script>alert('Akses Ditolak!');</script>"; } else {
        $name = mysqli_real_escape_string($conn, $_POST['supplier_name']);
        $address = mysqli_real_escape_string($conn, $_POST['supplier_address']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        
        mysqli_query($conn, "INSERT INTO suppliers (supplier_name, supplier_address, phone_number, email) VALUES ('$name', '$address', '$phone', '$email')");
        header("Location: supplier.php");
    }
}

if (isset($_GET['delete'])) {
    if ($_SESSION['role'] !== 'manager') {
        echo "<script>alert('Hanya Warehouse Manager yang boleh menghapus data!'); window.location='supplier.php';</script>";
    } else {
        $id = intval($_GET['delete']);
        mysqli_query($conn, "DELETE FROM suppliers WHERE supplier_id=$id");
        header("Location: supplier.php");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Supplier Management</title></head>
<body style="display: flex; min-height: 100vh; background: #f4f6f9; font-family: Arial;">

    <div style="width: 250px; background: #343a40; color: white; padding: 20px;">
        <h3>WMS GUDANG</h3><br>
        <a href="dashboard.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Dashboard</a>
        <a href="produk.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Product Management</a>
        <a href="supplier.php" style="display:block; color:white; padding:12px; text-decoration:none; background:#495057; border-radius:4px;">Supplier Management</a>
        <a href="stok_masuk.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Incoming Stock</a>
        <a href="stok_keluar.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Outgoing Stock</a>
        <a href="laporan.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Reports</a>
        <a href="logout.php" style="display:block; color:#ff8080; padding:12px; text-decoration:none; margin-top:30px;">Logout</a>
    </div>

    <div style="flex: 1; padding: 30px;">
        <h2>Supplier Management</h2><br>

        <?php if ($_SESSION['role'] === 'staff'): ?>
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <h3>Tambah Supplier</h3><br>
            <form action="" method="POST">
                <table style="width:100%;">
                    <tr><td>Nama Supplier</td><td><input type="text" name="supplier_name" required style="width:100%; padding:8px;"></td></tr>
                    <tr><td>Alamat</td><td><textarea name="supplier_address" required style="width:100%; padding:8px;"></textarea></td></tr>
                    <tr><td>No Telepon</td><td><input type="text" name="phone_number" required style="width:100%; padding:8px;"></td></tr>
                    <tr><td>Email</td><td><input type="email" name="email" required style="width:100%; padding:8px;"></td></tr>
                    <tr><td colspan="2"><button type="submit" name="add_supplier" style="padding:10px 20px; background:#28a745; color:white; border:none; border-radius:4px; cursor:pointer;">Simpan Supplier</button></td></tr>
                </table>
            </form>
        </div>
        <?php endif; ?>

        <table style="width:100%; border-collapse:collapse; background:white; border:1px solid #dee2e6;">
            <thead>
                <tr style="background:#007bff; color:white;">
                    <th style="padding:12px;">ID</th><th style="padding:12px;">Nama Supplier</th><th style="padding:12px;">Alamat</th><th style="padding:12px;">No Telp</th><th style="padding:12px;">Email</th><th style="padding:12px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = mysqli_query($conn, "SELECT * FROM suppliers");
                while ($row = mysqli_fetch_assoc($res)):
                ?>
                <tr>
                    <td style="padding:10px;"><?= $row['supplier_id']; ?></td>
                    <td style="padding:10px;"><?= $row['supplier_name']; ?></td>
                    <td style="padding:10px;"><?= $row['supplier_address']; ?></td>
                    <td style="padding:10px;"><?= $row['phone_number']; ?></td>
                    <td style="padding:10px;"><?= $row['email']; ?></td>
                    <td style="padding:10px;">
                        <?php if ($_SESSION['role'] === 'manager'): ?>
                            <a href="supplier.php?delete=<?= $row['supplier_id']; ?>" onclick="return confirm('Hapus supplier ini?')" style="padding:6px 12px; background:#dc3545; color:white; text-decoration:none; border-radius:4px; font-size:13px;">Delete</a>
                        <?php else: ?>
                            <span style="color:#aaa; font-size:13px;">No Action</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>