<?php
require_once 'config.php'; 
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Vui lòng đăng nhập để thêm địa chỉ!'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $user_id = $_SESSION['user']['id'];
    
    $ho_ten = $Conn->real_escape_string($_POST['addr_fullname']);
    $so_dien_thoai = $Conn->real_escape_string($_POST['addr_phone']);
    $dia_chi = $Conn->real_escape_string($_POST['dia_chi_day_du']);

    $sql = "INSERT INTO user_addresses (user_id, ho_ten, so_dien_thoai, dia_chi_day_du) 
            VALUES ($user_id, '$ho_ten', '$so_dien_thoai', '$dia_chi')";

    if ($Conn->query($sql) === TRUE) {
        echo "<script>
                alert('Thêm địa chỉ mới thành công!');
                window.location.href='ThongTinTaiKhoan.php';
              </script>";
    } else {
        echo "Lỗi khi lưu địa chỉ: " . $Conn->error;
    }
}
?>