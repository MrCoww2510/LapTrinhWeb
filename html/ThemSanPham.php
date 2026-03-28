<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
require_once("config.php");

// ================= CHẶN USER =================
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
	header("Location: index.php");
	exit();
}

$action = $_GET['action'] ?? "";

// ================= XÓA =================
if ($action == "delete") {

	$Id = (int)$_GET['id'];

	$Conn->query("DELETE FROM thongso WHERE product_id = $Id");
	$Conn->query("DELETE FROM products WHERE id = $Id");

	header("Location: ThemSanPham.php");
	exit();
}

// ================= THÊM =================
if (isset($_POST['add'])) {

	$Name = trim($_POST['name']);
	$CategoryId = (int)$_POST['category_id'];
	$Price = (float)$_POST['price'];
	$Stock = (int)$_POST['stock'];

	// thông số
	$Cpu = $_POST['cpu'] ?? "";
	$Ram = $_POST['ram'] ?? "";
	$Vga = $_POST['vga'] ?? "";
	$Storage = $_POST['storage'] ?? "";
	$Screen = $_POST['screen'] ?? "";
	$Battery = $_POST['battery'] ?? "";
	$Weight = $_POST['weight'] ?? "";

	// ảnh
	$ImageName = "";
	if (!empty($_FILES['image']['name'])) {
		$ImageName = time() . "_" . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], "SanPham/" . $ImageName);
	}

	if ($Name != "" && $Price >= 0 && $Stock >= 0) {

		$Stmt = $Conn->prepare("INSERT INTO products(name, price, stock, category_id, image) VALUES (?, ?, ?, ?, ?)");
		$Stmt->bind_param("sdiis", $Name, $Price, $Stock, $CategoryId, $ImageName);
		$Stmt->execute();

		$ProductId = $Stmt->insert_id;
		$Stmt->close();

		$Stmt2 = $Conn->prepare("
			INSERT INTO thongso(product_id, cpu, ram, vga, storage, screen, battery, weight)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?)
		");

		$Stmt2->bind_param("isssssss", $ProductId, $Cpu, $Ram, $Vga, $Storage, $Screen, $Battery, $Weight);
		$Stmt2->execute();
		$Stmt2->close();
	}

	header("Location: ThemSanPham.php");
	exit();
}

// ================= SỬA =================
if (isset($_POST['edit'])) {

	$Id = (int)$_POST['id'];
	$Name = trim($_POST['name']);
	$CategoryId = (int)$_POST['category_id'];
	$Price = (float)$_POST['price'];
	$Stock = (int)$_POST['stock'];

	$Cpu = $_POST['cpu'] ?? "";
	$Ram = $_POST['ram'] ?? "";
	$Vga = $_POST['vga'] ?? "";
	$Storage = $_POST['storage'] ?? "";
	$Screen = $_POST['screen'] ?? "";
	$Battery = $_POST['battery'] ?? "";
	$Weight = $_POST['weight'] ?? "";

	// update products
	$Stmt = $Conn->prepare("UPDATE products SET name=?, category_id=?, price=?, stock=? WHERE id=?");
	$Stmt->bind_param("sidii", $Name, $CategoryId, $Price, $Stock, $Id);
	$Stmt->execute();
	$Stmt->close();

	// update thông số
	$Stmt2 = $Conn->prepare("
		UPDATE thongso
		SET cpu=?, ram=?, vga=?, storage=?, screen=?, battery=?, weight=?
		WHERE product_id=?
	");
	$Stmt2->bind_param("sssssssi", $Cpu, $Ram, $Vga, $Storage, $Screen, $Battery, $Weight, $Id);
	$Stmt2->execute();

	// nếu chưa có thì insert
	if ($Stmt2->affected_rows == 0) {
		$Stmt3 = $Conn->prepare("
			INSERT INTO thongso(product_id, cpu, ram, vga, storage, screen, battery, weight)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?)
		");
		$Stmt3->bind_param("isssssss", $Id, $Cpu, $Ram, $Vga, $Storage, $Screen, $Battery, $Weight);
		$Stmt3->execute();
		$Stmt3->close();
	}
	$Stmt2->close();

	header("Location: ThemSanPham.php");
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

				<input class="QLSP_input" type="number" name="price" placeholder="Giá">
				<input class="QLSP_input" type="number" name="stock" placeholder="Tồn kho">

				<input class="QLSP_input" type="file" name="image" accept="image/*">

				<input class="QLSP_input" name="cpu" placeholder="CPU">
				<input class="QLSP_input" name="ram" placeholder="RAM">
				<input class="QLSP_input" name="vga" placeholder="VGA">
				<input class="QLSP_input" name="storage" placeholder="Ổ cứng">
				<input class="QLSP_input" name="screen" placeholder="Màn hình">
				<input class="QLSP_input" name="battery" placeholder="Pin">
				<input class="QLSP_input" name="weight" placeholder="Cân nặng">

				<button class="QLSP_btn QLSP_btn_add" name="add">Thêm</button>
				<a href="ThemSanPham.php" class="QLSP_btn">Hủy</a>

			</form>
		</div>

		<?php } elseif ($action == "edit") {

$Id = (int)$_GET['id'];
$Result = $Conn->query("
	SELECT p.*, t.*
	FROM products p
	LEFT JOIN thongso t ON p.id = t.product_id
	WHERE p.id = $Id
");
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

				<input class="QLSP_input" name="cpu" value="<?= $Row['cpu']; ?>">
				<input class="QLSP_input" name="ram" value="<?= $Row['ram']; ?>">
				<input class="QLSP_input" name="vga" value="<?= $Row['vga']; ?>">
				<input class="QLSP_input" name="storage" value="<?= $Row['storage']; ?>">
				<input class="QLSP_input" name="screen" value="<?= $Row['screen']; ?>">
				<input class="QLSP_input" name="battery" value="<?= $Row['battery']; ?>">
				<input class="QLSP_input" name="weight" value="<?= $Row['weight']; ?>">

				<button class="QLSP_btn" name="edit">Cập nhật</button>
				<a href="ThemSanPham.php" class="QLSP_btn">Hủy</a>
			</form>
		</div>

		<?php } else {

$Result = $Conn->query("
	SELECT p.*, c.name AS category_name
	FROM products p
	LEFT JOIN categories c ON p.category_id = c.id
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

					<a href="ThemSanPham.php?action=delete&id=<?= $Row['id']; ?>"
						onclick="return confirm('Xóa thật không?');" class="QLSP_btn_delete">
						Xóa
					</a>
				</td>
			</tr>
			<?php } ?>

		</table>

		<?php } ?>

	</div>
</section>