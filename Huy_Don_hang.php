<?php
require_once("config.php");

// 1. Kiểm tra đăng nhập
if (!KiemTraDangNhap()) {
    echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='index.php';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']);
    $user_id = intval($_SESSION['user']['id']);

    // 2. Kiểm tra bảo mật: Chỉ cho phép hủy nếu đơn hàng là của CHÍNH USER NÀY và đang ở trạng thái 'Pending'
    $sql_check = "SELECT id FROM orders WHERE id = $order_id AND user_id = $user_id AND status = 'Pending'";
    $result = $Conn->query($sql_check);

    if ($result->num_rows > 0) {
        // 3. Thực thi việc hủy đơn
        $sql_cancel = "UPDATE orders SET status = 'Cancelled' WHERE id = $order_id";

        if ($Conn->query($sql_cancel) === TRUE) {
            echo "<script>
                alert('Hủy đơn hàng thành công!'); 
                window.location.href='TraCuuDonHang.php';
            </script>";
        } else {
            echo "<script>alert('Lỗi hệ thống khi hủy đơn: " . $Conn->error . "'); window.history.back();</script>";
        }
    } else {
        // Nếu cố tình hủy đơn đã xử lý hoặc đơn của người khác
        echo "<script>alert('Không thể hủy đơn hàng này! Đơn hàng có thể đã được xử lý hoặc không tồn tại.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Thiếu thông tin mã đơn hàng!'); window.history.back();</script>";
}
