<?php
// Nhúng file kết nối database
require_once("config.php");

// 1. KIỂM TRA ĐĂNG NHẬP
// Nếu chưa có session 'user', tức là chưa đăng nhập
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    echo "<script>
        alert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!');
        window.location.href = 'DangNhap.php'; // Đổi lại tên file đăng nhập nếu của bạn khác nhé
    </script>";
    exit(); // Dừng toàn bộ code bên dưới
}

// Lấy ID thật của khách hàng từ Session
$user_id = intval($_SESSION['user']['id']);

// 2. XỬ LÝ THÊM VÀO GIỎ HÀNG
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']); // Lấy ID sản phẩm từ URL và chuyển thành số nguyên

    // Bước 1: Lấy giá tiền hiện tại của sản phẩm
    $sql_price = "SELECT price FROM products WHERE id = $product_id";
    $result_price = $Conn->query($sql_price);

    if ($result_price->num_rows > 0) {
        $row_price = $result_price->fetch_assoc();
        $price = $row_price['price'];

        // Bước 2: Kiểm tra xem khách hàng này đã có giỏ hàng (đơn hàng trạng thái 'Cart') chưa
        // Bước 2: Kiểm tra xem khách hàng này đã có giỏ hàng chưa
        $sql_check_order = "SELECT id FROM orders WHERE user_id = $user_id AND status = 'Cart'";
        $result_order = $Conn->query($sql_check_order);

        if ($result_order->num_rows > 0) {
            $row_order = $result_order->fetch_assoc();
            $order_id = $row_order['id'];
        } else {
            // Thêm giỏ hàng mới và BẮT LỖI NẾU CÓ
            $sql_create_order = "INSERT INTO orders (user_id, total_price, status) VALUES ($user_id, 0, 'Cart')";
            if ($Conn->query($sql_create_order) === TRUE) {
                $order_id = $Conn->insert_id;
            } else {
                die("Lỗi tạo giỏ hàng: " . $Conn->error); // In lỗi ra màn hình để biết đường sửa
            }
        }

        // Bước 3: Thêm vào chi tiết giỏ hàng và BẮT LỖI
        $sql_check_detail = "SELECT id, quantity FROM order_details WHERE order_id = $order_id AND product_id = $product_id";
        $result_detail = $Conn->query($sql_check_detail);

        if ($result_detail->num_rows > 0) {
            $sql_update_qty = "UPDATE order_details SET quantity = quantity + 1 WHERE order_id = $order_id AND product_id = $product_id";
            if (!$Conn->query($sql_update_qty)) die("Lỗi cập nhật số lượng: " . $Conn->error);
        } else {
            $sql_insert_detail = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, 1, $price)";
            if (!$Conn->query($sql_insert_detail)) die("Lỗi thêm chi tiết: " . $Conn->error);
        }

        // Hiển thị thông báo thành công
        echo "<script>
            alert('Đã thêm sản phẩm vào giỏ hàng thành công!');
            window.location.href = 'TrangSanPham.php'; // Quay lại trang sản phẩm
        </script>";
    } else {
        echo "<script>alert('Lỗi: Sản phẩm không tồn tại!'); window.history.back();</script>";
    }
} else {
    // Trường hợp URL không có ?id=...
    echo "<script>alert('Không nhận được dữ liệu sản phẩm!'); window.history.back();</script>";
}
