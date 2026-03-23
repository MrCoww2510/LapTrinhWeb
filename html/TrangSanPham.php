<?php
require_once("config.php");

// =======================
// LẤY DỮ LIỆU TỪ DATABASE
// =======================

$sql = "SELECT p.id, p.name, p.price, p.stock, p.image,
               b.name AS brand_name,
               c.name AS category_name
        FROM products p
        JOIN brands b ON p.brand_id = b.id
        JOIN categories c ON p.category_id = c.id";
$result = $Conn->query($sql);
?>
<section>

	<div class="SP_ThanhSP">
		<h2>Sản phẩm</h2>

		<div class="SP_SapXep">
			<button class="SP_SapXepNut" onclick="toggleSort()">
				Sắp xếp: <span id="SP_SXHienTai">Nổi bật</span> ▼
			</button>

			<ul class="SP_SapXepMenu" id="SP_SapXepMenu">
				<li onclick="selectSort('Nổi bật')">Nổi bật</li>
				<li onclick="selectSort('Tên từ A-Z')">Tên từ A-Z</li>
				<li onclick="selectSort('Tên từ Z-A')">Tên từ Z-A</li>
				<li onclick="selectSort('Giá tăng dần')">Giá tăng dần</li>
				<li onclick="selectSort('Giá giảm dần')">Giá giảm dần</li>
			</ul>
		</div>
	</div>

	<div class="SP_DanhSachSanPham">

		<?php while ($row = $result->fetch_assoc()): ?>

		<div class="SP_SanPham">
			<a href="ChiTietSanPham.php?id=<?= $row['id'] ?>">

				<!-- ================= HÌNH ================= -->
				<div class="SP_HinhAnh">
					<img src="SanPham/<?= $row['image'] ?>" alt="img" />
				</div>

				<!-- ================= TÊN ================= -->
				<h3 class="SP_TenSanPham">
					<?= $row['name'] ?>
				</h3>

				<!-- ================= THÔNG TIN ================= -->
				<div class="SP_ThongSo">
					<div>Hãng: <?= $row['brand_name'] ?></div>
					<div>Loại: <?= $row['category_name'] ?></div>
					<div>Còn lại: <?= $row['stock'] ?></div>
				</div>

				<!-- ================= GIÁ ================= -->
				<div class="SP_Gia">
					<span class="SP_GiaMoi">
						<?= number_format($row['price'], 0, ',', '.') ?>đ
					</span>
				</div>

				<!-- ================= ĐÁNH GIÁ (FAKE) ================= -->
				<div class="SP_DanhGia">
					<span class="SP_Sao">★</span> 5.0
					<span class="SP_NhanXet">(100 đánh giá)</span>
				</div>

			</a>

			<!-- ================= THÊM GIỎ ================= -->
			<a href="addToCart.php?id=<?= $row['id'] ?>">
				<button>Thêm vào giỏ</button>
			</a>
		</div>

		<?php endwhile; ?>

	</div>
</section>
<!-- file cũ -->
<script>
function toggleSort() {
	let menu = document.getElementById("SP_SapXepMenu");
	menu.style.display = (menu.style.display === "block") ? "none" : "block";
}

function selectSort(text) {
	document.getElementById("SP_SXHienTai").innerText = text;
	document.getElementById("SP_SapXepMenu").style.display = "none";
}
</script>