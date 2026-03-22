<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
    $uid = $_SESSION['user']['id'];
    $fullname = $Conn->real_escape_string($_POST['fullname']);
    $phone = $Conn->real_escape_string($_POST['phone']);
    $new_email = $Conn->real_escape_string($_POST['email']); 
    $gender = $_POST['gender'];
    
    // Ghép ngày sinh 
    $ngay_sinh = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];

    // Cập nhật email và SĐT vào Database
    $sql = "UPDATE users SET 
            fullname = '$fullname', 
            phone = '$phone', 
            email = '$new_email', 
            gioi_tinh = '$gender', 
            ngay_sinh = '$ngay_sinh' 
            WHERE id = $uid";

    if ($Conn->query($sql) === TRUE) {
        // Cập nhật lại Session để hiển thị ngay lập tức trên Header
        $_SESSION['user']['fullname'] = $fullname;
        $_SESSION['user']['email'] = $new_email;
        
        echo "<script>alert('Cập nhật thành công!'); window.location.href='ThongTinTaiKhoan.php';</script>";
    } else {
        echo "Lỗi: " . $Conn->error;
    }
}
?>