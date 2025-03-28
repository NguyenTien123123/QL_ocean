<?php
include 'db_connect.php';

// Thêm khách hàng mới khi form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten = $_POST['Ten'];
    $email = $_POST['Email'];
    $sdt = $_POST['SDT'];
    $diachi = $_POST['DiaChi'];
    $ngaysinh = $_POST['NgaySinh'];
    $gioitinh = $_POST['GioiTinh'];
    $ghichu = $_POST['GhiChu'];

    // Thêm vào bảng khách hàng
    $query = "
        INSERT INTO khachhang (Ten, Email, SDT, DiaChi) 
        VALUES (?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$ten, $email, $sdt, $diachi]);
    $khid = $conn->lastInsertId(); // Lấy ID của khách hàng vừa thêm

    // Thêm vào bảng chi tiết khách hàng
    $query = "
        INSERT INTO chitietkhachhang (KHID, NgaySinh, GioiTinh, GhiChu) 
        VALUES (?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$khid, $ngaysinh, $gioitinh, $ghichu]);

    // Chuyển hướng về trang quản lý khách hàng
    header("Location: quanly_khachhang.php");
    exit;
}
?>
