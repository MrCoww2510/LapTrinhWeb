<?php
session_start();
include("config.php");

// Kiểm tra đăng nhập 
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Xử lý thoát hệ thống 
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý hệ thống bán hàng</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }       
        body { font-family: Arial, sans-serif; }
        .Header { 
            position: relative; 
            line-height: 0; 
        }
        .Header img.MainBanner { width: 100%; height: auto; }

        .Menu {
            background-color: #e5e5e5;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            display: flex;
            justify-content: space-between; 
            align-items: center;
            padding: 0 5px;
        }

        .Menu_List {
            display: flex;
            list-style: none;
        }

        .Menu_List li { position: relative; }

        .Menu_List li a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }

        

        /* Hover  */
        .Dropdown_Content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 150px;
            border: 1px solid #ccc;
            z-index: 100;
        }
        .Dropdown:hover .Dropdown_Content { display: block; }
        .Dropdown_Content li a:hover { 
            background-color: #008000 !important; 
            color: #fff !important; 
        }

        .User_Info {
            font-size: 14px;
            padding-right: 15px;
        }
        .Logout_Link { color: red; text-decoration: none; }

        .Content {
            margin: 15px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 15px;
            min-height: 400px;
        }

        .Footer {
            background-color: #008000;
            color: white;
            text-align: center;
            padding: 10px;
            margin: 0 15px 15px 15px;
            border-radius: 30px;
        }
    </style>
</head>
<body>

    <div class="Header">
        <img src="banner/co2.png" class="Co">
        <img src="banner/co2.png" class="Co2">
        <img src="banner/bang.png" class="Bang">
        <h1 class="Header_Title">QUẢN LÝ HỆ THỐNG BÁN HÀNG</h1>
    </div>

    <div class="Menu">
        <ul class="Menu_List">
            <li><a href="admin.php">Trang chủ</a></li>
            <li class="Dropdown">
                <a href="#" class="nav-them">Thêm</a>
                <ul class="Dropdown_Content">
                    <li><a href="admin.php?page=themsp">Thêm sản phẩm</a></li>
                    <li><a href="admin.php?page=themnhom">Thêm nhóm</a></li>
                </ul>
            </li>
            <li><a href="admin.php?page=dssp">Cập nhật sản phẩm</a></li>
            <li><a href="admin.php?page=dsnhom">Cập nhật nhóm</a></li>
        </ul>

        <div class="User_Info">
            Xin chào: <b><?php echo $_SESSION['username']; ?></b> | 
            <a href="admin.php?action=logout" class="Logout_Link">Thoát</a>
        </div>
    </div>

    <div class="Content">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        switch ($page) {
            case 'themnhom':
                if (file_exists('themnhom.php')) include 'themnhom.php';
                break;
            default:
                echo "Nội dung thay đổi theo click";
                break;
        }
        ?>
    </div>

    <div class="Footer">
        Thực hiện: ............
    </div>

</body>
</html>