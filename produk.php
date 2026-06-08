<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: index.php"); exit; }
include 'config.php';


if (isset($_POST['add_product'])) {
    if ($_SESSION['role'] !== 'staff') { echo "<script>alert('Akses Ditolak!');</script>"; } else {
        $code = mysqli_real_escape_string($conn, $_POST['product_code']);
        $name = mysqli_real_escape_string($conn, $_POST['product_name']);
        $category = mysqli_real_escape_string($conn, $_POST['product_category']);
        $price = intval($_POST['product_price']);
        
       
        $filename = $_FILES['product_image']['name'];
        $tempname = $_FILES['product_image']['tmp_name'];
        $folder = "assets/uploads/" . time() . "_" . $filename;
        
        
        if (!is_dir('assets/uploads/')) { mkdir('assets/uploads/', 0777, true); }

        if (move_uploaded_file($tempname, $folder)) {
            mysqli_query($conn, "INSERT INTO products VALUES ('$code', '$name', '$category', 0, '$price', '$folder')");
            header("Location: produk.php");
        } else {
            echo "<script>alert('Gagal mengunggah gambar!');</script>";
        }
    }
}


if (isset($_GET['delete'])) {
    if ($_SESSION['role'] !== 'manager') {
        echo "<script>alert('Hanya Warehouse Manager yang boleh menghapus data!'); window.location='produk.php';</script>";
    } else {
        $id = mysqli_real_escape_string($conn, $_GET['delete']);
      
        $img_res = mysqli_query($conn, "SELECT product_image FROM products WHERE product_code='$id'");
        $img_data = mysqli_fetch_assoc($img_res);
        if(file_exists($img_data['product_image'])) { unlink($img_data['product_image']); }
        
        mysqli_query($conn, "DELETE FROM products WHERE product_code='$id'");
        header("Location: produk.php");
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Product Management</title>
    <link rel="stylesheet" href="dashboard.php"> </head>
<body style="display: flex; min-height: 100vh; background: #f4f6f9; font-family: Arial;">

    <div style="width: 250px; background: #343a40; color: white; padding: 20px;">
        <h3>WMS GUDANG</h3><br>
        <a href="dashboard.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Dashboard</a>
        <a href="produk.php" style="display:block; color:white; padding:12px; text-decoration:none; background:#495057; border-radius:4px;">Product Management</a>
        <a href="supplier.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Supplier Management</a>
        <a href="stok_masuk.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Incoming Stock</a>
        <a href="stok_keluar.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Outgoing Stock</a>
        <a href="laporan.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Reports</a>
        <a href="logout.php" style="display:block; color:#ff8080; padding:12px; text-decoration:none; margin-top:30px;">Logout</a>
    </div>

    <div style="flex: 1; padding: 30px;">
        <h2>Product Management</h2><br>

        <?php if ($_SESSION['role'] === 'staff'): ?>
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <h3>Tambah Produk Baru</h3><br>
            <form action="" method="POST" enctype="multipart/form-data">
                <table style="box-shadow: none; width:100%;">
                    <tr>
                        <td>Kode Produk</td>
                        <td><input type="text" name="product_code" required style="width:100%; padding:8px;"></td>
                    </tr>
                    <tr>
                        <td>Nama Produk</td>
                        <td><input type="text" name="product_name" required style="width:100%; padding:8px;"></td>
                    </tr>
                    <tr>
                        <td>Kategori</td>
                        <td><input type="text" name="product_category" required style="width:100%; padding:8px;"></td>
                    </tr>
                    <tr>
                        <td>Harga</td>
                        <td><input type="number" name="product_price" required style="width:100%; padding:8px;"></td>
                    </tr>
                    <tr>
                        <td>Foto Produk</td>
                        <td><input type="file" name="product_image" accept="image/*" required></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button type="submit" name="add_product" style="padding:10px 20px; background:#28a745; color:white; border:none; border-radius:4px; cursor:pointer;">Simpan Produk</button></td>
                    </tr>
                </table>
            </form>
        </div>
        <?php endif; ?>

        <table style="width:100%; border-collapse:collapse; background:white;">
            <thead>
                <tr style="background:#007bff; color:white;">
                    <th style="padding:12px;">Foto</th>
                    <th style="padding:12px;">Kode</th>
                    <th style="padding:12px;">Nama Produk</th>
                    <th style="padding:12px;">Kategori</th>
                    <th style="padding:12px;">Stok</th>
                    <th style="padding:12px;">Harga</th>
                    <th style="padding:12px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = mysqli_query($conn, "SELECT * FROM products");
                while ($row = mysqli_fetch_assoc($res)):
                ?>
                <tr>
                    <td style="padding:10px; text-align:center;"><img src="<?= $row['product_image']; ?>" width="60" height="60" style="border-radius:4px; object-fit:cover;"></td>
                    <td style="padding:10px;"><?= $row['product_code']; ?></td>
                    <td style="padding:10px;"><?= $row['product_name']; ?></td>
                    <td style="padding:10px;"><?= $row['product_category']; ?></td>
                    <td style="padding:10px; font-weight:bold;"><?= $row['product_stock']; ?></td>
                    <td style="padding:10px;">Rp <?= number_format($row['product_price'], 0, ',', '.'); ?></td>
                    <td style="padding:10px;">
                        <?php if ($_SESSION['role'] === 'manager'): ?>
                            <a href="produk.php?delete=<?= $row['product_code']; ?>" onclick="return confirm('Hapus produk ini?')" style="padding:6px 12px; background:#dc3545; color:white; text-decoration:none; border-radius:4px; font-size:13px;">Delete</a>
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