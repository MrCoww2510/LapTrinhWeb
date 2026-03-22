<?php
	require_once("config.php");
?>
<!DOCTYPE html>
<html lang="vi">

<head>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>F1GamingGear</title>
	<link rel="stylesheet" href="css/Style.css">
	<link rel="stylesheet" href="css/TrangChu.css">
	<link rel="stylesheet" href="css/TrangSanPham.css">
	<link rel="stylesheet" href="css/DangNhap.css">
	<link rel="stylesheet" href="css/ChiTietSanPham.css">
	<link rel="stylesheet" href="css/TrangBaoHanh.css">
	<link rel="stylesheet" href="css/GioHang.css">
	<link rel="stylesheet" href="css/TrangGioiThieu.css">
	<link rel="stylesheet" href="css/TraCuuDonHang.css">

</head>

<body>
	<header class="HD_header">
		<!-- ===== TOP HEADER ===== -->
		<div class="HD_topHeader">
			<div class="HD_logo">
				<a href="index.php">
					<img src="img/Logo.png" alt="logo">
				</a>
			</div>

			<div class="HD_search">
				<input type="text" placeholder="Tìm sản phẩm...">
				<button>Tìm</button>
			</div>

			<div class="HD_rightMenu">
				<a href="GioHang.php" class="HD_menu_item">Giỏ hàng</a>
				
				<?php if (isset($_SESSION['user'])): ?>
					<a href="ThongTinTaiKhoan.php" class="HD_menu_item">
						<i class="fas fa-user"></i> <?php echo explode(' ', $_SESSION['user']['fullname'])[0]; ?>
					</a>
				<?php else: ?>
					<a href="javascript:void(0)" onclick="moPopupDangNhap()" class="HD_menu_item">Tài khoản</a>
				<?php endif; ?>
			</div>
		</div>
		<!-- ===== MENU ===== -->
		<nav class="HD_nav">
			<ul class="HD_menu">
				<li><a href="index.php">Trang chủ</a></li>
				<li><a href="TrangGioiThieu.php">Giới thiệu</a></li>
				<li><a href="TrangBaoHanh.php">Bảo hành</a></li>
				<li><a href="TrangSanPham.php">Sản phẩm</a></li>
				<li><a href="TraCuuDonHang.php">Tra cứu đơn hàng</a></li>
				<li><a href="LienHe.php">Liên hệ</a></li>
			</ul>
		</nav>
	</header>
	<?php include 'html/DangNhap.html'; ?>