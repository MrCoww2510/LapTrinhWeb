<?php
include("config.php")
?>
<!DOCTYPE html>
<html lang="vi">

<head>
	<meta charset="UTF-8">
	<title>Quản lý hệ thống bán hàng</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/them.css">
</head>

<body>

	<!-- ================= HEADER ================= -->
	<div class="Header">
		<img src="banner/co2.png" class="Co">
		<img src="banner/co2.png" class="Co2">
		<img src="banner/bang.png" class="Bang">
		<h1 class="Header_Title">QUẢN LÝ HỆ THỐNG BÁN HÀNG</h1>
	</div>

	<!-- ================= MENU ================= -->
	<div class="Menu">
		<ul class="Menu_List">
			<li><a href="index.php">Trang chủ</a></li>

			<li class="Dropdown">
				<a href="#">Thêm</a>
				<ul class="Dropdown_Content">
					<li><a href="ThemSanPham.php">Thêm sản phẩm</a></li>
					<li><a href="ThemNhom.php">Thêm nhóm</a></li>
				</ul>
			</li>

			<li><a href="#">Cập nhật sản phẩm</a></li>
			<li><a href="#">Cập nhật nhóm</a></li>

			<li class="Right">Xin chào: họ và tên | <span class="Logout">Thoát</span></li>
		</ul>
	</div>