<?php
session_start();
include 'config.php';

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

$error_msg = "";

if ($_SESSION['login_attempts'] >= 3) {
    $error_msg = "Aplikasi Terkunci! Anda telah salah memasukkan password sebanyak 3 kali.";
}

if (isset($_POST['login']) && $_SESSION['login_attempts'] < 3) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($password === $row['password']) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['login_attempts'] = 0; 
            header("Location: dashboard.php");
            exit;
        }
    }
    
    $_SESSION['login_attempts']++;
    $sisa = 3 - $_SESSION['login_attempts'];
    if ($_SESSION['login_attempts'] >= 3) {
        $error_msg = "Aplikasi Terkunci! Anda telah salah memasukkan password sebanyak 3 kali.";
    } else {
        $error_msg = "Username atau Password salah! Sisa percobaan: $sisa";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - WMS</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        h2 { color: #333; margin-bottom: 20px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #007bff; border: none; color: white; font-size: 16px; border-radius: 4px; cursor: pointer; }
        button:disabled { background: #cccccc; cursor: not-allowed; }
        .alert { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; text-align: left; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Warehouse Management</h2>
        <?php if($error_msg != ""): ?>
            <div class="alert"><?= $error_msg; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required <?= ($_SESSION['login_attempts'] >= 3) ? 'disabled' : ''; ?>>
            <input type="password" name="password" placeholder="Password" required <?= ($_SESSION['login_attempts'] >= 3) ? 'disabled' : ''; ?>>
            <button type="submit" name="login" <?= ($_SESSION['login_attempts'] >= 3) ? 'disabled' : ''; ?>>Login</button>
        </form>
    </div>
</body>
</html>