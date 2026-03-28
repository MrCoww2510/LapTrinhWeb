<?php
require_once("config.php");

if (!KiemTraDangNhap()) {
    echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='DangNhap.php';</script>";
    exit();
}

$user_id = intval($_SESSION['user']['id']);

//Tìm giỏ hàng hiện tại
$sql_find_cart = "SELECT id FROM orders WHERE user_id = $user_id AND status = 'Cart'";
$result_cart = $Conn->query($sql_find_cart);

if ($result_cart->num_rows > 0) {
    $row_cart = $result_cart->fetch_assoc();
    $order_id = $row_cart['id'];

    // Tính toán tổng tiền thực tế từ order_details để lưu vào hóa đơn chính
    $sql_calculate = "SELECT SUM(quantity * price) AS final_total FROM order_details WHERE order_id = $order_id";
    $result_calc = $Conn->query($sql_calculate);
    $row_calc = $result_calc->fetch_assoc();

    $final_total = $row_calc['final_total'];

    // Đảm bảo giỏ hàng không bị trống) thì mới cho thanh toán
    if ($final_total > 0) {

        $sql_checkout = "UPDATE orders SET status = 'Pending', total_price = $final_total WHERE id = $order_id";

        if ($Conn->query($sql_checkout) === TRUE) {
            echo "<script>
                alert('🎉 Đặt hàng thành công!');
                window.location.href = 'TrangSanPham.php';
            </script>";
        } else {
            echo "<script>alert('Lỗi hệ thống khi thanh toán: " . $Conn->error . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Giỏ hàng của bạn đang trống, không thể thanh toán!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Không tìm thấy giỏ hàng!'); window.history.back();</script>";
}
