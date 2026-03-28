<?php
require_once("config.php");

// Kiểm tra đăng nhập
if (!KiemTraDangNhap()) {
    echo "<script>
        alert('Vui lòng đăng nhập!');
        window.location.href = 'DangNhap.php';
    </script>";
    exit();
}

// Xử lý xóa sản phẩm
if (isset($_GET['id'])) {

    $detail_id = intval($_GET['id']);
    $user_id = intval($_SESSION['user']['id']);

    /* 3. Câu lệnh DELETE bảo mật:
       Chỉ cho phép xóa dòng trong bảng order_details NẾU:
       - Đúng ID chi tiết đơn hàng ($detail_id)
       - Đơn hàng đó phải thuộc về User đang đăng nhập ($user_id)
       - Đơn hàng đó phải đang ở trạng thái Giỏ hàng ('Cart')
       (Viết thế này để chống trường hợp user đổi số ID trên URL để xóa trộm đồ của người khác)
    */
    $sql_delete = "DELETE order_details 
                   FROM order_details 
                   JOIN orders ON order_details.order_id = orders.id 
                   WHERE order_details.id = $detail_id 
                   AND orders.user_id = $user_id 
                   AND orders.status = 'Cart'";

    if ($Conn->query($sql_delete) === TRUE) {
        // Xóa thành công, quay lại trang giỏ hàng
        header("Location: GioHang.php");
        exit();
    } else {
        echo "<script>alert('Lỗi khi xóa sản phẩm: " . $Conn->error . "'); window.history.back();</script>";
    }
} else {
    // Nếu không có ID truyền vào
    echo "<script>alert('Không tìm thấy sản phẩm cần xóa!'); window.history.back();</script>";
}
