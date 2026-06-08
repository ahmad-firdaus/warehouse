<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: index.php"); exit; }
include 'config.php';

if (isset($_POST['stock_in'])) {
    $product_code = $_POST['product_code'];
    $supplier_id = $_POST['supplier_id'];
    $date = $_POST['incoming_date'];
    $qty = intval($_POST['quantity']);

    mysqli_query($conn, "INSERT INTO incoming_stock (product_code, supplier_id, incoming_date, quantity) VALUES ('$product_code', '$supplier_id', '$date', $qty)");
    
 
    mysqli_query($conn, "UPDATE products SET product_stock = product_stock + $qty WHERE product_code = '$product_code'");
    
    header("Location: stok_masuk.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Incoming Stock</title></head>
<body style="display: flex; min-height: 100vh; background: #f4f6f9; font-family: Arial;">

    <div style="width: 250px; background: #343a40; color: white; padding: 20px;">
        <h3>WMS GUDANG</h3><br>
        <a href="dashboard.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Dashboard</a>
        <a href="produk.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Product Management</a>
        <a href="supplier.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Supplier Management</a>
        <a href="stok_masuk.php" style="display:block; color:white; padding:12px; text-decoration:none; background:#495057; border-radius:4px;">Incoming Stock</a>
        <a href="stok_keluar.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Outgoing Stock</a>
        <a href="laporan.php" style="display:block; color:#c2c7d0; padding:12px; text-decoration:none;">Reports</a>
        <a href="logout.php" style="display:block; color:#ff8080; padding:12px; text-decoration:none; margin-top:30px;">Logout</a>
    </div>

    <div style="flex: 1; padding: 30px;">
        <h2>Incoming Stock (Barang Masuk)</h2><br>

        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow:0 2px 5px rgba(0,0,0,0.05);">
            <form action="" method="POST">
                <table style="width:100%;">
                    <tr>
                        <td>Pilih Produk</td>
                        <td>
                            <select name="product_code" required style="width:100%; padding:8px;">
                                <?php $p = mysqli_query($conn, "SELECT product_code, product_name FROM products"); while($r=mysqli_fetch_assoc($p)) { echo "<option value='".$r['product_code']."'>".$r['product_name']."</option>"; } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Pilih Supplier</td>
                        <td>
                            <select name="supplier_id" required style="width:100%; padding:8px;">
                                <?php $s = mysqli_query($conn, "SELECT supplier_id, supplier_name FROM suppliers"); while($r=mysqli_fetch_assoc($s)) { echo "<option value='".$r['supplier_id']."'>".$r['supplier_name']."</option>"; } ?>
                            </select>
                        </td>
                    </tr>
                    <tr><td>Tanggal Masuk</td><td><input type="date" name="incoming_date" required style="width:100%; padding:8px;"></td></tr>
                    <tr><td>Jumlah (Qty)</td><td><input type="number" name="quantity" min="1" required style="width:100%; padding:8px;"></td></tr>
                    <tr><td colspan="2"><button type="submit" name="stock_in" style="padding:10px 20px; background:#28a745; color:white; border:none; border-radius:4px; cursor:pointer;">Input Barang Masuk</button></td></tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>