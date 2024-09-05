<?php
session_start();
require '../dbconnect.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        // echo '<pre>';
        // print_r($admin);
        // echo '</pre>';
        if ($password == $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin-panel.php");
            exit();
        }
        else {
            $error = "Sai mật khẩu";
        }
    }
    else {
        $error = "Sai tên đăng nhập";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
</head>
<body>
    <div>
        <h1>Đăng nhập tài khoản Admin</h1>
        <form method="POST" action="admin-login.php">
            <label for="username">Tên đăng nhập:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Mật khẩu:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit">Đăng nhập</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>