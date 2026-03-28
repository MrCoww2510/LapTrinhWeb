<?php
require_once("config.php");

$action = isset($_GET['action']) ? $_GET['action'] : "";

// ===== THÊM =====
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $Conn->query("INSERT INTO products(name, price, stock, category_id)
                  VALUES ('$name','$price','$stock','$category_id')");

    header("Location: ThemSanPham.php");
    exit();
}

// ===== SỬA =====
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $Conn->query("UPDATE products SET 
        name='$name',
        category_id='$category_id',
        price='$price',
        stock='$stock'
        WHERE id=$id");

    header("Location: ThemSanPham.php");
    exit();
}

// ===== XÓA =====
if ($action == "delete") {
    $id = $_GET['id'];
    $Conn->query("DELETE FROM products WHERE id=$id");

    header("Location: ThemSanPham.php");
    exit();
}
?>

<div class="qlsp-container">

<?php
// ===== FORM THÊM =====
if ($action == "add") {
?>
    <div class="qlsp-card">
        <h2>Thêm sản phẩm</h2>

        <form method="POST">
            <input class="qlsp-input" type="text" name="name" placeholder="Tên sản phẩm" required>
            <select name="category_id" class="qlsp-input">
                <?php
                $cate = $Conn->query("SELECT * FROM categories");
                while($c = $cate->fetch_assoc()){
                ?>
                    <option value="<?php echo $c['id']; ?>">
                        <?php echo $c['name']; ?>
                    </option>
                <?php } ?>
            </select>

            <input class="qlsp-input" type="number" name="price" placeholder="Giá">
            <input class="qlsp-input" type="number" name="stock" placeholder="Tồn kho">

            <button class="qlsp-btn qlsp-btn-add" name="add">Thêm</button>
            <a href="ThemSanPham.php" class="qlsp-btn">Hủy</a>
        </form>
    </div>

<?php
// ===== FORM SỬA =====
} elseif ($action == "edit") {
    $id = $_GET['id'];

    $result = $Conn->query("
        SELECT p.*, c.name AS category_name
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE p.id=$id
    ");
    $row = $result->fetch_assoc();
?>
    <div class="qlsp-card">
        <h2>Sửa sản phẩm</h2>

        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <input class="qlsp-input" type="text" name="name" value="<?php echo $row['name']; ?>">
            <select name="category_id" class="qlsp-input">
                <?php
                $cate = $Conn->query("SELECT * FROM categories");
                while($c = $cate->fetch_assoc()){
                ?>
                    <option value="<?php echo $c['id']; ?>"
                        <?php if($c['id'] == $row['category_id']) echo "selected"; ?>>
                        <?php echo $c['name']; ?>
                    </option>
                <?php } ?>
            </select>

            <input class="qlsp-input" type="number" name="price" value="<?php echo $row['price']; ?>">
            <input class="qlsp-input" type="number" name="stock" value="<?php echo $row['stock']; ?>">

            <button class="qlsp-btn qlsp-btn-add" name="edit">Cập nhật</button>
            <a href="ThemSanPham.php" class="qlsp-btn">Hủy</a>
        </form>
    </div>

<?php
// ===== DANH SÁCH =====
} else {
    $result = $Conn->query("
        SELECT p.*, c.name AS category_name
        FROM products p
        JOIN categories c ON p.category_id = c.id
    ");
?>

    <div class="qlsp-card">
        <h2>Quản lý sản phẩm</h2>
        <a href="ThemSanPham.php?action=add" class="qlsp-btn qlsp-btn-add">
            + Thêm sản phẩm
        </a>
    </div>

    <table class="qlsp-table">
        <tr>
            <th>Tên</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Tồn kho</th>
            <th>Thao tác</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['category_name']; ?></td>
            <td><?php echo number_format($row['price']); ?> đ</td>
            <td><?php echo $row['stock']; ?></td>
            <td>
                <a class="qlsp-btn-edit"
                   href="ThemSanPham.php?action=edit&id=<?php echo $row['id']; ?>">✏️</a>

                <a class="qlsp-btn-delete"
                   href="ThemSanPham.php?action=delete&id=<?php echo $row['id']; ?>"
                   onclick="return confirm('Xóa sản phẩm này?')">🗑</a>
            </td>
        </tr>
        <?php } ?>
    </table>

<?php } ?>

</div>