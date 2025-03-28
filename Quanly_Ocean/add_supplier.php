<?php
include 'db_connect.php';

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin nhà cung cấp từ form
    $tenNCC = $_POST['TenNCC'];
    $diaChi = $_POST['DiaChi'];
    $sdt = $_POST['SDT'];
    $email = $_POST['Email'];
    $website = $_POST['Website'];

    // Thực hiện truy vấn để thêm nhà cung cấp vào cơ sở dữ liệu
    $query = "INSERT INTO Nhacungcap (TenNCC, DiaChi, SDT, Email, Website) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$tenNCC, $diaChi, $sdt, $email, $website]);

    // Nếu thêm nhà cung cấp thành công, chuyển hướng về trang quản lý nhà cung cấp và thêm tham số 'added=true'
    header("Location: quanly_nhacungcap.php?added=true#quanly_nhacungcap");
    exit();
}
?>