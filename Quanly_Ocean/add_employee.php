<?php
include 'db_connect.php';

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin nhân viên từ form
    $ten = $_POST['Ten'];
    $email = $_POST['Email'];
    $sdt = $_POST['SDT'];
    $chucVu = $_POST['ChucVu'];

    // Thực hiện truy vấn để thêm nhân viên vào cơ sở dữ liệu
    $query = "INSERT INTO nhanvien (Ten, Email, SDT, ChucVu) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$ten, $email, $sdt, $chucVu]);

    // Nếu thêm nhân viên thành công, chuyển hướng về trang quản lý nhân viên và thêm tham số 'added=true'
    header("Location: quanly_nhanvien.php?added=true#");
    exit();
}
?>