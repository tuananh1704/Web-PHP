<?php
session_start();  // Bắt đầu phiên làm việc

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!$_SESSION['user_id']) {
    header("Location: login.php");
    exit();
}

require 'dbconnect.php';

$user_id = $_SESSION['user_id'];
$message = '';

// Hiển thị thông báo từ session và xóa session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Lấy danh sách bài đăng của người dùng
$sql = 'SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC';
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);

// Đóng kết nối
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ</title>

    <script>
        // JavaScript để ẩn thông báo sau 3 giây
        function hideMessage() {
            setTimeout(function() {
                var messageDiv = document.getElementById("message-box");
                if (messageDiv) {
                    messageDiv.style.display = "none";
                }
            }, 3000);
        }
    </script>
    
</head>
<body onload="hideMessage()">
    <div>
        <div>
            <h2>Chào mừng, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <a href="login.php">Đăng xuất</a><br><br>
        </div>
        
        <button onclick="document.getElementById('addPostForm').style.display='block';">Thêm bài đăng</button><br><br>
        
        <div id="addPostForm" style="display:none;">
            <form action="manage-post/add-post.php" method="POST">
                <h2>Thêm Bài Viết</h2>
                <label for="title">Tiêu đề:</label><br>
                <input type="text" id="title" name="title" required><br>
                <label for="content">Nội dung:</label><br>
                <textarea id="content" name="content" required></textarea>
                <button type="submit" name="submit">Thêm</button>
                <button type="button" onclick="window.location.href='home.php'">Hủy</button><br><br>
            </form>
        </div>

        <form method="GET" action="manage-post/search.php">
            <!-- Nếu người dùng nhấn phím Enter (mã phím là 13), form sẽ được tự động gửi đi. -->
            <input type="text" name="search" placeholder="Tìm kiếm bài viết..." onkeypress="if(event.keyCode == 13) { this.form.submit(); }">
        </form>

        <div>
            <!-- Hiển thị thông báo nếu có -->
            <?php if (!empty($message)): ?>
                <div><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Hiển thị danh sách bài viết người dùng -->
            <div>
                <h2>Bài Viết Của Bạn</h2>
                <?php foreach ($posts as $post): ?>
                    <div>
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p><?php echo htmlspecialchars($post['content']); ?></p>
                        <a href="manage-post/edit-post.php?id=<?php echo $post['id']; ?>">Chỉnh Sửa</a>
                        <a href="manage-post/delete-post.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">Xóa</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
