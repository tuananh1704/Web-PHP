<?php
session_start();

// Kiểm tra xem admin đã đăng nhập chưa
if(!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

require '../dbconnect.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Kiểm tra người dùng đã tồn tại chưa
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        // Người dùng đã tồn tại
        $error = "Người dùng hoặc email đã tồn tại!";
    }
    else {
        // Thêm người dùng mới
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $username, $password, $email);

        if($stmt->execute()) {
            header("Location: admin-panel.php");
            exit();
        }
        else {
            echo "Lỗi: ".$conn->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Người Dùng</title>
</head>
<body>
    <div>
        <h1>Thêm Người Dùng</h1>
        <?php
        if (isset($error)) {
            echo "<div class='error'>$error</div>";
        }
        ?>
        <form action="add-user.php" method="POST">
            <label for="username">Tên đăng nhập:</label><br>
            <input id="username" type="text" name="username" required><br>
            
            <label for="password">Mật khẩu:</label><br>
            <input id="password" type="text" name="password" required><br>
            
            <label for="email">Email:</label><br>
            <input id="email" type="email" name="email" required><br><br>
            
            <div class="button-group">
                <button type="submit">Thêm</button>
                <a class="cancel-btn" href="admin-panel.php">Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>