<?php
session_start(); 

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require '../dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_post'])) {
    $post_id = $_POST['post_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $title, $content, $post_id, $user_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Bài đăng đã được cập nhật thành công!";
    } else {
        $_SESSION['message'] = "Lỗi: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../home.php");
    exit();
}

// Lấy dữ liệu bài đăng để chỉnh sửa
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM posts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    if (!$post) {
        $_SESSION['message'] = "Bài đăng không tồn tại hoặc bạn không có quyền chỉnh sửa bài đăng này.";
        header("Location: ../home.php");
        exit();
    }
} else {
    header("Location: ../home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>

</head>
<body>
    <div>
        <div>
            <h2>Chỉnh sửa bài đăng</h2>
            <a href="../login.php">Đăng xuất</a>
        </div>

        <div>
            <div>
                <form method="post" action="edit-post.php">
                    <input type="hidden" name="edit_post" value="1">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <label for="title">Tiêu đề</label><br>
                    <input type="text" id="title" name="title" value="<?php echo $post['title']; ?>" required><br>
                    <label for="content">Nội dung</label><br>
                    <textarea id="content" name="content" required><?php echo $post['content']; ?></textarea><br>
                    <button type="submit">Lưu</button>
                    <button type="button" onclick="window.location.href='../home.php'">Hủy</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>