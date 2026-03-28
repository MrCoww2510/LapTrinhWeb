<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

// Chỉ xử lý POST request và user đã đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
    $uid = $_SESSION['user']['id'];
    $address_id = trim($_POST['id'] ?? '');

    if (empty($address_id)) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy ID địa chỉ!']);
        exit();
    }

    // 1. Kiểm tra database xem địa chỉ có thuộc về user hiện tại không
    // (Bảo mật: Không cho user xóa địa chỉ của người khác)
    $stmt_check = $Conn->prepare("SELECT id FROM user_addresses WHERE id = ? AND user_id = ?");
    $stmt_check->bind_param("ss", $address_id, $uid);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // 2. Nếu khớp -> Thực hiện câu lệnh xóa
        $stmt_delete = $Conn->prepare("DELETE FROM user_addresses WHERE id = ?");
        $stmt_delete->bind_param("s", $address_id);

        if ($stmt_delete->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi thực thi: ' . $Conn->error]);
        }
        $stmt_delete->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Địa chỉ không tồn tại hoặc không thuộc quyền của bạn!']);
    }
    $stmt_check->close();
} else {

    echo json_encode(['success' => false, 'message' => 'Truy cập không hợp lệ!']);
    exit();
}
?>