<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
require_once("config.php");

// CHẶN KHÔNG PHẢI ADMIN

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
	header("Location: index.php");
	exit();
}

$action = $_GET['action'] ?? "";

// THÊM

if (isset($_POST['add'])) {
	$Name = trim($_POST['name']);
	$CategoryId = (int)$_POST['category_id'];
	$Price = (float)$_POST['price'];
	$Stock = (int)$_POST['stock'];
	$ImageName = $_FILES['image']['name'];
	$TmpName = $_FILES['image']['tmp_name'];

	// tránh trùng tên
	$ImageName = time() . "_" . $ImageName;

	$UploadDir = "SanPham/";
	$Folder = $UploadDir . $ImageName;

	move_uploaded_file($TmpName, $Folder);

	if ($Name != "" && $Price >= 0 && $Stock >= 0) {
		$Stmt = $Conn->prepare("INSERT INTO products(name, price, stock, category_id, image) VALUES (?, ?, ?, ?, ?)");
		$Stmt->bind_param("sdiis", $Name, $Price, $Stock, $CategoryId, $ImageName);
		$Stmt->execute();
		$Stmt->close();
	}

	header("Location: ThemSanPham.php");
	exit();
}

// SỬA

if (isset($_POST['edit'])) {
	$Id = (int)$_POST['id'];
	$Name = trim($_POST['name']);
	$CategoryId = (int)$_POST['category_id'];
	$Price = (float)$_POST['price'];
	$Stock = (int)$_POST['stock'];

	$Stmt = $Conn->prepare("UPDATE products SET name=?, category_id=?, price=?, stock=? WHERE id=?");
	$Stmt->bind_param("sidii", $Name, $CategoryId, $Price, $Stock, $Id);
	$Stmt->execute();
	$Stmt->close();

	header("Location: ThemSanPham.php");
	exit();
}

if ($action == "delete" && isset($_GET['id'])) {
	$Id = (int)$_GET['id'];

	if ($Id > 0) {
		$Stmt = $Conn->prepare("DELETE FROM products WHERE id=?");
		$Stmt->bind_param("i", $Id);
		$Stmt->execute();
		$Stmt->close();
	}

	header("Location: ThemSanPham.php?msg=deleted");
	exit();
}
?>
<section class="BH_khung_chinh">
	<div class="QLSP_container">

		<?php if ($action == "add") { ?>

		<!-- ================= THÊM ================= -->
		<div class="QLSP_card">
			<h2>Thêm sản phẩm</h2>

			<form method="POST" enctype="multipart/form-data">

				<input class="QLSP_input" type="text" name="name" placeholder="Tên sản phẩm" required>

				<select name="category_id" class="QLSP_input">
					<?php
			$Cate = $Conn->query("SELECT * FROM categories");
			while ($C = $Cate->fetch_assoc()) {
			?>
					<option value="<?= $C['id']; ?>"><?= $C['name']; ?></option>
					<?php } ?>
				</select>

				<input class="QLSP_input" type="number" name="price" placeholder="Giá" min="0">
				<input class="QLSP_input" type="number" name="stock" placeholder="Tồn kho" min="0">

				<input class="QLSP_input" type="file" name="image" accept="image/*" required>

				<button class="QLSP_btn QLSP_btn_add" name="add">Thêm</button>
				<a href="ThemSanPham.php" class="QLSP_btn">Hủy</a>

			</form>
		</div>

		<?php } elseif ($action == "edit") {

		$Id = (int)$_GET['id'];
		$Result = $Conn->query("SELECT * FROM products WHERE id=$Id");
		$Row = $Result->fetch_assoc();
	?>

		<!-- ================= SỬA ================= -->
		<div class="QLSP_card">
			<h2>Sửa sản phẩm</h2>

			<form method="POST">
				<input type="hidden" name="id" value="<?= $Row['id']; ?>">

				<input class="QLSP_input" type="text" name="name" value="<?= $Row['name']; ?>">

				<select name="category_id" class="QLSP_input">
					<?php
				$Cate = $Conn->query("SELECT * FROM categories");
				while ($C = $Cate->fetch_assoc()) {
				?>
					<option value="<?= $C['id']; ?>" <?= ($C['id'] == $Row['category_id']) ? "selected" : ""; ?>>
						<?= $C['name']; ?>
					</option>
					<?php } ?>
				</select>

				<input class="QLSP_input" type="number" name="price" value="<?= $Row['price']; ?>">
				<input class="QLSP_input" type="number" name="stock" value="<?= $Row['stock']; ?>">

				<button class="QLSP_btn QLSP_btn_add" name="edit">Cập nhật</button>
				<a href="ThemSanPham.php" class="QLSP_btn">Hủy</a>
			</form>
		</div>

		<?php } else {

		$Result = $Conn->query("
			SELECT p.*, c.name AS category_name
			FROM products p
			JOIN categories c ON p.category_id = c.id
		");
	?>

		<!-- ================= DANH SÁCH ================= -->
		<div class="QLSP_card">
			<h2>Quản lý sản phẩm</h2>
			<a href="ThemSanPham.php?action=add" class="QLSP_btn QLSP_btn_add">+ Thêm sản phẩm</a>
		</div>

		<table class="QLSP_table">
			<tr>
				<th>Tên</th>
				<th>Danh mục</th>
				<th>Giá</th>
				<th>Tồn kho</th>
				<th>Thao tác</th>
			</tr>

			<?php while ($Row = $Result->fetch_assoc()) { ?>
			<tr>
				<td><?= $Row['name']; ?></td>
				<td><?= $Row['category_name']; ?></td>
				<td><?= number_format($Row['price']); ?> đ</td>
				<td><?= $Row['stock']; ?></td>
				<td>
					<a class="QLSP_btn_edit" href="ThemSanPham.php?action=edit&id=<?= $Row['id']; ?>">Sửa</a>

					<a href="ThemSanPham.php?action=delete&id=<?= $Row['id']; ?>" class="QLSP_btn_delete"
						onclick="return confirm('Bạn có chắc muốn xóa không?');">
						Xóa
					</a>
				</td>
			</tr>
			<?php } ?>
		</table>

		<?php } ?>

	</div>
</section>