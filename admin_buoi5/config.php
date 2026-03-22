<?php if (session_status() == PHP_SESSION_NONE) {session_start();}
$Host = "localhost";      
$User = "root";           
$Password = "";          
$Database = "banhang";   
$Conn = new mysqli($Host, $User, $Password, $Database);
if ($Conn->connect_error) {
	die("Kết nối database thất bại: " . $Conn->connect_error);
}
$Conn->set_charset("utf8");
?>