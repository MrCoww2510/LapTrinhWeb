<?php
require_once("config.php");

// =======================
// XỬ LÝ SẮP XẾP
// =======================

$sort = $_GET['sort'] ?? 'featured';

$order = "";

switch ($sort) {

	case "name_asc":
		$order = "ORDER BY p.name ASC";
		break;

	case "name_desc":
		$order = "ORDER BY p.name DESC";
		break;

	case "price_asc":
		$order = "ORDER BY p.price ASC";
		break;

	case "price_desc":
		$order = "ORDER BY p.price DESC";
		break;

	default:
		$order = "ORDER BY p.id DESC"; // nổi bật
}

$sql = "SELECT p.id, p.name, p.price, p.stock, p.image,
        b.name AS brand_name,
        c.name AS category_name
        FROM products p
        LEFT JOIN brands b ON p.brand_id = b.id
        LEFT JOIN categories c ON p.category_id = c.id
        $order";

$result = $Conn->query($sql);
?>

<section class="SP_TrangSanPham">

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

				<div class="SP_HinhAnh">
					<img src="SanPham/<?= $row['image'] ?>" alt="img">
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


<script>
function toggleSort() {
	document.getElementById("SP_SapXepMenu").classList.toggle("show");
}

function selectSort(type) {

	document.getElementById("SP_SXHienTai").innerText = type;

	let sortValue = "featured";

	if (type === "Tên từ A-Z") sortValue = "name_asc";
	if (type === "Tên từ Z-A") sortValue = "name_desc";
	if (type === "Giá tăng dần") sortValue = "price_asc";
	if (type === "Giá giảm dần") sortValue = "price_desc";

	window.location.href = "TrangSanPham.php?sort=" + sortValue;
}

// hiển thị sort hiện tại khi load trang
const urlParams = new URLSearchParams(window.location.search);
const sort = urlParams.get('sort');

if (sort === "name_asc") document.getElementById("SP_SXHienTai").innerText = "Tên từ A-Z";
if (sort === "name_desc") document.getElementById("SP_SXHienTai").innerText = "Tên từ Z-A";
if (sort === "price_asc") document.getElementById("SP_SXHienTai").innerText = "Giá tăng dần";
if (sort === "price_desc") document.getElementById("SP_SXHienTai").innerText = "Giá giảm dần";
</script>