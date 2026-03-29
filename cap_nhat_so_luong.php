<?php
require_once("config.php");

if (!KiemTraDangNhap()) {
    exit();
}

if (isset($_GET['id']) && isset($_GET['qty'])) {
    $detail_id = intval($_GET['id']);
    $new_quantity = intval($_GET['qty']);
    $user_id = intval($_SESSION['user']['id']);

    if ($new_quantity > 0) {
        $sql_update = "UPDATE order_details 
                       JOIN orders ON order_details.order_id = orders.id 
                       SET order_details.quantity = $new_quantity 
                       WHERE order_details.id = $detail_id 
                       AND orders.user_id = $user_id 
                       AND orders.status = 'Cart'";

        $Conn->query($sql_update);
    }
}

header("Location: GioHang.php");
exit();
