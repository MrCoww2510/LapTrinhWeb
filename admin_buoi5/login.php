<?php
session_start();
include("admin_funs.php"); 

$error = '';
if (isset($_POST['btnLogin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // CheckLogin 
    $user = checkLogin($username, $password); 
    
    if ($user) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['loaitk'] = $user['loaitk'];
        
        // Điều hướng (1: admin, 0: user)
        if ($user['loaitk'] == 1) {
            header("Location: admin.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    } else {
        $error = "Sai Username hoặc Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống</title>
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        /* CSS ĐĂNG NHẬP */
        .login-content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 400px; 
            background-color: #ffffff;
        }

        .login-wrapper {
            position: relative;
            width: 380px;
            margin-top: 30px;
        }

        .login-tab {
            position: absolute;
            top: -28px;
            left: 15px;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-bottom: none;
            padding: 5px 15px;
            font-size: 14px;
            font-weight: bold;
            color: #333333;
            box-shadow: 0 -2px 4px rgba(0,0,0,0.05);
            z-index: 10;
        }

        .login-box {
            background-color: #f2f2f2; 
            border: 1px solid #cccccc;
            border-radius: 4px;
            position: relative;
            z-index: 5;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .login-body {
            padding: 30px 20px 20px 20px;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            width: 100px;
            font-weight: bold;
            font-size: 14px;
            color: #333333;
        }

        .form-group input {
            flex: 1;
            padding: 6px;
            border: 1px solid #cccccc;
            border-radius: 2px;
        }

        .login-footer {
            background-color: #e2e6e9; 
            border-top: 1px solid #cccccc;
            padding: 10px 15px;
            text-align: right;
            border-radius: 0 0 4px 4px;
        }

        .btn-go {
            background-color: #f8f8f8;
            border: 1px solid #cccccc;
            padding: 5px 15px;
            font-weight: bold;
            font-size: 13px;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .btn-go:hover {
            background-color: #e8e8e8;
        }

        .error-msg {
            color: red;
            font-size: 13px;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="Header">
        <img src="banner/co2.png" class="Co" alt="Lá trái">
        
        <img src="banner/co2.png" class="Co2" alt="Lá dưới">
        
        <img src="banner/bang.png" class="Bang" alt="Bảng gỗ">
        
        <h1 class="Header_Title">QUẢN LÝ HỆ THỐNG BÁN HÀNG</h1>
    </div>

    <div class="login-content">
        <div class="login-wrapper">
            <div class="login-tab">Log in</div>
            
            <form method="POST" action="" class="login-box">
                <div class="login-body">
                    <?php if ($error != ''): ?>
                        <div class="error-msg"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" name="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" name="password" required>
                    </div>
                </div>
                
                <div class="login-footer">
                    <button type="submit" name="btnLogin" class="btn-go">Go</button>
                </div>
            </form>
        </div>
    </div>

    <div class="Footer">
        Thực hiện: ............
    </div>

</body>
</html>