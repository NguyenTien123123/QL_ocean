<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "ql_ocean4";

try {
    // Kết nối MySQL bằng PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Kết nối thành công"; // Bỏ comment dòng này để kiểm tra kết nối
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>

