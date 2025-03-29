<?php
include 'db_connect.php';

// Kiểm tra nếu có dữ liệu từ form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten = $_POST['Ten'];
    $email = $_POST['Email'];
    $sdt = $_POST['SDT'];
    $diachi = $_POST['DiaChi'];
    $ngaysinh = $_POST['NgaySinh'];
    $gioitinh = $_POST['GioiTinh'];
    $ghichu = $_POST['GhiChu'];

    // Kiểm tra dữ liệu đầu vào
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $ngaysinh)) {
        echo "Ngày sinh không hợp lệ!";
        exit();
    }

    // Cập nhật vào bảng khách hàng
    $query = "
        INSERT INTO khachhang (Ten, Email, SDT, DiaChi, NgaySinh, GioiTinh, GhiChu)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$ten, $email, $sdt, $diachi, $ngaysinh, $gioitinh, $ghichu]);

    // Chuyển hướng về trang quản lý khách hàng và hiển thị thông báo thành công
    header("Location: quanly_khachhang.php?success=1");
    exit;
}
?>