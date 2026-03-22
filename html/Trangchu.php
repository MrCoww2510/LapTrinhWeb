<?php
require_once("config.php");

// =======================
// SẢN PHẨM NỔI BẬT
// =======================

$sqlNoiBat = "SELECT p.id, p.name, p.price, p.stock, p.image,
                     b.name AS brand_name,
                     c.name AS category_name
              FROM products p
              JOIN brands b ON p.brand_id = b.id
              JOIN categories c ON p.category_id = c.id
              LIMIT 5";

$resultNoiBat = $Conn->query($sqlNoiBat);

if (!$resultNoiBat) {
	die("Lỗi SQL (Nổi bật): " . $Conn->error);
}

// =======================
// SẢN PHẨM MỚI
// =======================

$sqlMoi = "SELECT p.id, p.name, p.price, p.stock, p.image,
   			b.name AS brand_name,
            c.name AS category_name
           	FROM products p
			JOIN brands b ON p.brand_id = b.id
            JOIN categories c ON p.category_id = c.id
           ORDER BY p.id DESC
           LIMIT 5";

$resultMoi = $Conn->query($sqlMoi);

if (!$resultMoi) {
	die("Lỗi SQL (Mới): " . $Conn->error);
}
?>

<div class="TC_main">

	<!-- ================= BANNER ================= -->
	<section class="TC_banner">
		<img src="img/Banner.png" />
	</section>

	<!-- ================= CATEGORY ================= -->
	<section class="TC_category">
		<h2 class="TC_title">Danh mục sản phẩm</h2>

		<div class="TC_categoryContainer">
			<div class="TC_categoryItem">Bàn phím</div>
			<div class="TC_categoryItem">Chuột</div>
			<div class="TC_categoryItem">Tai nghe</div>
			<div class="TC_categoryItem">Laptop</div>
			<div class="TC_categoryItem">Màn hình</div>
			<div class="TC_categoryItem">Ghế Gaming</div>
		</div>
	</section>

	<!-- ================= SẢN PHẨM NỔI BẬT ================= -->
	<section class="SP_DanhMuc">
		<h2 class="SP_TenDanhMuc">Sản phẩm nổi bật</h2>

		<div class="SP_DanhSachSanPham">

			<?php while ($row = $resultNoiBat->fetch_assoc()): ?>

			<div class="SP_SanPham">
				<a href="ChiTietSanPham.php?id=<?= $row['id'] ?>">

					<div class="SP_HinhAnh">
						<img src="SanPham/<?= $row['image'] ?>" alt="img" />
					</div>

					<h3 class="SP_TenSanPham">
						<?= $row['name'] ?>
					</h3>

					<div class="SP_ThongSo">
						<div>Hãng: <?= $row['brand_name'] ?></div>
						<div>Loại: <?= $row['category_name'] ?></div>
						<div>Còn lại: <?= $row['stock'] ?></div>
					</div>

					<div class="SP_Gia">
						<span class="SP_GiaMoi">
							<?= number_format($row['price'], 0, ',', '.') ?>đ
						</span>
					</div>

					<div class="SP_DanhGia">
						<span class="SP_Sao">★</span> 5.0
						<span class="SP_NhanXet">(100 đánh giá)</span>
					</div>

				</a>

				<a href="addToCart.php?id=<?= $row['id'] ?>">
					<button>Thêm vào giỏ</button>
				</a>
			</div>

			<?php endwhile; ?>

		</div>
	</section>

	<!-- ================= SẢN PHẨM MỚI ================= -->
	<section class="SP_DanhMuc">
		<h2 class="SP_TenDanhMuc">Sản phẩm mới</h2>

		<div class="SP_DanhSachSanPham">

			<?php while ($row = $resultMoi->fetch_assoc()): ?>

			<div class="SP_SanPham">
				<a href="ChiTietSanPham.php?id=<?= $row['id'] ?>">

					<div class="SP_HinhAnh">
						<img src="SanPham/<?= $row['image'] ?>" alt="img" />
					</div>

					<div class="SP_ThongSo">
						<div>Hãng: <?= $row['brand_name'] ?></div>
						<div>Loại: <?= $row['category_name'] ?></div>
						<div>Còn lại: <?= $row['stock'] ?></div>
					</div>

					<h3 class="SP_TenSanPham">
						<?= $row['name'] ?>
					</h3>

					<div class="SP_Gia">
						<span class="SP_GiaMoi">
							<?= number_format($row['price'], 0, ',', '.') ?>đ
						</span>
					</div>

				</a>

				<a href="addToCart.php?id=<?= $row['id'] ?>">
					<button>Thêm vào giỏ</button>
				</a>
			</div>

			<?php endwhile; ?>

		</div>
	</section>

	<!-- ================= BLOG ================= -->
	<section class="TC_category">
		<div class="TC_container">

			<h2 class="TC_blogTitle">Tin công nghệ</h2>

			<div class="TC_blogList">

				<div class="TC_blogCard">
					<div class="TC_blogImage">
						<img src="img/blog1.jpg" />
					</div>

					<div class="TC_blogContent">
						<h3>Top 5 bàn phím cơ đáng mua 2026</h3>
						<p>Gợi ý bàn phím cho game thủ & dev.</p>
					</div>
				</div>

				<div class="TC_blogCard">
					<div class="TC_blogImage">
						<img src="img/blog2.jpg" />
					</div>

					<div class="TC_blogContent">
						<h3>Chuột gaming dưới 1 triệu</h3>
						<p>Ngon - bổ - rẻ cho học sinh.</p>
					</div>
				</div>

				<div class="TC_blogCard">
					<div class="TC_blogImage">
						<img src="img/blog3.jpg" />
					</div>

					<div class="TC_blogContent">
						<h3>Cách chọn màn hình gaming</h3>
						<p>Hiểu rõ refresh rate & panel.</p>
					</div>
				</div>

			</div>
		</div>
	</section>

</div>

<?php
// =======================
// ĐÓNG KẾT NỐI
// =======================
$Conn->close();
?>